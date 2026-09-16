@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-9 mx-auto">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-soft-primary text-primary mr-3 rounded-circle p-2"
                            style="background: rgba(0,123,255,0.1);">
                            <i class="las la-cog font-medium-3"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 h5 text-dark font-weight-bold">{{ translate('Droploo API Credentials') }}</h5>
                            <p class="text-muted mb-0 small">
                                {{ translate('Configure your Droploo API connection settings') }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4 pb-4">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data"
                        class="mb-5">
                        @csrf

                        <div class="card mb-4">
                            <div class="card-header bg-soft-warning d-flex align-items-center">
                                <i class="las la-key la-2x mr-2 text-warning"></i>
                                <h5 class="mb-0">{{ translate('Droploo API Credentials') }}</h5>
                            </div>
                            <div class="card-body">

                                {{-- Username --}}
                                <div class="form-group row">
                                    <label
                                        class="col-sm-3 col-from-label font-weight-bold">{{ translate('Username') }}</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="hidden" name="types[]" value="droploo_username">
                                            <input type="text" name="droploo_username" class="form-control"
                                                id="droploo_username" value="{{ get_setting('droploo_username') }}"
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
                                {{-- App Key --}}
                                <div class="form-group row">
                                    <label
                                        class="col-sm-3 col-from-label font-weight-bold">{{ translate('App Key') }}</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="hidden" name="types[]" value="droploo_app_key">
                                            <input type="text" name="droploo_app_key" class="form-control"
                                                id="droploo_app_key" value="{{ get_setting('droploo_app_key') }}"
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

                                {{-- App Secret (with show/hide and copy) --}}
                                <div class="form-group row">
                                    <label
                                        class="col-sm-3 col-from-label font-weight-bold">{{ translate('App Secret') }}</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="hidden" name="types[]" value="droploo_app_secret">
                                            <input type="password" name="droploo_app_secret" class="form-control"
                                                id="droploo_app_secret" value="{{ get_setting('droploo_app_secret') }}"
                                                placeholder="{{ translate('Enter Droploo App Secret') }}">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    onclick="togglePasswordVisibility('droploo_app_secret', 'toggleIconDroplooSecret')">
                                                    <i class="lar la-eye" id="toggleIconDroplooSecret"></i>
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


                            </div>
                        </div>

                        <div class="alert alert-soft-info border-0 d-flex align-items-center mt-3"
                            style="background-color: #e9f5fe; color: #31708f;" role="alert">
                            <i class="las la-info-circle mr-2 font-medium-3"></i>
                            <span
                                class="small font-weight-medium">{{ translate('After updating these credentials, you can manage Droploo products from the Products section.') }}</span>
                        </div>

                        <div class="text-right mt-3">
                            <button type="submit"
                                class="btn btn-primary px-4 shadow-sm">{{ translate('Update Droploo') }}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-9 mx-auto">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-soft-primary text-primary mr-3 rounded-circle p-2"
                            style="background: rgba(0,123,255,0.1);">
                            <i class="las la-truck font-medium-3"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 h5 text-dark font-weight-bold">
                                {{ translate('Steadfast Courier Credentials') }}
                            </h5>
                            <p class="text-muted mb-0 small">
                                {{ translate('Configure your Steadfast Courier API connection settings') }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data"
                        class="mb-5">
                        @csrf

                        {{-- Steadfast API Key --}}
                        <div class="form-group row align-items-center">
                            <label
                                class="col-md-3 col-form-label font-weight-medium text-muted">{{ translate('Steadfast API Key') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="steadfast_api_key">
                                <div class="input-group">
                                    <input type="text" name="steadfast_api_key" class="form-control"
                                        id="steadfast_api_key" value="{{ get_setting('steadfast_api_key') }}"
                                        placeholder="Enter Steadfast API Key">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary copy-btn" type="button"
                                            data-target="#steadfast_api_key" title="{{ translate('Copy') }}">
                                            <i class="las la-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <small
                                    class="form-text text-muted mt-1">{{ translate('API Key provided by Steadfast Courier Ltd.') }}</small>
                            </div>
                        </div>

                        {{-- Steadfast Secret Key --}}
                        <div class="form-group row align-items-center">
                            <label
                                class="col-md-3 col-form-label font-weight-medium text-muted">{{ translate('Steadfast Secret Key') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="steadfast_secret_key">
                                <div class="input-group">
                                    <input type="password" name="steadfast_secret_key" class="form-control"
                                        id="steadfast_secret_key" value="{{ get_setting('steadfast_secret_key') }}"
                                        placeholder="Enter Steadfast Secret Key">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePasswordVisibility('steadfast_secret_key', 'toggleIconSteadfast')">
                                            <i class="lar la-eye" id="toggleIconSteadfast"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary copy-btn" type="button"
                                            data-target="#steadfast_secret_key" title="{{ translate('Copy') }}">
                                            <i class="las la-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <small
                                    class="form-text text-muted mt-1">{{ translate('Secret Key provided by Steadfast Courier Ltd.') }}</small>
                            </div>
                        </div>

                        <div class="text-right mt-3">
                            <button type="submit"
                                class="btn btn-primary px-4 shadow-sm">{{ translate('Update Steadfast') }}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-9 mx-auto">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-soft-danger text-danger mr-3 rounded-circle p-2"
                            style="background: rgba(220,53,69,0.1);">
                            <i class="las la-shield-alt font-medium-3"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 h5 text-dark font-weight-bold">{{ translate('Fraud Checker Credentials') }}
                            </h5>
                            <p class="text-muted mb-0 small">
                                {{ translate('Protect orders with automated fraud detection and courier validation') }}</p>
                        </div>
                    </div>
                    <a href="https://fraudbd.com/" target="_blank" class="btn btn-light btn-sm shadow-sm">
                        <i class="las la-external-link-alt mr-1"></i>
                        {{ translate('View Pricing') }}
                    </a>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Fraud Checker URL (read-only) --}}
                        <div class="form-group row align-items-center">
                            <label
                                class="col-md-3 col-form-label font-weight-medium text-muted">{{ translate('Fraud Checker URL') }}</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control bg-light text-muted border-dashed" readonly
                                        value="https://api.bdcourier.com/courier-check" style="border-style: dashed;"
                                        id="fraud_checker_url">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary copy-btn" type="button"
                                            data-target="#fraud_checker_url" title="{{ translate('Copy') }}">
                                            <i class="las la-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Fraud Checker Secret Key --}}
                        <div class="form-group row align-items-center">
                            <label
                                class="col-md-3 col-form-label font-weight-medium text-muted">{{ translate('Fraud Checker Secret Key') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="fraud_checker_api_key">
                                <div class="input-group">
                                    <input type="password" name="fraud_checker_api_key" class="form-control"
                                        id="fraud_checker_api_key" value="{{ get_setting('fraud_checker_api_key') }}"
                                        placeholder="Enter Fraud Checker Secret Key">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePasswordVisibility('fraud_checker_api_key', 'toggleIconFraudChecker')">
                                            <i class="lar la-eye" id="toggleIconFraudChecker"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary copy-btn" type="button"
                                            data-target="#fraud_checker_api_key" title="{{ translate('Copy') }}">
                                            <i class="las la-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <small
                                    class="form-text text-muted mt-1">{{ translate('Secret Key provided by Fraud Checker') }}</small>
                            </div>
                        </div>

                        <div class="text-right mt-3">
                            <button type="submit"
                                class="btn btn-primary px-4 shadow-sm">{{ translate('Update Fraud Checker') }}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-9 mx-auto">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-soft-success text-success mr-3 rounded-circle p-2"
                            style="background: rgba(25,135,84,0.1);">
                            <i class="las la-sms font-medium-3"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 h5 text-dark font-weight-bold">{{ translate('BulkSMSBD Credentials') }}</h5>
                            <p class="text-muted mb-0 small">
                                {{ translate('API credentials for BulkSMSBD SMS gateway') }}</p>
                        </div>
                    </div>
                    <a href="https://bulksmsbd.com/bulksms-price.php" target="_blank"
                        class="btn btn-light btn-sm shadow-sm">
                        <i class="las la-external-link-alt mr-1"></i>
                        {{ translate('View Pricing') }}
                    </a>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('env_key_update.update') }}" method="POST">
                        @csrf

                        {{-- Sender ID --}}
                        <div class="form-group row align-items-center">
                            <label
                                class="col-md-3 col-form-label font-weight-medium text-muted">{{ translate('Sender ID') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="BULKSMSBD_SENDER_ID">
                                <div class="input-group">
                                    <input type="text" name="BULKSMSBD_SENDER_ID" class="form-control"
                                        id="bulksmsbd_sender_id" value="{{ env('BULKSMSBD_SENDER_ID') }}"
                                        placeholder="Enter approved Sender ID">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary copy-btn" type="button"
                                            data-target="#bulksmsbd_sender_id" title="{{ translate('Copy') }}">
                                            <i class="las la-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <small
                                    class="form-text text-muted mt-1">{{ translate('Your approved Sender ID from BulkSMSBD') }}</small>
                            </div>
                        </div>

                        {{-- API Key --}}
                        <div class="form-group row align-items-center">
                            <label
                                class="col-md-3 col-form-label font-weight-medium text-muted">{{ translate('API Key') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="BULKSMSBD_API_KEY">
                                <div class="input-group">
                                    <input type="password" name="BULKSMSBD_API_KEY" class="form-control"
                                        id="bulksmsbd_api_key" value="{{ env('BULKSMSBD_API_KEY') }}"
                                        placeholder="Enter BulkSMSBD API Key">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePasswordVisibility('bulksmsbd_api_key', 'toggleIconApiKey')">
                                            <i class="lar la-eye" id="toggleIconApiKey"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary copy-btn" type="button"
                                            data-target="#bulksmsbd_api_key" title="{{ translate('Copy') }}">
                                            <i class="las la-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="form-text text-muted mt-1">{{ translate('Your BulkSMSBD API key') }}</small>
                            </div>
                        </div>



                        <div class="text-right mt-3">
                            <button type="submit"
                                class="btn btn-primary px-4 shadow-sm">{{ translate('Update BulkSMSBD') }}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <div class="row mt-4">
        <div class="col-lg-9 mx-auto">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-soft-primary text-primary mr-3 rounded-circle p-2"
                            style="background: rgba(0,123,255,0.1);">
                            <i class="las la-shipping-fast font-medium-3"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 h5 text-dark font-weight-bold">{{ translate('Pathao Courier Credentials') }}
                            </h5>
                            <p class="text-muted mb-0 small">
                                {{ translate('Manage your Pathao Courier API connection settings') }}</p>
                        </div>
                    </div>
                    <a href="https://merchant.pathao.com/login" target="_blank" class="btn btn-light btn-sm shadow-sm">
                        <i class="las la-external-link-alt mr-1"></i>
                        {{ translate('View Dashboard') }}
                    </a>
                </div>
                <div class="card-body px-4 pb-4">
                    <form class="form-horizontal" action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        {{-- Pathao Client ID --}}
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{ translate('Pathao Client ID') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="pathao_client_id">
                                <div class="input-group">
                                    <input type="text" name="pathao_client_id" class="form-control"
                                        id="pathao_client_id" value="{{ get_setting('pathao_client_id') }}"
                                        placeholder="Enter Pathao Client ID">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary copy-btn" type="button"
                                            data-target="#pathao_client_id" title="{{ translate('Copy') }}">
                                            <i class="las la-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <small
                                    class="form-text text-muted">{{ translate('Client ID provided by Pathao Courier Ltd.') }}</small>
                            </div>
                        </div>

                        {{-- Pathao Client Secret --}}
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{ translate('Pathao Client Secret') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="pathao_client_secret">
                                <div class="input-group">
                                    <input type="password" name="pathao_client_secret" class="form-control"
                                        id="pathao_client_secret" value="{{ get_setting('pathao_client_secret') }}"
                                        placeholder="Enter Pathao Client Secret">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePasswordVisibility('pathao_client_secret', 'toggleIconPathaoSecret')">
                                            <i class="lar la-eye" id="toggleIconPathaoSecret"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary copy-btn" type="button"
                                            data-target="#pathao_client_secret" title="{{ translate('Copy') }}">
                                            <i class="las la-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <small
                                    class="form-text text-muted">{{ translate('Secret Key provided by Pathao Courier Ltd.') }}</small>
                            </div>
                        </div>

                        {{-- Pathao Username --}}
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{ translate('Pathao Username') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="pathao_username">
                                <div class="input-group">
                                    <input type="email" name="pathao_username" class="form-control"
                                        id="pathao_username" value="{{ get_setting('pathao_username') }}"
                                        placeholder="Enter Pathao dashboard email">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary copy-btn" type="button"
                                            data-target="#pathao_username" title="{{ translate('Copy') }}">
                                            <i class="las la-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <small
                                    class="form-text text-muted">{{ translate('Email used for Pathao Courier authentication.') }}</small>
                            </div>
                        </div>

                        {{-- Pathao Password --}}
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{ translate('Pathao Password') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="pathao_password">
                                <div class="input-group">
                                    <input type="password" name="pathao_password" class="form-control"
                                        id="pathao_password" value="{{ get_setting('pathao_password') }}"
                                        placeholder="Enter Pathao dashboard password">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePasswordVisibility('pathao_password', 'toggleIconPathaoPassword')">
                                            <i class="lar la-eye" id="toggleIconPathaoPassword"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary copy-btn" type="button"
                                            data-target="#pathao_password" title="{{ translate('Copy') }}">
                                            <i class="las la-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <small
                                    class="form-text text-muted">{{ translate('Password used for Pathao Courier authentication.') }}</small>
                            </div>
                        </div>

                        {{-- Pathao API Base URL --}}
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{ translate('Pathao API Base URL') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="pathao_base_url">
                                <div class="input-group">
                                    <input type="text" name="pathao_base_url" class="form-control"
                                        id="pathao_base_url"
                                        value="{{ get_setting('pathao_base_url', 'https://api-hermes.pathao.com') }}"
                                        placeholder="https://api-hermes.pathao.com">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary copy-btn" type="button"
                                            data-target="#pathao_base_url" title="{{ translate('Copy') }}">
                                            <i class="las la-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <small
                                    class="form-text text-muted">{{ translate('Use https://api-hermes.pathao.com for live and https://courier-api-sandbox.pathao.com for sandbox.') }}</small>
                            </div>
                        </div>

                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function togglePasswordVisibility(inputId, iconId) {
            var passwordInput = document.getElementById(inputId);
            var toggleIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('lar', 'la-eye');
                toggleIcon.classList.add('las', 'la-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('las', 'la-eye-slash');
                toggleIcon.classList.add('lar', 'la-eye');
            }
        }
    </script>

    <script>
        $(document).ready(function() {
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
