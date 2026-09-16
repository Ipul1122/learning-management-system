<?php

namespace App\Http\Resources\Peserta;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizAttemptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quiz_id' => $this->quiz_id,
            'quiz' => $this->whenLoaded('quiz', function () {
                return [
                    'id' => $this->quiz->id,
                    'title' => $this->quiz->title,
                    'time_limit_minutes' => $this->quiz->time_limit_minutes,
                    'passing_grade' => (float) $this->quiz->passing_grade,
                    'max_attempts' => $this->quiz->max_attempts,
                ];
            }),
            'attempt_number' => $this->attempt_number,
            'total_score' => (float) $this->total_score,
            'is_passed' => (bool) $this->is_passed,
            'started_at' => $this->started_at?->toIso8601String(),
            'submitted_at' => $this->submitted_at?->toIso8601String(),
            'answers' => $this->whenLoaded('answers', function () {
                return $this->answers->map(function ($ans) {
                    return [
                        'id' => $ans->id,
                        'question_id' => $ans->question_id,
                        'selected_option_id' => $ans->selected_option_id,
                        'essay_answer' => $ans->essay_answer,
                        'is_correct' => (bool) $ans->is_correct,
                        'score_earned' => (float) $ans->score_earned,
                    ];
                });
            }),
        ];
    }
}
