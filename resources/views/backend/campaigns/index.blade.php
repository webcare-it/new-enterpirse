@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Campaign Management') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('campaigns.create') }}" class="btn btn-primary">
                    <i class="las la-plus"></i> {{ translate('Add New Campaign') }}
                </a>
                <a href="{{ route('campaigns.export') }}" class="btn btn-success">
                    <i class="las la-file-excel"></i> {{ translate('Export') }}
                </a>
            </div>
        </div>
    </div>

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

        .mm-bg-info {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
        }

        .mm-bg-secondary {
            background: linear-gradient(135deg, #64748b, #334155);
        }

        .mm-bg-warning {
            background: linear-gradient(135deg, #f59e0b, #f97316);
        }

        .mm-bg-purple {
            background: linear-gradient(135deg, #9333ea, #6366f1);
        }
    </style>

    <!-- Statistics Cards -->
    <div class="row mb-4">

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-primary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">
                            {{ translate('Total Campaigns') }}
                        </div>

                        <h2 class="mm-stat-number">
                            {{ $totalCampaigns }}
                        </h2>

                        <span class="mm-stat-small">
                            {{ translate('All campaign records') }}
                        </span>
                    </div>

                    <div class="mm-stat-icon">
                        <i class="las la-bullhorn"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-success">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">
                            {{ translate('Running Campaigns') }}
                        </div>

                        <h2 class="mm-stat-number">
                            {{ $runningCampaigns }}
                        </h2>

                        <span class="mm-stat-small">
                            {{ translate('Currently running') }}
                        </span>
                    </div>

                    <div class="mm-stat-icon">
                        <i class="las la-play-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-info">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">
                            {{ translate('Upcoming Campaigns') }}
                        </div>

                        <h2 class="mm-stat-number">
                            {{ $upcomingCampaigns }}
                        </h2>

                        <span class="mm-stat-small">
                            {{ translate('Scheduled campaigns') }}
                        </span>
                    </div>

                    <div class="mm-stat-icon">
                        <i class="las la-calendar-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-secondary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">
                            {{ translate('Ended Campaigns') }}
                        </div>

                        <h2 class="mm-stat-number">
                            {{ $endedCampaigns }}
                        </h2>

                        <span class="mm-stat-small">
                            {{ translate('Completed campaigns') }}
                        </span>
                    </div>

                    <div class="mm-stat-icon">
                        <i class="las la-flag-checkered"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Second Row Statistics -->
    <div class="row mb-4">

        <div class="col-md-4 mb-3">
            <div class="mm-stat-card mm-bg-warning">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">
                            {{ translate('Active Campaigns') }}
                        </div>

                        <h2 class="mm-stat-number">
                            {{ $activeCampaigns }}
                        </h2>

                        <span class="mm-stat-small">
                            {{ translate('Campaigns marked active') }}
                        </span>
                    </div>

                    <div class="mm-stat-icon">
                        <i class="las la-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="mm-stat-card mm-bg-purple">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">
                            {{ translate('Products in Campaigns') }}
                        </div>

                        <h2 class="mm-stat-number">
                            {{ $totalProductsInCampaigns }}
                        </h2>

                        <span class="mm-stat-small">
                            {{ translate('Total campaign products') }}
                        </span>
                    </div>

                    <div class="mm-stat-icon">
                        <i class="las la-box"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="mm-stat-card mm-bg-success">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">
                            {{ translate('Total Records') }}
                        </div>

                        <h2 class="mm-stat-number">
                            {{ $campaigns->total() }}
                        </h2>

                        <span class="mm-stat-small">
                            {{ translate('Visible campaign records') }}
                        </span>
                    </div>

                    <div class="mm-stat-icon">
                        <i class="las la-database"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Filter Section -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Filter Campaigns') }}</h5>
            <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#filterCollapse">
                <i class="las la-filter"></i> {{ translate('Show/Hide Filters') }}
            </button>
        </div>
        <div class="collapse {{ request()->has('search') || request()->has('status') || request()->has('sort_by') ? 'show' : '' }}"
            id="filterCollapse">
            <div class="card-body">
                <form method="GET" action="{{ route('campaigns.index') }}" id="filter-form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ translate('Search') }}</label>
                                <input type="text" class="form-control" name="search"
                                    placeholder="{{ translate('Search by campaign name') }}"
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ translate('Status') }}</label>
                                <select class="form-control" name="status">
                                    <option value="">{{ translate('All Status') }}</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                        {{ translate('Active') }}</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                        {{ translate('Inactive') }}</option>
                                    <option value="running" {{ request('status') == 'running' ? 'selected' : '' }}>
                                        {{ translate('Running') }}</option>
                                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>
                                        {{ translate('Upcoming') }}</option>
                                    <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>
                                        {{ translate('Ended') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ translate('Sort By') }}</label>
                                <select class="form-control" name="sort_by">
                                    <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>
                                        {{ translate('Latest') }}</option>
                                    <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>
                                        {{ translate('Oldest') }}</option>
                                    <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>
                                        {{ translate('Name (A-Z)') }}</option>
                                    <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>
                                        {{ translate('Name (Z-A)') }}</option>
                                    <option value="start_date_asc"
                                        {{ request('sort_by') == 'start_date_asc' ? 'selected' : '' }}>
                                        {{ translate('Start Date (Earliest)') }}</option>
                                    <option value="start_date_desc"
                                        {{ request('sort_by') == 'start_date_desc' ? 'selected' : '' }}>
                                        {{ translate('Start Date (Latest)') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="las la-search"></i> {{ translate('Search') }}
                                    </button>
                                    <a href="{{ route('campaigns.index') }}" class="btn btn-secondary w-100">
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
                <div class="col-md-6">
                    <i class="las la-check-circle"></i>
                    <span id="selected-count">0</span> {{ translate('campaigns selected') }}
                </div>
                <div class="col-md-6 text-md-right">
                    <select class="form-control-sm" id="bulk-action">
                        <option value="">{{ translate('Bulk Action') }}</option>
                        <option value="delete">{{ translate('Delete') }}</option>
                        <option value="active">{{ translate('Activate') }}</option>
                        <option value="inactive">{{ translate('Deactivate') }}</option>
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

    <!-- Campaigns Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('All Campaigns') }}</h5>
            <span class="badge badge-primary ml-2">{{ $campaigns->total() }} {{ translate('Total') }}</span>
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
                            <th width="5%">SL</th>
                            <th width="10%">{{ translate('Image') }}</th>
                            <th width="20%">{{ translate('Campaign Name') }}</th>
                            <th width="10%">{{ translate('Discount') }}</th>
                            <th width="15%">{{ translate('Duration') }}</th>
                            <th width="8%">{{ translate('Products') }}</th>
                            <th width="10%">{{ translate('Status') }}</th>
                            <th width="12%">{{ translate('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaigns as $key => $campaign)
                            <tr>
                                <td>
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="campaign-checkbox" value="{{ $campaign->id }}">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </td>

                                <td>
                                    {{ ($campaigns->currentPage() - 1) * $campaigns->perPage() + $key + 1 }}
                                </td>

                                <td>
                                    @if ($campaign->image)
                                        <img src="{{ uploaded_asset($campaign->image) }}" alt="{{ $campaign->name }}"
                                            style="width:50px;height:50px;object-fit:cover;border-radius:8px;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center"
                                            style="width:50px;height:50px;border-radius:8px;">
                                            <i class="las la-image text-muted fs-24"></i>
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <strong class="d-block">{{ $campaign->name }}</strong>
                                    <small class="text-muted">{{ $campaign->slug }}</small>
                                    @if ($campaign->description)
                                        <div class="small text-muted mt-1">{{ Str::limit($campaign->description, 50) }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    @if ($campaign->discount_amount)
                                        <span class="badge badge-primary">
                                            @if ($campaign->discount_type == 'percent')
                                                {{ $campaign->discount_amount }}% OFF
                                            @else
                                                ৳{{ number_format($campaign->discount_amount, 2) }} OFF
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-muted">{{ translate('N/A') }}</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="small">
                                        <i class="las la-calendar-alt"></i>
                                        {{ \Carbon\Carbon::parse($campaign->start_date)->format('d M Y') }}
                                    </div>
                                    <div class="small">
                                        <i class="las la-calendar-check"></i>
                                        {{ \Carbon\Carbon::parse($campaign->end_date)->format('d M Y') }}
                                    </div>

                                    @if ($campaign->isRunning())
                                        <span class="badge badge-success mt-1">{{ translate('Running') }}</span>
                                    @elseif($campaign->start_date > now())
                                        <span class="badge badge-info mt-1">{{ translate('Upcoming') }}</span>
                                    @elseif($campaign->end_date < now())
                                        <span class="badge badge-secondary mt-1">{{ translate('Ended') }}</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge badge-info">
                                        {{ $campaign->products->count() }} {{ translate('Products') }}
                                    </span>
                                </td>

                                <td>
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" onchange="update_status(this, '{{ $campaign->id }}')"
                                            {{ $campaign->status ? 'checked' : '' }}>
                                        <span></span>
                                    </label>
                                </td>

                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('campaigns.show', $campaign->id) }}"
                                            class="btn btn-sm btn-icon btn-info" title="{{ translate('View') }}">
                                            <i class="las la-eye"></i>
                                        </a>

                                        <a href="{{ route('campaigns.edit', $campaign->id) }}"
                                            class="btn btn-sm btn-icon btn-primary" title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>

                                        <button type="button" class="btn btn-sm btn-icon btn-danger" data-toggle="modal"
                                            data-target="#delete-single-modal"
                                            onclick="setDeleteForm('{{ route('campaigns.destroy', $campaign->id) }}')"
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
                                        <i class="las la-campaign fs-60 text-muted"></i>
                                        <h5 class="text-muted mt-3">{{ translate('No campaigns found') }}</h5>
                                        <p class="text-muted">
                                            {{ translate('Create your first campaign to get started.') }}</p>
                                        <a href="{{ route('campaigns.create') }}" class="btn btn-primary">
                                            <i class="las la-plus"></i> {{ translate('Add New Campaign') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="aiz-pagination mt-4">
                {{ $campaigns->links() }}
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- Bulk Action Modal -->
    <div id="bulk-action-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulk-action-title">{{ translate('Bulk Action') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="las la-question-circle" style="font-size: 48px;" id="bulk-icon"></i>
                    <h4 class="mt-2" id="bulk-title">{{ translate('Confirm Action') }}</h4>
                    <p id="bulk-message">{{ translate('Are you sure you want to perform this action?') }}</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="button" class="btn" id="bulk-confirm-btn"
                        onclick="confirmBulkAction()">{{ translate('Confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Single Delete Modal -->
    <div id="delete-single-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Delete Campaign') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="las la-trash text-danger" style="font-size: 48px;"></i>
                    <h4 class="mt-2">{{ translate('Are you sure?') }}</h4>
                    <p>{{ translate('This campaign will be permanently deleted. All associated products will be removed.') }}
                    </p>
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
        let bulkActionType = null;
        let bulkSelectedIds = [];

        $(document).ready(function() {
            // Select all checkbox functionality
            $('#select-all').on('change', function() {
                $('.campaign-checkbox').prop('checked', $(this).is(':checked'));
                updateBulkActionBar();
            });

            // Individual checkbox change
            $(document).on('change', '.campaign-checkbox', function() {
                updateBulkActionBar();
            });
        });

        function updateBulkActionBar() {
            var selected = $('.campaign-checkbox:checked').length;
            if (selected > 0) {
                $('#bulk-action-bar').show();
                $('#selected-count').text(selected);
            } else {
                $('#bulk-action-bar').hide();
            }
        }

        function clearSelection() {
            $('.campaign-checkbox').prop('checked', false);
            $('#select-all').prop('checked', false);
            updateBulkActionBar();
        }

        function setDeleteForm(url) {
            $('#delete-single-form').attr('action', url);
        }

        function showBulkActionModal() {
            bulkSelectedIds = [];
            $('.campaign-checkbox:checked').each(function() {
                bulkSelectedIds.push($(this).val());
            });

            if (bulkSelectedIds.length > 0) {
                bulkActionType = $('#bulk-action').val();
                if (!bulkActionType) {
                    AIZ.plugins.notify('warning', '{{ translate('Please select an action') }}');
                    return;
                }

                var title = '',
                    message = '',
                    btnClass = '',
                    icon = '';

                if (bulkActionType == 'delete') {
                    title = '{{ translate('Delete Campaigns') }}';
                    message = '{{ translate('Are you sure you want to delete') }} ' + bulkSelectedIds.length +
                        ' {{ translate('selected campaigns? This action cannot be undone.') }}';
                    btnClass = 'btn-danger';
                    icon = 'las la-trash text-danger';
                } else if (bulkActionType == 'active') {
                    title = '{{ translate('Activate Campaigns') }}';
                    message = '{{ translate('Are you sure you want to activate') }} ' + bulkSelectedIds.length +
                        ' {{ translate('selected campaigns?') }}';
                    btnClass = 'btn-success';
                    icon = 'las la-check-circle text-success';
                } else if (bulkActionType == 'inactive') {
                    title = '{{ translate('Deactivate Campaigns') }}';
                    message = '{{ translate('Are you sure you want to deactivate') }} ' + bulkSelectedIds.length +
                        ' {{ translate('selected campaigns?') }}';
                    btnClass = 'btn-warning';
                    icon = 'las la-times-circle text-warning';
                }

                $('#bulk-action-title').text(title);
                $('#bulk-title').text(title);
                $('#bulk-message').text(message);
                $('#bulk-confirm-btn').removeClass('btn-success btn-danger btn-warning').addClass(btnClass);
                $('#bulk-icon').removeClass().addClass(icon + ' fs-40');

                $('#bulk-action-modal').modal('show');
            } else {
                AIZ.plugins.notify('warning', '{{ translate('Please select at least one campaign') }}');
            }
        }

        function confirmBulkAction() {
            $('#bulk-action-modal').modal('hide');

            if (bulkActionType == 'delete') {
                bulkDelete(bulkSelectedIds);
            } else if (bulkActionType == 'active') {
                bulkUpdateStatus(bulkSelectedIds, 'active');
            } else if (bulkActionType == 'inactive') {
                bulkUpdateStatus(bulkSelectedIds, 'inactive');
            }
        }

        function bulkDelete(ids) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('campaigns.bulk-delete') }}",
                data: {
                    ids: ids
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

        function bulkUpdateStatus(ids, status) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('campaigns.bulk-status') }}",
                data: {
                    ids: ids,
                    status: status
                },
                beforeSend: function() {
                    AIZ.plugins.notify('info', '{{ translate('Processing...') }}');
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


        function update_status(el, product_id) {
            var status = $(el).is(':checked') ? 1 : 0;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('campaigns.update-status') }}",
                data: {
                    campaign_id: product_id,
                    status: status
                },
                success: function(response) {
                    AIZ.plugins.notify('success', response.message);
                },
                error: function(xhr) {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }
    </script>
@endsection
