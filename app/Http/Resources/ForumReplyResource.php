<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ForumReplyResource extends JsonResource
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
            'thread_id' => $this->thread_id,
            'parent_reply_id' => $this->parent_reply_id,
            'reply_content' => $this->reply_content,
            'author' => [
                'id' => $this->author?->id,
                'name' => $this->author?->name,
                'role' => $this->author?->getRoleNames()->first() ?? 'user',
            ],
            'children' => ForumReplyResource::collection($this->whenLoaded('children')),
            'created_at' => $this->created_at?->toIso8601String(),
            'created_at_human' => $this->created_at?->diffForHumans(),
        ];
    }
}
