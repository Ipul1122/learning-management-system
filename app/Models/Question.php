<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'creator_id',
        'question_text',
        'question_type',
        'score_weight',
        'explanation',
    ];

    protected $casts = [
        'score_weight' => 'integer',
    ];

    /**
     * Cabang pemilik bank soal.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Pengguna (Trainer / Admin Cabang) yang menyusun soal.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Daftar opsi pilihan jawaban (untuk PG atau Benar/Salah).
     */
    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class)->orderBy('option_order');
    }

    /**
     * Paket kuis yang mengikutsertakan butir soal ini.
     */
    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class, 'quiz_questions')
            ->withPivot('order_number')
            ->withTimestamps();
    }

    public function isMultipleChoice(): bool
    {
        return $this->question_type === 'multiple_choice';
    }

    public function isTrueFalse(): bool
    {
        return $this->question_type === 'true_false';
    }

    public function isEssay(): bool
    {
        return $this->question_type === 'essay';
    }

    public function correctOption(): ?QuestionOption
    {
        return $this->options->firstWhere('is_correct', true);
    }
}
