<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Admin\Campaign;
use App\Models\Admin\Cart;
use App\Models\Admin\Color;
use App\Models\Admin\Coupon;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVarient;
use App\Models\ShippingCost;
use App\Models\User;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiCartController extends Controller
{
    public function index(Request $request)
    {
        $requestedUserId = $request->header('User-Id');

        $user = User::where('id', $requestedUserId)->first();

        if ($user) {
            $cartItems = Cart::where(function ($query) use ($user, $requestedUserId) {
                $query->where('user_id', $user->id)
                    ->orWhere('temp_user_id', $requestedUserId);
            })->get();
        } else {
            $cartItems = Cart::where('temp_user_id', $requestedUserId)->get();
        }

        // Coupon data (summed from all items)
        $couponDiscount = round($cartItems->sum('coupon_discount'), 2);
        $couponCode     = $cartItems->isNotEmpty() ? $cartItems->first()->coupon_code : null;

        $formatted = $cartItems->map(function ($item) {
            $product = $item->product;

            $variation = [];
            if ($item->variation) {
                $variation = is_array($item->variation)
                    ? $item->variation
                    : json_decode($item->variation, true);
            }

            if (isset($variation['color']) && !isset($variation['color_name'])) {
                $color = Color::find($variation['color']);
                if ($color) {
                    $variation['color_name'] = $color->name;
                }
            }

            $subtotal      = (float) ($item->price * $item->quantity);
            $totalTax      = (float) ($item->tax * $item->quantity);
            $totalDiscount = (float) ($item->discount * $item->quantity);
            $shippingCost  = (float) $item->shipping_cost;
            $shippingArea  = $item->shipping_area;

            return [
                'id' => (int) $item->id,
                'product' => [
                    'id'        => (int) $item->product_id,
                    'name'      => $product?->name ?? 'Product not found',
                    'slug'      => $product?->slug,
                    'price'     => (float) $item->price,
                    'image'     => $product?->thumbnail ? uploaded_asset($product->thumbnail) : null,
                    'quantity'  => (int) $item->quantity,
                    'variation' => json_decode($variation['attribute_value'], true) ?? null,
                ],
                'subtotal'       => $subtotal,
                'total_tax'      => $totalTax,
                'total_discount' => $totalDiscount,
                'shipping_cost'  => $shippingCost,
                'shipping_area'  => $shippingArea,
                'total_item'     => (int) $item->quantity,
            ];
        });

        // Compute summary totals
        $subtotal       = $formatted->sum('subtotal');
        $totalTax       = $formatted->sum('total_tax');
        $productDiscount = $formatted->sum('total_discount');
        $shippingCost   = $formatted->sum('shipping_cost');
        $totalItems     = $formatted->sum('total_item');

        // Total = subtotal + tax - product_discount - coupon_discount + shipping
        $total = $subtotal + $totalTax - $productDiscount - $couponDiscount + $shippingCost;

        // Determine shipping ID (or null if no items)
        $shippingId = $formatted->isNotEmpty() ? $formatted->first()['shipping_area'] : null;

        return response()->json([
            'success' => true,
            'id' => $requestedUserId,
            'data' => [
                'items' => $formatted,
                'summary' => [
                    'total'             => round($total, 2),
                    'subtotal'          => round($subtotal, 2),
                    'total_tax'         => round($totalTax, 2),
                    'total_discount'    => round($productDiscount, 2),
                    'shipping_cost'     => round($shippingCost, 2),
                    'total_item'        => $totalItems,
                    'coupon_discount'   => $couponDiscount,
                    'coupon_code'       => $couponCode,
                    'shipping_id'       => $shippingId,
                ]
            ]
        ]);
    }

    public function shipping_areas(Request $request)
    {
        // 1. Identify user
        $requestedUserId = $request->header('User-Id');
        if ($user = User::find($requestedUserId)) {
            $cartItems = Cart::where('user_id', $user->id)->get();
        } else {
            $cartItems = Cart::where('temp_user_id', $requestedUserId)->get();
        }

        // 2. Validate shipping area
        $shippingAreaId = $request->input('shipping_area');
        if (!$shippingAreaId) {
            return response()->json([
                'success' => false,
                'message' => 'Shipping area is required.'
            ], 422);
        }

        $shippingCost = ShippingCost::find($shippingAreaId);
        if (!$shippingCost) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid shipping area.'
            ], 404);
        }

        $totalShipping = (float) $shippingCost->amount;
        $itemCount = $cartItems->count();

        if ($itemCount > 0) {
            $perItemShipping = $totalShipping / $itemCount;
            foreach ($cartItems as $item) {
                // Update the database column
                $item->shipping_cost = $perItemShipping;
                $item->shipping_area = $request->input('shipping_area');
                $item->save();
            }
        }


        // 6. Return JSON
        return response()->json([
            'success' => true,
            'message' => 'Shipping area updated successfully.'
        ]);
    }

    /**
     * Add a product to cart.
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'sometimes|integer|min:1',
            'variation'  => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $campaignId = $request->header('campaign_id');
        $campaign = null;
        $campaignDiscountAmount = null;
        $campaignDiscountType = null;

        if ($campaignId) {
            $campaign = Campaign::where('id', $campaignId)->where('status', 1)->first();
            if ($campaign) {
                $campaignDiscountAmount = (float) $campaign->discount_amount;
                $campaignDiscountType = $campaign->discount_type ?? 'flat';
            }
        }

        $product = Product::with('inventory')->find($request->product_id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $quantity = $request->quantity ?? 1;
        $variation = $request->variation ?? [];

        $regularPrice = (float) optional($product->price)->regular_price ?? 0;
        $productDiscount = (float) optional($product->price)->discount ?? 0;
        $salePrice = (float) optional($product->price)->sale_price ?? $regularPrice;
        $tax = (float) $product->tax ?? 0;
        $shippingCost = (float) $product->shipping_cost ?? 0;
        $sku = $variation['sku'] ?? $product->sku;

        $colorId = null;
        $attributeValue = null;
        $variantPrice = null;

        if ($sku && $sku !== $product->sku) {
            $variant = ProductVarient::where('product_id', $product->id)
                ->where('sku', $sku)
                ->first();

            if (!$variant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Variant not found'
                ], 404);
            }

            if ($variant->quantity < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock for this variant'
                ], 400);
            }

            $variantPrice = $variant->price !== null ? (float) $variant->price : null;
            $colorId = $variant->color ?? null;
            $attributeValue = $variant->attribute_value ?? null;
        }

        if ($variantPrice !== null) {
            $basePrice = $variantPrice;
        } else {
            $basePrice = $salePrice > 0 ? $salePrice : $regularPrice;
        }

        if ($campaign) {
            if ($campaignDiscountType === 'flat') {
                $finalPrice = max(0, $regularPrice - $campaignDiscountAmount);
            } else { // percent
                $finalPrice = $regularPrice * (1 - $campaignDiscountAmount / 100);
            }
            $discountToSave = $campaignDiscountAmount;
            $discountTypeToSave = $campaignDiscountType;
        } else {
            $finalPrice = $basePrice;
            $discountToSave = $productDiscount;
            $discountTypeToSave = optional($product->price)->discount_type ?? 'percent';
        }

        $userId = null;
        $tempUserId = null;
        $requestedUserId = $request->header('User-Id') ?? $request->input('user_id');

        if ($requestedUserId && \App\Models\User::where('id', $requestedUserId)->exists()) {
            $userId = $requestedUserId;
        } else {
            $tempUserId = $request->input('temp_user_id') ?? $requestedUserId ?? (string) \Illuminate\Support\Str::uuid();
        }

        $cartItem = Cart::where('product_id', $product->id)
            ->where('sku', $sku)
            ->when($userId, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when(!$userId && $tempUserId, function ($query) use ($tempUserId) {
                return $query->where('temp_user_id', $tempUserId);
            })
            ->first();

        $variationForStorage = [
            'sku'             => $sku,
            'color'           => $colorId,
            'attribute_value' => $attributeValue,
        ];

        if (!empty($variation)) {
            $variationForStorage = array_merge($variation, $variationForStorage);
        }

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->price = $finalPrice;
            $cartItem->discount = $discountToSave;
            $cartItem->save();
            $message = 'Cart updated successfully';
        } else {
            $cartItem = Cart::create([
                'product_id'    => $product->id,
                'sku'           => $sku ?? $product->inventory->sku,
                'user_id'       => $userId,
                'temp_user_id'  => $tempUserId,
                'variation'     => json_encode($variationForStorage),
                'price'         => $finalPrice,
                'tax'           => $tax,
                'discount'      => $discountToSave,
                'shipping_cost' => $shippingCost,
                'shipping_type' => $product->shipping_type ?? 'flat_rate',
                'quantity'      => $quantity,
                'owner_id'      => $product->vendor_id ?? null,
            ]);
            $message = 'Added to cart';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }


    /**
     * Update quantity of a cart item.
     */
    public function update(Request $request, $id)
    {
        $requestedUserId = $request->header('User-Id');
        $userId = null;
        $tempUserId = null;

        if ($requestedUserId) {
            if (User::where('id', $requestedUserId)->exists()) {
                $userId = $requestedUserId;
            } else {
                $tempUserId = $requestedUserId;
            }
        }

        $query = Cart::where('id', $id);
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($tempUserId) {
            $query->where('temp_user_id', $tempUserId);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User identification required',
            ], 401);
        }

        $cartItem = $query->first();
        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $newQuantity = $request->quantity;

        $product = $cartItem->product;
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        if ($cartItem->sku && $cartItem->sku !== $product->sku) {
            $variant = ProductVarient::where('product_id', $product->id)
                ->where('sku', $cartItem->sku)
                ->first();

            if (!$variant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Variant not found',
                ], 404);
            }

            if ($variant->quantity < $newQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock. Available: {$variant->quantity}",
                ], 400);
            }
        } else {
            $inventory = $product->inventory;
            if (!$inventory || $inventory->stock < $newQuantity) {
                $available = $inventory ? $inventory->stock : 0;
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock. Available: {$available}",
                ], 400);
            }
        }

        $cartItem->quantity = $newQuantity;
        $cartItem->save();

        return $this->index($request);
    }

    /**
     * Remove a single item from cart.
     */
    public function remove(Request $request, $id)
    {
        $requestedUserId = $request->header('User-Id');
        $userId = null;
        $tempUserId = null;

        if ($requestedUserId) {
            if (User::where('id', $requestedUserId)->exists()) {
                $userId = $requestedUserId;
            } else {
                $tempUserId = $requestedUserId;
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User-Id header is required',
            ], 401);
        }

        $query = Cart::where('id', $id);

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('temp_user_id', $tempUserId);
        }

        $cartItem = $query->first();
        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found',
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully',
        ]);
    }

    /**
     * Clear all items from cart.
     */
    public function clear(Request $request)
    {
        $requestedUserId = $request->header('User-Id');

        $userId = null;
        $tempUserId = null;
        if ($requestedUserId) {
            $userExists = User::where('id', $requestedUserId)->exists();
            if ($userExists) {
                $userId = $requestedUserId;
            } else {
                $tempUserId = $requestedUserId;
            }
        } else {
            $tempUserId = $request->input('temp_user_id') ?? $request->query('temp_user_id');
        }

        if (!$userId && !$tempUserId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }
        $query = Cart::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('temp_user_id', $tempUserId);
        }

        $items = $query->get();

        if ($items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is already empty'
            ], 404);
        }
        $items->each->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }

    public function cartRelated(Request $request)
    {
        $requestedUserId = $request->header('User-Id');

        $user = User::where('id', $requestedUserId)->first();

        $cartItemsQuery = Cart::with(['product.category']);
        if ($user) {
            $cartItems = $cartItemsQuery->where('user_id', $user->id)->get();
        } else {
            $cartItems = $cartItemsQuery->where('temp_user_id', $requestedUserId)->get();
        }

        $productIds = $cartItems->pluck('product_id')->filter()->unique()->values();

        $categoryIds = $cartItems->pluck('product.category_id')->filter()->unique();

        $relatedProducts = collect();
        if ($productIds->isNotEmpty() && $categoryIds->isNotEmpty()) {
            $allRelated = Product::with(['price', 'reviews', 'variants', 'inventory'])
                ->whereIn('category_id', $categoryIds)
                ->whereNotIn('id', $productIds)
                ->limit(100)
                ->get();

            $relatedProducts = ProductResource::collection($allRelated->take(12));
        }

        return response()->json([
            'success' => true,
            'data' => [
                'related_products' => $relatedProducts
            ]
        ]);
    }
}
