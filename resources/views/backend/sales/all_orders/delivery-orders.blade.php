@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Order Management') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('orders.export') }}" class="btn btn-success">
                    <i class="las la-file-excel"></i> {{ translate('Export') }}
                </a>
            </div>
        </div>
    </div>

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

        .stat-top {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 14px;
            font-weight: 700;
            opacity: .96;
            margin: 0;
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

        .revenue-toggle {
            width: 30px;
            height: 30px;
            border: 0;
            outline: none;
            border-radius: 50%;
            color: #fff;
            background: rgba(255, 255, 255, .20);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: .2s ease;
            padding: 0;
        }

        .revenue-toggle:hover {
            background: rgba(255, 255, 255, .32);
            color: #fff;
        }

        .revenue-toggle i {
            font-size: 18px;
            line-height: 1;
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

        .filter-actions {
            display: flex;
            gap: 10px;
        }

        .filter-actions .btn {
            min-width: 120px;
        }
    </style>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-blue">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Total Orders') }}</div>
                    <div class="stat-number">{{ $totalOrders }}</div>
                    <div class="stat-small">{{ translate('All order records') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-shopping-cart"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-green">
                <div class="mm-content">
                    <div class="stat-top">
                        <div class="stat-label">{{ translate('Total Revenue') }}</div>

                        <button type="button" onclick="toggleRevenue()" class="revenue-toggle">
                            <i id="revenue-eye" class="las la-eye-slash"></i>
                        </button>
                    </div>

                    <div class="stat-number" id="revenue-amount" data-hidden="true">
                        ৳ ******
                    </div>

                    <div class="stat-small">{{ translate('Revenue generated') }}</div>
                </div>

                <div class="mm-icon">
                    <i class="las la-chart-line"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-orange">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Pending Orders') }}</div>
                    <div class="stat-number">{{ $pendingOrders }}</div>
                    <div class="stat-small">{{ translate('Awaiting processing') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-clock"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-purple">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Delivered Orders') }}</div>
                    <div class="stat-number">{{ $deliveredOrders }}</div>
                    <div class="stat-small">{{ translate('Successfully delivered') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Filter Orders') }}</h5>
            <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#filterCollapse">
                <i class="las la-filter"></i>
            </button>
        </div>
        <div class="collapse {{ request()->has('search') || request()->has('delivery_status') || request()->has('payment_status') ? 'show' : '' }}"
            id="filterCollapse">
            <div class="card-body">
                <form method="GET" action="{{ route('orders.index') }}" id="filter-form">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ translate('Search') }}</label>
                                <input type="text" class="form-control" name="search"
                                    placeholder="{{ translate('Search by order code, customer, address') }}"
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ translate('Delivery Status') }}</label>
                                <select class="form-control" name="delivery_status">
                                    <option value="">{{ translate('All') }}</option>
                                    <option value="pending"
                                        {{ request('delivery_status') == 'pending' ? 'selected' : '' }}>
                                        {{ translate('Pending') }}</option>
                                    <option value="confirmed"
                                        {{ request('delivery_status') == 'confirmed' ? 'selected' : '' }}>
                                        {{ translate('Confirmed') }}</option>
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ translate('Sort By') }}</label>
                                <select class="form-control" name="sort_by">
                                    <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>
                                        {{ translate('Latest') }}</option>
                                    <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>
                                        {{ translate('Oldest') }}</option>
                                    <option value="grand_total_desc"
                                        {{ request('sort_by') == 'grand_total_desc' ? 'selected' : '' }}>
                                        {{ translate('Amount (High to Low)') }}</option>
                                    <option value="grand_total_asc"
                                        {{ request('sort_by') == 'grand_total_asc' ? 'selected' : '' }}>
                                        {{ translate('Amount (Low to High)') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-flex" style="margin-left: 2px; gap:10px;">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="las la-search"></i> {{ translate('Filter') }}
                                    </button>

                                    <a href="{{ route('orders.index') }}" class="btn btn-secondary w-100">
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

    <!-- Bulk Action Bar -->
    <div class="mb-3" id="bulk-action-bar" style="display: none;">
        <div class="alert alert-primary">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <i class="las la-check-circle"></i>
                    <span id="selected-count">0</span> {{ translate('orders selected') }}
                </div>
                <div class="col-md-8 text-md-right">
                    <select class="form-control-sm" id="bulk-delivery-status">
                        <option value="">{{ translate('Change Delivery Status') }}</option>
                        <option value="pending">{{ translate('Pending') }}</option>
                        <option value="confirmed">{{ translate('Confirmed') }}</option>
                        <option value="processing">{{ translate('Processing') }}</option>
                        <option value="shipped">{{ translate('Shipped') }}</option>
                        <option value="delivered">{{ translate('Delivered') }}</option>
                        <option value="cancelled">{{ translate('Cancelled') }}</option>
                    </select>
                    <select class="form-control-sm ml-2" id="bulk-payment-status">
                        <option value="">{{ translate('Change Payment Status') }}</option>
                        <option value="unpaid">{{ translate('Unpaid') }}</option>
                        <option value="paid">{{ translate('Paid') }}</option>
                        <option value="refunded">{{ translate('Refunded') }}</option>
                    </select>
                    <button type="button" class="btn btn-sm btn-primary ml-2" onclick="applyBulkStatus()">
                        {{ translate('Apply') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary ml-2" onclick="clearSelection()">
                        {{ translate('Clear') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('All Orders') }}</h5>
            <span class="badge badge-primary ml-2">{{ $orders->total() }} {{ translate('Total') }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered aiz-table">
                    <thead>
                        <tr>
                            <th width="3%">
                                <label class="aiz-checkbox">
                                    <input type="checkbox" id="select-all">
                                    <span class="aiz-square-check"></span>
                                </label>
                            </th>
                            <th width="5%">#</th>
                            <th width="15%">{{ translate('Order Code') }}</th>
                            <th width="15%">{{ translate('Customer') }}</th>
                            <th width="10%">{{ translate('Amount') }}</th>
                            <th width="12%">{{ translate('Delivery Status') }}</th>
                            <th width="12%">{{ translate('Payment Status') }}</th>
                            <th width="10%">{{ translate('Payment Method') }}</th>
                            <th width="10%">{{ translate('Date') }}</th>
                            <th width="10%">{{ translate('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $key => $order)
                            <tr>
                                <td>
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </td>
                                <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $key + 1 }}</td>
                                <td><strong>{{ $order->code }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm mr-2">
                                            @if ($order->user && $order->user->avatar)
                                                <img src="{{ uploaded_asset($order->user->avatar) }}"
                                                    class="rounded-circle">
                                            @else
                                                <img src="{{ asset('default/avatar.jpg') }}" class="rounded-circle">
                                            @endif
                                        </div>
                                        <div>
                                            <div>{{ $order->user->name ?? 'Guest User' }}</div>
                                            <small class="text-muted">{{ $order->user->email ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span
                                        class="font-weight-bold text-primary">৳{{ number_format($order->grand_total, 2) }}</span>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm delivery-status-select"
                                        data-order-id="{{ $order->id }}"
                                        data-current-status="{{ $order->delivery_status }}">
                                        <option value="pending"
                                            {{ $order->delivery_status == 'pending' ? 'selected' : '' }}>
                                            {{ translate('Pending') }}</option>
                                        <option value="confirmed"
                                            {{ $order->delivery_status == 'confirmed' ? 'selected' : '' }}>
                                            {{ translate('Confirmed') }}</option>
                                        <option value="processing"
                                            {{ $order->delivery_status == 'processing' ? 'selected' : '' }}>
                                            {{ translate('Processing') }}</option>
                                        <option value="shipped"
                                            {{ $order->delivery_status == 'shipped' ? 'selected' : '' }}>
                                            {{ translate('Shipped') }}</option>
                                        <option value="delivered"
                                            {{ $order->delivery_status == 'delivered' ? 'selected' : '' }}>
                                            {{ translate('Delivered') }}</option>
                                        <option value="cancelled"
                                            {{ $order->delivery_status == 'cancelled' ? 'selected' : '' }}>
                                            {{ translate('Cancelled') }}</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm payment-status-select"
                                        data-order-id="{{ $order->id }}"
                                        data-current-status="{{ $order->payment_status }}">
                                        <option value="unpaid"
                                            {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>
                                            {{ translate('Unpaid') }}</option>
                                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>
                                            {{ translate('Paid') }}</option>
                                        <option value="refunded"
                                            {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>
                                            {{ translate('Refunded') }}</option>
                                    </select>
                                </td>
                                <td>{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                                <td>
                                    <div>
                                        <span class="d-block">{{ $order->created_at->format('d M Y') }}</span>
                                        <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('orders.show', $order->id) }}"
                                            class="btn btn-sm btn-icon btn-info" title="{{ translate('View') }}">
                                            <i class="las la-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-icon btn-danger confirm-delete"
                                            data-href="{{ route('orders.destroy', $order->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">
                                    <div class="py-5">
                                        <i class="las la-shopping-cart fs-60 text-muted"></i>
                                        <h5 class="text-muted mt-3">{{ translate('No orders found') }}</h5>
                                        <p class="text-muted">{{ translate('Create your first order to get started.') }}
                                        </p>
                                    </div>
                                </td>
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

@section('modal')
    <!-- Delete Modal -->
    <div id="delete-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Delete Order') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="las la-trash text-danger" style="font-size: 48px;"></i>
                    <h4 class="mt-2">{{ translate('Are you sure?') }}</h4>
                    <p>{{ translate('This order will be permanently deleted.') }}</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <form id="delete-form" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ translate('Delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#select-all').on('change', function() {
                $('.order-checkbox').prop('checked', $(this).is(':checked'));
                updateBulkActionBar();
            });

            $(document).on('change', '.order-checkbox', function() {
                updateBulkActionBar();
            });
        });

        function updateBulkActionBar() {
            var selected = $('.order-checkbox:checked').length;
            if (selected > 0) {
                $('#bulk-action-bar').show();
                $('#selected-count').text(selected);
            } else {
                $('#bulk-action-bar').hide();
            }
        }

        function clearSelection() {
            $('.order-checkbox').prop('checked', false);
            $('#select-all').prop('checked', false);
            updateBulkActionBar();
        }

        // Individual delivery status change
        $(document).on('change', '.delivery-status-select', function() {
            var orderId = $(this).data('order-id');
            var status = $(this).val();
            var $select = $(this);

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
                beforeSend: function() {
                    $select.prop('disabled', true);
                },
                success: function(response) {
                    if (response.success) {
                        AIZ.plugins.notify('success', response.message);
                        $select.data('current-status', status);
                    } else {
                        AIZ.plugins.notify('danger', response.message);
                        $select.val($select.data('current-status'));
                    }
                },
                error: function() {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                    $select.val($select.data('current-status'));
                },
                complete: function() {
                    $select.prop('disabled', false);
                }
            });
        });

        // Individual payment status change
        $(document).on('change', '.payment-status-select', function() {
            var orderId = $(this).data('order-id');
            var status = $(this).val();
            var $select = $(this);

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
                beforeSend: function() {
                    $select.prop('disabled', true);
                },
                success: function(response) {
                    if (response.success) {
                        AIZ.plugins.notify('success', response.message);
                        $select.data('current-status', status);
                    } else {
                        AIZ.plugins.notify('danger', response.message);
                        $select.val($select.data('current-status'));
                    }
                },
                error: function() {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                    $select.val($select.data('current-status'));
                },
                complete: function() {
                    $select.prop('disabled', false);
                }
            });
        });

        // Bulk status update
        function applyBulkStatus() {
            var selectedIds = [];
            $('.order-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                AIZ.plugins.notify('warning', 'Please select at least one order');
                return;
            }

            var deliveryStatus = $('#bulk-delivery-status').val();
            var paymentStatus = $('#bulk-payment-status').val();

            if (!deliveryStatus && !paymentStatus) {
                AIZ.plugins.notify('warning', 'Please select a status to update');
                return;
            }

            if (deliveryStatus && confirm('Are you sure you want to update delivery status for ' + selectedIds.length +
                    ' orders?')) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('orders.bulk-update-delivery') }}",
                    data: {
                        ids: selectedIds,
                        status: deliveryStatus
                    },
                    beforeSend: function() {
                        AIZ.plugins.notify('info', 'Processing...');
                    },
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                        }
                    },
                    error: function() {
                        AIZ.plugins.notify('danger', 'Something went wrong');
                    }
                });
            }

            if (paymentStatus && confirm('Are you sure you want to update payment status for ' + selectedIds.length +
                    ' orders?')) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('orders.bulk-update-payment') }}",
                    data: {
                        ids: selectedIds,
                        status: paymentStatus
                    },
                    beforeSend: function() {
                        AIZ.plugins.notify('info', 'Processing...');
                    },
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                        }
                    },
                    error: function() {
                        AIZ.plugins.notify('danger', 'Something went wrong');
                    }
                });
            }
        }

        // Delete confirmation handler
        $(document).on('click', '.confirm-delete', function(e) {
            e.preventDefault();
            var url = $(this).data('href');
            $('#delete-form').attr('action', url);
            $('#delete-modal').modal('show');
        });
    </script>


    <script>
        const revenueValue = "৳{{ number_format($totalRevenue, 2) }}";
        let hideTimer;

        function toggleRevenue() {
            let amount = document.getElementById('revenue-amount');
            let eye = document.getElementById('revenue-eye');

            if (amount.dataset.hidden === "true") {
                amount.innerHTML = revenueValue;
                amount.dataset.hidden = "false";
                eye.className = "las la-eye";
                clearTimeout(hideTimer);
                hideTimer = setTimeout(() => {
                    amount.innerHTML = "৳ ******";
                    amount.dataset.hidden = "true";
                    eye.className = "las la-eye-slash";
                }, 5000);

            } else {
                amount.innerHTML = "৳ ******";
                amount.dataset.hidden = "true";
                eye.className = "las la-eye-slash";

                clearTimeout(hideTimer);
            }
        }
    </script>
@endsection
