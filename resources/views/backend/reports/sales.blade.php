@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Sales Report') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('reports.export-sales', request()->query()) }}" class="btn btn-success">
                    <i class="las la-file-excel"></i> {{ translate('Export CSV') }}
                </a>
            </div>
        </div>
    </div>

    <style>
        .mm-card {
            min-height: 150px;
            border-radius: 20px;
            padding: 24px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 14px 32px rgba(15, 23, 42, .14);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            transition: .25s ease;
            margin-bottom: 20px;
        }

        .mm-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 42px rgba(15, 23, 42, .20);
        }

        .mm-card::before {
            content: "";
            position: absolute;
            width: 150px;
            height: 150px;
            right: -45px;
            bottom: -55px;
            background: rgba(255, 255, 255, .16);
            border-radius: 50%;
        }

        .mm-card::after {
            content: "";
            position: absolute;
            width: 75px;
            height: 75px;
            right: 55px;
            top: -28px;
            background: rgba(255, 255, 255, .10);
            border-radius: 50%;
        }

        .mm-content {
            position: relative;
            z-index: 2;
            width: calc(100% - 70px);
        }

        .stat-label {
            font-size: 14px;
            font-weight: 700;
            opacity: .96;
            margin-bottom: 10px;
            color: #fff;
        }

        .stat-number {
            font-size: 33px;
            font-weight: 900;
            margin: 0 0 8px;
            line-height: 1.1;
            color: #fff;
            letter-spacing: -.5px;
            word-break: break-word;
        }

        .stat-small {
            font-size: 13px;
            font-weight: 600;
            margin: 0;
            opacity: .92;
            color: #fff;
        }

        .mm-icon {
            width: 56px;
            height: 56px;
            border-radius: 18px;
            background: rgba(255, 255, 255, .18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 29px;
            position: relative;
            z-index: 2;
            flex-shrink: 0;
            backdrop-filter: blur(4px);
        }

        .mm-card-blue {
            background: linear-gradient(135deg, #405cf5, #11b4d8);
        }

        .mm-card-green {
            background: linear-gradient(135deg, #0f9f52, #22c55e);
        }

        .mm-card-orange {
            background: linear-gradient(135deg, #ff9f1c, #ff6b00);
        }

        .mm-card-purple {
            background: linear-gradient(135deg, #d946ef, #7c3aed);
        }

        .mm-card-pink {
            background: linear-gradient(135deg, #ff758c, #ff7eb3);
        }

        .mm-card-dark {
            background: linear-gradient(135deg, #334155, #0f172a);
        }

        .mm-card-cyan {
            background: linear-gradient(135deg, #06b6d4, #3b82f6);
        }

        .mm-card-indigo {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
        }

        .mm-card-red {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .time-filter-chip {
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 30px;
            padding: 8px 20px;
            margin-right: 10px;
            margin-bottom: 10px;
            display: inline-block;
            font-size: 13px;
            font-weight: 500;
        }

        .time-filter-chip.active {
            background: #405cf5;
            color: #fff;
            border-color: #405cf5;
            box-shadow: 0 2px 8px rgba(64, 92, 245, 0.3);
        }

        .time-filter-chip.inactive {
            background: #f3f4f6;
            color: #6b7280;
            border: 1px solid #e5e7eb;
        }

        .time-filter-chip.inactive:hover {
            background: #e5e7eb;
            color: #374151;
            transform: translateY(-1px);
        }

        .time-filter-group {
            background: #f9fafb;
            border-radius: 16px;
            padding: 15px 20px;
            margin-bottom: 25px;
            border: 1px solid #e5e7eb;
        }

        .time-filter-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #374151;
        }

        .stat-card-small {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .stat-card-small:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .stat-card-title {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .stat-card-value {
            font-size: 28px;
            font-weight: 800;
            color: #1f2937;
        }

        .stat-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .trend-btn {
            cursor: pointer;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .trend-btn.active {
            background: #405cf5;
            color: white;
        }

        .trend-btn.inactive {
            background: #f3f4f6;
            color: #6b7280;
        }

        .trend-up {
            color: #10b981;
        }

        .trend-down {
            color: #ef4444;
        }

        @media (min-width: 1200px) {
            .col-xl-2 .mm-card {
                padding: 18px;
                min-height: 145px;
            }

            .col-xl-2 .stat-number {
                font-size: 24px;
            }

            .col-xl-2 .stat-label {
                font-size: 13px;
            }

            .col-xl-2 .stat-small {
                font-size: 12px;
            }

            .col-xl-2 .mm-icon {
                width: 46px;
                height: 46px;
                font-size: 23px;
                border-radius: 14px;
            }

            .col-xl-2 .mm-content {
                width: calc(100% - 54px);
            }
        }
    </style>

    {{-- Time Filter Chips --}}
    <div class="time-filter-group">
        <div class="time-filter-title">
            <i class="las la-calendar-alt mr-2"></i> {{ translate('Quick Date Filters') }}
        </div>
        <div>
            <span class="time-filter-chip {{ $timeFilter == 'today' ? 'active' : 'inactive' }}" data-filter="today">
                <i class="las la-calendar-day"></i> {{ translate('Today') }}
            </span>
            <span class="time-filter-chip {{ $timeFilter == '7days' ? 'active' : 'inactive' }}" data-filter="7days">
                <i class="las la-calendar-week"></i> {{ translate('Last 7 Days') }}
            </span>
            <span class="time-filter-chip {{ $timeFilter == '30days' ? 'active' : 'inactive' }}" data-filter="30days">
                <i class="las la-calendar-alt"></i> {{ translate('Last 30 Days') }}
            </span>
            <span class="time-filter-chip {{ $timeFilter == 'month' ? 'active' : 'inactive' }}" data-filter="month">
                <i class="las la-calendar-month"></i> {{ translate('This Month') }}
            </span>
            <span class="time-filter-chip {{ $timeFilter == 'year' ? 'active' : 'inactive' }}" data-filter="year">
                <i class="las la-calendar-year"></i> {{ translate('This Year') }}
            </span>
            <span class="time-filter-chip {{ $timeFilter == 'all' ? 'active' : 'inactive' }}" data-filter="all">
                <i class="las la-infinity"></i> {{ translate('All Time') }}
            </span>
        </div>
        <style>
            .time-filter-chip {
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 8px;

                padding: 12px 18px;
                margin-right: 10px;
                margin-bottom: 10px;

                border-radius: 14px;
                border: 1px solid #e5e7eb;

                font-size: 13px;
                font-weight: 700;

                transition: all .25s ease;
                user-select: none;
            }

            .time-filter-chip i {
                font-size: 15px;
            }

            .time-filter-chip.active {
                background: linear-gradient(135deg, #405cf5, #11b4d8);
                color: #fff;
                border-color: transparent;
                box-shadow: 0 8px 20px rgba(64, 92, 245, .25);
            }

            .time-filter-chip.active:hover {
                color: #fff;
                transform: translateY(-2px);
            }

            .time-filter-chip.inactive {
                background: #ffffff;
                color: #64748b;
                border: 1px solid #e5e7eb;
            }

            .time-filter-chip.inactive:hover {
                background: #eef2ff;
                color: #405cf5;
                border-color: #c7d2fe;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
            }

            @media (max-width: 768px) {
                .time-filter-chip {
                    padding: 10px 14px;
                    font-size: 12px;
                    margin-right: 6px;
                    margin-bottom: 6px;
                }
            }
        </style>
    </div>

    {{-- Main Statistics Cards Row 1 --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-blue">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Total Revenue') }}</div>
                    <div class="stat-number">৳{{ number_format($totalRevenue, 2) }}</div>
                    <div class="stat-small">{{ translate('From paid orders') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-chart-line"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-green">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Total Orders') }}</div>
                    <div class="stat-number">{{ number_format($totalOrders) }}</div>
                    <div class="stat-small">{{ translate('All orders') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-shopping-cart"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-orange">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Average Order Value') }}</div>
                    <div class="stat-number">৳{{ number_format($avgOrderValue, 2) }}</div>
                    <div class="stat-small">{{ translate('Per order average') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-calculator"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-purple">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Delivered Orders') }}</div>
                    <div class="stat-number">{{ number_format($totalDeliveredOrders) }}</div>
                    <div class="stat-small">{{ translate('Successfully delivered') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Time-based Statistics Cards Row 2 --}}
    <div class="row mb-4">

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="mm-card mm-card-red">
                <div class="mm-content">
                    <div class="stat-label">{{ translate("Today's Sales") }}</div>
                    <div class="stat-number">৳{{ number_format($todaySales, 2) }}</div>
                    <div class="stat-small">{{ $todayOrders }} {{ translate('orders') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-calendar-day"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="mm-card mm-card-green">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Last 7 Days Sales') }}</div>
                    <div class="stat-number">৳{{ number_format($weekSales, 2) }}</div>
                    <div class="stat-small">{{ $weekOrders ?? 0 }} {{ translate('orders') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-calendar-week"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="mm-card mm-card-cyan">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Last 30 Days Sales') }}</div>
                    <div class="stat-number">৳{{ number_format($last30DaysSales ?? 0, 2) }}</div>
                    <div class="stat-small">{{ $last30DaysOrders ?? 0 }} {{ translate('orders') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-calendar-alt"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="mm-card mm-card-purple">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('This Month Sales') }}</div>
                    <div class="stat-number">৳{{ number_format($monthSales, 2) }}</div>
                    <div class="stat-small">{{ $monthOrders ?? 0 }} {{ translate('orders') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-calendar-month"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="mm-card mm-card-orange">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('This Year Sales') }}</div>
                    <div class="stat-number">৳{{ number_format($yearSales, 2) }}</div>
                    <div class="stat-small">{{ $yearOrders ?? 0 }} {{ translate('orders') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-calendar"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="mm-card mm-card-dark">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Pending Orders') }}</div>
                    <div class="stat-number">{{ number_format($totalOrders - $totalDeliveredOrders) }}</div>
                    <div class="stat-small">{{ translate('Awaiting delivery') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-clock"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- Sales Trend Analysis with Toggle Buttons --}}
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center w-100 flex-wrap">
                <h5 class="mb-0 h6">{{ translate('Sales Trend Analysis') }}</h5>
                <div class="trend-filter-group">

                    <button type="button" class="trend-filter-btn active" id="trend7DaysBtn"
                        onclick="loadTrendData('7days')">

                        <i class="las la-calendar-week"></i>
                        {{ translate('Last 7 Days') }}
                    </button>

                    <button type="button" class="trend-filter-btn" id="trend30DaysBtn"
                        onclick="loadTrendData('30days')">

                        <i class="las la-calendar-alt"></i>
                        {{ translate('Last 30 Days') }}
                    </button>

                    <button type="button" class="trend-filter-btn" id="trendYearBtn" onclick="loadTrendData('year')">

                        <i class="las la-calendar"></i>
                        {{ translate('Last Year') }}
                    </button>

                </div>
                <style>
                    .trend-filter-group {
                        display: inline-flex;
                        gap: 12px;
                        padding: 8px;
                        background: #f8fafc;
                        border-radius: 16px;
                        border: 1px solid #e5e7eb;
                        flex-wrap: wrap;
                    }

                    .trend-filter-btn {
                        border: none;
                        outline: none;
                        cursor: pointer;

                        display: inline-flex;
                        align-items: center;
                        gap: 8px;

                        padding: 12px 18px;
                        border-radius: 12px;

                        background: transparent;
                        color: #64748b;

                        font-size: 13px;
                        font-weight: 700;

                        transition: all .25s ease;
                    }

                    .trend-filter-btn:hover {
                        background: #eef2ff;
                        color: #405cf5;
                        transform: translateY(-2px);
                    }

                    .trend-filter-btn.active {
                        background: linear-gradient(135deg, #405cf5, #11b4d8);
                        color: #fff;
                        box-shadow: 0 8px 20px rgba(64, 92, 245, .25);
                    }

                    .trend-filter-btn.active:hover {
                        color: #fff;
                    }

                    .trend-filter-btn i {
                        font-size: 16px;
                    }

                    @media (max-width: 768px) {
                        .trend-filter-group {
                            width: 100%;
                        }

                        .trend-filter-btn {
                            flex: 1;
                            justify-content: center;
                            padding: 10px;
                            font-size: 12px;
                        }
                    }
                </style>
            </div>
        </div>
        <div class="card-body">
            <div id="trendChartContainer">
                <canvas id="salesTrendChart" style="max-height: 450px; width: 100%;"></canvas>
            </div>
            <div id="trendSummary" class="mt-3 text-center text-muted"></div>
        </div>
    </div>

    {{-- Charts Row - Payment Methods & Delivery Status --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Payment Methods Distribution') }}</h5>
                </div>
                <div class="card-body">
                    @if ($paymentMethods->count() > 0)
                        <canvas id="paymentPieChart" style="max-height: 300px; width: 100%;"></canvas>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    @foreach ($paymentMethods as $method)
                                        @php
                                            $percentage =
                                                $totalRevenue > 0
                                                    ? round(($method->total / $totalRevenue) * 100, 1)
                                                    : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <span
                                                    class="badge badge-primary">{{ ucfirst(str_replace('_', ' ', $method->payment_type)) }}</span>
                                            </td>
                                            <td class="text-right">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-primary"
                                                        style="width: {{ $percentage }}%"></div>
                                                </div>
                                            </td>
                                            <td class="text-right"><strong>{{ $percentage }}%</strong></td>
                                            <td class="text-right text-muted">৳{{ number_format($method->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="las la-chart-pie fs-40 text-muted"></i>
                            <p class="text-muted mb-0">{{ translate('No payment data available') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Delivery Status Distribution') }}</h5>
                </div>
                <div class="card-body">
                    @if ($deliveryStatusBreakdown->count() > 0)
                        <canvas id="deliveryPieChart" style="max-height: 300px; width: 100%;"></canvas>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    @foreach ($deliveryStatusBreakdown as $status)
                                        @php
                                            $totalCount = $deliveryStatusBreakdown->sum('count');
                                            $percentage =
                                                $totalCount > 0 ? round(($status->count / $totalCount) * 100, 1) : 0;
                                            $badgeClass =
                                                [
                                                    'delivered' => 'badge-success',
                                                    'pending' => 'badge-warning',
                                                    'confirmed' => 'badge-info',
                                                    'processing' => 'badge-primary',
                                                    'shipped' => 'badge-dark',
                                                    'cancelled' => 'badge-danger',
                                                ][$status->delivery_status] ?? 'badge-secondary';
                                        @endphp
                                        <tr>
                                            <td>
                                                <span
                                                    class="badge {{ $badgeClass }}">{{ ucfirst($status->delivery_status) }}</span>
                                            </td>
                                            <td class="text-right">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-info"
                                                        style="width: {{ $percentage }}%"></div>
                                                </div>
                                            </td>
                                            <td class="text-right"><strong>{{ $percentage }}%</strong></td>
                                            <td class="text-right text-muted">{{ number_format($status->count) }} orders
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="las la-truck fs-40 text-muted"></i>
                            <p class="text-muted mb-0">{{ translate('No delivery data available') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Top Products Chart --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Top 40 Selling Products') }}</h5>
                </div>
                <div class="card-body">
                    @if ($topProducts->count() > 0)
                        <!-- Chart Section -->
                        <div class="chart-container mb-4" style="position: relative; min-height: 400px;">
                            <canvas id="topProductsChart" style="width: 100%; min-height: 400px;"></canvas>
                        </div>

                        <hr>

                        <!-- Table Section -->
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%" class="text-center">#</th>
                                        <th width="35%">{{ translate('Product') }}</th>
                                        <th width="15%" class="text-center">{{ translate('Quantity Sold') }}</th>
                                        <th width="20%" class="text-right">{{ translate('Total Sales') }}</th>
                                        <th width="25%">{{ translate('Performance') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topProducts as $index => $product)
                                        @php
                                            // Check if product exists
                                            $productExists = $product->product ? true : false;

                                            if ($productExists) {
                                                $maxQuantity = $topProducts->first()->total_quantity ?? 1;
                                                $percentage = round(($product->total_quantity / $maxQuantity) * 100, 1);
                                                $productName = $product->product->name ?? 'Product Not Found';
                                                $productSku = $product->product->inventory->sku ?? 'N/A';
                                                $productImage = $product->product->thumbnail
                                                    ? uploaded_asset($product->product->thumbnail)
                                                    : asset('assets/img/placeholder.jpg');
                                            } else {
                                                // For deleted products
                                                $percentage = 0;
                                                $productName = 'Deleted Product';
                                                $productSku = 'N/A';
                                                $productImage = asset('assets/img/placeholder.jpg');
                                            }
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge badge-secondary">{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $productImage }}" alt="{{ $productName }}"
                                                        class="size-40px mr-2"
                                                        style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;">
                                                    <div>
                                                        <strong>{{ Str::limit($productName, 40) }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ translate('SKU') }}:
                                                            {{ $productSku }}</small>
                                                        @if (!$productExists)
                                                            <span
                                                                class="badge badge-danger ml-1">{{ translate('Deleted') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge badge-primary">{{ number_format($product->total_quantity) }}</span>
                                            </td>
                                            <td class="text-right">
                                                <strong
                                                    class="text-success">৳{{ number_format($product->total_sales, 2) }}</strong>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1" style="height: 8px;">
                                                        <div class="progress-bar bg-success"
                                                            style="width: {{ $percentage }}%"></div>
                                                    </div>
                                                    <small class="text-muted ml-2">{{ $percentage }}%</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="las la-box-open fs-40 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">{{ translate('No sales data available') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 h6">{{ translate('Advanced Filters') }}</h5>
            <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#filterCollapse">
                <i class="las la-filter"></i> {{ translate('Show/Hide Filters') }}
            </button>
        </div>

        <div class="collapse {{ request()->has('date_from') || request()->has('payment_status') ? 'show' : '' }}"
            id="filterCollapse">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.sales') }}" id="filter-form">
                    <input type="hidden" name="time_filter" id="time_filter" value="{{ $timeFilter ?? 'all' }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ translate('Date From') }}</label>
                                <input type="date" class="form-control" name="date_from"
                                    value="{{ request('date_from') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ translate('Date To') }}</label>
                                <input type="date" class="form-control" name="date_to"
                                    value="{{ request('date_to') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ translate('Payment Status') }}</label>
                                <select class="form-control" name="payment_status">
                                    <option value="">{{ translate('All') }}</option>
                                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                                        {{ translate('Paid') }}</option>
                                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>
                                        {{ translate('Unpaid') }}</option>
                                    <option value="refunded"
                                        {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>
                                        {{ translate('Refunded') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ translate('Delivery Status') }}</label>
                                <select class="form-control" name="delivery_status">
                                    <option value="">{{ translate('All') }}</option>
                                    <option value="delivered"
                                        {{ request('delivery_status') == 'delivered' ? 'selected' : '' }}>
                                        {{ translate('Delivered') }}</option>
                                    <option value="pending"
                                        {{ request('delivery_status') == 'pending' ? 'selected' : '' }}>
                                        {{ translate('Pending') }}</option>
                                    <option value="cancelled"
                                        {{ request('delivery_status') == 'cancelled' ? 'selected' : '' }}>
                                        {{ translate('Cancelled') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ translate('Payment Type') }}</label>
                                <select class="form-control" name="payment_type">
                                    <option value="">{{ translate('All') }}</option>
                                    <option value="cash_on_delivery"
                                        {{ request('payment_type') == 'cash_on_delivery' ? 'selected' : '' }}>
                                        {{ translate('Cash on Delivery') }}</option>
                                    <option value="sslcommerz"
                                        {{ request('payment_type') == 'sslcommerz' ? 'selected' : '' }}>
                                        {{ translate('SSLCommerz') }}</option>
                                    <option value="bkash" {{ request('payment_type') == 'bkash' ? 'selected' : '' }}>
                                        {{ translate('bKash') }}</option>
                                    <option value="nagad" {{ request('payment_type') == 'nagad' ? 'selected' : '' }}>
                                        {{ translate('Nagad') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ translate('Customer') }}</label>
                                <select class="form-control" name="customer_id">
                                    <option value="">{{ translate('All Customers') }}</option>
                                    @foreach ($customers ?? [] as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ translate('Sort By') }}</label>
                                <select class="form-control" name="sort_by">
                                    <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>
                                        {{ translate('Latest') }}</option>
                                    <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>
                                        {{ translate('Oldest') }}</option>
                                    <option value="amount_desc"
                                        {{ request('sort_by') == 'amount_desc' ? 'selected' : '' }}>
                                        {{ translate('Amount (High to Low)') }}</option>
                                    <option value="amount_asc"
                                        {{ request('sort_by') == 'amount_asc' ? 'selected' : '' }}>
                                        {{ translate('Amount (Low to High)') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="las la-search"></i> {{ translate('Apply Filters') }}
                                    </button>
                                    <a href="{{ route('reports.sales') }}" class="btn btn-secondary">
                                        <i class="las la-undo-alt"></i> {{ translate('Reset All Filters') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6 d-inline-block">{{ translate('Sales Orders List') }}</h5>
            <span class="badge badge-primary ml-2">{{ $orders->total() }} {{ translate('Orders') }}</span>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered aiz-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">{{ translate('Order Code') }}</th>
                            <th width="15%">{{ translate('Customer') }}</th>
                            <th width="12%">{{ translate('Date') }}</th>
                            <th width="10%">{{ translate('Payment Method') }}</th>
                            <th width="10%">{{ translate('Payment Status') }}</th>
                            <th width="10%">{{ translate('Delivery Status') }}</th>
                            <th width="10%">{{ translate('Amount') }}</th>
                            <th width="10%">{{ translate('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $key => $order)
                            @php
                                $statusClass =
                                    [
                                        'pending' => 'badge-secondary',
                                        'confirmed' => 'badge-info',
                                        'processing' => 'badge-warning',
                                        'shipped' => 'badge-primary',
                                        'delivered' => 'badge-success',
                                        'cancelled' => 'badge-danger',
                                    ][$order->delivery_status] ?? 'badge-secondary';
                            @endphp
                            <tr>
                                <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $key + 1 }}</td>
                                <td><strong>{{ $order->code }}</strong></td>
                                <td>
                                    <div>{{ $order->user->name ?? 'Guest User' }}</div>
                                    <small class="text-muted">{{ $order->user->email ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                                <td>
                                    @if ($order->payment_status == 'paid')
                                        <span class="badge badge-success">{{ translate('Paid') }}</span>
                                    @elseif ($order->payment_status == 'unpaid')
                                        <span class="badge badge-danger">{{ translate('Unpaid') }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ ucfirst($order->payment_status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $statusClass }}">
                                        {{ ucfirst($order->delivery_status) }}
                                    </span>
                                </td>
                                <td><strong>৳{{ number_format($order->grand_total, 2) }}</strong></td>
                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}"
                                        class="btn btn-sm btn-icon btn-info">
                                        <i class="las la-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    <div class="py-5">
                                        <i class="las la-chart-line fs-60 text-muted"></i>
                                        <h5 class="text-muted mt-3">{{ translate('No sales data found') }}</h5>
                                        <p class="text-muted">{{ translate('No orders match your filter criteria.') }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="aiz-pagination mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let salesChart, paymentChart, deliveryChart, topProductsChart;
        let trendData = {
            '7days': @json($trendData7Days ?? []),
            '30days': @json($trendData30Days ?? []),
            'year': @json($trendDataYear ?? [])
        };

        let currentTrend = '7days';

        $(document).ready(function() {
            $('.time-filter-chip').on('click', function() {
                var filter = $(this).data('filter');
                $('#time_filter').val(filter);
                if (filter !== 'all') {
                    $('input[name="date_from"]').val('');
                    $('input[name="date_to"]').val('');
                }

                $('#filter-form').submit();
            });
            setTimeout(function() {
                initializeAllCharts();
            }, 100);
        });

        function initializeAllCharts() {
            loadTrendData('7days');
            initializePaymentChart();
            initializeDeliveryChart();
            initializeTopProductsChart();
        }

        function loadTrendData(period) {
            currentTrend = period;

            // Update button active states
            $('#trend7DaysBtn').removeClass('active').addClass('inactive');
            $('#trend30DaysBtn').removeClass('active').addClass('inactive');
            $('#trendYearBtn').removeClass('active').addClass('inactive');

            if (period === '7days') {
                $('#trend7DaysBtn').removeClass('inactive').addClass('active');
            } else if (period === '30days') {
                $('#trend30DaysBtn').removeClass('inactive').addClass('active');
            } else if (period === 'year') {
                $('#trendYearBtn').removeClass('inactive').addClass('active');
            }

            // Get data for selected period
            let data = trendData[period] || [];

            if (data.length === 0) {
                $('#trendChartContainer').html(
                    '<div class="text-center py-5"><i class="las la-chart-line fs-40 text-muted"></i><p class="text-muted mt-2">{{ translate('No data available for this period') }}</p></div>'
                );
                return;
            }

            // Group data by date to combine multiple entries for same date
            let groupedData = {};

            data.forEach(item => {
                let date = item.date;
                if (!groupedData[date]) {
                    groupedData[date] = {
                        date: date,
                        total_sales: 0,
                        total_orders: 0
                    };
                }
                groupedData[date].total_sales += parseFloat(item.total_sales);
                groupedData[date].total_orders += parseInt(item.total_orders);
            });

            // Convert grouped data back to array and sort by date
            let groupedArray = Object.values(groupedData);
            groupedArray.sort(function(a, b) {
                return new Date(a.date) - new Date(b.date);
            });

            // Prepare chart data from grouped array
            let labels = groupedArray.map(item => item.date);
            let salesAmounts = groupedArray.map(item => item.total_sales);
            let orderCounts = groupedArray.map(item => item.total_orders);

            // Destroy existing chart if it exists
            if (salesChart) {
                salesChart.destroy();
            }

            // Calculate summary statistics
            let totalSales = salesAmounts.reduce((a, b) => a + b, 0);
            let totalOrders = orderCounts.reduce((a, b) => a + b, 0);
            let avgDailySales = totalSales / groupedArray.length;
            let avgDailyOrders = totalOrders / groupedArray.length;

            // Update summary
            $('#trendSummary').html(`
                <div class="row">
                    <div class="col-md-3">
                        <small class="text-muted">{{ translate('Total Sales') }}</small>
                        <br><strong class="text-success">৳${totalSales.toLocaleString()}</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">{{ translate('Total Orders') }}</small>
                        <br><strong class="text-primary">${totalOrders.toLocaleString()}</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">{{ translate('Average Daily Sales') }}</small>
                        <br><strong>৳${avgDailySales.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">{{ translate('Average Daily Orders') }}</small>
                        <br><strong>${avgDailyOrders.toLocaleString(undefined, {minimumFractionDigits: 1, maximumFractionDigits: 1})}</strong>
                    </div>
                </div>
            `);

            // Create new chart
            let canvas = document.getElementById('salesTrendChart');
            if (!canvas) return;

            let ctx = canvas.getContext('2d');
            if (!ctx) return;

            salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '{{ translate('Daily Sales Amount (BDT)') }}',
                        data: salesAmounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        yAxisID: 'y'
                    }, {
                        label: '{{ translate('Daily Order Count') }}',
                        data: orderCounts,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        yAxisID: 'y1'
                    }]
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
                                    let label = context.dataset.label || '';
                                    let value = context.raw;
                                    if (context.dataset.label.includes('Sales')) {
                                        return label + ': ৳' + value.toLocaleString();
                                    }
                                    return label + ': ' + value.toLocaleString();
                                },
                                footer: function(tooltipItems) {
                                    if (tooltipItems.length === 0) return '';
                                    let totalSalesForDay = 0;
                                    let totalOrdersForDay = 0;

                                    tooltipItems.forEach(function(item) {
                                        if (item.dataset.label.includes('Sales')) {
                                            totalSalesForDay = item.raw;
                                        } else {
                                            totalOrdersForDay = item.raw;
                                        }
                                    });

                                    return 'Daily Total: ৳' + totalSalesForDay.toLocaleString() + ' | ' +
                                        totalOrdersForDay + ' orders';
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 10,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            position: 'left',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '{{ translate('Sales Amount (BDT)') }}',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                callback: function(value) {
                                    return '৳' + value.toLocaleString();
                                }
                            }
                        },
                        y1: {
                            position: 'right',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '{{ translate('Number of Orders') }}',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                },
                                stepSize: 1
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '{{ translate('Date') }}',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                autoSkip: true,
                                maxTicksLimit: period === 'year' ? 12 : 10
                            }
                        }
                    }
                }
            });
        }

        function initializePaymentChart() {
            @if ($paymentMethods->count() > 0)
                var paymentCtx = document.getElementById('paymentPieChart');
                if (paymentCtx) {
                    // Destroy existing chart
                    if (paymentChart) {
                        paymentChart.destroy();
                    }

                    var ctx = paymentCtx.getContext('2d');
                    var paymentLabels = [];
                    var paymentData = [];
                    var paymentColors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#C9CBCF',
                        '#7C3AED', '#10B981', '#F59E0B'
                    ];

                    @foreach ($paymentMethods as $index => $method)
                        paymentLabels.push('{{ ucfirst(str_replace('_', ' ', $method->payment_type)) }}');
                        paymentData.push({{ $method->total }});
                    @endforeach

                    paymentChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: paymentLabels,
                            datasets: [{
                                data: paymentData,
                                backgroundColor: paymentColors.slice(0, paymentLabels.length),
                                borderWidth: 2,
                                borderColor: '#fff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        font: {
                                            size: 11
                                        },
                                        padding: 10
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.label || '';
                                            let value = context.raw;
                                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            let percentage = ((value / total) * 100).toFixed(1);
                                            return label + ': ৳' + value.toLocaleString() + ' (' + percentage +
                                                '%)';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            @endif
        }

        function initializeDeliveryChart() {
            @if ($deliveryStatusBreakdown->count() > 0)
                var deliveryCtx = document.getElementById('deliveryPieChart');
                if (deliveryCtx) {
                    // Destroy existing chart
                    if (deliveryChart) {
                        deliveryChart.destroy();
                    }

                    var ctx = deliveryCtx.getContext('2d');
                    var deliveryLabels = [];
                    var deliveryData = [];
                    var deliveryColors = [];

                    var statusColorsMap = {
                        'delivered': '#4BC0C0',
                        'pending': '#FFCE56',
                        'confirmed': '#36A2EB',
                        'processing': '#9966FF',
                        'shipped': '#FF9F40',
                        'cancelled': '#FF6384',
                        'refunded': '#9E9E9E',
                        'returned': '#FF9800'
                    };

                    @foreach ($deliveryStatusBreakdown as $status)
                        deliveryLabels.push('{{ ucfirst($status->delivery_status) }}');
                        deliveryData.push({{ $status->count }});
                        var statusColor = statusColorsMap['{{ $status->delivery_status }}'] || '#C9CBCF';
                        deliveryColors.push(statusColor);
                    @endforeach

                    deliveryChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: deliveryLabels,
                            datasets: [{
                                data: deliveryData,
                                backgroundColor: deliveryColors,
                                borderWidth: 2,
                                borderColor: '#fff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        font: {
                                            size: 11
                                        },
                                        padding: 10
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.label || '';
                                            let value = context.raw;
                                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            let percentage = ((value / total) * 100).toFixed(1);
                                            return label + ': ' + value.toLocaleString() + ' orders (' +
                                                percentage + '%)';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            @endif
        }

        function initializeTopProductsChart() {
            // Check if canvas exists
            var canvas = document.getElementById('topProductsChart');

            @if ($topProducts->count() > 0)
                if (canvas) {
                    // Destroy existing chart if it exists
                    if (topProductsChart) {
                        topProductsChart.destroy();
                        topProductsChart = null;
                    }

                    var ctx = canvas.getContext('2d');

                    // Prepare data from PHP
                    var productNames = [];
                    var productQuantities = [];
                    var productSales = [];

                    @foreach ($topProducts as $product)
                        productNames.push('{{ Str::limit($product->product->name ?? 'Unknown', 25) }}');
                        productQuantities.push({{ $product->total_quantity }});
                        productSales.push({{ $product->total_sales }});
                    @endforeach

                    // Create horizontal bar chart for better readability
                    topProductsChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: productNames,
                            datasets: [{
                                label: '{{ translate('Quantity Sold') }}',
                                data: productQuantities,
                                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1,
                                order: 2
                            }, {
                                label: '{{ translate('Sales Amount (BDT)') }}',
                                data: productSales,
                                type: 'line',
                                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 2,
                                fill: false,
                                tension: 0.4,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                yAxisID: 'y1',
                                order: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        font: {
                                            size: 12
                                        },
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            let value = context.raw;
                                            if (context.dataset.label.includes('Sales')) {
                                                return label + ': ৳' + value.toLocaleString();
                                            }
                                            return label + ': ' + value.toLocaleString();
                                        },
                                        footer: function(tooltipItems) {
                                            if (tooltipItems.length === 0) return '';
                                            let totalQuantity = 0;
                                            let totalSales = 0;

                                            tooltipItems.forEach(function(item) {
                                                if (item.dataset.label.includes('Quantity')) {
                                                    totalQuantity = item.raw;
                                                } else if (item.dataset.label.includes('Sales')) {
                                                    totalSales = item.raw;
                                                }
                                            });

                                            if (totalQuantity > 0 && totalSales > 0) {
                                                let avgPrice = totalSales / totalQuantity;
                                                return 'Avg Price: ৳' + avgPrice.toFixed(2);
                                            }
                                            return '';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: '{{ translate('Quantity Sold') }}',
                                        font: {
                                            weight: 'bold',
                                            size: 12
                                        }
                                    },
                                    ticks: {
                                        stepSize: 1,
                                        callback: function(value) {
                                            return value.toLocaleString();
                                        }
                                    },
                                    position: 'left',
                                    grid: {
                                        drawOnChartArea: true
                                    }
                                },
                                y1: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: '{{ translate('Sales Amount (BDT)') }}',
                                        font: {
                                            weight: 'bold',
                                            size: 12
                                        },
                                        color: 'rgba(255, 99, 132, 1)'
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return '৳' + value.toLocaleString();
                                        }
                                    },
                                    position: 'right',
                                    grid: {
                                        drawOnChartArea: false
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: '{{ translate('Products') }}',
                                        font: {
                                            weight: 'bold',
                                            size: 12
                                        }
                                    },
                                    ticks: {
                                        maxRotation: 45,
                                        minRotation: 45,
                                        autoSkip: true,
                                        font: {
                                            size: 11
                                        }
                                    }
                                }
                            },
                            animation: {
                                duration: 800,
                                easing: 'easeInOutQuart'
                            }
                        }
                    });

                    console.log('Top Products Chart initialized successfully');
                } else {
                    console.log('Top Products Chart canvas not found');
                }
            @else
                console.log('No top products data available');
                if (canvas) {
                    var ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.font = '16px Arial';
                    ctx.fillStyle = '#999';
                    ctx.textAlign = 'center';
                    ctx.fillText('{{ translate('No data available') }}', canvas.width / 2, canvas.height / 2);
                }
            @endif
        }

        // Window resize handler for all charts
        $(window).on('resize', function() {
            if (salesChart && typeof salesChart.resize === 'function') {
                salesChart.resize();
            }
            if (paymentChart && typeof paymentChart.resize === 'function') {
                paymentChart.resize();
            }
            if (deliveryChart && typeof deliveryChart.resize === 'function') {
                deliveryChart.resize();
            }
            if (topProductsChart && typeof topProductsChart.resize === 'function') {
                topProductsChart.resize();
            }
        });

        // Refresh charts when needed (for dynamic data updates)
        function refreshAllCharts() {
            if (salesChart) {
                salesChart.update();
            }
            if (paymentChart) {
                paymentChart.update();
            }
            if (deliveryChart) {
                deliveryChart.update();
            }
            if (topProductsChart) {
                topProductsChart.update();
            }
        }

        // Export charts as images (optional feature)
        function exportChartAsImage(chartId, filename) {
            var canvas = document.getElementById(chartId);
            if (canvas) {
                var link = document.createElement('a');
                link.download = filename + '.png';
                link.href = canvas.toDataURL();
                link.click();
            }
        }
    </script>

    <style>
        .chart-container {
            position: relative;
            width: 100%;
            min-height: 400px;
        }

        canvas {
            max-width: 100%;
            height: auto;
        }

        @media (max-width: 768px) {
            .chart-container {
                min-height: 300px;
            }

            canvas {
                min-height: 300px;
            }
        }

        .chartjs-tooltip {
            opacity: 1;
            position: absolute;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            border-radius: 4px;
            padding: 8px 12px;
            pointer-events: none;
            font-size: 12px;
            z-index: 1000;
        }
    </style>
@endsection
