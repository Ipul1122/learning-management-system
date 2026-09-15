<?php

namespace App\Http\Resources\AdminCabang;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
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
            'action' => $this->action,
            'description' => $this->description,
            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name ?? 'System',
                'email' => $this->user?->email,
                'role' => $this->user?->roles->first()?->name,
            ],
            'branch' => $this->branch ? [
                'id' => $this->branch->id,
                'name' => $this->branch->name,
                'code' => $this->branch->code,
            ] : null,
            'target_entity' => $this->target_entity,
            'target_id' => $this->target_id,
            'properties_old' => $this->properties_old,
            'properties_new' => $this->properties_new,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
