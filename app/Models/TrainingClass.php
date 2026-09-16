<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'branch_id',
    'trainer_id',
    'title',
    'slug',
    'description',
    'type',
    'offline_capacity',
    'online_capacity',
    'enrolled_offline',
    'enrolled_online',
    'required_jp',
    'start_date',
    'end_date',
    'status',
])]
class TrainingClass extends Model
{
    use HasFactory;

    /**
     * Nama tabel eksplisit untuk menghindari kata kunci 'class' di PHP.
     */
    protected $table = 'classes';

    /**
     * Atribut casting.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'offline_capacity' => 'integer',
            'online_capacity' => 'integer',
            'enrolled_offline' => 'integer',
            'enrolled_online' => 'integer',
            'required_jp' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    /**
     * Kantor Cabang penyelenggara kelas.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Trainer / Instruktur penanggung jawab kelas.
     */
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    /**
     * Seluruh jadwal sesi pembelajaran pada kelas ini.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(ClassSession::class, 'class_id')->orderBy('session_order');
    }

    /**
     * Akumulasi total JP dari seluruh sesi yang telah dijadwalkan.
     */
    public function totalAccumulatedJp(): int
    {
        return (int) $this->sessions()->sum('jp_duration');
    }

    /**
     * Akumulasi total durasi menit belajar (JP * 45).
     */
    public function totalAccumulatedMinutes(): int
    {
        return (int) $this->sessions()->sum('minute_duration');
    }

    /**
     * Cek apakah kelas bertipe offline.
     */
    public function isOffline(): bool
    {
        return $this->type === 'offline';
    }

    /**
     * Cek apakah kelas bertipe online.
     */
    public function isOnline(): bool
    {
        return $this->type === 'online';
    }

    /**
     * Cek apakah kelas bertipe hybrid.
     */
    public function isHybrid(): bool
    {
        return $this->type === 'hybrid';
    }

    /**
     * Hitung sisa kursi fisik (offline).
     */
    public function remainingOfflineSeats(): int
    {
        return max(0, $this->offline_capacity - $this->enrolled_offline);
    }

    /**
     * Hitung sisa kuota daring (online).
     */
    public function remainingOnlineSeats(): int
    {
        return max(0, $this->online_capacity - $this->enrolled_online);
    }

    /**
     * Helper generate unique slug from title.
     */
    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    /**
     * Paket kuis pada kelas ini.
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'class_id');
    }

    /**
     * Data pendaftaran peserta pada kelas ini.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(ClassEnrollment::class, 'class_id');
    }

    /**
     * Siswa / Peserta yang terdaftar pada kelas ini.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_enrollments', 'class_id', 'user_id')
            ->withPivot(['id', 'attendance_mode', 'accumulated_minutes', 'accumulated_jp', 'status', 'enrolled_at'])
            ->withTimestamps();
    }

    /**
     * Cek apakah kuota fisik offline (maks 40) sudah penuh.
     */
    public function isFullOffline(): bool
    {
        return $this->enrolled_offline >= $this->offline_capacity;
    }

    /**
     * Cek apakah kuota daring online sudah penuh.
     */
    public function isFullOnline(): bool
    {
        return $this->enrolled_online >= $this->online_capacity;
    }

    /**
     * Cek apakah user telah terdaftar di kelas ini.
     */
    public function isUserEnrolled(?int $userId): bool
    {
        if (! $userId) {
            return false;
        }

        return $this->enrollments()->where('user_id', $userId)->exists();
    }

    /**
     * Seluruh thread diskusi forum pada kelas ini.
     */
    public function forumThreads(): HasMany
    {
        return $this->hasMany(ForumThread::class, 'class_id');
    }
}
