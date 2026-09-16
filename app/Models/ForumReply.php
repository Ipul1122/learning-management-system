<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'thread_id',
        'author_id',
        'parent_reply_id',
        'reply_content',
    ];

    /**
     * Topik diskusi induk tempat balasan ini berada.
     */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(ForumThread::class, 'thread_id');
    }

    /**
     * Penulis balasan.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Balasan induk jika balasan ini adalah respon bersarang (nested reply).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ForumReply::class, 'parent_reply_id');
    }

    /**
     * Anak-anak balasan bersarang di bawah balasan ini.
     */
    public function children(): HasMany
    {
        return $this->hasMany(ForumReply::class, 'parent_reply_id')->with(['author', 'children.author'])->oldest();
    }

    /**
     * Cek apakah user tertentu adalah penulis balasan.
     */
    public function isAuthor(User $user): bool
    {
        return $this->author_id === $user->id;
    }

    /**
     * Cek apakah ini adalah balasan bersarang (child reply).
     */
    public function isNested(): bool
    {
        return ! is_null($this->parent_reply_id);
    }
}
