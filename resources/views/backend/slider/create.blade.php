@extends('backend.layouts.app')

@section('content')
    @include('backend.section._tabs')


    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <form action="{{ route('slider.store') }}" method="POST">
                    @csrf
                    <!-- Slider Info -->
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Slider Information</h5>
                            <a href="{{ route('slider.index') }}" class="btn btn-secondary btn-sm">
                                {{ translate('Back') }}
                            </a>
                        </div>

                        <div class="card-body">
                            <!-- Title -->
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Title</label>
                                <div class="col-md-9">
                                    <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                                        placeholder="Enter title" required>
                                </div>
                            </div>

                            <!-- Sub Title -->
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Sub Title</label>
                                <div class="col-md-9">
                                    <input type="text" name="sub_title" class="form-control"
                                        value="{{ old('sub_title') }}" placeholder="Enter sub title" required>
                                </div>
                            </div>

                            <!-- Image -->
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Image') }}
                                    <small>(600x600)</small></label>
                                <div class="col-md-9">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="photos" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                    <small class="text-muted">{{ translate('These images are visible in slider') }}</small>
                                </div>
                            </div>


                            <!-- Button Name -->
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Button Name</label>
                                <div class="col-md-9">
                                    <input type="text" name="button_name" class="form-control"
                                        value="{{ old('button_name') }}" placeholder="e.g. Shop Now" required>
                                </div>
                            </div>

                            <!-- Button Link -->
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Button Link</label>
                                <div class="col-md-9">
                                    <input type="url" name="button_link" class="form-control"
                                        value="{{ old('button_link') }}" placeholder="https://example.com">
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="col-12">
                            <div class="btn-toolbar float-right mb-3" role="toolbar"
                                aria-label="Toolbar with button groups">
                                <div class="btn-group mr-2" role="group" aria-label="Third group">
                                    <button type="submit" name="button" value="unpublish"
                                        class="btn btn-primary">{{ translate('Save & Unpublish') }}</button>
                                </div>
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <button type="submit" name="button" value="publish"
                                        class="btn btn-success">{{ translate('Save & Publish') }}</button>
                                </div>
                            </div>
                        </div>

                </form>
            </div>
        </div>
    </div>
@endsection
