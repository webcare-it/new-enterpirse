
@extends('backend.layouts.app')

@section('content')
     <div class="aiz-body-wrapper">
        <div class="d-flex align-items-center justify-content-center" style="height: 70vh;">
            <div class="text-center">
                <h1 class="display-1 text-muted mb-0" style="font-size: 6rem;">401</h1>
                <h3 class="mb-3">{{ translate('Unauthorized') }}</h3>
                <p class="text-muted mb-4">{{ translate('You are not authenticated to access this page.') }}</p>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                    {{ translate('Back to Dashboard') }}
                </a>
            </div>
        </div>
    </div>
@endsection


