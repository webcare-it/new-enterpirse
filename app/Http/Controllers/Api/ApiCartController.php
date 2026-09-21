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
    public function index1(Request $request)
    {
        $requestedUserId = $request->header('User-Id');

        $user = User::where('id', $requestedUserId)->first();

        // Eager load product + its shipping record to avoid N+1 and get correct shipping cost
        $query = Cart::with(['product.shippings']);

        if ($user) {
            $cartItems = $query->where(function ($q) use ($user, $requestedUserId) {
                $q->where('user_id', $user->id)
                    ->orWhere('temp_user_id', $requestedUserId);
            })->get();
        } else {
            $cartItems = $query->where('temp_user_id', $requestedUserId)->get();
        }

        // Coupon data (summed from all items)
        $couponDiscount = round($cartItems->sum('coupon_discount'), 2);
        $couponCode     = $cartItems->isNotEmpty() ? $cartItems->first()->coupon_code : null;

        $formatted = $cartItems->map(function ($item) {
            $product = $item->product;

            // ---- Variation handling ----
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

            // ---- Shipping from product_shippings ----
            // Assumes Product model has a `shippings` relation (hasMany).
            $shipping     = $product?->shippings?->first();
            $shippingCost = (float) ($shipping?->shipping_cost ?? 0);
            $shippingArea = $shipping?->id; // this is product_shippings.id
            // If you actually store a separate shipping "area"/zone id, use that column instead.

            // ---- Totals per item ----
            $subtotal      = (float) ($item->price * $item->quantity);
            $totalTax      = (float) ($item->tax * $item->quantity);
            $totalDiscount = (float) ($item->discount * $item->quantity);

            return [
                'id' => (int) $item->id,
                'product' => [
                    'id'        => (int) $item->product_id,
                    'name'      => $product?->name ?? 'Product not found',
                    'slug'      => $product?->slug,
                    'price'     => (float) $item->price,
                    'image'     => $product?->thumbnail ? uploaded_asset($product->thumbnail) : null,
                    'quantity'  => (int) $item->quantity,
                    'variation' => isset($variation['attribute_value'])
                        ? json_decode($variation['attribute_value'], true)
                        : null,
                    'shipping_cost' => $shippingCost,
                ],
                'subtotal'       => $subtotal,
                'total_tax'      => $totalTax,
                'total_discount' => $totalDiscount,
                'shipping_cost'  => $shippingCost,
                'shipping_area'  => $shippingArea,
                'total_item'     => (int) $item->quantity,
            ];
        });

        // ---- Compute summary totals ----
        $subtotal        = $formatted->sum('subtotal');
        $totalTax        = $formatted->sum('total_tax');
        $productDiscount = $formatted->sum('total_discount');
        $shippingCost    = $formatted->sum('shipping_cost'); // now correct
        $productShipping = $formatted->sum('product.shipping_cost'); // now correct
        $totalItems      = $formatted->sum('total_item');

        // Total = subtotal + tax - product_discount - coupon_discount + shipping shipping_area
        $total = $subtotal + $totalTax - $productDiscount - $couponDiscount + $shippingCost;

        // Shipping id (product_shippings.id) of first item, or null
        $shippingId = $formatted->isNotEmpty() ? $formatted->first()['shipping_area'] : null;

        return response()->json([
            'success' => true,
            'id'      => $requestedUserId,
            'data'    => [
                'items'   => $formatted,
                'summary' => [
                    'total'           => round($total, 2),
                    'subtotal'        => round($subtotal, 2),
                    'total_tax'       => round($totalTax, 2),
                    'total_discount'  => round($productDiscount, 2),
                    'shipping_cost'   => round($shippingCost, 2),
                    'total_item'      => $totalItems,
                    'coupon_discount' => $couponDiscount,
                    'coupon_code'     => $couponCode,
                    'shipping_id'     => $shippingId,
                    'is_has_shipping'     => $shippingCost == 0 ? false : true,
                ],
            ],
        ]);
    }

    public function index(Request $request)
    {
        $requestedUserId = $request->header('User-Id');

        $user = User::where('id', $requestedUserId)->first();

        // Eager load product + its shipping record
        $query = Cart::with(['product.shippings']);

        if ($user) {
            $cartItems = $query->where(function ($q) use ($user, $requestedUserId) {
                $q->where('user_id', $user->id)
                    ->orWhere('temp_user_id', $requestedUserId);
            })->get();
        } else {
            $cartItems = $query->where('temp_user_id', $requestedUserId)->get();
        }

        // ---- Shipping area from request ----
        $shippingArea = $request->filled('shipping_area')
            ? ShippingCost::find($request->shipping_area)
            : null;

        $isFreeOrPickup = in_array($request->shipping_type, ['free', 'pickup'], true);

        // Coupon data
        $couponDiscount = round($cartItems->sum('coupon_discount'), 2);
        $couponCode     = $cartItems->isNotEmpty() ? $cartItems->first()->coupon_code : null;

        $formatted = $cartItems->map(function ($item) use ($shippingArea, $isFreeOrPickup) {
            $product = $item->product;

            // ---- Variation handling ----
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

            // ---- Shipping logic (place() er sathe same) ----
            // 1) product_shippings.shipping_cost > 0 -> setai
            // 2) 0 / null -> cart e saved shipping_cost
            // 3) cart e o 0 -> shipping_costs.amount (area, jodi request e thake)
            $productShipping = $product?->shippings?->first()?->shipping_cost;
            $cartShipping    = (float) ($item->shipping_cost ?? 0);

            if (!empty($productShipping) && $productShipping > 0) {
                $itemShipping   = (float) $productShipping;
                $shippingSource = 'product';
            } elseif ($cartShipping > 0) {
                $itemShipping   = $cartShipping;
                $shippingSource = 'cart';
            } elseif ($shippingArea) {
                $itemShipping   = (float) $shippingArea->amount;
                $shippingSource = 'area';
            } else {
                $itemShipping   = 0.0;
                $shippingSource = 'none';
            }

            if ($isFreeOrPickup) {
                $itemShipping   = 0.0;
                $shippingSource = 'free';
            }

            // ---- Totals per item ----
            $subtotal      = (float) ($item->price * $item->quantity);
            $totalTax      = (float) ($item->tax * $item->quantity);
            $totalDiscount = (float) ($item->discount * $item->quantity);

            return [
                'id' => (int) $item->id,
                'product' => [
                    'id'        => (int) $item->product_id,
                    'name'      => $product?->name ?? 'Product not found',
                    'slug'      => $product?->slug,
                    'price'     => (float) $item->price,
                    'image'     => $product?->thumbnail ? uploaded_asset($product->thumbnail) : null,
                    'quantity'  => (int) $item->quantity,
                    'variation' => isset($variation['attribute_value'])
                        ? json_decode($variation['attribute_value'], true)
                        : null,
                    'shipping_cost' => $itemShipping,
                ],
                'subtotal'        => $subtotal,
                'total_tax'       => $totalTax,
                'total_discount'  => $totalDiscount,
                'shipping_cost'   => $itemShipping,
                'shipping_area'   => $shippingArea?->id,
                'shipping_source' => $shippingSource,
                'total_item'      => (int) $item->quantity,
            ];
        });

        // ---- Summary ----
        $subtotal        = $formatted->sum('subtotal');
        $totalTax        = $formatted->sum('total_tax');
        $productDiscount = $formatted->sum('total_discount');
        $shippingCost    = $formatted->sum('shipping_cost');
        $totalItems      = $formatted->sum('total_item');

        $total = $subtotal + $totalTax - $productDiscount - $couponDiscount + $shippingCost;

        $shippingId = $shippingArea?->id ?? null;

        return response()->json([
            'success' => true,
            'id'      => $requestedUserId,
            'data'    => [
                'items'   => $formatted,
                'summary' => [
                    'total'           => round($total, 2),
                    'subtotal'        => round($subtotal, 2),
                    'total_tax'       => round($totalTax, 2),
                    'total_discount'  => round($productDiscount, 2),
                    'shipping_cost'   => round($shippingCost, 2),
                    'total_item'      => $totalItems,
                    'coupon_discount' => $couponDiscount,
                    'coupon_code'     => $couponCode,
                    'shipping_id'     => $shippingId,
                    'is_has_shipping' => $shippingCost > 0,
                ],
            ],
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

        // 3. Find shipping cost
        $shippingCost = ShippingCost::find($shippingAreaId);

        if (!$shippingCost) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid shipping area.'
            ], 404);
        }

        $totalShipping = (float) $shippingCost->amount;

        // 4. Only get items whose shipping cost is 0 or NULL
        $itemsWithoutShipping = $cartItems->filter(function ($item) {
            return (float) $item->shipping_cost <= 0;
        });

        $itemCount = $itemsWithoutShipping->count();

        // 5. Divide shipping cost only among items without shipping cost
        if ($itemCount > 0) {

            $perItemShipping = $totalShipping / $itemCount;

            foreach ($itemsWithoutShipping as $item) {

                $item->shipping_cost = $perItemShipping;
                $item->shipping_area = $shippingAreaId;
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

        // Eager load inventory + shippings so we can read product-level shipping cost
        $product = Product::with(['inventory', 'shippings'])->find($request->product_id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $quantity = $request->quantity ?? 1;
        $variation = $request->variation ?? [];

        $regularPrice = (float) optional($product->price)->regular_price ?? 0;
        $productDiscount = (float) optional($product->price)->discount ?? 0;
        $salePrice = (float) optional($product->price)->sale_price ?? $regularPrice;
        $tax = (float) $product->tax ?? 0;

        // ---- Shipping cost from product_shippings ----
        // 0 hole cart e 0 save hobe; place() e area cost diye replace hobe.
        $productShipping = $product->shippings->first()?->shipping_cost;
        $shippingCost = (float) ($productShipping ?? 0);

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
            $cartItem->shipping_cost = $shippingCost; // refresh in case product shipping changed
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

        $inventory = $product->inventory;
        $baseSkus = array_filter([
            $product->sku,
            optional($inventory)->sku,
        ]);
        $variant = null;

        if ($cartItem->sku && !in_array($cartItem->sku, $baseSkus, true)) {
            $variant = ProductVarient::where('product_id', $product->id)
                ->where('sku', $cartItem->sku)
                ->first();

            if (!$variant && $product->variants()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Variant not found',
                ], 404);
            }
        }

        if ($variant) {
            if ($variant->quantity < $newQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock. Available: {$variant->quantity}",
                ], 400);
            }
        } else {
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
