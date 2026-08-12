<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropshipper Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .dashboard-card {
            border: none;
            border-radius: 18px;
        }

        .topbar {
            background: white;
            padding: 18px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>

    {{-- Topbar --}}
    <div class="topbar d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-0">Dropshipper Dashboard</h4>
        </div>

        <div class="d-flex align-items-center gap-3">

            <span class="fw-semibold">
                {{ auth('dropshipper')->user()->name }}
            </span>

            <form action="{{ route('dropshipper.logout') }}" method="POST">
                @csrf

                <button class="btn btn-danger btn-sm">
                    Logout
                </button>
            </form>

        </div>
    </div>

    <div class="container py-5">

        <div class="row g-4">

            <div class="col-md-4">
                <div class="card dashboard-card shadow-sm p-4">

                    <h5>Total Products</h5>

                    <h2 class="fw-bold text-primary">
                        120
                    </h2>

                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-card shadow-sm p-4">

                    <h5>Total Orders</h5>

                    <h2 class="fw-bold text-success">
                        58
                    </h2>

                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-card shadow-sm p-4">

                    <h5>Total Earnings</h5>

                    <h2 class="fw-bold text-warning">
                        $1,250
                    </h2>

                </div>
            </div>

        </div>

        <div class="card shadow-sm border-0 rounded-4 mt-5">
            <div class="card-body p-4">

                <h4 class="mb-3">
                    Welcome,
                    {{ auth('dropshipper')->user()->name }}
                </h4>

                <p class="text-muted mb-0">
                    You are successfully logged into the dropshipper panel.
                </p>

            </div>
        </div>

    </div>

</body>

</html>
