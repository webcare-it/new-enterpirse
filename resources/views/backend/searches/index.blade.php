@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Search Analytics') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('searches.export') }}" class="btn btn-sm btn-success">
                    <i class="las la-file-excel"></i> {{ translate('Export') }}
                </a>
                <button type="button" class="btn btn-sm btn-danger" onclick="confirmClearAll()">
                    <i class="las la-trash-alt"></i> {{ translate('Clear All') }}
                </button>
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

        .toggle-btn {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .toggle-btn:hover {
            transform: translateX(5px);
        }

        .rotate-icon {
            transition: transform 0.3s ease;
        }

        .rotate-icon.rotated {
            transform: rotate(90deg);
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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-primary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">{{ translate('Total Searches') }}</div>
                        <h2 class="mm-stat-number">{{ $totalSearches }}</h2>
                        <span class="mm-stat-small">{{ translate('All search records') }}</span>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-search"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-success">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">{{ translate('Unique Keywords') }}</div>
                        <h2 class="mm-stat-number">{{ $uniqueKeywords }}</h2>
                        <span class="mm-stat-small">{{ translate('Different searched keywords') }}</span>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-tag"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-warning">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">{{ translate('Avg Searches/Keyword') }}</div>
                        <h2 class="mm-stat-number">{{ $avgSearchesPerKeyword }}</h2>
                        <span class="mm-stat-small">{{ translate('Average search count') }}</span>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mm-stat-card mm-bg-info">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mm-stat-title">{{ translate('This Week Searches') }}</div>
                        <h2 class="mm-stat-number">{{ $searchesThisWeek }}</h2>
                        <span class="mm-stat-small">{{ translate('Searches this week') }}</span>
                    </div>
                    <div class="mm-stat-icon">
                        <i class="las la-calendar-week"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Searches Card (Collapsible - Hidden by Default) -->
    <div class="card mb-4">
        <div class="card-header toggle-btn" onclick="toggleTopSearches()">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 h6">
                    <i class="las la-chart-line"></i> {{ translate('Top 10 Most Searched Keywords') }}
                </h5>
                <i class="las la-chevron-right rotate-icon" id="topSearchesIcon"></i>
            </div>
        </div>
        <div class="card-body" id="topSearchesContent" style="display: none;">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="40%">{{ translate('Keyword') }}</th>
                            <th width="15%">{{ translate('Search Count') }}</th>
                            <th width="30%">{{ translate('Percentage') }}</th>
                            <th width="10%">{{ translate('Last Searched') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topSearches as $index => $search)
                            <tr>
                                <td>{{ $index + 1 }}
            </div>
            <td><strong>{{ $search->query }}</strong>
        </div>
        <td><span class="badge badge-primary">{{ $search->count }}</span>
    </div>
    <td>
        @php
            $percentage = $totalSearches > 0 ? round(($search->count / $totalSearches) * 100, 1) : 0;
        @endphp
        <div class="d-flex align-items-center">
            <div class="progress flex-grow-1" style="height: 8px;">
                <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
            </div>
            <span class="ml-2">{{ $percentage }}%</span>
        </div>
        </div>
    <td>{{ $search->updated_at->diffForHumans() }}</div>
        </tr>
        @endforeach
        </tbody>
        </table>
        </div>
        </div>
        </div>

        <!-- Search Filter -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('searches.index') }}" id="filter-form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control" name="search"
                                    placeholder="{{ translate('Search by keyword') }}" value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <select class="form-control" name="sort_by">
                                    <option value="popular" {{ request('sort_by') == 'popular' ? 'selected' : '' }}>
                                        {{ translate('Most Popular') }}</option>
                                    <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>
                                        {{ translate('Latest First') }}</option>
                                    <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>
                                        {{ translate('Oldest First') }}</option>
                                    <option value="query_asc" {{ request('sort_by') == 'query_asc' ? 'selected' : '' }}>
                                        {{ translate('Keyword (A-Z)') }}</option>
                                    <option value="query_desc" {{ request('sort_by') == 'query_desc' ? 'selected' : '' }}>
                                        {{ translate('Keyword (Z-A)') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="las la-filter"></i> {{ translate('Filter') }}
                                </button>
                                <a href="{{ route('searches.index') }}" class="btn btn-secondary">
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

        <!-- Searches Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Search Keywords') }}</h5>
                <span class="badge badge-primary ml-2">{{ $searches->total() }} {{ translate('Total') }}</span>
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
                                <th width="40%">{{ translate('Search Keyword') }}</th>
                                <th width="15%">{{ translate('Search Count') }}</th>
                                <th width="20%">{{ translate('First Searched') }}</th>
                                <th width="15%">{{ translate('Last Searched') }}</th>
                                <th width="10%">{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($searches as $key => $search)
                                <tr>
                                    <td>
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="search-checkbox" value="{{ $search->id }}">
                                            <span class="aiz-square-check"></span>
                                        </label>
                </div>
    <td>
        {{ ($searches->currentPage() - 1) * $searches->perPage() + $key + 1 }}
        </div>
    <td><strong>{{ $search->query }}</strong></div>
    <td><span class="badge badge-primary">{{ $search->count }}</span></div>
    <td>{{ $search->created_at->format('d M Y, h:i A') }}</div>
    <td>{{ $search->updated_at->format('d M Y, h:i A') }}</div>
    <td>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-icon btn-danger" data-toggle="modal"
                data-target="#delete-single-modal"
                onclick="setDeleteForm('{{ route('searches.destroy', $search->id) }}')"
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
                    <i class="las la-search fs-60 text-muted"></i>
                    <h5 class="text-muted mt-3">{{ translate('No search data found') }}</h5>
                    <p class="text-muted">{{ translate('Customers have not searched for anything yet.') }}</p>
                </div>
                </div>
        </tr>
        @endforelse
        </tbody>
        </table>
        </div>

        <div class="aiz-pagination mt-4">
            {{ $searches->links() }}
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
                        <h5 class="modal-title">{{ translate('Delete Search Records') }}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="las la-trash text-danger" style="font-size: 48px;"></i>
                        <h4 class="mt-2">{{ translate('Are you sure?') }}</h4>
                        <p id="bulk-delete-message">
                            {{ translate('Are you sure you want to delete selected search records?') }}</p>
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
                        <h5 class="modal-title">{{ translate('Delete Search Record') }}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="las la-trash text-danger" style="font-size: 48px;"></i>
                        <h4 class="mt-2">{{ translate('Are you sure?') }}</h4>
                        <p>{{ translate('This search record will be permanently deleted.') }}</p>
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
                    $('.search-checkbox').prop('checked', $(this).is(':checked'));
                    updateBulkActionBar();
                });

                $(document).on('change', '.search-checkbox', function() {
                    updateBulkActionBar();
                });
            });

            function updateBulkActionBar() {
                var selected = $('.search-checkbox:checked').length;
                if (selected > 0) {
                    $('#bulk-action-bar').show();
                    $('#selected-count').text(selected);
                } else {
                    $('#bulk-action-bar').hide();
                }
            }

            function clearSelection() {
                $('.search-checkbox').prop('checked', false);
                $('#select-all').prop('checked', false);
                updateBulkActionBar();
            }

            function setDeleteForm(url) {
                $('#delete-single-form').attr('action', url);
            }

            function toggleTopSearches() {
                $('#topSearchesContent').slideToggle(300);
                $('#topSearchesIcon').toggleClass('rotated');
            }

            function showBulkDeleteModal() {
                bulkSelectedIds = [];
                $('.search-checkbox:checked').each(function() {
                    bulkSelectedIds.push($(this).val());
                });

                if (bulkSelectedIds.length > 0) {
                    $('#bulk-delete-message').text('{{ translate('Are you sure you want to delete') }} ' + bulkSelectedIds
                        .length + ' {{ translate('search records?') }}');
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
                    url: "{{ route('searches.bulk-delete') }}",
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
                if (confirm('{{ translate('Are you sure you want to clear ALL search records? This cannot be undone.') }}')) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "{{ route('searches.clear-all') }}",
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
