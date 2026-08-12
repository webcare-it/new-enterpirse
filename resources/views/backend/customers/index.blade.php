@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Customer Management') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('customers.export') }}" class="btn btn-sm btn-success">
                    <i class="las la-file-excel"></i> {{ translate('Export') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <style>
        .mm-stat-card {
            position: relative;
            overflow: hidden;
            border-radius: 18px;
            padding: 22px;
            color: #fff;
            min-height: 135px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
            transition: all .3s ease;
        }

        .mm-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
        }

        .mm-stat-card::after {
            content: "";
            position: absolute;
            width: 140px;
            height: 140px;
            right: -45px;
            bottom: -50px;
            background: rgba(255, 255, 255, 0.16);
            border-radius: 50%;
        }

        .mm-stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.20);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
        }

        .mm-stat-title {
            font-size: 13px;
            opacity: .85;
            margin-bottom: 10px;
        }

        .mm-stat-number {
            font-size: 34px;
            font-weight: 800;
            margin: 0;
            line-height: 1;
        }

        .mm-stat-small {
            opacity: .9;
            font-size: 12px;
            margin-top: 12px;
            display: inline-block;
        }

        .mm-bg-primary {
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
        }

        .mm-bg-success {
            background: linear-gradient(135deg, #16a34a, #22c55e);
        }

        .mm-bg-danger {
            background: linear-gradient(135deg, #dc2626, #fb7185);
        }

        .mm-bg-info {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
        }

        .mm-bg-dark {
            background: linear-gradient(135deg, #334155, #0f172a);
        }

        .mm-bg-pink {
            background: linear-gradient(135deg, #ec4899, #8b5cf6);
        }

        .filter-chip {
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 30px;
            padding: 6px 16px;
            margin-right: 8px;
            margin-bottom: 8px;
            display: inline-block;
            font-size: 13px;
        }

        .filter-chip.active {
            background: #4f46e5;
            color: #fff;
            border-color: #4f46e5;
        }

        .filter-chip.inactive {
            background: #f3f4f6;
            color: #6b7280;
            border: 1px solid #e5e7eb;
        }

        .filter-chip.inactive:hover {
            background: #e5e7eb;
            color: #374151;
        }

        .time-filter-group {
            background: #f9fafb;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 20px;
        }

        .time-filter-title {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #4b5563;
        }
    </style>

    <!-- Statistics Cards Row 1 -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-primary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">{{ translate('Total Customers') }}</div>
                        <h2 class="mm-stat-number">{{ $totalCustomers }}</h2>
                        <span class="mm-stat-small">{{ translate('All customer records') }}</span>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-success">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">{{ translate('Active Customers') }}</div>
                        <h2 class="mm-stat-number">{{ $activeCustomers }}</h2>
                        <span class="mm-stat-small">{{ translate('Currently active users') }}</span>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-user-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-danger">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">{{ translate('Banned Customers') }}</div>
                        <h2 class="mm-stat-number">{{ $bannedCustomers }}</h2>
                        <span class="mm-stat-small">{{ translate('Restricted customer accounts') }}</span>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-user-slash"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-info">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">{{ translate('Total Balance') }}</div>
                        <h2 class="mm-stat-number">৳{{ number_format($totalBalance, 2) }}</h2>
                        <span class="mm-stat-small">{{ translate('Customer wallet balance') }}</span>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-wallet"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Row 2 - Time Based Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-dark">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">{{ translate('Last 7 Days') }}</div>
                        <h2 class="mm-stat-number">{{ $last7DaysCustomers ?? 0 }}</h2>
                        <span class="mm-stat-small">{{ translate('New customers this week') }}</span>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-calendar-week"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-pink">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">{{ translate('Last 30 Days') }}</div>
                        <h2 class="mm-stat-number">{{ $last30DaysCustomers ?? 0 }}</h2>
                        <span class="mm-stat-small">{{ translate('New customers this month') }}</span>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-calendar-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-primary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">{{ translate('Last Year') }}</div>
                        <h2 class="mm-stat-number">{{ $lastYearCustomers ?? 0 }}</h2>
                        <span class="mm-stat-small">{{ translate('New customers this year') }}</span>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-calendar-year"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-success">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">{{ translate('All Time') }}</div>
                        <h2 class="mm-stat-number">{{ $totalCustomers }}</h2>
                        <span class="mm-stat-small">{{ translate('Total registered customers') }}</span>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-database"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Filter Chips -->
    <div class="time-filter-group">
        <div class="time-filter-title">{{ translate('Filter by Registration Date') }}</div>
        <div>
            <span class="filter-chip {{ $timeFilter == '7days' ? 'active' : 'inactive' }}" data-filter="7days">
                <i class="las la-calendar-week"></i> {{ translate('Last 7 Days') }}
            </span>
            <span class="filter-chip {{ $timeFilter == '30days' ? 'active' : 'inactive' }}" data-filter="30days">
                <i class="las la-calendar-alt"></i> {{ translate('Last 30 Days') }}
            </span>
            <span class="filter-chip {{ $timeFilter == 'month' ? 'active' : 'inactive' }}" data-filter="month">
                <i class="las la-calendar-month"></i> {{ translate('This Month') }}
            </span>
            <span class="filter-chip {{ $timeFilter == 'year' ? 'active' : 'inactive' }}" data-filter="year">
                <i class="las la-calendar-year"></i> {{ translate('This Year') }}
            </span>
            <span class="filter-chip {{ $timeFilter == 'all' ? 'active' : 'inactive' }}" data-filter="all">
                <i class="las la-infinity"></i> {{ translate('All Time') }}
            </span>
        </div>
    </div>

    {{-- Bulk Action Bar --}}
    <div class="mb-3" id="bulk-action-bar" style="display: none;">
        <div class="alert alert-primary">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <i class="las la-check-circle"></i>
                    <span id="selected-count">0</span> {{ translate('customers selected') }}
                </div>
                <div class="col-md-6 text-md-right">
                    <select class="form-control-sm" id="bulk-action">
                        <option value="">{{ translate('Bulk Action') }}</option>
                        <option value="delete">{{ translate('Delete') }}</option>
                        <option value="ban">{{ translate('Ban') }}</option>
                        <option value="activate">{{ translate('Activate') }}</option>
                        <option value="sms">{{ translate('Send SMS') }}</option>
                    </select>
                    <button type="button" class="btn btn-sm btn-primary" onclick="showBulkActionModal()">
                        {{ translate('Apply') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="clearSelection()">
                        {{ translate('Clear') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Search and Filter --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('customers.index') }}" id="filter-form">
                <input type="hidden" name="time_filter" id="time_filter" value="{{ $timeFilter ?? 'all' }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <input type="text" class="form-control" name="search"
                                placeholder="{{ translate('Search by name, email or phone') }}"
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <select class="form-control" name="status">
                                <option value="">{{ translate('All Status') }}</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                    {{ translate('Active') }}</option>
                                <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>
                                    {{ translate('Banned') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <select class="form-control" name="balance_filter">
                                <option value="">{{ translate('All Balance') }}</option>
                                <option value="has_balance"
                                    {{ request('balance_filter') == 'has_balance' ? 'selected' : '' }}>
                                    {{ translate('Has Balance') }}</option>
                                <option value="no_balance"
                                    {{ request('balance_filter') == 'no_balance' ? 'selected' : '' }}>
                                    {{ translate('No Balance') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <select class="form-control" name="sort_by">
                                <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>
                                    {{ translate('Latest') }}</option>
                                <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>
                                    {{ translate('Oldest') }}</option>
                                <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>
                                    {{ translate('Name (A-Z)') }}</option>
                                <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>
                                    {{ translate('Name (Z-A)') }}</option>
                                <option value="balance_high" {{ request('sort_by') == 'balance_high' ? 'selected' : '' }}>
                                    {{ translate('Balance (High to Low)') }}</option>
                                <option value="balance_low" {{ request('sort_by') == 'balance_low' ? 'selected' : '' }}>
                                    {{ translate('Balance (Low to High)') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="las la-filter"></i> {{ translate('Filter') }}
                                </button>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-block">
                                    <i class="las la-undo-alt"></i> {{ translate('Reset') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Customers Table --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('All Customers') }}</h5>
            <span class="badge badge-primary ml-2">{{ $customers->total() }} {{ translate('Total') }}</span>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered aiz-table mb-0">
                    <thead>
                        <tr>
                            <th width="3%">
                                <label class="aiz-checkbox">
                                    <input type="checkbox" id="select-all">
                                    <span class="aiz-square-check"></span>
                                </label>
                            </th>
                            <th width="5%">#</th>
                            <th width="14%">{{ translate('Customer Info') }}</th>
                            <th width="14%">{{ translate('Contact Details') }}</th>
                            <th width="13%">{{ translate('Address') }}</th>
                            <th width="8%">{{ translate('Balance') }}</th>
                            <th width="7%">{{ translate('Orders') }}</th> {{-- NEW COLUMN --}}
                            <th width="8%">{{ translate('Point') }}</th>
                            <th width="10%">{{ translate('Award') }}</th>
                            <th width="8%">{{ translate('Status') }}</th>
                            <th width="10%">{{ translate('Registered Date') }}</th>
                            <th width="10%">{{ translate('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $key => $customer)
                            <tr>
                                <td>
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="customer-checkbox" value="{{ $customer->id }}">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </td>
                                <td>{{ ($customers->currentPage() - 1) * $customers->perPage() + $key + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-md mr-2">
                                            @if ($customer->user && $customer->user->avatar)
                                                <img src="{{ uploaded_asset($customer->user->avatar) }}"
                                                    class="rounded-circle">
                                            @else
                                                <img src="{{ asset('default/avatar.jpg') }}" class="rounded-circle">
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $customer->user->name ?? 'N/A' }}</div>
                                            <small class="text-muted">ID: #{{ $customer->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div><i class="las la-envelope"></i> {{ $customer->user->email ?? 'N/A' }}</div>
                                        <div><i class="las la-phone"></i> {{ $customer->phone ?? 'N/A' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div>{{ $customer->address ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $customer->city ?? '' }}
                                            {{ $customer->postal_code ?? '' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="font-weight-bold {{ $customer->balance > 0 ? 'text-success' : 'text-muted' }}">
                                        ৳{{ number_format($customer->balance, 2) }}
                                    </span>
                                </td>
                                <td>
                                    {{-- Use the relationship count (eager-loaded with 'orders') --}}
                                    <span class="badge badge-secondary">{{ $customer->orders->count() }}</span>
                                </td>
                                <td>
                                    <span
                                        class="font-weight-bold {{ $customer->point > 0 ? 'text-success' : 'text-muted' }}">
                                        {{ number_format($customer->point, 2) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $tier = match (true) {
                                            $customer->point >= 0 && $customer->point <= 10 => 'Silver',
                                            $customer->point >= 11 && $customer->point <= 30 => 'Gold',
                                            $customer->point >= 31 && $customer->point <= 100 => 'Diamond',
                                            default => 'Unknown',
                                        };
                                        $tierColor = match ($tier) {
                                            'Silver' => '#C0C0C0',
                                            'Gold' => '#FFD700',
                                            'Diamond' => '#B9F2FF',
                                            default => '#6c757d',
                                        };
                                    @endphp
                                    <svg width="16" height="16" viewBox="0 0 16 16" class="mr-1"
                                        style="display:inline-block; vertical-align:middle;">
                                        <circle cx="8" cy="8" r="7" fill="{{ $tierColor }}"
                                            stroke="#333" stroke-width="0.5" />
                                    </svg>
                                    <span
                                        class="font-weight-bold {{ $customer->point > 0 ? 'text-success' : 'text-muted' }}">
                                        {{ number_format($customer->point, 2) }}
                                    </span>
                                    <span class="badge badge-{{ strtolower($tier) }} ml-1">
                                        {{ $tier }}
                                    </span>
                                </td>
                                <td>
                                    @if ($customer->banned)
                                        <span class="badge badge-danger">{{ translate('Banned') }}</span>
                                    @else
                                        <span class="badge badge-success">{{ translate('Active') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <span class="d-block">{{ $customer->created_at->format('d M Y') }}</span>
                                        <small class="text-muted">{{ $customer->created_at->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('customers.show', $customer->id) }}"
                                            class="btn btn-sm btn-icon btn-info" title="{{ translate('View') }}">
                                            <i class="las la-eye"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer->id) }}"
                                            class="btn btn-sm btn-icon btn-primary" title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        @if ($customer->banned)
                                            <button type="button" class="btn btn-sm btn-icon btn-success"
                                                data-toggle="modal" data-target="#activate-modal"
                                                onclick="setCustomerData({{ $customer->id }})"
                                                title="{{ translate('Activate') }}">
                                                <i class="las la-user-check"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-icon btn-warning"
                                                data-toggle="modal" data-target="#ban-modal"
                                                onclick="setCustomerData({{ $customer->id }})"
                                                title="{{ translate('Ban') }}">
                                                <i class="las la-user-slash"></i>
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-icon btn-danger" data-toggle="modal"
                                            data-target="#delete-modal"
                                            onclick="setDeleteForm('{{ route('customers.destroy', $customer->id) }}')"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">
                                    <div class="py-5">
                                        <i class="las la-users fs-60 text-muted"></i>
                                        <h5 class="text-muted mt-3">{{ translate('No customers found') }}</h5>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="aiz-pagination mt-4">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- Bulk Action Modal -->
    <div id="bulk-action-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="bulk-action-title">{{ translate('Bulk Action') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center px-4 pb-4">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 80px; height: 80px; border-radius: 50%;" id="bulk-action-icon-container">
                        <i class="las la-question-circle" style="font-size: 36px;" id="bulk-action-icon"></i>
                    </div>
                    <h4 class="fw-bold mb-2" id="bulk-action-message-title">{{ translate('Confirm Action') }}</h4>
                    <p class="text-muted mb-4" id="bulk-action-message">
                        {{ translate('Are you sure you want to perform this action?') }}
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-light rounded-pill px-4 py-2" data-dismiss="modal">
                            {{ translate('Cancel') }}
                        </button>
                        <button type="button" class="btn rounded-pill px-4 py-2" id="bulk-action-confirm-btn"
                            onclick="executeBulkActionConfirm()">
                            {{ translate('Confirm') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk SMS Modal -->
    <div id="bulk-sms-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-info">{{ translate('Send Bulk SMS') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="bulk-sms-form">
                    @csrf
                    <div class="modal-body px-4 pb-2">
                        <div class="alert alert-info mb-3">
                            <i class="las la-info-circle"></i>
                            <span id="sms-recipient-count">0</span> {{ translate('customers selected') }}
                        </div>

                        <div class="form-group">
                            <label>{{ translate('Message') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="message" id="sms-message" rows="4"
                                placeholder="{{ translate('Type your message here...') }}" maxlength="500" required></textarea>
                            <small class="text-muted">
                                <span id="char-count">0</span>/500 {{ translate('characters') }}
                            </small>
                        </div>

                        <div class="form-group">
                            <label>{{ translate('Message Template') }}</label>
                            <select class="form-control" id="sms-template">
                                <option value="">{{ translate('Select Template') }}</option>
                                <option value="Dear {name}, welcome to our store! Get 10% off on your first purchase.">
                                    {{ translate('Welcome Message') }}
                                </option>
                                <option
                                    value="Dear {name}, thank you for being with us! Your account is in good standing.">
                                    {{ translate('Thank You Message') }}
                                </option>
                                <option value="Dear {name}, we miss you! Get 20% off on your next purchase.">
                                    {{ translate('Offer Message') }}
                                </option>
                                <option value="Dear {name}, your account has been updated successfully.">
                                    {{ translate('Account Update') }}
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>{{ translate('Additional Options') }}</label>
                            <div class="row">
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="include_name" checked>
                                        <label class="custom-control-label"
                                            for="include_name">{{ translate('Include Customer Name') }}</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="include_balance">
                                        <label class="custom-control-label"
                                            for="include_balance">{{ translate('Include Balance') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="sms-preview" class="alert alert-secondary mt-2" style="display: none;">
                            <strong>{{ translate('Preview') }}:</strong>
                            <p id="preview-text" class="mb-0 mt-1"></p>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center pb-4">
                        <button type="button" class="btn btn-light rounded-pill px-4 py-2" data-dismiss="modal">
                            {{ translate('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-info rounded-pill px-4 py-2">
                            <i class="las la-paper-plane"></i> {{ translate('Send SMS') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Ban Modal -->
    <div id="ban-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-warning">{{ translate('Ban Customer') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center px-4 pb-4">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 80px; height: 80px; border-radius: 50%; background: rgba(255, 193, 7, 0.1);">
                        <i class="las la-user-slash text-warning" style="font-size: 36px;"></i>
                    </div>
                    <h4 class="fw-bold mb-2">{{ translate('Ban this customer?') }}</h4>
                    <p class="text-muted mb-4">
                        {{ translate('This customer will be banned and cannot login or make purchases.') }}
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-light rounded-pill px-4 py-2" data-dismiss="modal">
                            {{ translate('Cancel') }}
                        </button>
                        <button type="button" class="btn btn-warning rounded-pill px-4 py-2" onclick="confirmBan()">
                            <i class="las la-user-slash me-1"></i> {{ translate('Ban Customer') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activate Modal -->
    <div id="activate-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-success">{{ translate('Activate Customer') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center px-4 pb-4">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 80px; height: 80px; border-radius: 50%; background: rgba(40, 167, 69, 0.1);">
                        <i class="las la-user-check text-success" style="font-size: 36px;"></i>
                    </div>
                    <h4 class="fw-bold mb-2">{{ translate('Activate this customer?') }}</h4>
                    <p class="text-muted mb-4">
                        {{ translate('This customer will be activated and can login again.') }}
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-light rounded-pill px-4 py-2" data-dismiss="modal">
                            {{ translate('Cancel') }}
                        </button>
                        <button type="button" class="btn btn-success rounded-pill px-4 py-2"
                            onclick="confirmActivate()">
                            <i class="las la-user-check me-1"></i> {{ translate('Activate Customer') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">{{ translate('Delete Customer') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center px-4 pb-4">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 80px; height: 80px; border-radius: 50%; background: rgba(220, 53, 69, 0.1);">
                        <i class="las la-trash text-danger" style="font-size: 36px;"></i>
                    </div>
                    <h4 class="fw-bold mb-2">{{ translate('Are you sure?') }}</h4>
                    <p class="text-muted mb-4">{{ translate('This customer will be permanently deleted.') }}</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-light rounded-pill px-4 py-2" data-dismiss="modal">
                            {{ translate('Cancel') }}
                        </button>
                        <form id="delete-form" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger rounded-pill px-4 py-2">
                                <i class="las la-trash me-1"></i> {{ translate('Delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        let currentCustomerId = null;
        let bulkActionType = null;
        let bulkSelectedIds = [];
        let smsSelectedIds = [];

        $(document).ready(function() {
            // Select All checkbox
            $('#select-all').on('change', function() {
                $('.customer-checkbox').prop('checked', $(this).is(':checked'));
                updateBulkActionBar();
            });

            // Individual checkbox change
            $(document).on('change', '.customer-checkbox', function() {
                updateBulkActionBar();
            });

            // Time filter chip click
            $('.filter-chip').on('click', function() {
                var filter = $(this).data('filter');
                $('#time_filter').val(filter);
                $('#filter-form').submit();
            });

            // Character count for SMS
            $('#sms-message').on('keyup', function() {
                var count = $(this).val().length;
                $('#char-count').text(count);
                if (count > 0) {
                    updateSmsPreview();
                    $('#sms-preview').show();
                } else {
                    $('#sms-preview').hide();
                }
            });

            // SMS Template selection
            $('#sms-template').on('change', function() {
                var template = $(this).val();
                if (template) {
                    $('#sms-message').val(template);
                    var count = template.length;
                    $('#char-count').text(count);
                    updateSmsPreview();
                    $('#sms-preview').show();
                }
            });

            // Update preview when checkboxes change
            $('#include_name, #include_balance').on('change', function() {
                if ($('#sms-message').val().length > 0) {
                    updateSmsPreview();
                }
            });

            // Bulk SMS Form Submit
            $('#bulk-sms-form').on('submit', function(e) {
                e.preventDefault();
                sendBulkSms();
            });
        });

        // Update bulk action bar visibility and count
        function updateBulkActionBar() {
            var selected = $('.customer-checkbox:checked').length;
            if (selected > 0) {
                $('#bulk-action-bar').show();
                $('#selected-count').text(selected);
            } else {
                $('#bulk-action-bar').hide();
            }
        }

        // Get all selected customer IDs
        function getSelectedIds() {
            var ids = [];
            $('.customer-checkbox:checked').each(function() {
                ids.push($(this).val());
            });
            return ids;
        }

        // Clear all selections
        function clearSelection() {
            $('.customer-checkbox').prop('checked', false);
            $('#select-all').prop('checked', false);
            updateBulkActionBar();
        }

        // Show bulk action modal
        function showBulkActionModal() {
            let action = $('#bulk-action').val();
            if (!action) {
                AIZ.plugins.notify('warning', '{{ translate('Please select an action') }}');
                return;
            }

            bulkSelectedIds = getSelectedIds();
            if (bulkSelectedIds.length === 0) {
                AIZ.plugins.notify('warning', '{{ translate('Please select at least one customer') }}');
                return;
            }

            bulkActionType = action;

            // Configure modal based on action
            if (action === 'delete') {
                $('#bulk-action-message-title').text('{{ translate('Delete Customers') }}');
                $('#bulk-action-message').text(
                    '{{ translate('Are you sure you want to delete the selected customers? This action cannot be undone.') }}'
                );
                $('#bulk-action-icon').removeClass().addClass('las la-trash');
                $('#bulk-action-icon-container').css('background', 'rgba(220, 53, 69, 0.1)');
                $('#bulk-action-icon').css('color', '#dc3545');
                $('#bulk-action-confirm-btn').removeClass().addClass('btn btn-danger rounded-pill px-4 py-2');
                $('#bulk-action-confirm-btn').text('{{ translate('Delete') }}');
                $('#bulk-action-modal').modal('show');
            } else if (action === 'ban') {
                $('#bulk-action-message-title').text('{{ translate('Ban Customers') }}');
                $('#bulk-action-message').text(
                    '{{ translate('Are you sure you want to ban the selected customers? They will not be able to login or make purchases.') }}'
                );
                $('#bulk-action-icon').removeClass().addClass('las la-user-slash');
                $('#bulk-action-icon-container').css('background', 'rgba(255, 193, 7, 0.1)');
                $('#bulk-action-icon').css('color', '#ffc107');
                $('#bulk-action-confirm-btn').removeClass().addClass('btn btn-warning rounded-pill px-4 py-2');
                $('#bulk-action-confirm-btn').text('{{ translate('Ban') }}');
                $('#bulk-action-modal').modal('show');
            } else if (action === 'activate') {
                $('#bulk-action-message-title').text('{{ translate('Activate Customers') }}');
                $('#bulk-action-message').text(
                    '{{ translate('Are you sure you want to activate the selected customers? They will be able to login again.') }}'
                );
                $('#bulk-action-icon').removeClass().addClass('las la-user-check');
                $('#bulk-action-icon-container').css('background', 'rgba(40, 167, 69, 0.1)');
                $('#bulk-action-icon').css('color', '#28a745');
                $('#bulk-action-confirm-btn').removeClass().addClass('btn btn-success rounded-pill px-4 py-2');
                $('#bulk-action-confirm-btn').text('{{ translate('Activate') }}');
                $('#bulk-action-modal').modal('show');
            } else if (action === 'sms') {
                smsSelectedIds = bulkSelectedIds;
                $('#sms-recipient-count').text(smsSelectedIds.length);
                $('#bulk-sms-modal').modal('show');
            }
        }

        // Execute bulk action after confirmation
        function executeBulkActionConfirm() {
            if (bulkActionType === 'delete') {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('customers.bulk-action') }}",
                    data: {
                        ids: bulkSelectedIds,
                        action: 'delete',
                        _method: 'DELETE'
                    },
                    beforeSend: function() {
                        $('#bulk-action-confirm-btn').prop('disabled', true).html(
                            '<i class="las la-spinner la-spin"></i> {{ translate('Processing...') }}');
                    },
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                            $('#bulk-action-modal').modal('hide');
                        }
                    },
                    error: function() {
                        AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                        $('#bulk-action-modal').modal('hide');
                    }
                });
            } else if (bulkActionType === 'ban') {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('customers.bulk-action') }}",
                    data: {
                        ids: bulkSelectedIds,
                        action: 'ban'
                    },
                    beforeSend: function() {
                        $('#bulk-action-confirm-btn').prop('disabled', true).html(
                            '<i class="las la-spinner la-spin"></i> {{ translate('Processing...') }}');
                    },
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                            $('#bulk-action-modal').modal('hide');
                        }
                    },
                    error: function() {
                        AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                        $('#bulk-action-modal').modal('hide');
                    }
                });
            } else if (bulkActionType === 'activate') {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('customers.bulk-action') }}",
                    data: {
                        ids: bulkSelectedIds,
                        action: 'activate'
                    },
                    beforeSend: function() {
                        $('#bulk-action-confirm-btn').prop('disabled', true).html(
                            '<i class="las la-spinner la-spin"></i> {{ translate('Processing...') }}');
                    },
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                            $('#bulk-action-modal').modal('hide');
                        }
                    },
                    error: function() {
                        AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                        $('#bulk-action-modal').modal('hide');
                    }
                });
            }
        }

        // Send bulk SMS
        function sendBulkSms() {
            var message = $('#sms-message').val();

            if (!message.trim()) {
                AIZ.plugins.notify('warning', '{{ translate('Please enter a message') }}');
                return;
            }

            var includeName = $('#include_name').is(':checked') ? 1 : 0;
            var includeBalance = $('#include_balance').is(':checked') ? 1 : 0;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('customers.bulk-sms') }}",
                data: {
                    ids: smsSelectedIds,
                    message: message,
                    include_name: includeName,
                    include_balance: includeBalance
                },
                beforeSend: function() {
                    $('#bulk-sms-modal').find('button[type="submit"]').prop('disabled', true)
                        .html('<i class="las la-spinner la-spin"></i> {{ translate('Sending...') }}');
                },
                success: function(response) {
                    if (response.success) {
                        AIZ.plugins.notify('success', response.message);
                        $('#bulk-sms-modal').modal('hide');
                        $('#sms-message').val('');
                        $('#char-count').text(0);
                        $('#sms-template').val('');
                        $('#sms-preview').hide();
                        clearSelection();
                    } else {
                        AIZ.plugins.notify('danger', response.message);
                    }
                    $('#bulk-sms-modal').find('button[type="submit"]').prop('disabled', false)
                        .html('<i class="las la-paper-plane"></i> {{ translate('Send SMS') }}');
                },
                error: function() {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    $('#bulk-sms-modal').find('button[type="submit"]').prop('disabled', false)
                        .html('<i class="las la-paper-plane"></i> {{ translate('Send SMS') }}');
                }
            });
        }

        // Update SMS preview
        function updateSmsPreview() {
            var message = $('#sms-message').val();
            var includeName = $('#include_name').is(':checked');
            var includeBalance = $('#include_balance').is(':checked');

            var previewText = message;
            if (includeName) {
                previewText = previewText.replace(/{name}/g, '[Customer Name]');
            }
            if (includeBalance) {
                previewText = previewText + ' [Balance: ৳XX.XX]';
            }

            $('#preview-text').text(previewText);
        }

        // Set customer ID for single actions
        function setCustomerData(customerId) {
            currentCustomerId = customerId;
        }

        // Set delete form action
        function setDeleteForm(actionUrl) {
            $('#delete-form').attr('action', actionUrl);
        }

        // Confirm single ban
        function confirmBan() {
            if (!currentCustomerId) return;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('customers.toggle-ban', '') }}/" + currentCustomerId,
                data: {
                    _method: 'POST'
                },
                beforeSend: function() {
                    $('#ban-modal').find('.btn-warning').prop('disabled', true).html(
                        '<i class="las la-spinner la-spin"></i> {{ translate('Processing...') }}');
                },
                success: function(response) {
                    if (response.success) {
                        AIZ.plugins.notify('success', response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        AIZ.plugins.notify('danger', response.message);
                        $('#ban-modal').modal('hide');
                    }
                },
                error: function() {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    $('#ban-modal').modal('hide');
                }
            });
        }

        // Confirm single activate
        function confirmActivate() {
            if (!currentCustomerId) return;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('customers.toggle-ban', '') }}/" + currentCustomerId,
                data: {
                    _method: 'POST'
                },
                beforeSend: function() {
                    $('#activate-modal').find('.btn-success').prop('disabled', true).html(
                        '<i class="las la-spinner la-spin"></i> {{ translate('Processing...') }}');
                },
                success: function(response) {
                    if (response.success) {
                        AIZ.plugins.notify('success', response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        AIZ.plugins.notify('danger', response.message);
                        $('#activate-modal').modal('hide');
                    }
                },
                error: function() {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    $('#activate-modal').modal('hide');
                }
            });
        }
    </script>
@endsection
