@extends('backend.layouts.app')

@section('content')

    <div class="row">
    	<div class="col-lg-9 mx-auto">
    		<div class="card">
    			<div class="card-header">
    				<h6 class="fw-600 mb-0">{{ translate('General') }}</h6>
    			</div>
    			<div class="card-body">
    				<form action="{{ route('business_settings.update') }}" method="POST">
    					@csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Front Store Name')}}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="website_name">
        	                    <input type="text" name="website_name" class="form-control" placeholder="{{ translate('Website Name') }}" value="{{ get_setting('website_name') }}">
                            </div>
                        </div>
                        <div class="form-group row">
    	                    <label class="col-md-3 col-from-label">{{translate('Front Store Motto')}}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="site_motto">
        	                    <input type="text" name="site_motto" class="form-control" placeholder="{{ translate('Best eCommerce Website') }}" value="{{  get_setting('site_motto') }}">
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
        							<input type="hidden" name="site_icon" value="{{ get_setting('site_icon') }}" class="selected-files">
        						</div>
        						<div class="file-preview box"></div>
        						<small class="text-muted">{{ translate('Website favicon. 32x32 .png') }}</small>
                            </div>
    					</div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Website Base Color') }}</label>
                            <div class="col-md-8 d-flex align-items-center gap-2">
                                <input type="hidden" name="types[]" value="base_color">
                                <input type="color" id="base_color_picker" class="form-control form-control-color" value="{{ get_setting('base_color') ?? '#f04d6e' }}" title="Choose color">
                                <input type="text" name="base_color" id="base_color_input" class="form-control" placeholder="#f04d6e" value="{{ get_setting('base_color') ?? '#f04d6e' }}">
                            </div>
                            <small class="text-muted offset-md-3 col-md-8">{{ translate('Hex Color Code') }}</small>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Website Base Font Color') }}</label>
                            <div class="col-md-8 d-flex align-items-center gap-2">
                                <input type="hidden" name="types[]" value="base_hov_color">
                                <input type="color" id="base_hov_color_picker" class="form-control form-control-color" value="{{ get_setting('base_hov_color') ?? '#f04d6e' }}" title="Choose color">
                                <input type="text" name="base_hov_color" id="base_hov_color_input" class="form-control" placeholder="#f04d6e" value="{{ get_setting('base_hov_color') ?? '#f04d6e' }}">
                            </div>
                            <small class="text-muted offset-md-3 col-md-8">{{ translate('Hex Color Code') }}</small>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Whatsapp Number')}}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="whatsapp_number">
        	                    <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="whatsapp_number" class="form-control" placeholder="{{ translate('Whatsapp Number') }}" value="{{ get_setting('whatsapp_number') }}">

                                <small class="text-danger fw-medium" style="font-size: 13px">
                                    WhatsApp number must include the country code. Example: <strong>8801700000000</strong>
                                </small>
                            </div>
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
    		<div class="card">
    			<div class="card-header">
    				<h6 class="fw-600 mb-0">{{ translate('Global SEO') }}</h6>
    			</div>
    			<div class="card-body">
    				<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    					@csrf
    					<div class="form-group row">
    						<label class="col-md-3 col-from-label">{{ translate('Meta Title') }}</label>
                            <div class="col-md-8">
        						<input type="hidden" name="types[]" value="meta_title">
        						<input type="text" class="form-control" placeholder="{{translate('Title')}}" name="meta_title" value="{{ get_setting('meta_title') }}">
                            </div>
    					</div>
    					<div class="form-group row">
    						<label class="col-md-3 col-from-label">{{ translate('Meta description') }}</label>
                            <div class="col-md-8">
        						<input type="hidden" name="types[]" value="meta_description">
        						<textarea class="resize-off form-control" placeholder="{{translate('Description')}}" name="meta_description">{{  get_setting('meta_description') }}</textarea>
                            </div>
    					</div>
    					<div class="form-group row">
    						<label class="col-md-3 col-from-label">{{ translate('Keywords') }}</label>
                            <div class="col-md-8">
        						<input type="hidden" name="types[]" value="meta_keywords">
        						<textarea class="resize-off form-control" placeholder="{{translate('Keyword, Keyword')}}" name="meta_keywords">{{ get_setting('meta_keywords') }}</textarea>
        						<small class="text-muted">{{ translate('Separate with coma') }}</small>
                            </div>
    					</div>
    					<div class="form-group row">
    						<label class="col-md-3 col-from-label">{{ translate('Meta Image') }}</label>
                            <div class="col-md-8">
        						<div class="input-group " data-toggle="aizuploader" data-type="image">
        							<div class="input-group-prepend">
        								<div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
        							</div>
        							<div class="form-control file-amount">{{ translate('Choose File') }}</div>
        							<input type="hidden" name="types[]" value="meta_image">
        							<input type="hidden" name="meta_image" value="{{ get_setting('meta_image') }}" class="selected-files">
        						</div>
        						<div class="file-preview box"></div>
                            </div>
    					</div>
    					<div class="text-right">
    						<button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
    					</div>
    				</form>
    			</div>
    		</div>
    	</div>
    </div>

@endsection
