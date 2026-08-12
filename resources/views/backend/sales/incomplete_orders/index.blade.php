@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Incomplete Orders') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    <i class="las la-arrow-left"></i> {{ translate('Back to Orders') }}
                </a>
            </div>
        </div>
    </div>

    <style>
        .stat-card {
            color: #fff;
            border-radius: 20px;
            padding: 24px;
            min-height: 150px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 14px 35px rgba(0, 0, 0, .13);
            transition: all .3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, .18);
        }

        .stat-card::after {
            content: "";
            position: absolute;
            width: 160px;
            height: 160px;
            right: -50px;
            bottom: -60px;
            background: rgba(255, 255, 255, .15);
            border-radius: 50%;
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 16px;
            background: rgba(255, 255, 255, .2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .stat-title {
            font-size: 14px;
            opacity: .9;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            line-height: 1.2;
            margin: 8px 0;
        }

        .stat-small {
            font-size: 13px;
            opacity: .9;
        }

        .bg-one {
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
        }

        .bg-two {
            background: linear-gradient(135deg, #16a34a, #22c55e);
        }

        .bg-three {
            background: linear-gradient(135deg, #f59e0b, #f97316);
        }

        .bg-four {
            background: linear-gradient(135deg, #ec4899, #8b5cf6);
        }

        .bg-five {
            background: linear-gradient(135deg, #dc2626, #fb7185);
        }
    </style>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4 mb-4">

        <!-- Total -->
        <div class="col">
            <div class="stat-card bg-one h-100">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-title">{{ translate('Total') }}</div>
                        <div class="stat-number">{{ $stats['total'] }}</div>
                        <div class="stat-small">{{ translate('Orders') }}</div>
                    </div>

                    <div class="stat-icon">
                        <i class="las la-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="col">
            <div class="stat-card bg-three h-100">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-title">{{ translate('Pending') }}</div>
                        <div class="stat-number">{{ $stats['pending'] }}</div>
                        <div class="stat-small">{{ translate('Orders') }}</div>
                    </div>

                    <div class="stat-icon">
                        <i class="las la-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Abandoned -->
        <div class="col">
            <div class="stat-card bg-five h-100">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-title">{{ translate('Abandoned') }}</div>
                        <div class="stat-number">{{ $stats['abandoned'] }}</div>
                        <div class="stat-small">{{ translate('Orders') }}</div>
                    </div>

                    <div class="stat-icon">
                        <i class="las la-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div class="col">
            <div class="stat-card bg-two h-100">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-title">{{ translate('Completed') }}</div>
                        <div class="stat-number">{{ $stats['completed'] }}</div>
                        <div class="stat-small">{{ translate('Orders') }}</div>
                    </div>

                    <div class="stat-icon">
                        <i class="las la-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cancelled -->
        <div class="col">
            <div class="stat-card bg-four h-100">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-title">{{ translate('Cancelled') }}</div>
                        <div class="stat-number">{{ $stats['cancelled'] }}</div>
                        <div class="stat-small">{{ translate('Orders') }}</div>
                    </div>

                    <div class="stat-icon">
                        <i class="las la-ban"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Filter & Search -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('incomplete-orders.index') }}" method="GET" id="filter-form">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{ translate('Search') }}</label>
                            <input type="text" class="form-control" name="search"
                                placeholder="{{ translate('Search by name, email, phone or code') }}"
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ translate('Status') }}</label>
                            <select class="form-control" name="status">
                                <option value="all">{{ translate('All Status') }}</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                    {{ translate('Pending') }}
                                </option>
                                <option value="abandoned" {{ request('status') == 'abandoned' ? 'selected' : '' }}>
                                    {{ translate('Abandoned') }}
                                </option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                                    {{ translate('Processing') }}
                                </option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                    {{ translate('Completed') }}
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                    {{ translate('Cancelled') }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ translate('From Date') }}</label>
                            <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ translate('To Date') }}</label>
                            <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-group w-100">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="las la-search"></i> {{ translate('Filter') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Incomplete Orders List') }}</h5>
            <div>
                <button class="btn btn-sm btn-danger" id="bulk-delete" disabled>
                    <i class="las la-trash"></i> {{ translate('Delete Selected') }}
                </button>
                <button class="btn btn-sm btn-warning" id="bulk-abandoned" disabled>
                    <i class="las la-exclamation-triangle"></i> {{ translate('Mark Abandoned') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered aiz-table mb-0">
                    <thead>
                        <tr>
                            <th width="3%">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th width="5%">#</th>
                            <th width="15%">{{ translate('Order Code') }}</th>
                            <th width="20%">{{ translate('Customer') }}</th>
                            <th width="15%">{{ translate('Total') }}</th>
                            <th width="10%">{{ translate('Status') }}</th>
                            <th width="12%">{{ translate('Created At') }}</th>
                            <th width="10%">{{ translate('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $index => $order)
                            <tr>
                                <td>
                                    <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                </td>
                                <td>{{ $orders->firstItem() + $index }}</td>
                                <td>
                                    <a href="{{ route('incomplete-orders.show', $order->id) }}">
                                        <strong>{{ $order->order_code }}</strong>
                                    </a>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $order->customer_name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="las la-envelope"></i> {{ $order->customer_email ?? 'N/A' }}
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <i class="las la-phone"></i> {{ $order->customer_phone ?? 'N/A' }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <strong class="text-primary">৳{{ number_format($order->total, 2) }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $order->cart_data ? count($order->cart_data) : 0 }} items
                                    </small>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'abandoned' => 'danger',
                                            'processing' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'secondary',
                                        ];
                                        $statusText = ucfirst($order->status);
                                    @endphp
                                    <span class="badge badge-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                        {{ translate($statusText) }}
                                    </span>
                                    @if ($order->status == 'abandoned')
                                        <br>
                                        <small class="text-danger">
                                            {{ $order->formatted_abandoned_duration }} ago
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    {{ $order->created_at->format('d M Y, h:i A') }}
                                    <br>
                                    <small class="text-muted">
                                        {{ $order->created_at->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                            data-toggle="dropdown">
                                            {{-- <i class="las la-ellipsis-v"></i> --}}
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('incomplete-orders.show', $order->id) }}"
                                                class="dropdown-item">
                                                <i class="las la-eye"></i> {{ translate('View') }}
                                            </a>
                                            @if ($order->status != 'completed' && $order->status != 'cancelled')
                                                <button class="dropdown-item convert-order"
                                                    data-id="{{ $order->id }}">
                                                    <i class="las la-check-circle"></i>
                                                    {{ translate('Convert to Order') }}
                                                </button>
                                                @if ($order->status == 'abandoned')
                                                    <button class="dropdown-item send-reminder"
                                                        data-id="{{ $order->id }}">
                                                        <i class="las la-bell"></i> {{ translate('Send Reminder') }}
                                                    </button>
                                                @endif
                                            @endif
                                            <div class="dropdown-divider"></div>
                                            <button class="dropdown-item text-danger delete-order"
                                                data-id="{{ $order->id }}">
                                                <i class="las la-trash"></i>

                                                {{ translate('Delete') }}
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="las la-inbox fs-40 text-muted"></i>
                                    <h5 class="text-muted mt-3">{{ translate('No incomplete orders found') }}</h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            // ===== SELECT ALL =====
            $('#select-all').on('change', function() {
                $('.order-checkbox').prop('checked', $(this).prop('checked'));
                updateBulkButtons();
            });

            $('.order-checkbox').on('change', function() {
                updateBulkButtons();
            });

            function updateBulkButtons() {
                var checked = $('.order-checkbox:checked').length;
                $('#bulk-delete, #bulk-abandoned').prop('disabled', checked === 0);
            }

            // ===== BULK DELETE =====
            $('#bulk-delete').on('click', function() {
                var ids = getSelectedIds();
                if (ids.length === 0) return;

                if (!confirm('{{ translate('Are you sure you want to delete selected orders?') }}')) {
                    return;
                }

                performBulkAction(ids, 'delete');
            });

            // ===== BULK ABANDONED =====
            $('#bulk-abandoned').on('click', function() {
                var ids = getSelectedIds();
                if (ids.length === 0) return;

                if (!confirm(
                        '{{ translate('Are you sure you want to mark selected orders as abandoned?') }}'
                    )) {
                    return;
                }

                performBulkAction(ids, 'mark_abandoned');
            });

            function getSelectedIds() {
                return $('.order-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
            }

            function performBulkAction(ids, action) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('incomplete-orders.bulk-action') }}",
                    data: {
                        ids: ids,
                        action: action
                    },
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                        }
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        AIZ.plugins.notify('danger', response?.message || 'Something went wrong');
                    }
                });
            }

            // ===== DELETE SINGLE ORDER =====
            $(document).on('click', '.delete-order', function() {
                var id = $(this).data('id');

                if (!confirm('{{ translate('Are you sure you want to delete this order?') }}')) {
                    return;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "DELETE",
                    url: "{{ route('incomplete-orders.destroy', '') }}/" + id,
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
                        var response = xhr.responseJSON;
                        AIZ.plugins.notify('danger', response?.message ||
                            'Something went wrong');
                    }
                });
            });

            // ===== CONVERT TO ORDER =====
            $(document).on('click', '.convert-order', function() {
                var id = $(this).data('id');

                if (!confirm('{{ translate('Convert this incomplete order to a full order?') }}')) {
                    return;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('incomplete-orders.convert', '') }}/" + id,
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
                        }
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        AIZ.plugins.notify('danger', response?.message ||
                            'Something went wrong');
                    }
                });
            });

            // ===== SEND REMINDER =====
            $(document).on('click', '.send-reminder', function() {
                var id = $(this).data('id');

                if (!confirm('{{ translate('Send reminder to this customer?') }}')) {
                    return;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('incomplete-orders.send-reminder', '') }}/" + id,
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
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
        });
    </script>
@endsection

@section('styles')
    <style>
        .badge {
            font-size: 11px;
            padding: 4px 10px;
        }

        .table td {
            vertical-align: middle;
        }

        .fs-40 {
            font-size: 40px;
        }

        .card-body .table-responsive {
            min-height: 300px;
        }
    </style>
@endsection
