@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-9 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Header Setting') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Front Store Name') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="website_name">
                                <input type="text" name="website_name" class="form-control"
                                    placeholder="{{ translate('Website Name') }}" value="{{ get_setting('website_name') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Front Store Motto') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="site_motto">
                                <input type="text" name="site_motto" class="form-control"
                                    placeholder="{{ translate('Best eCommerce Website') }}"
                                    value="{{ get_setting('site_motto') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Help line number') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="helpline_number">
                                <input type="text" class="form-control" placeholder="{{ translate('Help line number') }}"
                                    name="helpline_number" value="{{ get_setting('helpline_number') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Top bar Offer Text (120char)') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="top_bar_offer">
                                <input type="text" name="top_bar_offer" class="form-control"
                                    placeholder="{{ translate('Top bar Offer Text') }}"
                                    value="{{ get_setting('top_bar_offer') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Front Store Favicon') }}</label>
                            <div class="col-md-9">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="site_icon">
                                    <input type="hidden" name="site_icon" value="{{ get_setting('site_icon') }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                                <small class="text-muted">{{ translate('Website favicon. 32x32 .png') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Header Logo') }}</label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="header_logo">
                                    <input type="hidden" name="header_logo" class="selected-files"
                                        value="{{ get_setting('header_logo') }}">
                                </div>
                                <div class="file-preview"></div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
