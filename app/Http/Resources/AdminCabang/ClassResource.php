<?php

namespace App\Http\Resources\AdminCabang;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'type' => $this->type,
            'offline_capacity' => $this->offline_capacity,
            'online_capacity' => $this->online_capacity,
            'enrolled_offline' => $this->enrolled_offline,
            'enrolled_online' => $this->enrolled_online,
            'remaining_offline_seats' => $this->remainingOfflineSeats(),
            'remaining_online_seats' => $this->remainingOnlineSeats(),
            'required_jp' => $this->required_jp,
            'total_accumulated_jp' => $this->totalAccumulatedJp(),
            'total_accumulated_minutes' => $this->totalAccumulatedMinutes(),
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'status' => $this->status,
            'trainer' => [
                'id' => $this->trainer?->id,
                'name' => $this->trainer?->name,
                'email' => $this->trainer?->email,
            ],
            'branch' => [
                'id' => $this->branch?->id,
                'name' => $this->branch?->name,
                'code' => $this->branch?->code,
            ],
            'sessions' => ClassSessionResource::collection($this->whenLoaded('sessions')),
            'sessions_count' => $this->whenCounted('sessions'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
