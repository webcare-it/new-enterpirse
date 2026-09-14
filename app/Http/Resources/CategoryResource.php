<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => (int) ($this->id ?? 0),
            'category_name' => (string) ($this->category_name ?? ''),
            'category_image' => (string) (uploaded_asset($this->category_image) ?? ''),
            'icon' => (string) ($this->icon ?? ''),
            'slug' => (string) ($this->slug ?? ''),
            'position' => (int) ($this->position ?? 0),
        ];
    }
}