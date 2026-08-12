<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'title'             => $this->blog_title,
            'slug'              => $this->slug,
            'thumbnail' =>    $this->thumbnailImage
                ? asset($this->thumbnailImage->file_name)
                : null,
            'short_description' => $this->short_description,
            'created_at'        => $this->created_at?->format('d M Y'),
            'author' => $this->user?->name,
        ];
    }
}
