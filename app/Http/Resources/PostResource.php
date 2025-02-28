<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'comments_count' => $this->comments_count ?? $this->comments()->count(),
            'likes_count' => $this->likes_count ?? $this->likes()->count(),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
