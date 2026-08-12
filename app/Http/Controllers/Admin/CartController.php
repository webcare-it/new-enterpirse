<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display all cart items
     */
    public function index(Request $request)
    {
        $query = Cart::with(['user', 'product']);

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort filter
        $sort = $request->get('sort', 'latest');
        if ($sort == 'oldest') {
            $query->orderBy('id', 'asc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $carts = $query->paginate(10)->appends($request->query());

        // Statistics
        $totalCarts = Cart::count();
        $totalProducts = Cart::distinct('product_id')->count('product_id');
        $totalUsers = Cart::distinct('user_id')->count('user_id');
        $cartThisWeek = Cart::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        return view('backend.carts.index', compact(
            'carts',
            'totalCarts',
            'totalProducts',
            'totalUsers',
            'cartThisWeek',
            'sort'
        ));
    }

    /**
     * Delete cart item
     */
    public function destroy($id)
    {
        try {
            $cart = Cart::findOrFail($id);
            $cart->delete();

            // Check if AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart item deleted successfully!'
                ]);
            }
            // Regular form submission
            flash(translate('Cart item deleted successfully!'))->success();
            return redirect()->route('carts.index');
        } catch (\Exception $e) {
            // Handle error
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }

            flash(translate('Error: ' . $e->getMessage()))->error();
            return back();
        }
    }

    /**
     * Bulk delete cart items
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:carts,id'
            ]);

            $count = Cart::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => $count . ' cart items deleted successfully!',
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
