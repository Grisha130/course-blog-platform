<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'comment' => $this->comment,
            'blog_id' => $this->blog_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->toIso8601String(),
            'author' => new UserResource($this->user),
        ];
    }
}
