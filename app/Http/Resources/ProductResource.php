<?php

namespace App\Http\Resources;

use App\Models\Admin\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $campaign = $this->whenLoaded('campaigns') ? $this->campaigns->first() : null;

        $priceData = ProductPrice::where('product_id', $this->id)->first();

        $wholesale_price = $priceData ? (float) $priceData->wholesale_price : null;
        $regularPrice = $priceData ? (float) $priceData->regular_price : null;
        $salePrice    = $priceData ? (float) $priceData->sale_price : null;

        if (is_null($regularPrice) && property_exists($this, 'regular_price')) {
            $regularPrice = (float) $this->regular_price;
        }
        if (is_null($salePrice) && property_exists($this, 'sale_price')) {
            $salePrice = (float) $this->sale_price;
        }

        $productDiscount = 0;
        $productDiscountType = null;
        if ($priceData) {
            $productDiscountType = $priceData->discount_type;
            $productDiscount = (float) ($priceData->discount);
        }

        $campaignDiscount = null;
        $campaignDiscountType = null;
        if ($campaign) {
            $campaignDiscount = (float) $campaign->discount_amount;
            $campaignDiscountType = $campaign->discount_type ?? 'flat';
        }

        $basePrice = ($salePrice && $salePrice > 0) ? $salePrice : $regularPrice;

        if ($campaign) {
            if ($campaignDiscountType === 'flat') {
                $finalPrice = max(0, $regularPrice - $campaignDiscount);
            } else {
                $finalPrice = $regularPrice * (1 - $campaignDiscount / 100);
            }
            $discount = $campaignDiscount;
            $discountType = $campaignDiscountType;
        } else {
            $finalPrice = $basePrice;
            $discount = $productDiscount;
            $discountType = $productDiscountType;
        }

        $original = $regularPrice ?: $finalPrice;

        $rating = 0.0;
        if ($this->relationLoaded('reviews') && $this->reviews->isNotEmpty()) {
            $rating = round((float) $this->reviews->avg('rating'), 1);
        }
        $stock = 0;
        if ($this->relationLoaded('inventory')) {
            $stock = (int) ($this->inventory->stock ?? 0);
        } elseif (property_exists($this, 'stock')) {
            $stock = (int) $this->stock;
        }

        $hasVariants = false;
        if ($this->relationLoaded('variants')) {
            $hasVariants = $this->variants->isNotEmpty();
        } else {
            $hasVariants = $this->variants()->exists();
        }
        $brand = null;
        if ($this->relationLoaded('brand') && $this->brand) {
            $brand = (string) $this->brand->name;
        } elseif (property_exists($this, 'brand_name')) {
            $brand = (string) $this->brand_name;
        } else {
            $brand = 'Brand';
        }

        $categoryName = null;
        if ($this->relationLoaded('category') && $this->category) {
            $categoryName = (string) $this->category->category_name;
        }
        $image = uploaded_asset($this->thumbnail) ?? '';

        $shortDescription = $this->short_description ?? '';

        return [
            'id'                => (int) $this->id,
            'name'              => (string) $this->name,
            'wholesale_price'   => $wholesale_price,
            'slug'              => (string) $this->slug,
            'short_description' => (string) $shortDescription,
            'price'             => $finalPrice,
            'original'          => $original,
            'discount'          => $discount,
            'discount_type'     => $discountType,
            'rating'            => (float) $rating,
            'image'             => (string) $image,
            'sold'              => (int) ($this->num_of_sale ?? 0),
            'has_variants'      => $hasVariants,
            'in_stock'          => $stock > 0,
            'category'          => $categoryName,
            'brand'             => $brand,
        ];
    }
}
