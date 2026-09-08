@extends('backend.layouts.app')

@section('content')
    <div class="row gutters-5 justify-content-center">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">API Connection Information</h5>

                    <a href="/admin/credentials" class="btn btn-light btn-sm shadow-sm">
                        <i class="las la-external-link-alt mr-1"></i>
                        Configure API Credentials
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Username:</strong>
                                Not configured</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>App Key:</strong>
                                Not configured</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>App Secret:</strong>
                                Not configured</p>
                        </div>
                    </div>
                    <div class="alert alert-warning">
                        connect your API credentials to access the product data. Please configure your API credentials by
                        clicking the "Configure API Credentials" button above.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="las la-box-open la-6x text-muted"></i>
                    </div>
                    <h3 class="h3 mb-3">{{ translate('Product Not Found') }}</h3>
                    <p class="text-muted mb-4">
                        {{ translate('Api Credentials Not Configured') }}
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
