<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'     => $this->id,
            'title'  => $this->title,
            'url'    => $this->photos ? uploaded_asset($this->photos) : null,
            'type'   => 'image',
            'button' => $this->button_name,
            'link'   => $this->button_link,
        ];
    }
}
