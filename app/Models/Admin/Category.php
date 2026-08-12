<?php

namespace App\Models\Admin;

use App\Models\Upload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'categories';



    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }


    public function upload()
    {
        return $this->belongsTo(Upload::class, 'photos');
    }
}
