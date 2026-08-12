<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiCampaignController extends Controller
{
    public function list(Request $request)
    {
        $request->validate([
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100',
        ]);

        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $campaigns = \App\Models\Admin\Campaign::paginate($perPage, ['*'], 'page', $page);
        $items = $campaigns->items();

        // Transform each campaign to include image URL
        $data = collect($items)->map(function ($campaign) {
            return [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'slug' => $campaign->slug,
                'description' => $campaign->description,
                'start_date' => $campaign->start_date,
                'end_date' => $campaign->end_date,
                'discount_amount' => $campaign->discount_amount,
                'discount_type' => $campaign->discount_type,
                'status' => $campaign->status,
                'image' => $campaign->image ? uploaded_asset($campaign->image) : null,
            ];
        });

        return response()->json([
            'data' => $data,
            'message' => 'Campaigns fetched successfully'
        ]);
    }


    public function details($slug)
    {
        $campaign = \App\Models\Admin\Campaign::with([
            'products',
            'products.price',
            'products.inventory',
            'products.brand',
            'products.category',          // load category for name
            'products.reviews'            // load reviews for rating
        ])->where('slug', $slug)->first();

        if (!$campaign) {
            return response()->json([
                'data'    => null,
                'message' => 'Campaign not found'
            ], 404);
        }

        // Fetch campaign products with priority
        $campaignProducts = \App\Models\Admin\CampaignProduct::with('product')
            ->where('campaign_id', $campaign->id)
            ->orderBy('priority')
            ->get();

        $data = [
            'campaign'        => [
                'id'              => $campaign->id,
                'name'            => $campaign->name,
                'slug'            => $campaign->slug,
                'description'     => $campaign->description,
                'start_date'      => $campaign->start_date,
                'end_date'        => $campaign->end_date,
                'discount_amount' => (float) $campaign->discount_amount,
                'discount_type'   => $campaign->discount_type,
                'status'          => $campaign->status,
                'image'           => $campaign->image ? uploaded_asset($campaign->image) : null,
            ],
            'products'        => $campaignProducts->map(function ($campaignProduct) {
                $product = $campaignProduct->product;

                // Compute price and discount
                $regularPrice = $product->price->regular_price ?? 0;
                $salePrice    = $product->price->sale_price ?? 0;
                $currentPrice = ($salePrice > 0 && $salePrice < $regularPrice) ? $salePrice : $regularPrice;
                $discount     = $regularPrice - $currentPrice;

                // Rating (average from reviews)
                $avgRating = $product->reviews->avg('rating') ?? 0;

                // Stock availability
                $stock = $product->inventory->stock ?? 0;

                return [
                    'id'            => $product->id,
                    'name'          => $product->name,
                    'slug'          => $product->slug,
                    'price'         => (float) $currentPrice,
                    'original'      => (float) $regularPrice,
                    'discount'      => (float) $discount,
                    'rating'        => round($avgRating, 1),
                    'image'         => $product->thumbnail ? uploaded_asset($product->thumbnail) : null,
                    'sold'          => $product->num_of_sale ?? 0,
                    'has_variants'  => (bool) $product->is_variant,
                    'in_stock'      => $stock > 0,
                    'category'      => $product->category->category_name ?? null,
                    'brand'         => $product->brand->name ?? null,
                    'priority'      => $campaignProduct->priority, // keep priority if needed
                ];
            }),
        ];

        return response()->json([
            'data'    => $data,
            'message' => 'Campaign details fetched successfully'
        ]);
    }
}
