@extends('backend.layouts.app')

@section('content')
    <style>
        .fc-wrapper {
            font-family: 'Poppins', sans-serif;
        }

        .fc-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 2rem 2rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .fc-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .fc-hero::after {
            content: '';
            position: absolute;
            bottom: -60%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 50%;
        }

        .fc-hero h2 {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
            position: relative;
            z-index: 1;
        }

        .fc-hero p {
            opacity: 0.85;
            font-size: 0.9rem;
            margin-bottom: 0;
            position: relative;
            z-index: 1;
        }

        .fc-search-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem 2rem 1.25rem;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            position: relative;
            z-index: 2;
            border: 1px solid rgba(0, 0, 0, 0.04);
        }

        .fc-search-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .fc-search-row .fc-input-group {
            flex: 1;
        }

        .fc-search-row label {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            margin-bottom: 6px;
        }

        .fc-search-row .fc-input-wrap {
            display: flex;
            align-items: center;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            background: #fff;
            height: 48px;
        }

        .fc-search-row .fc-input-wrap:focus-within {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .fc-search-row .fc-input-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            color: #94a3b8;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .fc-search-row .fc-input-wrap input {
            border: none;
            outline: none;
            box-shadow: none;
            padding: 0 12px 0 0;
            font-size: 0.95rem;
            height: 100%;
            width: 100%;
            background: transparent;
        }

        .fc-search-row .fc-input-hint {
            font-size: 0.68rem;
            color: #94a3b8;
            margin-top: 4px;
        }

        .fc-search-actions {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            flex-shrink: 0;
        }

        .btn-fc-search {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 0 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            color: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            height: 48px;
            white-space: nowrap;
        }

        .btn-fc-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: #fff;
        }

        .btn-fc-search:disabled {
            opacity: 0.7;
            transform: none;
        }

        .btn-fc-reset {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 600;
            color: #64748b;
            background: transparent;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .btn-fc-reset:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
            color: #475569;
        }

        .btn-fc-orders {
            border: 2px solid rgba(255, 255, 255, 0.25);
            border-radius: 12px;
            padding: 0 1.25rem;
            height: 48px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(4px);
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-fc-orders:hover {
            background: rgba(255, 255, 255, 0.22);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.4);
        }

        .fc-stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .fc-stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .fc-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .fc-stat-label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
        }

        .fc-stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
        }

        .fc-score-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(0, 0, 0, 0.04);
            overflow: hidden;
            position: relative;
        }

        .fc-score-card .card-body {
            padding: 2rem;
        }

        .fc-score-header {
            text-align: center;
            padding: 1.5rem 1.5rem 0;
        }

        .fc-score-header .badge {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .fc-score-ring {
            position: relative;
            width: 180px;
            height: 180px;
            margin: 1.5rem auto;
        }

        .fc-score-ring svg {
            width: 100%;
            height: 100%;
        }

        .fc-score-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .fc-score-center .value {
            font-size: 2.25rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
        }

        .fc-score-center .label {
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
        }

        .fc-risk-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .fc-score-note {
            font-size: 0.8rem;
            color: #94a3b8;
            line-height: 1.6;
        }

        .fc-section-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .fc-section-card .card-body {
            padding: 1.75rem;
        }

        .fc-section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .fc-section-title i {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        .fc-table thead th {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            border-bottom: 2px solid #f1f5f9;
            padding: 0.85rem 1rem;
            background: transparent;
        }

        .fc-table tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f8fafc;
            vertical-align: middle;
        }

        .fc-table tbody tr {
            transition: background 0.15s ease;
        }

        .fc-table tbody tr:hover {
            background: #f8fafc;
        }

        .fc-table .courier-name {
            font-weight: 600;
            color: #1e293b;
        }

        .fc-table .badge-stat {
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
        }

        .fc-verdict-card {
            border-radius: 16px;
            padding: 1.5rem;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .fc-verdict-card .verdict-label {
            font-size: 1.1rem;
            font-weight: 700;
        }

        .fc-verdict-card .verdict-action {
            font-size: 0.85rem;
            opacity: 0.85;
            margin-top: 0.25rem;
        }

        .fc-verdict-card .verdict-status {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #94a3b8;
        }

        .fc-verdict-card .verdict-status-val {
            font-weight: 700;
            color: #1e293b;
        }

        .fc-report-card {
            background: #f8fafc;
            border-radius: 14px;
            padding: 1.25rem;
            border: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .fc-report-card:hover {
            background: #fff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        }

        .fc-report-card .report-title {
            font-weight: 700;
            color: #1e293b;
            font-size: 0.95rem;
        }

        .fc-report-card .report-id {
            font-size: 0.7rem;
            font-weight: 600;
            color: #94a3b8;
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
        }

        .fc-report-card .report-detail {
            font-size: 0.8rem;
            color: #64748b;
            line-height: 1.5;
        }

        .fc-report-card .report-meta {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .fc-reason-item {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #64748b;
            padding: 0.4rem 0;
        }

        .fc-reason-item::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #cbd5e1;
            margin-top: 0.4rem;
            flex-shrink: 0;
        }

        .fc-info-alert {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.08) 0%, rgba(118, 75, 162, 0.08) 100%);
            border: 1px solid rgba(102, 126, 234, 0.15);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            color: #667eea;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
        }

        .fc-info-alert i {
            font-size: 1.25rem;
        }

        .fc-alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 14px;
            padding: 1rem 1.5rem;
            color: #dc2626;
            font-size: 0.9rem;
        }

        @keyframes fcSpinIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .fc-animate-in {
            animation: fcSpinIn 0.4s ease forwards;
        }

        .fc-delay-1 {
            animation-delay: 0.05s;
            opacity: 0;
        }

        .fc-delay-2 {
            animation-delay: 0.1s;
            opacity: 0;
        }

        .fc-delay-3 {
            animation-delay: 0.15s;
            opacity: 0;
        }

        .fc-delay-4 {
            animation-delay: 0.2s;
            opacity: 0;
        }

        .fc-orders-section {
            margin-bottom: 1.5rem;
        }
        .fc-order-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.85rem 1rem;
            background: #fff;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }
        .fc-order-row:hover {
            border-color: #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .fc-order-code {
            font-weight: 700;
            color: #1e293b;
            font-size: 0.9rem;
            min-width: 100px;
        }
        .fc-order-meta {
            font-size: 0.78rem;
            color: #94a3b8;
        }
        .fc-order-status-badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.25rem 0.65rem;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }
        .fc-fraud-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            background: #fff;
            cursor: pointer;
            transition: border-color 0.2s ease;
            min-width: 140px;
        }
        .fc-fraud-select:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }
        .fc-order-updated {
            font-size: 0.7rem;
            color: #10b981;
            font-weight: 600;
            display: none;
        }
        .fc-no-orders {
            text-align: center;
            padding: 2rem;
            color: #94a3b8;
            font-size: 0.9rem;
        }
        .fc-no-orders i {
            font-size: 2rem;
            display: block;
            margin-bottom: 0.5rem;
            opacity: 0.4;
        }
    </style>

    <div class="fc-wrapper">
        <!-- Hero Header -->
        <div class="fc-hero mb-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2><i class="las la-shield-alt mr-2"></i>{{ translate('Courier Fraud Checker') }}</h2>
                    <p>{{ translate('Verify courier reliability and detect potential fraud patterns') }}</p>
                </div>
                <a href="{{ url('admin/orders') }}" class="btn btn-fc-orders d-none d-md-inline-flex">
                    <i class="las la-shopping-basket"></i> {{ translate('View All Orders') }}
                </a>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="fc-search-card mb-4">
            <form method="GET" action="{{ route('fraud_checker') }}" id="fraudCheckerForm">
                <div class="fc-search-row">
                    <div class="fc-input-group">
                        <label for="phone">{{ translate('PHONE NUMBER') }}</label>
                        <div class="fc-input-wrap">
                            <span class="fc-input-icon"><i class="las la-phone"></i></span>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $phone ?? '') }}"
                                placeholder="01XXXXXXXXX" autocomplete="off">
                        </div>
                        <div class="fc-input-hint">11 digits, no country code</div>
                    </div>
                    <div class="fc-search-actions">
                        <button type="submit" class="btn btn-fc-search" id="fraudSearchBtn">
                            <span class="btn-text"><i class="las la-search mr-1"></i> {{ translate('Search') }}</span>
                            <span class="btn-loading d-none"><i class="las la-spinner la-spin mr-1"></i>
                                {{ translate('Searching...') }}</span>
                        </button>
                        <button type="button" id="fraudResetBtn" class="btn btn-fc-reset" title="{{ translate('Reset') }}">
                            <i class="las la-redo-alt"></i>
                        </button>
                    </div>
                </div>
                <a href="{{ url('admin/orders') }}" class="btn btn-fc-orders d-md-none mt-3">
                    <i class="las la-shopping-basket"></i> {{ translate('View All Orders') }}
                </a>
            </form>
        </div>

        @if (isset($error) && $error)
            <div class="fc-alert-error mb-4 fc-animate-in">
                <i class="las la-exclamation-triangle mr-1"></i> {{ $error }}
            </div>
        @endif

        @if ($phone && $matchedOrders->count() > 0)
            @php
                $hasFraud = $matchedOrders->contains('is_verified_fraud', 'fraud');
                $hasVerified = $matchedOrders->contains('is_verified_fraud', 'verified');
                $overallStatus = $hasFraud ? 'fraud' : ($hasVerified ? 'verified' : 'not_verified');

                $statusUI = match($overallStatus) {
                    'verified' => [
                        'bg' => '#d1fae5', 'color' => '#059669', 'border' => '#a7f3d0',
                        'icon' => 'la-check-circle',
                        'title' => translate('This phone number is Verified'),
                        'desc' => translate('No fraud detected. All orders for this number are clean.'),
                    ],
                    'fraud' => [
                        'bg' => '#fee2e2', 'color' => '#dc2626', 'border' => '#fecaca',
                        'icon' => 'la-exclamation-triangle',
                        'title' => translate('This phone number is flagged as Fraud'),
                        'desc' => translate('One or more orders for this number are marked as fraud.'),
                    ],
                    default => [
                        'bg' => '#fef3c7', 'color' => '#d97706', 'border' => '#fde68a',
                        'icon' => 'la-question-circle',
                        'title' => translate('This phone number is Not Verified yet'),
                        'desc' => translate('No fraud status set. Verify or flag this number.'),
                    ],
                };
            @endphp
            <div class="fc-section-card fc-orders-section fc-animate-in">
                <div class="card-body">
                    <div style="display: flex; align-items: center; gap: 16px; padding: 1rem 1.25rem; border-radius: 14px; background: {{ $statusUI['bg'] }}; border: 1px solid {{ $statusUI['border'] }};">
                        <div style="width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.7); flex-shrink: 0;">
                            <i class="las {{ $statusUI['icon'] }}" style="font-size: 1.5rem; color: {{ $statusUI['color'] }};"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 700; color: {{ $statusUI['color'] }}; font-size: 1rem;">{{ $statusUI['title'] }}</div>
                            <div style="font-size: 0.8rem; color: {{ $statusUI['color'] }}; opacity: 0.8;">{{ $statusUI['desc'] }}</div>
                            <div style="font-size: 0.72rem; color: {{ $statusUI['color'] }}; opacity: 0.6; margin-top: 2px;">
                                {{ $matchedOrders->count() }} {{ translate('order(s) found for this phone') }}
                            </div>
                        </div>
                        <div style="flex-shrink: 0;">
                            <select class="fc-fraud-select" id="fraudStatusSelect"
                                onchange="bulkUpdateFraudStatus(this.value)"
                                style="border-color: {{ $statusUI['color'] }};">
                                <option value="not_verified" {{ $overallStatus === 'not_verified' ? 'selected' : '' }}>
                                    {{ translate('Not Verified') }}</option>
                                <option value="verified" {{ $overallStatus === 'verified' ? 'selected' : '' }}>
                                    {{ translate('Verified') }}</option>
                                <option value="fraud" {{ $overallStatus === 'fraud' ? 'selected' : '' }}>
                                    {{ translate('Fraud') }}</option>
                            </select>
                        </div>
                        <span id="bulkUpdatedMsg" style="font-size: 0.75rem; color: #059669; font-weight: 600; display: none;">
                            <i class="las la-check"></i> {{ translate('All Updated') }}
                        </span>
                    </div>
                    <input type="hidden" id="matchedOrderIds" value="{{ $matchedOrders->pluck('id')->implode(',') }}">
                </div>
            </div>
        @endif

        @if (isset($result) && $result)
            @php
                $status = strtolower($result['status'] ?? 'success');
                $riskLevel = strtolower($result['risk_level'] ?? ($result['risk_verdict']['level'] ?? 'safe'));
                $riskLabel = $result['risk_verdict']['label'] ?? ucfirst($riskLevel);
                $riskAction = $result['risk_verdict']['action'] ?? 'Review courier performance';
                $riskReasons = $result['risk_verdict']['reasons'] ?? [];

                $riskColor = match ($riskLevel) {
                    'safe', 'excellent', 'excellent risk' => '#10b981',
                    'danger', 'high risk' => '#ef4444',
                    'warning', 'medium risk' => '#f59e0b',
                    default => '#6b7280',
                };

                $riskBg = match ($riskLevel) {
                    'safe', 'excellent', 'excellent risk' => '#d1fae5',
                    'danger', 'high risk' => '#fee2e2',
                    'warning', 'medium risk' => '#fef3c7',
                    default => '#f3f4f6',
                };

                $summary = $result['data']['summary'] ?? [
                    'total_parcel' => 0,
                    'success_parcel' => 0,
                    'cancelled_parcel' => 0,
                    'success_ratio' => $result['success_ratio'] ?? 0,
                ];

                $providers = $result['data'] ?? [];
                $reports = $result['reports'] ?? [];
            @endphp

            <div class="row g-4">

                <!-- Left: Score Card -->
                <div class="col-lg-4 col-xl-3">
                    <div class="fc-score-card h-100 fc-animate-in fc-delay-1">
                        <div class="fc-score-header">
                            <span class="badge"><i class="las la-shield-alt mr-1"></i>
                                {{ translate('Fraud Check') }}</span>
                            <h6 class="mt-3 mb-0" style="font-weight: 700; color: #1e293b;">
                                {{ translate('Delivery Success Ratio') }}</h6>
                        </div>

                        <div class="fc-score-ring">
                            <svg viewBox="0 0 180 180">
                                <circle cx="90" cy="90" r="78" fill="none" stroke="#f1f5f9"
                                    stroke-width="14"></circle>
                                <circle cx="90" cy="90" r="78" fill="none" stroke="{{ $riskColor }}"
                                    stroke-width="14" stroke-dasharray="490"
                                    stroke-dashoffset="{{ 490 - (490 * min(100, max(0, $summary['success_ratio']))) / 100 }}"
                                    stroke-linecap="round" transform="rotate(-90 90 90)"
                                    style="transition: stroke-dashoffset 1s ease;"></circle>
                            </svg>
                            <div class="fc-score-center">
                                <div class="value">{{ $summary['success_ratio'] }}%</div>
                                <div class="label">{{ translate('Success Rate') }}</div>
                            </div>
                        </div>

                        <div class="text-center px-3 mb-3">
                            <span class="fc-risk-badge"
                                style="background: {{ $riskBg }}; color: {{ $riskColor }};">
                                <i class="las la-check-circle"></i> {{ ucfirst($riskLabel) }}
                            </span>
                        </div>

                        <p class="fc-score-note text-center px-4">
                            {{ translate('Verified against overall historical courier logs.') }}
                        </p>
                    </div>
                </div>

                <!-- Right: Stats + Details -->
                <div class="col-lg-8 col-xl-9">

                    <!-- Stat Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-sm-3">
                            <div class="fc-stat-card h-100 fc-animate-in fc-delay-1">
                                <div class="fc-stat-icon mb-3" style="background: rgba(99,102,241,0.1); color: #6366f1;">
                                    <i class="las la-shopping-cart"></i>
                                </div>
                                <div class="fc-stat-label mb-1">{{ translate('Total Orders') }}</div>
                                <div class="fc-stat-value">{{ $summary['total_parcel'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="fc-stat-card h-100 fc-animate-in fc-delay-2">
                                <div class="fc-stat-icon mb-3" style="background: rgba(16,185,129,0.1); color: #10b981;">
                                    <i class="las la-check-circle"></i>
                                </div>
                                <div class="fc-stat-label mb-1">{{ translate('Delivered') }}</div>
                                <div class="fc-stat-value">{{ $summary['success_parcel'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="fc-stat-card h-100 fc-animate-in fc-delay-3">
                                <div class="fc-stat-icon mb-3" style="background: rgba(239,68,68,0.1); color: #ef4444;">
                                    <i class="las la-times-circle"></i>
                                </div>
                                <div class="fc-stat-label mb-1">{{ translate('Cancelled') }}</div>
                                <div class="fc-stat-value">{{ $summary['cancelled_parcel'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="fc-stat-card h-100 fc-animate-in fc-delay-4">
                                <div class="fc-stat-icon mb-3" style="background: rgba(14,165,233,0.1); color: #0ea5e9;">
                                    <i class="las la-chart-line"></i>
                                </div>
                                <div class="fc-stat-label mb-1">{{ translate('Success Rate') }}</div>
                                <div class="fc-stat-value">{{ $summary['success_ratio'] }}%</div>
                            </div>
                        </div>
                    </div>

                    <!-- Risk Verdict -->
                    <div class="fc-section-card mb-4 fc-animate-in fc-delay-2">
                        <div class="card-body">
                            <div class="fc-section-title">
                                <i class="las la-gavel" style="background: rgba(102,126,234,0.1); color: #667eea;"></i>
                                {{ translate('Risk Verdict') }}
                            </div>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <div class="fc-verdict-card"
                                        style="background: {{ $riskBg }}; color: {{ $riskColor }};">
                                        <div class="verdict-label"><i class="las la-shield-alt mr-1"></i>
                                            {{ $riskLabel }}</div>
                                        <div class="verdict-action">{{ $riskAction }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="fc-verdict-card" style="background: #f8fafc; color: #1e293b;">
                                        <div class="verdict-status">{{ translate('STATUS') }}</div>
                                        <div class="verdict-status-val mt-1">{{ ucfirst($status) }}</div>
                                        @if (!empty($result['message']))
                                            <div class="report-detail mt-2">{{ $result['message'] }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if (!empty($riskReasons))
                                <div class="mt-3">
                                    @foreach ($riskReasons as $reason)
                                        <div class="fc-reason-item">{{ $reason }}</div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Courier Details Table -->
                    <div class="fc-section-card mb-4 fc-animate-in fc-delay-3">
                        <div class="card-body">
                            <div class="fc-section-title">
                                <i class="las la-table" style="background: rgba(14,165,233,0.1); color: #0ea5e9;"></i>
                                {{ translate('Courier Fraud Check Details') }}
                            </div>
                            <div class="table-responsive">
                                <table class="table fc-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ translate('Courier') }}</th>
                                            <th class="text-center">{{ translate('Orders') }}</th>
                                            <th class="text-center">{{ translate('Delivered') }}</th>
                                            <th class="text-center">{{ translate('Cancelled') }}</th>
                                            <th class="text-center">{{ translate('Performance') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($providers as $key => $provider)
                                            @if ($key !== 'summary' && is_array($provider) && isset($provider['name']))
                                                @php
                                                    $perfRatio = intval($provider['success_ratio'] ?? 0);
                                                    $perfBg =
                                                        $perfRatio >= 70
                                                            ? '#d1fae5'
                                                            : ($perfRatio >= 40
                                                                ? '#fef3c7'
                                                                : '#fee2e2');
                                                    $perfColor =
                                                        $perfRatio >= 70
                                                            ? '#059669'
                                                            : ($perfRatio >= 40
                                                                ? '#d97706'
                                                                : '#dc2626');
                                                @endphp
                                                <tr>
                                                    <td class="courier-name">{{ $provider['name'] ?? ucfirst($key) }}</td>
                                                    <td class="text-center">
                                                        <span class="badge-stat"
                                                            style="background: #f1f5f9; color: #475569;">
                                                            {{ $provider['total_parcel'] ?? 0 }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge-stat"
                                                            style="background: #d1fae5; color: #059669;">
                                                            {{ $provider['success_parcel'] ?? 0 }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge-stat"
                                                            style="background: #fee2e2; color: #dc2626;">
                                                            {{ $provider['cancelled_parcel'] ?? 0 }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge-stat"
                                                            style="background: {{ $perfBg }}; color: {{ $perfColor }};">
                                                            {{ $provider['success_ratio'] ?? 0 }}%
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Fraud Reports -->
                    @if (!empty($reports))
                        <div class="fc-section-card fc-animate-in fc-delay-4">
                            <div class="card-body">
                                <div class="fc-section-title">
                                    <i class="las la-flag" style="background: rgba(239,68,68,0.1); color: #ef4444;"></i>
                                    {{ translate('Fraud Reports') }}
                                </div>
                                <div class="row g-3">
                                    @foreach ($reports as $report)
                                        <div class="col-md-6">
                                            <div class="fc-report-card h-100">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span
                                                        class="report-title">{{ $report['name'] ?? translate('Report') }}</span>
                                                    <span class="report-id">#{{ $report['id'] ?? '-' }}</span>
                                                </div>
                                                <div class="report-detail mb-2">{{ $report['details'] ?? '-' }}</div>
                                                <div class="report-meta">
                                                    <i class="las la-truck mr-1"></i>{{ translate('Courier') }}:
                                                    {{ $report['courierName'] ?? '-' }}
                                                </div>
                                                <div class="report-meta mt-1">
                                                    <i class="las la-clock mr-1"></i>{{ translate('Created') }}:
                                                    {{ $report['created_at'] ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @elseif(isset($phone) && !isset($error))
            <div class="fc-info-alert mb-4 fc-animate-in">
                <i class="las la-info-circle"></i>
                {{ translate('Phone number parsed. Click Search to view performance indicators.') }}
            </div>
        @endif
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('fraudCheckerForm');
            const searchBtn = document.getElementById('fraudSearchBtn');
            const resetBtn = document.getElementById('fraudResetBtn');
            const phoneInput = document.getElementById('phone');
            const btnText = searchBtn ? searchBtn.querySelector('.btn-text') : null;
            const btnLoading = searchBtn ? searchBtn.querySelector('.btn-loading') : null;

            if (form && searchBtn && btnText && btnLoading && resetBtn) {
                form.addEventListener('submit', function() {
                    searchBtn.disabled = true;
                    resetBtn.disabled = true;
                    btnText.classList.add('d-none');
                    btnLoading.classList.remove('d-none');
                });
            }

            if (resetBtn && phoneInput) {
                resetBtn.addEventListener('click', function() {
                    if (resetBtn.disabled) return;
                    phoneInput.value = '';
                    window.location.href = '{{ route('fraud_checker') }}';
                });
            }
        });

        function bulkUpdateFraudStatus(status) {
            var idsInput = document.getElementById('matchedOrderIds');
            var msg = document.getElementById('bulkUpdatedMsg');
            if (!idsInput) return;

            var ids = idsInput.value.split(',').filter(function(id) { return id.trim() !== ''; });
            var pending = ids.length;

            ids.forEach(function(orderId) {
                fetch('{{ route("fraud_checker.update_status") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        is_verified_fraud: status
                    })
                })
                .then(function() {
                    pending--;
                    if (pending === 0 && msg) {
                        msg.style.display = 'inline';
                        setTimeout(function() { msg.style.display = 'none'; }, 2500);
                    }
                });
            });
        }
    </script>
@endsection
