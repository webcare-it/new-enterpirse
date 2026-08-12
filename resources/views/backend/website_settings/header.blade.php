@extends('backend.layouts.app')

@section('content')
    @php
        $layoutConfig = json_decode(get_setting('layout_breakpoints'), true) ?? [
            'homePage' => ['mobile' => 1, 'tablet' => 2, 'laptop' => 3, 'desktop' => 4, 'ultrawide' => 5],
            'withFilter' => ['mobile' => 2, 'tablet' => 2, 'laptop' => 3, 'desktop' => 4, 'ultrawide' => 5],
            'fullLayout' => ['mobile' => 2, 'tablet' => 3, 'laptop' => 4, 'desktop' => 5, 'ultrawide' => 6],
        ];
    @endphp

    <div class="row">
        <div class="col-md-9 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Header Setting') }}</h6>
                </div>
                <div class="card-body">
                    <!-- ========== FORM 1: Header Logo & Helpline ========== -->
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Header Logo') }}</label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="header_logo">
                                    <input type="hidden" name="header_logo" class="selected-files"
                                        value="{{ get_setting('header_logo') }}">
                                </div>
                                <div class="file-preview"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Help line number') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="helpline_number">
                                <input type="text" class="form-control" placeholder="{{ translate('Help line number') }}"
                                    name="helpline_number" value="{{ get_setting('helpline_number') }}">
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <!-- ========== FORM 2: Layout Breakpoints ========== -->
                    <form action="{{ route('business_settings.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="types[]" value="layout_breakpoints">

                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h5 class="mb-0">{{ translate('Layout Breakpoints Configuration') }}</h5>
                            <span
                                class="badge badge-soft-primary">{{ translate('Set columns per breakpoint (1–6)') }}</span>
                        </div>

                        @php
                            $groups = [
                                'homePage' => [
                                    'label' => 'Home Page',
                                    'icon' => 'bi-house-door',
                                    'color' => 'primary',
                                    'desc' => 'Default listing layout',
                                ],
                                'withFilter' => [
                                    'label' => 'With Filter Layout',
                                    'icon' => 'bi-funnel',
                                    'color' => 'info',
                                    'desc' => 'Sidebar filter layout',
                                ],
                                'fullLayout' => [
                                    'label' => 'Full Layout',
                                    'icon' => 'bi-grid-fill',
                                    'color' => 'success',
                                    'desc' => 'Full width layout',
                                ],
                            ];
                            $breakpoints = [
                                'mobile' => ['label' => 'Mobile', 'icon' => 'bi-phone', 'class' => 'text-secondary'],
                                'tablet' => ['label' => 'Tablet', 'icon' => 'bi-tablet', 'class' => 'text-info'],
                                'laptop' => ['label' => 'Laptop', 'icon' => 'bi-laptop', 'class' => 'text-primary'],
                                'desktop' => ['label' => 'Desktop', 'icon' => 'bi-display', 'class' => 'text-success'],
                                'ultrawide' => [
                                    'label' => 'Ultrawide',
                                    'icon' => 'bi-aspect-ratio',
                                    'class' => 'text-warning',
                                ],
                            ];
                        @endphp

                        <div class="row g-4">
                            @foreach ($groups as $groupKey => $groupInfo)
                                <div class="col-md-4">
                                    <div class="card h-100 border-{{ $groupInfo['color'] }} border-top-4">
                                        <div
                                            class="card-header bg-soft-{{ $groupInfo['color'] }} border-0 d-flex align-items-center">
                                            <i class="bi {{ $groupInfo['icon'] }} fs-5 me-2"></i>
                                            <strong>{{ translate($groupInfo['label']) }}</strong>
                                            <span
                                                class="badge bg-{{ $groupInfo['color'] }} ms-auto">{{ ucfirst($groupKey) }}</span>
                                        </div>
                                        <div class="card-body pt-3">
                                            <p class="small text-muted mb-3">{{ translate($groupInfo['desc']) }}</p>
                                            <div class="row g-2">
                                                @foreach ($breakpoints as $bpKey => $bpInfo)
                                                    <div class="col-6 col-lg-4">
                                                        <div class="breakpoint-item p-2 rounded bg-light text-center">
                                                            <div
                                                                class="d-flex align-items-center justify-content-center gap-1">
                                                                <i
                                                                    class="bi {{ $bpInfo['icon'] }} {{ $bpInfo['class'] }}"></i>
                                                                <span
                                                                    class="small fw-bold">{{ translate($bpInfo['label']) }}</span>
                                                            </div>
                                                            <input type="number"
                                                                class="form-control form-control-sm text-center mt-1"
                                                                name="{{ $groupKey . '_' . $bpKey }}"
                                                                value="{{ $layoutConfig[$groupKey][$bpKey] ?? 1 }}"
                                                                min="1" max="6" step="1">
                                                            <div class="mt-1">
                                                                <span class="badge badge-soft-secondary columns-indicator">
                                                                    {{ $layoutConfig[$groupKey][$bpKey] ?? 1 }}
                                                                    {{ translate('cols') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-actions mt-4 d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise"></i> {{ translate('Reset to Saved') }}
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2-circle"></i> {{ translate('Save Configuration') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
