<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Review::with(['product', 'user']);

            // Search filter - Search by product name, user name, email, or comment
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('comment', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            }

            // Filter by status
            if ($request->has('status') && $request->status != '') {
                if ($request->status == 'pending') {
                    $query->where('status', 0);
                } elseif ($request->status == 'approved') {
                    $query->where('status', 1);
                }
            }

            // Filter by rating
            if ($request->has('rating') && $request->rating > 0) {
                $query->where('rating', $request->rating);
            }

            // Filter by product
            if ($request->has('product_id') && $request->product_id > 0) {
                $query->where('product_id', $request->product_id);
            }

            // Filter by date range
            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Get paginated results
            $reviews = $query->latest('id')->paginate(15)->appends($request->query());

            // Calculate statistics (respecting filters for stats)
            $totalReviews = $query->count();
            $averageRating = $query->avg('rating');
            $pendingReviews = (clone $query)->where('status', 0)->count();
            $approvedReviews = (clone $query)->where('status', 1)->count();
            $fiveStarReviews = (clone $query)->where('rating', 5)->count();

            // Rating distribution (respecting filters)
            $ratingCounts = [];
            for ($i = 1; $i <= 5; $i++) {
                $ratingCounts[$i] = (clone $query)->where('rating', $i)->count();
            }

            // Recent activity (respecting filters)
            $reviewsThisWeek = (clone $query)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
            $reviewsThisMonth = (clone $query)->whereMonth('created_at', now()->month)->count();

            return view('backend.review.index', compact(
                'reviews',
                'totalReviews',
                'averageRating',
                'pendingReviews',
                'approvedReviews',
                'fiveStarReviews',
                'ratingCounts',
                'reviewsThisWeek',
                'reviewsThisMonth'
            ));
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Display the specified review.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $review = Review::with(['product', 'user'])->findOrFail($id);

            // Mark as read when viewed
            if (!$review->is_read) {
                $review->is_read = 1;
                $review->save();
            }

            return view('backend.review.show', compact('review'));
        } catch (\Exception $e) {
            flash(translate('Review not found!'))->error();
            return redirect()->route('reviews.index');
        }
    }

    /**
     * Update review status (approve/disapprove)
     */
    public function updateStatus(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:reviews,id',
                'status' => 'required|in:0,1'
            ]);

            $review = Review::findOrFail($request->id);
            $review->status = $request->status;
            $review->save();

            $statusText = $request->status == 1 ? 'approved' : 'disapproved';

            return response()->json([
                'success' => true,
                'message' => "Review {$statusText} successfully!",
                'data' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark review as read
     */
    public function markAsRead(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:reviews,id'
            ]);

            $review = Review::findOrFail($request->id);
            $review->is_read = 1;
            $review->save();

            return response()->json([
                'success' => true,
                'message' => 'Review marked as read!',
                'data' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update review status
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:reviews,id',
                'status' => 'required|in:0,1'
            ]);

            $count = Review::whereIn('id', $request->ids)
                ->update(['status' => $request->status]);

            $statusText = $request->status == 1 ? 'approved' : 'disapproved';

            return response()->json([
                'success' => true,
                'message' => "{$count} reviews {$statusText} successfully!",
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
     * Bulk delete reviews
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:reviews,id'
            ]);

            $count = Review::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "{$count} reviews deleted successfully!",
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
     * Get review statistics (for dashboard)
     */
    public function getStatistics()
    {
        try {
            $statistics = [
                'total_reviews' => Review::count(),
                'average_rating' => round(Review::avg('rating'), 1),
                'pending_reviews' => Review::where('status', 0)->count(),
                'approved_reviews' => Review::where('status', 1)->count(),
                'unread_reviews' => Review::where('is_read', 0)->count(),
                'five_star' => Review::where('rating', 5)->count(),
                'four_star' => Review::where('rating', 4)->count(),
                'three_star' => Review::where('rating', 3)->count(),
                'two_star' => Review::where('rating', 2)->count(),
                'one_star' => Review::where('rating', 1)->count(),
                'reviews_this_week' => Review::whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
                'reviews_this_month' => Review::whereMonth('created_at', now()->month)->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reviews for a specific product
     */
    public function getProductReviews($productId)
    {
        try {
            $product = Product::findOrFail($productId);

            $reviews = Review::with('user')
                ->where('product_id', $productId)
                ->where('status', 1)
                ->latest()
                ->paginate(10);

            $statistics = [
                'average_rating' => round($reviews->avg('rating'), 1),
                'total_reviews' => $reviews->total(),
                'rating_distribution' => [
                    '5' => Review::where('product_id', $productId)->where('rating', 5)->count(),
                    '4' => Review::where('product_id', $productId)->where('rating', 4)->count(),
                    '3' => Review::where('product_id', $productId)->where('rating', 3)->count(),
                    '2' => Review::where('product_id', $productId)->where('rating', 2)->count(),
                    '1' => Review::where('product_id', $productId)->where('rating', 1)->count(),
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $reviews,
                'statistics' => $statistics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
