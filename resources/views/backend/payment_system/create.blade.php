@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Create Payment System') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('paymentsystem.index') }}" class="btn btn-primary">
                    {{ translate('Back to List') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <form action="{{ route('paymentsystem.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Payment System Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- Title -->
                        <div class="form-group">
                            <label>{{ translate('Title') }} <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}" placeholder="{{ translate('Enter payment system title') }}">
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="form-group">
                            <label>{{ translate('Type') }} <span class="text-danger">*</span></label>
                            <input type="text" name="type" class="form-control @error('type') is-invalid @enderror"
                                value="{{ old('type') }}" placeholder="{{ translate('e.g. bkash, nagad, paypal') }}">
                            @error('type')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Image') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="image" value="{{ old('image') }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                                @error('image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Is Default -->
                        <div class="form-group">
                            <label class="d-block">{{ translate('Set as Default') }}</label>
                            <label class="aiz-checkbox">
                                <input type="checkbox" name="is_default" value="1"
                                    {{ old('is_default') ? 'checked' : '' }}>
                                <span class="aiz-square-check"></span>
                                <span>{{ translate('Make this payment system default') }}</span>
                            </label>
                            <small
                                class="text-muted d-block">{{ translate('If set as default, it will be selected by default during checkout. Only one can be default.') }}</small>
                        </div>

                        <!-- Submit -->
                        <div class="form-group text-right mb-0">
                            <button type="submit" class="btn btn-primary">
                                {{ translate('Save Payment System') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // If you need any custom JS, add here
    </script>
@endsection
