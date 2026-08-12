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
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Breadcrumb Image') }}</label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="breadcrumb_image">
                                    <input type="hidden" name="breadcrumb_image" class="selected-files"
                                        value="{{ get_setting('breadcrumb_image') }}">
                                </div>
                                <div class="file-preview"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Help line number') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="helpline_number">
                                <input type="text" class="form-control"
                                    placeholder="{{ translate('Help line number') }}" name="helpline_number"
                                    value="{{ get_setting('helpline_number') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Facebook Page Username') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="fb_page_username">
                                <input type="text" name="fb_page_username" class="form-control"
                                    placeholder="{{ translate('ExampleBD') }}"
                                    value="{{ get_setting('fb_page_username') }}"
                                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9._]/g, '')">
                                <small class="text-info fw-medium" style="font-size: 13px">
                                    Only accepted usernames no url. Example: <strong>yourpageusername</strong>
                                </small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Whatsapp Number') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="whatsapp_number">
                                <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    name="whatsapp_number" class="form-control"
                                    placeholder="{{ translate('Whatsapp Number') }}"
                                    value="{{ get_setting('whatsapp_number') }}">

                                <small class="text-info fw-medium" style="font-size: 13px">
                                    WhatsApp number must include the country code. Example: <strong>8801300000000</strong>
                                </small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label
                                class="col-md-3 col-from-label">{{ translate('Product Details Whatsapp Message') }}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="details_whatsapp_message">
                                <input type="text" name="details_whatsapp_message" class="form-control"
                                    placeholder="{{ translate('Product Details Whatsapp Message') }}"
                                    value="{{ get_setting('details_whatsapp_message') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Website Base Color') }}</label>
                            <div class="col-md-9 d-flex align-items-center gap-2">
                                <input type="hidden" name="types[]" value="base_color">
                                <input type="color" id="base_color_picker" class="form-control form-control-color"
                                    value="{{ get_setting('base_color') ?? '#f04d6e' }}" title="Choose color">
                                <input type="text" name="base_color" id="base_color_input" class="form-control"
                                    placeholder="#f04d6e" value="{{ get_setting('base_color') ?? '#f04d6e' }}">
                            </div>
                            <small class="text-muted offset-md-3 col-md-9">{{ translate('Hex Color Code') }}</small>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Website Base Font Color') }}</label>
                            <div class="col-md-9 d-flex align-items-center gap-2">
                                <input type="hidden" name="types[]" value="base_hov_color">
                                <input type="color" id="base_hov_color_picker" class="form-control form-control-color"
                                    value="{{ get_setting('base_hov_color') ?? '#f04d6e' }}" title="Choose color">
                                <input type="text" name="base_hov_color" id="base_hov_color_input"
                                    class="form-control" placeholder="#f04d6e"
                                    value="{{ get_setting('base_hov_color') ?? '#f04d6e' }}">
                            </div>
                            <small class="text-muted offset-md-3 col-md-9">{{ translate('Hex Color Code') }}</small>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Base color sync
                                const basePicker = document.getElementById('base_color_picker');
                                const baseInput = document.getElementById('base_color_input');
                                basePicker.addEventListener('input', () => baseInput.value = basePicker.value);
                                baseInput.addEventListener('input', () => basePicker.value = baseInput.value);

                                // Hover color sync
                                const hovPicker = document.getElementById('base_hov_color_picker');
                                const hovInput = document.getElementById('base_hov_color_input');
                                hovPicker.addEventListener('input', () => hovInput.value = hovPicker.value);
                                hovInput.addEventListener('input', () => hovPicker.value = hovInput.value);
                            });
                        </script>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
