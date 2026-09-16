<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'author_id',
        'title',
        'content',
        'is_pinned',
        'is_locked',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
    ];

    /**
     * Kelas tempat diskusi ini diadakan.
     */
    public function trainingClass(): BelongsTo
    {
        return $this->belongsTo(TrainingClass::class, 'class_id');
    }

    /**
     * Pembuat / inisiator topik diskusi.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Seluruh balasan pada thread ini.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ForumReply::class, 'thread_id');
    }

    /**
     * Balasan tingkat utama (root replies) yang tidak membalas balasan lain.
     */
    public function rootReplies(): HasMany
    {
        return $this->hasMany(ForumReply::class, 'thread_id')
            ->whereNull('parent_reply_id')
            ->with(['author', 'children.author'])
            ->oldest();
    }

    /**
     * Scope untuk mengutamakan thread yang disematkan (pinned) di bagian atas.
     */
    public function scopePinnedFirst(Builder $query): Builder
    {
        return $query->orderByDesc('is_pinned')->latest();
    }

    /**
     * Cek apakah user tertentu adalah penulis thread.
     */
    public function isAuthor(User $user): bool
    {
        return $this->author_id === $user->id;
    }

    /**
     * Cek apakah user memiliki wewenang moderasi pada thread ini.
     */
    public function canModerate(User $user): bool
    {
        if ($user->hasRole(['super-admin', 'admin-cabang'])) {
            return true;
        }

        if ($user->hasRole('trainer') && $this->trainingClass?->trainer_id === $user->id) {
            return true;
        }

        return false;
    }
}
