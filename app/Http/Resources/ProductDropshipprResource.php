<?php

namespace App\Http\Resources;

use App\Models\Admin\ProductPrice;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDropshipprResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // ---- Load price data ----
        $priceData = $this->whenLoaded('price') ?? ProductPrice::where('product_id', $this->id)->first();
        $regularPrice = $priceData ? (float) $priceData->regular_price : null;
        $salePrice    = $priceData ? (float) $priceData->sale_price : null;
        $productDiscount = $priceData ? (float) $priceData->discount : 0;
        $productDiscountType = $priceData ? $priceData->discount_type : null;

        // Fallback to direct properties if relation missing
        if (is_null($regularPrice) && property_exists($this, 'regular_price')) {
            $regularPrice = (float) $this->regular_price;
        }
        if (is_null($salePrice) && property_exists($this, 'sale_price')) {
            $salePrice = (float) $this->sale_price;
        }

        // ---- Final price (no campaign) ----
        $finalPrice = ($salePrice && $salePrice > 0) ? $salePrice : $regularPrice;
        $original   = $regularPrice ?: $finalPrice;

        // ---- Variant handling ----
        $variants = $this->whenLoaded('variants');
        $hasVariants = $variants && $variants->isNotEmpty();

        // wholesale_price: max variant wholesale if variants exist, else parent wholesale
        $wholesalePrice = null;
        $priceRange = null;

        if ($hasVariants) {
            // Max wholesale among variants
            $wholesalePrice = (float) $variants->max('wholesale_price') ?: null;

            // Price range from variant retail prices
            $prices = $variants->pluck('price')->filter()->map(fn($p) => (float) $p)->values();
            if ($prices->isNotEmpty()) {
                $priceRange = ['min' => $prices->min(), 'max' => $prices->max()];
            }
        } else {
            // Simple product: use parent wholesale
            $wholesalePrice = $priceData ? (float) $priceData->wholesale_price : null;
        }

        // ---- Rating ----
        $rating = 0.0;
        if ($this->relationLoaded('reviews') && $this->reviews->isNotEmpty()) {
            $rating = round((float) $this->reviews->avg('rating'), 1);
        }

        // ---- Stock ----
        $stock = 0;
        if ($this->relationLoaded('inventory')) {
            $stock = (int) ($this->inventory->stock ?? 0);
        } elseif (property_exists($this, 'stock')) {
            $stock = (int) $this->stock;
        }

        // ---- Brand ----
        $brand = null;
        if ($this->relationLoaded('brand') && $this->brand) {
            $brand = (string) $this->brand->name;
        } elseif (property_exists($this, 'brand_name')) {
            $brand = (string) $this->brand_name;
        } else {
            $brand = 'Brand';
        }

        // ---- Category ----
        $categoryName = null;
        if ($this->relationLoaded('category') && $this->category) {
            $categoryName = (string) $this->category->category_name;
        }

        // ---- Image ----
        $image = uploaded_asset($this->thumbnail) ?? '';

        return [
            'id'                => (int) $this->id,
            'name'              => (string) $this->name,
            'slug'              => (string) $this->slug,
            'short_description' => (string) ($this->short_description ?? ''),
            'price'             => $finalPrice,
            'original'          => $original,
            'discount'          => $productDiscount,
            'discount_type'     => $productDiscountType,
            'price_range'       => $priceRange,        // null for simple products
            'wholesale_price'   => $wholesalePrice,    // max variant or parent
            'rating'            => (float) $rating,
            'image'             => (string) $image,
            'sold'              => (int) ($this->num_of_sale ?? 0),
            'has_variants'      => $hasVariants,
            'in_stock'          => $stock > 0,
            'category'          => $categoryName,
            'brand'             => $brand,
            'details' => [
                'slug' => url("/api/v1/dropshippers/products/{$this->slug}"),
                'id'   => url("/api/v1/dropshippers/products/{$this->id}"),
            ]
        ];
    }
}
