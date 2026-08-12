<?php

namespace App\Models\Admin;

use App\Models\Upload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function avatarImage()
    {
        return $this->belongsTo(Upload::class, 'avatar');
    }
}
