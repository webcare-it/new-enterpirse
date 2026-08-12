@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0 h6">{{ translate('Product Reviews') }}</h5>
            </div>
        </div>
    </div>

    <!-- Advanced Filter Card -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Advanced Filters') }}</h5>
            <button type="button" class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#filterCollapse">
                <i class="las la-filter"></i> Filter
            </button>
        </div>
        <div class="collapse {{ request()->has('search') || request()->has('status') || request()->has('rating') || request()->has('date_from') ? 'show' : '' }}"
            id="filterCollapse">
            <div class="card-body">
                <form method="GET" action="{{ route('reviews.index') }}" id="filter-form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ translate('Search') }}</label>
                                <input type="text" class="form-control" name="search"
                                    placeholder="{{ translate('Search by product name, customer name or comment') }}"
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ translate('Status') }}</label>
                                <select class="form-control" name="status">
                                    <option value="">{{ translate('All Status') }}</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                        {{ translate('Pending') }}</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                        {{ translate('Approved') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ translate('Rating') }}</label>
                                <select class="form-control" name="rating">
                                    <option value="">{{ translate('All Ratings') }}</option>
                                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Star</option>
                                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Star</option>
                                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Star</option>
                                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Star</option>
                                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ translate('Date From') }}</label>
                                <input type="date" class="form-control" name="date_from"
                                    value="{{ request('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ translate('Date To') }}</label>
                                <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-search"></i> {{ translate('Search') }}
                            </button>
                            <a href="{{ route('reviews.index') }}" class="btn btn-secondary">
                                <i class="las la-undo-alt"></i> {{ translate('Reset') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('All Reviews') }}</h5>
            <span class="badge badge-primary ml-2">{{ $reviews->total() ?? count($reviews) }}
                {{ translate('Total') }}</span>
        </div>
        <div class="card-body">

            <!-- Bulk Action Bar -->
            <div class="mb-3" id="bulk-action-bar" style="display: none;">
                <div class="alert alert-primary">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <i class="las la-check-circle"></i>
                            <span id="selected-count">0</span> {{ translate('reviews selected') }}
                        </div>
                        <div class="col-md-6 text-md-right">
                            <select class="form-control-sm" id="bulk-action">
                                <option value="">{{ translate('Bulk Action') }}</option>
                                <option value="approve">{{ translate('Approve Selected') }}</option>
                                <option value="disapprove">{{ translate('Disapprove Selected') }}</option>
                                <option value="delete">{{ translate('Delete Selected') }}</option>
                            </select>
                            <button type="button" class="btn btn-sm btn-primary" onclick="showBulkActionModal()">
                                {{ translate('Apply') }}
                                button>
                                <button type="button" class="btn btn-sm btn-secondary" onclick="clearSelection()">
                                    {{ translate('Clear') }}
                                </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="bg-gradient-primary text-white rounded p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ translate('Average Rating') }}</h6>
                                <h2 class="mb-0">{{ number_format($averageRating ?? 0, 1) }} <small>/5</small></h2>
                            </div>
                            <div class="fs-40">
                                <i class="las la-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-gradient-success text-white rounded p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ translate('Total Reviews') }}</h6>
                                <h2 class="mb-0">{{ $totalReviews ?? count($reviews) }}</h2>
                            </div>
                            <div class="fs-40">
                                <i class="las la-comments"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-gradient-warning text-white rounded p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ translate('Pending Approval') }}</h6>
                                <h2 class="mb-0">{{ $pendingReviews ?? 0 }}</h2>
                            </div>
                            <div class="fs-40">
                                <i class="las la-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-gradient-info text-white rounded p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ translate('5 Star Reviews') }}</h6>
                                <h2 class="mb-0">{{ $fiveStarReviews ?? 0 }}</h2>
                            </div>
                            <div class="fs-40">
                                <i class="las la-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rating Distribution Pie Chart & Recent Activity Bar Chart -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">{{ translate('Rating Distribution') }}</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="ratingPieChart" style="max-height: 300px; width: 100%;"></canvas>
                            <div class="mt-3">
                                @for ($i = 5; $i >= 1; $i--)
                                    @php $count = $ratingCounts[$i] ?? 0; @endphp
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="w-10">{{ $i }} <i class="las la-star text-warning"></i>
                                        </div>
                                        <div class="w-75 mx-2">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-warning"
                                                    style="width: {{ $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0 }}%">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-15">{{ $count }}</div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">{{ translate('Recent Activity') }}</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="activityBarChart" style="max-height: 300px; width: 100%;"></canvas>
                            <div class="mt-3">
                                <div class="d-flex justify-content-between">
                                    <div>{{ translate('This Week') }}</div>
                                    <div class="font-weight-bold">{{ $reviewsThisWeek ?? 0 }}</div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div>{{ translate('This Month') }}</div>
                                    <div class="font-weight-bold">{{ $reviewsThisMonth ?? 0 }}</div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div>{{ translate('Approval Rate') }}</div>
                                    <div class="font-weight-bold">
                                        {{ $totalReviews > 0 ? round(($approvedReviews / $totalReviews) * 100) : 0 }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Table -->
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
                            <th width="15%">{{ translate('Product') }}</th>
                            <th width="15%">{{ translate('Customer') }}</th>
                            <th width="10%">{{ translate('Rating') }}</th>
                            <th width="25%">{{ translate('Comment') }}</th>
                            <th width="10%">{{ translate('Status') }}</th>
                            <th width="12%">{{ translate('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $key => $review)
                            <tr>
                                <td>
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="review-checkbox" value="{{ $review->id }}">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </td>
                                <td>
                                    {{ ($reviews->currentPage() - 1) * $reviews->perPage() + $key + 1 }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($review->product && $review->product->thumbnail)
                                            <img src="{{ uploaded_asset($review->product->thumbnail) }}"
                                                alt="{{ $review->product->name }}" class="size-40px img-fit mr-2">
                                        @else
                                            <img src="{{ asset('assets/img/placeholder.jpg') }}" alt="Product"
                                                class="size-40px img-fit mr-2">
                                        @endif
                                        <div>
                                            <div class="text-truncate" style="max-width: 150px;">
                                                {{ $review->product->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ translate('SKU:') }}
                                                {{ $review->product->inventory->sku ?? 'N/A' }}</small>
                                        </div>
                                    </div>
            </div>
            <td>
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm mr-2">
                        @if ($review->user && $review->user->avatar)
                            <img src="{{ uploaded_asset($review->user->avatar) }}" class="rounded-circle" width="32"
                                height="32">
                        @else
                            <img src="{{ asset('default/avatar.jpg') }}" class="rounded-circle" width="32"
                                height="32">
                        @endif
                    </div>
                    <div>
                        <div>{{ $review->user->name ?? 'Guest User' }}</div>
                        <small class="text-muted">{{ $review->user->email ?? '' }}</small>
                    </div>
                </div>
        </div>
        <td>
            <div class="rating">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $review->rating)
                        <i class="las la-star text-warning"></i>
                    @else
                        <i class="lar la-star text-muted"></i>
                    @endif
                @endfor
                <span class="ml-2 font-weight-bold">{{ $review->rating }}/5</span>
            </div>
    </div>
    <td>
        <div class="review-comment">
            <div class="text-truncate" style="max-width: 250px;">{{ $review->comment }}</div>
            <small class="text-muted">{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</small>
        </div>
        </div>
    <td>
        @if ($review->status == 1)
            <span class="badge badge-success">{{ translate('Approved') }}</span>
        @else
            <span class="badge badge-warning">{{ translate('Pending') }}</span>
        @endif
        <br>
        @if ($review->is_read == 1)
            <span class="badge badge-info mt-1">{{ translate('Read') }}</span>
        @else
            <span class="badge badge-secondary mt-1">{{ translate('Unread') }}</span>
        @endif
        </div>
    <td>
        <div class="btn-group" role="group">
            <a href="{{ route('reviews.show', $review->id) }}" class="btn btn-sm btn-icon btn-info"
                title="{{ translate('View Details') }}">
                <i class="las la-eye"></i>
            </a>
            @if ($review->status == 0)
                <button type="button" class="btn btn-sm btn-icon btn-success" data-toggle="modal"
                    data-target="#approve-modal" onclick="setReviewData({{ $review->id }}, 1)"
                    title="{{ translate('Approve Review') }}">
                    <i class="las la-check-circle"></i>
                </button>
            @else
                <button type="button" class="btn btn-sm btn-icon btn-secondary" data-toggle="modal"
                    data-target="#disapprove-modal" onclick="setReviewData({{ $review->id }}, 0)"
                    title="{{ translate('Disapprove Review') }}">
                    <i class="las la-times-circle"></i>
                </button>
            @endif
            <button type="button" class="btn btn-sm btn-icon btn-danger" data-toggle="modal"
                data-target="#delete-single-modal" onclick="setDeleteForm('{{ route('reviews.destroy', $review->id) }}')"
                title="{{ translate('Delete') }}">
                <i class="las la-trash"></i>
            </button>
        </div>
        </div>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center">
                <div class="py-5">
                    <i class="las la-comment-slash fs-60 text-muted"></i>
                    <h5 class="text-muted mt-3">{{ translate('No reviews found') }}</h5>
                    <p class="text-muted">{{ translate('No reviews match your filter criteria.') }}</p>
                    <a href="{{ route('reviews.index') }}" class="btn btn-primary"><i class="las la-undo-alt"></i>
                        {{ translate('Clear Filters') }}</a>
                </div>
                </div>
        </tr>
        @endforelse
        </tbody>
        </table>
        </div>

        <!-- Pagination -->
        @if (method_exists($reviews, 'links'))
            <div class="aiz-pagination">
                {{ $reviews->links() }}
            </div>
        @endif
        </div>
        </div>
    @endsection

    @section('modal')
        <!-- Approve Modal -->
        <div id="approve-modal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-success">{{ translate('Approve Review') }}</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="las la-check-circle text-success" style="font-size: 48px;"></i>
                        <h4 class="mt-2">{{ translate('Approve this review?') }}</h4>
                        <p>{{ translate('This review will be visible to customers.') }}</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="button" class="btn btn-success"
                            onclick="confirmApprove()">{{ translate('Approve') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Disapprove Modal -->
        <div id="disapprove-modal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-warning">{{ translate('Disapprove Review') }}</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="las la-times-circle text-warning" style="font-size: 48px;"></i>
                        <h4 class="mt-2">{{ translate('Disapprove this review?') }}</h4>
                        <p>{{ translate('This review will be hidden from customers.') }}</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="button" class="btn btn-warning"
                            onclick="confirmDisapprove()">{{ translate('Disapprove') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Action Modal -->
        <div id="bulk-action-modal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bulk-action-title">{{ translate('Bulk Action') }}</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
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
                        <h5 class="modal-title text-danger">{{ translate('Delete Review') }}</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="las la-trash text-danger" style="font-size: 48px;"></i>
                        <h4 class="mt-2">{{ translate('Are you sure?') }}</h4>
                        <p>{{ translate('This review will be permanently deleted.') }}</p>
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
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script type="text/javascript">
            let currentReviewId = null;
            let currentStatus = null;
            let bulkActionType = null;
            let bulkSelectedIds = [];

            $(document).ready(function() {
                $('#select-all').on('change', function() {
                    $('.review-checkbox').prop('checked', $(this).is(':checked'));
                    updateBulkActionBar();
                });

                $(document).on('change', '.review-checkbox', function() {
                    updateBulkActionBar();
                });

                // Rating Pie Chart
                var ctx = document.getElementById('ratingPieChart').getContext('2d');
                var ratingData = {
                    labels: ['5 Star', '4 Star', '3 Star', '2 Star', '1 Star'],
                    datasets: [{
                        data: [
                            {{ $ratingCounts[5] ?? 0 }},
                            {{ $ratingCounts[4] ?? 0 }},
                            {{ $ratingCounts[3] ?? 0 }},
                            {{ $ratingCounts[2] ?? 0 }},
                            {{ $ratingCounts[1] ?? 0 }}
                        ],
                        backgroundColor: ['#28a745', '#20c997', '#ffc107', '#fd7e14', '#dc3545'],
                        borderWidth: 0,
                    }]
                };

                new Chart(ctx, {
                    type: 'pie',
                    data: ratingData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        var label = context.label || '';
                                        var value = context.raw || 0;
                                        var total = {{ $totalReviews ?? 0 }};
                                        var percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });

                // Activity Bar Chart
                var barCtx = document.getElementById('activityBarChart').getContext('2d');

                // Get last 6 months data
                var months = [];
                var monthlyReviews = [];

                @for ($i = 5; $i >= 0; $i--)
                    @php
                        $date = \Carbon\Carbon::now()->subMonths($i);
                        $monthName = $date->format('M Y');
                        $monthCount = \App\Models\Admin\Review::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count();
                    @endphp
                    months.push('{{ $monthName }}');
                    monthlyReviews.push({{ $monthCount }});
                @endfor

                new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: months,
                        datasets: [{
                            label: '{{ translate('Reviews') }}',
                            data: monthlyReviews,
                            backgroundColor: '#0ea5e9',
                            borderRadius: 8,
                            barPercentage: 0.7,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Reviews: ' + context.raw;
                                    }
                                }
                            }
                        }
                    }
                });
            });

            function updateBulkActionBar() {
                var selected = $('.review-checkbox:checked').length;
                if (selected > 0) {
                    $('#bulk-action-bar').show();
                    $('#selected-count').text(selected);
                } else {
                    $('#bulk-action-bar').hide();
                }
            }

            function clearSelection() {
                $('.review-checkbox').prop('checked', false);
                $('#select-all').prop('checked', false);
                updateBulkActionBar();
            }

            function setReviewData(reviewId, status) {
                currentReviewId = reviewId;
                currentStatus = status;
            }

            function setDeleteForm(url) {
                $('#delete-single-form').attr('action', url);
            }

            function confirmApprove() {
                $('#approve-modal').modal('hide');
                updateReviewStatus(currentReviewId, 1);
            }

            function confirmDisapprove() {
                $('#disapprove-modal').modal('hide');
                updateReviewStatus(currentReviewId, 0);
            }

            function updateReviewStatus(reviewId, status) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('reviews.update-status') }}",
                    data: {
                        id: reviewId,
                        status: status
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

            function showBulkActionModal() {
                bulkSelectedIds = [];
                $('.review-checkbox:checked').each(function() {
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
                    if (bulkActionType == 'approve') {
                        title = 'Approve Reviews';
                        message = 'Are you sure you want to approve ' + bulkSelectedIds.length + ' selected reviews?';
                        btnClass = 'btn-success';
                        icon = 'las la-check-circle text-success';
                    } else if (bulkActionType == 'disapprove') {
                        title = 'Disapprove Reviews';
                        message = 'Are you sure you want to disapprove ' + bulkSelectedIds.length + ' selected reviews?';
                        btnClass = 'btn-warning';
                        icon = 'las la-times-circle text-warning';
                    } else if (bulkActionType == 'delete') {
                        title = 'Delete Reviews';
                        message = 'Are you sure you want to delete ' + bulkSelectedIds.length +
                            ' selected reviews? This action cannot be undone.';
                        btnClass = 'btn-danger';
                        icon = 'las la-trash text-danger';
                    }

                    $('#bulk-action-title').text(title);
                    $('#bulk-title').text(title);
                    $('#bulk-message').text(message);
                    $('#bulk-confirm-btn').removeClass('btn-success btn-warning btn-danger').addClass(btnClass);
                    $('#bulk-icon').removeClass().addClass(icon + ' fs-40');
                    $('#bulk-action-modal').modal('show');
                } else {
                    AIZ.plugins.notify('warning', '{{ translate('Please select at least one review') }}');
                }
            }

            function confirmBulkAction() {
                $('#bulk-action-modal').modal('hide');
                if (bulkActionType == 'approve') bulkUpdateStatus(bulkSelectedIds, 1);
                else if (bulkActionType == 'disapprove') bulkUpdateStatus(bulkSelectedIds, 0);
                else if (bulkActionType == 'delete') bulkDelete(bulkSelectedIds);
            }

            function bulkUpdateStatus(ids, status) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('reviews.bulk-update-status') }}",
                    data: {
                        ids: ids,
                        status: status
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

            function bulkDelete(ids) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('reviews.bulk-delete') }}",
                    data: {
                        ids: ids
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
        </script>
    @endsection
