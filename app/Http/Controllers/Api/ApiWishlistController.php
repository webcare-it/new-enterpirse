<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVarient;
use App\Models\Admin\Wishlist;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiWishlistController extends Controller
{
    /**
     * Get all wishlist items for the current user or guest.
     */
    public function index(Request $request)
    {
        $userId = $request->header('User-Id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User-Id header is required',
            ], 401);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid user',
            ], 401);
        }

        $wishlistItems = Wishlist::with([
            'product.price',
            'product.category',
            'product.brand',
            'product.reviews',
            'product.inventory',
            'product.variants'
        ])->where('user_id', $userId)->get();

        $items = $wishlistItems->map(function ($wishlist) {
            $product = $wishlist->product;
            $regularPrice = $product->price->regular_price ?? 0;
            $discountPrice = $product->price->discount ?? 0;
            $original = $regularPrice;
            $price = $discountPrice > 0 ? $discountPrice : $regularPrice;
            $discount = $discountPrice > 0 ? round((($regularPrice - $discountPrice) / $regularPrice) * 100) : 0;

            $rating = $product->reviews->avg('rating') ?? 0;

            $inStock = false;
            if ($product->is_variant) {
                $inStock = $product->variants->some(fn($v) => $v->quantity > 0);
            } else {
                $inStock = ($product->inventory->stock ?? 0) > 0;
            }
            $image = $product->thumbnail ? uploaded_asset($product->thumbnail) : null;

            return [
                'id' => $wishlist->id,
                'product' => [
                    'id'            => $product->id,
                    'name'          => $product->name,
                    'slug'          => $product->slug,
                    'price'         => (float) $price,
                    'original'      => (float) $original,
                    'discount'      => (int) $discount,
                    'rating'        => (float) number_format($rating, 1),
                    'image'         => $image,
                    'sold'          => (int) ($product->num_of_sale ?? 0),
                    'has_variants'  => (bool) $product->is_variant,
                    'in_stock'      => $inStock,
                    'category'      => $product->category?->category_name,
                    'brand'         => $product->brand?->name
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => [
                'items' => $items
            ]
        ]);
    }

    /**
     * Toggle product in wishlist.
     */
    public function toggle(Request $request)
    {
        // Authenticate via header
        $userId = $request->header('User-Id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User-Id header is required',
            ], 401);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid user',
            ], 401);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'variation'  => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        // Prepare variation data (optional)
        $variation = $request->variation ?? [];

        if (!empty($variation)) {
            ksort($variation);
        }

        $sku = $variation['sku'] ?? null;

        $variationForStorage = $variation;
        unset($variationForStorage['sku']);

        // Check only by user_id + product_id
        $wishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist',
            ], 200);
        }

        // Create wishlist
        $wishlist = Wishlist::create([
            'user_id'      => $userId,
            'temp_user_id' => null,
            'product_id'   => $product->id,
            'variation'    => json_encode($variationForStorage),
            'sku'          => $sku,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist'
        ], 201);
    }


    /**
     * Remove a product from the wishlist (by product ID).
     * Deletes all entries for that product (including different variations) for the current user/guest.
     */
    public function destroy(Request $request, $productId)
    {
        $userId = $request->header('User-Id');
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User-Id header is required',
            ], 401);
        }
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid user',
            ], 401);
        }
        $wishlist = Wishlist::where('id', $productId)
            ->where('user_id', $userId)
            ->first();

        if (!$wishlist) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in wishlist',
            ], 404);
        }
        $wishlist->delete();
        return response()->json([
            'success' => true,
            'message' => 'Product removed from wishlist',
        ]);
    }
}
