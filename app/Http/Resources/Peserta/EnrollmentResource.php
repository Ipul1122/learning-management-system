<?php

namespace App\Http\Resources\Peserta;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
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
            'class' => $this->whenLoaded('trainingClass', function () {
                return [
                    'id' => $this->trainingClass->id,
                    'title' => $this->trainingClass->title,
                    'type' => $this->trainingClass->type,
                    'required_jp' => $this->trainingClass->required_jp,
                    'status' => $this->trainingClass->status,
                    'branch' => $this->trainingClass->branch ? [
                        'id' => $this->trainingClass->branch->id,
                        'name' => $this->trainingClass->branch->name,
                    ] : null,
                    'trainer' => $this->trainingClass->trainer ? [
                        'id' => $this->trainingClass->trainer->id,
                        'name' => $this->trainingClass->trainer->name,
                    ] : null,
                ];
            }),
            'attendance_mode' => $this->attendance_mode,
            'accumulated_minutes' => $this->accumulated_minutes,
            'accumulated_jp' => (float) $this->accumulated_jp,
            'progress_percentage' => $this->progressPercentage(),
            'is_completed_jp' => $this->isCompletedJp(),
            'status' => $this->status,
            'enrolled_at' => $this->enrolled_at?->toIso8601String(),
            'attendances_count' => $this->attendances_count ?? $this->attendances->count(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
