@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Create Category') }}</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">

            <form action="{{ route('dropshipping-category.store') }}" method="POST">
                @csrf
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
                                <input type="text" name="category_name" value="{{ old('category_name') }}"
                                    class="form-control" placeholder="{{ translate('Enter category name') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Category Image') }}
                                <small>({{ translate('400z400') }})</small>
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
                                    <input type="hidden" name="category_image" value="{{ old('category_image') }}"
                                        class="selected-files">
                                </div>

                                <div class="file-preview box sm mt-2"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Category Icon') }}
                                <small>({{ translate('200X200') }})</small>
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
                                    <input type="hidden" name="icon" value="{{ old('icon') }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm mt-2"></div>
                            </div>


                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Position') }}</label>
                            <div class="col-md-9">
                                <input type="number" name="position" value="{{ old('position', 0) }}" class="form-control"
                                    min="0">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="text-right mb-3">
                    <button type="submit" class="btn btn-success">
                        {{ translate('Save Category') }}
                    </button>
                </div>

            </form>

        </div>
    </div>
@endsection
