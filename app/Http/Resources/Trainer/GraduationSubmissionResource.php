<?php

namespace App\Http\Resources\Trainer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GraduationSubmissionResource extends JsonResource
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
            'enrollment_id' => $this->enrollment_id,
            'student' => [
                'id' => $this->enrollment?->user?->id,
                'name' => $this->enrollment?->user?->name,
                'email' => $this->enrollment?->user?->email,
            ],
            'class' => [
                'id' => $this->enrollment?->trainingClass?->id,
                'title' => $this->enrollment?->trainingClass?->title,
                'branch' => $this->enrollment?->trainingClass?->branch?->name,
                'type' => $this->enrollment?->trainingClass?->type,
            ],
            'total_jp_earned' => $this->total_jp_earned,
            'avg_quiz_score' => $this->avg_quiz_score,
            'status' => $this->status,
            'trainer_feedback' => $this->trainer_feedback,
            'reviewed_at' => $this->reviewed_at?->toIso8601String(),
            'trainer' => $this->trainer ? [
                'id' => $this->trainer->id,
                'name' => $this->trainer->name,
            ] : null,
            'certificate_number' => $this->certificate_number,
            'verification_url' => $this->getVerificationUrl(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
