<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CounterResource extends JsonResource
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
            ...collect(range(1, 4))->mapWithKeys(fn($i) => [
                "title_$i"      => $this->{"title_$i"},
                "count_$i"      => $this->{"count_$i"},
                "icon_image_$i" => $this->{"icon{$i}Image"}
                    ? asset($this->{"icon{$i}Image"}->file_name)
                    : null,
            ])->toArray(),
        ];
    }
}
