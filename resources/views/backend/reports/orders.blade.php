@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Order Report') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('reports.export-orders', request()->query()) }}" class="btn btn-success">
                    <i class="las la-file-excel"></i> {{ translate('Export CSV') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Styles -->
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

        .fs-40 {
            font-size: 40px;
        }

        .fs-60 {
            font-size: 60px;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .flex-grow-1 {
            flex-grow: 1;
        }

        .ml-2 {
            margin-left: 0.5rem;
        }
    </style>

    <!-- Main Statistics Cards -->
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

    <!-- Second Row Statistics -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="mm-card mm-card-pink">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Today\'s Orders') }}</div>
                    <div class="stat-number">{{ number_format($todayOrders) }}</div>
                    <div class="stat-small">৳{{ number_format($todayRevenue, 2) }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-calendar-day"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-3">
            <div class="mm-card mm-card-dark">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('This Week Orders') }}</div>
                    <div class="stat-number">{{ number_format($weekOrders) }}</div>
                    <div class="stat-small">৳{{ number_format($weekRevenue, 2) }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-calendar-week"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-3">
            <div class="mm-card mm-card-green">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('This Month Orders') }}</div>
                    <div class="stat-number">{{ number_format($monthOrders) }}</div>
                    <div class="stat-small">৳{{ number_format($monthRevenue, 2) }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-calendar-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods & Delivery Status -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Payment Methods Breakdown') }}</h5>
                </div>
                <div class="card-body">
                    @if ($paymentMethods->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ translate('Payment Method') }}</th>
                                        <th>{{ translate('Orders') }}</th>
                                        <th>{{ translate('Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paymentMethods as $method)
                                        <tr>
                                            <td>{{ ucfirst(str_replace('_', ' ', $method->payment_type)) }}
                        </div>
                        <td>{{ number_format($method->count) }}
                </div>
                <td>৳{{ number_format($method->total, 2) }}
            </div>
            </tr>
            @endforeach
            </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-4">
            <i class="las la-credit-card fs-40 text-muted"></i>
            <p class="text-muted">{{ translate('No payment data available') }}</p>
        </div>
        @endif
    </div>
    </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Delivery Status Breakdown') }}</h5>
            </div>
            <div class="card-body">
                @if ($deliveryStatuses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ translate('Status') }}</th>
                                    <th>{{ translate('Orders') }}</th>
                                    <th>{{ translate('Percentage') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deliveryStatuses as $status)
                                    @php
                                        $percentage =
                                            $totalOrders > 0 ? round(($status->count / $totalOrders) * 100, 1) : 0;
                                        $badgeClass =
                                            [
                                                'delivered' => 'success',
                                                'pending' => 'warning',
                                                'processing' => 'info',
                                                'shipped' => 'primary',
                                                'cancelled' => 'danger',
                                            ][$status->delivery_status] ?? 'secondary';
                                    @endphp
                                    <tr>
                                        <td><span
                                                class="badge badge-{{ $badgeClass }}">{{ ucfirst($status->delivery_status) }}</span>
                    </div>
                    <td>{{ number_format($status->count) }}
            </div>
            <td>
                <div class="d-flex align-items-center">
                    <div class="progress flex-grow-1" style="height: 8px;">
                        <div class="progress-bar bg-{{ $badgeClass }}" style="width: {{ $percentage }}%"></div>
                    </div>
                    <span class="ml-2">{{ $percentage }}%</span>
                </div>
        </div>
        </tr>
        @endforeach
        </tbody>
        </table>
    </div>
@else
    <div class="text-center py-4">
        <i class="las la-truck fs-40 text-muted"></i>
        <p class="text-muted">{{ translate('No delivery data available') }}</p>
    </div>
    @endif
    </div>
    </div>
    </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Filter Reports') }}</h5>
            <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#filterCollapse">
                <i class="las la-filter"></i> {{ translate('Show/Hide Filters') }}
            </button>
        </div>
        <div class="collapse {{ request()->has('search') || request()->has('date_from') || request()->has('payment_status') ? 'show' : '' }}"
            id="filterCollapse">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.orders') }}" id="filter-form">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ translate('Search') }}</label>
                                <input type="text" class="form-control" name="search"
                                    placeholder="{{ translate('Order Code') }}" value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ translate('Date From') }}</label>
                                <input type="date" class="form-control" name="date_from"
                                    value="{{ request('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ translate('Date To') }}</label>
                                <input type="date" class="form-control" name="date_to"
                                    value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
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
                                    <option value="pending"
                                        {{ request('delivery_status') == 'pending' ? 'selected' : '' }}>
                                        {{ translate('Pending') }}</option>
                                    <option value="processing"
                                        {{ request('delivery_status') == 'processing' ? 'selected' : '' }}>
                                        {{ translate('Processing') }}</option>
                                    <option value="shipped"
                                        {{ request('delivery_status') == 'shipped' ? 'selected' : '' }}>
                                        {{ translate('Shipped') }}</option>
                                    <option value="delivered"
                                        {{ request('delivery_status') == 'delivered' ? 'selected' : '' }}>
                                        {{ translate('Delivered') }}</option>
                                    <option value="cancelled"
                                        {{ request('delivery_status') == 'cancelled' ? 'selected' : '' }}>
                                        {{ translate('Cancelled') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
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
                                    <option value="amount_asc" {{ request('sort_by') == 'amount_asc' ? 'selected' : '' }}>
                                        {{ translate('Amount (Low to High)') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-9 text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-search"></i> {{ translate('Filter') }}
                            </button>
                            <a href="{{ route('reports.orders') }}" class="btn btn-secondary">
                                <i class="las la-undo-alt"></i> {{ translate('Reset') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Order Report') }}</h5>
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
                            <th width="12%">{{ translate('Order Date') }}</th>
                            <th width="12%">{{ translate('Payment Method') }}</th>
                            <th width="10%">{{ translate('Payment Status') }}</th>
                            <th width="10%">{{ translate('Delivery Status') }}</th>
                            <th width="10%">{{ translate('Amount') }}</th>
                            <th width="8%">{{ translate('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $key => $order)
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
                                <td>
                                    {{ ($orders->currentPage() - 1) * $orders->perPage() + $key + 1 }}
            </div>
            <td><strong>{{ $order->code }}</strong>
        </div>
        <td>
            <div>
                <div>{{ $order->user->name ?? 'Guest User' }}</div>
                <small class="text-muted">{{ $order->user->email ?? 'N/A' }}</small>
            </div>
    </div>
    <td>{{ $order->created_at->format('d M Y, h:i A') }}</div>
    <td>{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</div>
    <td>
        @if ($order->payment_status == 'paid')
            <span class="badge badge-success">{{ translate('Paid') }}</span>
        @elseif($order->payment_status == 'unpaid')
            <span class="badge badge-danger">{{ translate('Unpaid') }}</span>
        @else
            <span class="badge badge-warning">{{ ucfirst($order->payment_status) }}</span>
        @endif
        </div>
    <td><span class="badge {{ $statusClass }}">{{ ucfirst($order->delivery_status) }}</span></div>
    <td><strong class="text-primary">৳{{ number_format($order->grand_total, 2) }}</strong></div>
    <td>
        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-icon btn-info">
            <i class="las la-eye"></i>
        </a>
        </div>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="text-center">
                <div class="py-5">
                    <i class="las la-chart-line fs-60 text-muted"></i>
                    <h5 class="text-muted mt-3">{{ translate('No orders found') }}</h5>
                    <p class="text-muted">{{ translate('No orders match your filter criteria.') }}</p>
                </div>
                </div>
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
        <script type="text/javascript">
            $('.aiz-selectpicker').selectpicker();
        </script>
    @endsection
