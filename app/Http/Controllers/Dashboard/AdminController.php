<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin\Campaign;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use App\Models\Admin\ProductInventory;
use App\Models\Admin\Review;
use App\Models\Landingpage;
use App\Models\OrderDetail;
use App\Models\Search;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function admin_dashboard(Request $request)
    {
        $totalSales = Order::where('payment_status', 'paid')
            ->sum(DB::raw('grand_total - shipping_cost'));
        $totalOrders = Order::count();
        $ordersThisWeek = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $ordersToday = Order::whereDate('created_at', today())->count();

        // Products
        $totalProducts = Product::count();
        $publishedProducts = Product::where('is_published', 1)->count();
        $draftProducts = Product::where('is_published', 0)->count();
        $lowStockProducts = ProductInventory::whereColumn('stock', '<=', 'low_stock_qty')->where('stock', '>', 0)->count();

        $outOfStockProducts = ProductInventory::where('stock', 0)->count();

        $totalStockValue = DB::table('products')
            ->join('product_inventories', 'products.id', '=', 'product_inventories.product_id')
            ->join('product_prices', 'products.id', '=', 'product_prices.product_id')
            ->sum(DB::raw('product_inventories.stock * product_prices.regular_price'));

        // Customers
        $totalCustomers = User::count();
        $newCustomersToday = User::whereDate('created_at', today())->count();
        $newCustomersThisWeek = User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $newCustomersThisMonth = User::whereMonth('created_at', now()->month)->count();
        $verifiedCustomers = User::whereNotNull('email_verified_at')->count();

        // Orders by Status
        $pendingOrders = Order::where('delivery_status', 'pending')->count();
        $processingOrders = Order::where('delivery_status', 'processing')->count();
        $shippedOrders = Order::where('delivery_status', 'shipped')->count();
        $deliveredOrders = Order::where('delivery_status', 'delivered')->count();
        $cancelledOrders = Order::where('delivery_status', 'cancelled')->count();

        // Payment Stats
        $paidOrders = Order::where('payment_status', 'paid')->count();
        $unpaidOrders = Order::where('payment_status', 'unpaid')->count();
        $refundedOrders = Order::where('payment_status', 'refunded')->count();
        $refundAmount = Order::where('payment_status', 'refunded')->sum('grand_total');

        // Payment Methods Breakdown
        $paymentMethods = Order::select('payment_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(grand_total) as total'))
            ->groupBy('payment_type')
            ->get();

        // ========== ADVANCED METRICS ==========

        // Average Order Value
        $avgOrderValue = $totalOrders > 0 ? round($totalSales / $totalOrders, 2) : 0;

        // Customer Lifetime Value (CLV)
        $customerLTV = $totalCustomers > 0 ? round($totalSales / $totalCustomers, 2) : 0;

        // Average Order Frequency
        $customersWithOrders = User::has('orders')->count();
        $avgOrderFrequency = $customersWithOrders > 0 ? round($totalOrders / $customersWithOrders, 2) : 0;

        // Conversion Rate
        $totalVisits = Search::sum('count') + 50000;
        $conversionRate = $totalVisits > 0 ? round(($totalOrders / $totalVisits) * 100, 1) : 0;

        // Return/Cancellation Rate
        $returnRate = $totalOrders > 0 ? round(($cancelledOrders / $totalOrders) * 100, 1) : 0;

        // Customer Satisfaction Score
        $avgRating = Review::avg('rating') ?? 0;
        $totalReviews = Review::count();
        $positiveReviews = Review::where('rating', '>=', 4)->count();
        $positiveReviewRate = $totalReviews > 0 ? round(($positiveReviews / $totalReviews) * 100, 1) : 0;

        // ========== TIME-BASED ANALYSIS ==========

        // Today's Revenue
        $todayRevenue = Order::whereDate('created_at', today())->where('payment_status', 'paid')->sum('grand_total');

        $todayTarget = 75000;

        $todayProgress = $todayTarget > 0 ? min(100, round(($todayRevenue / $todayTarget) * 100)) : 0;

        // This Week Revenue
        $weekRevenue = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        // This Month Revenue
        $monthRevenue = Order::whereMonth('created_at', now()->month)
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        // This Year Revenue
        $yearRevenue = Order::whereYear('created_at', date('Y'))
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        // Previous Month Revenue
        $previousMonthRevenue = Order::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        $revenueGrowth = $previousMonthRevenue > 0
            ? round((($monthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100, 1)
            : 0;

        // ========== MONTHLY SALES TREND ==========
        $monthlySales = [];
        $monthlyOrders = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlySales[] = Order::whereMonth('created_at', $i)
                ->whereYear('created_at', date('Y'))
                ->where('payment_status', 'paid')
                ->sum('grand_total');

            $monthlyOrders[] = Order::whereMonth('created_at', $i)
                ->whereYear('created_at', date('Y'))
                ->count();
        }

        // ========== DAILY SALES (LAST 7 DAYS) ==========
        $dailySales = [];
        $dailyLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dailyLabels[] = $date->format('D, M d');
            $dailySales[] = Order::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('grand_total');
        }

        // ========== WEEKLY TRENDS (FIXED) ==========
        $weeklyOrders = [];
        $weeklyRevenue = [];
        $weeklyLabels = [];

        for ($i = 6; $i >= 0; $i--) {
            $startOfWeek = now()->subWeeks($i)->startOfWeek();
            $endOfWeek = now()->subWeeks($i)->endOfWeek();

            if ($i == 0) {
                $weeklyLabels[] = 'This Week';
            } elseif ($i == 1) {
                $weeklyLabels[] = 'Last Week';
            } else {
                $weeklyLabels[] = $startOfWeek->format('M d');
            }

            $weeklyOrders[] = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
            $weeklyRevenue[] = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->where('payment_status', 'paid')
                ->sum('grand_total');
        }

        // ========== PEAK HOURS (FIXED) ==========
        $peakHours = array_fill(0, 24, 0);
        $hourlyData = Order::select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')
            ->get();

        foreach ($hourlyData as $data) {
            $peakHours[$data->hour] = $data->count;
        }

        // ========== TOP PERFORMING ==========

        // Top 5 Products
        $topProducts = OrderDetail::select(
            'product_id',
            DB::raw('SUM(quantity) as total_sold'),
            DB::raw('SUM(price * quantity) as total_revenue')
        )
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Top 5 Categories
        $topCategories = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->select(
                'categories.id',
                'categories.category_name',
                DB::raw('SUM(order_details.quantity) as total_sold'),
                DB::raw('SUM(order_details.price * order_details.quantity) as total_revenue')
            )
            ->groupBy('categories.id', 'categories.category_name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Top 5 Brands
        $topBrands = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->select(
                'brands.id',
                'brands.name',
                DB::raw('SUM(order_details.quantity) as total_sold'),
                DB::raw('SUM(order_details.price * order_details.quantity) as total_revenue')
            )
            ->groupBy('brands.id', 'brands.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Top 5 Customers
        $topCustomers = Order::select(
            'user_id',
            DB::raw('COUNT(*) as order_count'),
            DB::raw('SUM(grand_total) as total_spent')
        )
            ->with('user')
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderBy('total_spent', 'desc')
            ->limit(5)
            ->get();

        // Most Wishlisted Products
        $mostWishlisted = Product::withCount('wishlists')
            ->orderBy('wishlists_count', 'desc')
            ->limit(5)
            ->get();

        // Most Reviewed Products
        $mostReviewed = Product::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderBy('reviews_count', 'desc')
            ->limit(5)
            ->get();

        // ========== RECENT ACTIVITIES ==========
        $recentActivities = collect();

        // Recent Orders
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        foreach ($recentOrders as $order) {
            $userName = $order->user ? $order->user->name : 'Guest';
            $recentActivities->push([
                'time' => $order->created_at->diffForHumans(),
                'title' => 'New Order',
                'description' => "Order #{$order->code} by {$userName}",
                'amount' => $order->grand_total,
                'type' => 'order',
                'icon' => 'shopping-cart',
                'color' => 'primary'
            ]);
        }

        // Recent User Registrations
        $recentUsers = User::latest()->take(3)->get();
        foreach ($recentUsers as $user) {
            $recentActivities->push([
                'time' => $user->created_at->diffForHumans(),
                'title' => 'New Customer',
                'description' => "{$user->name} registered as new customer",
                'type' => 'user',
                'icon' => 'user-plus',
                'color' => 'success'
            ]);
        }

        // Recent Reviews
        $recentReviewsList = Review::with('user', 'product')->latest()->take(3)->get();
        foreach ($recentReviewsList as $review) {
            $reviewerName = $review->user ? $review->user->name : 'Guest';
            $productName = $review->product ? $review->product->name : 'Unknown Product';
            $recentActivities->push([
                'time' => $review->created_at->diffForHumans(),
                'title' => 'New Review',
                'description' => $reviewerName . ' reviewed ' . $productName,
                'rating' => $review->rating,
                'type' => 'review',
                'icon' => 'star',
                'color' => 'warning'
            ]);
        }

        // Low Stock Alerts
        $lowStockProductsList = ProductInventory::with('product')
            ->whereColumn('stock', '<=', 'low_stock_qty')
            ->where('stock', '>', 0)
            ->take(3)
            ->get();
        foreach ($lowStockProductsList as $item) {
            $recentActivities->push([
                'time' => $item->updated_at->diffForHumans(),
                'title' => 'Low Stock Alert',
                'description' => "{$item->product->name} has only {$item->stock} units left",
                'type' => 'alert',
                'icon' => 'exclamation-triangle',
                'color' => 'danger'
            ]);
        }

        $recentActivities = $recentActivities->sortByDesc('time')->take(10);

        // ========== INVENTORY INSIGHTS ==========
        $inventoryAlerts = ProductInventory::with('product')
            ->whereColumn('stock', '<=', 'low_stock_qty')
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        $topSellingVariants = DB::table('order_details')
            ->select('variation', DB::raw('SUM(quantity) as total_sold'))
            // ->whereNotNull('variation')
            // ->where('variation', '!=', '')
            // ->groupBy('variation')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // ========== MARKETING INSIGHTS ==========

        // Coupon Usage
        $totalCouponDiscount = Order::sum('coupon_discount');
        $ordersWithCoupon = Order::where('coupon_discount', '>', 0)->count();
        $couponUsageRate = $totalOrders > 0 ? round(($ordersWithCoupon / $totalOrders) * 100, 1) : 0;

        // Active Campaigns
        $activeCampaigns = Campaign::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->count();

        // Landing Pages Performance
        $landingPages = LandingPage::where('is_published', 1)->get();
        $totalLandingPageVisits = $landingPages->sum('visitor_count');
        $totalLandingPageSales = $landingPages->sum('sold_count');

        // ========== SEARCH ANALYTICS ==========
        $totalSearches = Search::sum('count');
        $uniqueKeywords = Search::count();
        $topSearchKeywords = Search::orderBy('count', 'desc')->limit(6)->get();

        // ========== CUSTOMER SEGMENTATION ==========
        $newCustomers = $newCustomersThisMonth;
        $returningCustomers = User::has('orders', '>', 1)->count();
        $oneTimeCustomers = User::has('orders', '=', 1)->count();

        // ========== RECENT ORDERS LIST ==========
        $recentOrdersList = Order::with('user')->latest()->take(7)->get();

        // ========== MONTHLY LABELS ==========
        $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // ========== DEVICE STATS (Placeholder) ==========
        $deviceStats = [
            'Mobile' => 65,
            'Desktop' => 28,
            'Tablet' => 7
        ];

        // ========== POPULAR TAGS ==========
        $popularTags = ['Wireless', 'Bluetooth', 'Smart Watch', 'Headphone', 'Fast Charging', 'Gaming Mouse', 'Phone Case', 'Power Bank', 'USB Cable', 'Keyboard'];


        // ========== COURIER PERFORMANCE ==========
        $courierStats = [
            'Pathao' => ['delivered' => 0, 'total' => 0, 'avg_days' => 2.3, 'on_time' => 94],
            'Steadfast' => ['delivered' => 0, 'total' => 0, 'avg_days' => 2.8, 'on_time' => 89],
            'RedX' => ['delivered' => 0, 'total' => 0, 'avg_days' => 1.9, 'on_time' => 97],
            'Others' => ['delivered' => 0, 'total' => 0, 'avg_days' => 2.5, 'on_time' => 85],
        ];

        // You can update this from shipping tracking data
        $courierStats['Pathao']['delivered'] = Order::where('delivery_status', 'delivered')->where('shipping_type', 'pathao')->count();
        $courierStats['Pathao']['total'] = Order::where('shipping_type', 'pathao')->count();

        return view('backend.dashboard', compact(
            // Core Stats
            'totalSales',
            'totalOrders',
            'ordersThisWeek',
            'ordersToday',
            'totalProducts',
            'publishedProducts',
            'draftProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'totalStockValue',
            'totalCustomers',
            'newCustomersToday',
            'newCustomersThisWeek',
            'newCustomersThisMonth',
            'verifiedCustomers',

            // Order Status
            'pendingOrders',
            'processingOrders',
            'shippedOrders',
            'deliveredOrders',
            'cancelledOrders',
            'paidOrders',
            'unpaidOrders',
            'refundedOrders',
            'refundAmount',
            'paymentMethods',

            // Advanced Metrics
            'avgOrderValue',
            'customerLTV',
            'avgOrderFrequency',
            'totalVisits',
            'conversionRate',
            'returnRate',
            'avgRating',
            'totalReviews',
            'positiveReviews',
            'positiveReviewRate',

            // Revenue Analysis
            'todayRevenue',
            'todayProgress',
            'weekRevenue',
            'monthRevenue',
            'yearRevenue',
            'previousMonthRevenue',
            'revenueGrowth',

            // Charts Data
            'monthlySales',
            'monthlyOrders',
            'monthlyLabels',
            'dailySales',
            'dailyLabels',
            'weeklyOrders',
            'weeklyRevenue',
            'weeklyLabels',
            'peakHours',

            // Top Performers
            'topProducts',
            'topCategories',
            'topBrands',
            'topCustomers',
            'mostWishlisted',
            'mostReviewed',

            // Activities & Alerts
            'recentActivities',
            'inventoryAlerts',
            'topSellingVariants',
            'recentOrdersList',
            'recentReviewsList',

            // Marketing
            'totalCouponDiscount',
            'ordersWithCoupon',
            'couponUsageRate',
            'activeCampaigns',
            'landingPages',
            'totalLandingPageVisits',
            'totalLandingPageSales',

            // Search Analytics
            'totalSearches',
            'uniqueKeywords',
            'topSearchKeywords',

            // Customer Segmentation
            'newCustomers',
            'returningCustomers',
            'oneTimeCustomers',

            // Other
            'deviceStats',
            'popularTags',
            'courierStats'
        ));
    }


    public function admin_dashboard1(Request $request)
    {
        // ========== STATISTICS ==========

        // Total Sales (Revenue)
        $totalSales = Order::where('payment_status', 'paid')->sum('grand_total');

        // Total Orders
        $totalOrders = Order::count();
        $ordersThisWeek = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        // Total Products
        $totalProducts = Product::count();
        $lowStockProducts = ProductInventory::whereColumn('stock', '<=', 'low_stock_qty')
            ->where('stock', '>', 0)
            ->count();

        // Total Customers
        $totalCustomers = User::count();
        $newCustomers = User::whereMonth('created_at', now()->month)->count();

        // Pending Delivery
        $pendingDelivery = Order::where('delivery_status', 'pending')->count();

        // Refund Requests (orders with refund status)
        $refundRequests = Order::where('payment_status', 'refunded')->count();
        $refundAmount = Order::where('payment_status', 'refunded')->sum('grand_total');

        // Total Visits (unique visitors - from landing page or session tracking)
        $totalVisits = session()->get('total_visits', 12450); // You can implement actual tracking

        // Average Order Value
        $avgOrderValue = $totalOrders > 0 ? round($totalSales / $totalOrders, 2) : 0;

        // ========== PROGRESS CARDS ==========

        // Today's Revenue
        $todayRevenue = Order::whereDate('created_at', today())->where('payment_status', 'paid')->sum('grand_total');
        $todayTarget = 50000; // Set your target
        $todayProgress = $todayTarget > 0 ? min(100, round(($todayRevenue / $todayTarget) * 100)) : 0;

        // Conversion Rate (orders / unique visitors)
        $conversionRate = $totalVisits > 0 ? round(($totalOrders / $totalVisits) * 100, 1) : 0;

        // Return Rate (cancelled orders / total orders)
        $cancelledOrders = Order::where('delivery_status', 'cancelled')->count();
        $returnRate = $totalOrders > 0 ? round(($cancelledOrders / $totalOrders) * 100, 1) : 0;

        // Customer Satisfaction (average rating)
        $avgRating = Review::avg('rating') ?? 0;
        $totalReviews = Review::count();

        // ========== SALES OVERVIEW (Monthly) ==========
        $monthlySales = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlySales[] = Order::whereMonth('created_at', $i)
                ->whereYear('created_at', date('Y'))
                ->where('payment_status', 'paid')
                ->sum('grand_total');
        }

        // ========== RECENT ACTIVITY ==========
        $recentActivities = [];

        // Recent orders
        $recentOrders = Order::latest()->take(3)->get();
        foreach ($recentOrders as $order) {
            $recentActivities[] = [
                'time' => $order->created_at->diffForHumans(),
                'title' => 'New order placed',
                'description' => "Order #{$order->code} placed",
                'amount' => $order->grand_total,
                'type' => 'order'
            ];
        }

        // Recent payments
        $recentPayments = Order::where('payment_status', 'paid')->latest()->take(3)->get();
        foreach ($recentPayments as $payment) {
            $recentActivities[] = [
                'time' => $payment->updated_at->diffForHumans(),
                'title' => 'Payment received',
                'description' => "Payment received for order #{$payment->code}",
                'amount' => $payment->grand_total,
                'type' => 'payment'
            ];
        }

        // Low stock alerts
        $lowStockProductsList = ProductInventory::with('product')
            ->whereColumn('stock', '<=', 'low_stock_qty')
            ->where('stock', '>', 0)
            ->take(3)
            ->get();
        foreach ($lowStockProductsList as $item) {
            $recentActivities[] = [
                'time' => $item->updated_at->diffForHumans(),
                'title' => 'Low stock alert',
                'description' => "{$item->product->name} has only {$item->stock} units left",
                'type' => 'alert'
            ];
        }

        // Sort activities by time (latest first)
        $recentActivities = collect($recentActivities)->sortByDesc('time')->take(10);

        // ========== TOP CATEGORIES ==========
        $topCategories = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->select('categories.category_name', DB::raw('SUM(order_details.quantity) as total_sold'))
            ->groupBy('categories.id', 'categories.category_name')
            ->orderBy('total_sold', 'desc')
            ->limit(4)
            ->get();

        // Calculate percentages
        $totalSold = $topCategories->sum('total_sold');
        foreach ($topCategories as $category) {
            $category->percentage = $totalSold > 0 ? round(($category->total_sold / $totalSold) * 100) : 0;
        }

        // ========== RECENT ORDERS ==========
        $recentOrdersList = Order::with('user')
            ->latest()
            ->take(7)
            ->get();

        // ========== TOP PRODUCTS ==========
        $topProducts = OrderDetail::select(
            'product_id',
            DB::raw('SUM(quantity) as total_sold'),
            DB::raw('SUM(price * quantity) as total_revenue')
        )
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->limit(4)
            ->get();

        // ========== COURIER PERFORMANCE ==========
        $courierStats = [
            'Pathao' => ['delivered' => 0, 'total' => 0, 'avg_days' => 2.3, 'on_time' => 94],
            'Steadfast' => ['delivered' => 0, 'total' => 0, 'avg_days' => 2.8, 'on_time' => 89],
            'RedX' => ['delivered' => 0, 'total' => 0, 'avg_days' => 1.9, 'on_time' => 97],
            'Others' => ['delivered' => 0, 'total' => 0, 'avg_days' => 2.5, 'on_time' => 85],
        ];

        // You can update this from shipping tracking data
        $courierStats['Pathao']['delivered'] = Order::where('delivery_status', 'delivered')->where('shipping_type', 'pathao')->count();
        $courierStats['Pathao']['total'] = Order::where('shipping_type', 'pathao')->count();

        // ========== INVENTORY ALERTS ==========
        $inventoryAlerts = ProductInventory::with('product')
            ->whereColumn('stock', '<=', 'low_stock_qty')
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->limit(4)
            ->get();

        // ========== ORDER SUMMARY ==========
        $orderSummary = [
            'pending' => Order::where('delivery_status', 'pending')->count(),
            'processing' => Order::where('delivery_status', 'processing')->count(),
            'shipped' => Order::where('delivery_status', 'shipped')->count(),
            'delivered' => Order::where('delivery_status', 'delivered')->count(),
            'cancelled' => Order::where('delivery_status', 'cancelled')->count(),
        ];

        // ========== REVENUE SUMMARY ==========
        $revenueSummary = [
            'today' => Order::whereDate('created_at', today())->where('payment_status', 'paid')->sum('grand_total'),
            'this_week' => Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('payment_status', 'paid')->sum('grand_total'),
            'this_month' => Order::whereMonth('created_at', now()->month)->where('payment_status', 'paid')->sum('grand_total'),
            'this_year' => Order::whereYear('created_at', date('Y'))->where('payment_status', 'paid')->sum('grand_total'),
            'refund' => Order::where('payment_status', 'refunded')->sum('grand_total'),
        ];

        // ========== RECENT REVIEWS ==========
        $recentReviews = Review::with('user', 'product')
            ->latest()
            ->take(3)
            ->get();

        // ========== POPULAR TAGS ==========
        $popularTags = ['Wireless', 'Bluetooth', 'Smart Watch', 'Headphone', 'Fast Charging', 'Gaming Mouse', 'Phone Case', 'Power Bank', 'USB Cable', 'Keyboard'];

        // ========== MONTHLY LABELS ==========
        $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        return view('backend.dashboard', compact(
            'totalSales',
            'totalOrders',
            'ordersThisWeek',
            'totalProducts',
            'lowStockProducts',
            'totalCustomers',
            'newCustomers',
            'pendingDelivery',
            'refundRequests',
            'refundAmount',
            'totalVisits',
            'avgOrderValue',
            'todayRevenue',
            'todayProgress',
            'conversionRate',
            'returnRate',
            'avgRating',
            'totalReviews',
            'monthlySales',
            'monthlyLabels',
            'recentActivities',
            'topCategories',
            'recentOrdersList',
            'topProducts',
            'courierStats',
            'inventoryAlerts',
            'orderSummary',
            'revenueSummary',
            'recentReviews',
            'popularTags'
        ));
    }

    function clearCache(Request $request)
    {
        Artisan::call('cache:clear');
        flash(translate('Cache cleared successfully'))->success();
        return back();
    }
}
