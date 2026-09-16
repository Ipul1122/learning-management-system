<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'code', 'address', 'city', 'phone', 'is_active'])]
class Branch extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Seluruh pengguna yang terafiliasi dengan cabang ini.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Log aktivitas pada cabang ini.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Seluruh kelas pelatihan yang diselenggarakan cabang ini.
     */
    public function classes(): HasMany
    {
        return $this->hasMany(TrainingClass::class);
    }

    /**
     * Bank soal yang dimiliki cabang ini.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Seluruh berita atau pengumuman cabang ini.
     */
    public function newsPosts(): HasMany
    {
        return $this->hasMany(NewsPost::class);
    }
}
