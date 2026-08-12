@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Create Sub Category') }}</h1>
            </div>

            <div class="col-md-6 text-md-right">
                <a href="{{ route('subcategory.index') }}" class="btn btn-primary">
                    {{ translate('Back to List') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">

            <form action="{{ route('subcategory.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card">

                    {{-- Header --}}
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Sub Category Information') }}</h5>
                    </div>

                    <div class="card-body">

                        {{-- Category Select --}}
                        <div class="form-group">
                            <label>{{ translate('Select Category') }}</label>

                            <select name="category_id" class="form-control" required>
                                <option value="">{{ translate('Select Category') }}</option>

                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Name --}}
                        <div class="form-group">
                            <label>{{ translate('Sub Category Name') }}</label>

                            <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                placeholder="Enter sub category name" required>
                        </div>

                        {{-- Image --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Image') }}
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

                                    <input type="hidden" name="image" value="{{ old('image') }}"
                                        class="selected-files">

                                </div>

                                <div class="file-preview box sm"></div>

                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="form-group text-right mb-0">
                            <button type="submit" class="btn btn-primary">
                                {{ translate('Save Sub Category') }}
                            </button>
                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>
@endsection
