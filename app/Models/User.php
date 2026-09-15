<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'branch_id', 'phone_number', 'avatar', 'status'])]
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
}
