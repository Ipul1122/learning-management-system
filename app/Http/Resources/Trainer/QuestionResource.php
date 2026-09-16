<?php

namespace App\Http\Resources\Trainer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'branch_id' => $this->branch_id,
            'creator' => [
                'id' => $this->creator?->id,
                'name' => $this->creator?->name,
                'email' => $this->creator?->email,
            ],
            'question_text' => $this->question_text,
            'question_type' => $this->question_type,
            'score_weight' => $this->score_weight,
            'explanation' => $this->explanation,
            'options' => $this->options->map(function ($opt) {
                return [
                    'id' => $opt->id,
                    'option_text' => $opt->option_text,
                    'is_correct' => $opt->is_correct,
                    'option_order' => $opt->option_order,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
