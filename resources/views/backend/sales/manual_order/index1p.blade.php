@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Manual Order Creation') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('manual_orders.index') }}" class="btn btn-secondary">
                    <i class="las la-arrow-left"></i> {{ translate('Back to Orders') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Product Selection -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Select Products') }}</h5>
                </div>
                <div class="card-body">
                    <!-- Category Selection -->
                    <div class="row">
                        <div class="col-8">
                            <div class="form-group">
                                <label>{{ translate('Product Name Or SKU') }}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="product-search"
                                        placeholder="{{ translate('Search by product name or SKU') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" id="search-button" type="button">
                                            <i class="las la-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>{{ translate('Select Category') }}</label>
                                <select class="form-control aiz-selectpicker" id="category-select" data-live-search="true">
                                    <option value="all" selected>ALL</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div id="products-grid" class="row mt-3">
                        <div class="col-12 text-center py-5">
                            <i class="las la-box-open fs-60 text-muted"></i>
                            <h5 class="text-muted mt-3">{{ translate('Select a category to view products') }}</h5>
                        </div>
                    </div>

                    <!-- Load More Button -->
                    <div id="load-more-container" class="text-center mt-3" style="display: none;">
                        <button class="btn btn-primary" id="load-more-btn">
                            <i class="las la-spinner la-spin" style="display: none;"></i>
                            <span class="btn-text">{{ translate('Load More Products') }}</span>
                            <span class="badge badge-light ml-2" id="remaining-count"></span>
                        </button>
                    </div>

                    <style>
                        #load-more-container {
                            margin-top: 2.5rem !important;
                            text-align: center !important;
                            display: none;
                            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                        }

                        #load-more-btn {
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            gap: 0.65rem;
                            padding: 0.9rem 2.4rem;
                            background: linear-gradient(145deg, #1e293b, #0f172a);
                            border: none;
                            border-radius: 60px;
                            color: #f8fafc;
                            font-weight: 600;
                            font-size: 1rem;
                            letter-spacing: 0.3px;
                            box-shadow: 0 8px 20px -6px rgba(15, 23, 42, 0.25), 0 2px 6px rgba(0, 0, 0, 0.08);
                            transition: all 0.25s cubic-bezier(0.2, 0, 0, 1);
                            cursor: pointer;
                            position: relative;
                            background-clip: padding-box;
                            border: 1px solid rgba(255, 255, 255, 0.06);
                            backdrop-filter: blur(2px);
                            min-width: 220px;
                        }

                        #load-more-btn:hover {
                            transform: translateY(-2px) scale(1.02);
                            box-shadow: 0 16px 32px -10px rgba(15, 23, 42, 0.35), 0 4px 12px rgba(0, 0, 0, 0.1);
                            background: linear-gradient(145deg, #1e2a3a, #0b1422);
                            border-color: rgba(255, 255, 255, 0.15);
                        }

                        #load-more-btn:active {
                            transform: translateY(1px) scale(0.98);
                            box-shadow: 0 4px 12px -4px rgba(0, 0, 0, 0.3);
                        }

                        #load-more-btn .las.la-spinner {
                            font-size: 1.2rem;
                            color: #94a3b8;
                            display: none;
                            /* stays hidden until you toggle it */
                        }

                        /* main button text */
                        .btn-text {
                            font-weight: 600;
                            letter-spacing: 0.2px;
                            background: linear-gradient(to right, #f1f5f9, #e2e8f0);
                            -webkit-background-clip: text;
                            background-clip: text;
                            color: transparent;
                            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
                        }

                        /* Badge (remaining count) — redesigned as a chic pill */
                        .badge.badge-light.ml-2 {
                            background: rgba(255, 255, 255, 0.12) !important;
                            backdrop-filter: blur(4px);
                            color: #e2e8f0;
                            font-weight: 500;
                            font-size: 0.75rem;
                            padding: 0.25rem 0.75rem;
                            border-radius: 40px;
                            border: 1px solid rgba(255, 255, 255, 0.08);
                            margin-left: 0.5rem !important;
                            letter-spacing: 0.3px;
                            transition: all 0.2s;
                            box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.05);
                        }

                        /* if you ever show the badge with a number, it looks crisp */
                        #remaining-count:not(:empty) {
                            background: rgba(255, 255, 255, 0.15);
                            padding: 0.2rem 0.8rem;
                            border-radius: 40px;
                        }

                        /* loading state (when spinner is visible) — graceful */
                        #load-more-btn:has(.la-spinner[style*="display: block"]) .btn-text {
                            opacity: 0.7;
                        }

                        /* optional: small touch for the container */
                        #load-more-container {
                            position: relative;
                        }

                        /* add a tiny decorative line above the button (optional) */
                        #load-more-container::before {
                            content: '';
                            display: block;
                            width: 60px;
                            height: 2px;
                            background: linear-gradient(90deg, transparent, rgba(148, 163, 184, 0.3), transparent);
                            margin: 0 auto 1.2rem auto;
                            border-radius: 4px;
                        }
                    </style>
                </div>
            </div>
        </div>

        <!-- Right Column - Cart & Customer Info -->
        <div class="col-lg-4">
            <!-- Customer Information Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Customer Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>{{ translate('Search Customer') }}</label>
                        <select class="form-control" id="customer-search" style="width: 100%;"></select>
                        <small class="text-muted">{{ translate('Search by name, email or phone') }}</small>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Customer Name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="customer-name"
                            placeholder="{{ translate('Customer Name') }}">
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Customer Email') }}</label>
                        <input type="email" class="form-control" id="customer-email"
                            placeholder="{{ translate('Customer Email') }}">
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Customer Phone') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="customer-phone"
                            placeholder="{{ translate('Customer Phone') }}">
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Shipping Address') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="shipping-address" rows="3"
                            placeholder="{{ translate('Full Shipping Address') }}"></textarea>
                    </div>
                </div>
            </div>

            <!-- Shopping Cart Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Shopping Cart') }}</h5>
                    <button class="btn btn-sm btn-danger" id="clear-cart">
                        <i class="las la-trash"></i> {{ translate('Clear') }}
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th width="35%">{{ translate('Product') }}</th>
                                    <th width="15%">{{ translate('Price') }}</th>
                                    <th width="10%">{{ translate('Qty') }}</th>
                                    <th width="20%">{{ translate('Total') }}</th>
                                    <th width="10%"></th>
                                </tr>
                            </thead>
                            <tbody id="cart-items">
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="las la-shopping-cart fs-40 text-muted"></i>
                                        <p class="text-muted mb-0">{{ translate('Cart is empty') }}</p>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right">{{ translate('Subtotal') }}:</td>
                                    <td colspan="2" class="text-right font-weight-bold" id="cart-subtotal">৳0.00</td>
                                </tr>
                                <tr id="coupon-discount-row" style="display: none;">
                                    <td colspan="3" class="text-right text-success">
                                        {{ translate('Coupon Discount') }}:</td>
                                    <td colspan="2" class="text-right text-success" id="coupon-discount-display">-
                                        ৳0.00</td>
                                </tr>
                                <tr id="manual-discount-row" style="display: none;">
                                    <td colspan="3" class="text-right text-danger">{{ translate('Manual Discount') }}:
                                    </td>
                                    <td colspan="2" class="text-right text-danger" id="manual-discount-display">-
                                        ৳0.00</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right font-weight-bold h6">
                                        {{ translate('Grand Total') }}: </td>
                                    <td colspan="2" class="font-weight-bold text-primary h5" id="cart-total">৳0.00
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Coupon Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Apply Coupon') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="coupon_code"
                                placeholder="{{ translate('Enter coupon code') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="apply-coupon">
                                    <i class="las la-tag"></i> {{ translate('Apply') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="applied-coupon" style="display: none;" class="alert alert-success mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ translate('Coupon Applied') }}</strong>
                                <br>
                                <span id="coupon-code-display"></span>
                                <span class="text-primary"> - <span id="coupon-discount-amount"></span></span>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger" id="remove-coupon">
                                <i class="las la-times"></i> {{ translate('Remove') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manual Discount Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Manual Discount') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="number" step="0.01" class="form-control" id="manual_discount_amount"
                                    placeholder="{{ translate('Discount Amount') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="form-control" id="manual_discount_type">
                                    <option value="flat">{{ translate('Flat (BDT)') }}</option>
                                    <option value="percent">{{ translate('Percent (%)') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" id="apply-manual-discount">
                                <i class="las la-check"></i>
                            </button>
                        </div>
                    </div>

                    <div id="manual-discount-info" style="display: none;" class="alert alert-warning mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ translate('Discount Applied') }}</strong>
                                <br>
                                <span id="manual-discount-value"></span>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger" id="remove-manual-discount">
                                <i class="las la-times"></i> {{ translate('Remove') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Payment Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>{{ translate('Payment Method') }} <span class="text-danger">*</span></label>
                        <select class="form-control" id="payment-type">
                            <option value="cash_on_delivery">{{ translate('Cash on Delivery') }}</option>
                            <option value="bkash">{{ translate('bKash') }}</option>
                            <option value="nagad">{{ translate('Nagad') }}</option>
                            <option value="rocket">{{ translate('Rocket') }}</option>
                            <option value="bank_transfer">{{ translate('Bank Transfer') }}</option>
                            <option value="manual">{{ translate('Manual Payment') }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Order Notes') }}</label>
                        <textarea class="form-control" id="order-notes" rows="2"
                            placeholder="{{ translate('Additional notes for this order') }}"></textarea>
                    </div>

                    <button class="btn btn-primary btn-block btn-lg" id="place-order">
                        <i class="las la-check-circle"></i> {{ translate('Place Order') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <script type="text/javascript">
        let searchTimeout;
        let currentPage = 1;
        let totalProducts = 0;
        let currentCategory = 'all';
        let currentSearch = '';
        let isLoading = false;
        const PER_PAGE = 40;

        $(document).ready(function() {
            console.log('Document ready - Manual Order System initialized');

            // Initialize Select2 for customer search
            $('#customer-search').select2({
                placeholder: "{{ translate('Search customer...') }}",
                minimumInputLength: 2,
                ajax: {
                    url: "{{ route('manual_orders.search-customer') }}",
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            // Handle customer selection
            $('#customer-search').on('select2:select', function(e) {
                var data = e.params.data;
                $('#customer-name').val(data.name);
                $('#customer-email').val(data.email);
                $('#customer-phone').val(data.phone);
            });

            // Product search by name or SKU
            $('#product-search').on('keyup', function() {
                clearTimeout(searchTimeout);
                var searchTerm = $(this).val().trim();
                currentSearch = searchTerm;

                searchTimeout = setTimeout(function() {
                    console.log('Searching for:', searchTerm);
                    currentPage = 1;
                    loadProducts(currentCategory, searchTerm, currentPage, true);
                }, 300);
            });

            // Trigger search on Enter key
            $('#product-search').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    clearTimeout(searchTimeout);
                    var searchTerm = $(this).val().trim();
                    currentSearch = searchTerm;
                    currentPage = 1;
                    console.log('Enter key pressed - Searching for:', searchTerm);
                    loadProducts(currentCategory, searchTerm, currentPage, true);
                }
            });

            // Search button click
            $('#search-button').on('click', function() {
                var searchTerm = $('#product-search').val().trim();
                currentSearch = searchTerm;
                currentPage = 1;
                console.log('Search button clicked - Searching for:', searchTerm);
                loadProducts(currentCategory, searchTerm, currentPage, true);
            });

            // Category change
            $('#category-select').on('change', function() {
                currentCategory = $(this).val();
                currentPage = 1;
                $('#product-search').val('');
                currentSearch = '';
                console.log('Category changed to:', currentCategory);
                loadProducts(currentCategory, '', currentPage, true);
            });

            // Load more button click
            $('#load-more-btn').on('click', function() {
                if (!isLoading) {
                    currentPage++;
                    console.log('Loading more products - Page:', currentPage);
                    loadProducts(currentCategory, currentSearch, currentPage, false);
                }
            });

            // Load initial products
            loadProducts('all', '', 1, true);

            // Load cart on page load
            loadCart();

            // Handle add to cart for variant products - FIXED
            $(document).on('click', '.add-to-cart-variant', function() {
                var productId = $(this).data('product-id');
                var variantSelect = $('#variant_select_' + productId);
                var variantId = variantSelect.val();
                var quantity = parseInt($('#qty_' + productId).val()) || 1;

                if (!variantId) {
                    AIZ.plugins.notify('warning', 'Please select a variant');
                    return;
                }

                var selectedOption = variantSelect.find('option:selected');
                var maxStock = parseInt(selectedOption.data('stock')) || 0;
                var price = parseFloat(selectedOption.data('price')) || 0;

                console.log('Variant details:', {
                    variantId: variantId,
                    quantity: quantity,
                    maxStock: maxStock,
                    price: price
                });

                if (maxStock === 0) {
                    AIZ.plugins.notify('danger', 'This variant is out of stock');
                    return;
                }

                if (quantity > maxStock) {
                    AIZ.plugins.notify('danger', 'Quantity exceeds available stock! Only ' + maxStock +
                        ' available.');
                    return;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('manual_orders.add-variant-to-cart') }}",
                    data: {
                        variant_id: variantId,
                        quantity: quantity
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            updateCartUI(response);
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                        }
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        AIZ.plugins.notify('danger', response?.message ||
                            'Something went wrong');
                    }
                });
            });

            // Handle add to cart for simple products - FIXED
            $(document).on('click', '.add-to-cart-simple', function() {
                var productId = $(this).data('product-id');
                var price = parseFloat($(this).data('price')) || 0;
                var quantity = parseInt($('#qty_' + productId).val()) || 1;
                var maxStock = parseInt($(this).data('stock')) || 0;

                console.log('Simple product details:', {
                    productId: productId,
                    quantity: quantity,
                    maxStock: maxStock,
                    price: price
                });

                if (maxStock === 0) {
                    AIZ.plugins.notify('danger', 'This product is out of stock');
                    return;
                }

                if (quantity > maxStock) {
                    AIZ.plugins.notify('danger', 'Quantity exceeds available stock! Only ' + maxStock +
                        ' available.');
                    return;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('manual_orders.add-to-cart') }}",
                    data: {
                        product_id: productId,
                        quantity: quantity,
                        price: price
                    },
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            updateCartUI(response);
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                        }
                    },
                    error: function(xhr) {
                        AIZ.plugins.notify('danger', 'Something went wrong');
                    }
                });
            });

            // Handle cart quantity update
            $(document).on('change', '.cart-qty', function() {
                var key = $(this).data('key');
                var quantity = parseInt($(this).val()) || 1;

                if (quantity < 1) {
                    $(this).val(1);
                    quantity = 1;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('manual_orders.update-cart') }}",
                    data: {
                        key: key,
                        quantity: quantity
                    },
                    success: function(response) {
                        if (response.success) {
                            updateCartUI(response);
                        }
                    }
                });
            });

            // Handle remove from cart
            $(document).on('click', '.remove-from-cart', function() {
                var key = $(this).data('key');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('manual_orders.remove-from-cart') }}",
                    data: {
                        key: key
                    },
                    success: function(response) {
                        if (response.success) {
                            updateCartUI(response);
                            AIZ.plugins.notify('success', 'Item removed from cart');
                        }
                    }
                });
            });

            // Clear cart
            $('#clear-cart').on('click', function() {
                if (confirm('{{ translate('Are you sure you want to clear the cart?') }}')) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "{{ route('manual_orders.clear-cart') }}",
                        success: function(response) {
                            if (response.success) {
                                updateCartUI(response);
                                AIZ.plugins.notify('success', response.message);
                                resetCouponUI();
                                resetManualDiscountUI();
                            }
                        }
                    });
                }
            });

            // Apply coupon
            $('#apply-coupon').on('click', function() {
                var couponCode = $('#coupon_code').val();

                if (!couponCode) {
                    AIZ.plugins.notify('warning', 'Please enter coupon code');
                    return;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('manual_orders.apply-coupon') }}",
                    data: {
                        coupon_code: couponCode
                    },
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            updateCartTotals(response);
                            $('#applied-coupon').show();
                            $('#coupon-code-display').text(response.coupon_code);
                            $('#coupon-discount-amount').text('-' + formatMoney(response
                                .discount_amount));
                            $('#coupon-discount-display').text('- ' + formatMoney(response
                                .discount_amount));
                            $('#coupon-discount-row').show();
                            $('#apply-coupon').hide();
                            $('#coupon_code').val('');
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                        }
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        AIZ.plugins.notify('danger', response?.message ||
                            'Something went wrong');
                    }
                });
            });

            // Remove coupon
            $('#remove-coupon').on('click', function() {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('manual_orders.remove-coupon') }}",
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            updateCartTotals(response);
                            resetCouponUI();
                        }
                    }
                });
            });

            // Apply manual discount
            $('#apply-manual-discount').on('click', function() {
                var discountAmount = parseFloat($('#manual_discount_amount').val());
                var discountType = $('#manual_discount_type').val();

                if (!discountAmount || discountAmount <= 0) {
                    AIZ.plugins.notify('warning', 'Please enter discount amount');
                    return;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('manual_orders.apply-manual-discount') }}",
                    data: {
                        discount_amount: discountAmount,
                        discount_type: discountType
                    },
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            updateCartTotals(response);
                            $('#manual-discount-info').show();
                            $('#manual-discount-value').text('-' + formatMoney(response
                                .discount_amount));
                            $('#manual-discount-display').text('- ' + formatMoney(response
                                .discount_amount));
                            $('#manual-discount-row').show();
                            $('#manual_discount_amount').val('');
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                        }
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        AIZ.plugins.notify('danger', response?.message ||
                            'Something went wrong');
                    }
                });
            });

            // Remove manual discount
            $('#remove-manual-discount').on('click', function() {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('manual_orders.remove-manual-discount') }}",
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            updateCartTotals(response);
                            resetManualDiscountUI();
                        }
                    }
                });
            });

            // Place order
            $('#place-order').on('click', function() {
                var customerName = $('#customer-name').val().trim();
                var customerPhone = $('#customer-phone').val().trim();
                var shippingAddress = $('#shipping-address').val().trim();

                if (!customerName) {
                    AIZ.plugins.notify('warning', 'Please enter customer name');
                    $('#customer-name').focus();
                    return;
                }
                if (!customerPhone) {
                    AIZ.plugins.notify('warning', 'Please enter customer phone');
                    $('#customer-phone').focus();
                    return;
                }
                if (!shippingAddress) {
                    AIZ.plugins.notify('warning', 'Please enter shipping address');
                    $('#shipping-address').focus();
                    return;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('manual_orders.place-order') }}",
                    data: {
                        customer_id: $('#customer-search').val(),
                        customer_name: customerName,
                        customer_email: $('#customer-email').val().trim(),
                        customer_phone: customerPhone,
                        shipping_address: shippingAddress,
                        payment_type: $('#payment-type').val(),
                        notes: $('#order-notes').val().trim()
                    },
                    beforeSend: function() {
                        $('#place-order').prop('disabled', true).html(
                            '<i class="las la-spinner la-spin"></i> Processing...');
                    },
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            setTimeout(function() {
                                window.location.href =
                                    "{{ route('orders.show', '') }}/" + response
                                    .order_id;
                            }, 1500);
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                            $('#place-order').prop('disabled', false).html(
                                '<i class="las la-check-circle"></i> Place Order');
                        }
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        AIZ.plugins.notify('danger', response?.message ||
                            'Something went wrong');
                        $('#place-order').prop('disabled', false).html(
                            '<i class="las la-check-circle"></i> Place Order');
                    }
                });
            });
        });

        // Load products with pagination
        function loadProducts(categoryId, searchTerm, page, reset) {
            if (isLoading) return;
            isLoading = true;

            console.log('loadProducts called with:', {
                categoryId: categoryId,
                searchTerm: searchTerm,
                page: page,
                reset: reset
            });

            if (reset) {
                $('#products-grid').html(
                    '<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div></div>'
                );
                $('#load-more-container').hide();
            }

            var postData = {
                category_id: categoryId || 'all',
                page: page,
                per_page: PER_PAGE
            };

            if (searchTerm && searchTerm.length > 0) {
                postData.search = searchTerm;
            }

            console.log('Sending AJAX request with data:', postData);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('manual_orders.get-products') }}",
                data: postData,
                dataType: 'json',
                success: function(response) {
                    isLoading = false;
                    console.log('AJAX Response received:', response);

                    if (reset) {
                        $('#products-grid').html(response.html);
                    } else {
                        $('#products-grid').append(response.html);
                    }

                    totalProducts = response.total;
                    currentPage = response.current_page;
                    var totalPages = response.total_pages;

                    console.log('Pagination info - Current page:', currentPage, 'Total pages:', totalPages,
                        'Total products:', totalProducts);

                    if (currentPage < totalPages) {
                        $('#load-more-container').show();
                        var remaining = totalProducts - (currentPage * PER_PAGE);
                        $('#remaining-count').text(remaining > 0 ? remaining + ' more' : '');
                        $('#load-more-btn .btn-text').text('{{ translate('Load More Products') }}');
                        $('#load-more-btn .las').hide();
                    } else {
                        $('#load-more-container').hide();
                    }
                },
                error: function(xhr) {
                    isLoading = false;
                    console.error('Error loading products:', xhr);
                    console.error('Error response:', xhr.responseJSON);
                    if (reset) {
                        $('#products-grid').html(
                            '<div class="col-12 text-center py-5"><i class="las la-exclamation-circle fs-60 text-danger"></i><h5 class="text-danger mt-3">Error loading products</h5><p class="text-muted">Please check console for errors</p></div>'
                        );
                    }
                    $('#load-more-container').hide();
                }
            });
        }

        function loadCart() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('manual_orders.get-cart') }}",
                success: function(response) {
                    updateCartUI(response);
                }
            });
        }

        function updateCartUI(response) {
            $('#cart-items').html(response.cart);
            $('#cart-total').text(formatMoney(response.total));
            $('#cart-subtotal').text(formatMoney(response.subtotal || response.total));

            if (response.coupon_discount && response.coupon_discount > 0) {
                $('#coupon-discount-row').show();
                $('#coupon-discount-display').text('- ' + formatMoney(response.coupon_discount));
            } else {
                $('#coupon-discount-row').hide();
            }

            if (response.discount_amount && response.discount_amount > 0) {
                $('#manual-discount-row').show();
                $('#manual-discount-display').text('- ' + formatMoney(response.discount_amount));
            } else {
                $('#manual-discount-row').hide();
            }
        }

        function updateCartTotals(response) {
            $('#cart-total').text(formatMoney(response.cart_total));
            $('#cart-subtotal').text(formatMoney(response.cart_subtotal || response.cart_total));

            if (response.coupon_discount && response.coupon_discount > 0) {
                $('#coupon-discount-row').show();
                $('#coupon-discount-display').text('- ' + formatMoney(response.coupon_discount));
            } else {
                $('#coupon-discount-row').hide();
            }

            if (response.discount_amount && response.discount_amount > 0) {
                $('#manual-discount-row').show();
                $('#manual-discount-display').text('- ' + formatMoney(response.discount_amount));
            } else {
                $('#manual-discount-row').hide();
            }
        }

        function resetCouponUI() {
            $('#applied-coupon').hide();
            $('#coupon-discount-row').hide();
            $('#apply-coupon').show();
            $('#coupon_code').val('');
        }

        function resetManualDiscountUI() {
            $('#manual-discount-info').hide();
            $('#manual-discount-row').hide();
            $('#manual_discount_amount').val('');
        }

        function formatMoney(amount) {
            return '৳' + parseFloat(amount || 0).toFixed(2);
        }
    </script>

    <style>
        .product-card {
            transition: transform 0.3s;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .product-title {
            font-size: 14px;
            font-weight: 500;
            margin: 10px 0;
            min-height: 40px;
        }

        .current-price {
            font-size: 18px;
            font-weight: bold;
            color: #e94560;
        }

        .size-100px {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .fs-40 {
            font-size: 40px;
        }

        .fs-60 {
            font-size: 60px;
        }

        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #d4d4d4;
            border-radius: 4px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .table td {
            vertical-align: middle;
        }

        .variant-select {
            font-size: 12px;
            padding: 4px 8px;
        }

        .quantity-input {
            text-align: center;
            width: 60px;
        }

        #load-more-btn .las {
            display: none;
        }

        #load-more-btn .btn-text {
            display: inline;
        }

        #load-more-container {
            margin-top: 20px;
        }

        .size-50px {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }

        .stock-badge {
            font-size: 11px;
            padding: 2px 8px;
        }
    </style>
@endsection
