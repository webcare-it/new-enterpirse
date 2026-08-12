<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInventory extends Model
{
    use HasFactory;

    protected $table = 'product_inventories';

    protected $guarded = ['id'];


    protected $casts = [
        'stock' => 'integer',
        'price' => 'decimal:2',
        'attribute' => 'array'
    ];

    /**
     * Get the product that owns the inventory
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
