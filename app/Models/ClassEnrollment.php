<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'user_id',
        'attendance_mode',
        'accumulated_minutes',
        'accumulated_jp',
        'status',
        'enrolled_at',
    ];

    protected $casts = [
        'accumulated_minutes' => 'integer',
        'accumulated_jp' => 'float',
        'enrolled_at' => 'datetime',
    ];

    /**
     * Kelas pelatihan yang diikuti peserta.
     */
    public function trainingClass(): BelongsTo
    {
        return $this->belongsTo(TrainingClass::class, 'class_id');
    }

    /**
     * Pengguna / Peserta yang terdaftar.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Catatan presensi dan keikutsertaan per sesi.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(SessionAttendance::class, 'enrollment_id');
    }

    /**
     * Cek apakah syarat 20 JP (900 Menit) telah terpenuhi.
     */
    public function isCompletedJp(): bool
    {
        return $this->accumulated_jp >= 20.0 || $this->accumulated_minutes >= 900;
    }

    /**
     * Cek apakah mode kehadiran fisik (offline).
     */
    public function isAttendanceOffline(): bool
    {
        return $this->attendance_mode === 'offline';
    }

    /**
     * Cek apakah mode kehadiran daring (online).
     */
    public function isAttendanceOnline(): bool
    {
        return $this->attendance_mode === 'online';
    }

    /**
     * Persentase pemenuhan 20 JP (0% s/d 100%).
     */
    public function progressPercentage(): float
    {
        return min(100.0, round(($this->accumulated_minutes / 900) * 100, 1));
    }

    /**
     * Tambahkan akumulasi menit belajar dan sinkronkan JP (1 JP = 45 menit).
     */
    public function addMinutes(int $minutes): void
    {
        $this->accumulated_minutes += $minutes;
        $this->accumulated_jp = round($this->accumulated_minutes / 45, 1);

        // Jika telah memenuhi syarat 20 JP dan status belum review_pending / graduated
        if ($this->isCompletedJp() && in_array($this->status, ['enrolled', 'in_progress'])) {
            $this->status = 'review_pending';
        } elseif ($this->status === 'enrolled' && $this->accumulated_minutes > 0) {
            $this->status = 'in_progress';
        }

        $this->save();
    }
}
