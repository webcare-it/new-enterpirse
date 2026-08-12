@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Incomplete Order Details') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('incomplete-orders.index') }}" class="btn btn-secondary">
                    <i class="las la-arrow-left"></i> {{ translate('Back to List') }}
                </a>
                @if ($incompleteOrder->status != 'completed' && $incompleteOrder->status != 'cancelled')
                    <button class="btn btn-success" id="convert-order" data-id="{{ $incompleteOrder->id }}">
                        <i class="las la-check-circle"></i> {{ translate('Convert to Order') }}
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Order Items -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Cart Items') }}</h5>
                    <span class="badge badge-primary">
                        {{ is_array($incompleteOrder->cart_data) ? count($incompleteOrder->cart_data) : 0 }}
                        {{ translate('items') }}
                    </span>
                </div>
                <div class="card-body">
                    @php
                        $cartData = $incompleteOrder->cart_data;
                        if (is_string($cartData)) {
                            $cartData = json_decode($cartData, true);
                        }
                    @endphp

                    @if ($cartData && count($cartData) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="35%">{{ translate('Product') }}</th>
                                        <th width="15%">{{ translate('Price') }}</th>
                                        <th width="10%">{{ translate('Qty') }}</th>
                                        <th width="20%">{{ translate('Total') }}</th>
                                        <th width="20%">{{ translate('Stock') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartData as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if (isset($item['thumbnail']) && $item['thumbnail'])
                                                        <img src="{{ uploaded_asset($item['thumbnail']) }}"
                                                            class="size-50px mr-2"
                                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                                    @else
                                                        <div
                                                            class="size-50px mr-2 bg-light d-flex align-items-center justify-content-center rounded">
                                                            <i class="las la-image fs-20 text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $item['name'] ?? 'Product' }}</strong>
                                                        @if (isset($item['variation']) && $item['variation'])
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="las la-tag"></i> {{ translate('Variant') }}:
                                                                {{ $item['variation'] }}
                                                            </small>
                                                        @endif
                                                        @if (isset($item['sku']) && $item['sku'])
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="las la-barcode"></i> {{ translate('SKU') }}:
                                                                {{ $item['sku'] }}
                                                            </small>
                                                        @endif
                                                        @if (isset($item['attributes']) && is_array($item['attributes']))
                                                            <br>
                                                            <small class="text-muted">
                                                                @foreach ($item['attributes'] as $key => $value)
                                                                    <span class="badge badge-light mr-1">
                                                                        {{ $key }}: {{ $value }}
                                                                    </span>
                                                                @endforeach
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>৳{{ number_format($item['price'] ?? 0, 2) }}</strong>
                                                @if (isset($item['regular_price']) && $item['regular_price'] > $item['price'])
                                                    <br>
                                                    <del
                                                        class="text-muted">৳{{ number_format($item['regular_price'], 2) }}</del>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ $item['quantity'] ?? 1 }}</span>
                                            </td>
                                            <td>
                                                <strong class="text-primary">
                                                    ৳{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}
                                                </strong>
                                            </td>
                                            <td>
                                                @if (isset($item['stock']) && $item['stock'] > 0)
                                                    <span class="badge badge-success">
                                                        {{ $item['stock'] }} {{ translate('in stock') }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger">
                                                        {{ translate('Out of stock') }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold">
                                            {{ translate('Subtotal') }}:
                                        </td>
                                        <td colspan="2" class="font-weight-bold">
                                            ৳{{ number_format($incompleteOrder->subtotal ?? 0, 2) }}
                                        </td>
                                    </tr>
                                    @if (($incompleteOrder->discount ?? 0) > 0)
                                        <tr>
                                            <td colspan="3" class="text-right text-success">
                                                {{ translate('Discount') }}:
                                            </td>
                                            <td colspan="2" class="text-success">
                                                - ৳{{ number_format($incompleteOrder->discount, 2) }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (($incompleteOrder->tax ?? 0) > 0)
                                        <tr>
                                            <td colspan="3" class="text-right">
                                                {{ translate('Tax') }}:
                                            </td>
                                            <td colspan="2">
                                                ৳{{ number_format($incompleteOrder->tax, 2) }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (($incompleteOrder->shipping_cost ?? 0) > 0)
                                        <tr>
                                            <td colspan="3" class="text-right">
                                                {{ translate('Shipping') }}:
                                            </td>
                                            <td colspan="2">
                                                ৳{{ number_format($incompleteOrder->shipping_cost, 2) }}
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold h5">
                                            {{ translate('Total') }}:
                                        </td>
                                        <td colspan="2" class="font-weight-bold text-primary h5">
                                            ৳{{ number_format($incompleteOrder->total ?? 0, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="las la-shopping-cart fs-40 text-muted"></i>
                            <p class="text-muted">{{ translate('No items in this cart') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Order Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>{{ translate('Order Code') }}</label>
                        <input type="text" class="form-control" value="{{ $incompleteOrder->order_code }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Status') }}</label>
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'abandoned' => 'danger',
                                'processing' => 'info',
                                'completed' => 'success',
                                'cancelled' => 'secondary',
                            ];
                            $statusText = ucfirst($incompleteOrder->status);
                        @endphp
                        <span class="badge badge-{{ $statusColors[$incompleteOrder->status] ?? 'secondary' }} form-control"
                            style="font-size: 14px; padding: 8px;">
                            {{ translate($statusText) }}
                        </span>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Created At') }}</label>
                        <input type="text" class="form-control"
                            value="{{ $incompleteOrder->created_at->format('d M Y, h:i A') }}" readonly>
                    </div>

                    @if ($incompleteOrder->abandoned_at)
                        <div class="form-group">
                            <label>{{ translate('Abandoned At') }}</label>
                            <input type="text" class="form-control"
                                value="{{ $incompleteOrder->abandoned_at->format('d M Y, h:i A') }}" readonly>
                        </div>
                    @endif

                    @if ($incompleteOrder->last_activity)
                        <div class="form-group">
                            <label>{{ translate('Last Activity') }}</label>
                            <input type="text" class="form-control"
                                value="{{ $incompleteOrder->last_activity->format('d M Y, h:i A') }}" readonly>
                        </div>
                    @endif

                    <div class="form-group">
                        <label>{{ translate('Reminder Count') }}</label>
                        <input type="text" class="form-control"
                            value="{{ $incompleteOrder->reminder_count }} {{ translate('times') }}" readonly>
                    </div>

                    @if ($incompleteOrder->last_reminder_sent_at)
                        <div class="form-group">
                            <label>{{ translate('Last Reminder Sent') }}</label>
                            <input type="text" class="form-control"
                                value="{{ $incompleteOrder->last_reminder_sent_at->format('d M Y, h:i A') }}" readonly>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Customer Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>{{ translate('Customer Name') }}</label>
                        <input type="text" class="form-control"
                            value="{{ $incompleteOrder->customer_name ?? 'N/A' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Email') }}</label>
                        <input type="email" class="form-control"
                            value="{{ $incompleteOrder->customer_email ?? 'N/A' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>{{ translate('Phone') }}</label>
                        <input type="text" class="form-control"
                            value="{{ $incompleteOrder->customer_phone ?? 'N/A' }}" readonly>
                    </div>

                    @if ($incompleteOrder->shipping_address)
                        <div class="form-group">
                            <label>{{ translate('Shipping Address') }}</label>
                            <textarea class="form-control" rows="2" readonly>{{ $incompleteOrder->shipping_address }}</textarea>
                        </div>
                    @endif

                    @if ($incompleteOrder->billing_address)
                        <div class="form-group">
                            <label>{{ translate('Billing Address') }}</label>
                            <textarea class="form-control" rows="2" readonly>{{ $incompleteOrder->billing_address }}</textarea>
                        </div>
                    @endif

                    @if ($incompleteOrder->notes)
                        <div class="form-group">
                            <label>{{ translate('Notes') }}</label>
                            <textarea class="form-control" rows="2" readonly>{{ $incompleteOrder->notes }}</textarea>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card mt-3">
                <div class="card-body">
                    @if ($incompleteOrder->status != 'completed' && $incompleteOrder->status != 'cancelled')
                        <button class="btn btn-success btn-block" id="convert-order"
                            data-id="{{ $incompleteOrder->id }}">
                            <i class="las la-check-circle"></i> {{ translate('Convert to Order') }}
                        </button>

                        @if ($incompleteOrder->status == 'abandoned')
                            <button class="btn btn-warning btn-block mt-2" id="send-reminder"
                                data-id="{{ $incompleteOrder->id }}">
                                <i class="las la-bell"></i> {{ translate('Send Reminder') }}
                            </button>
                        @endif

                        <button class="btn btn-danger btn-block mt-2" id="delete-order"
                            data-id="{{ $incompleteOrder->id }}">
                            <i class="las la-trash"></i> {{ translate('Delete Order') }}
                        </button>
                    @endif

                    @if ($incompleteOrder->order_id)
                        <a href="{{ route('orders.show', $incompleteOrder->order_id) }}" class="btn btn-info btn-block">
                            <i class="las la-eye"></i> {{ translate('View Full Order') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            // ===== CONVERT TO ORDER =====
            $(document).on('click', '#convert-order', function() {
                var id = $(this).data('id');

                if (!confirm('{{ translate('Convert this incomplete order to a full order?') }}')) {
                    return;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('incomplete-orders.convert', $incompleteOrder->id) }}",
                    beforeSend: function() {
                        $('#convert-order').prop('disabled', true).html(
                            '<i class="las la-spinner la-spin"></i> {{ translate('Converting...') }}'
                        );
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
                            $('#convert-order').prop('disabled', false).html(
                                '<i class="las la-check-circle"></i> {{ translate('Convert to Order') }}'
                            );
                        }
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        AIZ.plugins.notify('danger', response?.message ||
                            'Something went wrong');
                        $('#convert-order').prop('disabled', false).html(
                            '<i class="las la-check-circle"></i> {{ translate('Convert to Order') }}'
                        );
                    }
                });
            });

            // ===== SEND REMINDER =====
            $(document).on('click', '#send-reminder', function() {
                var id = $(this).data('id');

                if (!confirm('{{ translate('Send reminder to this customer?') }}')) {
                    return;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('incomplete-orders.send-reminder', $incompleteOrder->id) }}",
                    beforeSend: function() {
                        $('#send-reminder').prop('disabled', true).html(
                            '<i class="las la-spinner la-spin"></i> {{ translate('Sending...') }}'
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
                            $('#send-reminder').prop('disabled', false).html(
                                '<i class="las la-bell"></i> {{ translate('Send Reminder') }}'
                            );
                        }
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        AIZ.plugins.notify('danger', response?.message ||
                            'Something went wrong');
                        $('#send-reminder').prop('disabled', false).html(
                            '<i class="las la-bell"></i> {{ translate('Send Reminder') }}'
                        );
                    }
                });
            });

            // ===== DELETE ORDER =====
            $(document).on('click', '#delete-order', function() {
                var id = $(this).data('id');

                if (!confirm('{{ translate('Are you sure you want to delete this order?') }}')) {
                    return;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "DELETE",
                    url: "{{ route('incomplete-orders.destroy', $incompleteOrder->id) }}",
                    beforeSend: function() {
                        $('#delete-order').prop('disabled', true).html(
                            '<i class="las la-spinner la-spin"></i> {{ translate('Deleting...') }}'
                        );
                    },
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            setTimeout(function() {
                                window.location.href =
                                    "{{ route('incomplete-orders.index') }}";
                            }, 1500);
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                            $('#delete-order').prop('disabled', false).html(
                                '<i class="las la-trash"></i> {{ translate('Delete Order') }}'
                            );
                        }
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        AIZ.plugins.notify('danger', response?.message ||
                            'Something went wrong');
                        $('#delete-order').prop('disabled', false).html(
                            '<i class="las la-trash"></i> {{ translate('Delete Order') }}'
                        );
                    }
                });
            });
        });
    </script>
@endsection

@section('styles')
    <style>
        .size-50px {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }

        .fs-20 {
            font-size: 20px;
        }

        .fs-40 {
            font-size: 40px;
        }

        .badge {
            font-size: 11px;
            padding: 4px 10px;
        }

        .table td {
            vertical-align: middle;
        }

        .form-control[readonly] {
            background-color: #f8f9fa;
        }
    </style>
@endsection
