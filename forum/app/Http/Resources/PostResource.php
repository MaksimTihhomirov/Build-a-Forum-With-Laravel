<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'html' => str($this->body)->markdown(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'likes_count' => $this->likes_count ?? 0,

            'can_like' => $request->user()
                ? ! $this->likes()
                    ->where('user_id', $request->user()->id)
                    ->exists()
                : false,

            'can_unlike' => $request->user()
                ? $this->likes()
                    ->where('user_id', $request->user()->id)
                    ->exists()
                : false,

            'user' => UserResource::make(
                $this->whenLoaded('user')
            ),

            'topic' => TopicResource::make(
                $this->whenLoaded('topic')
            ),
        ];
    }
}
