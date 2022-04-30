<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'author' => $this->whenLoaded('author'),
            'title' => $this->title,
            'image_url' => $this->imageUrl(),
            'created_at_pretty' => $this->created_at->format('F j, Y'),
            'created_at' => $this->created_at,
        ];
    }
}
