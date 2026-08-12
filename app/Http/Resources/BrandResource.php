<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => (int) ($this->id ?? 0),
            'name' => (string) ($this->name ?? ''),
            'logo' => (string) (uploaded_asset($this->brand_image) ?? ''),
            'slug' => (string) ($this->slug ?? ''),
        ];
    }
}