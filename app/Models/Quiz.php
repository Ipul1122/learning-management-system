<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'creator_id',
        'title',
        'description',
        'time_limit_minutes',
        'passing_grade',
        'is_randomized',
        'max_attempts',
    ];

    protected $casts = [
        'time_limit_minutes' => 'integer',
        'passing_grade' => 'float',
        'is_randomized' => 'boolean',
        'max_attempts' => 'integer',
    ];

    /**
     * Kelas tempat kuis ini diselenggarakan.
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(TrainingClass::class, 'class_id');
    }

    /**
     * Pembuat / instruktur perancang kuis.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Butir-butir soal yang diikutsertakan dalam paket kuis ini.
     */
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'quiz_questions')
            ->withPivot('order_number')
            ->withTimestamps()
            ->orderByPivot('order_number');
    }

    /**
     * Seluruh riwayat percobaan pengerjaan kuis oleh peserta.
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Total jumlah soal dalam kuis.
     */
    public function totalQuestions(): int
    {
        return $this->questions()->count();
    }

    /**
     * Akumulasi total skor maksimal (jumlah bobot semua soal).
     */
    public function totalScoreWeight(): int
    {
        return $this->questions()->sum('score_weight');
    }
}
