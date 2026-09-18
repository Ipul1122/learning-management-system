<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

#[Fillable([
    'user_id',
    'branch_id',
    'action',
    'target_entity',
    'target_id',
    'description',
    'properties_old',
    'properties_new',
    'ip_address',
    'user_agent',
])]
class ActivityLog extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'target_entity_label',
        'formatted_created_at',
        'formatted_ip_address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'properties_old' => 'array',
            'properties_new' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Label entitas target yang ramah pengguna.
     */
    public function getTargetEntityLabelAttribute(): string
    {
        if (! $this->target_entity) {
            return 'Sistem / Umum';
        }

        $map = [
            'App\Models\Branch' => 'Kantor Cabang',
            'App\Models\User' => 'Akun Pengguna',
            'App\Models\Quiz' => 'Kuis',
            'App\Models\Question' => 'Bank Soal',
            'App\Models\ClassSchedule' => 'Jadwal Kelas',
            'App\Models\ClassSession' => 'Sesi Kelas',
            'App\Models\Course' => 'Kursus / Materi',
            'App\Models\Enrollment' => 'Pendaftaran Kelas',
            'App\Models\GraduationReview' => 'Review Kelulusan',
            'App\Models\QuizAttempt' => 'Pengerjaan Kuis',
            'App\Models\Certificate' => 'Sertifikat',
            'App\Models\QuizQuestion' => 'Pertanyaan Kuis',
            'App\Models\BranchClass' => 'Kelas Cabang',
        ];

        return $map[$this->target_entity] ?? class_basename($this->target_entity);
    }

    /**
     * Format IP address dengan label ramah (misal localhost).
     */
    public function getFormattedIpAddressAttribute(): string
    {
        $ip = $this->ip_address ?? '127.0.0.1';

        if (in_array($ip, ['127.0.0.1', '::1'])) {
            return "{$ip} (Localhost / Server Internal)";
        }

        return $ip;
    }

    /**
     * Format waktu aktivitas yang ramah pengguna.
     */
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('d M Y, H:i:s') . ' WIB' : '-';
    }

    /**
     * Pengguna yang melakukan aktivitas.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cabang tempat aktivitas berlangsung.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Helper praktis untuk mencatat audit log secara otomatis.
     */
    public static function record(
        string $action,
        string $description,
        ?Model $target = null,
        ?array $old = null,
        ?array $new = null,
        ?int $userId = null,
        ?int $branchId = null
    ): self {
        /** @var User|null $user */
        $user = Auth::user();
        $actualUserId = $userId ?? $user?->id ?? 1;
        $actualBranchId = $branchId ?? $user?->branch_id;

        return self::create([
            'user_id' => $actualUserId,
            'branch_id' => $actualBranchId,
            'action' => strtoupper($action),
            'target_entity' => $target ? get_class($target) : null,
            'target_id' => $target ? $target->getKey() : null,
            'description' => $description,
            'properties_old' => $old,
            'properties_new' => $new,
            'ip_address' => Request::ip() ?? '127.0.0.1',
            'user_agent' => Request::userAgent() ?? 'System / Console',
        ]);
    }
}
