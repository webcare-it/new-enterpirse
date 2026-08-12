@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Wishlist Management') }}</h1>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Styles -->
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
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.22);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .mm-stat-title {
            font-size: 13px;
            opacity: .85;
            margin-bottom: 8px;
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
        }

        .mm-bg-primary {
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
        }

        .mm-bg-success {
            background: linear-gradient(135deg, #16a34a, #22c55e);
        }

        .mm-bg-warning {
            background: linear-gradient(135deg, #f59e0b, #f97316);
        }

        .mm-bg-info {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .flex-grow-1 {
            flex-grow: 1;
        }
    </style>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-primary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">{{ translate('Total Wishlists') }}</div>
                        <h2 class="mm-stat-number">{{ $totalWishlists ?? $wishlists->total() }}</h2>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-heart"></i>
                    </div>
                </div>
                <div class="mt-3 mm-stat-small">{{ translate('All wishlist records') }}</div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-success">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">{{ translate('Total Products') }}</div>
                        <h2 class="mm-stat-number">{{ $totalProducts ?? 0 }}</h2>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-box"></i>
                    </div>
                </div>
                <div class="mt-3 mm-stat-small">{{ translate('Unique wishlist products') }}</div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-warning">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">{{ translate('Total Users') }}</div>
                        <h2 class="mm-stat-number">{{ $totalUsers ?? 0 }}</h2>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-users"></i>
                    </div>
                </div>
                <div class="mt-3 mm-stat-small">{{ translate('Users added wishlist') }}</div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-info">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">{{ translate('This Week') }}</div>
                        <h2 class="mm-stat-number">{{ $wishlistsThisWeek ?? 0 }}</h2>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-calendar-week"></i>
                    </div>
                </div>
                <div class="mt-3 mm-stat-small">{{ translate('New wishlist items this week') }}</div>
            </div>
        </div>
    </div>

    {{-- Search and Filter Section --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('wishlists.index') }}" id="filter-form">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <input type="text" class="form-control" name="search"
                                placeholder="{{ translate('Search by product name or user name/email') }}"
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <select class="form-control" name="sort">
                                <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>
                                    {{ translate('Latest First') }}
                                </option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                    {{ translate('Oldest First') }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <input type="date" class="form-control" name="date_from"
                                placeholder="{{ translate('Date From') }}" value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <input type="date" class="form-control" name="date_to"
                                placeholder="{{ translate('Date To') }}" value="{{ request('date_to') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="las la-filter"></i> {{ translate('Filter') }}
                            </button>
                            <a href="{{ route('wishlists.index') }}" class="btn btn-secondary flex-grow-1">
                                <i class="las la-undo-alt"></i> {{ translate('Reset') }}
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('All Wishlist Products') }}</h5>
            <span class="badge badge-primary ml-2">{{ $wishlists->total() }} {{ translate('Total') }}</span>
        </div>
        <div class="card-body">

            {{-- Bulk Action Bar --}}
            <div class="mb-3" id="bulk-action-bar" style="display: none;">
                <div class="alert alert-primary">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <i class="las la-check-circle"></i>
                            <span id="selected-count">0</span> {{ translate('wishlist items selected') }}
                        </div>
                        <div class="col-md-6 text-md-right">
                            <button type="button" class="btn btn-sm btn-danger" onclick="showBulkDeleteModal()">
                                <i class="las la-trash"></i> {{ translate('Delete Selected') }}
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="clearSelection()">
                                <i class="las la-times"></i> {{ translate('Clear') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
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
                            <th width="10%">{{ translate('Image') }}</th>
                            <th width="20%">{{ translate('Product') }}</th>
                            <th width="15%">{{ translate('User') }}</th>
                            <th width="15%">{{ translate('Email') }}</th>
                            <th width="10%">{{ translate('Phone') }}</th>
                            <th width="12%">{{ translate('Added Date') }}</th>
                            <th width="10%">{{ translate('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($wishlists as $key => $wishlist)
                            <tr>
                                {{-- Checkbox --}}
                                <td>
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="wishlist-checkbox" value="{{ $wishlist->id }}">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </td>

                                {{-- Serial Number --}}
                                <td>
                                    {{ $key + 1 + ($wishlists->currentPage() - 1) * $wishlists->perPage() }}
                                </td>

                                {{-- Product Image --}}
                                <td>
                                    @if ($wishlist->product && $wishlist->product->thumbnail)
                                        <img src="{{ uploaded_asset($wishlist->product->thumbnail) }}"
                                            alt="{{ $wishlist->product->name }}" class="img-fluid"
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center"
                                            style="width: 60px; height: 60px; border-radius: 8px;">
                                            <i class="las la-image text-muted fs-24"></i>
                                        </div>
                                    @endif
                                </td>

                                {{-- Product Info --}}
                                <td>
                                    @if ($wishlist->product)
                                        <strong class="d-block text-truncate" style="max-width: 200px;">
                                            {{ $wishlist->product->name }}
                                        </strong>
                                        <small class="text-muted d-block text-truncate" style="max-width: 200px;">
                                            {{ $wishlist->product->slug }}
                                        </small>
                                        @if ($wishlist->product->price)
                                            <small class="text-primary font-weight-bold">
                                                {{ number_format($wishlist->product->price->regular_price ?? 0, 2) }} BDT
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">{{ translate('Product Not Found') }}</span>
                                    @endif
                                </td>

                                {{-- User Info --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm mr-2">
                                            @if ($wishlist->user && $wishlist->user->avatar)
                                                <img src="{{ uploaded_asset($wishlist->user->avatar) }}"
                                                    class="rounded-circle">
                                            @else
                                                <img src="{{ asset('default/avatar.jpg') }}" class="rounded-circle">
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $wishlist->user->name ?? 'Guest User' }}
                                            </div>
                                            <small class="text-muted">ID: {{ $wishlist->user_id ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td>
                                    {{ $wishlist->user->email ?? 'N/A' }}
                                </td>

                                {{-- Phone --}}
                                <td>
                                    {{ $wishlist->user->phone ?? 'N/A' }}
                                </td>

                                {{-- Added Date --}}
                                <td>
                                    <div>
                                        <span
                                            class="d-block">{{ \Carbon\Carbon::parse($wishlist->created_at)->format('d M Y') }}</span>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($wishlist->created_at)->format('h:i A') }}</small>
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-icon btn-danger" data-toggle="modal"
                                            data-target="#delete-single-modal"
                                            onclick="setDeleteForm('{{ route('wishlists.destroy', $wishlist->id) }}')"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    <div class="py-5">
                                        <i class="las la-heart-broken fs-60 text-muted"></i>
                                        <h5 class="text-muted mt-3">{{ translate('No wishlist data found') }}</h5>
                                        <p class="text-muted">
                                            {{ translate('No wishlist items match your filter criteria.') }}
                                        </p>
                                        <a href="{{ route('wishlists.index') }}" class="btn btn-primary">
                                            <i class="las la-undo-alt"></i> {{ translate('Clear Filters') }}
                                        </a>
                                    </div>
                                <td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="aiz-pagination mt-4">
                {{ $wishlists->appends(request()->query())->links() }}
            </div>

        </div>
    </div>
@endsection

@section('modal')
    {{-- Bulk Delete Modal --}}
    <div id="bulk-delete-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-semibold text-dark">{{ translate('Delete Wishlist Items') }}</h5>
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
                    <p class="text-muted mb-4" id="bulk-delete-message" style="font-size: 14px;">
                        {{ translate('Are you sure you want to delete selected wishlist items? This action cannot be undone.') }}
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-light rounded-pill px-4 py-2" data-dismiss="modal">
                            {{ translate('Cancel') }}
                        </button>
                        <button type="button" class="btn btn-danger rounded-pill px-4 py-2"
                            onclick="confirmBulkDelete()">
                            <i class="las la-trash me-1"></i> {{ translate('Delete') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Single Delete Modal --}}
    <div id="delete-single-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-semibold text-dark">{{ translate('Delete Wishlist Item') }}</h5>
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
                    <p class="text-muted mb-4" style="font-size: 14px;">
                        {{ translate('This wishlist item will be permanently deleted.') }}
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-light rounded-pill px-4 py-2" data-dismiss="modal">
                            {{ translate('Cancel') }}
                        </button>
                        <form id="delete-single-form" method="POST" action="">
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
        $(document).ready(function() {
            // Select all checkbox functionality
            $('#select-all').on('change', function() {
                $('.wishlist-checkbox').prop('checked', $(this).is(':checked'));
                updateBulkActionBar();
            });

            // Individual checkbox change
            $(document).on('change', '.wishlist-checkbox', function() {
                updateBulkActionBar();
            });
        });

        // Update bulk action bar visibility
        function updateBulkActionBar() {
            var selected = $('.wishlist-checkbox:checked').length;
            if (selected > 0) {
                $('#bulk-action-bar').show();
                $('#selected-count').text(selected);
            } else {
                $('#bulk-action-bar').hide();
            }
        }

        // Clear all selections
        function clearSelection() {
            $('.wishlist-checkbox').prop('checked', false);
            $('#select-all').prop('checked', false);
            updateBulkActionBar();
        }

        // Set delete form action for single delete
        function setDeleteForm(url) {
            $('#delete-single-form').attr('action', url);
        }

        // Bulk delete - show modal
        let bulkSelectedIds = [];

        function showBulkDeleteModal() {
            bulkSelectedIds = [];
            $('.wishlist-checkbox:checked').each(function() {
                bulkSelectedIds.push($(this).val());
            });

            if (bulkSelectedIds.length > 0) {
                $('#bulk-delete-message').text('{{ translate('Are you sure you want to delete') }} ' + bulkSelectedIds
                    .length + ' {{ translate('selected wishlist items? This action cannot be undone.') }}');
                $('#bulk-delete-modal').modal('show');
            } else {
                AIZ.plugins.notify('warning', '{{ translate('Please select at least one wishlist item') }}');
            }
        }

        // Confirm bulk delete
        function confirmBulkDelete() {
            $('#bulk-delete-modal').modal('hide');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('wishlists.bulkDelete') }}",
                data: {
                    ids: bulkSelectedIds
                },
                beforeSend: function() {
                    AIZ.plugins.notify('info', '{{ translate('Deleting...') }}');
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
                error: function(xhr) {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
