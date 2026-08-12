<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use App\Models\Admin\ProductInventory;
use App\Models\Admin\ProductPrice;
use App\Models\Admin\Review;
use App\Models\Admin\Wishlist;
use App\Models\OrderDetail;
use App\Models\Search;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Product Stocks Report
     */
    public function productStocksReport(Request $request)
    {
        $query = Product::with(['inventory', 'category', 'brand'])
            ->where('is_published', 1);

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('inventory', function ($q2) use ($search) {
                        $q2->where('sku', 'like', "%{$search}%");
                    });
            });
        }

        // Category filter
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Brand filter
        if ($request->has('brand_id') && $request->brand_id != '') {
            $query->where('brand_id', $request->brand_id);
        }

        // Stock status filter
        if ($request->has('stock_status') && $request->stock_status != '') {
            if ($request->stock_status == 'in_stock') {
                $query->whereHas('inventory', function ($q) {
                    $q->where('stock', '>', 0);
                });
            } elseif ($request->stock_status == 'out_of_stock') {
                $query->whereHas('inventory', function ($q) {
                    $q->where('stock', '=', 0);
                });
            } elseif ($request->stock_status == 'low_stock') {
                $query->whereHas('inventory', function ($q) {
                    $q->whereColumn('stock', '<=', 'low_stock_qty')
                        ->where('stock', '>', 0);
                });
            }
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'name_asc');
        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'stock_asc':
                $query->join('product_inventories', 'products.id', '=', 'product_inventories.product_id')
                    ->orderBy('product_inventories.stock', 'asc')
                    ->select('products.*');
                break;
            case 'stock_desc':
                $query->join('product_inventories', 'products.id', '=', 'product_inventories.product_id')
                    ->orderBy('product_inventories.stock', 'desc')
                    ->select('products.*');
                break;
            case 'price_asc':
                $query->join('product_prices', 'products.id', '=', 'product_prices.product_id')
                    ->orderBy('product_prices.regular_price', 'asc')
                    ->select('products.*');
                break;
            case 'price_desc':
                $query->join('product_prices', 'products.id', '=', 'product_prices.product_id')
                    ->orderBy('product_prices.regular_price', 'desc')
                    ->select('products.*');
                break;
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $products = $query->paginate(20)->appends($request->query());

        // Statistics
        $totalProducts = Product::where('is_published', 1)->count();
        $totalStock = ProductInventory::sum('stock');
        $lowStockProducts = ProductInventory::whereColumn('stock', '<=', 'low_stock_qty')
            ->where('stock', '>', 0)
            ->count();
        $outOfStockProducts = ProductInventory::where('stock', 0)->count();
        $inStockProducts = ProductInventory::where('stock', '>', 0)->count();

        // Stock value calculation
        $totalStockValue = DB::table('products')
            ->join('product_inventories', 'products.id', '=', 'product_inventories.product_id')
            ->join('product_prices', 'products.id', '=', 'product_prices.product_id')
            ->sum(DB::raw('product_inventories.stock * product_prices.regular_price'));

        // Get categories and brands for filters
        $categories = Category::orderBy('category_name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('backend.reports.product_stocks_report', compact(
            'products',
            'totalProducts',
            'totalStock',
            'lowStockProducts',
            'outOfStockProducts',
            'inStockProducts',
            'totalStockValue',
            'categories',
            'brands'
        ));
    }

    /**
     * Export Product Stocks Report to CSV
     */
    public function exportProductStocks(Request $request)
    {
        $query = Product::with(['inventory', 'category', 'brand'])
            ->where('is_published', 1);

        // Apply same filters
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('inventory', function ($q2) use ($search) {
                        $q2->where('sku', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('brand_id') && $request->brand_id != '') {
            $query->where('brand_id', $request->brand_id);
        }

        $products = $query->orderBy('name', 'asc')->get();

        $fileName = 'product_stocks_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM
            fputs($file, "\xEF\xBB\xBF");

            // Add headers
            fputcsv($file, [
                'SL',
                'Product Name',
                'SKU',
                'Category',
                'Brand',
                'Regular Price',
                'Sale Price',
                'Current Stock',
                'Low Stock Qty',
                'Stock Status',
                'Stock Value'
            ]);

            // Add data
            foreach ($products as $index => $product) {
                $regularPrice = $product->price->regular_price ?? 0;
                $stock = $product->inventory->stock ?? 0;
                $stockValue = $stock * $regularPrice;

                fputcsv($file, [
                    $index + 1,
                    $product->name,
                    $product->inventory->sku ?? 'N/A',
                    $product->category->name ?? 'N/A',
                    $product->brand->name ?? 'N/A',
                    number_format($regularPrice, 2),
                    number_format($product->price->sale_price ?? 0, 2),
                    $stock,
                    $product->inventory->low_stock_qty ?? 1,
                    $stock > 0 ? ($stock <= ($product->inventory->low_stock_qty ?? 1) ? 'Low Stock' : 'In Stock') : 'Out of Stock',
                    number_format($stockValue, 2)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }












    /**
     * Product Wishlist Report
     */
    public function productWishlistReport(Request $request)
    {
        $query = Product::with(['inventory', 'category', 'brand'])
            ->withCount(['wishlists' => function ($q) {
                $q->whereNotNull('user_id');
            }])
            ->withCount(['guestWishlists' => function ($q) {
                $q->whereNull('user_id');
            }]);

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('inventory', function ($q2) use ($search) {
                        $q2->where('sku', 'like', "%{$search}%");
                    });
            });
        }

        // Category filter
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Brand filter
        if ($request->has('brand_id') && $request->brand_id != '') {
            $query->where('brand_id', $request->brand_id);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'wishlist_desc');
        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'wishlist_asc':
                $query->orderBy('wishlists_count', 'asc');
                break;
            case 'wishlist_desc':
                $query->orderBy('wishlists_count', 'desc');
                break;
            case 'guest_wishlist_asc':
                $query->orderBy('guest_wishlists_count', 'asc');
                break;
            case 'guest_wishlist_desc':
                $query->orderBy('guest_wishlists_count', 'desc');
                break;
            case 'total_wishlist_asc':
                $query->orderBy(DB::raw('wishlists_count + guest_wishlists_count'), 'asc');
                break;
            case 'total_wishlist_desc':
                $query->orderBy(DB::raw('wishlists_count + guest_wishlists_count'), 'desc');
                break;
            default:
                $query->orderBy('wishlists_count', 'desc');
                break;
        }

        $products = $query->paginate(20)->appends($request->query());

        // Calculate total wishlists count
        $totalUserWishlists = Wishlist::whereNotNull('user_id')->count();
        $totalGuestWishlists = Wishlist::whereNull('user_id')->count();
        $totalWishlists = $totalUserWishlists + $totalGuestWishlists;

        // Products with wishlists
        $productsWithWishlists = Product::has('wishlists')->count();

        // Most wishlisted product
        $mostWishlisted = Product::withCount('wishlists')
            ->orderBy('wishlists_count', 'desc')
            ->first();

        // Top 5 wishlisted products
        $topWishlisted = Product::withCount('wishlists')
            ->orderBy('wishlists_count', 'desc')
            ->limit(5)
            ->get();

        // Categories and brands for filters
        $categories = Category::orderBy('category_name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('backend.reports.product_wishlist', compact(
            'products',
            'totalUserWishlists',
            'totalGuestWishlists',
            'totalWishlists',
            'productsWithWishlists',
            'mostWishlisted',
            'topWishlisted',
            'categories',
            'brands'
        ));
    }

    /**
     * Export Product Wishlist Report to CSV
     */
    public function exportProductWishlist(Request $request)
    {
        $query = Product::with(['inventory', 'category', 'brand'])
            ->withCount(['wishlists' => function ($q) {
                $q->whereNotNull('user_id');
            }])
            ->withCount(['guestWishlists' => function ($q) {
                $q->whereNull('user_id');
            }]);

        // Apply filters
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('inventory', function ($q2) use ($search) {
                        $q2->where('sku', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('brand_id') && $request->brand_id != '') {
            $query->where('brand_id', $request->brand_id);
        }

        $products = $query->orderBy('wishlists_count', 'desc')->get();

        $fileName = 'product_wishlist_report_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM
            fputs($file, "\xEF\xBB\xBF");

            // Add headers
            fputcsv($file, [
                'SL',
                'Product ID',
                'Product Name',
                'SKU',
                'Category',
                'Brand',
                'Regular Price',
                'User Wishlists',
                'Guest Wishlists',
                'Total Wishlists'
            ]);

            // Add data
            foreach ($products as $index => $product) {
                fputcsv($file, [
                    $index + 1,
                    $product->id,
                    $product->name,
                    $product->inventory->sku ?? 'N/A',
                    $product->category->name ?? 'N/A',
                    $product->brand->name ?? 'N/A',
                    number_format($product->price->regular_price ?? 0, 2),
                    $product->wishlists_count ?? 0,
                    $product->guest_wishlists_count ?? 0,
                    ($product->wishlists_count ?? 0) + ($product->guest_wishlists_count ?? 0)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    /**
     * User Searches Report
     */
    public function userSearchesReport(Request $request)
    {
        $query = Search::query();

        // Search filter by keyword
        if ($request->has('search') && $request->search != '') {
            $query->where('query', 'like', '%' . $request->search . '%');
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Minimum search count filter
        if ($request->has('min_count') && $request->min_count != '') {
            $query->where('count', '>=', $request->min_count);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'count_desc');
        switch ($sortBy) {
            case 'keyword_asc':
                $query->orderBy('query', 'asc');
                break;
            case 'keyword_desc':
                $query->orderBy('query', 'desc');
                break;
            case 'count_asc':
                $query->orderBy('count', 'asc');
                break;
            case 'count_desc':
                $query->orderBy('count', 'desc');
                break;
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('count', 'desc');
                break;
        }

        $searches = $query->paginate(20)->appends($request->query());

        // Statistics
        $totalSearches = Search::sum('count');
        $uniqueKeywords = Search::count();
        $avgSearchesPerKeyword = $uniqueKeywords > 0 ? round($totalSearches / $uniqueKeywords, 2) : 0;

        // Most popular search
        $mostPopular = Search::orderBy('count', 'desc')->first();

        // Top 10 search keywords
        $topKeywords = Search::orderBy('count', 'desc')->limit(10)->get();

        // Searches this week
        $searchesThisWeek = Search::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->sum('count');

        // Searches this month
        $searchesThisMonth = Search::whereMonth('created_at', now()->month)->sum('count');

        // Daily search trend (last 30 days)
        $dailyTrend = Search::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(count) as total')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('backend.reports.user_searches', compact(
            'searches',
            'totalSearches',
            'uniqueKeywords',
            'avgSearchesPerKeyword',
            'mostPopular',
            'topKeywords',
            'searchesThisWeek',
            'searchesThisMonth',
            'dailyTrend'
        ));
    }

    /**
     * Export User Searches Report to CSV
     */
    public function exportUserSearches(Request $request)
    {
        $query = Search::query();

        // Apply filters
        if ($request->has('search') && $request->search != '') {
            $query->where('query', 'like', '%' . $request->search . '%');
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('min_count') && $request->min_count != '') {
            $query->where('count', '>=', $request->min_count);
        }

        $searches = $query->orderBy('count', 'desc')->get();

        $fileName = 'user_searches_report_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($searches) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM
            fputs($file, "\xEF\xBB\xBF");

            // Add headers
            fputcsv($file, [
                'SL',
                'Search Keyword',
                'Search Count',
                'First Searched',
                'Last Searched'
            ]);

            // Add data
            foreach ($searches as $index => $search) {
                fputcsv($file, [
                    $index + 1,
                    $search->query,
                    $search->count,
                    $search->created_at->format('Y-m-d H:i:s'),
                    $search->updated_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Clear all search records
     */
    public function clearSearchRecords(Request $request)
    {
        try {
            $count = Search::count();
            Search::truncate();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $count . ' search records cleared successfully!'
                ]);
            }

            flash(translate('All search records cleared successfully!'))->success();
            return redirect()->route('reports.user-searches');
        } catch (\Exception $e) {
            if ($request->ajax()) {
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
     * Sales Report
     */
    public function salesReport(Request $request)
    {
        // Base query with eager loading paginate todayOrders topProducts
        $query = Order::with(['user', 'orderDetails.product']);

        // Apply filters only for paid orders in main query
        $query->where('payment_status', 'paid');

        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Payment status filter
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Delivery status filter
        if ($request->has('delivery_status') && $request->delivery_status != '') {
            $query->where('delivery_status', $request->delivery_status);
        }

        // Payment type filter
        if ($request->has('payment_type') && $request->payment_type != '') {
            $query->where('payment_type', $request->payment_type);
        }

        // Customer filter
        if ($request->has('customer_id') && $request->customer_id != '' && $request->customer_id > 0) {
            $query->where('user_id', $request->customer_id);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'amount_asc':
                $query->orderBy('grand_total', 'asc');
                break;
            case 'amount_desc':
                $query->orderBy('grand_total', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $orders = $query->paginate(5)->appends($request->query());

        $statsQuery = Order::query();

        // Apply same filters to stats
        if ($request->has('date_from') && $request->date_from != '') {
            $statsQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $statsQuery->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->has('payment_type') && $request->payment_type != '') {
            $statsQuery->where('payment_type', $request->payment_type);
        }
        if ($request->has('customer_id') && $request->customer_id != '' && $request->customer_id > 0) {
            $statsQuery->where('user_id', $request->customer_id);
        }

        $totalOrders = $statsQuery->count();

        $totalRevenue = (clone $statsQuery)->where('payment_status', 'paid')->sum('grand_total');

        $totalPendingOrders = (clone $statsQuery)->where('payment_status', 'unpaid')->count();

        $totalDeliveredOrders = (clone $statsQuery)->where('delivery_status', 'delivered')->count();

        $totalCancelledOrders = (clone $statsQuery)->where('delivery_status', 'cancelled')->count();

        $totalProcessingOrders = (clone $statsQuery)->whereIn('delivery_status', ['pending', 'confirmed', 'processing', 'shipped'])->count();

        $paidOrdersCount = (clone $statsQuery)->where('payment_status', 'paid')->count();
        $avgOrderValue = $paidOrdersCount > 0 ? round($totalRevenue / $paidOrdersCount, 2) : 0;

        $todaySales = Order::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->when($request->has('payment_type') && $request->payment_type != '', function ($q) use ($request) {
                return $q->where('payment_type', $request->payment_type);
            })
            ->when($request->has('customer_id') && $request->customer_id != '' && $request->customer_id > 0, function ($q) use ($request) {
                return $q->where('user_id', $request->customer_id);
            })
            ->sum('grand_total');

        $todayOrders = Order::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->when($request->has('payment_type') && $request->payment_type != '', function ($q) use ($request) {
                return $q->where('payment_type', $request->payment_type);
            })
            ->when($request->has('customer_id') && $request->customer_id != '' && $request->customer_id > 0, function ($q) use ($request) {
                return $q->where('user_id', $request->customer_id);
            })
            ->count();

        $weekSales = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('payment_status', 'paid')
            ->when($request->has('payment_type') && $request->payment_type != '', function ($q) use ($request) {
                return $q->where('payment_type', $request->payment_type);
            })
            ->when($request->has('customer_id') && $request->customer_id != '' && $request->customer_id > 0, function ($q) use ($request) {
                return $q->where('user_id', $request->customer_id);
            })
            ->sum('grand_total');

        $monthSales = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('payment_status', 'paid')
            ->when($request->has('payment_type') && $request->payment_type != '', function ($q) use ($request) {
                return $q->where('payment_type', $request->payment_type);
            })
            ->when($request->has('customer_id') && $request->customer_id != '' && $request->customer_id > 0, function ($q) use ($request) {
                return $q->where('user_id', $request->customer_id);
            })
            ->sum('grand_total');

        $yearSales = Order::whereYear('created_at', now()->year)
            ->where('payment_status', 'paid')
            ->when($request->has('payment_type') && $request->payment_type != '', function ($q) use ($request) {
                return $q->where('payment_type', $request->payment_type);
            })
            ->when($request->has('customer_id') && $request->customer_id != '' && $request->customer_id > 0, function ($q) use ($request) {
                return $q->where('user_id', $request->customer_id);
            })
            ->sum('grand_total');

        $paymentMethods = Order::select(
            'payment_type',
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(grand_total) as total'),
            DB::raw('SUM(CASE WHEN payment_status = "paid" THEN grand_total ELSE 0 END) as paid_total')
        )
            ->when($request->has('date_from') && $request->date_from != '', function ($q) use ($request) {
                return $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->has('date_to') && $request->date_to != '', function ($q) use ($request) {
                return $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->when($request->has('customer_id') && $request->customer_id != '' && $request->customer_id > 0, function ($q) use ($request) {
                return $q->where('user_id', $request->customer_id);
            })
            ->groupBy('payment_type')
            ->get();

        $topProducts = OrderDetail::select(
            'product_id',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('SUM(price * quantity) as total_sales')
        )
            ->with('product')
            ->whereHas('order', function ($q) use ($request) {
                $q->where('payment_status', 'paid');
                if ($request->has('date_from') && $request->date_from != '') {
                    $q->whereDate('created_at', '>=', $request->date_from);
                }
                if ($request->has('date_to') && $request->date_to != '') {
                    $q->whereDate('created_at', '<=', $request->date_to);
                }
                if ($request->has('customer_id') && $request->customer_id != '' && $request->customer_id > 0) {
                    $q->where('user_id', $request->customer_id);
                }
            })
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(40)
            ->get();

        $trendQuery = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(grand_total) as total_sales')
        )
            ->where('payment_status', 'paid');

        if ($request->has('date_from') && $request->date_from != '') {
            $trendQuery->whereDate('created_at', '>=', $request->date_from);
        } else {
            $trendQuery->where('created_at', '>=', now()->subDays(30));
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $trendQuery->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('customer_id') && $request->customer_id != '' && $request->customer_id > 0) {
            $trendQuery->where('user_id', $request->customer_id);
        }

        $dailyTrend = $trendQuery
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $deliveryStatusBreakdown = Order::select('delivery_status', DB::raw('COUNT(*) as count'))
            ->when($request->has('date_from') && $request->date_from != '', function ($q) use ($request) {
                return $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->has('date_to') && $request->date_to != '', function ($q) use ($request) {
                return $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->when($request->has('customer_id') && $request->customer_id != '' && $request->customer_id > 0, function ($q) use ($request) {
                return $q->where('user_id', $request->customer_id);
            })
            ->groupBy('delivery_status')
            ->get();

        $customers = User::whereIn('id', Order::select('user_id')->distinct()->pluck('user_id'))
            ->orderBy('name')
            ->get(['id', 'name', 'email']);



        // Add to your salesReport method before returning the view:
        $weekOrders = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('payment_status', 'paid')->count();
        $last30DaysSales = Order::where('created_at', '>=', now()->subDays(30))->where('payment_status', 'paid')->sum('grand_total');
        $last30DaysOrders = Order::where('created_at', '>=', now()->subDays(30))->where('payment_status', 'paid')->count();
        $monthOrders = Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->where('payment_status', 'paid')->count();
        $yearOrders = Order::whereYear('created_at', now()->year)->where('payment_status', 'paid')->count();
        $timeFilter = $request->get('time_filter', 'all');

        $trendData7Days = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(grand_total) as total_sales'),
            DB::raw('COUNT(*) as total_orders')
        )
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(created_at)'))  // Important: Group by date in database
            ->orderBy('date', 'asc')
            ->get();

        // For Last 30 Days
        $trendData30Days = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(grand_total) as total_sales'),
            DB::raw('COUNT(*) as total_orders')
        )
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();

        // For Last Year - Group by month
        $trendDataYear = Order::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m-01") as date'),
            DB::raw('SUM(grand_total) as total_sales'),
            DB::raw('COUNT(*) as total_orders')
        )
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subYear())
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($item) {
                $item->date = \Carbon\Carbon::parse($item->date)->format('d M, Y');
                return $item;
            });


        return view('backend.reports.sales', compact(
            'orders',
            'totalOrders',
            'totalRevenue',
            'totalPendingOrders',
            'totalDeliveredOrders',
            'totalCancelledOrders',
            'totalProcessingOrders',
            'avgOrderValue',
            'todaySales',
            'todayOrders',
            'weekSales',
            'monthSales',
            'yearSales',
            'paymentMethods',
            'topProducts',
            'dailyTrend',
            'deliveryStatusBreakdown',
            'customers',
            'weekOrders',
            'last30DaysSales',
            'last30DaysOrders',
            'monthOrders',
            'yearOrders',
            'timeFilter',
            'trendData7Days',
            'trendData30Days',
            'trendDataYear'
        ));
    }



    /**
     * Export Sales Report to CSV
     */
    public function exportSalesReport(Request $request)
    {
        $query = Order::with(['user']);

        // Apply filters
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->has('delivery_status') && $request->delivery_status != '') {
            $query->where('delivery_status', $request->delivery_status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $fileName = 'sales_report_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'SL',
                'Order Code',
                'Customer',
                'Email',
                'Phone',
                'Order Date',
                'Payment Method',
                'Payment Status',
                'Delivery Status',
                'Subtotal',
                'Discount',
                'Shipping',
                'Grand Total'
            ]);

            foreach ($orders as $index => $order) {
                $subtotal = $order->orderDetails->sum(function ($detail) {
                    return $detail->price * $detail->quantity;
                });

                fputcsv($file, [
                    $index + 1,
                    $order->code,
                    $order->user->name ?? 'Guest',
                    $order->user->email ?? 'N/A',
                    $order->user->phone ?? 'N/A',
                    $order->created_at->format('Y-m-d H:i:s'),
                    ucfirst(str_replace('_', ' ', $order->payment_type)),
                    ucfirst($order->payment_status),
                    ucfirst($order->delivery_status),
                    number_format($subtotal, 2),
                    number_format($order->discount + $order->coupon_discount, 2),
                    number_format($order->shipping_cost, 2),
                    number_format($order->grand_total, 2)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Product Report
     */
    public function productReport(Request $request)
    {
        $query = Product::with(['inventory', 'price', 'category', 'brand']);

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('inventory', function ($q2) use ($search) {
                        $q2->where('sku', 'like', "%{$search}%");
                    });
            });
        }

        // Category filter
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Brand filter
        if ($request->has('brand_id') && $request->brand_id != '') {
            $query->where('brand_id', $request->brand_id);
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price != '') {
            $query->whereHas('price', function ($q) use ($request) {
                $q->where('regular_price', '>=', $request->min_price);
            });
        }
        if ($request->has('max_price') && $request->max_price != '') {
            $query->whereHas('price', function ($q) use ($request) {
                $q->where('regular_price', '<=', $request->max_price);
            });
        }

        // Stock status filter
        if ($request->has('stock_status') && $request->stock_status != '') {
            if ($request->stock_status == 'in_stock') {
                $query->whereHas('inventory', function ($q) {
                    $q->where('stock', '>', 0);
                });
            } elseif ($request->stock_status == 'out_of_stock') {
                $query->whereHas('inventory', function ($q) {
                    $q->where('stock', '=', 0);
                });
            } elseif ($request->stock_status == 'low_stock') {
                $query->whereHas('inventory', function ($q) {
                    $q->whereColumn('stock', '<=', 'low_stock_qty')
                        ->where('stock', '>', 0);
                });
            }
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'name_asc');
        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy(ProductPrice::select('regular_price')
                    ->whereColumn('product_id', 'products.id'), 'asc');
                break;
            case 'price_desc':
                $query->orderBy(ProductPrice::select('regular_price')
                    ->whereColumn('product_id', 'products.id'), 'desc');
                break;
            case 'stock_asc':
                $query->orderBy(ProductInventory::select('stock')
                    ->whereColumn('product_id', 'products.id'), 'asc');
                break;
            case 'stock_desc':
                $query->orderBy(ProductInventory::select('stock')
                    ->whereColumn('product_id', 'products.id'), 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $products = $query->paginate(20)->appends($request->query());

        // Statistics
        $totalProducts = Product::count();
        $totalStock = ProductInventory::sum('stock');
        $totalValue = DB::table('products')
            ->join('product_inventories', 'products.id', '=', 'product_inventories.product_id')
            ->join('product_prices', 'products.id', '=', 'product_prices.product_id')
            ->sum(DB::raw('product_inventories.stock * product_prices.regular_price'));

        $publishedProducts = Product::where('is_published', 1)->count();
        $draftProducts = Product::where('is_published', 0)->count();
        $outOfStock = ProductInventory::where('stock', 0)->count();

        // Get categories and brands for filters
        $categories = Category::orderBy('category_name')->get();
        $brands = Brand::orderBy('name')->get();

        // Average product price
        $avgPrice = ProductPrice::avg('regular_price') ?? 0;

        return view('backend.reports.products', compact(
            'products',
            'totalProducts',
            'totalStock',
            'totalValue',
            'publishedProducts',
            'draftProducts',
            'outOfStock',
            'avgPrice',
            'categories',
            'brands'
        ));
    }

    /**
     * Export Product Report to CSV
     */
    public function exportProductReport(Request $request)
    {
        $query = Product::with(['inventory', 'price', 'category', 'brand']);

        // Apply filters
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('inventory', function ($q2) use ($search) {
                        $q2->where('sku', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('brand_id') && $request->brand_id != '') {
            $query->where('brand_id', $request->brand_id);
        }

        $products = $query->orderBy('name', 'asc')->get();

        $fileName = 'product_report_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'SL',
                'Product ID',
                'Product Name',
                'SKU',
                'Category',
                'Brand',
                'Regular Price',
                'Sale Price',
                'Current Stock',
                'Stock Status',
                'Status',
                'Created At'
            ]);

            foreach ($products as $index => $product) {
                $regularPrice = $product->price->regular_price ?? 0;
                $stock = $product->inventory->stock ?? 0;
                $stockStatus = $stock > 0 ? ($stock <= ($product->inventory->low_stock_qty ?? 1) ? 'Low Stock' : 'In Stock') : 'Out of Stock';

                fputcsv($file, [
                    $index + 1,
                    $product->id,
                    $product->name,
                    $product->inventory->sku ?? 'N/A',
                    $product->category->category_name ?? 'N/A',
                    $product->brand->name ?? 'N/A',
                    number_format($regularPrice, 2),
                    number_format($product->price->sale_price ?? 0, 2),
                    $stock,
                    $stockStatus,
                    $product->is_published ? 'Published' : 'Draft',
                    $product->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    /**
     * Customer Report
     */
    public function customerReport(Request $request)
    {
        $query = User::query();

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Customer type filter (registered/guest)
        if ($request->has('customer_type') && $request->customer_type != '') {
            if ($request->customer_type == 'registered') {
                $query->whereNotNull('id');
            } elseif ($request->customer_type == 'guest') {
                // Guests are users with no orders? Adjust as needed
                $query->whereDoesntHave('orders');
            }
        }

        // Min orders filter
        if ($request->has('min_orders') && $request->min_orders != '') {
            $query->has('orders', '>=', $request->min_orders);
        }

        // Min spent filter
        if ($request->has('min_spent') && $request->min_spent != '') {
            $query->whereHas('orders', function ($q) use ($request) {
                $q->where('payment_status', 'paid')
                    ->groupBy('user_id')
                    ->havingRaw('SUM(grand_total) >= ?', [$request->min_spent]);
            });
        }

        // Date range filter (customer since)
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'name_asc');
        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'orders_desc':
                $query->withCount('orders')
                    ->orderBy('orders_count', 'desc');
                break;
            case 'orders_asc':
                $query->withCount('orders')
                    ->orderBy('orders_count', 'asc');
                break;
            case 'spent_desc':
                $query->withSum(['orders' => function ($q) {
                    $q->where('payment_status', 'paid');
                }], 'grand_total')
                    ->orderBy('orders_sum_grand_total', 'desc');
                break;
            case 'spent_asc':
                $query->withSum(['orders' => function ($q) {
                    $q->where('payment_status', 'paid');
                }], 'grand_total')
                    ->orderBy('orders_sum_grand_total', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $customers = $query->paginate(20)->appends($request->query());

        // Statistics
        $totalCustomers = User::count();
        $customersWithOrders = User::has('orders')->count();
        $customersWithReviews = User::has('reviews')->count();
        $customersWithWishlist = User::has('wishlists')->count();

        // Total spent by all customers
        $totalSpent = Order::where('payment_status', 'paid')->sum('grand_total');

        // Average order per customer
        $avgOrderPerCustomer = $customersWithOrders > 0 ? round(Order::count() / $customersWithOrders, 2) : 0;

        // Average spent per customer
        $avgSpentPerCustomer = $customersWithOrders > 0 ? round($totalSpent / $customersWithOrders, 2) : 0;

        // New customers this month
        $newCustomersThisMonth = User::whereMonth('created_at', now()->month)->count();

        return view('backend.reports.customers', compact(
            'customers',
            'totalCustomers',
            'customersWithOrders',
            'customersWithReviews',
            'customersWithWishlist',
            'totalSpent',
            'avgOrderPerCustomer',
            'avgSpentPerCustomer',
            'newCustomersThisMonth'
        ));
    }

    /**
     * Export Customer Report to CSV
     */
    public function exportCustomerReport(Request $request)
    {
        $query = User::query();

        // Apply filters
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('min_orders') && $request->min_orders != '') {
            $query->has('orders', '>=', $request->min_orders);
        }

        $customers = $query->orderBy('name', 'asc')->get();

        $fileName = 'customer_report_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($customers) {
            $file = fopen('php://output', 'w');

            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'SL',
                'Customer ID',
                'Name',
                'Email',
                'Phone',
                'Total Orders',
                'Total Spent',
                'Reviews',
                'Wishlist Items',
                'Joined Date',
                'Status'
            ]);

            foreach ($customers as $index => $customer) {
                $totalOrders = $customer->orders->count();
                $totalSpent = $customer->orders->where('payment_status', 'paid')->sum('grand_total');
                $reviewsCount = $customer->reviews->count();
                $wishlistCount = $customer->wishlists->count();

                fputcsv($file, [
                    $index + 1,
                    $customer->id,
                    $customer->name,
                    $customer->email,
                    $customer->phone ?? 'N/A',
                    $totalOrders,
                    number_format($totalSpent, 2),
                    $reviewsCount,
                    $wishlistCount,
                    $customer->created_at->format('Y-m-d H:i:s'),
                    $customer->email_verified_at ? 'Verified' : 'Unverified'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function orderReport(Request $request)
    {
        $query = Order::with(['user', 'orderDetails.product']);

        // Search filter by order code
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('code', 'like', "%{$search}%");
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Payment status filter
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Delivery status filter
        if ($request->has('delivery_status') && $request->delivery_status != '') {
            $query->where('delivery_status', $request->delivery_status);
        }

        // Payment type filter
        if ($request->has('payment_type') && $request->payment_type != '') {
            $query->where('payment_type', $request->payment_type);
        }

        // Customer filter
        if ($request->has('customer_id') && $request->customer_id != '') {
            $query->where('user_id', $request->customer_id);
        }

        // Amount range filter
        if ($request->has('min_amount') && $request->min_amount != '') {
            $query->where('grand_total', '>=', $request->min_amount);
        }
        if ($request->has('max_amount') && $request->max_amount != '') {
            $query->where('grand_total', '<=', $request->max_amount);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'amount_asc':
                $query->orderBy('grand_total', 'asc');
                break;
            case 'amount_desc':
                $query->orderBy('grand_total', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // PAGINATION - Fixed!
        $orders = $query->paginate(15)->appends($request->query());

        // Order Statistics (using direct queries)
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')
            ->sum(DB::raw('grand_total - shipping_cost'));
        $totalPendingOrders = Order::where('payment_status', 'unpaid')->count();
        $totalDeliveredOrders = Order::where('delivery_status', 'delivered')->count();
        $totalCancelledOrders = Order::where('delivery_status', 'cancelled')->count();
        $totalProcessingOrders = Order::where('delivery_status', 'processing')->count();
        $totalShippedOrders = Order::where('delivery_status', 'shipped')->count();

        // Average order value
        $avgOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;

        // Today's orders
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::whereDate('created_at', today())->where('payment_status', 'paid')->sum('grand_total');

        // This week orders
        $weekOrders = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $weekRevenue = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('payment_status', 'paid')->sum('grand_total');

        // This month orders
        $monthOrders = Order::whereMonth('created_at', now()->month)->count();
        $monthRevenue = Order::whereMonth('created_at', now()->month)
            ->where('payment_status', 'paid')->sum('grand_total');

        // Payment method breakdown
        $paymentMethods = Order::select('payment_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(grand_total) as total'))
            ->groupBy('payment_type')
            ->get();

        // Delivery status breakdown
        $deliveryStatuses = Order::select('delivery_status', DB::raw('COUNT(*) as count'))
            ->groupBy('delivery_status')
            ->get();

        // Customers for filter
        $customers = User::whereHas('orders')->get(['id', 'name', 'email']);

        return view('backend.reports.orders', compact(
            'orders',
            'totalOrders',
            'totalRevenue',
            'totalPendingOrders',
            'totalDeliveredOrders',
            'totalCancelledOrders',
            'totalProcessingOrders',
            'totalShippedOrders',
            'avgOrderValue',
            'todayOrders',
            'todayRevenue',
            'weekOrders',
            'weekRevenue',
            'monthOrders',
            'monthRevenue',
            'paymentMethods',
            'deliveryStatuses',
            'customers'
        ));
    }

    /**
     * Export Order Report to CSV
     */
    public function exportOrderReport(Request $request)
    {
        $query = Order::with(['user']);

        // Apply filters
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('code', 'like', "%{$search}%");
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('delivery_status') && $request->delivery_status != '') {
            $query->where('delivery_status', $request->delivery_status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $fileName = 'order_report_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'SL',
                'Order Code',
                'Customer',
                'Email',
                'Phone',
                'Order Date',
                'Payment Method',
                'Payment Status',
                'Delivery Status',
                'Subtotal',
                'Discount',
                'Shipping',
                'Grand Total'
            ]);

            foreach ($orders as $index => $order) {
                $subtotal = $order->orderDetails->sum(function ($detail) {
                    return $detail->price * $detail->quantity;
                });

                fputcsv($file, [
                    $index + 1,
                    $order->code,
                    $order->user->name ?? 'Guest',
                    $order->user->email ?? 'N/A',
                    $order->user->phone ?? 'N/A',
                    $order->created_at->format('Y-m-d H:i:s'),
                    ucfirst(str_replace('_', ' ', $order->payment_type)),
                    ucfirst($order->payment_status),
                    ucfirst($order->delivery_status),
                    number_format($subtotal, 2),
                    number_format($order->discount + $order->coupon_discount, 2),
                    number_format($order->shipping_cost, 2),
                    number_format($order->grand_total, 2)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
