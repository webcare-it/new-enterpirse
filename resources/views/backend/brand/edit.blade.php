@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Edit Brand') }}</h1>
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

            <form action="{{ route('brand.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card">

                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Edit Brand') }}</h5>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label>{{ translate('Brand Name') }}</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $brand->name) }}">
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Slug') }}</label>
                            <input type="text" name="slug" class="form-control"
                                value="{{ old('slug', $brand->slug) }}">
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Position') }}</label>
                            <input type="number" name="position" class="form-control"
                                value="{{ old('position', $brand->position) }}">
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Brand Image') }}</label>

                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">
                                            {{ translate('Browse') }}
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">
                                        {{ $brand->brand_image ? translate('Change File') : translate('Choose File') }}
                                    </div>

                                    <input type="hidden" name="brand_image"
                                        value="{{ old('brand_image', $brand->brand_image) }}" class="selected-files">
                                </div>

                                @if ($brand->brand_image)
                                    <img src="{{ uploaded_asset($brand->brand_image) }}" class="mt-2"
                                        style="height:60px;">
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Background Image') }}</label>

                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">

                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">
                                            {{ translate('Browse') }}
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">
                                        {{ $brand->bg_image ? translate('Change File') : translate('Choose File') }}
                                    </div>

                                    <input type="hidden" name="bg_image" value="{{ old('bg_image', $brand->bg_image) }}"
                                        class="selected-files">
                                </div>

                                @if ($brand->bg_image)
                                    <img src="{{ uploaded_asset($brand->bg_image) }}" class="mt-2" style="height:60px;">
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Description') }}</label>
                            <textarea name="description" class="form-control" rows="5">{{ old('description', $brand->description) }}</textarea>
                        </div>
                        <div class="form-group text-right mb-0">
                            <button type="submit" class="btn btn-primary">
                                {{ translate('Update Brand') }}
                            </button>
                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>
@endsection
