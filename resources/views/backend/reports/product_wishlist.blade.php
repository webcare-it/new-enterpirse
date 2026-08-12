@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Product Wishlist Report') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('reports.export-product-wishlist', request()->query()) }}" class="btn btn-success">
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

        .mm-card-pink {
            background: linear-gradient(135deg, #ff758c, #ff7eb3);
        }

        .mm-card-blue {
            background: linear-gradient(135deg, #405cf5, #11b4d8);
        }

        .mm-card-green {
            background: linear-gradient(135deg, #0f9f52, #22c55e);
        }

        .mm-card-purple {
            background: linear-gradient(135deg, #d946ef, #7c3aed);
        }

        .mm-card-orange {
            background: linear-gradient(135deg, #ff9f1c, #ff6b00);
        }

        .mm-card-red {
            background: linear-gradient(135deg, #ef4444, #f97316);
        }

        .mm-card-dark {
            background: linear-gradient(135deg, #334155, #0f172a);
        }
    </style>

    <!-- Statistics Cards Row 1 -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-pink">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Total Wishlists') }}</div>
                    <div class="stat-number">{{ number_format($totalWishlists) }}</div>
                    <div class="stat-small">{{ translate('All wishlist records') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-heart"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-blue">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('User Wishlists') }}</div>
                    <div class="stat-number">{{ number_format($totalUserWishlists) }}</div>
                    <div class="stat-small">{{ translate('Registered users') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-user"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-green">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Guest Wishlists') }}</div>
                    <div class="stat-number">{{ number_format($totalGuestWishlists) }}</div>
                    <div class="stat-small">{{ translate('Guest visitors') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-user-friends"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-purple">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Products with Wishlists') }}</div>
                    <div class="stat-number">{{ number_format($productsWithWishlists) }}</div>
                    <div class="stat-small">{{ translate('Products added to wishlist') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-box"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Most Wishlisted Product Card -->
    @if ($mostWishlisted)
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="mm-card mm-card-orange">
                    <div class="mm-content">
                        <div class="stat-label">{{ translate('Most Wishlisted Product') }}</div>
                        <div class="stat-number">{{ $mostWishlisted->name }}</div>
                        <div class="stat-small">
                            {{ $mostWishlisted->wishlists_count ?? 0 }} {{ translate('wishlists') }}
                        </div>
                    </div>
                    <div class="mm-icon">
                        <i class="las la-crown"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mm-card mm-card-dark">
                    <div class="mm-content">
                        <div class="stat-label">{{ translate('Filtered Records') }}</div>
                        <div class="stat-number">{{ $products->total() }}</div>
                        <div class="stat-small">{{ translate('Current result records') }}</div>
                    </div>
                    <div class="mm-icon">
                        <i class="las la-database"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Top 5 Wishlisted Products -->
    @if ($topWishlisted->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Top 5 Wishlisted Products') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="45%">{{ translate('Product Name') }}</th>
                                <th width="25%">{{ translate('User Wishlists') }}</th>
                                <th width="25%">{{ translate('Total Wishlists') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topWishlisted as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}
                </div>
                <td><strong>{{ $product->name }}</strong>
            </div>
            <td>
                <div class="progress" style="height: 8px;">
                    @php
                        $maxCount = $topWishlisted->first()->wishlists_count ?? 1;
                        $percentage = ($product->wishlists_count / $maxCount) * 100;
                    @endphp
                    <div class="progress-bar bg-primary" style="width: {{ $percentage }}%"></div>
                </div>
                <span class="badge badge-primary mt-1">{{ $product->wishlists_count ?? 0 }}</span>
        </div>
        <td>
            <span
                class="badge badge-success">{{ ($product->wishlists_count ?? 0) + ($product->guest_wishlists_count ?? 0) }}</span>
            </div>
            </tr>
    @endforeach
    </tbody>
    </table>
    </div>
    </div>
    </div>
    @endif

    <!-- Filter Section -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Filter Reports') }}</h5>
            <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#filterCollapse">
                <i class="las la-filter"></i> {{ translate('Show/Hide Filters') }}
            </button>
        </div>
        <div class="collapse {{ request()->has('search') || request()->has('category_id') || request()->has('brand_id') ? 'show' : '' }}"
            id="filterCollapse">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.product-wishlist') }}" id="filter-form">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ translate('Search') }}</label>
                                <input type="text" class="form-control" name="search"
                                    placeholder="{{ translate('Search by product name or SKU') }}"
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ translate('Category') }}</label>
                                <select class="form-control aiz-selectpicker" name="category_id">
                                    <option value="">{{ translate('All Categories') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ translate('Brand') }}</label>
                                <select class="form-control aiz-selectpicker" name="brand_id">
                                    <option value="">{{ translate('All Brands') }}</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ translate('Sort By') }}</label>
                                <select class="form-control" name="sort_by">
                                    <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>
                                        {{ translate('Name (A-Z)') }}
                                    </option>
                                    <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>
                                        {{ translate('Name (Z-A)') }}
                                    </option>
                                    <option value="wishlist_desc"
                                        {{ request('sort_by') == 'wishlist_desc' ? 'selected' : '' }}>
                                        {{ translate('Most Wishlisted') }}
                                    </option>
                                    <option value="wishlist_asc"
                                        {{ request('sort_by') == 'wishlist_asc' ? 'selected' : '' }}>
                                        {{ translate('Least Wishlisted') }}
                                    </option>
                                    <option value="total_wishlist_desc"
                                        {{ request('sort_by') == 'total_wishlist_desc' ? 'selected' : '' }}>
                                        {{ translate('Most Wishlisted (Total)') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="las la-search"></i> {{ translate('Filter') }}
                                    </button>
                                    <a href="{{ route('reports.product-wishlist') }}" class="btn btn-secondary w-100">
                                        <i class="las la-undo-alt"></i> {{ translate('Reset') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Product Wishlist Report') }}</h5>
            <span class="badge badge-primary ml-2">{{ $products->total() }} {{ translate('Products') }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered aiz-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="20%">{{ translate('Product') }}</th>
                            <th width="10%">{{ translate('SKU') }}</th>
                            <th width="12%">{{ translate('Category') }}</th>
                            <th width="10%">{{ translate('Brand') }}</th>
                            <th width="12%">{{ translate('Price') }}</th>
                            <th width="12%">{{ translate('User Wishlists') }}</th>
                            <th width="12%">{{ translate('Guest Wishlists') }}</th>
                            <th width="10%">{{ translate('Total Wishlists') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $key => $product)
                            @php
                                $userWishlists = $product->wishlists_count ?? 0;
                                $guestWishlists = $product->guest_wishlists_count ?? 0;
                                $totalWishlists = $userWishlists + $guestWishlists;
                                $regularPrice = $product->price->regular_price ?? 0;
                            @endphp
                            <tr>
                                <td>
                                    {{ ($products->currentPage() - 1) * $products->perPage() + $key + 1 }}
            </div>
            <td>
                <div class="d-flex align-items-center">
                    <img src="{{ uploaded_asset($product->thumbnail) }}" alt="{{ $product->name }}"
                        class="size-40px img-fit mr-2" style="border-radius: 8px;">
                    <div>
                        <div class="font-weight-bold">{{ $product->name }}</div>
                        <small class="text-muted">ID: #{{ $product->id }}</small>
                    </div>
                </div>
        </div>
        <td>{{ $product->inventory->sku ?? 'N/A' }}
    </div>
    <td>{{ $product->category->category_name ?? 'N/A' }}</div>
    <td>{{ $product->brand->name ?? 'N/A' }}</div>
    <td><strong>৳{{ number_format($regularPrice, 2) }}</strong></div>
    <td>
        <span class="badge badge-primary">{{ number_format($userWishlists) }}</span>
        </div>
    <td>
        <span class="badge badge-info">{{ number_format($guestWishlists) }}</span>
        </div>
    <td>
        <span class="badge badge-success badge-lg">{{ number_format($totalWishlists) }}</span>
        </div>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="text-center">
                <div class="py-5">
                    <i class="las la-heart-broken fs-60 text-muted"></i>
                    <h5 class="text-muted mt-3">{{ translate('No products found') }}</h5>
                    <p class="text-muted">{{ translate('No products match your filter criteria.') }}</p>
                </div>
                </div>
        </tr>
        @endforelse
        </tbody>
        </table>
        </div>

        <div class="aiz-pagination mt-4">
            {{ $products->links() }}
        </div>
        </div>
        </div>
    @endsection

    @section('script')
        <script type="text/javascript">
            $('.aiz-selectpicker').selectpicker();
            $(document).ready(function() {
                $('.progress-bar').each(function() {
                    var width = $(this).css('width');
                    $(this).css('width', 0);
                    setTimeout(function() {
                        $(this).css('width', width);
                    }.bind(this), 100);
                });
            });
        </script>

        <style>
            .fs-40 {
                font-size: 40px;
            }

            .fs-60 {
                font-size: 60px;
            }

            .size-40px {
                width: 40px;
                height: 40px;
                object-fit: cover;
            }

            .badge-lg {
                font-size: 14px;
                padding: 6px 12px;
            }

            .gap-2 {
                gap: 0.5rem;
            }
        </style>
    @endsection
