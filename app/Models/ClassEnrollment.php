<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        return $this->accumulated_minutes >= 900;
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
     * Pengajuan kelulusan & verifikasi 20 JP untuk sertifikat.
     */
    public function graduationSubmission(): HasOne
    {
        return $this->hasOne(GraduationSubmission::class, 'enrollment_id');
    }

    /**
     * Tambahkan akumulasi menit belajar dan sinkronkan JP (1 JP = 45 menit).
     */
    public function addMinutes(int $minutes): void
    {
        $this->accumulated_minutes += $minutes;
        $this->accumulated_jp = floor(($this->accumulated_minutes / 45) * 10) / 10;

        if ($this->status === 'enrolled' && $this->accumulated_minutes > 0) {
            $this->status = 'in_progress';
        }

        $this->save();

        // Otomatis cek dan ajukan review jika telah mencapai target 20 JP (900 menit)
        if ($this->isCompletedJp()) {
            $this->checkAndSubmitGraduation();
        }
    }

    /**
     * Accessor untuk total_minutes_accumulated.
     */
    public function getTotalMinutesAccumulatedAttribute(): int
    {
        return $this->accumulated_minutes ?? 0;
    }

    /**
     * Accessor untuk total_jp_accumulated.
     */
    public function getTotalJpAccumulatedAttribute(): float
    {
        return $this->accumulated_jp ?? 0.0;
    }

    /**
     * Periksa dan ajukan kelulusan secara otomatis jika syarat 20 JP telah terpenuhi.
     */
    public function checkAndSubmitGraduation(): ?GraduationSubmission
    {
        if (! $this->isCompletedJp()) {
            return null;
        }

        $submission = GraduationSubmission::where('enrollment_id', $this->id)->first();

        // Hitung rata-rata nilai kuis pada kelas ini
        $classQuizIds = $this->trainingClass->quizzes()->pluck('id');
        $avgScore = 0.00;

        if ($classQuizIds->isNotEmpty()) {
            $avgScore = (float) QuizAttempt::whereIn('quiz_id', $classQuizIds)
                ->where('user_id', $this->user_id)
                ->whereNotNull('submitted_at')
                ->avg('total_score') ?? 0.00;
        }

        $trainerId = $this->trainingClass?->trainer_id;

        if (! $submission) {
            $submission = GraduationSubmission::create([
                'enrollment_id' => $this->id,
                'trainer_id' => $trainerId,
                'total_jp_earned' => $this->accumulated_jp,
                'avg_quiz_score' => round($avgScore, 2),
                'status' => 'pending',
            ]);

            $this->update(['status' => 'review_pending']);
        } elseif ($submission->status === 'rejected') {
            // Remedial update
            $submission->update([
                'total_jp_earned' => $this->accumulated_jp,
                'avg_quiz_score' => round($avgScore, 2),
                'status' => 'pending',
                'trainer_feedback' => null,
                'reviewed_at' => null,
            ]);

            $this->update(['status' => 'review_pending']);
        }

        return $submission;
    }
}
