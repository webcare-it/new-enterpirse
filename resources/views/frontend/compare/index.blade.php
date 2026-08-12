<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ translate('Compare Products') }} - {{ get_setting('site_name') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome / Line Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fb;
            color: #1a1a2e;
        }

        /* Header Styles */
        .header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar {
            padding: 1rem 0;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff !important;
            letter-spacing: -0.5px;
        }

        .navbar-brand span {
            color: #e94560;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            transition: all 0.3s;
        }

        .nav-link:hover {
            color: #fff !important;
            transform: translateY(-2px);
        }

        .cart-icon {
            position: relative;
            font-size: 1.4rem;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -12px;
            background: #e94560;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 50%;
            min-width: 18px;
            text-align: center;
        }

        /* Breadcrumb */
        .breadcrumb-section {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
        }

        .breadcrumb {
            margin: 0;
        }

        .breadcrumb-item a {
            color: #6c757d;
            text-decoration: none;
            transition: color 0.3s;
        }

        .breadcrumb-item a:hover {
            color: #e94560;
        }

        .breadcrumb-item.active {
            color: #e94560;
        }

        /* Compare Section */
        .compare-section {
            min-height: calc(100vh - 250px);
            padding: 40px 0;
        }

        /* Compare Table */
        .compare-table {
            background: white;
            border-radius: 20px;
            overflow-x: auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .compare-table table {
            min-width: 800px;
        }

        .compare-table th,
        .compare-table td {
            vertical-align: middle;
            padding: 20px 15px;
            border-color: #e9ecef;
        }

        .compare-table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            border: none;
            position: relative;
        }

        .feature-label {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
            width: 180px;
        }

        .remove-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            cursor: pointer;
        }

        .remove-btn:hover {
            background: #dc3545;
            transform: rotate(90deg);
        }

        .product-image {
            min-height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .product-image img {
            max-height: 120px;
            max-width: 100%;
            object-fit: contain;
            transition: transform 0.3s;
        }

        .product-image img:hover {
            transform: scale(1.05);
        }

        .product-name {
            font-size: 16px;
            font-weight: 600;
            margin: 10px 0;
            color: #2c3e50;
        }

        .product-brand {
            font-size: 12px;
            color: #6c757d;
        }

        .current-price {
            font-size: 20px;
            font-weight: 700;
            color: #e94560;
        }

        .old-price {
            font-size: 14px;
            color: #adb5bd;
            text-decoration: line-through;
            margin-left: 8px;
        }

        .badge-in-stock {
            background: #28a745;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: white;
        }

        .badge-out-stock {
            background: #dc3545;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: white;
        }

        .rating {
            color: #ffc107;
            font-size: 14px;
        }

        .rating-count {
            font-size: 12px;
            color: #6c757d;
            margin-left: 5px;
        }

        .short-description {
            font-size: 13px;
            color: #6c757d;
            line-height: 1.5;
        }

        .btn-add-cart {
            background: #e94560;
            border: none;
            padding: 8px 20px;
            border-radius: 25px;
            color: white;
            font-weight: 500;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn-add-cart:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(233, 69, 96, 0.3);
        }

        .btn-view-details {
            background: transparent;
            border: 1px solid #e94560;
            padding: 8px 20px;
            border-radius: 25px;
            color: #e94560;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-view-details:hover {
            background: #e94560;
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .empty-state-icon {
            font-size: 80px;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        .empty-state-title {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .empty-state-text {
            color: #6c757d;
            margin-bottom: 30px;
        }

        .btn-shop-now {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 30px;
            color: white;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-shop-now:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        /* Footer */
        .footer {
            background: #1a1a2e;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 60px;
            padding: 60px 0 20px;
        }

        .footer h5 {
            color: white;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s;
        }

        .footer-links a:hover {
            color: #e94560;
            padding-left: 5px;
        }

        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            margin-right: 10px;
            color: white;
            transition: all 0.3s;
        }

        .social-icons a:hover {
            background: #e94560;
            transform: translateY(-3px);
        }

        .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .compare-table {
            animation: fadeInUp 0.5s ease;
        }

        /* Loading Spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #e94560;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .compare-table {
                font-size: 12px;
            }

            .compare-table th,
            .compare-table td {
                padding: 10px 8px;
            }

            .product-name {
                font-size: 12px;
            }

            .current-price {
                font-size: 14px;
            }

            .btn-add-cart,
            .btn-view-details {
                padding: 5px 12px;
                font-size: 11px;
            }

            .feature-label {
                width: 100px;
                font-size: 11px;
            }
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="">
                    Shop<span>Hub</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link" href="">{{ translate('Home') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">{{ translate('Shop') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">{{ translate('Categories') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">{{ translate('Deals') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">{{ translate('Contact') }}</a>
                        </li>
                        <li class="nav-item ms-lg-3">
                            <a class="nav-link cart-icon" href="">
                                <i class="las la-shopping-cart"></i>
                                <span class="cart-count" id="cart-count">0</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">{{ translate('Home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ translate('Compare Products') }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Compare Section -->
    <div class="compare-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <div>
                            <h2 class="mb-0 fw-bold">{{ translate('Compare Products') }}</h2>
                            <p class="text-muted mb-0 mt-2">
                                {{ translate('Compare and find the best product for you') }}</p>
                        </div>
                        @if ($products->count() > 0)
                            <button type="button" class="btn btn-danger px-4 py-2 rounded-pill"
                                onclick="clearAllCompare()">
                                <i class="las la-trash-alt me-2"></i>{{ translate('Clear All') }}
                            </button>
                        @endif
                    </div>

                    @if ($products->count() > 0)
                        <!-- Compare Table -->
                        <div class="compare-table">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="feature-label">{{ translate('Features') }}</th>
                                        @foreach ($products as $product)
                                            <th class="text-center" style="min-width: 250px;">
                                                <button class="remove-btn"
                                                    onclick="removeFromCompare({{ $product->id }})">
                                                    <i class="las la-times"></i>
                                                </button>
                                                <div class="product-image">
                                                    <img src="{{ uploaded_asset($product->thumbnail) }}"
                                                        alt="{{ $product->name }}">
                                                </div>
                                                <h5 class="product-name">{{ $product->name }}</h5>
                                                @if ($product->brand)
                                                    <p class="product-brand mb-0">{{ translate('Brand') }}:
                                                        {{ $product->brand->name }}</p>
                                                @endif
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Price Row -->
                                    <tr>
                                        <td class="feature-label">{{ translate('Price') }}</td>
                                        @foreach ($products as $product)
                                            <td class="text-center">
                                                @php
                                                    $price = $product->price->regular_price ?? 0;
                                                    $sale_price = $product->price->sale_price ?? null;
                                                    $discount = $product->price->discount ?? 0;
                                                    $discount_type = $product->price->discount_type ?? null;

                                                    if ($sale_price && $sale_price < $price) {
                                                        $display_price = $sale_price;
                                                    } elseif ($discount > 0) {
                                                        if ($discount_type == 'percent') {
                                                            $display_price = $price - ($price * $discount) / 100;
                                                        } else {
                                                            $display_price = $price - $discount;
                                                        }
                                                    } else {
                                                        $display_price = $price;
                                                    }
                                                @endphp
                                                <div class="price">
                                                    <span
                                                        class="current-price">৳{{ number_format($display_price, 2) }}</span>
                                                    @if ($display_price != $price && $price > 0)
                                                        <del class="old-price">৳{{ number_format($price, 2) }}</del>
                                                    @endif
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>

                                    <!-- Stock Status Row -->
                                    <tr>
                                        <td class="feature-label">{{ translate('Stock Status') }}</td>
                                        @foreach ($products as $product)
                                            <td class="text-center">
                                                @if ($product->inventory && $product->inventory->stock > 0)
                                                    <span class="badge-in-stock">
                                                        <i class="las la-check-circle"></i> {{ translate('In Stock') }}
                                                    </span>
                                                    <small
                                                        class="d-block text-muted mt-1">{{ $product->inventory->stock }}
                                                        {{ translate('units available') }}</small>
                                                @else
                                                    <span class="badge-out-stock">
                                                        <i class="las la-times-circle"></i>
                                                        {{ translate('Out of Stock') }}
                                                    </span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>

                                    <!-- SKU Row -->
                                    <tr>
                                        <td class="feature-label">{{ translate('SKU') }}</td>
                                        @foreach ($products as $product)
                                            <td class="text-center">{{ $product->inventory->sku ?? 'N/A' }}</td>
                                        @endforeach
                                    </tr>

                                    <!-- Rating Row -->
                                    <tr>
                                        <td class="feature-label">{{ translate('Rating') }}</td>
                                        @foreach ($products as $product)
                                            <td class="text-center">
                                                @php
                                                    $avg_rating = $product->reviews->avg('rating') ?? 0;
                                                    $review_count = $product->reviews->count();
                                                @endphp
                                                <div class="rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= round($avg_rating))
                                                            <i class="las la-star"></i>
                                                        @elseif($i <= $avg_rating + 0.5)
                                                            <i class="las la-star-half-alt"></i>
                                                        @else
                                                            <i class="lar la-star"></i>
                                                        @endif
                                                    @endfor
                                                    <span class="rating-count">({{ $review_count }}
                                                        {{ translate('reviews') }})</span>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>

                                    <!-- Category Row -->
                                    <tr>
                                        <td class="feature-label">{{ translate('Category') }}</td>
                                        @foreach ($products as $product)
                                            <td class="text-center">{{ $product->category->category_name ?? 'N/A' }}
                                            </td>
                                        @endforeach
                                    </tr>

                                    <!-- Brand Row -->
                                    <tr>
                                        <td class="feature-label">{{ translate('Brand') }}</td>
                                        @foreach ($products as $product)
                                            <td class="text-center">{{ $product->brand->name ?? 'N/A' }}</td>
                                        @endforeach
                                    </tr>

                                    <!-- Description Row -->
                                    <tr>
                                        <td class="feature-label">{{ translate('Description') }}</td>
                                        @foreach ($products as $product)
                                            <td class="text-center">
                                                <div class="short-description">
                                                    {{ Str::limit($product->short_description ?? $product->description, 100) }}
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>

                                    <!-- Shipping Row -->
                                    <tr>
                                        <td class="feature-label">{{ translate('Shipping') }}</td>
                                        @foreach ($products as $product)
                                            <td class="text-center">
                                                @if ($product->shipping)
                                                    @if ($product->shipping->shipping_type == 'free')
                                                        <span
                                                            class="badge-in-stock">{{ translate('Free Shipping') }}</span>
                                                    @else
                                                        <span>৳{{ number_format($product->shipping->shipping_cost ?? 0, 2) }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">{{ translate('N/A') }}</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>

                                    <!-- Actions Row -->
                                    <tr>
                                        <td class="feature-label">{{ translate('Actions') }}</td>
                                        @foreach ($products as $product)
                                            <td class="text-center">
                                                <div class="d-flex flex-column gap-2">
                                                    @if ($product->inventory && $product->inventory->stock > 0)
                                                        <button class="btn-add-cart"
                                                            onclick="addToCart({{ $product->id }})">
                                                            <i
                                                                class="las la-shopping-cart me-2"></i>{{ translate('Add to Cart') }}
                                                        </button>
                                                    @else
                                                        <button class="btn-add-cart" disabled
                                                            style="opacity: 0.5; cursor: not-allowed;">
                                                            <i
                                                                class="las la-shopping-cart me-2"></i>{{ translate('Out of Stock') }}
                                                        </button>
                                                    @endif
                                                    <a href="" class="btn-view-details">
                                                        <i class="las la-eye me-2"></i>{{ translate('View Details') }}
                                                    </a>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Continue Shopping Button -->
                        <div class="text-center mt-5">
                            <a href="" class="btn btn-secondary px-4 py-2 rounded-pill">
                                <i class="las la-arrow-left me-2"></i>{{ translate('Continue Shopping') }}
                            </a>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="las la-balance-scale"></i>
                            </div>
                            <h3 class="empty-state-title">{{ translate('No Products to Compare') }}</h3>
                            <p class="empty-state-text">
                                {{ translate('Add products to compare and see which one suits you best.') }}</p>
                            <a href="" class="btn-shop-now">
                                <i class="las la-shopping-cart me-2"></i>{{ translate('Continue Shopping') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5>Shop<span style="color: #e94560;">Hub</span></h5>
                    <p class="mt-3">
                        {{ translate('Your one-stop destination for all your shopping needs. Quality products, best prices, fast delivery.') }}
                    </p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="lab la-facebook-f"></i></a>
                        <a href="#"><i class="lab la-twitter"></i></a>
                        <a href="#"><i class="lab la-instagram"></i></a>
                        <a href="#"><i class="lab la-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5>{{ translate('Quick Links') }}</h5>
                    <ul class="footer-links">
                        <li><a href="">{{ translate('About Us') }}</a></li>
                        <li><a href="">{{ translate('Contact Us') }}</a></li>
                        <li><a href="">{{ translate('FAQs') }}</a></li>
                        <li><a href="">{{ translate('Blog') }}</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h5>{{ translate('Categories') }}</h5>
                    <ul class="footer-links">
                        <li><a href="#">{{ translate('Electronics') }}</a></li>
                        <li><a href="#">{{ translate('Fashion') }}</a></li>
                        <li><a href="#">{{ translate('Home & Living') }}</a></li>
                        <li><a href="#">{{ translate('Sports & Fitness') }}</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h5>{{ translate('Contact Info') }}</h5>
                    <ul class="footer-links">
                        <li><i class="las la-map-marker"></i> 123 Main Street, NY 10001</li>
                        <li><i class="las la-phone"></i> +1 234 567 890</li>
                        <li><i class="las la-envelope"></i> support@shophub.com</li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; {{ date('Y') }} ShopHub. {{ translate('All rights reserved.') }}</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        // CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Remove product from compare
        function removeFromCompare(productId) {
            if (confirm('{{ translate('Are you sure you want to remove this product from compare?') }}')) {
                showLoading();
                $.ajax({
                    type: "POST",
                    url: "",
                    data: {
                        product_id: productId
                    },
                    success: function(response) {
                        hideLoading();
                        if (response.success) {
                            showNotification('success', response.message);
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showNotification('danger', response.message);
                        }
                    },
                    error: function(xhr) {
                        hideLoading();
                        showNotification('danger', '{{ translate('Something went wrong') }}');
                    }
                });
            }
        }

        // Clear all compare products
        function clearAllCompare() {
            if (confirm('{{ translate('Are you sure you want to clear all compare products?') }}')) {
                showLoading();
                $.ajax({
                    type: "POST",
                    url: "",
                    success: function(response) {
                        hideLoading();
                        if (response.success) {
                            showNotification('success', response.message);
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showNotification('danger', response.message);
                        }
                    },
                    error: function(xhr) {
                        hideLoading();
                        showNotification('danger', '{{ translate('Something went wrong') }}');
                    }
                });
            }
        }

        // Add to cart
        function addToCart(productId) {
            showLoading();
            $.ajax({
                type: "POST",
                url: "",
                data: {
                    id: productId,
                    quantity: 1
                },
                success: function(response) {
                    hideLoading();
                    if (response.success) {
                        showNotification('success', response.message);
                        updateCartCount();
                    } else {
                        showNotification('danger', response.message);
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    showNotification('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        // Update cart count in navigation
        function updateCartCount() {
            $.ajax({
                type: "GET",
                url: "",
                success: function(response) {
                    $('#cart-count').text(response.count);
                }
            });
        }

        // Show loading spinner
        function showLoading() {
            // You can implement a loading spinner overlay here
        }

        // Hide loading spinner
        function hideLoading() {
            // You can hide the loading spinner here
        }

        // Show notification
        function showNotification(type, message) {
            // Using Aiz notification if available, otherwise alert
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                AIZ.plugins.notify(type, message);
            } else {
                alert(message);
            }
        }
    </script>
</body>

</html>
