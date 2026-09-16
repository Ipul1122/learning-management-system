<?php

namespace App\Http\Resources\Trainer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
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
            'class_id' => $this->class_id,
            'class' => $this->whenLoaded('class', function () {
                return [
                    'id' => $this->class->id,
                    'title' => $this->class->title,
                    'type' => $this->class->type,
                    'status' => $this->class->status,
                ];
            }),
            'creator' => $this->whenLoaded('creator', function () {
                return [
                    'id' => $this->creator->id,
                    'name' => $this->creator->name,
                    'email' => $this->creator->email,
                ];
            }),
            'title' => $this->title,
            'description' => $this->description,
            'time_limit_minutes' => $this->time_limit_minutes,
            'passing_grade' => $this->passing_grade,
            'is_randomized' => (bool) $this->is_randomized,
            'max_attempts' => $this->max_attempts,
            'total_questions' => $this->questions_count ?? $this->questions->count(),
            'total_score_weight' => $this->totalScoreWeight(),
            'questions' => QuestionResource::collection($this->whenLoaded('questions')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
