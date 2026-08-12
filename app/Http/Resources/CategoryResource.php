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
            'name' => (string) ($this->name ?? ''),
            'image' => (string) (uploaded_asset($this->brand_image) ?? ''),
            'slug' => (string) ($this->slug ?? ''),
        ];
    }
}