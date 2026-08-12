<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubCategory extends Model
{
    use HasFactory;


    protected $guarded = ['id'];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }


    /**
     * Get the parent category
     */
    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }


    /**
     * Slug Mutator
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;

        if (!isset($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }


    public function products()
    {
        return $this->hasMany(Product::class, 'subcategory_id');
    }
}
