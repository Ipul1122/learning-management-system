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
