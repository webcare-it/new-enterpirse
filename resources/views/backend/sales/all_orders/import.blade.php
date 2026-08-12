@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4>Import Orders from CSV</h4>
                    <a href="{{ route('orders.sample-csv') }}" class="btn btn-info btn-sm float-right">
                        Download Sample CSV
                    </a>
                </div>
                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('import_summary'))
                        <div class="alert alert-info">{{ session('import_summary') }}</div>
                    @endif

                    @if (session('import_errors'))
                        <div class="alert alert-warning">
                            <strong>Errors encountered:</strong>
                            <ul>
                                @foreach (session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('orders.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label>CSV File <span class="text-danger">*</span></label>
                            <input type="file" name="csv_file" class="form-control" accept=".csv,.txt" required>
                            <small class="text-muted">
                                Allowed: CSV files only. Max size: 10MB.
                            </small>
                        </div>

                        <div class="alert alert-info">
                            <strong>Required Columns:</strong>
                            <ul class="mb-0">
                                <li><code>customer_email</code> OR <code>customer_phone</code> - At least one required</li>
                                <li><code>grand_total</code> - Order total amount</li>
                            </ul>
                            <hr class="my-2">
                            <strong>Optional Columns:</strong>
                            <ul class="mb-0">
                                <li>order_code, customer_name, delivery_status, payment_status, payment_type</li>
                                <li>shipping_address, shipping_cost, coupon_discount, discount</li>
                                <li>order_date, notes, order_type, shipping_type</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Import Orders</button>
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
