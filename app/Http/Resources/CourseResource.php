<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'price' => $this->price,
            'status' => $this->status,
            'image' => $this->image ? asset('storage/' . $this->image) : null, 
            'published_at' => $this->published_at,
            'created_at' => $this->created_at->toIso8601String(),
            'author' => new UserResource($this->user),
            'comments'=>CommentResource::collection($this->comments),
        ];
    }
}
