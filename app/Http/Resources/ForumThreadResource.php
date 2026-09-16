<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ForumThreadResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'is_pinned' => $this->is_pinned,
            'is_locked' => $this->is_locked,
            'replies_count' => $this->replies_count ?? $this->replies()->count(),
            'author' => [
                'id' => $this->author?->id,
                'name' => $this->author?->name,
                'role' => $this->author?->getRoleNames()->first() ?? 'user',
            ],
            'class' => $this->whenLoaded('trainingClass', function () {
                return [
                    'id' => $this->trainingClass->id,
                    'title' => $this->trainingClass->title,
                ];
            }),
            'root_replies' => ForumReplyResource::collection($this->whenLoaded('rootReplies')),
            'created_at' => $this->created_at?->toIso8601String(),
            'created_at_human' => $this->created_at?->diffForHumans(),
        ];
    }
}
