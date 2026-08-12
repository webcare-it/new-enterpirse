<?php

namespace App\Models;

use App\Models\Admin\LandingPageProduct;
use App\Models\Admin\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landingpage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'title',
        'sub_title',
        'banner_image',
        'mobile_banner',
        'video_link',
        'deadline',
        'features',
        'description',
        'short_description',
        'testimonials',
        'faq',
        'meta_title',
        'meta_description',
        'meta_image',
        'sold_count',
        'visitor_count',
        'phone',
        'email',
        'whatsapp',
        'copyright_text',
        'is_published'
    ];

    protected $casts = [
        'features' => 'array',
        'testimonials' => 'array',
        'faq' => 'array',
        'deadline' => 'datetime',
        'is_published' => 'boolean',
        'sold_count' => 'integer',
        'visitor_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the products associated with this landing page
     */
    public function landingPageProducts()
    {
        return $this->hasMany(LandingPageProduct::class, 'landingpage_id');
    }

    /**
     * Get the products through pivot
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'landing_page_products', 'landingpage_id', 'product_id')
            ->withPivot('regular_price', 'discount_price')
            ->withTimestamps();
    }

    /**
     * Scope for published landing pages
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Get discount percentage for a product
     */
    public function getDiscountPercentage($productId)
    {
        $product = $this->products()->where('product_id', $productId)->first();
        if ($product && $product->pivot->regular_price > 0 && $product->pivot->discount_price) {
            return round((($product->pivot->regular_price - $product->pivot->discount_price) / $product->pivot->regular_price) * 100);
        }
        return 0;
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->is_published) {
            return '<span class="badge badge-success">Published</span>';
        }
        return '<span class="badge badge-danger">Draft</span>';
    }

    /**
     * Check if deadline is expired
     */
    public function isDeadlineExpired()
    {
        return $this->deadline && $this->deadline < now();
    }
}
