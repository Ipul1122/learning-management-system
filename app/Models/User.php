<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'branch_id', 'phone_number', 'avatar', 'status', 'email_verified_at', 'total_points', 'level'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke Cabang pengguna (Nullable untuk Super Admin).
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Relasi ke Log Aktivitas yang dilakukan pengguna.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Seluruh kelas pelatihan yang diampu oleh instruktur/trainer ini.
     */
    public function assignedClasses(): HasMany
    {
        return $this->hasMany(TrainingClass::class, 'trainer_id');
    }

    /**
     * Helper cek peran super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Helper cek peran admin cabang
     */
    public function isAdminCabang(): bool
    {
        return $this->hasRole('admin-cabang');
    }

    /**
     * Helper cek peran trainer
     */
    public function isTrainer(): bool
    {
        return $this->hasRole('trainer');
    }

    /**
     * Helper cek peran peserta
     */
    public function isPeserta(): bool
    {
        return $this->hasRole('peserta');
    }

    /**
     * Scope query untuk Super Admin
     *
     * @param  Builder<User>  $query
     */
    public function scopeSuperAdmins(Builder $query): Builder
    {
        return $query->role('super-admin');
    }

    /**
     * Scope query untuk Admin Cabang
     *
     * @param  Builder<User>  $query
     */
    public function scopeAdminCabangs(Builder $query): Builder
    {
        return $query->role('admin-cabang');
    }

    /**
     * Scope query untuk Trainer
     *
     * @param  Builder<User>  $query
     */
    public function scopeTrainers(Builder $query): Builder
    {
        return $query->role('trainer');
    }

    /**
     * Scope query untuk Peserta
     *
     * @param  Builder<User>  $query
     */
    public function scopePesertas(Builder $query): Builder
    {
        return $query->role('peserta');
    }

    /**
     * Butir soal yang disusun oleh pengguna ini.
     */
    public function createdQuestions(): HasMany
    {
        return $this->hasMany(Question::class, 'creator_id');
    }

    /**
     * Paket kuis yang dirancang oleh pengguna ini.
     */
    public function createdQuizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'creator_id');
    }

    /**
     * Riwayat pendaftaran kelas peserta.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(ClassEnrollment::class, 'user_id');
    }

    /**
     * Kelas-kelas yang sedang atau pernah diikuti oleh peserta ini.
     */
    public function enrolledClasses(): BelongsToMany
    {
        return $this->belongsToMany(TrainingClass::class, 'class_enrollments', 'user_id', 'class_id')
            ->withPivot(['id', 'attendance_mode', 'accumulated_minutes', 'accumulated_jp', 'status', 'enrolled_at'])
            ->withTimestamps();
    }

    /**
     * Seluruh percobaan pengerjaan kuis oleh pengguna ini.
     */
    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class, 'user_id');
    }

    /**
     * Pengajuan kelulusan yang ditinjau oleh Trainer ini.
     */
    public function reviewedGraduations(): HasMany
    {
        return $this->hasMany(GraduationSubmission::class, 'trainer_id');
    }

    /**
     * Berita dan artikel yang ditulis oleh pengguna ini.
     */
    public function newsPosts(): HasMany
    {
        return $this->hasMany(NewsPost::class, 'author_id');
    }

    /**
     * Thread forum diskusi yang diinisiasi oleh pengguna ini.
     */
    public function forumThreads(): HasMany
    {
        return $this->hasMany(ForumThread::class, 'author_id');
    }

    /**
     * Balasan forum yang ditulis oleh pengguna ini.
     */
    public function forumReplies(): HasMany
    {
        return $this->hasMany(ForumReply::class, 'author_id');
    }

    /**
     * Lencana prestasi yang telah diraih pengguna.
     */
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('earned_at');
    }

    /**
     * Riwayat transaksi perolehan poin XP.
     */
    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class, 'user_id');
    }

    /**
     * Gelar tingkat/level pengguna.
     */
    public function getRankTitleAttribute(): string
    {
        $lvl = $this->level ?? 1;

        return match (true) {
            $lvl >= 5 => 'Master Kejuruan (Grandmaster)',
            $lvl === 4 => 'Cendekia (Expert)',
            $lvl === 3 => 'Pejuang Belajar (Warrior)',
            $lvl === 2 => 'Penjelajah (Explorer)',
            default => 'Pemula (Novice)',
        };
    }

    /**
     * Target poin untuk naik ke level berikutnya.
     */
    public function getNextLevelThresholdAttribute(): int
    {
        return ($this->level ?? 1) * 200;
    }

    /**
     * Persentase progres poin pada level saat ini.
     */
    public function getLevelProgressPercentageAttribute(): float
    {
        $lvl = $this->level ?? 1;
        $currentLvlStart = ($lvl - 1) * 200;
        $currentLvlPoints = max(0, ($this->total_points ?? 0) - $currentLvlStart);

        return min(100.0, round(($currentLvlPoints / 200) * 100, 1));
    }
}
