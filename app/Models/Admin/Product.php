<?php

namespace App\Models\Admin;

use App\Models\Upload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function inventory()
    {
        return $this->hasOne(ProductInventory::class);
    }

    public function price()
    {
        return $this->hasOne(ProductPrice::class);
    }

    public function shipping()
    {
        return $this->hasOne(ProductShipping::class);
    }

    public function seo()
    {
        return $this->hasOne(ProductSeo::class);
    }

    public function taxes()
    {
        return $this->hasMany(ProductTax::class);
    }

    /**
     * Get the variants for this product
     */
    public function variants()
    {
        return $this->hasMany(ProductVarient::class, 'product_id');
    }


    /**
     * Get the wishlists for this product (registered users)
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'product_id')->whereNotNull('user_id');
    }

    /**
     * Get the guest wishlists for this product
     */
    public function guestWishlists()
    {
        return $this->hasMany(Wishlist::class, 'product_id')->whereNull('user_id');
    }


    public function color()
    {
        return $this->belongsTo(Color::class, 'color');
    }


    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_products');
    }

    public function upload()
    {
        return $this->belongsTo(Upload::class, 'photos');
    }

    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'product_id');
    }


    public function shippings()
    {
        return $this->hasMany(ProductShipping::class, 'product_id');
    }
}
