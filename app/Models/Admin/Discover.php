<?php

namespace App\Models\Admin;

use App\Models\Upload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discover extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function mainImage()
    {
        return $this->belongsTo(Upload::class, 'main_image');
    }

    public function avatarImage()
    {
        return $this->belongsTo(Upload::class, 'avatar_image');
    }
}
