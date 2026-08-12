@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Customer Report') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('reports.export-customers', request()->query()) }}" class="btn btn-success">
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
    </style>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-blue">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Total Customers') }}</div>
                    <div class="stat-number">{{ number_format($totalCustomers) }}</div>
                    <div class="stat-small">{{ translate('All registered customers') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-users"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-green">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Customers with Orders') }}</div>
                    <div class="stat-number">{{ number_format($customersWithOrders) }}</div>
                    <div class="stat-small">{{ translate('Have placed orders') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-shopping-cart"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-orange">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Total Spent') }}</div>
                    <div class="stat-number">৳{{ number_format($totalSpent, 2) }}</div>
                    <div class="stat-small">{{ translate('All customer purchases') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-chart-line"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-purple">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Avg Spent/Customer') }}</div>
                    <div class="stat-number">৳{{ number_format($avgSpentPerCustomer, 2) }}</div>
                    <div class="stat-small">{{ translate('Average per customer') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-calculator"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="mm-card mm-card-pink">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Customers with Reviews') }}</div>
                    <div class="stat-number">{{ number_format($customersWithReviews) }}</div>
                    <div class="stat-small">{{ translate('Have written reviews') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-star"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-3">
            <div class="mm-card mm-card-dark">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Customers with Wishlist') }}</div>
                    <div class="stat-number">{{ number_format($customersWithWishlist) }}</div>
                    <div class="stat-small">{{ translate('Have wishlist items') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-heart"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-3">
            <div class="mm-card mm-card-green">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('New Customers (This Month)') }}</div>
                    <div class="stat-number">{{ number_format($newCustomersThisMonth) }}</div>
                    <div class="stat-small">{{ translate('Joined this month') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-calendar-alt"></i>
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
        <div class="collapse {{ request()->has('search') || request()->has('min_orders') ? 'show' : '' }}"
            id="filterCollapse">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.customers') }}" id="filter-form">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ translate('Search') }}</label>
                                <input type="text" class="form-control" name="search"
                                    placeholder="{{ translate('Search by name, email or phone') }}"
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ translate('Min Orders') }}</label>
                                <input type="number" class="form-control" name="min_orders"
                                    placeholder="{{ translate('Minimum orders') }}" value="{{ request('min_orders') }}"
                                    min="1">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ translate('Min Spent') }}</label>
                                <input type="number" class="form-control" name="min_spent"
                                    placeholder="{{ translate('Minimum spent') }}" value="{{ request('min_spent') }}"
                                    min="0">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ translate('Date From') }}</label>
                                <input type="date" class="form-control" name="date_from"
                                    value="{{ request('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ translate('Sort By') }}</label>
                                <select class="form-control" name="sort_by">
                                    <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>
                                        {{ translate('Name (A-Z)') }}
                                    </option>
                                    <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>
                                        {{ translate('Name (Z-A)') }}
                                    </option>
                                    <option value="orders_desc"
                                        {{ request('sort_by') == 'orders_desc' ? 'selected' : '' }}>
                                        {{ translate('Most Orders') }}
                                    </option>
                                    <option value="spent_desc" {{ request('sort_by') == 'spent_desc' ? 'selected' : '' }}>
                                        {{ translate('Highest Spent') }}
                                    </option>
                                    <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>
                                        {{ translate('Newest First') }}
                                    </option>
                                    <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>
                                        {{ translate('Oldest First') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ translate('Date To') }}</label>
                                <input type="date" class="form-control" name="date_to"
                                    value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="col-md-10 text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-search"></i> {{ translate('Filter') }}
                            </button>
                            <a href="{{ route('reports.customers') }}" class="btn btn-secondary">
                                <i class="las la-undo-alt"></i> {{ translate('Reset') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Customer Report') }}</h5>
            <span class="badge badge-primary ml-2">{{ $customers->total() }} {{ translate('Customers') }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered aiz-table">
                    <thead>
                        <tr>
                            <th width="5%">{{ translate('SL') }}</th>
                            <th width="20%">{{ translate('Customer') }}</th>
                            <th width="20%">{{ translate('Contact Info') }}</th>
                            <th width="12%">{{ translate('Total Orders') }}</th>
                            <th width="15%">{{ translate('Total Spent') }}</th>
                            <th width="10%">{{ translate('Reviews') }}</th>
                            <th width="8%">{{ translate('Wishlist') }}</th>
                            <th width="10%">{{ translate('Joined Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $key => $customer)
                            @php
                                $totalOrders = $customer->orders->count();
                                $totalSpent = $customer->orders->where('payment_status', 'paid')->sum('grand_total');
                                $reviewsCount = $customer->reviews->count();
                                $wishlistCount = $customer->wishlists->count();
                            @endphp
                            <tr>
                                <td>
                                    {{ ($customers->currentPage() - 1) * $customers->perPage() + $key + 1 }}
            </div>
            <td>
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm mr-2">
                        @if ($customer->avatar)
                            <img src="{{ uploaded_asset($customer->avatar) }}" class="rounded-circle">
                        @else
                            <img src="{{ asset('default/avatar.jpg') }}" class="rounded-circle">
                        @endif
                    </div>
                    <div>
                        <div class="font-weight-bold">{{ $customer->name }}</div>
                        <small class="text-muted">ID: #{{ $customer->id }}</small>
                    </div>
                </div>
        </div>
        <td>
            <div>
                <div><i class="las la-envelope"></i> {{ $customer->email }}</div>
                <div><i class="las la-phone"></i> {{ $customer->phone ?? 'N/A' }}</div>
            </div>
    </div>
    <td>
        <span class="badge badge-primary">{{ number_format($totalOrders) }}</span>
        </div>
    <td>
        <strong class="text-success">৳{{ number_format($totalSpent, 2) }}</strong>
        </div>
    <td>
        <span class="badge badge-info">{{ number_format($reviewsCount) }}</span>
        </div>
    <td>
        <span class="badge badge-pink">{{ number_format($wishlistCount) }}</span>
        </div>
    <td>
        <div>
            <span class="d-block">{{ $customer->created_at->format('d M Y') }}</span>
            <small class="text-muted">{{ $customer->created_at->format('h:i A') }}</small>
        </div>
        </div>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center">
                <div class="py-5">
                    <i class="las la-users fs-60 text-muted"></i>
                    <h5 class="text-muted mt-3">{{ translate('No customers found') }}</h5>
                    <p class="text-muted">{{ translate('No customers match your filter criteria.') }}</p>
                </div>
                </div>
        </tr>
        @endforelse
        </tbody>
        </table>
        </div>

        <div class="aiz-pagination mt-4">
            {{ $customers->links() }}
        </div>
        </div>
        </div>
    @endsection

    @section('script')
        <script type="text/javascript">
            $('.aiz-selectpicker').selectpicker();
        </script>

        <style>
            .fs-40 {
                font-size: 40px;
            }

            .fs-60 {
                font-size: 60px;
            }

                {
                font-size: 14px;
                padding: 6px 12px;
            }

            .badge-pink {
                background: #ff69b4;
                color: white;
            }
        </style>
    @endsection
