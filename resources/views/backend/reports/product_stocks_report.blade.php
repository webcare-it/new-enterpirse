@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Product Stocks Report') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('reports.export-product-stocks', request()->query()) }}" class="btn btn-success">
                    <i class="las la-file-excel"></i> {{ translate('Export CSV') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <!-- Statistics Cards -->
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

        .mm-card-red {
            background: linear-gradient(135deg, #ef4444, #f97316);
        }

        .mm-card-dark {
            background: linear-gradient(135deg, #334155, #0f172a);
        }
    </style>

    <div class="row mb-4">

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-blue">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Total Products') }}</div>
                    <div class="stat-number">{{ $totalProducts }}</div>
                    <div class="stat-small">{{ translate('All product records') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-box"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-green">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Total Stock Units') }}</div>
                    <div class="stat-number">{{ number_format($totalStock) }}</div>
                    <div class="stat-small">{{ translate('Available stock quantity') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-cubes"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-orange">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Total Stock Value') }}</div>
                    <div class="stat-number">৳{{ number_format($totalStockValue, 2) }}</div>
                    <div class="stat-small">{{ translate('Inventory value') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-chart-line"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-purple">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Low Stock Products') }}</div>
                    <div class="stat-number">{{ $lowStockProducts }}</div>
                    <div class="stat-small">{{ translate('Need restocking soon') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-exclamation-triangle"></i>
                </div>
            </div>
        </div>

    </div>

    <div class="row mb-4">

        <div class="col-xl-4 col-md-6 mb-3">
            <div class="mm-card mm-card-green">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('In Stock Products') }}</div>
                    <div class="stat-number">{{ $inStockProducts }}</div>
                    <div class="stat-small">{{ translate('Products available now') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-3">
            <div class="mm-card mm-card-red">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Out of Stock Products') }}</div>
                    <div class="stat-number">{{ $outOfStockProducts }}</div>
                    <div class="stat-small">{{ translate('Currently unavailable') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-times-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-3">
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
                <form method="GET" action="{{ route('reports.product-stocks') }}" id="filter-form">
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
                                <label>{{ translate('Stock Status') }}</label>
                                <select class="form-control" name="stock_status">
                                    <option value="">{{ translate('All') }}</option>
                                    <option value="in_stock"
                                        {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>
                                        {{ translate('In Stock') }}
                                    </option>
                                    <option value="low_stock"
                                        {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>
                                        {{ translate('Low Stock') }}
                                    </option>
                                    <option value="out_of_stock"
                                        {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>
                                        {{ translate('Out of Stock') }}
                                    </option>
                                </select>
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
                                    <option value="stock_asc" {{ request('sort_by') == 'stock_asc' ? 'selected' : '' }}>
                                        {{ translate('Stock (Low to High)') }}
                                    </option>
                                    <option value="stock_desc" {{ request('sort_by') == 'stock_desc' ? 'selected' : '' }}>
                                        {{ translate('Stock (High to Low)') }}
                                    </option>
                                    <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>
                                        {{ translate('Price (Low to High)') }}
                                    </option>
                                    <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>
                                        {{ translate('Price (High to Low)') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-search"></i> {{ translate('Filter') }}
                            </button>
                            <a href="{{ route('reports.product-stocks') }}" class="btn btn-secondary">
                                <i class="las la-undo-alt"></i> {{ translate('Reset') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Product Stock Report') }}</h5>
            <span class="badge badge-primary ml-2">{{ $products->total() }} {{ translate('Products') }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered aiz-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">{{ translate('Product') }}</th>
                            <th width="10%">{{ translate('SKU') }}</th>
                            <th width="12%">{{ translate('Category') }}</th>
                            <th width="10%">{{ translate('Brand') }}</th>
                            <th width="12%">{{ translate('Price') }}</th>
                            <th width="10%">{{ translate('Stock') }}</th>
                            <th width="10%">{{ translate('Status') }}</th>
                            <th width="10%">{{ translate('Stock Value') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $key => $product)
                            @php
                                $regularPrice = $product->price->regular_price ?? 0;
                                $stock = $product->inventory->stock ?? 0;
                                $lowStockQty = $product->inventory->low_stock_qty ?? 1;
                                $stockValue = $stock * $regularPrice;
                                $stockStatus = $stock > 0 ? ($stock <= $lowStockQty ? 'low' : 'in') : 'out';
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
    <td>
        <div class="price">
            <strong>৳{{ number_format($regularPrice, 2) }}</strong>
            @if ($product->price->sale_price && $product->price->sale_price < $regularPrice)
                <br>
                <small class="text-danger">
                    <del>৳{{ number_format($product->price->sale_price, 2) }}</del>
                </small>
            @endif
        </div>
        </div>
    <td>
        <div class="text-center">
            <span
                class="badge {{ $stockStatus == 'in' ? 'badge-success' : ($stockStatus == 'low' ? 'badge-warning' : 'badge-danger') }}">
                {{ number_format($stock) }}
            </span>
            @if ($stockStatus == 'low')
                <br>
                <small class="text-warning">{{ translate('Low Stock') }}</small>
            @endif
        </div>
        </div>
    <td>
        @if ($stockStatus == 'in')
            <span class="badge badge-success">{{ translate('In Stock') }}</span>
        @elseif($stockStatus == 'low')
            <span class="badge badge-warning">{{ translate('Low Stock') }}</span>
        @else
            <span class="badge badge-danger">{{ translate('Out of Stock') }}</span>
        @endif
        </div>
    <td>
        <strong>৳{{ number_format($stockValue, 2) }}</strong>
        <br>
        <small class="text-muted">{{ translate('Value') }}</small>
        </div>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="text-center">
                <div class="py-5">
                    <i class="las la-box-open fs-60 text-muted"></i>
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
        </script>

        <style>
            .fs-40 {
                font-size: 40px;
            }

            .size-40px {
                width: 40px;
                height: 40px;
                object-fit: cover;
            }

                {
                font-size: 14px;
                padding: 6px 12px;
            }
        </style>
    @endsection
