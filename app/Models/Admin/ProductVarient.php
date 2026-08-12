<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVarient extends Model
{
    use HasFactory;


    protected $guarded = ['id'];


    /**
     * Get the product that owns the inventory
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


    public function attributeRel()
    {
        return $this->belongsTo(Attribute::class, 'attribute');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color');
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute'); // 'attribute' is the foreign key column
    }
}
