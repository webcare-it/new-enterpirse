@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid py-4">

        <style>
            .dash-card {
                border: 0;
                border-radius: 18px;
                box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
                overflow: hidden
            }

            .stat-card {
                color: #fff;
                border-radius: 20px;
                padding: 24px;
                min-height: 145px;
                position: relative;
                overflow: hidden;
                box-shadow: 0 14px 35px rgba(0, 0, 0, .13);
                transition: .3s
            }

            .stat-card:hover {
                transform: translateY(-5px)
            }

            .stat-card:after {
                content: "";
                position: absolute;
                width: 150px;
                height: 150px;
                right: -50px;
                bottom: -55px;
                background: rgba(255, 255, 255, .16);
                border-radius: 50%
            }

            .stat-icon {
                width: 54px;
                height: 54px;
                border-radius: 16px;
                background: rgba(255, 255, 255, .22);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 28px
            }

            .stat-title {
                font-size: 14px;
                opacity: .9
            }

            .stat-number {
                font-size: 32px;
                font-weight: 800;
                margin: 8px 0 0
            }

            .stat-small {
                font-size: 12px;
                opacity: .9
            }

            .bg-one {
                background: linear-gradient(135deg, #4f46e5, #06b6d4)
            }

            .bg-two {
                background: linear-gradient(135deg, #16a34a, #22c55e)
            }

            .bg-three {
                background: linear-gradient(135deg, #f59e0b, #f97316)
            }

            .bg-four {
                background: linear-gradient(135deg, #ec4899, #8b5cf6)
            }

            .bg-five {
                background: linear-gradient(135deg, #0f766e, #14b8a6)
            }

            .bg-six {
                background: linear-gradient(135deg, #dc2626, #fb7185)
            }

            .bg-seven {
                background: linear-gradient(135deg, #0284c7, #38bdf8)
            }

            .bg-eight {
                background: linear-gradient(135deg, #7c3aed, #a78bfa)
            }

            .section-title {
                font-weight: 700;
                color: #111827;
                margin-bottom: 0
            }

            .mini-text {
                color: #6b7280;
                font-size: 13px
            }

            .badge-soft-success {
                background: #dcfce7;
                color: #166534
            }

            .badge-soft-warning {
                background: #fef3c7;
                color: #92400e
            }

            .badge-soft-danger {
                background: #fee2e2;
                color: #991b1b
            }

            .badge-soft-info {
                background: #dbeafe;
                color: #1d4ed8
            }

            .badge-soft-secondary {
                background: #f1f5f9;
                color: #475569
            }

            .badge-soft-pink {
                background: #fce7f3;
                color: #be185d
            }

            .fake-chart {
                height: 260px;
                display: flex;
                align-items: end;
                gap: 12px;
                padding: 20px 10px 5px
            }

            .bar {
                flex: 1;
                border-radius: 12px 12px 0 0;
                background: linear-gradient(180deg, #4f46e5, #06b6d4);
                min-height: 45px;
                transition: height 0.5s ease
            }

            .product-img {
                width: 42px;
                height: 42px;
                border-radius: 12px;
                background: #f3f4f6;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                overflow: hidden
            }

            .product-img img {
                width: 100%;
                height: 100%;
                object-fit: cover
            }

            .quick-box {
                border: 1px solid #eef2f7;
                border-radius: 14px;
                padding: 16px;
                background: #fff
            }

            .progress {
                height: 8px;
                border-radius: 20px
            }

            .review-stars {
                color: #fbbf24;
                font-size: 13px;
                letter-spacing: 2px
            }

            .timeline-item {
                padding-left: 20px;
                border-left: 2px solid #e2e8f0;
                position: relative;
                margin-bottom: 20px
            }

            .timeline-item:before {
                content: "";
                width: 10px;
                height: 10px;
                background: #4f46e5;
                border-radius: 50%;
                position: absolute;
                left: -6px;
                top: 0
            }

            .category-tag {
                background: #f1f5f9;
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 12px;
                margin-right: 6px;
                margin-bottom: 6px;
                display: inline-block
            }

            .courier-logo {
                width: 32px;
                height: 32px;
                background: #f8fafc;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 16px
            }

            .fs-40 {
                font-size: 40px
            }

            .fs-60 {
                font-size: 60px
            }
        </style>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div>
                <h2 class="fw-bold mb-0">{{ translate('Dashboard') }}</h2>
                <small class="text-muted">{{ translate('Real-time store analytics') }}</small>
            </div>
            <div class="mt-2">
                <a href="{{ route('reports.orders') }}" class="btn btn-light shadow-sm mr-2"><i class="las la-download"></i>
                    {{ translate('Reports') }}</a>
                <a href="{{ route('products.create') }}" class="btn btn-primary px-4"><i class="las la-plus"></i>
                    {{ translate('Add Product') }}</a>
            </div>
        </div>

        <!-- Stats Row 1 - Core Metrics -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card bg-one">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-title">{{ translate('Total Sales') }}</div>
                            <h2 class="stat-number">৳{{ number_format($totalSales, 2) }}</h2>
                            <span class="stat-small">{{ translate('Lifetime revenue') }}</span>
                        </div>
                        <div class="stat-icon"><i class="las la-wallet"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card bg-two">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-title">{{ translate('Total Orders') }}</div>
                            <h2 class="stat-number">{{ number_format($totalOrders) }}</h2>
                            <span class="stat-small">{{ $ordersThisWeek }} {{ translate('this week') }}</span>
                        </div>
                        <div class="stat-icon"><i class="las la-shopping-cart"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card bg-three">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-title">{{ translate('Total Products') }}</div>
                            <h2 class="stat-number">{{ number_format($totalProducts) }}</h2>
                            <span class="stat-small">{{ $lowStockProducts }} {{ translate('low stock') }}</span>
                        </div>
                        <div class="stat-icon"><i class="las la-box"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card bg-four">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-title">{{ translate('Customers') }}</div>
                            <h2 class="stat-number">{{ number_format($totalCustomers) }}</h2>
                            <span class="stat-small">{{ $newCustomersThisMonth }} {{ translate('new this month') }}</span>
                        </div>
                        <div class="stat-icon"><i class="las la-users"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Row 2 - Operational Metrics -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card bg-five">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-title">{{ translate('Pending Delivery') }}</div>
                            <h2 class="stat-number">{{ number_format($pendingOrders) }}</h2>
                            <span class="stat-small">{{ translate('Need courier assign') }}</span>
                        </div>
                        <div class="stat-icon"><i class="las la-truck"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card bg-six">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-title">{{ translate('Refund Requests') }}</div>
                            <h2 class="stat-number">{{ number_format($refundedOrders) }}</h2>
                            <span class="stat-small">৳{{ number_format($refundAmount, 2) }}
                                {{ translate('total') }}</span>
                        </div>
                        <div class="stat-icon"><i class="las la-undo"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card bg-seven">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-title">{{ translate('Avg. Order Value') }}</div>
                            <h2 class="stat-number">৳{{ number_format($avgOrderValue, 2) }}</h2>
                            <span class="stat-small">{{ translate('Per order average') }}</span>
                        </div>
                        <div class="stat-icon"><i class="las la-chart-bar"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card bg-eight">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-title">{{ translate('Conversion Rate') }}</div>
                            <h2 class="stat-number">{{ $conversionRate }}%</h2>
                            <span class="stat-small">{{ translate('Visitors to order') }}</span>
                        </div>
                        <div class="stat-icon"><i class="las la-chart-line"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Cards Row -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="quick-box">
                    <small class="text-muted">{{ translate('Today Revenue') }}</small>
                    <h4 class="fw-bold mb-1">৳ {{ number_format($todayRevenue, 2) }}</h4>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width:{{ $todayProgress }}%"></div>
                    </div>
                    <small class="text-muted">{{ $todayProgress }}% {{ translate('target completed') }}</small>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="quick-box">
                    <small class="text-muted">{{ translate('Customer Satisfaction') }}</small>
                    <h4 class="fw-bold mb-1">{{ number_format($avgRating, 1) }}
                        <span class="review-stars">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($avgRating))
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#f59e0b"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z">
                                        </path>
                                    </svg>
                                @else
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b"
                                        stroke-width="2"
                                        xmlns="http:'
                                        //www.w3.org/2000/svg">
                                        <path
                                            d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z">
                                        </path>
                                    </svg>
                                @endif
                            @endfor
                        </span>
                    </h4>
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width:{{ ($avgRating / 5) * 100 }}%"></div>
                    </div>
                    <small class="text-muted">{{ $positiveReviewRate }}% {{ translate('positive reviews') }}</small>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="quick-box">
                    <small class="text-muted">{{ translate('Return Rate') }}</small>
                    <h4 class="fw-bold mb-1">{{ $returnRate }}%</h4>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width:{{ min(100, $returnRate * 10) }}%"></div>
                    </div>
                    <small class="text-muted">{{ translate('Order cancellation rate') }}</small>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="quick-box">
                    <small class="text-muted">{{ translate('Customer LTV') }}</small>
                    <h4 class="fw-bold mb-1">৳ {{ number_format($customerLTV, 2) }}</h4>
                    <div class="progress">
                        <div class="progress-bar bg-primary" style="width:{{ min(100, ($customerLTV / 10000) * 100) }}%">
                        </div>
                    </div>
                    <small class="text-muted">{{ translate('Lifetime value per customer') }}</small>
                </div>
            </div>
        </div>

        <!-- Customer Segmentation & Marketing Performance & Peak Hours -->
        <div class="row mb-4">
            <!-- Customer Segmentation -->
            <div class="col-lg-4 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Customer Segmentation') }}</h5>
                        <span class="mini-text">{{ translate('Customer behavior analysis') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="border-end">
                                    <div class="h2 mb-0 text-primary">{{ number_format($newCustomersThisMonth) }}</div>
                                    <div class="small text-muted">{{ translate('New Customers') }}</div>
                                    <small class="text-success">+{{ $newCustomersThisMonth }}
                                        {{ translate('this month') }}</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-end">
                                    <div class="h2 mb-0 text-warning">{{ number_format($returningCustomers) }}</div>
                                    <div class="small text-muted">{{ translate('Returning') }}</div>
                                    <small>{{ $totalCustomers > 0 ? round(($returningCustomers / $totalCustomers) * 100, 1) : 0 }}%</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div>
                                    <div class="h2 mb-0 text-info">{{ number_format($oneTimeCustomers) }}</div>
                                    <div class="small text-muted">{{ translate('One-Time') }}</div>
                                    <small>{{ $totalCustomers > 0 ? round(($oneTimeCustomers / $totalCustomers) * 100, 1) : 0 }}%</small>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 pt-2 border-top">
                            <div class="d-flex justify-content-between">
                                <span>{{ translate('Avg Order Frequency') }}:</span>
                                <strong>{{ $avgOrderFrequency }} {{ translate('orders/customer') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>{{ translate('Verified Customers') }}:</span>
                                <strong>{{ number_format($verifiedCustomers) }}
                                    ({{ $totalCustomers > 0 ? round(($verifiedCustomers / $totalCustomers) * 100, 1) : 0 }}%)</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Marketing Performance -->
            <div class="col-lg-4 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Marketing Performance') }}</h5>
                        <span class="mini-text">{{ translate('Campaign & coupon insights') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="small text-muted">{{ translate('Active Campaigns') }}</div>
                                <div class="h4 mb-0">{{ $activeCampaigns }}</div>
                            </div>
                            <div>
                                <div class="small text-muted">{{ translate('Landing Pages') }}</div>
                                <div class="h4 mb-0">{{ $landingPages->count() }}</div>
                            </div>
                            <div>
                                <div class="small text-muted">{{ translate('Landing Visits') }}</div>
                                <div class="h4 mb-0">{{ number_format($totalLandingPageVisits) }}</div>
                            </div>
                        </div>
                        <div class="progress mb-2" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: {{ $couponUsageRate }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ translate('Coupon Usage') }}:</span>
                            <strong>{{ $couponUsageRate }}% ({{ $ordersWithCoupon }} {{ translate('orders') }})</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>{{ translate('Coupon Savings') }}:</span>
                            <strong class="text-success">৳{{ number_format($totalCouponDiscount, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Peak Hours Analysis -->
            <div class="col-lg-4 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Peak Order Hours') }}</h5>
                        <span class="mini-text">{{ translate('When customers order most') }}</span>
                    </div>
                    <div class="card-body">
                        <canvas id="peakHoursChart" style="height: 200px; width: 100%;"></canvas>
                        <div class="mt-3 text-center">
                            @php
                                $maxHour = array_keys($peakHours, max($peakHours))[0] ?? 0;
                                $peakHourFormatted = date('g:i A', strtotime("{$maxHour}:00"));
                            @endphp
                            <span class="badge badge-soft-info">Peak Time: {{ $peakHourFormatted }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Overview & Weekly Trends -->
        <div class="row mb-4">
            <!-- Sales Overview -->
            <div class="col-lg-6 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="section-title">{{ translate('Sales Overview') }}</h5>
                            <span class="mini-text">{{ translate('Monthly sales performance') }}</span>
                        </div>
                        <span class="badge badge-soft-info px-3 py-2">{{ date('Y') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="fake-chart">
                            @foreach ($monthlySales as $sale)
                                @php
                                    $maxSale = max($monthlySales) > 0 ? max($monthlySales) : 1;
                                    $height = max(45, ($sale / $maxSale) * 100);
                                @endphp
                                <div class="bar" style="height: {{ $height }}%"></div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-between text-muted small px-2">
                            @foreach ($monthlyLabels as $label)
                                <span>{{ $label }}</span>
                            @endforeach
                        </div>
                        <div class="mt-3 text-center">
                            <span class="badge badge-soft-success">Revenue Growth:
                                {{ $revenueGrowth > 0 ? '+' : '' }}{{ $revenueGrowth }}% vs last month</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weekly Trends Chart -->
            <div class="col-lg-6 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Weekly Performance Trend') }}</h5>
                        <span class="mini-text">{{ translate('Last 7 weeks analysis') }}</span>
                    </div>
                    <div class="card-body">
                        <canvas id="weeklyTrendChart" style="height: 250px; width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Top Search Keywords -->
        <div class="row mb-4">
            <!-- Recent Activity -->
            <div class="col-lg-6 mb-3">
                <div class="card dash-card h-100">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Recent Activity') }}</h5>
                        <span class="mini-text">{{ translate('Latest store updates') }}</span>
                    </div>
                    <div class="card-body">
                        @foreach ($recentActivities->take(8) as $activity)
                            <div class="timeline-item">
                                <small class="text-muted">{{ $activity['time'] }}</small>
                                <p class="mb-0 small">
                                    @if ($activity['type'] == 'order')
                                        <i class="las la-shopping-cart text-primary"></i>
                                        <strong>{{ $activity['title'] }}</strong> - {{ $activity['description'] }}
                                        <span class="text-success">৳{{ number_format($activity['amount'], 2) }}</span>
                                    @elseif($activity['type'] == 'user')
                                        <i class="las la-user-plus text-success"></i>
                                        <strong>{{ $activity['title'] }}</strong> - {{ $activity['description'] }}
                                    @elseif($activity['type'] == 'review')
                                        <i class="las la-star text-warning"></i>
                                        <strong>{{ $activity['title'] }}</strong> - {{ $activity['description'] }}
                                        <span class="review-stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $activity['rating'])
                                                    <svg width="18" height="18" viewBox="0 0 24 24"
                                                        fill="#f59e0b" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                                                    </svg>
                                                @else
                                                    <svg width="18" height="18" viewBox="0 0 24 24"
                                                        fill="none" stroke="#f59e0b" stroke-width="2"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                                                    </svg>
                                                @endif
                                            @endfor
                                        </span>
                                    @else
                                        <i class="las la-exclamation-triangle text-danger"></i>
                                        <strong>{{ $activity['title'] }}</strong> - {{ $activity['description'] }}
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top Search Keywords -->
            <div class="col-lg-6 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="section-title">{{ translate('Top Search Keywords') }}</h5>
                            <span class="mini-text">{{ translate('What customers are looking for') }}</span>
                        </div>
                        <a href="{{ route('reports.user-searches') }}"
                            class="btn btn-sm btn-outline-primary">{{ translate('View All') }}</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                @foreach ($topSearchKeywords->take(3) as $keyword)
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span><i class="las la-search text-muted"></i> {{ $keyword->query }}</span>
                                        <span class="badge badge-primary">{{ number_format($keyword->count) }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                @foreach ($topSearchKeywords->skip(3)->take(3) as $keyword)
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span><i class="las la-search text-muted"></i> {{ $keyword->query }}</span>
                                        <span class="badge badge-primary">{{ number_format($keyword->count) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-2 pt-2 border-top">
                            <div class="d-flex justify-content-between">
                                <span>{{ translate('Total Searches') }}:</span>
                                <strong>{{ number_format($totalSearches) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>{{ translate('Unique Keywords') }}:</span>
                                <strong>{{ number_format($uniqueKeywords) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products & Top Categories & Top Brands -->
        <div class="row mb-4">
            <!-- Top Products -->
            <div class="col-lg-4 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Top Products') }}</h5>
                        <span class="mini-text">{{ translate('Best selling items') }}</span>
                    </div>
                    <div class="card-body">
                        @foreach ($topProducts as $product)
                            <div class="d-flex align-items-center mb-3">
                                <div class="product-img mr-3">
                                    <img src="{{ uploaded_asset(optional($product->product)->thumbnail) }}"
                                        alt="{{ optional($product->product)->name ?? 'Product Deleted' }}">
                                </div>

                                <div class="flex-grow-1">
                                    <strong>
                                        {{ Str::limit(optional($product->product)->name ?? 'Product Deleted', 20) }}
                                    </strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $product->total_sold }} {{ translate('sales') }}
                                    </small>
                                </div>

                                <div class="text-right">
                                    <strong class="text-primary">
                                        ৳{{ number_format($product->total_revenue, 2) }}
                                    </strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-3 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Courier Performance') }}</h5>
                        <span class="mini-text">{{ translate('Delivery stats') }}</span>
                    </div>
                    <div class="card-body">
                        @foreach ($courierStats as $name => $stats)
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <div><strong>{{ $name }}</strong><br><small class="text-muted">Avg
                                            {{ $stats['avg_days'] }} days</small></div>
                                </div>
                                <span class="badge badge-soft-success">{{ $stats['on_time'] }}% on-time</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top Categories -->
            <div class="col-lg-4 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Top Categories') }}</h5>
                        <span class="mini-text">{{ translate('Sales by category') }}</span>
                    </div>
                    <div class="card-body">
                        @foreach ($topCategories as $category)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <strong>{{ $category->category_name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ number_format($category->total_sold) }}
                                        {{ translate('units sold') }}</small>
                                </div>
                                <div class="text-right">
                                    <strong
                                        class="text-success">৳{{ number_format($category->total_revenue, 2) }}</strong>
                                </div>
                            </div>
                            <div class="progress mb-3" style="height: 6px;">
                                @php $maxCategorySale = $topCategories->max('total_sold') ?: 1; @endphp
                                <div class="progress-bar bg-primary"
                                    style="width: {{ ($category->total_sold / $maxCategorySale) * 100 }}%"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top Brands -->
            <div class="col-lg-4 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Top Brands') }}</h5>
                        <span class="mini-text">{{ translate('Best performing brands') }}</span>
                    </div>
                    <div class="card-body">
                        @foreach ($topBrands as $brand)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <strong>{{ $brand->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ number_format($brand->total_sold) }}
                                        {{ translate('units') }}</small>
                                </div>
                                <div class="text-right">
                                    <strong class="text-warning">৳{{ number_format($brand->total_revenue, 2) }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-4 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Most Wishlisted') }}</h5>
                        <span class="mini-text">{{ translate('Products customers want') }}</span>
                    </div>
                    <div class="card-body">
                        @foreach ($mostWishlisted as $product)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <strong>{{ Str::limit($product->name, 25) }}</strong>
                                    <br>
                                    <small class="text-muted">ID: #{{ $product->id }}</small>
                                </div>
                                <span class="badge badge-soft-pink">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="#ec4899"
                                        xmlns="http://www.w3.org/2000/svg"
                                        style="vertical-align: middle; margin-top: -2px;">
                                        <path
                                            d="M12 21.35L10.55 20.03C5.4 15.36 2 12.28 2 8.5C2 5.42 4.42 3 7.5 3C9.24 3 10.91 3.81 12 5.09C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.42 22 8.5C22 12.28 18.6 15.36 13.45 20.03L12 21.35Z" />
                                    </svg>

                                    {{ number_format($product->wishlists_count) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Most Reviewed -->
            <div class="col-lg-4 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Most Reviewed') }}</h5>
                        <span class="mini-text">{{ translate('Most discussed products') }}</span>
                    </div>
                    <div class="card-body">
                        @foreach ($mostReviewed as $product)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <strong>{{ Str::limit($product->name, 25) }}</strong>
                                    <br>
                                    <small class="text-muted">Avg:
                                        {{ number_format($product->reviews_avg_rating ?? 0, 1) }}★</small>
                                </div>
                                <span class="badge badge-soft-info d-inline-flex align-items-center">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"
                                        xmlns="http://www.w3.org/2000/svg" class="mr-1">
                                        <path
                                            d="M20 2H4C2.9 2 2.01 2.9 2.01 4L2 22L6 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2Z" />
                                    </svg>

                                    {{ number_format($product->reviews_count) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


            <div class="col-lg-4 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Top Selling Variants') }}</h5>
                        <span class="mini-text">{{ translate('Most popular variations') }}</span>
                    </div>
                    <div class="card-body">
                        @foreach ($topSellingVariants as $variant)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <strong>{{ $variant->variation }}</strong>
                                </div>
                                <span class="badge badge-success">{{ number_format($variant->total_sold) }}
                                    {{ translate('sold') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-4 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Inventory Alerts') }}</h5>
                        <span class="mini-text">{{ translate('Products need attention') }}</span>
                    </div>
                    <div class="card-body">
                        @forelse($inventoryAlerts as $alert)
                            <div class="alert alert-warning mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $alert->product->name }}</strong>
                                        <br>
                                        <small>Stock: <span class="text-danger">{{ $alert->stock }}</span> left</small>
                                    </div>
                                    <i class="las la-exclamation-triangle fs-24"></i>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-success mb-0">
                                <i class="las la-check-circle"></i>
                                {{ translate('All products have sufficient stock!') }}
                            </div>
                        @endforelse
                        <div class="mt-2 pt-2 border-top">
                            <div class="d-flex justify-content-between">
                                <span>{{ translate('Out of Stock') }}:</span>
                                <strong class="text-danger">{{ number_format($outOfStockProducts) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>{{ translate('Total Stock Value') }}:</span>
                                <strong>৳{{ number_format($totalStockValue, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods Breakdown -->
            <div class="col-lg-4 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Payment Methods') }}</h5>
                        <span class="mini-text">{{ translate('Transaction breakdown') }}</span>
                    </div>
                    <div class="card-body">
                        @foreach ($paymentMethods as $method)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <strong>{{ ucfirst(str_replace('_', ' ', $method->payment_type)) }}</strong>
                                    <br>
                                    <small class="text-muted">{{ number_format($method->count) }}
                                        {{ translate('transactions') }}
                                    </small>
                                </div>
                                <div class="text-right">
                                    <strong>৳{{ number_format($method->total, 2) }}</strong>
                                </div>
                            </div>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar bg-info"
                                    style="width: {{ ($method->total / max($totalSales, 1)) * 100 }}%"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Order Summary') }}</h5>
                        <span class="mini-text">{{ translate('Current order status') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="border-end">
                                    <div class="h4 mb-0 text-primary">{{ number_format($paidOrders) }}</div>
                                    <div class="small">{{ translate('Paid Orders') }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <div class="h4 mb-0 text-warning">{{ number_format($unpaidOrders) }}</div>
                                    <div class="small">{{ translate('Unpaid Orders') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ translate('Pending') }}:</span>
                                <strong>{{ number_format($pendingOrders) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ translate('Processing') }}:</span>
                                <strong>{{ number_format($processingOrders) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ translate('Shipped') }}:</span>
                                <strong>{{ number_format($shippedOrders) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ translate('Delivered') }}:</span>
                                <strong class="text-success">{{ number_format($deliveredOrders) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>{{ translate('Cancelled') }}:</span>
                                <strong class="text-danger">{{ number_format($cancelledOrders) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <!-- Revenue Summary -->
            <div class="col-lg-4 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Revenue Summary') }}</h5>
                        <span class="mini-text">{{ translate('Financial overview') }}</span>
                    </div>
                    <div class="card-body">
                        <p class="d-flex justify-content-between mb-2">
                            <span>{{ translate('Today') }}:</span>
                            <strong class="text-success">৳{{ number_format($todayRevenue, 2) }}</strong>
                        </p>
                        <p class="d-flex justify-content-between mb-2">
                            <span>{{ translate('This Week') }}:</span>
                            <strong>৳{{ number_format($weekRevenue, 2) }}</strong>
                        </p>
                        <p class="d-flex justify-content-between mb-2">
                            <span>{{ translate('This Month') }}:</span>
                            <strong class="text-primary">৳{{ number_format($monthRevenue, 2) }}</strong>
                        </p>
                        <p class="d-flex justify-content-between mb-2">
                            <span>{{ translate('This Year') }}:</span>
                            <strong>৳{{ number_format($yearRevenue, 2) }}</strong>
                        </p>
                        <p class="d-flex justify-content-between pt-2 border-top">
                            <span>{{ translate('Refunded') }}:</span>
                            <strong class="text-danger">-৳{{ number_format($refundAmount, 2) }}</strong>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="col-lg-8 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="section-title">{{ translate('Recent Orders') }}</h5>
                            <span class="mini-text">{{ translate('Latest customer orders') }}</span>
                        </div>
                        <a href="{{ route('orders.index') }}"
                            class="btn btn-sm btn-outline-primary">{{ translate('View All') }}</a>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>{{ translate('Order') }}</th>
                                    <th>{{ translate('Customer') }}</th>
                                    <th>{{ translate('Amount') }}</th>
                                    <th>{{ translate('Payment') }}</th>
                                    <th>{{ translate('Delivery') }}</th>
                                    <th>{{ translate('Date') }}</th>
                                    </td>
                            </thead>
                            <tbody>
                                @foreach ($recentOrdersList as $order)
                                    <tr>
                                        <td><strong>#{{ $order->code }}</strong>
                    </div>
                    <td>{{ $order->user->name ?? 'Guest User' }}
                </div>
                <td>৳{{ number_format($order->grand_total, 2) }}
            </div>
            <td>
                @if ($order->payment_status == 'paid')
                    <span class="badge badge-soft-success">{{ translate('Paid') }}</span>
                @else
                    <span class="badge badge-soft-warning">{{ translate('COD') }}</span>
                @endif
        </div>
        <td>
            @php
                $badgeClass =
                    [
                        'pending' => 'badge-soft-secondary',
                        'processing' => 'badge-soft-info',
                        'shipped' => 'badge-soft-info',
                        'delivered' => 'badge-soft-success',
                        'cancelled' => 'badge-soft-danger',
                    ][$order->delivery_status] ?? 'badge-soft-secondary';
            @endphp
            <span class="badge {{ $badgeClass }}">{{ ucfirst($order->delivery_status) }}</span>
    </div>
    <td>{{ $order->created_at->format('d M Y') }}</div>
        </tr>
        @endforeach
        </tbody>
        </table>
        </div>
        </div>
        </div>
        </div>

        <!-- Top Customers & Recent Reviews & Popular Tags -->
        <div class="row">
            <!-- Top Customers -->
            <div class="col-lg-4 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Top Customers') }}</h5>
                        <span class="mini-text">{{ translate('Most valuable customers') }}</span>
                    </div>
                    <div class="card-body">
                        @foreach ($topCustomers as $customer)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm mr-2">
                                        @if ($customer->user && $customer->user->avatar)
                                            <img src="{{ uploaded_asset($customer->user->avatar) }}"
                                                class="rounded-circle">
                                        @else
                                            <img src="{{ asset('default/avatar.jpg') }}" class="rounded-circle">
                                        @endif
                                    </div>
                                    <div>
                                        <strong>{{ $customer->user->name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $customer->order_count }}
                                            {{ translate('orders') }}</small>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <strong class="text-success">৳{{ number_format($customer->total_spent, 2) }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="col-lg-4 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Recent Reviews') }}</h5>
                        <span class="mini-text">{{ translate('Latest customer feedback') }}</span>
                    </div>
                    <div class="card-body">
                        @foreach ($recentReviewsList as $review)
                            <div class="mb-3">
                                <div class="review-stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#f59e0b"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                                            </svg>
                                        @else
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="#f59e0b" stroke-width="2" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <p class="small mb-0">"{{ Str::limit($review->comment, 60) }}"</p>
                                <small class="text-muted">- {{ $review->user->name ?? 'Guest' }} ·
                                    {{ $review->created_at->diffForHumans() }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Popular Tags -->
            <div class="col-lg-4 mb-3">
                <div class="card dash-card">
                    <div class="card-header bg-white">
                        <h5 class="section-title">{{ translate('Popular Tags') }}</h5>
                        <span class="mini-text">{{ translate('Trending search terms') }}</span>
                    </div>
                    <div class="card-body">
                        <div>
                            @foreach ($popularTags as $tag)
                                <span class="category-tag">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    @endsection

    @section('script')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            $(document).ready(function() {
                $('.progress-bar').each(function() {
                    var width = $(this).css('width');
                    $(this).css('width', 0);
                    setTimeout(function() {
                        $(this).css('width', width);
                    }.bind(this), 100);
                });

                // Animate bars on chart
                $('.bar').each(function() {
                    var height = $(this).css('height');
                    $(this).css('height', 0);
                    setTimeout(function() {
                        $(this).css('height', height);
                    }.bind(this), 100);
                });
            });

            var peakCtx = document.getElementById('peakHoursChart').getContext('2d');
            new Chart(peakCtx, {
                type: 'line',
                data: {
                    labels: ['12a', '1a', '2a', '3a', '4a', '5a', '6a', '7a', '8a', '9a', '10a', '11a', '12p', '1p',
                        '2p', '3p', '4p', '5p', '6p', '7p', '8p', '9p', '10p', '11p'
                    ],
                    datasets: [{
                        label: 'Orders',
                        data: @json(array_values($peakHours)),
                        backgroundColor: 'rgba(79, 70, 229, 0.2)',
                        borderColor: '#4f46e5',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            var weeklyCtx = document.getElementById('weeklyTrendChart').getContext('2d');
            new Chart(weeklyCtx, {
                type: 'bar',
                data: {
                    labels: @json($weeklyLabels),
                    datasets: [{
                            label: 'Orders',
                            data: @json($weeklyOrders),
                            backgroundColor: 'rgba(79, 70, 229, 0.7)',
                            borderRadius: 8,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Revenue (৳)',
                            data: @json($weeklyRevenue),
                            backgroundColor: 'rgba(34, 197, 94, 0.7)',
                            borderRadius: 8,
                            yAxisID: 'y1',
                            type: 'line'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    if (context.dataset.label === 'Revenue (৳)') {
                                        return 'Revenue: ৳' + context.raw.toLocaleString();
                                    }
                                    return context.dataset.label + ': ' + context.raw;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            title: {
                                display: true,
                                text: 'Orders'
                            },
                            beginAtZero: true
                        },
                        y1: {
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Revenue (৳)'
                            },
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });
        </script>
    @endsection
