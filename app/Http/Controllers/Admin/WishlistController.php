<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display all wishlist items
     */
    public function index(Request $request)
    {
        $query = Wishlist::with(['user', 'product']);

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
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $wishlists = $query->paginate(10)->appends($request->query());

        // Statistics
        $totalWishlists = Wishlist::count();
        $totalProducts = Wishlist::distinct('product_id')->count('product_id');
        $totalUsers = Wishlist::distinct('user_id')->count('user_id');
        $wishlistsThisWeek = Wishlist::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        return view('backend.wishlists.index', compact(
            'wishlists',
            'totalWishlists',
            'totalProducts',
            'totalUsers',
            'wishlistsThisWeek',
            'sort'
        ));
    }

    /**
     * Remove the specified wishlist item.
     */
    public function destroy($id)
    {
        try {
            $wishlist = Wishlist::findOrFail($id);
            $wishlist->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Wishlist item deleted successfully!'
                ]);
            }

            flash('Wishlist item deleted successfully!')->success();
            return redirect()->route('wishlists.index');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }

            flash('Error: ' . $e->getMessage())->error();
            return back();
        }
    }

    /**
     * Bulk delete wishlist items
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:wishlists,id'
            ]);

            $count = Wishlist::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => $count . ' wishlist items deleted successfully!',
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
