<?php

namespace App\Models\Admin;

use App\Models\Landingpage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPageProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'landingpage_id',
        'product_id',
        'regular_price',
        'discount_price'
    ];

    protected $casts = [
        'regular_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the landing page
     */
    public function landingPage()
    {
        return $this->belongsTo(Landingpage::class, 'landingpage_id');
    }

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
