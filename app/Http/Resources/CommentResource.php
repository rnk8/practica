<?php


// app/Http/Resources/CommentResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'post_id' => $this->post_id,
            'likes_count' => $this->likes_count ?? $this->likes()->count(),
        ];
    }
}