@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4 no-print">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Order Details') }}</h1>
                <p class="text-muted mb-0">{{ translate('Order ID') }}: #{{ $order->id }} | {{ translate('Order Code') }}:
                    {{ $order->code }}</p>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    <i class="las la-arrow-left"></i> {{ translate('Back to Orders') }}
                </a>
                <a href="{{ route('orders.download.invoice', $order->id) }}" title="Invoice Downlaod"
                    class="btn btn-success">
                    Download Invoice
                </a>
                <button onclick="window.print()" class="btn btn-info">
                    <i class="las la-print"></i> {{ translate('Print Invoice') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Print Header - Only visible when printing -->
    <div class="print-header print-only">
        <div class="text-center">
            <h2>{{ get_setting('site_name', 'ShopHub') }}</h2>
            <p>{{ get_setting('site_address', '') }}</p>
            <p>{{ translate('Phone') }}: {{ get_setting('site_phone', '') }} | {{ translate('Email') }}:
                {{ get_setting('site_email', '') }}</p>
            <hr>
            <h4>{{ translate('Order Invoice') }}</h4>
            <p>{{ translate('Order ID') }}: #{{ $order->id }} | {{ translate('Order Code') }}: {{ $order->code }}
            </p>
            <p>{{ translate('Order Date') }}: {{ $order->created_at->format('d M Y, h:i A') }}</p>
            <hr>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Order Items -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Order Items') }}</h5>
                    <span class="badge badge-primary ml-2 no-print">{{ $order->orderDetails->sum('quantity') }}
                        {{ translate('Items') }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="10%">{{ translate('Image') }}</th>
                                    <th width="35%">{{ translate('Product') }}</th>
                                    <th width="15%">{{ translate('Price') }}</th>
                                    <th width="10%">{{ translate('Quantity') }}</th>
                                    <th width="15%">{{ translate('Tax') }}</th>
                                    <th width="15%">{{ translate('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderDetails as $detail)
                                    <tr>
                                        <td class="text-center">
                                            @if ($detail->product && $detail->product->thumbnail)
                                                <img src="{{ uploaded_asset($detail->product->thumbnail) }}"
                                                    alt="{{ $detail->product->name }}" class="size-50px img-fit"
                                                    style="border-radius: 8px;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                    style="width: 50px; height: 50px; border-radius: 8px;">
                                                    <i class="las la-image text-muted"></i>
                                                </div>
                                            @endif
                    </div>
                    <td>
                        <strong>{{ $detail->product->name ?? 'Product Not Found' }}</strong>
                        @if ($detail->variation)
                            <br>
                            <small class="text-muted">{{ translate('Variation') }}:
                                {{ $detail->variation }}</small>
                        @endif
                        @if ($detail->product && $detail->product->inventory)
                            <br>
                            <small class="text-muted">{{ translate('SKU') }}:
                                {{ $detail->sku ?? 'N/A' }}</small>
                        @endif
                </div>
                <td>৳{{ number_format($detail->price, 2) }}
            </div>
            <td>{{ $detail->quantity }}
        </div>
        <td>৳{{ number_format($detail->tax, 2) }}
    </div>
    <td class="font-weight-bold">
        ৳{{ number_format($detail->price * $detail->quantity, 2) }}</div>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right font-weight-bold">{{ translate('Subtotal') }}:</div>
                <td class="font-weight-bold">
                    ৳{{ number_format($subtotal ??$order->orderDetails->sum(function ($d) {return $d->price * $d->quantity;}),2) }}
                    </div>
            </tr>
            @if (($totalTax ?? $order->orderDetails->sum('tax')) > 0)
                <tr>
                    <td colspan="5" class="text-right">{{ translate('Tax') }}:</div>
                    <td>৳{{ number_format($totalTax ?? $order->orderDetails->sum('tax'), 2) }}</div>
                </tr>
            @endif
            @if (($totalShipping ?? $order->orderDetails->sum('shipping_cost')) > 0)
                <tr>
                    <td colspan="5" class="text-right">{{ translate('Shipping Cost') }}:</div>
                    <td>৳{{ number_format($totalShipping ?? $order->orderDetails->sum('shipping_cost'), 2) }}
                        </div>
                </tr>
            @endif
            @if ($order->coupon_discount > 0)
                <tr>
                    <td colspan="5" class="text-right">{{ translate('Coupon Discount') }}:</div>
                    <td class="text-danger">- ৳{{ number_format($order->coupon_discount, 2) }}</div>
                </tr>
            @endif
            @if ($order->discount > 0)
                <tr>
                    <td colspan="5" class="text-right">{{ translate('Additional Discount') }}:</div>
                    <td class="text-danger">- ৳{{ number_format($order->discount, 2) }}</div>
                </tr>
            @endif
            <tr class="bg-light">
                <td colspan="5" class="text-right font-weight-bold h5">
                    {{ translate('Grand Total') }}:</div>
                <td class="font-weight-bold h5 text-primary">
                    ৳{{ number_format($order->grand_total, 2) }}</div>
            </tr>
        </tfoot>
        </table>
        </div>
        </div>
        </div>
        </div>

        <!-- Right Column - Order Info -->
        <div class="col-lg-4">
            <!-- Order Status Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Order Status') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group no-print">
                        <label>{{ translate('Delivery Status') }}</label>
                        <select class="form-control" id="delivery_status" data-order-id="{{ $order->id }}"
                            {{ $order->delivery_status == 'delivered' ? 'disabled' : '' }}>
                            <option value="pending" {{ $order->delivery_status == 'pending' ? 'selected' : '' }}>
                                {{ translate('Pending') }}</option>
                            <option value="confirmed" {{ $order->delivery_status == 'confirmed' ? 'selected' : '' }}>
                                {{ translate('Confirmed') }}</option>
                            <option value="processing" {{ $order->delivery_status == 'processing' ? 'selected' : '' }}>
                                {{ translate('Processing') }}</option>
                            <option value="shipped" {{ $order->delivery_status == 'shipped' ? 'selected' : '' }}>
                                {{ translate('Shipped') }}</option>
                            <option value="delivered" {{ $order->delivery_status == 'delivered' ? 'selected' : '' }}>
                                {{ translate('Delivered') }}</option>
                            <option value="cancelled" {{ $order->delivery_status == 'cancelled' ? 'selected' : '' }}>
                                {{ translate('Cancelled') }}</option>
                        </select>
                    </div>
                    <div class="print-only">
                        <p><strong>{{ translate('Delivery Status') }}:</strong> {{ ucfirst($order->delivery_status) }}</p>
                    </div>

                    <div class="form-group no-print">
                        <label>{{ translate('Payment Status') }}</label>
                        <select class="form-control" id="payment_status" data-order-id="{{ $order->id }}"
                            {{ $order->payment_status == 'paid' ? 'disabled' : '' }}>
                            <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>
                                {{ translate('Unpaid') }}</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>
                                {{ translate('Paid') }}</option>
                            <option value="partially_paid"
                                {{ $order->payment_status == 'partially_paid' ? 'selected' : '' }}>
                                {{ translate('Partially Paid') }}</option>
                            <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>
                                {{ translate('Refunded') }}</option>
                        </select>
                    </div>
                    <div class="print-only">
                        <p><strong>{{ translate('Payment Status') }}:</strong> {{ ucfirst($order->payment_status) }}</p>
                    </div>
                </div>
            </div>

            <!-- Customer Information Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Customer Information') }}</h5>
                </div>
                <div class="card-body">
                    @if ($customer)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-md mr-2 no-print">
                                @if ($customer->avatar)
                                    <img src="{{ uploaded_asset($customer->avatar) }}" class="rounded-circle">
                                @else
                                    <img src="{{ asset('default/avatar.jpg') }}" class="rounded-circle">
                                @endif
                            </div>
                            <div>
                                <div class="font-weight-bold">{{ $order->name }}</div>
                            </div>
                        </div>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="35%">{{ translate('Email') }}</th>
                                <td>{{ $order->email_address ?? 'N/A' }}
                </div>
                </tr>
                <tr>
                    <th>{{ translate('Phone') }}</th>
                    <td>{{ $order->phone_number ?? 'N/A' }}
            </div>
            </tr>
            </table>
        @else
            <div class="alert alert-warning mb-0">
                <i class="las la-user"></i> {{ translate('Guest Customer') }}
            </div>
            @endif
        </div>
        </div>

        <!-- Shipping Information Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Shipping Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <i class="las la-map-marker text-primary"></i>
                    <strong>{{ translate('Shipping Address') }}</strong>
                </div>
                <p class="mb-0">{{ $order->shipping_address ?? 'N/A' }}</p>

                @if ($order->tracking_code)
                    <div class="mt-3 pt-2 border-top">
                        <div class="mb-2">
                            <i class="las la-truck text-primary"></i>
                            <strong>{{ translate('Tracking Code') }}</strong>
                        </div>
                        <p class="mb-0">{{ $order->tracking_code }}</p>
                    </div>
                @endif

                @if ($order->notes)
                    <div class="mt-3 pt-2 border-top">
                        <div class="mb-2">
                            <i class="las la-sticky-note text-primary"></i>
                            <strong>{{ translate('Order Notes') }}</strong>
                        </div>
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Payment Information Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Payment Information') }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th width="40%">{{ translate('Payment Method') }}</th>
                        <td>{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}
            </div>
            </tr>
            <tr>
                <th>{{ translate('Payment Status') }}</th>
                <td>
                    @if ($order->payment_status == 'paid')
                        <span class="badge badge-success no-print">{{ translate('Paid') }}</span>
                        <span class="print-only">{{ translate('Paid') }}</span>
                    @elseif($order->payment_status == 'unpaid')
                        <span class="badge badge-danger no-print">{{ translate('Unpaid') }}</span>
                        <span class="print-only">{{ translate('Unpaid') }}</span>
                    @else
                        <span class="badge badge-warning no-print">{{ ucfirst($order->payment_status) }}</span>
                        <span class="print-only">{{ ucfirst($order->payment_status) }}</span>
                    @endif
        </div>
        </tr>
        @if ($order->manual_payment)
            <tr>
                <th>{{ translate('Manual Payment') }}</th>
                <td><span class="badge badge-info">{{ translate('Yes') }}</span></div>
            </tr>
        @endif
        </table>
        </div>
        </div>
        </div>
        </div>
    @endsection

    @section('script')
        <script type="text/javascript">
            // Update delivery status
            $('#delivery_status').on('change', function() {
                var orderId = $(this).data('order-id');
                var status = $(this).val();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('orders.update-delivery-status') }}",
                    data: {
                        order_id: orderId,
                        status: status
                    },
                    success: function(response) {
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
                        AIZ.plugins.notify('danger', 'Something went wrong');
                    }
                });
            });

            // Update payment status
            $('#payment_status').on('change', function() {
                var orderId = $(this).data('order-id');
                var status = $(this).val();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('orders.update-payment-status') }}",
                    data: {
                        order_id: orderId,
                        status: status
                    },
                    success: function(response) {
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
                        AIZ.plugins.notify('danger', 'Something went wrong');
                    }
                });
            });
        </script>

        <style>
            .size-50px {
                width: 50px;
                height: 50px;
                object-fit: cover;
            }

            .table-borderless th,
            .table-borderless td {
                padding: 5px 0;
            }

            /* Hide print-only elements by default */
            .print-only {
                display: none;
            }

            /* Print Styles - Hide everything except order content */
            @media print {

                /* Hide ALL header elements */
                .aiz-header,
                header,
                .header,
                .navbar,
                nav,
                .sidebar,
                .aiz-sidebar,
                .main-sidebar,
                .footer,
                footer,
                .copyright,
                .breadcrumb,
                .aiz-footer,
                .topbar,
                .page-title,
                .aiz-titlebar .btn,
                .card-header .btn,
                .btn-group,
                .no-print,
                .no-print *,
                .form-group,
                select,
                button,
                .btn,
                .modal,
                .dropdown,
                .action-buttons,
                .aiz-pagination,
                .pagination,
                [class*="sidebar"],
                [class*="header"],
                [class*="footer"],
                [class*="navbar"],
                [class*="breadcrumb"],
                .avatar,
                .avatar img,
                .badge.no-print {
                    display: none !important;
                }

                /* Show print-only elements */
                .print-only {
                    display: block !important;
                }

                /* Card styling for print */
                .card {
                    border: 1px solid #ddd !important;
                    box-shadow: none !important;
                    margin-bottom: 15px !important;
                    break-inside: avoid;
                    page-break-inside: avoid;
                }

                .card-header {
                    background-color: #f8f9fa !important;
                    border-bottom: 1px solid #ddd !important;
                }

                /* Table styling for print */
                .table {
                    width: 100% !important;
                    border-collapse: collapse !important;
                }

                .table th,
                .table td {
                    border: 1px solid #ddd !important;
                    padding: 8px !important;
                }

                /* Body and container styling */
                body {
                    padding: 0 !important;
                    margin: 0 !important;
                    background: white !important;
                }

                .container,
                .container-fluid {
                    width: 100% !important;
                    padding: 0 !important;
                    margin: 0 !important;
                    max-width: 100% !important;
                }

                .row {
                    margin: 0 !important;
                }

                .col-lg-8,
                .col-lg-4 {
                    width: 100% !important;
                    max-width: 100% !important;
                    flex: 0 0 100% !important;
                }

                /* Hide images in print (optional) */
                .product-image img,
                .size-50px {
                    max-width: 50px !important;
                }

                /* Text colors for print */
                .text-primary {
                    color: #000 !important;
                }

                .text-danger {
                    color: #000 !important;
                }

                .text-success {
                    color: #000 !important;
                }

                /* Print header styling */
                .print-header {
                    text-align: center;
                    margin-bottom: 20px;
                    padding-bottom: 10px;
                }

                .print-header h2 {
                    margin: 0;
                    font-size: 24px;
                }

                .print-header p {
                    margin: 5px 0;
                    font-size: 12px;
                }

                .print-header hr {
                    margin: 10px 0;
                }
            }
        </style>
    @endsection
