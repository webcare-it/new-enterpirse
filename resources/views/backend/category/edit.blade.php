@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Edit Category') }}</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">

            <form action="{{ route('dropshipping-category.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 h6">{{ translate('Category Information') }}</h5>
                        <a href="{{ route('dropshipping-category.index') }}" class="btn btn-secondary btn-sm">
                            {{ translate('Back') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Category Name') }}</label>
                            <div class="col-md-9">
                                <input type="text" name="category_name"
                                    value="{{ old('category_name', $category->category_name) }}" class="form-control"
                                    placeholder="{{ translate('Enter category name') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Category Image') }}
                                <small>({{ translate('400X400') }})</small>
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
                                    <input type="hidden" name="category_image"
                                        value="{{ old('category_image', $category->category_image) }}"
                                        class="selected-files">
                                </div>

                                <div class="file-preview box sm mt-2">
                                    @if ($category->category_image)
                                        <img src="{{ uploaded_asset($category->category_image) }}" class="img-fluid mt-2"
                                            style="max-height: 100px;">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Category Icon') }}
                                <small>({{ translate('200x200') }})</small>
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
                                    <input type="hidden" name="icon" value="{{ old('icon', $category->icon) }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm mt-2"></div>
                            </div>


                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Position') }}</label>
                            <div class="col-md-9">
                                <input type="number" name="position" value="{{ old('position', $category->position) }}"
                                    class="form-control" min="0">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="text-right mb-3">
                    <button type="submit" class="btn btn-primary">
                        {{ translate('Update Category') }}
                    </button>
                </div>

            </form>

        </div>
    </div>
@endsection
