@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Compare Products Management') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('compares.export') }}" class="btn btn-sm btn-success">
                    <i class="las la-file-excel"></i> {{ translate('Export') }}
                </a>
                <button type="button" class="btn btn-sm btn-danger" onclick="confirmClearAll()">
                    <i class="las la-trash-alt"></i> {{ translate('Clear All') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
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

        .mm-bg-warning {
            background: linear-gradient(135deg, #f59e0b, #f97316);
        }

        .mm-bg-info {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .d-flex {
            display: flex;
        }

        .flex-grow-1 {
            flex-grow: 1;
        }
    </style>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-primary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">
                            {{ translate('Total Compares') }}
                        </div>
                        <h2 class="mm-stat-number">
                            {{ $totalCompares }}
                        </h2>
                        <span class="mm-stat-small">
                            {{ translate('All compare records') }}
                        </span>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-balance-scale"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-success">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">
                            {{ translate('Unique Products') }}
                        </div>
                        <h2 class="mm-stat-number">
                            {{ $uniqueProducts }}
                        </h2>
                        <span class="mm-stat-small">
                            {{ translate('Compared products') }}
                        </span>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-box"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-warning">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">
                            {{ translate('Unique Users') }}
                        </div>
                        <h2 class="mm-stat-number">
                            {{ $uniqueUsers }}
                        </h2>
                        <span class="mm-stat-small">
                            {{ translate('Users used compare') }}
                        </span>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-info">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">
                            {{ translate('Guest Compares') }}
                        </div>
                        <h2 class="mm-stat-number">
                            {{ $guestCompares }}
                        </h2>

                        <span class="mm-stat-small">
                            {{ translate('Guest compare activities') }}
                        </span>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-user-friends"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('compares.index') }}" id="filter-form">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <input type="text" class="form-control" name="search"
                                placeholder="{{ translate('Search by product or user') }}"
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <select class="form-control" name="sort_by">
                                <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>
                                    {{ translate('Latest First') }}</option>
                                <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>
                                    {{ translate('Oldest First') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-filter"></i> {{ translate('Filter') }}
                            </button>
                            <a href="{{ route('compares.index') }}" class="btn btn-secondary">
                                <i class="las la-undo-alt"></i> {{ translate('Reset') }}
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Action Bar -->
    <div class="mb-3" id="bulk-action-bar" style="display: none;">
        <div class="alert alert-primary">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <i class="las la-check-circle"></i>
                    <span id="selected-count">0</span> {{ translate('items selected') }}
                </div>
                <div class="col-md-6 text-md-right">
                    <button type="button" class="btn btn-sm btn-danger" onclick="showBulkDeleteModal()">
                        <i class="las la-trash"></i> {{ translate('Delete Selected') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="clearSelection()">
                        {{ translate('Clear') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Compares Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Compare Records') }}</h5>
            <span class="badge badge-primary ml-2">{{ $compares->total() }} {{ translate('Total') }}</span>
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
                            <th width="20%">{{ translate('User') }}</th>
                            <th width="30%">{{ translate('Product') }}</th>
                            <th width="15%">{{ translate('IP Address') }}</th>
                            <th width="15%">{{ translate('Added Date') }}</th>
                            <th width="10%">{{ translate('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($compares as $key => $compare)
                            <tr>
                                <td>
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="compare-checkbox" value="{{ $compare->id }}">
                                        <span class="aiz-square-check"></span>
                                    </label>
            </div>
            <td>
                {{ ($compares->currentPage() - 1) * $compares->perPage() + $key + 1 }}
        </div>
        <td>
            @if ($compare->user)
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm mr-2">
                        <img src="{{ uploaded_asset($compare->user->avatar) ?? asset('default/avatar.jpg') }}"
                            class="rounded-circle">
                    </div>
                    <div>
                        <div>{{ $compare->user->name }}</div>
                        <small class="text-muted">{{ $compare->user->email }}</small>
                    </div>
                </div>
            @else
                <span class="text-muted">{{ translate('Guest User') }}</span>
            @endif
    </div>
    <td>
        @if ($compare->product)
            <div class="d-flex align-items-center">
                <img src="{{ uploaded_asset($compare->product->thumbnail) }}" alt="{{ $compare->product->name }}"
                    style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;" class="mr-2">
                <div>
                    <div><strong>{{ $compare->product->name }}</strong></div>
                    <small class="text-muted">SKU: {{ $compare->product->inventory->sku ?? 'N/A' }}</small>
                </div>
            </div>
        @else
            <span class="text-danger">{{ translate('Product Not Found') }}</span>
        @endif
        </div>
    <td>{{ $compare->ip_address ?? 'N/A' }}</div>
    <td>{{ $compare->created_at->format('d M Y, h:i A') }}</div>
    <td>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-icon btn-danger" data-toggle="modal"
                data-target="#delete-single-modal"
                onclick="setDeleteForm('{{ route('compares.destroy', $compare->id) }}')"
                title="{{ translate('Delete') }}">
                <i class="las la-trash"></i>
            </button>
        </div>
        </div>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center">
                <div class="py-5">
                    <i class="las la-balance-scale fs-60 text-muted"></i>
                    <h5 class="text-muted mt-3">{{ translate('No compare records found') }}</h5>
                    <p class="text-muted">{{ translate('No products have been added to compare list yet.') }}</p>
                </div>
                </div>
        </tr>
        @endforelse
        </tbody>
        </table>
        </div>

        <div class="aiz-pagination mt-4">
            {{ $compares->links() }}
        </div>
        </div>
        </div>
    @endsection

    @section('modal')
        <!-- Bulk Delete Modal -->
        <div id="bulk-delete-modal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ translate('Delete Compare Records') }}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="las la-trash text-danger" style="font-size: 48px;"></i>
                        <h4 class="mt-2">{{ translate('Are you sure?') }}</h4>
                        <p id="bulk-delete-message">
                            {{ translate('Are you sure you want to delete selected compare records?') }}</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="button" class="btn btn-danger"
                            onclick="confirmBulkDelete()">{{ translate('Delete') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Single Delete Modal -->
        <div id="delete-single-modal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ translate('Delete Compare Record') }}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="las la-trash text-danger" style="font-size: 48px;"></i>
                        <h4 class="mt-2">{{ translate('Are you sure?') }}</h4>
                        <p>{{ translate('This compare record will be permanently deleted.') }}</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <form id="delete-single-form" method="POST" action="">
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
            let bulkSelectedIds = [];

            $(document).ready(function() {
                $('#select-all').on('change', function() {
                    $('.compare-checkbox').prop('checked', $(this).is(':checked'));
                    updateBulkActionBar();
                });

                $(document).on('change', '.compare-checkbox', function() {
                    updateBulkActionBar();
                });
            });

            function updateBulkActionBar() {
                var selected = $('.compare-checkbox:checked').length;
                if (selected > 0) {
                    $('#bulk-action-bar').show();
                    $('#selected-count').text(selected);
                } else {
                    $('#bulk-action-bar').hide();
                }
            }

            function clearSelection() {
                $('.compare-checkbox').prop('checked', false);
                $('#select-all').prop('checked', false);
                updateBulkActionBar();
            }

            function setDeleteForm(url) {
                $('#delete-single-form').attr('action', url);
            }

            function showBulkDeleteModal() {
                bulkSelectedIds = [];
                $('.compare-checkbox:checked').each(function() {
                    bulkSelectedIds.push($(this).val());
                });

                if (bulkSelectedIds.length > 0) {
                    $('#bulk-delete-message').text('{{ translate('Are you sure you want to delete') }} ' + bulkSelectedIds
                        .length + ' {{ translate('compare records?') }}');
                    $('#bulk-delete-modal').modal('show');
                } else {
                    AIZ.plugins.notify('warning', '{{ translate('Please select at least one item') }}');
                }
            }

            function confirmBulkDelete() {
                $('#bulk-delete-modal').modal('hide');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('compares.bulk-delete') }}",
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
                    error: function() {
                        AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    }
                });
            }

            function confirmClearAll() {
                if (confirm('{{ translate('Are you sure you want to clear ALL compare records? This cannot be undone.') }}')) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "{{ route('compares.clear-all') }}",
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
                            AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                        }
                    });
                }
            }
        </script>
    @endsection
