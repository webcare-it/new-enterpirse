@extends('backend.layouts.app')

@section('content')
    <h4 class="text-center text-muted">{{ translate('OTP SMS Provider') }}</h4>
    <div class="row">
        @php
            $active_provider = \App\Models\OtpConfiguration::where('type', 'active_provider')->value('value');
        @endphp
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 h6">{{ translate('BulkSMSBD') }}</h3>
                </div>
                <div class="card-body text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateProvider(this, 'bulksmsbd')"
                            @if ($active_provider == 'bulksmsbd') checked @endif>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <h4 class="text-center text-muted mt-4">{{ translate('OTP will be Used For') }}</h4>
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 h6">{{ translate('Phone Number Verification') }}</h3>
                </div>
                <div class="card-body text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'phone_verification_otp')"
                            @if (get_setting('phone_verification_otp') == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 h6">{{ translate('Forget Password') }}</h3>
                </div>
                <div class="card-body text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'forget_password_otp')"
                            @if (get_setting('forget_password_otp') == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 h6">{{ translate('Checkout Verification') }}</h3>
                </div>
                <div class="card-body text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'otp_for_order')"
                            @if (get_setting('otp_for_order') == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 h6">{{ translate('Delivery Status Changing') }}</h3>
                </div>
                <div class="card-body text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'otp_for_delivery_status')"
                            @if (get_setting('otp_for_delivery_status') == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 h6">{{ translate('Payment Status Changing') }}</h3>
                </div>
                <div class="card-body text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'otp_for_paid_status')"
                            @if (get_setting('otp_for_paid_status') == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function updateProvider(el, provider) {
            if ($(el).is(':checked')) {
                var value = provider;
            } else {
                var value = '';
            }
            $.post('{{ route('otp_configurations.update.activation') }}', {
                _token: '{{ csrf_token() }}',
                value: value
            }, function(data) {
                if (data == '1') {
                    AIZ.plugins.notify('success', '{{ translate('Settings updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function updateSettings(el, type) {
            if ($(el).is(':checked')) {
                var value = 1;
            } else {
                var value = 0;
            }
            $.post('{{ route('business_settings.update.activation') }}', {
                _token: '{{ csrf_token() }}',
                type: type,
                value: value
            }, function(data) {
                if (data == '1') {
                    AIZ.plugins.notify('success', '{{ translate('Settings updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
