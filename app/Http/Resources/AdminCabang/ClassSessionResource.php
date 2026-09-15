<?php

namespace App\Http\Resources\AdminCabang;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassSessionResource extends JsonResource
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
            'session_order' => $this->session_order,
            'title' => $this->title,
            'jp_duration' => $this->jp_duration,
            'minute_duration' => $this->minute_duration,
            'session_date' => $this->session_date?->toISOString(),
            'zoom_url' => $this->zoom_url,
            'zoom_meeting_id' => $this->zoom_meeting_id,
            'zoom_passcode' => $this->zoom_passcode,
            'created_by_user_id' => $this->created_by_user_id,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
