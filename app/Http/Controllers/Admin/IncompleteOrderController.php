<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\IncompleteOrder;
use App\Models\Admin\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class IncompleteOrderController extends Controller
{
    /**
     * Display a listing of incomplete orders.
     */
    public function index(Request $request)
    {
        $query = IncompleteOrder::with(['order', 'creator']);
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('order_code', 'like', "%{$search}%");
            });
        }
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => IncompleteOrder::count(),
            'pending' => IncompleteOrder::where('status', 'pending')->count(),
            'abandoned' => IncompleteOrder::where('status', 'abandoned')->count(),
            'completed' => IncompleteOrder::where('status', 'completed')->count(),
            'cancelled' => IncompleteOrder::where('status', 'cancelled')->count(),
        ];

        return view('backend.sales.incomplete_orders.index', compact('orders', 'stats'));
    }

    /**
     * Show the form for creating a new incomplete order.
     */
    public function create()
    {
        return view('backend.sales.incomplete_orders.create');
    }

    /**
     * Store a newly created incomplete order in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
            'cart_data' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $orderCode = 'INCO-' . strtoupper(Str::random(8));

            $incompleteOrder = IncompleteOrder::create([
                'order_code' => $orderCode,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'] ?? null,
                'billing_address' => $validated['billing_address'] ?? null,
                'cart_data' => $validated['cart_data'] ?? [],
                'subtotal' => $request->subtotal ?? 0,
                'tax' => $request->tax ?? 0,
                'shipping_cost' => $request->shipping_cost ?? 0,
                'discount' => $request->discount ?? 0,
                'total' => $request->total ?? 0,
                'status' => 'pending',
                'last_activity' => now(),
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Incomplete order created successfully',
                'data' => $incompleteOrder
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating incomplete order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create incomplete order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified incomplete order.
     */
    public function show(IncompleteOrder $incompleteOrder)
    {
        return view('backend.sales.incomplete_orders.show', compact('incompleteOrder'));
    }

    /**
     * Show the form for editing the specified incomplete order.
     */
    public function edit(IncompleteOrder $incompleteOrder)
    {
        return view('backend.sales.incomplete_orders.edit', compact('incompleteOrder'));
    }

    /**
     * Update the specified incomplete order in storage.
     */
    public function update(Request $request, IncompleteOrder $incompleteOrder)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
            'status' => 'required|in:pending,abandoned,processing,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $incompleteOrder->update([
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'] ?? null,
                'billing_address' => $validated['billing_address'] ?? null,
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
                'updated_by' => auth()->id(),
            ]);

            // If status is abandoned, set abandoned_at
            if ($validated['status'] === 'abandoned' && !$incompleteOrder->abandoned_at) {
                $incompleteOrder->update([
                    'abandoned_at' => now()
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Incomplete order updated successfully',
                'data' => $incompleteOrder
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating incomplete order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update incomplete order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Convert incomplete order to full order.
     */
    public function convertToOrder(IncompleteOrder $incompleteOrder)
    {
        try {
            DB::beginTransaction();

            // Create new order
            $order = Order::create([
                'code' => 'ORD-' . strtoupper(Str::random(10)),
                'user_id' => null, // Guest order
                'name' => $incompleteOrder->customer_name,
                'email' => $incompleteOrder->customer_email,
                'phone' => $incompleteOrder->customer_phone,
                'shipping_address' => $incompleteOrder->shipping_address,
                'billing_address' => $incompleteOrder->billing_address,
                'subtotal' => $incompleteOrder->subtotal,
                'tax' => $incompleteOrder->tax,
                'shipping_cost' => $incompleteOrder->shipping_cost,
                'discount' => $incompleteOrder->discount,
                'total' => $incompleteOrder->total,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => 'Converted from incomplete order #' . $incompleteOrder->order_code,
                'created_by' => auth()->id(),
            ]);

            // Create order details from cart data
            if ($incompleteOrder->cart_data) {
                foreach ($incompleteOrder->cart_data as $item) {
                    $order->orderDetails()->create([
                        'product_id' => $item['product_id'] ?? null,
                        'variant_id' => $item['variant_id'] ?? null,
                        'product_name' => $item['name'] ?? 'Product',
                        'price' => $item['price'] ?? 0,
                        'quantity' => $item['quantity'] ?? 1,
                        'total' => ($item['price'] ?? 0) * ($item['quantity'] ?? 1),
                    ]);
                }
            }

            // Update incomplete order
            $incompleteOrder->update([
                'order_id' => $order->id,
                'status' => 'completed',
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order converted successfully',
                'order_id' => $order->id,
                'order' => $order
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error converting incomplete order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to convert order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send reminder for abandoned order.
     */
    public function sendReminder(IncompleteOrder $incompleteOrder)
    {
        try {
            // Increment reminder count
            $incompleteOrder->increment('reminder_count');
            $incompleteOrder->update([
                'last_reminder_sent_at' => now()
            ]);

            // Here you can send email or SMS reminder
            // \Mail::to($incompleteOrder->customer_email)->send(new IncompleteOrderReminder($incompleteOrder));

            return response()->json([
                'success' => true,
                'message' => 'Reminder sent successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error sending reminder: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send reminder: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified incomplete order from storage.
     */
    public function destroy(IncompleteOrder $incompleteOrder)
    {
        try {
            $incompleteOrder->delete();

            return response()->json([
                'success' => true,
                'message' => 'Incomplete order deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting incomplete order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete incomplete order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk action for incomplete orders.
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:incomplete_orders,id',
            'action' => 'required|in:delete,mark_abandoned,mark_processing,mark_completed,send_reminder'
        ]);

        try {
            $count = 0;
            $orders = IncompleteOrder::whereIn('id', $validated['ids']);

            switch ($validated['action']) {
                case 'delete':
                    $count = $orders->delete();
                    $message = $count . ' orders deleted successfully';
                    break;

                case 'mark_abandoned':
                    $count = $orders->update([
                        'status' => 'abandoned',
                        'abandoned_at' => now(),
                        'updated_by' => auth()->id()
                    ]);
                    $message = $count . ' orders marked as abandoned';
                    break;

                case 'mark_processing':
                    $count = $orders->update([
                        'status' => 'processing',
                        'updated_by' => auth()->id()
                    ]);
                    $message = $count . ' orders marked as processing';
                    break;

                case 'mark_completed':
                    $count = $orders->update([
                        'status' => 'completed',
                        'updated_by' => auth()->id()
                    ]);
                    $message = $count . ' orders marked as completed';
                    break;

                case 'send_reminder':
                    foreach ($orders->get() as $order) {
                        $order->increment('reminder_count');
                        $order->update(['last_reminder_sent_at' => now()]);
                    }
                    $count = $orders->count();
                    $message = $count . ' reminders sent';
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in bulk action: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics for incomplete orders.
     */
    public function getStats()
    {
        $stats = [
            'total' => IncompleteOrder::count(),
            'pending' => IncompleteOrder::where('status', 'pending')->count(),
            'abandoned' => IncompleteOrder::where('status', 'abandoned')->count(),
            'completed' => IncompleteOrder::where('status', 'completed')->count(),
            'cancelled' => IncompleteOrder::where('status', 'cancelled')->count(),
            'today' => IncompleteOrder::whereDate('created_at', today())->count(),
            'last_7_days' => IncompleteOrder::where('created_at', '>=', now()->subDays(7))->count(),
            'last_30_days' => IncompleteOrder::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return response()->json($stats);
    }
}
