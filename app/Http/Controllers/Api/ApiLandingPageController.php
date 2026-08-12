<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Landingpage;
use Illuminate\Http\Request;

class ApiLandingPageController extends Controller
{
    public function landingpage(Request $request, $slug)
    {
        $landingPage = LandingPage::with([
            'products',
            'products.price',
            'products.inventory',
            'products.brand',
            'products.category',
            'products.reviews'
        ])->where('slug', $slug)->where('is_published', true)->first();

        if (!$landingPage) {
            return response()->json([
                'data' => null,
                'message' => 'Landing page not found'
            ], 404);
        }

        // Get pivot data (LandingPageProduct) with product relations
        $pivotProducts = \App\Models\Admin\LandingPageProduct::with('product')
            ->where('landingpage_id', $landingPage->id)
            ->get();

        // Build response
        $data = [
            'id' => $landingPage->id,
            'name' => $landingPage->name,
            'slug' => $landingPage->slug,
            'title' => $landingPage->title,
            'sub_title' => $landingPage->sub_title,
            'banner_image' => $landingPage->banner_image ? uploaded_asset($landingPage->banner_image) : null,
            'mobile_banner' => $landingPage->mobile_banner ? uploaded_asset($landingPage->mobile_banner) : null,
            'video_link' => $landingPage->video_link,
            'deadline' => $landingPage->deadline,
            'features' => json_decode($landingPage->features, true),
            'description' => $landingPage->description,
            'short_description' => $landingPage->short_description,
            'testimonials' => json_decode($landingPage->testimonials, true),
            'faq' => json_decode($landingPage->faq, true),
            'meta_title' => $landingPage->meta_title,
            'meta_description' => $landingPage->meta_description,
            'meta_image' => $landingPage->meta_image ? uploaded_asset($landingPage->meta_image) : null,
            'sold_count' => (int) $landingPage->sold_count,
            'visitor_count' => (int) $landingPage->visitor_count,
            'phone' => $landingPage->phone,
            'email' => $landingPage->email,
            'whatsapp' => $landingPage->whatsapp,
            'copyright_text' => $landingPage->copyright_text,
            'products' => $pivotProducts->map(function ($pivot) {
                $product = $pivot->product;
                $regularPrice = (float) ($pivot->regular_price ?? $product->price->regular_price ?? 0);
                $discountPrice = $pivot->discount_price !== null ? (float) $pivot->discount_price : null;
                $currentPrice = ($discountPrice !== null && $discountPrice < $regularPrice) ? $discountPrice : $regularPrice;
                $discount = $regularPrice - $currentPrice;

                $avgRating = (float) ($product->reviews->avg('rating') ?? 0);
                $stock = (int) ($product->inventory->stock ?? 0);

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $currentPrice,
                    'original' => $regularPrice,
                    'discount' => $discount,
                    'rating' => round($avgRating, 1),
                    'image' => $product->thumbnail ? uploaded_asset($product->thumbnail) : null,
                    'sold' => (int) ($product->num_of_sale ?? 0),
                    'has_variants' => (bool) $product->is_variant,
                    'in_stock' => $stock > 0,
                    'category' => $product->category->category_name ?? null,
                    'brand' => $product->brand->name ?? null,
                ];
            }),
        ];

        return response()->json([
            'data' => $data,
            'message' => 'Landing page details fetched successfully'
        ]);
    }
}
