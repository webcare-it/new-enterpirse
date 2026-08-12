<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleBlogResource extends JsonResource
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
            'id'                => $this->id,
            'title'             => $this->blog_title,
            'slug'              => $this->slug,
            'main_image' => $this->mainImage
                ? asset($this->mainImage->file_name)
                : null,
            'tags'              => explode(',', $this->tags),
            'short_description' => $this->short_description,
            'long_description'  => $this->long_description,
            'meta' => [
                'title'       => $this->meta_title,
                'description' => $this->meta_description,
                'image'       => $this->metaImage?->file_name,
            ],
            'created_at' => $this->created_at?->format('d M Y'),
            'user' => [
                'id'   => $this->user?->id,
                'name' => $this->user?->name,
            ],
        ];
    }
}
