<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVarient;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Log;
use Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function manualOrders(Request $request)
    {
        $query = Order::where('order_type', 'physical')->with(['user', 'orderDetails']);
        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('shipping_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by delivery status
        if ($request->has('delivery_status') && $request->delivery_status != '') {
            $query->where('delivery_status', $request->delivery_status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by payment type
        if ($request->has('payment_type') && $request->payment_type != '') {
            $query->where('payment_type', $request->payment_type);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'grand_total_asc':
                $query->orderBy('grand_total', 'asc');
                break;
            case 'grand_total_desc':
                $query->orderBy('grand_total', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $orders = $query->paginate(15)->appends($request->query());

        // Statistics
        $totalOrders = Order::where('order_type', 'physical')->count();
        $totalRevenue = Order::where('order_type', 'physical')->where('payment_status', 'paid')->sum('grand_total');
        $pendingOrders = Order::where('order_type', 'physical')->where('delivery_status', 'pending')->count();
        $deliveredOrders = Order::where('order_type', 'physical')->where('delivery_status', 'delivered')->count();

        return view('backend.sales.all_orders.manual-orders', compact('orders', 'totalOrders', 'totalRevenue', 'pendingOrders', 'deliveredOrders'));
    }

    /**
     * Display a listing of orders
     */
    public function deliveredOrders(Request $request)
    {
        $query = Order::where('delivery_status', 'delivered')->with(['user', 'orderDetails']);
        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('shipping_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by delivery status
        if ($request->has('delivery_status') && $request->delivery_status != '') {
            $query->where('delivery_status', $request->delivery_status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by payment type
        if ($request->has('payment_type') && $request->payment_type != '') {
            $query->where('payment_type', $request->payment_type);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'grand_total_asc':
                $query->orderBy('grand_total', 'asc');
                break;
            case 'grand_total_desc':
                $query->orderBy('grand_total', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $orders = $query->paginate(15)->appends($request->query());
        // Statistics
        $totalOrders = Order::where('delivery_status', 'delivered')->count();
        $totalRevenue = Order::where('delivery_status', 'delivered')->where('payment_status', 'paid')->sum('grand_total');
        $pendingOrders = Order::where('delivery_status', 'pending')->count();
        $deliveredOrders = Order::where('delivery_status', 'delivered')->count();

        return view('backend.sales.all_orders.delivery-orders', compact('orders', 'totalOrders', 'totalRevenue', 'pendingOrders', 'deliveredOrders'));
    }

    /**
     * Display a listing of orders
     */
    public function shippedOrders(Request $request)
    {
        $query = Order::where('delivery_status', 'shipped')->with(['user', 'orderDetails']);
        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('shipping_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by delivery status
        if ($request->has('delivery_status') && $request->delivery_status != '') {
            $query->where('delivery_status', $request->delivery_status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by payment type
        if ($request->has('payment_type') && $request->payment_type != '') {
            $query->where('payment_type', $request->payment_type);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'grand_total_asc':
                $query->orderBy('grand_total', 'asc');
                break;
            case 'grand_total_desc':
                $query->orderBy('grand_total', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $orders = $query->paginate(15)->appends($request->query());

        // Statistics
        $totalOrders = Order::where('delivery_status', 'shipped')->count();
        $totalRevenue = Order::where('delivery_status', 'shipped')->where('payment_status', 'paid')->sum('grand_total');
        $pendingOrders = Order::where('delivery_status', 'pending')->count();
        $deliveredOrders = Order::where('delivery_status', 'shipped')->count();

        return view('backend.sales.all_orders.delivery-orders', compact('orders', 'totalOrders', 'totalRevenue', 'pendingOrders', 'deliveredOrders'));
    }

    /**
     * Display a listing of orders
     */
    public function canceledOrders(Request $request)
    {
        $query = Order::where('delivery_status', 'cancelled')->with(['user', 'orderDetails']);

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('shipping_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by delivery status
        if ($request->has('delivery_status') && $request->delivery_status != '') {
            $query->where('delivery_status', $request->delivery_status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by payment type
        if ($request->has('payment_type') && $request->payment_type != '') {
            $query->where('payment_type', $request->payment_type);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'grand_total_asc':
                $query->orderBy('grand_total', 'asc');
                break;
            case 'grand_total_desc':
                $query->orderBy('grand_total', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $orders = $query->paginate(15)->appends($request->query());

        // Statistics
        $totalOrders = Order::where('delivery_status', 'cancelled')->count();
        $totalRevenue = Order::where('delivery_status', 'cancelled')->where('payment_status', 'paid')->sum('grand_total');
        $pendingOrders = Order::where('delivery_status', 'pending')->count();
        $deliveredOrders = Order::where('delivery_status', 'cancelled')->count();

        return view('backend.sales.all_orders.delivery-orders', compact('orders', 'totalOrders', 'totalRevenue', 'pendingOrders', 'deliveredOrders'));
    }



    /**
     * Display a listing of orders
     */
    public function orders(Request $request)
    {
        $query = Order::with(['user', 'orderDetails']);

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('shipping_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by delivery status
        if ($request->has('delivery_status') && $request->delivery_status != '') {
            $query->where('delivery_status', $request->delivery_status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by payment type
        if ($request->has('payment_type') && $request->payment_type != '') {
            $query->where('payment_type', $request->payment_type);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'grand_total_asc':
                $query->orderBy('grand_total', 'asc');
                break;
            case 'grand_total_desc':
                $query->orderBy('grand_total', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $orders = $query->paginate(15)->appends($request->query());

        // Statistics
        $totalOrders = Order::count();

        $totalRevenue = Order::where('payment_status', 'paid')
            ->sum(DB::raw('grand_total - shipping_cost'));


        $pendingOrders = Order::where('delivery_status', 'pending')->count();
        $deliveredOrders = Order::where('delivery_status', 'delivered')->count();

        return view('backend.sales.all_orders.all-order', compact('orders', 'totalOrders', 'totalRevenue', 'pendingOrders', 'deliveredOrders'));
    }
    /**
     * Display a listing of orders
     */
    public function dropshippers(Request $request)
    {
        $query = Order::with('dropshipper:id,name')
            ->where('order_type', 'Dropshipping')
            ->with(['user', 'orderDetails']);


        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('shipping_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->has('dropshipper_id') && $request->dropshipper_id != '') {
            $query->where('dropshipper_id', $request->dropshipper_id);
        }


        if ($request->has('delivery_status') && $request->delivery_status != '') {
            $query->where('delivery_status', $request->delivery_status);
        }

        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('payment_type') && $request->payment_type != '') {
            $query->where('payment_type', $request->payment_type);
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'grand_total_asc':
                $query->orderBy('grand_total', 'asc');
                break;
            case 'grand_total_desc':
                $query->orderBy('grand_total', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $orders = $query->paginate(15)->appends($request->query());

        $dropshippers = \App\Models\Dropshipper::get(['id', 'name']);

        $totalOrders    = Order::where('order_type', 'Dropshipping')->count();
        $totalRevenue   = Order::where('order_type', 'Dropshipping')
            ->where('payment_status', 'paid')
            ->sum('grand_total');
        $pendingOrders  = Order::where('order_type', 'Dropshipping')
            ->where('delivery_status', 'pending')
            ->count();
        $deliveredOrders = Order::where('order_type', 'Dropshipping')
            ->where('delivery_status', 'delivered')
            ->count();

        return view('backend.sales.all_orders.dropshipper', compact(
            'orders',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'deliveredOrders',
            'dropshippers'
        ));
    }

    /**
     * Display order details
     */
    public function edit($id)
    {
        $order = Order::with(['orderDetails', 'orderDetails.product', 'user'])->findOrFail($id);
        $customer = $order->user;
        $subtotal = $order->orderDetails->sum(function ($detail) {
            return $detail->price * $detail->quantity;
        });
        $totalTax = $order->orderDetails->sum('tax');
        $totalShipping = $order->orderDetails->sum('shipping_cost');

        return view('backend.sales.all_orders.edit', compact('order', 'customer', 'subtotal', 'totalTax', 'totalShipping'));
    }

    // ========================================================================================
    public function searchProducts(Request $request)
    {
        $search = $request->get('q');
        $page = $request->get('page', 1);
        $perPage = 20;

        try {
            $query = Product::with(['inventory']);

            if (!empty($search) && strlen($search) >= 2) {
                $query->where('name', 'LIKE', "%{$search}%");
            }

            $products = $query->orderBy('name')
                ->paginate($perPage, ['*'], 'page', $page);

            $results = $products->map(function ($product) {
                $price = 0;
                if (isset($product->unit_price)) {
                    $price = $product->unit_price;
                } elseif (isset($product->regular_price)) {
                    $price = $product->regular_price;
                } elseif (isset($product->price)) {
                    $price = $product->price;
                } else {
                    $price = 0;
                }

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->inventory?->sku ?? 'N/A',
                    'price' => $price,
                    'stock' => $product->inventory?->stock ?? 0,
                    'thumbnail' => $product->thumbnail,
                    'variants' => []
                ];
            });

            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => $products->hasMorePages()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Product search error: ' . $e->getMessage());
            return response()->json([
                'results' => [],
                'pagination' => ['more' => false]
            ]);
        }
    }

    /**
     * Add item to order
     */
    public function addItem(Request $request, $orderId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'variant_id' => 'nullable|exists:product_variants,id',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $order = Order::findOrFail($orderId);

            // Check if product already exists with same variant
            $existingDetail = OrderDetail::where('order_id', $orderId)
                ->where('product_id', $request->product_id)
                ->where('variant_id', $request->variant_id)
                ->first();

            DB::beginTransaction();

            if ($existingDetail) {
                // Update quantity
                $existingDetail->quantity += $request->quantity;
                $existingDetail->save();
                $detail = $existingDetail;
            } else {
                // Create new order detail
                $detail = OrderDetail::create([
                    'order_id' => $orderId,
                    'product_id' => $request->product_id,
                    'variant_id' => $request->variant_id,
                    'price' => $request->price,
                    'quantity' => $request->quantity,
                    'tax' => 0,
                    'shipping_cost' => 0
                ]);
            }

            // Update order grand total
            $this->calculateOrderTotal($order);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item added successfully!',
                'item' => $detail,
                'grand_total' => $order->grand_total,
                'shipping_cost' => $order->shipping_cost,
                'coupon_discount' => $order->coupon_discount,
                'discount' => $order->discount
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update order item
     */
    public function updateItem(Request $request, $orderId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:order_details,id',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $detail = OrderDetail::where('order_id', $orderId)
                ->findOrFail($request->id);

            DB::beginTransaction();

            $detail->price = $request->price;
            $detail->quantity = $request->quantity;
            $detail->save();

            $order = Order::findOrFail($orderId);
            $this->calculateOrderTotal($order);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item updated successfully!',
                'grand_total' => $order->grand_total,
                'shipping_cost' => $order->shipping_cost,
                'coupon_discount' => $order->coupon_discount,
                'discount' => $order->discount
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove item from order
     */
    public function removeItem(Request $request, $orderId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:order_details,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $detail = OrderDetail::where('order_id', $orderId)
                ->findOrFail($request->id);

            DB::beginTransaction();

            $detail->delete();

            $order = Order::findOrFail($orderId);
            $this->calculateOrderTotal($order);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item removed successfully!',
                'grand_total' => $order->grand_total,
                'shipping_cost' => $order->shipping_cost,
                'coupon_discount' => $order->coupon_discount,
                'discount' => $order->discount
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update order notes
     */
    public function updateNotes(Request $request, $orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            $order->notes = $request->notes;
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Notes updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update shipping address only
     */
    public function updateAddress(Request $request, $orderId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'shipping_address' => 'required|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $order = Order::findOrFail($orderId);
            $order->shipping_address = $request->shipping_address;
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Shipping address updated successfully!',
                'shipping_address' => $order->shipping_address
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel order
     */
    public function cancelOrder($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);

            if ($order->delivery_status === 'delivered') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel a delivered order!'
                ]);
            }

            DB::beginTransaction();

            // Restore product stock
            foreach ($order->orderDetails as $detail) {
                if ($detail->product && $detail->product->inventory) {
                    $detail->product->inventory->stock += $detail->quantity;
                    $detail->product->inventory->save();
                }
            }

            $order->delivery_status = 'cancelled';
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate order grand total
     */
    private function calculateOrderTotal($order)
    {
        // Calculate subtotal from order details
        $subtotal = $order->orderDetails->sum(function ($detail) {
            return $detail->price * $detail->quantity;
        });

        // Calculate grand total: subtotal + shipping - coupon_discount - discount
        $grandTotal = $subtotal + ($order->shipping_cost ?? 0) - ($order->coupon_discount ?? 0) - ($order->discount ?? 0);

        $order->grand_total = $grandTotal;
        $order->save();

        return $order;
    }

    /**
     * Print order invoice
     */
    public function printOrder($id)
    {
        $order = Order::with(['orderDetails', 'orderDetails.product', 'user'])->findOrFail($id);
        $customer = $order->user;

        return view('backend.sales.all_orders.print', compact('order', 'customer'));
    }

    /**
     * Update order (full update)
     */
    public function updateOrder(Request $request, $orderId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
                'payment_status' => 'required|in:unpaid,paid,partial,refunded',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $order = Order::findOrFail($orderId);

            // If status is being changed to delivered, handle stock
            if ($request->status === 'delivered' && $order->delivery_status !== 'delivered') {
                foreach ($order->orderDetails as $detail) {
                    if ($detail->product && $detail->product->inventory) {
                        $detail->product->inventory->stock -= $detail->quantity;
                        $detail->product->inventory->save();
                    }
                }
            }

            $order->delivery_status = $request->status;
            $order->payment_status = $request->payment_status;
            $order->notes = $request->notes;
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========================================================================================


    /**
     * Display order details
     */
    public function show($id)
    {
        $order = Order::with(['orderDetails', 'orderDetails.product', 'user'])->findOrFail($id);
        $customer = $order->user;
        $subtotal = $order->orderDetails->sum(function ($detail) {
            return $detail->price * $detail->quantity;
        });
        $totalTax = $order->orderDetails->sum('tax');
        $totalShipping = $order->orderDetails->sum('shipping_cost');

        return view('backend.sales.all_orders.show', compact('order', 'customer', 'subtotal', 'totalTax', 'totalShipping'));
    }

    public function downloadInvoice($id)
    {
        try {
            $order = Order::with(['orderDetails', 'orderDetails.product', 'user'])->findOrFail($id);
            $customer = $order->user;
            $subtotal = $order->orderDetails->sum(function ($detail) {
                return $detail->price * $detail->quantity;
            });
            $totalTax = $order->orderDetails->sum('tax');
            $totalShipping = $order->shipping_cost;

            // Generate PDF from a view (you can reuse the print view)
            $pdf = Pdf::loadView('backend.sales.all_orders.invoice-pdf', compact(
                'order',
                'customer',
                'subtotal',
                'totalTax',
                'totalShipping'
            ));

            // Optional: Set paper size and orientation
            $pdf->setPaper('A4', 'portrait');

            // Download the PDF
            return $pdf->download('invoice-' . $order->code . '.pdf');
        } catch (\Exception $e) {
            // Log error and redirect back with error message
            \Log::error('Invoice download error: ' . $e->getMessage());
            flash(translate('Failed to generate invoice. Please try again.'))->error();
            return back();
        }
    }

    /**
     * Update order status (combined)
     */
    public function updateStatus(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|exists:orders,id',
                'status' => 'required|string',
                'type' => 'required|in:delivery,payment'
            ]);

            $order = Order::findOrFail($request->order_id);

            if ($request->type == 'delivery') {
                if ($request->status === 'transfer') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Transfer status cannot be set directly. Use the transfer action instead.'
                    ], 400);
                }
                $order->delivery_status = $request->status;
                $message = 'Delivery status updated successfully!';
            } else {
                $order->payment_status = $request->status;
                $message = 'Payment status updated successfully!';
            }

            $order->save();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update delivery status only
     */
    public function updateDeliveryStatus(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|exists:orders,id',
                'status' => 'required|in:pending,confirmed,picked_up,on_the_way,delivered,transfer,cancelled'
            ]);

            $order = Order::with('orderDetails.product')->findOrFail($request->order_id);

            if ($request->status === 'transfer') {
                return response()->json([
                    'success' => false,
                    'message' => 'Transfer status cannot be set directly. Use the transfer action instead.'
                ], 400);
            }

            $oldStatus = $order->delivery_status;

            $isBecomingDelivered = $request->status === 'delivered' && $oldStatus !== 'delivered';

            if ($isBecomingDelivered) {
                foreach ($order->orderDetails as $detail) {
                    $product = $detail->product;
                    if ($product) {
                        $product->num_of_sale = ($product->num_of_sale ?? 0) + $detail->quantity;
                        $product->save();
                    }
                }
            }

            $order->delivery_status = $request->status;
            $order->save();

            // Only credit profit when the order transitions TO delivered
            if ($isBecomingDelivered && $order->dropshipper_id && $order->dropshipper) {
                $appKey        = $order->dropshipper->app_key;
                $appSecret     = $order->dropshipper->app_secret;
                $userName      = $order->dropshipper->user_name;
                $invoiceNumber = $order->code;

                // Step 1: Calculate product selling total & total wholesale cost
                $orderTotal         = 0;
                $totalWholesaleCost = 0;

                foreach ($order->orderDetails as $detail) {
                    $quantity = (int) $detail->quantity;
                    $orderTotal += (float) $detail->price * $quantity;

                    $product = $detail->product;
                    if (!$product) {
                        continue;
                    }

                    $wholesalePrice = $product->price->wholesale_price ?? 0;

                    // For variant products, wholesale price always comes from product_varients table
                    if ($product->is_variant == 1) {
                        $variantWholesale = null;
                        $sku = $detail->sku ?? null;

                        if ($sku) {
                            // Try to match the variant by SKU (SKU is unique in product_varients)
                            $variant = ProductVarient::where('sku', $sku)->first();

                            if ($variant) {
                                $variantWholesale = $variant->wholesale_price;
                            }
                        }

                        // Fallback: match by the color-size variation (orders without SKU)
                        if (is_null($variantWholesale)) {
                            $attributeValue = '';
                            $variation = json_decode($detail->variation, true);

                            if (!empty($variation['color'])) {
                                $attributeValue .= str_replace(' ', '', $variation['color']);
                            }
                            if (!empty($variation['size'])) {
                                if ($attributeValue !== '') {
                                    $attributeValue .= '-';
                                }
                                $attributeValue .= $variation['size'];
                            }

                            if ($attributeValue !== '') {
                                $variant = $product->variants()
                                    ->where('attribute_value', $attributeValue)
                                    ->first();

                                if ($variant) {
                                    $variantWholesale = $variant->wholesale_price;
                                }
                            }
                        }

                        // Wholesale price taken from product_varients table
                        if (!is_null($variantWholesale)) {
                            $wholesalePrice = $variantWholesale;
                        }
                    }

                    $totalWholesaleCost += (float) $wholesalePrice * $quantity;
                }

                // Step 2: Calculate profit = selling revenue - wholesale cost (product profit)
                // Shipping cost is always credited to the dropshipper, even when product profit is 0.
                $productProfit = $orderTotal - $totalWholesaleCost;
                $profitAmount  = max(0, $productProfit) + (float) $order->shipping_cost;

                // Step 3: Send profit to balance API
                $balanceResponse = Http::withHeaders([
                    'App-Secret' => $appSecret,
                    'App-Key'    => $appKey,
                    'Username'   => $userName,
                ])->post('https://dropshipper.nittoz.com/api/dropshipper/profit/update', [
                    'amount'         => $profitAmount,
                    'type'           => 'credit',
                    'reason'         => 'Profit balance add for invoice #' . $invoiceNumber,
                    'invoice_number' => $invoiceNumber,
                ]);

                if (!$balanceResponse->ok()) {
                    Log::warning('Profit balance add failed for invoice #' . $invoiceNumber, [
                        'status' => $balanceResponse->status(),
                        'body'   => $balanceResponse->body(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Delivery status updated from ' . ucfirst($oldStatus) . ' to ' . ucfirst($request->status) . '!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update payment status only
     */
    public function updatePaymentStatus(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|exists:orders,id',
                'status' => 'required|in:unpaid,paid,refunded'
            ]);

            $order = Order::findOrFail($request->order_id);
            $oldStatus = $order->payment_status;
            $order->payment_status = $request->status;
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Payment status updated from ' . ucfirst($oldStatus) . ' to ' . ucfirst($request->status) . '!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update delivery status
     */
    public function bulkUpdateDeliveryStatus(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:orders,id',
                'status' => 'required|in:pending,confirmed,picked_up,on_the_way,delivered,cancelled'
            ]);

            $count = Order::whereIn('id', $request->ids)->update(['delivery_status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => $count . ' orders delivery status updated successfully!',
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update payment status
     */
    public function bulkUpdatePaymentStatus(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:orders,id',
                'status' => 'required|in:unpaid,paid,refunded'
            ]);

            $count = Order::whereIn('id', $request->ids)->update(['payment_status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => $count . ' orders payment status updated successfully!',
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete order
     */
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);

            // Delete order details first
            // $order->orderDetails()->delete();

            // Delete order
            // $order->delete();

            flash(translate('Order deleted successfully!'))->success();
            return redirect()->route('orders.index');
        } catch (\Exception $e) {
            flash(translate('Error: ' . $e->getMessage()))->error();
            return back();
        }
    }

    /**
     * Bulk delete orders
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:orders,id'
            ]);

            // Delete order details first
            OrderDetail::whereIn('order_id', $request->ids)->delete();

            // Delete orders
            $count = Order::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => $count . ' orders deleted successfully!',
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export orders to CSV
     */
    public function export()
    {
        $orders = Order::with(['user', 'orderDetails'])->latest()->get();

        $fileName = 'orders_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM
            fputs($file, "\xEF\xBB\xBF");

            // Add headers
            fputcsv($file, ['ID', 'Order Code', 'Customer Name', 'Customer Email', 'Customer Phone', 'Amount', 'Delivery Status', 'Payment Status', 'Payment Method', 'Order Date']);

            // Add data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->code,
                    $order->user->name ?? 'Guest User',
                    $order->user->email ?? 'N/A',
                    $order->user->phone ?? 'N/A',
                    $order->grand_total,
                    $order->delivery_status,
                    $order->payment_status,
                    $order->payment_type,
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    public function transferOrder(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|exists:orders,id',
            ]);

            $order = Order::with(['orderDetails', 'orderDetails.product', 'user'])->findOrFail($request->order_id);

            if ($order->delivery_status === 'transfer') {
                return response()->json([
                    'success' => false,
                    'message' => 'This order has already been transferred.',
                ], 400);
            }

            $appKey = env('DROPLOO_APP_KEY');
            $appSecret = env('DROPLOO_APP_SECRET');
            $userName = env('DROPLOO_USERNAME');

            $productQuantity = $order->orderDetails->sum('quantity');

            $productsArray = [];
            foreach ($order->orderDetails as $detail) {
                $variantSku = null;
                if ($detail->variation) {
                    $variation = json_decode($detail->variation, true);
                    if (!empty($variation['sku'])) {
                        $variantSku = $variation['sku'];
                    }
                }

                $externalProductId = $detail->product->droploo_product_id ?? $detail->product_id;

                $productsArray[] = [
                    'id' => (int) $externalProductId,
                    'price' => (float) $detail->price,
                    'variant_sku' => $variantSku,
                    'qty' => (int) $detail->quantity,
                ];
            }

            $apiData = [
                'invoice_number' => $order->code,
                'customer_name' => $order->user->name ?? 'Guest',
                'customer_phone' => $order->phone_number ?? ($order->user->phone ?? ''),
                'customer_address' => $order->shipping_address ?? '',
                'delivery_cost' => (float) $order->shipping_cost,
                'price' => (float) $order->grand_total,
                'discount' => (float) $order->discount,
                'advance' => 0.00,
                'product_quantity' => (int) $productQuantity,
                'delivery_charge_type' => $order->shipping_type ?? 'inside',
                'payment_type' => strtolower($order->payment_type ?? 'cod'),
                'order_type' => 'Dropshipping',
                'special_notes' => $order->notes ?? '',
                'payment_gateway' => null,
                'transaction_id' => null,
                'products' => $productsArray,
            ];

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => 'https://nittoz.com/api/v1/dropshippers/place-order',
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($apiData),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTPHEADER => [
                    'username: ' . $userName,
                    'api_key: ' . $appKey,
                    'api_secret: ' . $appSecret,
                    'Content-Type: application/json',
                    'Accept: application/json',
                ],
            ]);

            $apiResponseBody = curl_exec($ch);
            $apiHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                throw new \Exception('cURL error: ' . $curlError);
            }

            $apiResponseBody = json_decode($apiResponseBody, true);

            Log::info('Transfer order API response', [
                'order_code' => $order->code,
                'status' => $apiHttpCode,
                'body' => $apiResponseBody,
                'sent_data' => $apiData
            ]);

            if ($apiHttpCode < 200 || $apiHttpCode >= 300) {
                $errorMessage = $apiResponseBody['message'] ?? $apiResponseBody['error'] ?? 'Failed to transfer order to external system';

                if (isset($apiResponseBody['errors']) && is_array($apiResponseBody['errors'])) {
                    $flatErrors = [];
                    foreach ($apiResponseBody['errors'] as $field => $msgs) {
                        $flatErrors[] = $field . ': ' . implode(', ', (array)$msgs);
                    }
                    $errorMessage .= ' | ' . implode(' | ', $flatErrors);
                }

                Log::warning('Transfer order API rejected', [
                    'order_code' => $order->code,
                    'status' => $apiHttpCode,
                    'body' => $apiResponseBody
                ]);

                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                ], 400);
            }

            $order->delivery_status = 'transfer';
            $order->save();

            Log::info('Transfer order success', [
                'order_code' => $order->code,
                'api_response' => $apiResponseBody
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order transferred successfully!',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Transfer order error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while transferring the order: ' . $e->getMessage()
            ], 500);
        }
    }
}
