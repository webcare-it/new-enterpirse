@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('User Searches Report') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('reports.export-user-searches', request()->query()) }}" class="btn btn-success">
                    <i class="las la-file-excel"></i> {{ translate('Export CSV') }}
                </a>
                <button type="button" class="btn btn-danger" onclick="confirmClearAll()">
                    <i class="las la-trash-alt"></i> {{ translate('Clear All') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Styles -->
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
            margin-bottom: 20px;
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
            color: #fff;
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

        .mm-card-dark {
            background: linear-gradient(135deg, #334155, #0f172a);
        }

        .mm-card-pink {
            background: linear-gradient(135deg, #ff758c, #ff7eb3);
        }
    </style>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-blue">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Total Searches') }}</div>
                    <div class="stat-number">{{ number_format($totalSearches) }}</div>
                    <div class="stat-small">{{ translate('All search records') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-search"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-green">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Unique Keywords') }}</div>
                    <div class="stat-number">{{ number_format($uniqueKeywords) }}</div>
                    <div class="stat-small">{{ translate('Different search terms') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-tag"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-orange">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Avg Searches/Keyword') }}</div>
                    <div class="stat-number">{{ $avgSearchesPerKeyword }}</div>
                    <div class="stat-small">{{ translate('Average per keyword') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-chart-line"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mm-card mm-card-purple">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Searches This Week') }}</div>
                    <div class="stat-number">{{ number_format($searchesThisWeek) }}</div>
                    <div class="stat-small">{{ translate('Last 7 days') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-calendar-week"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="mm-card mm-card-pink">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Searches This Month') }}</div>
                    <div class="stat-number">{{ number_format($searchesThisMonth) }}</div>
                    <div class="stat-small">{{ translate('Current month') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-calendar-alt"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-3">
            <div class="mm-card mm-card-dark">
                <div class="mm-content">
                    <div class="stat-label">{{ translate('Filtered Records') }}</div>
                    <div class="stat-number">{{ $searches->total() }}</div>
                    <div class="stat-small">{{ translate('Current result records') }}</div>
                </div>
                <div class="mm-icon">
                    <i class="las la-database"></i>
                </div>
            </div>
        </div>

        @if ($mostPopular)
            <div class="col-xl-4 col-md-6 mb-3">
                <div class="mm-card mm-card-green">
                    <div class="mm-content">
                        <div class="stat-label">{{ translate('Most Popular Keyword') }}</div>
                        <div class="stat-number">{{ Str::limit($mostPopular->query, 30) }}</div>
                        <div class="stat-small">{{ $mostPopular->count }} {{ translate('searches') }}</div>
                    </div>
                    <div class="mm-icon">
                        <i class="las la-crown"></i>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Daily Trend Chart -->
    @if ($dailyTrend->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Daily Search Trend (Last 30 Days)') }}</h5>
            </div>
            <div class="card-body">
                <canvas id="searchTrendChart" style="max-height: 300px; width: 100%;"></canvas>
            </div>
        </div>
    @endif

    <!-- Top Keywords Card -->
    @if ($topKeywords->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Top 10 Most Searched Keywords') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="45%">{{ translate('Keyword') }}</th>
                                <th width="20%">{{ translate('Search Count') }}</th>
                                <th width="30%">{{ translate('Percentage') }}</th>
                                </td>
                        </thead>
                        <tbody>
                            @foreach ($topKeywords as $index => $keyword)
                                <tr>
                                    <td>{{ $index + 1 }}
                </div>
                <td><strong>{{ $keyword->query }}</strong>
            </div>
            <td><span class="badge badge-primary">{{ number_format($keyword->count) }}</span>
        </div>
        <td>
            @php
                $percentage = $totalSearches > 0 ? round(($keyword->count / $totalSearches) * 100, 1) : 0;
            @endphp
            <div class="d-flex align-items-center">
                <div class="progress flex-grow-1" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                </div>
                <span class="ml-2">{{ $percentage }}%</span>
            </div>
            </div>
            </tr>
    @endforeach
    </tbody>
    </table>
    </div>
    </div>
    </div>
    @endif

    <!-- Filter Section -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Filter Reports') }}</h5>
            <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#filterCollapse">
                <i class="las la-filter"></i> {{ translate('Show/Hide Filters') }}
            </button>
        </div>
        <div class="collapse {{ request()->has('search') || request()->has('date_from') || request()->has('min_count') ? 'show' : '' }}"
            id="filterCollapse">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.user-searches') }}" id="filter-form">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ translate('Search Keyword') }}</label>
                                <input type="text" class="form-control" name="search"
                                    placeholder="{{ translate('Search by keyword') }}" value="{{ request('search') }}">
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
                                <input type="date" class="form-control" name="date_to"
                                    value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ translate('Min Search Count') }}</label>
                                <input type="number" class="form-control" name="min_count"
                                    placeholder="{{ translate('Minimum count') }}" value="{{ request('min_count') }}"
                                    min="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ translate('Sort By') }}</label>
                                <select class="form-control" name="sort_by">
                                    <option value="count_desc" {{ request('sort_by') == 'count_desc' ? 'selected' : '' }}>
                                        {{ translate('Most Searched') }}
                                    </option>
                                    <option value="count_asc" {{ request('sort_by') == 'count_asc' ? 'selected' : '' }}>
                                        {{ translate('Least Searched') }}
                                    </option>
                                    <option value="keyword_asc"
                                        {{ request('sort_by') == 'keyword_asc' ? 'selected' : '' }}>
                                        {{ translate('Keyword (A-Z)') }}
                                    </option>
                                    <option value="keyword_desc"
                                        {{ request('sort_by') == 'keyword_desc' ? 'selected' : '' }}>
                                        {{ translate('Keyword (Z-A)') }}
                                    </option>
                                    <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>
                                        {{ translate('Latest First') }}
                                    </option>
                                    <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>
                                        {{ translate('Oldest First') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-search"></i> {{ translate('Filter') }}
                            </button>
                            <a href="{{ route('reports.user-searches') }}" class="btn btn-secondary">
                                <i class="las la-undo-alt"></i> {{ translate('Reset') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Searches Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Search Keywords') }}</h5>
            <span class="badge badge-primary ml-2">{{ $searches->total() }} {{ translate('Keywords') }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered aiz-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="40%">{{ translate('Search Keyword') }}</th>
                            <th width="15%">{{ translate('Search Count') }}</th>
                            <th width="20%">{{ translate('First Searched') }}</th>
                            <th width="20%">{{ translate('Last Searched') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($searches as $key => $search)
                            <tr>
                                <td>
                                    {{ ($searches->currentPage() - 1) * $searches->perPage() + $key + 1 }}
            </div>
            <td><strong>{{ $search->query }}</strong>
        </div>
        <td><span class="badge badge-primary badge-lg">{{ number_format($search->count) }}</span>
    </div>
    <td>{{ $search->created_at->format('d M Y, h:i A') }}</div>
    <td>{{ $search->updated_at->format('d M Y, h:i A') }}</div>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">
                <div class="py-5">
                    <i class="las la-search fs-60 text-muted"></i>
                    <h5 class="text-muted mt-3">{{ translate('No search records found') }}</h5>
                    <p class="text-muted">{{ translate('No search data available.') }}</p>
                </div>
                </div>
        </tr>
        @endforelse
        </tbody>
        </table>
        </div>

        <div class="aiz-pagination mt-4">
            {{ $searches->links('backend.paginate.pagination') }}
        </div>
        </div>
        </div>
    @endsection

    @section('script')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script type="text/javascript">
            @if ($dailyTrend->count() > 0)
                var ctx = document.getElementById('searchTrendChart').getContext('2d');
                var chartData = {
                    labels: [
                        @foreach ($dailyTrend as $trend)
                            '{{ \Carbon\Carbon::parse($trend->date)->format('d M') }}',
                        @endforeach
                    ],
                    datasets: [{
                        label: '{{ translate('Search Count') }}',
                        data: [
                            @foreach ($dailyTrend as $trend)
                                {{ $trend->total }},
                            @endforeach
                        ],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                };

                new Chart(ctx, {
                    type: 'line',
                    data: chartData,
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
                                        return 'Searches: ' + context.raw;
                                    }
                                }
                            }
                        }
                    }
                });
            @endif

            function confirmClearAll() {
                if (confirm('{{ translate('Are you sure you want to clear ALL search records? This cannot be undone.') }}')) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "{{ route('reports.clear-search-records') }}",
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
            }
        </script>

        <style>
            .fs-40 {
                font-size: 40px;
            }

            .fs-60 {
                font-size: 60px;
            }

            .badge-lg {
                font-size: 14px;
                padding: 6px 12px;
            }

            .gap-2 {
                gap: 0.5rem;
            }

            .flex-grow-1 {
                flex-grow: 1;
            }

            .ml-2 {
                margin-left: 0.5rem;
            }
        </style>
    @endsection
