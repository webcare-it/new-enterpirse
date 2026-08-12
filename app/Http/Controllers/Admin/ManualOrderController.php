<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Cart;
use App\Models\Admin\Coupon;
use App\Models\Admin\Customer;
use App\Models\Admin\Category;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use App\Models\Admin\ProductInventory;
use App\Models\Admin\ProductVarient;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManualOrderController extends Controller
{
    /**
     * Display manual order creation page
     */
    public function create_manual_order(Request $request)
    {
        $categories = Category::oldest('position')->get();
        $coupons = Coupon::where('start_date', '<=', now()->timestamp)
            ->where('end_date', '>=', now()->timestamp)
            ->get();
        $cart = session()->get('manual_order_cart', []);
        $cartTotal = $this->calculateCartTotal($cart);
        $cartSubtotal = $this->calculateCartSubtotal($cart);

        return view('backend.sales.manual_order.index', compact('categories', 'cart', 'cartTotal', 'cartSubtotal', 'coupons'));
    }

    /**
     * Get products by category with variants
     */
    public function getProducts(Request $request)
    {
        $categoryId = $request->category_id;
        $search = $request->search;
        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 40;

        $query = Product::with(['variants', 'category', 'brand']);

        if ($categoryId && $categoryId != 'all') {
            $query->where('category_id', $categoryId);
        }

        if ($search && $search != '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $total = $query->count();
        $totalPages = ceil($total / $perPage);

        $products = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $html = '';
        if ($products->count() > 0) {
            foreach ($products as $product) {
                $html .= view('backend.sales.manual_order.partials.product_card', compact('product'))->render();
            }
        } else {
            $html = '<div class="col-12 text-center py-5">
                <i class="las la-box-open fs-60 text-muted"></i>
                <h5 class="text-muted mt-3">' . translate('No products found') . '</h5>
             </div>';
        }

        return response()->json([
            'html' => $html,
            'total' => $total,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'per_page' => $perPage
        ]);
    }

    public function addVariantToCart(Request $request)
    {
        try {
            $request->validate([
                'variant_id' => 'required|integer|min:1',
                'quantity' => 'required|integer|min:1'
            ]);

            // Find the variant
            $variant = ProductVarient::with('product')->find($request->variant_id);

            if (!$variant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Variant not found! ID: ' . $request->variant_id
                ], 404);
            }

            // Check stock availability
            $availableStock = $variant->quantity ?? 0;

            if ($availableStock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock! Only ' . $availableStock . ' items available.'
                ], 400);
            }

            // Get current cart
            $cart = session()->get('manual_order_cart', []);
            $cartKey = 'variant_' . $variant->id;

            // Parse variant attributes
            $variantAttributes = [];
            $variantName = '';

            if ($variant->attribute) {
                $variantAttributes = json_decode($variant->attribute, true);
                if (is_array($variantAttributes)) {
                    $variantName = '';
                    foreach ($variantAttributes as $key => $value) {
                        $variantName .= $key . ': ' . $value . ' ';
                    }
                    $variantName = trim($variantName);
                }
            }

            // Get product details
            $productName = $variant->product->name ?? 'Product';
            $productThumbnail = $variant->product->thumbnail ?? null;
            $variantPrice = floatval($variant->price ?? 0);
            $variantSku = $variant->sku ?? 'N/A';

            // Add or update cart item - Make sure ALL keys are included
            if (isset($cart[$cartKey])) {
                $newQuantity = $cart[$cartKey]['quantity'] + $request->quantity;
                $cart[$cartKey]['quantity'] = min($newQuantity, $availableStock);
            } else {
                $cart[$cartKey] = [
                    'key' => $cartKey,
                    'id' => $variant->product_id,
                    'variant_id' => $variant->id,
                    'name' => $productName . ($variantName ? ' (' . $variantName . ')' : ''),
                    'price' => $variantPrice,
                    'quantity' => intval($request->quantity),
                    'thumbnail' => $productThumbnail,
                    'sku' => $variantSku,
                    'max_stock' => $availableStock,
                    'variant_attributes' => $variantAttributes,
                    'has_variant' => true  // MAKE SURE THIS KEY EXISTS
                ];
            }

            // Save cart to session
            session()->put('manual_order_cart', $cart);

            // Calculate totals
            $cartTotal = $this->calculateCartTotal($cart);
            $cartSubtotal = $this->calculateCartSubtotal($cart);

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart' => $this->getCartHtml(),
                'total' => $cartTotal,
                'subtotal' => $cartSubtotal,
                'cart_count' => count($cart)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in addVariantToCart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add simple product to cart
     */
    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric'
            ]);

            // Find the product
            $product = Product::with('inventory')->find($request->product_id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found!'
                ], 404);
            }

            $inventory = $product->inventory;

            // Check if product has variants (should not be added here)
            if ($product->variants && $product->variants->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'This product has variants. Please select a variant.'
                ], 400);
            }

            // Check stock availability
            if (!$inventory || $inventory->stock < $request->quantity) {
                $availableStock = $inventory ? $inventory->stock : 0;
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock! Only ' . $availableStock . ' items available.'
                ], 400);
            }

            // Get current cart
            $cart = session()->get('manual_order_cart', []);
            $cartKey = 'product_' . $request->product_id;

            // Get product thumbnail
            $thumbnail = $product->thumbnail ?? null;

            // Add or update cart item
            if (isset($cart[$cartKey])) {
                $newQuantity = $cart[$cartKey]['quantity'] + $request->quantity;
                if ($newQuantity > $inventory->stock) {
                    $cart[$cartKey]['quantity'] = $inventory->stock;
                } else {
                    $cart[$cartKey]['quantity'] = $newQuantity;
                }
            } else {
                $cart[$cartKey] = [
                    'key' => $cartKey,
                    'id' => $product->id,
                    'variant_id' => null,
                    'name' => $product->name,
                    'price' => floatval($request->price),
                    'quantity' => intval($request->quantity),
                    'thumbnail' => $thumbnail,
                    'sku' => $inventory->sku ?? 'N/A',
                    'max_stock' => intval($inventory->stock),
                    'variant_attributes' => null,
                    'has_variant' => false
                ];
            }

            // Save cart to session
            session()->put('manual_order_cart', $cart);

            // Calculate totals
            $cartTotal = $this->calculateCartTotal($cart);
            $cartSubtotal = $this->calculateCartSubtotal($cart);

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart' => $this->getCartHtml(),
                'total' => $cartTotal,
                'subtotal' => $cartSubtotal,
                'cart_count' => count($cart)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getCartHtml()
    {
        $cart = session()->get('manual_order_cart', []);

        if (empty($cart)) {
            return '<tr><td colspan="5" class="text-center py-4">
                    <i class="las la-shopping-cart fs-40 text-muted"></i>
                    <p class="text-muted mb-0">Cart is empty</p>
                  </div>
                </div>';
        }

        $html = '';
        foreach ($cart as $key => $item) {
            // Safely get values with defaults
            $thumbnail = isset($item['thumbnail']) && $item['thumbnail']
                ? uploaded_asset($item['thumbnail'])
                : asset('assets/img/placeholder.jpg');

            $name = $item['name'] ?? 'Unknown Product';
            $sku = $item['sku'] ?? 'N/A';
            $price = $item['price'] ?? 0;
            $quantity = $item['quantity'] ?? 1;
            $maxStock = $item['max_stock'] ?? 999;
            $hasVariant = $item['has_variant'] ?? false;
            $variantAttributes = $item['variant_attributes'] ?? [];

            $html .= '<tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="' . $thumbnail . '" 
                                 class="size-40px img-fit mr-2" 
                                 style="border-radius: 8px;">
                            <div>
                                <strong>' . e($name) . '</strong>
                                <br>
                                <small class="text-muted">SKU: ' . e($sku) . '</small>';

            if ($hasVariant && !empty($variantAttributes)) {
                $html .= '<br><small class="text-info">';
                foreach ($variantAttributes as $attr => $val) {
                    $html .= $attr . ': ' . $val . ' ';
                }
                $html .= '</small>';
            }

            $html .= '</div>
                        </div>
                     </div>
                    <td>৳' . number_format($price, 2) . '</div>
                    <td>
                        <input type="number" class="form-control form-control-sm cart-qty" 
                               data-key="' . e($key) . '" 
                               value="' . $quantity . '" 
                               min="1" max="' . $maxStock . '"
                               style="width: 80px;">
                     </div>
                    <td class="text-right">
                        <strong>৳' . number_format($price * $quantity, 2) . '</strong>
                     </div>
                    <td class="text-center">
                        <button class="btn btn-sm btn-icon btn-danger remove-from-cart" 
                                data-key="' . e($key) . '">
                            <i class="las la-trash"></i>
                        </button>
                     </div>
                 </div>';
        }

        return $html;
    }

    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('manual_order_cart', []);

        if (isset($cart[$request->key])) {
            if ($request->quantity > $cart[$request->key]['max_stock']) {
                $cart[$request->key]['quantity'] = $cart[$request->key]['max_stock'];
            } else {
                $cart[$request->key]['quantity'] = $request->quantity;
            }
            session()->put('manual_order_cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cart' => $this->getCartHtml(),
            'total' => $this->calculateCartTotal($cart),
            'subtotal' => $this->calculateCartSubtotal($cart)
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'key' => 'required|string'
        ]);

        $cart = session()->get('manual_order_cart', []);

        if (isset($cart[$request->key])) {
            unset($cart[$request->key]);
            session()->put('manual_order_cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cart' => $this->getCartHtml(),
            'total' => $this->calculateCartTotal($cart),
            'subtotal' => $this->calculateCartSubtotal($cart)
        ]);
    }

    /**
     * Clear cart
     */
    public function clearCart()
    {
        session()->forget('manual_order_cart');
        session()->forget('manual_order_coupon');
        session()->forget('manual_order_discount');

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared!',
            'cart' => $this->getCartHtml(),
            'total' => 0,
            'subtotal' => 0
        ]);
    }

    /**
     * Get cart contents
     */
    public function getCart()
    {
        $cart = session()->get('manual_order_cart', []);

        return response()->json([
            'success' => true,
            'cart' => $this->getCartHtml(),
            'total' => $this->calculateCartTotal($cart),
            'subtotal' => $this->calculateCartSubtotal($cart)
        ]);
    }

    /**
     * Search customer
     */
    public function searchCustomer(Request $request)
    {
        $search = $request->get('q', '');

        $customers = User::where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('phone', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name', 'email', 'phone']);

        $results = [];
        foreach ($customers as $customer) {
            $results[] = [
                'id' => $customer->id,
                'text' => $customer->name . ' (' . $customer->email . ')',
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone
            ];
        }

        return response()->json($results);
    }

    /**
     * Calculate cart subtotal
     */
    private function calculateCartSubtotal($cart)
    {
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return $subtotal;
    }

    /**
     * Calculate cart total (subtotal - discount - coupon)
     */
    private function calculateCartTotal($cart)
    {
        $subtotal = $this->calculateCartSubtotal($cart);
        $total = $subtotal;

        // Apply coupon discount
        if (session()->has('manual_order_coupon')) {
            $coupon = session()->get('manual_order_coupon');
            $total -= $coupon['discount_amount'];
        }

        // Apply manual discount
        if (session()->has('manual_order_discount')) {
            $discount = session()->get('manual_order_discount');
            $total -= $discount['amount'];
        }

        return max(0, $total);
    }

    /**
     * Get total coupon discount
     */
    private function getCouponDiscount()
    {
        if (session()->has('manual_order_coupon')) {
            $coupon = session()->get('manual_order_coupon');
            return $coupon['discount_amount'];
        }
        return 0;
    }

    /**
     * Get manual discount amount
     */
    private function getManualDiscount()
    {
        if (session()->has('manual_order_discount')) {
            $discount = session()->get('manual_order_discount');
            return $discount['amount'];
        }
        return 0;
    }


    /**
     * Generate unique order code
     */
    private function generateOrderCode()
    {
        return 'ORD-' . strtoupper(uniqid()) . '-' . rand(1000, 9999);
    }

    /**
     * Display the specified order
     */
    public function show($id)
    {
        $order = Order::with(['orderDetails', 'orderDetails.product'])
            ->findOrFail($id);

        // Get customer details
        $customer = null;
        if ($order->user_id) {
            $customer = User::find($order->user_id);
        }

        // Calculate order statistics
        $totalItems = $order->orderDetails->sum('quantity');
        $subtotal = $order->orderDetails->sum(function ($detail) {
            return $detail->price * $detail->quantity;
        });
        $totalTax = $order->orderDetails->sum('tax');
        $totalShipping = $order->orderDetails->sum('shipping_cost');

        // Get order timeline
        $timeline = $this->getOrderTimeline($order);

        return view('backend.sales.all_orders.show', compact('order', 'customer', 'totalItems', 'subtotal', 'totalTax', 'totalShipping', 'timeline'));
    }

    /**
     * Update order status (delivery or payment)
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|string',
            'type' => 'required|in:delivery,payment'
        ]);

        try {
            $order = Order::findOrFail($request->order_id);

            if ($request->type == 'delivery') {
                $order->delivery_status = $request->status;
                $message = 'Delivery status updated successfully!';
            } else {
                $order->payment_status = $request->status;
                $message = 'Payment status updated successfully!';
            }

            $order->save();

            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $request->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order timeline
     */
    private function getOrderTimeline($order)
    {
        $timeline = [];

        // Order placed
        $timeline[] = [
            'status' => 'Order Placed',
            'date' => $order->created_at,
            'icon' => 'la la-shopping-cart',
            'color' => 'primary',
            'description' => 'Order has been placed successfully'
        ];

        // Payment confirmed
        if ($order->payment_status == 'paid') {
            $timeline[] = [
                'status' => 'Payment Confirmed',
                'date' => $order->updated_at,
                'icon' => 'la la-credit-card',
                'color' => 'success',
                'description' => 'Payment has been confirmed'
            ];
        }

        // Order confirmed
        if ($order->delivery_status == 'confirmed' || $order->delivery_status == 'processing') {
            $timeline[] = [
                'status' => 'Order Confirmed',
                'date' => $order->updated_at,
                'icon' => 'la la-check-circle',
                'color' => 'info',
                'description' => 'Order has been confirmed by admin'
            ];
        }

        // Shipped
        if ($order->delivery_status == 'shipped') {
            $timeline[] = [
                'status' => 'Shipped',
                'date' => $order->updated_at,
                'icon' => 'la la-truck',
                'color' => 'warning',
                'description' => 'Order has been shipped'
            ];
        }

        // Delivered
        if ($order->delivery_status == 'delivered') {
            $timeline[] = [
                'status' => 'Delivered',
                'date' => $order->updated_at,
                'icon' => 'la la-home',
                'color' => 'success',
                'description' => 'Order has been delivered to customer'
            ];
        }

        // Cancelled
        if ($order->delivery_status == 'cancelled') {
            $timeline[] = [
                'status' => 'Cancelled',
                'date' => $order->updated_at,
                'icon' => 'la la-times-circle',
                'color' => 'danger',
                'description' => 'Order has been cancelled'
            ];
        }

        return $timeline;
    }

    /**
     * Apply coupon to cart
     */
    public function applyCoupon(Request $request)
    {
        try {
            $request->validate([
                'coupon_code' => 'required|string'
            ]);

            $coupon = Coupon::where('code', $request->coupon_code)
                ->where('start_date', '<=', now()->timestamp)
                ->where('end_date', '>=', now()->timestamp)
                ->first();

            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired coupon code!'
                ], 400);
            }

            $cart = session()->get('manual_order_cart', []);

            if (empty($cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty!'
                ], 400);
            }

            $cartTotal = $this->calculateCartTotal($cart);
            $discountAmount = 0;
            $details = json_decode($coupon->details, true);

            // Apply coupon based on type
            if ($coupon->type == 'cart_base') {
                // Cart base coupon
                $minBuy = $details['min_buy'] ?? 0;
                $maxDiscount = $details['max_discount'] ?? null;

                if ($cartTotal >= $minBuy) {
                    if ($coupon->discount_type == 'percent') {
                        $discountAmount = ($cartTotal * $coupon->discount / 100);
                        if ($maxDiscount && $discountAmount > $maxDiscount) {
                            $discountAmount = $maxDiscount;
                        }
                    } else {
                        $discountAmount = $coupon->discount;
                        if ($discountAmount > $cartTotal) {
                            $discountAmount = $cartTotal;
                        }
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Minimum purchase amount of ৳' . number_format($minBuy, 2) . ' required!'
                    ], 400);
                }
            } elseif ($coupon->type == 'product_base') {
                // Product base coupon
                $applicableProducts = [];
                foreach ($details as $item) {
                    $applicableProducts[] = $item['product_id'];
                }

                $applicableCartTotal = 0;
                foreach ($cart as $item) {
                    if (in_array($item['id'], $applicableProducts)) {
                        $applicableCartTotal += $item['price'] * $item['quantity'];
                    }
                }

                if ($applicableCartTotal > 0) {
                    if ($coupon->discount_type == 'percent') {
                        $discountAmount = ($applicableCartTotal * $coupon->discount / 100);
                    } else {
                        $discountAmount = $coupon->discount;
                        if ($discountAmount > $applicableCartTotal) {
                            $discountAmount = $applicableCartTotal;
                        }
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No applicable products in cart for this coupon!'
                    ], 400);
                }
            }

            // Store coupon in session
            session()->put('manual_order_coupon', [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'discount_amount' => $discountAmount,
                'discount_type' => $coupon->discount_type,
                'original_discount' => $coupon->discount
            ]);

            $newTotal = $cartTotal - $discountAmount;
            $cartSubtotal = $this->calculateCartSubtotal($cart);

            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully!',
                'discount_amount' => $discountAmount,
                'cart_total' => $newTotal,
                'cart_subtotal' => $cartSubtotal,
                'coupon_code' => $coupon->code,
                'coupon_discount' => $discountAmount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove applied coupon
     */
    public function removeCoupon(Request $request)
    {
        try {
            session()->forget('manual_order_coupon');

            $cart = session()->get('manual_order_cart', []);
            $cartTotal = $this->calculateCartTotal($cart);
            $cartSubtotal = $this->calculateCartSubtotal($cart);

            return response()->json([
                'success' => true,
                'message' => 'Coupon removed successfully!',
                'cart_total' => $cartTotal,
                'cart_subtotal' => $cartSubtotal
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Apply manual discount
     */
    public function applyManualDiscount(Request $request)
    {
        try {
            $request->validate([
                'discount_amount' => 'required|numeric|min:0',
                'discount_type' => 'required|in:flat,percent'
            ]);

            $cart = session()->get('manual_order_cart', []);

            if (empty($cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty!'
                ], 400);
            }

            $cartTotal = $this->calculateCartTotal($cart);
            $discountAmount = $request->discount_amount;

            if ($request->discount_type == 'percent') {
                if ($discountAmount > 100) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Percentage discount cannot exceed 100%!'
                    ], 400);
                }
                $discountAmount = ($cartTotal * $discountAmount / 100);
            }

            if ($discountAmount > $cartTotal) {
                $discountAmount = $cartTotal;
            }

            // Store manual discount in session
            session()->put('manual_order_discount', [
                'amount' => $discountAmount,
                'original_amount' => $request->discount_amount,
                'type' => $request->discount_type
            ]);

            $newTotal = $cartTotal - $discountAmount;

            return response()->json([
                'success' => true,
                'message' => 'Discount applied successfully!',
                'discount_amount' => $discountAmount,
                'cart_total' => $newTotal,
                'cart_subtotal' => $cartTotal
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove manual discount
     */
    public function removeManualDiscount(Request $request)
    {
        try {
            session()->forget('manual_order_discount');

            $cart = session()->get('manual_order_cart', []);
            $cartTotal = $this->calculateCartTotal($cart);

            // Re-apply coupon if exists
            $couponDiscount = 0;
            if (session()->has('manual_order_coupon')) {
                $coupon = session()->get('manual_order_coupon');
                $couponDiscount = $coupon['discount_amount'];
            }

            return response()->json([
                'success' => true,
                'message' => 'Discount removed successfully!',
                'cart_total' => $cartTotal,
                'coupon_discount' => $couponDiscount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Place order
     */
    public function placeOrder(Request $request)
    {
        try {
            $request->validate([
                'customer_id' => 'nullable|exists:users,id',
                'customer_name' => 'required_if:customer_id,null|string|max:255',
                'customer_email' => 'nullable|email|max:255',
                'customer_phone' => 'required|string|max:20',
                'shipping_address' => 'required|string',
                'payment_type' => 'required|string',
                'notes' => 'nullable|string',
                'city' => 'nullable|string|max:255',
                'postal_code' => 'nullable|string|max:20',
            ]);

            $cart = session()->get('manual_order_cart', []);
            if (empty($cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty!'
                ], 400);
            }

            DB::beginTransaction();

            $subtotal = $this->calculateCartSubtotal($cart);
            $couponDiscount = $this->getCouponDiscount();
            $manualDiscount = $this->getManualDiscount();
            $totalDiscount = $couponDiscount + $manualDiscount;
            $grandTotal = $subtotal - $totalDiscount;

            // --- 1. Get or create User ---
            $user = null;
            if ($request->customer_id) {
                $user = User::find($request->customer_id);
            }

            if (!$user && $request->customer_name) {
                $user = User::create([
                    'name' => $request->customer_name,
                    'email' => $request->customer_email ?? 'guest_' . time() . '@example.com',
                    'phone' => $request->customer_phone,
                    'password' => bcrypt(uniqid()),
                    'email_verified_at' => now()
                ]);
            }

            if (!$user) {
                throw new \Exception('Customer information is required.');
            }

            // --- 2. Find or create Customer record for this user ---
            $customer = Customer::where('user_id', $user->id)->first();

            if (!$customer) {
                // Create new customer
                $customer = new Customer();
                $customer->user_id = $user->id;
                $customer->phone = $request->customer_phone;
                $customer->address = $request->shipping_address;
                $customer->city = $request->city ?? null;
                $customer->postal_code = $request->postal_code ?? null;
                $customer->balance = 0;
                $customer->point = 0;
                $customer->banned = false;
                $customer->save();
            } else {
                // Update existing customer with latest info
                $customer->phone = $request->customer_phone;
                $customer->address = $request->shipping_address;
                $customer->city = $request->city ?? $customer->city;
                $customer->postal_code = $request->postal_code ?? $customer->postal_code;
                $customer->save();
            }

            // Generate unique order code
            $orderCode = $this->generateOrderCode();

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'shipping_address' => $request->shipping_address,
                'delivery_status' => 'pending',
                'payment_type' => $request->payment_type,
                'manual_payment' => $request->payment_type == 'manual' ? true : false,
                'payment_status' => 'unpaid',
                'grand_total' => $grandTotal,
                'shipping_cost' => 0,
                'coupon_discount' => $couponDiscount,
                'discount' => $manualDiscount,
                'code' => $orderCode,
                'notes' => $request->notes,
                'date' => now()->timestamp,
                'viewed' => false,
                'delivery_viewed' => false,
                'payment_status_viewed' => false,
                'commission_calculated' => false,
                'shipping_type' => 'home_delivery',
                'order_type' => 'physical'
            ]);

            // Create order details and update stock
            foreach ($cart as $item) {
                $variation = null;
                if ($item['has_variant'] && !empty($item['variant_attributes'])) {
                    $variation = json_encode($item['variant_attributes']);
                }

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'variation' => $variation,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'tax' => 0,
                    'shipping_cost' => 0,
                    'payment_status' => 'unpaid',
                    'delivery_status' => 'pending',
                    'shipping_type' => 'home_delivery'
                ]);

                // Update stock
                if ($item['has_variant'] && isset($item['variant_id'])) {
                    $variant = ProductVarient::find($item['variant_id']);
                    if ($variant) {
                        $variant->decrement('quantity', $item['quantity']);
                    }
                } else {
                    $inventory = ProductInventory::where('product_id', $item['id'])->first();
                    if ($inventory) {
                        $inventory->decrement('stock', $item['quantity']);
                    }
                }
            }

            DB::commit();

            // Clear session data
            session()->forget('manual_order_cart');
            session()->forget('manual_order_coupon');
            session()->forget('manual_order_discount');

            // Prepare customer data response
            $customerData = [
                'id'            => $customer->id,
                'user_id'       => $customer->user_id,
                'name'          => $user->name,
                'email'         => $user->email,
                'phone'         => $customer->phone,
                'address'       => $customer->address,
                'city'          => $customer->city,
                'postal_code'   => $customer->postal_code,
                'balance'       => $customer->balance,
                'point'         => $customer->point,
                'banned'        => $customer->banned,
                'customer_status' => $customer->customer_status,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
                'order_code' => $order->code,
                'customer' => $customerData,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in placeOrder: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Search products by name or SKU for Select2
     */
    public function searchProducts(Request $request)
    {
        try {
            $search = $request->get('q', '');

            if (empty($search)) {
                return response()->json([]);
            }

            $products = Product::with(['inventory', 'price', 'variants'])
                ->where('is_published', 1)
                ->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhereHas('inventory', function ($q) use ($search) {
                            $q->where('sku', 'like', "%{$search}%");
                        });
                })
                ->latest()
                ->limit(20)
                ->get();

            $results = [];
            foreach ($products as $product) {
                $stock = $product->inventory->stock ?? 0;
                $price = $product->price->regular_price ?? 0;
                $salePrice = $product->price->sale_price ?? null;

                // Calculate display price
                $displayPrice = ($salePrice && $salePrice < $price) ? $salePrice : $price;

                // Get variants
                $variants = [];
                if ($product->variants && $product->variants->count() > 0) {
                    foreach ($product->variants as $variant) {
                        $variants[] = [
                            'id' => $variant->id,
                            'attribute_value' => $variant->attribute_value ?? 'Variant',
                            'price' => floatval($variant->price ?? 0),
                            'quantity' => intval($variant->quantity ?? 0),
                            'stock' => intval($variant->quantity ?? 0),
                            'sku' => $variant->sku ?? 'N/A'
                        ];
                    }
                }

                $results[] = [
                    'id' => $product->id,
                    'text' => $product->name . ' (SKU: ' . ($product->inventory->sku ?? 'N/A') . ')',
                    'name' => $product->name,
                    'sku' => $product->inventory->sku ?? 'N/A',
                    'price' => $displayPrice,
                    'regular_price' => $price,
                    'stock' => $stock,
                    'thumbnail' => $product->thumbnail,
                    'has_variants' => count($variants) > 0,
                    'variants' => $variants
                ];
            }

            return response()->json($results);
        } catch (\Exception $e) {
            \Log::error('Error in searchProducts: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
