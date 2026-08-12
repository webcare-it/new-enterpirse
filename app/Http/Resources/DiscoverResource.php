<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscoverResource extends JsonResource
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
            'main_image' => $this->mainImage
                ? asset($this->mainImage->file_name)
                : null,
            'avatar_image' => $this->avatarImage
                ? asset($this->avatarImage->file_name)
                : null,
            'title' => $this->title,
            'sub_title' => $this->sub_title,
            'description' => $this->description,
            'button_text' => $this->button_text,
            'button_url' => $this->button_url,
        ];
    }
}
