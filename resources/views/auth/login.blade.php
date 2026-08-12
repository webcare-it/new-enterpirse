@extends('backend.layouts.layout')

@section('content')
    <div class="min-vh-100 d-flex">
        <div class="col-lg-7 d-none d-lg-block p-0">
            <div class="h-100 position-relative overflow-hidden"
                style="
                    background: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)),
                    url('{{ uploaded_asset(get_setting('admin_login_background')) }}');
                    background-size: cover;
                    background-position: center;
                ">

                <div class="position-absolute top-50 start-50 translate-middle text-center text-white px-5">
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-12 d-flex align-items-center justify-content-center bg-white">
            <div class="w-100 px-4 px-lg-5" style="max-width: 480px;">

                <div class="text-center mb-5">
                    @if (get_setting('system_logo_black') != null)
                        <img src="{{ uploaded_asset(get_setting('system_logo_black')) }}" class="mb-4" height="45">
                    @else
                        <img src="{{ static_asset('assets/img/logo.png') }}" class="mb-4" height="45">
                    @endif

                    <h2 class="fw-bold text-dark mb-2">
                        {{ translate('Welcome Back') }}
                    </h2>

                    <p class="text-muted mb-0">
                        {{ translate('Please login to your account') }}
                    </p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            {{ translate('Email Address') }}
                        </label>

                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="{{ translate('Enter your email') }}"
                            class="form-control form-control-lg rounded-3 shadow-sm @error('email') is-invalid @enderror">

                        @error('email')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            {{ translate('Password') }}
                        </label>

                        <div class="position-relative">

                            <input id="password" type="password" name="password" required
                                placeholder="{{ translate('Enter your password') }}"
                                class="form-control form-control-lg rounded-3 shadow-sm pe-5 @error('password') is-invalid @enderror">
                            <span onclick="togglePassword()"
                                style="position: absolute; top: 50%; right: 18px; transform: translateY(-50%); cursor: pointer; z-index: 5 line-height: 1;">
                                <i id="togglePasswordIcon" class="las la-eye text-muted" style="font-size: 22px;"></i>
                            </span>

                        </div>

                        @error('password')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <label class="aiz-checkbox mb-0">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <span>{{ translate('Remember Me') }}</span>
                            <span class="aiz-square-check"></span>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3 fw-semibold shadow-sm">
                        {{ translate('Login') }}
                    </button>
                </form>

                @if (app()->environment('local'))
                    <form action="{{ route('login') }}" method="POST" class="mt-3">
                        @csrf

                        <input type="hidden" name="email" value="admin@mail.com">
                        <input type="hidden" name="password" value="12345678">

                        <button type="submit" class="btn btn-success btn-lg w-100 rounded-3 fw-semibold">
                            Quick Admin Login
                        </button>
                    </form>
                @endif

                <div class="text-center mt-5">
                    <p class="text-muted small mb-0">
                        © {{ date('Y') }} {{ env('APP_NAME') }}.
                        {{ translate('All rights reserved.') }}
                    </p>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function autoFill() {
            $('#email').val('admin@example.com');
            $('#password').val('123456');
        }

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('la-eye');
                icon.classList.add('la-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('la-eye-slash');
                icon.classList.add('la-eye');
            }
        }
    </script>
@endsection
