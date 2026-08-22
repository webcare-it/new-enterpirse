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

                        <div class="form-group row align-items-center">
                            <label
                                class="col-md-3 col-form-label font-weight-medium text-muted">{{ translate('Droploo Username') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="droploo_username">
                                <input type="text" name="droploo_username" class="form-control bg-light-input"
                                    value="{{ get_setting('droploo_username') }}" placeholder="Enter Droploo Username">
                                <small
                                    class="form-text text-muted mt-1">{{ translate('Your Droploo account username') }}</small>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label
                                class="col-md-3 col-form-label font-weight-medium text-muted">{{ translate('Droploo App Key') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="droploo_app_key">
                                <input type="text" name="droploo_app_key" class="form-control"
                                    value="{{ get_setting('droploo_app_key') }}" placeholder="Enter Droploo App Key">
                                <small
                                    class="form-text text-muted mt-1">{{ translate('API key provided by Droploo') }}</small>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label
                                class="col-md-3 col-form-label font-weight-medium text-muted">{{ translate('Droploo App Secret') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="droploo_app_secret">
                                <div class="input-group shadow-sm-none">
                                    <input type="password" name="droploo_app_secret" class="form-control border-right-0"
                                        value="{{ get_setting('droploo_app_secret') }}"
                                        placeholder="Enter Droploo App Secret" id="droploo_app_secret">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-light border-left-0 text-muted"
                                            style="border: 1px solid #ced4da;"
                                            onclick="togglePasswordVisibility('droploo_app_secret', 'toggleIconDroploo')">
                                            <i class="lar la-eye" id="toggleIconDroploo"></i>
                                        </button>
                                    </div>
                                </div>
                                <small
                                    class="form-text text-muted mt-1">{{ translate('API secret provided by Droploo') }}</small>
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
                            <h5 class="mb-0 h5 text-dark font-weight-bold">{{ translate('Steadfast Courier Credentials') }}
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

                        <div class="form-group row align-items-center">
                            <label
                                class="col-md-3 col-form-label font-weight-medium text-muted">{{ translate('Steadfast API Key') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="steadfast_api_key">
                                <input type="text" name="steadfast_api_key" class="form-control"
                                    value="{{ get_setting('steadfast_api_key') }}" placeholder="Enter Steadfast API Key">
                                <small
                                    class="form-text text-muted mt-1">{{ translate('API Key provided by Steadfast Courier Ltd.') }}</small>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label
                                class="col-md-3 col-form-label font-weight-medium text-muted">{{ translate('Steadfast Secret Key') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="steadfast_secret_key">
                                <div class="input-group">
                                    <input type="password" name="steadfast_secret_key"
                                        class="form-control border-right-0"
                                        value="{{ get_setting('steadfast_secret_key') }}"
                                        placeholder="Enter Steadfast Secret Key" id="steadfast_secret_key">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-light border-left-0 text-muted"
                                            style="border: 1px solid #ced4da;"
                                            onclick="togglePasswordVisibility('steadfast_secret_key', 'toggleIconSteadfast')">
                                            <i class="lar la-eye" id="toggleIconSteadfast"></i>
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
                        <div class="form-group row align-items-center">
                            <label
                                class="col-md-3 col-form-label font-weight-medium text-muted">{{ translate('Fraud Checker URL') }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control bg-light text-muted border-dashed" readonly
                                    value="https://api.bdcourier.com/courier-check" style="border-style: dashed;">
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label
                                class="col-md-3 col-form-label font-weight-medium text-muted">{{ translate('Fraud Checker Secret Key') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="fraud_checker_api_key">
                                <div class="input-group">
                                    <input type="password" name="fraud_checker_api_key"
                                        class="form-control border-right-0"
                                        value="{{ get_setting('fraud_checker_api_key') }}"
                                        placeholder="Enter Fraud Checker Secret Key" id="fraud_checker_api_key">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-light border-left-0 text-muted"
                                            style="border: 1px solid #ced4da;"
                                            onclick="togglePasswordVisibility('fraud_checker_api_key', 'toggleIconFraudChecker')">
                                            <i class="lar la-eye" id="toggleIconFraudChecker"></i>
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


                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{ translate('Pathao Client ID') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="pathao_client_id">
                                <input type="text" name="pathao_client_id" class="form-control"
                                    value="{{ get_setting('pathao_client_id') }}" placeholder="Enter Pathao Client ID">
                                <small
                                    class="form-text text-muted">{{ translate('Client ID provided by Pathao Courier Ltd.') }}</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{ translate('Pathao Client Secret') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="pathao_client_secret">
                                <div class="input-group">
                                    <input type="password" name="pathao_client_secret" class="form-control"
                                        value="{{ get_setting('pathao_client_secret') }}"
                                        placeholder="Enter Pathao Client Secret" id="pathao_client_secret">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePasswordVisibility('pathao_client_secret', 'toggleIconPathao')">
                                            <i class="lar la-eye" id="toggleIconPathao"></i>
                                        </button>
                                    </div>
                                </div>
                                <small
                                    class="form-text text-muted">{{ translate('Secret Key provided by Pathao Courier Ltd.') }}</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{ translate('Pathao Username') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="pathao_username">
                                <input type="email" name="pathao_username" class="form-control"
                                    value="{{ get_setting('pathao_username') }}"
                                    placeholder="Enter Pathao dashboard email">
                                <small
                                    class="form-text text-muted">{{ translate('Email used for Pathao Courier authentication.') }}</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{ translate('Pathao Password') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="pathao_password">
                                <div class="input-group">
                                    <input type="password" name="pathao_password" class="form-control"
                                        value="{{ get_setting('pathao_password') }}"
                                        placeholder="Enter Pathao dashboard password" id="pathao_password">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePasswordVisibility('pathao_password', 'toggleIconPathaoPassword')">
                                            <i class="lar la-eye" id="toggleIconPathaoPassword"></i>
                                        </button>
                                    </div>
                                </div>
                                <small
                                    class="form-text text-muted">{{ translate('Password used for Pathao Courier authentication.') }}</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{ translate('Pathao API Base URL') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="pathao_base_url">
                                <input type="text" name="pathao_base_url" class="form-control"
                                    value="{{ get_setting('pathao_base_url', 'https://api-hermes.pathao.com') }}"
                                    placeholder="https://api-hermes.pathao.com">
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
@endsection
