<?php

namespace App\Models\Admin;

use App\Models\Upload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getSliderImagesAttribute()
    {
        if (!$this->photos) {
            return collect();
        }

        $photoIds = explode(',', $this->photos);

        return Upload::whereIn('id', $photoIds)->get();
    }

    public function upload()
    {
        return $this->belongsTo(Upload::class, 'photos');
    }
}
