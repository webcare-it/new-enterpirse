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
                <a href="{{ route('orders.download.invoice', $order->id) }}" title="Invoice Download"
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
                                        </td>
                                        <td>
                                            <strong>{{ $detail->product->name ?? 'Product Not Found' }}</strong>
                                            @php
                                                $variationData = json_decode($detail->variation ?? '', true);
                                                $displayValue = '';
                                                if (is_array($variationData)) {
                                                    if (isset($variationData['attribute_value'])) {
                                                        $attr = json_decode($variationData['attribute_value'], true);
                                                        $displayValue = is_array($attr)
                                                            ? implode(' - ', array_values($attr))
                                                            : $variationData['attribute_value'];
                                                    }
                                                }
                                            @endphp
                                            @if ($displayValue)
                                                <br>
                                                <small class="text-muted"> {{ $displayValue }}</small>
                                            @endif
                                            @if ($detail->product && $detail->product->inventory)
                                                <br>
                                                <small class="text-muted">{{ translate('SKU') }}:
                                                    {{ $detail->sku ?? 'N/A' }}</small>
                                            @endif
                                        </td>
                                        <td>৳{{ number_format($detail->price, 2) }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td>৳{{ number_format($detail->tax, 2) }}</td>
                                        <td class="font-weight-bold">
                                            ৳{{ number_format($detail->price * $detail->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-right font-weight-bold">{{ translate('Subtotal') }}:
                                    </td>
                                    <td class="font-weight-bold">
                                        ৳{{ number_format($subtotal ??$order->orderDetails->sum(function ($d) {return $d->price * $d->quantity;}),2) }}
                                    </td>
                                </tr>
                                @if (($totalTax ?? $order->orderDetails->sum('tax')) > 0)
                                    <tr>
                                        <td colspan="5" class="text-right">{{ translate('Tax') }}:</td>
                                        <td>৳{{ number_format($totalTax ?? $order->orderDetails->sum('tax'), 2) }}</td>
                                    </tr>
                                @endif
                                @if (($totalShipping ?? $order->orderDetails->sum('shipping_cost')) > 0)
                                    <tr>
                                        <td colspan="5" class="text-right">{{ translate('Shipping Cost') }}:</td>
                                        <td>৳{{ number_format($totalShipping ?? $order->orderDetails->sum('shipping_cost'), 2) }}
                                        </td>
                                    </tr>
                                @endif
                                @if ($order->coupon_discount > 0)
                                    <tr>
                                        <td colspan="5" class="text-right">{{ translate('Coupon Discount') }}:</td>
                                        <td class="text-danger">- ৳{{ number_format($order->coupon_discount, 2) }}</td>
                                    </tr>
                                @endif
                                @if ($order->discount > 0)
                                    <tr>
                                        <td colspan="5" class="text-right">{{ translate('Additional Discount') }}:</td>
                                        <td class="text-danger">- ৳{{ number_format($order->discount, 2) }}</td>
                                    </tr>
                                @endif
                                <tr class="bg-light">
                                    <td colspan="5" class="text-right font-weight-bold h5">
                                        {{ translate('Grand Total') }}:</td>
                                    <td class="font-weight-bold h5 text-primary">
                                        ৳{{ number_format($order->grand_total, 2) }}</td>
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
                            {{ in_array($order->delivery_status, ['delivered', 'transfer']) ? 'disabled' : '' }}>
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
                            <option value="transfer" {{ $order->delivery_status == 'transfer' ? 'selected' : '' }}>
                                {{ translate('Transfer') }}</option>
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

            <!-- Courier Information Card (MODIFIED) -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Courier Information') }}</h5>
                    @if ($order->courier_name != null)
                        <button type="button" class="btn btn-sm btn-light ml-auto" data-toggle="modal"
                            data-target="#resetCourierModal" title="{{ translate('Assign another courier') }}">
                            <i class="las la-cog"></i>
                        </button>
                    @endif

                </div>
                <form action="{{ route('orders.send_to_steadfast', $order->id) }}" method="POST" id="courierForm">
                    @csrf
                    @method('POST')
                    <div class="card-body">
                        <div class="form-group">
                            <label>{{ translate('Courier Service') }}</label>
                            <select name="courier_name" class="form-control" id="courier_name"
                                {{ $order->courier_name != '' ? 'disabled' : '' }}>
                                <option value="">{{ translate('Select Courier') }}</option>
                                <option value="pathao" {{ $order->courier_name == 'pathao' ? 'selected' : '' }}>
                                    {{ translate('Pathao') }}</option>
                                <option value="steadfast" {{ $order->courier_name == 'steadfast' ? 'selected' : '' }}>
                                    {{ translate('Steadfast') }}</option>
                            </select>
                        </div>

                        @if ($order->courier_name != null)
                            <div class="form-group">
                                <div class="d-flex align-items-center">
                                    <label class="mb-0 mr-2">{{ translate('Tracking Code :') }}</label>
                                    <span id="courierTrackingCode">{{ $order->courier_tracking_code ?? 'N/A' }}</span>
                                    @if ($order->courier_tracking_code)
                                        <button type="button" class="btn btn-sm btn-light ml-2 p-1 copy-courier-value"
                                            data-copy-target="#courierTrackingCode"
                                            title="{{ translate('Copy tracking code') }}">
                                            <i class="las la-copy"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="d-flex align-items-center">
                                    <label class="mb-0 mr-2">{{ translate('Consignment ID :') }}</label>
                                    <span id="consignmentId">{{ $order->consignment_id ?? 'N/A' }}</span>
                                    @if ($order->consignment_id)
                                        <button type="button" class="btn btn-sm btn-light ml-2 p-1 copy-courier-value"
                                            data-copy-target="#consignmentId"
                                            title="{{ translate('Copy consignment ID') }}">
                                            <i class="las la-copy"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif


                        @if ($order->courier_name == null)
                            <button type="button" class="btn btn-primary" id="saveCourierBtn" disabled>
                                {{ translate('Save Courier') }}
                            </button>
                        @endif
                    </div>
                </form>
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
                                <td>{{ $order->email_address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ translate('Phone') }}</th>
                                <td>{{ $order->phone_number ?? 'N/A' }}</td>
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
                            <td>{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
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
                                    <span
                                        class="badge badge-warning no-print">{{ ucfirst($order->payment_status) }}</span>
                                    <span class="print-only">{{ ucfirst($order->payment_status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @if ($order->manual_payment)
                            <tr>
                                <th>{{ translate('Manual Payment') }}</th>
                                <td><span class="badge badge-info">{{ translate('Yes') }}</span></td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== MODAL: Courier Confirmation ========== -->
    <div class="modal fade" id="courierConfirmModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Confirm Courier Update') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ translate('Please review the shipment details before sending.') }}</p>
                    <div class="table-responsive" id="courierPreview" style="display: none;">
                        <table class="table table-sm table-bordered mb-0" id="courierPreviewTable">
                            <tbody></tbody>
                        </table>
                    </div>
                    <div id="pathaoOptions" style="display: none;">
                        <div class="form-group">
                            <label>{{ translate('Pathao Store') }}</label>
                            <select name="store_id" id="pathao_store_id" class="form-control" form="courierForm"
                                required>
                                <option value="">{{ translate('Loading stores...') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Delivery Type') }}</label>
                            <select name="delivery_type" class="form-control" form="courierForm" required>
                                <option value="48">{{ translate('Normal Delivery') }}</option>
                                <option value="12">{{ translate('On Demand Delivery') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Item Type') }}</label>
                            <select name="item_type" class="form-control" form="courierForm" required>
                                <option value="2">{{ translate('Parcel') }}</option>
                                <option value="1">{{ translate('Document') }}</option>
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label>{{ translate('Item Weight (kg)') }}</label>
                            <input type="number" name="item_weight" class="form-control" form="courierForm"
                                value="0.5" min="0.5" max="10" step="0.1" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ translate('Cancel') }}
                    </button>
                    <button type="button" class="btn btn-secondary" id="pathaoEditBtn" style="display: none;">
                        {{ translate('Edit Selection') }}
                    </button>
                    <button type="button" class="btn btn-primary" id="pathaoDoneBtn" style="display: none;">
                        {{ translate('Done') }}
                    </button>
                    <button type="button" class="btn btn-primary" id="confirmCourierBtn" style="display: none;">
                        {{ translate('Yes, Send') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if ($order->courier_name != null)
        <div class="modal fade" id="resetCourierModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ translate('Assign Another Courier') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ translate('Are you sure you want to send this order with another courier? The current courier information will be cleared.') }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{ translate('Cancel') }}
                        </button>
                        <form method="POST" action="{{ route('orders.reset_courier', $order->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                {{ translate('Yes, Assign Another') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
                    <p>{{ translate('This will send the order to the external system and lock the delivery status.') }}</p>
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
    @php
        $courierPreview = [
            'invoice' => $order->code,
            'recipient_name' => substr($order->name ?? '', 0, 100),
            'recipient_phone' => substr(preg_replace('/[^0-9]/', '', $order->phone_number ?? ''), -11),
            'recipient_address' => substr($order->shipping_address ?? '', 0, 100),
            'cod_amount' =>
                $order->payment_status == 'paid'
                    ? 0
                    : $order->orderDetails->sum(function ($detail) {
                            return $detail->price * $detail->quantity;
                        }) + $order->shipping_cost,
            'note' => $order->notes ?? '',
            'item_description' => substr(
                $order->orderDetails
                    ->map(function ($detail) {
                        return ($detail->product->name ?? '') . ' x ' . $detail->quantity;
                    })
                    ->implode(', '),
                0,
                250,
            ),
            'item_quantity' => (int) $order->orderDetails->sum('quantity'),
        ];
    @endphp
    <script type="text/javascript">
        // Update delivery status
        $('#delivery_status').on('change', function() {
            var $select = $(this);
            var orderId = $select.data('order-id');
            var status = $select.val();
            var currentStatus = $select.find('option[selected]').val();

            if (status === 'transfer') {
                $('#transfer-order-id').val(orderId);
                $('#transfer-modal').modal('show');
                $select.val(currentStatus);
                return;
            }

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

        // Transfer order
        $('#confirm-transfer-btn').on('click', function() {
            var orderId = $('#transfer-order-id').val();
            var $btn = $(this);
            $btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i> {{ translate("Transferring...") }}');

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
                    $btn.prop('disabled', false).html('<i class="las la-paper-plane"></i> {{ translate("Transfer") }}');
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
                    $btn.prop('disabled', false).html('<i class="las la-paper-plane"></i> {{ translate("Transfer") }}');
                    $('#transfer-modal').modal('hide');
                    var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Something went wrong';
                    AIZ.plugins.notify('danger', msg);
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

        $(document).ready(function() {
            var pathaoStoresUrl = "{{ route('orders.pathao.stores') }}";
            var pathaoSubmitUrl = "{{ route('orders.send_to_pathao', $order->id) }}";
            var steadfastSubmitUrl = "{{ route('orders.send_to_steadfast', $order->id) }}";
            var orderPreview = @json($courierPreview);

            function escapePreviewValue(value) {
                return $('<div>').text(value === null || value === undefined || value === '' ? '-' : value).html();
            }

            function renderCourierPreview(rows) {
                var html = '';
                $.each(rows, function(index, row) {
                    html += '<tr><th style="width: 42%;">' + escapePreviewValue(row[0]) + '</th><td>' +
                        escapePreviewValue(row[1]) + '</td></tr>';
                });
                $('#courierPreviewTable tbody').html(html);
            }

            function renderSteadfastPreview() {
                renderCourierPreview([
                    ['Courier', 'Steadfast'],
                    ['Invoice', orderPreview.invoice],
                    ['Recipient Name', orderPreview.recipient_name],
                    ['Recipient Phone', orderPreview.recipient_phone],
                    ['Recipient Address', orderPreview.recipient_address],
                    ['COD Amount', orderPreview.cod_amount],
                    ['Note', orderPreview.note],
                    ['Item Description', orderPreview.item_description]
                ]);
            }

            function renderPathaoPreview() {
                var selectedStore = $('#pathao_store_id option:selected').text();
                renderCourierPreview([
                    ['Courier', 'Pathao'],
                    ['Store', selectedStore],
                    ['Merchant Order ID', orderPreview.invoice],
                    ['Recipient Name', orderPreview.recipient_name],
                    ['Recipient Phone', orderPreview.recipient_phone],
                    ['Recipient Address', orderPreview.recipient_address],
                    ['Delivery Type', $('select[name="delivery_type"] option:selected').text()],
                    ['Item Type', $('select[name="item_type"] option:selected').text()],
                    ['Item Quantity', orderPreview.item_quantity],
                    ['Item Weight (kg)', $('input[name="item_weight"]').val()],
                    ['Item Description', orderPreview.item_description],
                    ['Special Instruction', orderPreview.note],
                    ['Amount To Collect', orderPreview.cod_amount]
                ]);
            }

            function showPathaoSelection() {
                $('#pathaoOptions').show();
                $('#courierPreview').hide();
                $('#pathaoDoneBtn').show();
                $('#pathaoEditBtn').hide();
                $('#confirmCourierBtn').hide();
            }

            function showPathaoPreview() {
                renderPathaoPreview();
                $('#pathaoOptions').hide();
                $('#courierPreview').show();
                $('#pathaoDoneBtn').hide();
                $('#pathaoEditBtn').show();
                $('#confirmCourierBtn').show().prop('disabled', false);
            }

            $('#courier_name').on('change', function() {
                $('#saveCourierBtn').prop('disabled', !$(this).val());
            });

            $('#saveCourierBtn').on('click', function() {
                var isPathao = $('#courier_name').val() === 'pathao';
                $('#courierForm').attr('action', isPathao ? pathaoSubmitUrl : steadfastSubmitUrl);
                $('#pathaoOptions').toggle(isPathao);
                $('#pathaoOptions :input').prop('disabled', !isPathao);
                $('#courierPreview').toggle(!isPathao);
                $('#pathaoDoneBtn').toggle(isPathao);
                $('#pathaoEditBtn').hide();
                $('#confirmCourierBtn').toggle(!isPathao).prop('disabled', false);
                if (!isPathao) {
                    renderSteadfastPreview();
                }
                $('#courierConfirmModal').modal('show');

                if (isPathao) {
                    $('#pathao_store_id').html(
                        '<option value="">{{ translate('Loading stores...') }}</option>');
                    $.get(pathaoStoresUrl)
                        .done(function(response) {
                            var options =
                                '<option value="">{{ translate('Select a store') }}</option>';
                            if (response.success && response.stores.length) {
                                $.each(response.stores, function(index, store) {
                                    options += '<option value="' + store.store_id + '">' +
                                        $('<div>').text(store.store_name).html() + '</option>';
                                });
                                $('#pathao_store_id').html(options).prop('disabled', false);
                            } else {
                                $('#pathao_store_id').html(
                                    '<option value="">{{ translate('No stores found') }}</option>'
                                );
                            }
                        })
                        .fail(function(xhr) {
                            $('#pathao_store_id').html(
                                '<option value="">{{ translate('Unable to load stores') }}</option>'
                            );
                            AIZ.plugins.notify('danger', xhr.responseJSON && xhr.responseJSON.message ?
                                xhr.responseJSON.message :
                                '{{ translate('Unable to load Pathao stores.') }}');
                        });
                }
            });

            $('#pathaoDoneBtn').on('click', function() {
                var valid = true;
                $('#pathaoOptions :input[required]').each(function() {
                    if (!this.checkValidity()) {
                        this.reportValidity();
                        valid = false;
                        return false;
                    }
                });

                if (valid) {
                    showPathaoPreview();
                }
            });

            $('#pathaoEditBtn').on('click', function() {
                showPathaoSelection();
            });

            $('#confirmCourierBtn').on('click', function() {
                $('#courierForm').submit();
            });

            $('.copy-courier-value').on('click', function() {
                var value = $($(this).data('copy-target')).text().trim();
                var button = $(this);

                function notifyCopied() {
                    AIZ.plugins.notify('success', '{{ translate('Copied successfully.') }}');
                    button.find('i').removeClass('la-copy').addClass('la-check');
                    setTimeout(function() {
                        button.find('i').removeClass('la-check').addClass('la-copy');
                    }, 1200);
                }

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(value).then(notifyCopied);
                    return;
                }

                var temporaryInput = $('<textarea>').val(value).appendTo('body').select();
                document.execCommand('copy');
                temporaryInput.remove();
                notifyCopied();
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
