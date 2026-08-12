<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use App\Models\Admin\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiReviewController extends Controller
{
    public function reviewStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $productId = $request->product_id;

        // 2. Check if the user has purchased this product (delivered order)
        $hasPurchased = Order::where('user_id', $user->id)
            ->where('delivery_status', 'delivered')
            ->whereHas('orderDetails', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists();

        if (!$hasPurchased) {
            return response()->json([
                'success' => false,
                'message' => 'You can only review products you have purchased and received.'
            ], 403);
        }

        $existingReview = Review::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this product.'
            ], 409);
        }
        $review = Review::create([
            'product_id' => $productId,
            'user_id'    => $user->id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
            'status'     => false,      // pending moderation
            'is_read'    => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully. It will be published after moderation.',
        ], 201);
    }

    public function purchaseHistory1()
    {
        $user = auth()->user();
        $purchaseHistory = Order::where('user_id', $user->id)->with('orderDetails')->paginate(5);
        $recentOrders = $user->orders()
            ->select('id', 'code', 'grand_total', 'payment_status', 'delivery_status', 'created_at')
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'purchaseHistory'          => $purchaseHistory
            ],
        ]);
    }



    public function purchaseHistory()
    {
        $user = auth()->user();

        $recentOrders = $user->orders()
            ->select('id', 'code', 'grand_total', 'payment_status', 'delivery_status', 'created_at')
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => [
                'orders' => $recentOrders->items(),
                'pagination' => [
                    'current_page' => $recentOrders->currentPage(),
                    'per_page' => $recentOrders->perPage(),
                    'total' => $recentOrders->total(),
                    'total_pages' => $recentOrders->lastPage(),
                    'has_more' => $recentOrders->hasMorePages(),
                ]
            ],
        ]);
    }
}
