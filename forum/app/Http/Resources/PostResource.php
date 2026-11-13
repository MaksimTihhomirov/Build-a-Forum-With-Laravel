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

            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],

            'topic' => $this->when(
                $this->resource->relationLoaded('topic')
                    && $this->topic !== null,
                fn () => TopicResource::make($this->topic)
            ),
        ];
    }
}
