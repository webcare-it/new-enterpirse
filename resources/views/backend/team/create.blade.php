@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Create Team Member') }}</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">

            <form action="{{ route('team.store') }}" method="POST">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Team Information') }}</h5>
                    </div>

                    <div class="card-body">

                        {{-- Name --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Name') }}
                            </label>
                            <div class="col-md-9">
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                    placeholder="{{ translate('Enter name') }}" required>
                            </div>
                        </div>

                        {{-- Designation --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Designation') }}
                            </label>
                            <div class="col-md-9">
                                <input type="text" name="designation" value="{{ old('designation') }}"
                                    class="form-control" placeholder="{{ translate('e.g. CEO, Developer') }}">
                            </div>
                        </div>

                        {{-- Avatar --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Avatar') }}
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

                                    <input type="hidden" name="avatar" value="{{ old('avatar') }}"
                                        class="selected-files">
                                </div>

                                <div class="file-preview box sm mt-2"></div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Submit --}}
                <div class="text-right mb-3">
                    <button type="submit" class="btn btn-success">
                        {{ translate('Save Member') }}
                    </button>
                </div>

            </form>

        </div>
    </div>
@endsection
