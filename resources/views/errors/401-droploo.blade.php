
@extends('backend.layouts.app')

@section('content')
    <div class="row gutters-5 justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="las la-box-open la-6x text-muted"></i>
                    </div>
                    <h3 class="h3 mb-3">{{ translate('Product Not Found') }}</h3>
                    <p class="text-muted mb-4">
                        {{ translate('The product you are trying to access does not exist or may have been removed.') }}
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
