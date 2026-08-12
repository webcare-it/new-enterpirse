<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Cart;
use App\Models\Admin\Color;
use App\Models\Admin\Coupon;
use App\Models\Admin\CouponUsage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiCheckoutController extends Controller
{
    /**
     * Get current cart
     */
    public function index(Request $request)
    {
        $userId = $request->header('User-Id');
        $cartData = $this->formatCartResponse($userId);

        return response()->json([
            'success' => true,
            'data' => $cartData,
        ]);
    }

    /**
     * Apply coupon code
     */
    public function apply_coupon_code(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:255'
        ]);

        $userId = $request->header('User-Id');
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User-Id header is required'
            ], 400);
        }

        // Get cart items
        $cartItems = $this->getCartItems($userId);
        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty'
            ], 404);
        }

        // Find coupon
        $coupon = Coupon::where('code', $request->coupon_code)->first();
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code'
            ], 404);
        }


        $userId = $this->resolveUserId($userId);
        if ($userId) {
            $existingUsage = CouponUsage::where('coupon_id', $coupon->id)
                ->where('user_id', $userId)
                ->exists();
            if ($existingUsage) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already used this coupon'
                ], 400);
            }
        }

        // Check expiry (Unix timestamps)
        $now = time();
        if ($coupon->start_date && $now < $coupon->start_date) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon is not yet active'
            ], 400);
        }
        if ($coupon->end_date && $now > $coupon->end_date) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon has expired'
            ], 400);
        }

        // Check global usage limit (count from coupon_usages)
        $usedCount = CouponUsage::where('coupon_id', $coupon->id)->count();
        if ($coupon->usage_limit && $usedCount >= $coupon->usage_limit) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon usage limit reached'
            ], 400);
        }

        // Parse coupon details (JSON)
        $details = [];
        if ($coupon->details) {
            $details = json_decode($coupon->details, true);
        }

        // Determine eligible items and subtotal based on coupon type
        $eligibleItems = collect();
        $eligibleSubtotal = 0;

        if ($coupon->type === 'cart_base') {
            $eligibleItems = $cartItems;
            $eligibleSubtotal = $cartItems->sum(fn($item) => (float) $item->price * (int) $item->quantity);

            $minBuy = $details['min_buy'] ?? 0;
            if ($eligibleSubtotal < $minBuy) {
                return response()->json([
                    'success' => false,
                    'message' => "Minimum purchase of {$minBuy} is required"
                ], 400);
            }
        } elseif ($coupon->type === 'product_base') {
            $productIds = collect($details)->pluck('product_id')->filter()->toArray();
            if (empty($productIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon has no valid product list'
                ], 400);
            }

            $eligibleItems = $cartItems->filter(fn($item) => in_array($item->product_id, $productIds));
            if ($eligibleItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This coupon does not apply to any product in your cart'
                ], 400);
            }

            $eligibleSubtotal = $eligibleItems->sum(fn($item) => (float) $item->price * (int) $item->quantity);

            $minBuy = $details['min_buy'] ?? 0;
            if ($eligibleSubtotal < $minBuy) {
                return response()->json([
                    'success' => false,
                    'message' => "Minimum purchase of {$minBuy} on eligible products is required"
                ], 400);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'This coupon type is not supported'
            ], 400);
        }

        // Calculate total discount
        $discountValue = (float) $coupon->discount;
        $maxDiscount = (float) ($details['max_discount'] ?? PHP_INT_MAX);

        if ($coupon->discount_type === 'percent') {
            $totalDiscount = ($discountValue / 100) * $eligibleSubtotal;
            $totalDiscount = min($totalDiscount, $maxDiscount);
        } else {
            $totalDiscount = min($discountValue, $maxDiscount);
            $totalDiscount = min($totalDiscount, $eligibleSubtotal);
        }

        // Distribute discount proportionally among eligible items
        $discountPerItem = [];
        foreach ($cartItems as $item) {
            if (!$eligibleItems->contains('id', $item->id)) {
                $discountPerItem[$item->id] = 0;
                continue;
            }
            $itemTotal = (float) $item->price * (int) $item->quantity;
            if ($eligibleSubtotal > 0) {
                $discount = ($itemTotal / $eligibleSubtotal) * $totalDiscount;
            } else {
                $discount = 0;
            }
            $discountPerItem[$item->id] = round($discount, 2);
        }

        // Atomic update: clear old coupon, apply new one, record usage
        DB::transaction(function () use ($cartItems, $discountPerItem, $coupon, $userId) {
            // Clear existing coupon data from all cart items
            foreach ($cartItems as $item) {
                $item->coupon_discount = 0;
                $item->coupon_code = null;
                $item->coupon_applied = 0;
                $item->save();
            }

            // Apply new coupon
            foreach ($cartItems as $item) {
                $item->coupon_discount = $discountPerItem[$item->id] ?? 0;
                $item->coupon_code = $coupon->code;
                $item->coupon_applied = 1;
                $item->save();
            }

            // Record usage in coupon_usages
            CouponUsage::create([
                'coupon_id' => $coupon->id,
                'user_id'   => $this->resolveUserId($userId),
                'used_at'   => now(),
            ]);
        });

        // Return updated cart
        $cartData = $this->formatCartResponse($userId);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully',
        ]);
    }

    /**
     * Remove coupon
     */
    public function remove_coupon_code(Request $request)
    {
        $userId = $request->header('User-Id');
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'User-Id header is required'], 400);
        }

        $cartItems = $this->getCartItems($userId);
        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty'
            ], 404);
        }

        $appliedCouponCode = $cartItems->first()->coupon_code;

        DB::transaction(function () use ($cartItems, $appliedCouponCode, $userId) {
            // Clear coupon data from cart
            foreach ($cartItems as $item) {
                $item->discount = 0;
                $item->coupon_code = null;
                $item->coupon_applied = 0;
                $item->save();
            }

            // Delete the usage record for this user and coupon
            if ($appliedCouponCode) {
                $coupon = Coupon::where('code', $appliedCouponCode)->first();
                if ($coupon) {
                    CouponUsage::where('coupon_id', $coupon->id)
                        ->where('user_id', $this->resolveUserId($userId))
                        ->delete();
                }
            }
        });

        $cartData = $this->formatCartResponse($userId);

        return response()->json([
            'success' => true,
            'message' => __('Coupon removed successfully')
        ]);
    }

    // -------------------------------------------------------------------------
    //  Helper Methods
    // -------------------------------------------------------------------------

    private function getCartItems($userId)
    {
        $user = User::where('id', $userId)->first();
        $query = Cart::with('product');
        if ($user) {
            $query->where('user_id', $user->id);
        } else {
            $query->where('temp_user_id', $userId);
        }
        return $query->get();
    }

    private function resolveUserId($userId)
    {
        $user = User::where('id', $userId)->first();
        return $user ? $user->id : null;
    }

    private function formatCartResponse($userId)
    {
        $cartItems = $this->getCartItems($userId);

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

            $subtotal = (float) ($item->price * $item->quantity);
            $totalTax = (float) ($item->tax * $item->quantity);
            $totalDiscount = (float) ($item->discount * $item->quantity);
            $shippingCost = (float) $item->shipping_cost;
            $shippingArea = $item->shipping_area;

            return [
                'id' => (int) $item->id,
                'product' => [
                    'id' => (int) $item->product_id,
                    'name' => $product?->name ?? 'Product not found',
                    'slug' => $product?->slug,
                    'price' => (float) $item->price,
                    'image' => $product?->thumbnail
                        ? uploaded_asset($product->thumbnail)
                        : null,
                    'quantity' => (int) $item->quantity,
                    'variation' => $variation,
                ],
                'subtotal' => $subtotal,
                'total_tax' => $totalTax,
                'total_discount' => $totalDiscount,
                'shipping_cost' => $shippingCost,
                'shipping_area' => $shippingArea,
                'total_item' => (int) $item->quantity,
            ];
        });

        $total = $formatted->sum('subtotal')
            + $formatted->sum('total_tax')
            - $formatted->sum('total_discount')
            + $formatted->sum('shipping_cost');

        return [
            'items' => $formatted,
            'summary' => [
                'total' => round($total, 2),
                'subtotal' => round($formatted->sum('subtotal'), 2),
                'total_tax' => round($formatted->sum('total_tax'), 2),
                'total_discount' => round($formatted->sum('total_discount'), 2),
                'shipping_cost' => round($formatted->sum('shipping_cost'), 2),
                'total_item' => $formatted->sum('total_item'),
                'shipping_id' => $formatted->isNotEmpty() ? $formatted->first()['shipping_area'] : null,
            ]
        ];
    }
}
