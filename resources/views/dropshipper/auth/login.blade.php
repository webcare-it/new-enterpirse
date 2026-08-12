<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropshipper Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
        }

        .login-wrapper {
            min-height: 100vh;
        }

        .login-card {
            width: 100%;
            max-width: 450px;
            border: none;
            border-radius: 20px;
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            color: white;
            padding: 35px;
            text-align: center;
        }

        .form-control {
            height: 50px;
            border-radius: 12px;
        }

        .btn-login {
            height: 50px;
            border-radius: 12px;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center align-items-center login-wrapper">

            <div class="col-lg-5">
                <div class="card shadow-lg login-card">

                    <div class="login-header">
                        <h2 class="mb-1">Dropshipper Login</h2>
                        <p class="mb-0">Login to your dashboard</p>
                    </div>

                    <div class="card-body p-4 p-lg-5">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('dropshipper.login') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Email Address</label>

                                <input type="email" name="email" class="form-control" placeholder="Enter email"
                                    value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Password</label>

                                <div class="position-relative">
                                    <input type="password" name="password" id="password" class="form-control pe-5"
                                        placeholder="Enter password" required>

                                    <span onclick="togglePassword()"
                                        style="
                                            position:absolute;
                                            right:15px;
                                            top:50%;
                                            transform:translateY(-50%);
                                            cursor:pointer;
                                        ">
                                        👁️
                                    </span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-login">
                                Login
                            </button>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function togglePassword() {

            let password = document.getElementById('password');

            if (password.type === 'password') {
                password.type = 'text';
            } else {
                password.type = 'password';
            }
        }
    </script>

</body>

</html>
