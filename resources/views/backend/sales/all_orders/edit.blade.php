@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Edit Order') }} #{{ $order->code ?? $order->id }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    <i class="las la-arrow-left"></i> {{ translate('Back to Orders') }}
                </a>
                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info">
                    <i class="las la-eye"></i> {{ translate('View Order') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Order Items Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Order Items') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="35%">{{ translate('Product') }}</th>
                                    <th width="15%">{{ translate('Price') }}</th>
                                    <th width="10%">{{ translate('Qty') }}</th>
                                    <th width="15%">{{ translate('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody id="order-items">
                                @if ($order->orderDetails->count() > 0)
                                    @foreach ($order->orderDetails as $index => $detail)
                                        <tr data-id="{{ $detail->id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($detail->product && $detail->product->thumbnail)
                                                        <img src="{{ uploaded_asset($detail->product->thumbnail) }}"
                                                            class="size-50px mr-2"
                                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                                    @else
                                                        <div
                                                            class="size-50px mr-2 bg-light d-flex align-items-center justify-content-center rounded">
                                                            <i class="las la-image fs-20 text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $detail->product->name ?? 'Product Not Found' }}</strong>
                                                        @php
                                                            $variationData = json_decode(
                                                                $detail->variation ?? '',
                                                                true,
                                                            );
                                                            $displayValue = '';
                                                            if (is_array($variationData)) {
                                                                if (isset($variationData['attribute_value'])) {
                                                                    $attr = json_decode(
                                                                        $variationData['attribute_value'],
                                                                        true,
                                                                    );
                                                                    $displayValue = is_array($attr)
                                                                        ? implode(' - ', array_values($attr))
                                                                        : $variationData['attribute_value'];
                                                                }
                                                            }
                                                        @endphp
                                                        @if ($displayValue)
                                                            <br>
                                                            <small class="text-muted">{{ $displayValue }}</small>
                                                        @endif
                                                        @if ($detail->product && $detail->product->inventory)
                                                            <br>
                                                            <small class="text-muted">{{ translate('SKU') }}:
                                                                {{ $detail->product->inventory->sku ?? 'N/A' }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm item-price"
                                                    readonly value="{{ $detail->price }}" step="0.01" min="0">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    {{ $detail->quantity }}
                                                </div>
                                            </td>
                                            <td class="item-total">
                                                ৳{{ number_format($detail->price * $detail->quantity, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="las la-shopping-cart fs-40 text-muted"></i>
                                            <p class="text-muted mb-0">{{ translate('No items in this order') }}</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right font-weight-bold">{{ translate('Subtotal') }}:
                                    </td>
                                    <td colspan="2" class="font-weight-bold" id="subtotal-display">
                                        ৳{{ number_format($subtotal ?? 0, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right">{{ translate('Shipping') }}:</td>
                                    <td colspan="2" id="shipping-display">
                                        ৳{{ number_format($order->shipping_cost ?? 0, 2) }}</td>
                                </tr>
                                @if (($order->coupon_discount ?? 0) > 0)
                                    <tr>
                                        <td colspan="4" class="text-right">{{ translate('Coupon Discount') }}:</td>
                                        <td colspan="2" class="text-danger">
                                            -৳{{ number_format($order->coupon_discount ?? 0, 2) }}</td>
                                    </tr>
                                @endif
                                @if (($order->discount ?? 0) > 0)
                                    <tr>
                                        <td colspan="4" class="text-right">{{ translate('Discount') }}:</td>
                                        <td colspan="2" class="text-danger">
                                            -৳{{ number_format($order->discount ?? 0, 2) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="4" class="text-right font-weight-bold h5">
                                        {{ translate('Grand Total') }}:</td>
                                    <td colspan="2" class="font-weight-bold text-primary h5" id="total-display">
                                        ৳{{ number_format($order->grand_total ?? 0, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Information Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Order Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>{{ translate('Order Code') }}</label>
                        <input type="text" class="form-control" value="{{ $order->code ?? 'N/A' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Delivery Status') }}</label>
                        <select class="form-control" id="order-status"
                            {{ in_array($order->delivery_status, ['delivered', 'transfer']) ? 'disabled' : '' }}>
                            @include('backend.sales.all_orders._status')
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Payment Status') }}</label>
                        <select class="form-control" id="payment-status"
                            {{ $order->payment_status == 'paid' ? 'disabled' : '' }}>
                            <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>
                                {{ translate('Unpaid') }}
                            </option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>
                                {{ translate('Paid') }}
                            </option>
                            <option value="partial" {{ $order->payment_status == 'partial' ? 'selected' : '' }}>
                                {{ translate('Partial') }}
                            </option>
                            <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>
                                {{ translate('Refunded') }}
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Payment Method') }}</label>
                        <input type="text" class="form-control" value="{{ $order->payment_type ?? 'N/A' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Order Date') }}</label>
                        <input type="text" class="form-control" value="{{ $order->created_at->format('d M Y, h:i A') }}"
                            readonly>
                    </div>
                </div>
            </div>

            <!-- Customer Information Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Customer Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>{{ translate('Customer Name') }}</label>
                        <input type="text" class="form-control" value="{{ $customer->name ?? 'Guest' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Email') }}</label>
                        <input type="email" class="form-control" value="{{ $customer->email ?? 'N/A' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Phone') }}</label>
                        <input type="text" class="form-control" value="{{ $customer->phone ?? 'N/A' }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Shipping Address Card - Editable -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Shipping Address') }}</h5>
                    <button class="btn btn-sm btn-primary" id="edit-address-btn">
                        <i class="las la-edit"></i> {{ translate('Edit') }}
                    </button>
                </div>
                <div class="card-body">
                    <div id="address-display">
                        <p class="mb-0">{{ $order->shipping_address ?? 'N/A' }}</p>
                    </div>

                    <div id="address-edit-form" style="display: none;">
                        <div class="form-group">
                            <label>{{ translate('Shipping Address') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="shipping-address" rows="3">{{ $order->shipping_address }}</textarea>
                        </div>
                        <button class="btn btn-sm btn-success" id="save-address">
                            <i class="las la-save"></i> {{ translate('Save Address') }}
                        </button>
                        <button class="btn btn-sm btn-secondary" id="cancel-address-edit">
                            <i class="las la-times"></i> {{ translate('Cancel') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Order Notes Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Order Notes') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <textarea class="form-control" id="order-notes" rows="3"
                            placeholder="{{ translate('Add notes about this order...') }}">{{ $order->notes ?? '' }}</textarea>
                    </div>
                    <button class="btn btn-sm btn-primary" id="update-notes">
                        <i class="las la-save"></i> {{ translate('Update Notes') }}
                    </button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card mt-3">
                <div class="card-body">
                    <button class="btn btn-success btn-block btn-lg" id="update-order">
                        <i class="las la-save"></i> {{ translate('Update Order') }}
                    </button>
                    <div class="row mt-2">
                        <div class="col-12">
                            <button class="btn btn-danger btn-block" id="delete-order"
                                {{ $order->delivery_status == 'delivered' ? 'disabled' : '' }}>
                                <i class="las la-trash"></i> {{ translate('Delete Order') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="transfer-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Transfer Order') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="las la-exchange-alt text-primary" style="font-size: 48px;"></i>
                    <h4 class="mt-2">{{ translate('Transfer this order?') }}</h4>
                    <p>{{ translate('This will send the order to the external system and lock the delivery status.') }}
                    </p>
                    <input type="hidden" id="transfer-order-id">
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="confirm-transfer-btn">
                        <i class="las la-paper-plane"></i> {{ translate('Transfer') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            var currentPage = 1;
            var searchQuery = '';
            var isLoading = false;
            var hasMore = true;
            var orderId = {{ $order->id }};

            // ============================================
            // ADD PRODUCT BUTTON - Scroll to product section
            // ============================================
            $('#add-product-btn').on('click', function() {
                $('html, body').animate({
                    scrollTop: $('#product-search-input').offset().top - 100
                }, 500);
                $('#product-search-input').focus();
            });

            // ============================================
            // SEARCH PRODUCTS - FIXED VERSION
            // ============================================
            var searchTimer = null;
            $('#product-search-input').on('keyup', function() {
                clearTimeout(searchTimer);
                var query = $(this).val().trim();

                searchTimer = setTimeout(function() {
                    console.log('Searching for:', query);
                    searchQuery = query;
                    currentPage = 1;
                    hasMore = true;
                    loadProducts(query, 1, true);
                }, 500);
            });

            // Enter key press
            $('#product-search-input').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    var query = $(this).val().trim();
                    searchQuery = query;
                    currentPage = 1;
                    hasMore = true;
                    loadProducts(query, 1, true);
                }
            });

            // Clear search
            $('#clear-search').on('click', function() {
                $('#product-search-input').val('');
                searchQuery = '';
                currentPage = 1;
                hasMore = true;
                loadProducts('', 1, true);
                $('#product-search-input').focus();
            });

            // ============================================
            // LOAD PRODUCTS FUNCTION - FIXED
            // ============================================
            function loadProducts(query, page, reset) {
                if (isLoading) return;

                // যদি query খালি থাকে বা ২ অক্ষরের কম হয়, তাহলে সব প্রোডাক্ট লোড করুন
                if (query.length > 0 && query.length < 2) {
                    return;
                }

                isLoading = true;
                $('#product-loading').show();

                if (reset) {
                    $('#product-list').html('');
                    $('#load-more-container').hide();
                    $('#no-more-products').hide();
                }

                // URL ঠিক করুন - আপনার Route অনুযায়ী
                var url = "{{ route('orders.search.product') }}";

                // যদি route কাজ না করে, তাহলে এই URL ব্যবহার করুন
                // var url = "/orders/search-products";
                // অথবা
                // var url = "/admin/orders/search-products";

                console.log('Loading from URL:', url);
                console.log('With data:', {
                    q: query,
                    page: page
                });

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        q: query,
                        page: page
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#product-loading').hide();
                        isLoading = false;

                        console.log('Response received:', response);

                        if (response.results && response.results.length > 0) {
                            // Build HTML for products
                            var html = '';
                            $.each(response.results, function(index, product) {
                                var stockBadge = (product.stock > 0) ? 'success' : 'danger';
                                var rowNumber = ((page - 1) * 20) + index + 1;
                                html += `
                                <tr class="product-row" data-id="${product.id}">
                                    <td>${rowNumber}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="size-50px mr-2 bg-light d-flex align-items-center justify-content-center rounded">
                                                <i class="las la-image fs-20 text-muted"></i>
                                            </div>
                                            <div>
                                                <strong>${product.name}</strong>
                                                <br>
                                                <small class="text-muted">SKU: ${product.sku || 'N/A'}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm product-price"
                                            value="${product.price}" step="0.01" min="0">
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-${stockBadge}">
                                            ${product.stock}
                                        </span>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm product-qty"
                                            value="1" min="1" style="width: 70px;">
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary add-product-to-order"
                                            data-id="${product.id}">
                                            <i class="las la-plus"></i> Add
                                        </button>
                                    </td>
                                </tr>
                            `;
                            });

                            if (reset) {
                                $('#product-list').html(html);
                            } else {
                                $('#product-list').append(html);
                            }

                            // Check for more pages
                            hasMore = response.pagination?.more || false;

                            if (hasMore) {
                                $('#load-more-container').show();
                                $('#no-more-products').hide();
                            } else {
                                $('#load-more-container').hide();
                                if (page > 1) {
                                    $('#no-more-products').show();
                                } else {
                                    $('#no-more-products').hide();
                                }
                            }
                        } else {
                            // No results
                            if (reset) {
                                var message = query ? 'No products found for "' + query + '"' :
                                    'No products available';
                                $('#product-list').html(`
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="las la-box fs-40 text-muted"></i>
                                        <p class="text-muted mb-0">${message}</p>
                                    </td>
                                </tr>
                            `);
                            }
                            $('#load-more-container').hide();
                            $('#no-more-products').hide();
                            hasMore = false;
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#product-loading').hide();
                        isLoading = false;

                        console.error('Product load error:', xhr);
                        console.error('Status:', status);
                        console.error('Error:', error);
                        console.error('Response:', xhr.responseText);

                        var errorMsg = 'Failed to load products';
                        if (xhr.status === 404) {
                            errorMsg = 'Search endpoint not found. Please check URL: ' + url;
                        } else if (xhr.status === 500) {
                            errorMsg = 'Server error. Please check logs.';
                        }

                        AIZ.plugins.notify('danger', errorMsg);

                        if (reset) {
                            $('#product-list').html(`
                            <tr>
                                <td colspan="6" class="text-center py-4 text-danger">
                                    <i class="las la-exclamation-circle fs-40"></i>
                                    <p class="mb-0">${errorMsg}</p>
                                    <small class="text-muted">Status: ${xhr.status}</small>
                                </td>
                            </tr>
                        `);
                        }
                    }
                });
            }

            // ============================================
            // LOAD MORE PRODUCTS
            // ============================================
            $('#load-more-products').on('click', function() {
                if (!hasMore || isLoading) return;
                currentPage++;
                loadProducts(searchQuery, currentPage, false);
            });

            // ============================================
            // ADD PRODUCT TO ORDER
            // ============================================
            $(document).on('click', '.add-product-to-order', function() {
                var button = $(this);
                var row = button.closest('tr');
                var productId = button.data('id');
                var price = parseFloat(row.find('.product-price').val()) || 0;
                var quantity = parseInt(row.find('.product-qty').val()) || 1;

                if (price <= 0) {
                    AIZ.plugins.notify('warning', 'Please enter a valid price');
                    return;
                }

                if (quantity < 1) {
                    AIZ.plugins.notify('warning', 'Please enter a valid quantity');
                    return;
                }

                button.prop('disabled', true).html('<i class="las la-spinner la-spin"></i>');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('orders.add-item', $order->id) }}",
                    data: {
                        product_id: productId,
                        variant_id: null,
                        price: price,
                        quantity: quantity
                    },
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                            button.prop('disabled', false).html(
                                '<i class="las la-plus"></i> Add');
                        }
                    },
                    error: function(xhr) {
                        var errorMsg = xhr.responseJSON?.message || 'Something went wrong';
                        AIZ.plugins.notify('danger', errorMsg);
                        button.prop('disabled', false).html('<i class="las la-plus"></i> Add');
                    }
                });
            });



            // ============================================
            // QUANTITY CONTROLS FOR ORDER ITEMS
            // ============================================
            $(document).on('click', '.qty-minus', function() {
                var input = $(this).siblings('.item-qty');
                var val = parseInt(input.val()) || 1;
                if (val > 1) {
                    input.val(val - 1).trigger('change');
                }
            });

            $(document).on('click', '.qty-plus', function() {
                var input = $(this).siblings('.item-qty');
                var val = parseInt(input.val()) || 1;
                input.val(val + 1).trigger('change');
            });

            // UPDATE ITEM QUANTITY
            $(document).on('change', '.item-qty', function() {
                var row = $(this).closest('tr');
                var id = row.data('id');
                var quantity = parseInt($(this).val()) || 1;
                var price = parseFloat(row.find('.item-price').val()) || 0;

                if (quantity < 1) {
                    $(this).val(1);
                    quantity = 1;
                }

                updateItem(row, id, price, quantity);
            });

            // UPDATE ITEM PRICE
            $(document).on('change', '.item-price', function() {
                var row = $(this).closest('tr');
                var id = row.data('id');
                var price = parseFloat($(this).val()) || 0;
                var quantity = parseInt(row.find('.item-qty').val()) || 1;

                if (price < 0) {
                    $(this).val(0);
                    price = 0;
                }

                updateItem(row, id, price, quantity);
            });

            // UPDATE ITEM FUNCTION
            function updateItem(row, id, price, quantity) {
                var total = price * quantity;
                row.find('.item-total').text('৳' + total.toFixed(2));

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('orders.update-item', $order->id) }}",
                    data: {
                        id: id,
                        price: price,
                        quantity: quantity
                    },
                    success: function(response) {
                        if (response.success) {
                            updateTotals(response);
                            AIZ.plugins.notify('success', response.message);
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                        }
                    },
                    error: function(xhr) {
                        var errorMsg = xhr.responseJSON?.message || 'Failed to update item';
                        AIZ.plugins.notify('danger', errorMsg);
                    }
                });
            }

            // REMOVE ITEM
            $(document).on('click', '.remove-item', function() {
                var row = $(this).closest('tr');
                var id = row.data('id');

                if (!confirm('{{ translate('Are you sure you want to remove this item?') }}')) {
                    return;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('orders.remove-item', $order->id) }}",
                    data: {
                        id: id
                    },
                    beforeSend: function() {
                        row.find('.remove-item').html(
                            '<i class="las la-spinner la-spin"></i>'
                        ).prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.success) {
                            row.remove();
                            updateTotals(response);
                            AIZ.plugins.notify('success', response.message);

                            if ($('#order-items tr').length === 0) {
                                $('#order-items').html(`
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="las la-shopping-cart fs-40 text-muted"></i>
                                        <p class="text-muted mb-0">{{ translate('No items in this order') }}</p>
                                    </td>
                                </tr>
                            `);
                            }
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                            row.find('.remove-item').html(
                                '<i class="las la-trash"></i>'
                            ).prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        var errorMsg = xhr.responseJSON?.message || 'Failed to remove item';
                        AIZ.plugins.notify('danger', errorMsg);
                        row.find('.remove-item').html(
                            '<i class="las la-trash"></i>'
                        ).prop('disabled', false);
                    }
                });
            });

            // UPDATE TOTALS
            function updateTotals(data) {
                var subtotal = 0;
                $('#order-items tr').each(function() {
                    var price = parseFloat($(this).find('.item-price').val()) || 0;
                    var qty = parseInt($(this).find('.item-qty').val()) || 0;
                    subtotal += price * qty;
                });

                $('#subtotal-display').text('৳' + subtotal.toFixed(2));

                var grandTotal = data.grand_total || subtotal;
                if (data.grand_total !== undefined) {
                    $('#total-display').text('৳' + parseFloat(data.grand_total).toFixed(2));
                } else {
                    $('#total-display').text('৳' + subtotal.toFixed(2));
                }
            }

            // UPDATE NOTES
            $('#update-notes').on('click', function() {
                var notes = $('#order-notes').val();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('orders.update-notes', $order->id) }}",
                    data: {
                        notes: notes
                    },
                    beforeSend: function() {
                        $('#update-notes').prop('disabled', true).html(
                            '<i class="las la-spinner la-spin"></i> {{ translate('Saving...') }}'
                        );
                    },
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                        }
                    },
                    error: function(xhr) {
                        var errorMsg = xhr.responseJSON?.message || 'Failed to update notes';
                        AIZ.plugins.notify('danger', errorMsg);
                    },
                    complete: function() {
                        $('#update-notes').prop('disabled', false).html(
                            '<i class="las la-save"></i> {{ translate('Update Notes') }}'
                        );
                    }
                });
            });

            // EDIT ADDRESS
            $('#edit-address-btn').on('click', function() {
                $('#address-display').hide();
                $('#address-edit-form').show();
                $(this).hide();
            });

            $('#cancel-address-edit').on('click', function() {
                $('#address-edit-form').hide();
                $('#address-display').show();
                $('#edit-address-btn').show();
            });

            // SAVE ADDRESS
            $('#save-address').on('click', function() {
                var shippingAddress = $('#shipping-address').val().trim();

                if (!shippingAddress) {
                    AIZ.plugins.notify('warning', '{{ translate('Shipping address is required') }}');
                    return;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('orders.update-address', $order->id) }}",
                    data: {
                        shipping_address: shippingAddress
                    },
                    beforeSend: function() {
                        $('#save-address').prop('disabled', true).html(
                            '<i class="las la-spinner la-spin"></i> {{ translate('Saving...') }}'
                        );
                    },
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            $('#address-display').html('<p class="mb-0">' + shippingAddress +
                                '</p>');
                            $('#address-edit-form').hide();
                            $('#address-display').show();
                            $('#edit-address-btn').show();
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                        }
                    },
                    error: function(xhr) {
                        var errorMsg = xhr.responseJSON?.message || 'Failed to update address';
                        AIZ.plugins.notify('danger', errorMsg);
                    },
                    complete: function() {
                        $('#save-address').prop('disabled', false).html(
                            '<i class="las la-save"></i> {{ translate('Save Address') }}'
                        );
                    }
                });
            });

            // UPDATE ORDER
            $('#update-order').on('click', function() {
                var status = $('#order-status').val();

                if (status === 'transfer') {
                    $('#transfer-order-id').val('{{ $order->id }}');
                    $('#transfer-modal').modal('show');
                    return;
                }

                var paymentStatus = $('#payment-status').val();
                var notes = $('#order-notes').val();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('orders.update-order', $order->id) }}",
                    data: {
                        status: status,
                        payment_status: paymentStatus,
                        notes: notes
                    },
                    beforeSend: function() {
                        $('#update-order').prop('disabled', true).html(
                            '<i class="las la-spinner la-spin"></i> {{ translate('Updating...') }}'
                        );
                    },
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                            $('#update-order').prop('disabled', false).html(
                                '<i class="las la-save"></i> {{ translate('Update Order') }}'
                            );
                        }
                    },
                    error: function(xhr) {
                        var errorMsg = xhr.responseJSON?.message || 'Failed to update order';
                        AIZ.plugins.notify('danger', errorMsg);
                        $('#update-order').prop('disabled', false).html(
                            '<i class="las la-save"></i> {{ translate('Update Order') }}'
                        );
                    }
                });
            });

            // TRANSFER ORDER
            $('#confirm-transfer-btn').on('click', function() {
                var orderId = $('#transfer-order-id').val();
                var $btn = $(this);
                $btn.prop('disabled', true).html(
                    '<i class="las la-spinner la-spin"></i> {{ translate('Transferring...') }}');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('orders.transfer-order') }}",
                    data: {
                        order_id: orderId
                    },
                    success: function(response) {
                        $btn.prop('disabled', false).html(
                            '<i class="las la-paper-plane"></i> {{ translate('Transfer') }}'
                        );
                        $('#transfer-modal').modal('hide');
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                        }
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).html(
                            '<i class="las la-paper-plane"></i> {{ translate('Transfer') }}'
                        );
                        $('#transfer-modal').modal('hide');
                        var msg = xhr.responseJSON ? xhr.responseJSON.message :
                            'Something went wrong';
                        AIZ.plugins.notify('danger', msg);
                    }
                });
            });

            // CANCEL ORDER
            $('#cancel-order').on('click', function() {
                if (!confirm('{{ translate('Are you sure you want to cancel this order?') }}')) {
                    return;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('orders.cancel', $order->id) }}",
                    beforeSend: function() {
                        $('#cancel-order').prop('disabled', true).html(
                            '<i class="las la-spinner la-spin"></i> {{ translate('Cancelling...') }}'
                        );
                    },
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                            $('#cancel-order').prop('disabled', false).html(
                                '<i class="las la-times"></i> {{ translate('Cancel Order') }}'
                            );
                        }
                    },
                    error: function(xhr) {
                        var errorMsg = xhr.responseJSON?.message || 'Failed to cancel order';
                        AIZ.plugins.notify('danger', errorMsg);
                        $('#cancel-order').prop('disabled', false).html(
                            '<i class="las la-times"></i> {{ translate('Cancel Order') }}'
                        );
                    }
                });
            });

            // PRINT ORDER
            $('#print-order').on('click', function() {
                window.open("{{ route('orders.print', $order->id) }}", '_blank');
            });

            // DELETE ORDER
            $('#delete-order').on('click', function() {
                if (!confirm(
                        '{{ translate('Are you sure you want to delete this order? This action cannot be undone!') }}'
                    )) {
                    return;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "DELETE",
                    url: "{{ route('orders.destroy', $order->id) }}",
                    beforeSend: function() {
                        $('#delete-order').prop('disabled', true).html(
                            '<i class="las la-spinner la-spin"></i> {{ translate('Deleting...') }}'
                        );
                    },
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            setTimeout(function() {
                                window.location.href = "{{ route('orders.index') }}";
                            }, 1500);
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                            $('#delete-order').prop('disabled', false).html(
                                '<i class="las la-trash"></i> {{ translate('Delete Order') }}'
                            );
                        }
                    },
                    error: function(xhr) {
                        var errorMsg = xhr.responseJSON?.message || 'Failed to delete order';
                        AIZ.plugins.notify('danger', errorMsg);
                        $('#delete-order').prop('disabled', false).html(
                            '<i class="las la-trash"></i> {{ translate('Delete Order') }}'
                        );
                    }
                });
            });
        });
    </script>

    <style>
        .size-50px {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }

        .fs-20 {
            font-size: 20px;
        }

        .fs-30 {
            font-size: 30px;
        }

        .fs-40 {
            font-size: 40px;
        }

        .item-qty {
            width: 60px;
            text-align: center;
        }

        .product-qty {
            width: 70px;
            text-align: center;
        }

        .qty-minus,
        .qty-plus {
            padding: 0 8px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #product-list tr:hover {
            background-color: #f8f9fa;
        }

        .product-row td {
            vertical-align: middle;
        }
    </style>
@endsection
