<?php

namespace App\Models\Admin;

use App\Models\Upload;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thumbnailImage()
    {
        return $this->belongsTo(Upload::class, 'thumbnail');
    }

    public function mainImage()
    {
        return $this->belongsTo(Upload::class, 'main_image');
    }

    public function metaImage()
    {
        return $this->belongsTo(Upload::class, 'meta_image');
    }
}
