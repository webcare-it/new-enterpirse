@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('General Settings') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <small
                    class="text-muted">{{ translate('Manage your site identity, branding, timezone, and API keys') }}</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- ===== SECTION 1: Site Identity ===== --}}
                <div class="card mb-4">
                    <div class="card-header bg-soft-primary d-flex align-items-center">
                        <i class="las la-id-card la-2x mr-2 text-primary"></i>
                        <h5 class="mb-0">{{ translate('Site Identity') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-from-label font-weight-bold">{{ translate('Admin Site Name') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="admin_site_name">
                                <input type="text" name="admin_site_name" class="form-control"
                                    value="{{ get_setting('admin_site_name') }}"
                                    placeholder="{{ translate('e.g. My Admin Panel') }}">
                                <small
                                    class="text-muted">{{ translate('This name appears in the browser tab and admin panel header.') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-from-label font-weight-bold">{{ translate('Admin Site Motto') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="admin_site_motto">
                                <input type="text" name="admin_site_motto" class="form-control"
                                    placeholder="{{ translate('Best eCommerce Website') }}"
                                    value="{{ get_setting('admin_site_motto') }}">
                                <small class="text-muted">{{ translate('A short tagline for your admin panel.') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-from-label font-weight-bold">{{ translate('Admin Site Favicon') }}</label>
                            <div class="col-sm-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="admin_site_icon">
                                    <input type="hidden" name="admin_site_icon"
                                        value="{{ get_setting('admin_site_icon') }}" class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                                <small class="text-muted">{{ translate('Recommended size: 32x32 .png') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SECTION 2: Branding (Logos) ===== --}}
                <div class="card mb-4">
                    <div class="card-header bg-soft-info d-flex align-items-center">
                        <i class="las la-paint-brush la-2x mr-2 text-info"></i>
                        <h5 class="mb-0">{{ translate('Branding') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-from-label font-weight-bold">{{ translate('System Logo - White') }}</label>
                            <div class="col-sm-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose Files') }}</div>
                                    <input type="hidden" name="types[]" value="system_logo_white">
                                    <input type="hidden" name="system_logo_white"
                                        value="{{ get_setting('system_logo_white') }}" class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                                <small
                                    class="text-muted">{{ translate('Used in admin panel side menu (on dark background).') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-from-label font-weight-bold">{{ translate('System Logo - Black') }}</label>
                            <div class="col-sm-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose Files') }}</div>
                                    <input type="hidden" name="types[]" value="system_logo_black">
                                    <input type="hidden" name="system_logo_black"
                                        value="{{ get_setting('system_logo_black') }}" class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                                <small
                                    class="text-muted">{{ translate('Used in admin panel topbar (mobile) and login page.') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SECTION 3: Timezone & Background ===== --}}
                <div class="card mb-4">
                    <div class="card-header bg-soft-success d-flex align-items-center">
                        <i class="las la-clock la-2x mr-2 text-success"></i>
                        <h5 class="mb-0">{{ translate('Timezone & Login Background') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-from-label font-weight-bold">{{ translate('System Timezone') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="timezone">
                                <select name="timezone" class="form-control aiz-selectpicker" data-live-search="true">
                                    @foreach (timezones() as $key => $value)
                                        <option value="{{ $value }}"
                                            @if (app_timezone() == $value) selected @endif>
                                            {{ $key }}
                                        </option>
                                    @endforeach
                                </select>
                                <small
                                    class="text-muted">{{ translate('Set the default timezone for all date/time displays.') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-from-label font-weight-bold">{{ translate('Admin Login Page Background') }}</label>
                            <div class="col-sm-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose Files') }}</div>
                                    <input type="hidden" name="types[]" value="admin_login_background">
                                    <input type="hidden" name="admin_login_background"
                                        value="{{ get_setting('admin_login_background') }}" class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                                <small
                                    class="text-muted">{{ translate('Background image for the admin login page.') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SECTION 4: Droploo API Credentials ===== --}}
                <div class="card mb-4">
                    <div class="card-header bg-soft-warning d-flex align-items-center">
                        <i class="las la-key la-2x mr-2 text-warning"></i>
                        <h5 class="mb-0">{{ translate('Droploo API Credentials') }}</h5>
                    </div>
                    <div class="card-body">
                        {{-- App Key --}}
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label font-weight-bold">{{ translate('App Key') }}</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="hidden" name="types[]" value="DROPLOO_APP_KEY">
                                    <input type="text" name="DROPLOO_APP_KEY" class="form-control"
                                        id="droploo_app_key" value="{{ get_setting('DROPLOO_APP_KEY') }}"
                                        placeholder="{{ translate('Enter Droploo App Key') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary copy-btn" type="button"
                                            data-target="#droploo_app_key" title="{{ translate('Copy') }}">
                                            <i class="las la-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">{{ translate('Your Droploo application key.') }}</small>
                            </div>
                        </div>

                        {{-- App Secret (with show/hide) --}}
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label font-weight-bold">{{ translate('App Secret') }}</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="hidden" name="types[]" value="DROPLOO_APP_SECRET">
                                    <input type="password" name="DROPLOO_APP_SECRET" class="form-control"
                                        id="droploo_app_secret" value="{{ get_setting('DROPLOO_APP_SECRET') }}"
                                        placeholder="{{ translate('Enter Droploo App Secret') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary toggle-password" type="button"
                                            data-target="#droploo_app_secret" title="{{ translate('Show/Hide') }}">
                                            <i class="las la-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary copy-btn" type="button"
                                            data-target="#droploo_app_secret" title="{{ translate('Copy') }}">
                                            <i class="las la-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <small
                                    class="text-muted">{{ translate('Your Droploo application secret. Keep this secure.') }}</small>
                            </div>
                        </div>

                        {{-- Username --}}
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label font-weight-bold">{{ translate('Username') }}</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="hidden" name="types[]" value="DROPLOO_USERNAME">
                                    <input type="text" name="DROPLOO_USERNAME" class="form-control"
                                        id="droploo_username" value="{{ get_setting('DROPLOO_USERNAME') }}"
                                        placeholder="{{ translate('Enter Droploo Username') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary copy-btn" type="button"
                                            data-target="#droploo_username" title="{{ translate('Copy') }}">
                                            <i class="las la-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">{{ translate('Your Droploo account username.') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="text-right">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="las la-save mr-1"></i> {{ translate('Save Settings') }}
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card-header i {
            font-size: 1.8rem;
            line-height: 1;
        }

        .card-header h5 {
            font-weight: 600;
        }

        .bg-soft-primary {
            background-color: #e8f0fe;
        }

        .bg-soft-info {
            background-color: #e3f2fd;
        }

        .bg-soft-success {
            background-color: #e8f5e9;
        }

        .bg-soft-warning {
            background-color: #fff8e1;
        }

        .form-group.row {
            margin-bottom: 1.5rem;
        }

        .col-from-label {
            font-weight: 600;
            color: #495057;
        }

        .file-preview.box {
            margin-top: 0.5rem;
        }

        .btn-lg {
            padding: 0.6rem 2.5rem;
            font-size: 1rem;
        }

        /* ensure input-group buttons don't wrap */
        .input-group-append .btn {
            padding: 0.375rem 0.75rem;
        }
    </style>
@endpush

@section('script')
    <script>
        $(document).ready(function() {
            // ---- Toggle password visibility ----
            $(document).on('click', '.toggle-password', function() {
                let input = $($(this).data('target'));
                let icon = $(this).find('i');
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('la-eye').addClass('la-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('la-eye-slash').addClass('la-eye');
                }
            });

            // ---- Copy to clipboard (modern + fallback) ----
            $(document).on('click', '.copy-btn', function() {
                let target = $(this).data('target');
                let input = $(target);
                if (!input.length) return;

                let value = input.val();
                if (!value || value.trim() === '') {
                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('warning', '{{ translate('Nothing to copy') }}');
                    } else {
                        alert('{{ translate('Nothing to copy') }}');
                    }
                    return;
                }

                // Temporarily reveal if password field
                let wasPassword = false;
                if (input.attr('type') === 'password') {
                    wasPassword = true;
                    input.attr('type', 'text');
                }

                // Try using the modern Clipboard API first
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(value).then(function() {
                        input.blur();
                        if (wasPassword) input.attr('type', 'password');
                        if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                            AIZ.plugins.notify('success',
                                '{{ translate('Copied to clipboard!') }}');
                        }
                    }).catch(function() {
                        // fallback to execCommand
                        input.select();
                        document.execCommand('copy');
                        input.blur();
                        if (wasPassword) input.attr('type', 'password');
                        if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                            AIZ.plugins.notify('success',
                                '{{ translate('Copied to clipboard!') }}');
                        }
                    });
                } else {
                    // fallback for older browsers
                    input.select();
                    document.execCommand('copy');
                    input.blur();
                    if (wasPassword) input.attr('type', 'password');
                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('success', '{{ translate('Copied to clipboard!') }}');
                    }
                }
            });
        });
    </script>
@endsection
