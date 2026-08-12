<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
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
            'video_url' => $this->video_url,
            'short_titles1' => $this->short_title_1,
            'short_titles2' => $this->short_title_2,
            'short_titles3' => $this->short_title_3,
            'button' => [
                'text' => $this->button_text,
                'url'  => $this->button_url,
            ]
        ];
    }
}
