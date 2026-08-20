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
        <div class="col-lg-9 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Appearance') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Website Base Color') }}</label>
                            <div class="col-md-9 d-flex align-items-center gap-2">
                                <input type="hidden" name="types[]" value="base_color">
                                <input type="color" id="base_color_picker" class="form-control form-control-color"
                                    value="{{ get_setting('base_color') ?? '#f04d6e' }}" title="Choose color">
                                <input type="text" name="base_color" id="base_color_input" class="form-control"
                                    placeholder="#f04d6e" value="{{ get_setting('base_color') ?? '#f04d6e' }}">
                            </div>
                            <small class="text-muted offset-md-3 col-md-9">{{ translate('Hex Color Code') }}</small>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Website Base Font Color') }}</label>
                            <div class="col-md-9 d-flex align-items-center gap-2">
                                <input type="hidden" name="types[]" value="base_hov_color">
                                <input type="color" id="base_hov_color_picker" class="form-control form-control-color"
                                    value="{{ get_setting('base_hov_color') ?? '#f04d6e' }}" title="Choose color">
                                <input type="text" name="base_hov_color" id="base_hov_color_input" class="form-control"
                                    placeholder="#f04d6e" value="{{ get_setting('base_hov_color') ?? '#f04d6e' }}">
                            </div>
                            <small class="text-muted offset-md-3 col-md-9">{{ translate('Hex Color Code') }}</small>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Base color sync
                                const basePicker = document.getElementById('base_color_picker');
                                const baseInput = document.getElementById('base_color_input');
                                basePicker.addEventListener('input', () => baseInput.value = basePicker.value);
                                baseInput.addEventListener('input', () => basePicker.value = baseInput.value);

                                // Hover color sync
                                const hovPicker = document.getElementById('base_hov_color_picker');
                                const hovInput = document.getElementById('base_hov_color_input');
                                hovPicker.addEventListener('input', () => hovInput.value = hovPicker.value);
                                hovInput.addEventListener('input', () => hovPicker.value = hovInput.value);
                            });
                        </script>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="fw-600 mb-0">{{ translate('General') }}</h6>
                </div>
                <div class="card-body">
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
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-600 mb-0">{{ translate('Global SEO') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Meta Title') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="meta_title">
                                <input type="text" class="form-control" placeholder="{{ translate('Title') }}"
                                    name="meta_title" value="{{ get_setting('meta_title') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Meta description') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="meta_description">
                                <textarea class="resize-off form-control" placeholder="{{ translate('Description') }}" name="meta_description">{{ get_setting('meta_description') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Keywords') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="meta_keywords">
                                <textarea class="resize-off form-control" placeholder="{{ translate('Keyword, Keyword') }}" name="meta_keywords">{{ get_setting('meta_keywords') }}</textarea>
                                <small class="text-muted">{{ translate('Separate with coma') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Meta Image') }}</label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="meta_image">
                                    <input type="hidden" name="meta_image" value="{{ get_setting('meta_image') }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
@endsection
