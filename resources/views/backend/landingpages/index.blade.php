@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Landing Page Management') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('landingpages.create') }}" class="btn btn-primary">
                    <i class="las la-plus"></i> {{ translate('Add New') }}
                </a>
                <a href="{{ route('landingpages.export') }}" class="btn btn-success">
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

        .stat-label {
            font-size: 14px;
            font-weight: 700;
            opacity: .96;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 33px;
            font-weight: 900;
            margin: 0 0 8px;
            line-height: 1.1;
            color: #fff;
            letter-spacing: -.5px;
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

        .mm-card-blue {
            background: linear-gradient(135deg, #405cf5, #11b4d8);
        }

        .mm-card-green {
            background: linear-gradient(135deg, #0f9f52, #22c55e);
        }

        .mm-card-orange {
            background: linear-gradient(135deg, #ff9f1c, #ff6b00);
        }
    </style>

    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="mm-card mm-card-blue">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Total Landing Pages') }}</div>
                    <div class="stat-number">{{ $totalPages }}</div>
                    <div class="stat-small">{{ translate('All landing pages') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-file-alt"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-3">
            <div class="mm-card mm-card-green">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Published Pages') }}</div>
                    <div class="stat-number">{{ $publishedPages }}</div>
                    <div class="stat-small">{{ translate('Currently live pages') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-3">
            <div class="mm-card mm-card-orange">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Draft Pages') }}</div>
                    <div class="stat-number">{{ $draftPages }}</div>
                    <div class="stat-small">{{ translate('Unpublished pages') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-pen-fancy"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Filter Landing Pages') }}</h5>
            <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#filterCollapse">
                <i class="las la-filter"></i> {{ translate('Show/Hide Filters') }}
            </button>
        </div>
        <div class="collapse {{ request()->has('search') || request()->has('status') || request()->has('sort_by') ? 'show' : '' }}"
            id="filterCollapse">
            <div class="card-body">
                <form method="GET" action="{{ route('landingpages.index') }}" id="filter-form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ translate('Search') }}</label>
                                <input type="text" class="form-control" name="search"
                                    placeholder="{{ translate('Search by name or slug') }}"
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ translate('Status') }}</label>
                                <select class="form-control" name="status">
                                    <option value="">{{ translate('All Status') }}</option>
                                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>
                                        {{ translate('Published') }}</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>
                                        {{ translate('Draft') }}</option>
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
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="las la-search"></i> {{ translate('Filter') }}
                                    </button>
                                    <a href="{{ route('landingpages.index') }}" class="btn btn-secondary w-100">
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
                    <span id="selected-count">0</span> {{ translate('items selected') }}
                </div>
                <div class="col-md-6 text-md-right">
                    <select class="form-control-sm" id="bulk-action">
                        <option value="">{{ translate('Bulk Action') }}</option>
                        <option value="delete">{{ translate('Delete') }}</option>
                        <option value="published">{{ translate('Publish') }}</option>
                        <option value="draft">{{ translate('Move to Draft') }}</option>
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

    <!-- Landing Pages Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('All Landing Pages') }}</h5>
            <span class="badge badge-primary ml-2">{{ $landingPages->total() }} {{ translate('Total') }}</span>
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
                            <th width="15%">{{ translate('Image') }}</th>
                            <th width="20%">{{ translate('Name') }}</th>
                            <th width="15%">{{ translate('Slug') }}</th>
                            <th width="10%">{{ translate('Status') }}</th>
                            <th width="15%">{{ translate('Created At') }}</th>
                            <th width="12%">{{ translate('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($landingPages as $key => $page)
                            <tr>
                                <td>
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="landingpage-checkbox" value="{{ $page->id }}">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </td>
                                <td>
                                    {{ ($landingPages->currentPage() - 1) * $landingPages->perPage() + $key + 1 }}
                                </td>
                                <td>
                                    @if ($page->banner_image)
                                        <img src="{{ uploaded_asset($page->banner_image) }}" alt="{{ $page->name }}"
                                            class="img-fluid"
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center"
                                            style="width: 60px; height: 60px; border-radius: 8px;">
                                            <i class="las la-image text-muted fs-24"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <strong class="d-block">{{ $page->name }}</strong>
                                        @if ($page->title)
                                            <small class="text-muted">{{ Str::limit($page->title, 40) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="d-block">{{ $page->slug }}</span>
                                        <small class="text-muted">
                                            <a href="{{ route('landing.product.preview', $page->slug) }}"
                                                target="_blank">
                                                {{ translate('Website View') }}
                                            </a>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group mb-0">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" class="toggle-status" data-id="{{ $page->id }}"
                                                {{ $page->is_published ? 'checked' : '' }}>
                                            <span></span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="d-block">{{ $page->created_at->format('d M Y') }}</span>
                                        <small class="text-muted">{{ $page->created_at->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">

                                        <a href="{{ route('landing.product.preview', $page->slug) }}" target="_blank"
                                            class="btn btn-sm btn-icon btn-success"
                                            title="{{ translate('Website View') }}">
                                            <i class="las la-hand-pointer"></i>
                                        </a>

                                        <a href="{{ route('landingpages.preview', $page->id) }}"
                                            class="btn btn-sm btn-icon btn-info" target="_blank"
                                            title="{{ translate('Preview') }}">
                                            <i class="las la-eye"></i>
                                        </a>
                                        <a href="{{ route('landingpages.edit', $page->id) }}"
                                            class="btn btn-sm btn-icon btn-primary" title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>

                                        <button type="button" class="btn btn-sm btn-icon btn-danger" data-toggle="modal"
                                            data-target="#delete-single-modal"
                                            onclick="setDeleteForm('{{ route('landingpages.destroy', $page->id) }}')"
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
                                        <i class="las la-file-alt fs-60 text-muted"></i>
                                        <h5 class="text-muted mt-3">{{ translate('No landing pages found') }}</h5>
                                        <p class="text-muted">
                                            {{ translate('Create your first landing page to get started.') }}
                                        </p>
                                        <a href="{{ route('landingpages.create') }}" class="btn btn-primary">
                                            <i class="las la-plus"></i> {{ translate('Add New') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="aiz-pagination mt-4">
                {{ $landingPages->links() }}
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
                    <h5 class="modal-title">{{ translate('Delete Landing Page') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="las la-trash text-danger" style="font-size: 48px;"></i>
                    <h4 class="mt-2">{{ translate('Are you sure?') }}</h4>
                    <p>{{ translate('This landing page will be permanently deleted.') }}</p>
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
            $('#select-all').on('change', function() {
                $('.landingpage-checkbox').prop('checked', $(this).is(':checked'));
                updateBulkActionBar();
            });

            $(document).on('change', '.landingpage-checkbox', function() {
                updateBulkActionBar();
            });

            // Toggle status
            $('.toggle-status').on('change', function() {
                var pageId = $(this).data('id');
                var $checkbox = $(this);
                var currentState = $checkbox.is(':checked');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('landingpages.toggle-status', '') }}/" + pageId,
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                        } else {
                            AIZ.plugins.notify('danger', response.message);
                            $checkbox.prop('checked', currentState);
                        }
                    },
                    error: function(xhr) {
                        AIZ.plugins.notify('danger',
                            '{{ translate('Something went wrong') }}');
                        $checkbox.prop('checked', currentState);
                    }
                });
            });
        });

        function setDeleteForm(url) {
            $('#delete-single-form').attr('action', url);
        }

        function updateBulkActionBar() {
            var selected = $('.landingpage-checkbox:checked').length;
            if (selected > 0) {
                $('#bulk-action-bar').show();
                $('#selected-count').text(selected);
            } else {
                $('#bulk-action-bar').hide();
            }
        }

        function clearSelection() {
            $('.landingpage-checkbox').prop('checked', false);
            $('#select-all').prop('checked', false);
            updateBulkActionBar();
        }

        function showBulkActionModal() {
            bulkSelectedIds = [];
            $('.landingpage-checkbox:checked').each(function() {
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
                    title = '{{ translate('Delete Landing Pages') }}';
                    message = '{{ translate('Are you sure you want to delete') }} ' + bulkSelectedIds.length +
                        ' {{ translate('selected landing pages? This action cannot be undone.') }}';
                    btnClass = 'btn-danger';
                    icon = 'las la-trash text-danger';
                } else if (bulkActionType == 'published') {
                    title = '{{ translate('Publish Landing Pages') }}';
                    message = '{{ translate('Are you sure you want to publish') }} ' + bulkSelectedIds.length +
                        ' {{ translate('selected landing pages?') }}';
                    btnClass = 'btn-success';
                    icon = 'las la-check-circle text-success';
                } else if (bulkActionType == 'draft') {
                    title = '{{ translate('Move to Draft') }}';
                    message = '{{ translate('Are you sure you want to move') }} ' + bulkSelectedIds.length +
                        ' {{ translate('selected landing pages to draft?') }}';
                    btnClass = 'btn-warning';
                    icon = 'las la-pen-fancy text-warning';
                }

                $('#bulk-action-title').text(title);
                $('#bulk-title').text(title);
                $('#bulk-message').text(message);
                $('#bulk-confirm-btn').removeClass('btn-success btn-danger btn-warning').addClass(btnClass);
                $('#bulk-icon').removeClass().addClass(icon + ' fs-40');

                $('#bulk-action-modal').modal('show');
            } else {
                AIZ.plugins.notify('warning', '{{ translate('Please select at least one landing page') }}');
            }
        }

        function confirmBulkAction() {
            $('#bulk-action-modal').modal('hide');

            if (bulkActionType == 'delete') {
                bulkDelete(bulkSelectedIds);
            } else if (bulkActionType == 'published') {
                bulkUpdateStatus(bulkSelectedIds, 'published');
            } else if (bulkActionType == 'draft') {
                bulkUpdateStatus(bulkSelectedIds, 'draft');
            }
        }

        function bulkDelete(ids) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('landingpages.bulk-delete') }}",
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
                url: "{{ route('landingpages.bulk-status') }}",
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
    </script>

    <style>
        .gap-2 {
            gap: 0.5rem;
        }

        .fs-40 {
            font-size: 40px;
        }

        .fs-60 {
            font-size: 60px;
        }
    </style>
@endsection
