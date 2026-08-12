@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Create Brand') }}</h1>
            </div>

            <div class="col-md-6 text-md-right">
                <a href="{{ route('brand.index') }}" class="btn btn-primary">
                    {{ translate('Back to List') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">

            <form action="{{ route('brand.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card">

                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Brand Information') }}</h5>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label>{{ translate('Brand Name') }}</label>

                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="Enter brand name">

                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Brand Image') }}
                            </label>

                            <div class="col-md-9">

                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">
                                            {{ translate('Browse') }}
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">
                                        {{ translate('Choose File') }}
                                    </div>
                                    <input type="hidden" name="brand_image" value="{{ old('brand_image') }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>

                                @error('brand_image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Background Image') }}
                            </label>

                            <div class="col-md-9">

                                <div class="input-group" data-toggle="aizuploader" data-type="image">

                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">
                                            {{ translate('Browse') }}
                                        </div>
                                    </div>

                                    <div class="form-control file-amount">
                                        {{ translate('Choose File') }}
                                    </div>

                                    <input type="hidden" name="bg_image" value="{{ old('bg_image') }}"
                                        class="selected-files">
                                </div>

                                <div class="file-preview box sm"></div>

                                @error('bg_image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ translate('Description') }}</label>

                            <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Enter description">{{ old('description') }}</textarea>

                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group text-right mb-0">
                            <button type="submit" class="btn btn-primary">
                                {{ translate('Save Brand') }}
                            </button>
                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>
@endsection
