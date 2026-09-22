@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Add New Product') }}</h5>
    </div>

    <div class="">
        <form class="form form-horizontal mar-top" action="{{ route('products.store') }}" method="POST"
            enctype="multipart/form-data" id="choice_form">
            @csrf
            <div class="row gutters-5">
                <div class="col-lg-10">

                    {{-- Basic Information --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Product Name') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="{{ translate('Product Name') }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Slug') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="slug" id="slug"
                                        placeholder="{{ translate('Product URL Slug (auto-generated if empty)') }}">
                                    <small
                                        class="text-muted">{{ translate('Leave empty to auto-generate from product name') }}</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Category') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="category_id" id="category_id"
                                        data-live-search="true" required>
                                        <option value="">{{ translate('Select Category') }}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Sub Category') }}</label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="subcategory_id" id="subcategory_id"
                                        data-live-search="true">
                                        <option value="">{{ translate('Select Sub Category') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Brand') }}</label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"
                                        data-live-search="true">
                                        <option value="">{{ translate('Select Brand') }}</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Unit') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="unit" value="Pc"
                                        placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Barcode') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="barcode"
                                        placeholder="{{ translate('Barcode') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Badge Name') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="badge_name" value="Hot"
                                        placeholder="{{ translate('e.g., Hot, New, Sale') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Batch No') }}</label>
                                <div class="col-md-8">
                                    <input type="number" class="form-control" name="batch_no" value="1"
                                        placeholder="{{ translate('Batch Number') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Tags') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control aiz-tag-input" name="tags[]"
                                        placeholder="{{ translate('Type and hit enter to add a tag') }}">
                                    <small
                                        class="text-muted">{{ translate('Used for search. Input words customers can find this product by.') }}</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Short Description') }}</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="short_description" rows="3"
                                        placeholder="{{ translate('Brief product summary') }}"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Full Description --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Description') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <textarea class="aiz-text-editor" name="description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Product Images --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Images') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">{{ translate('Gallery Images') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image"
                                        data-multiple="true">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}
                                            </div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="photos" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                    <small
                                        class="text-muted">{{ translate('These images appear in product gallery.') }}</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">{{ translate('Thumbnail Image') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}
                                            </div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="thumbnail_img" class="selected-files" required>
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Product Video --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Video') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Video Link') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="video_link"
                                        placeholder="{{ translate('YouTube or Vimeo video link') }}">
                                    <small class="text-muted">{{ translate('Use proper video URL') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Product Variation --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Variation') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" value="{{ translate('Colors') }}"
                                        disabled>
                                </div>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" data-live-search="true"
                                        data-selected-text-format="count" name="colors[]" id="colors" multiple
                                        disabled>
                                        @foreach ($colors as $color)
                                            <option value="{{ $color->code }}"
                                                data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>">
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="colors_active" value="1">
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" value="{{ translate('Attributes') }}"
                                        disabled>
                                </div>
                                <div class="col-md-8">
                                    <select name="choice_attributes[]" id="choice_attributes"
                                        class="form-control aiz-selectpicker" data-selected-text-format="count"
                                        data-live-search="true" multiple
                                        data-placeholder="{{ translate('Choose Attributes') }}">
                                        @foreach ($attributes as $attribute)
                                            <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="customer_choice_options" id="customer_choice_options"></div>
                        </div>
                    </div>

                    {{-- Product Price & Inventory --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Price & Inventory') }}</h5>
                        </div>
                        <div class="card-body">
                            {{-- Prices --}}
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Purchase Price') }}</label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" min="0" value="0" step="0.01"
                                        placeholder="{{ translate('Purchase Price') }}" name="purchase_price"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Regular Price') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" min="0" value="0" step="0.01"
                                        placeholder="{{ translate('Regular Price') }}" name="regular_price"
                                        class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Wholesale Price') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" min="0" value="0" step="0.01"
                                        placeholder="{{ translate('Wholesale Price') }}" name="wholesale_price"
                                        class="form-control" required>
                                </div>
                            </div>

                            {{-- Discount --}}
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Discount') }}</label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="0.01"
                                        placeholder="{{ translate('Discount') }}" name="discount" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control aiz-selectpicker" name="discount_type">
                                        <option value="flat">{{ translate('Flat') }}</option>
                                        <option value="percent">{{ translate('Percent') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Discount Date Range') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control aiz-date-range" name="date_range"
                                        placeholder="{{ translate('Select Date Range') }}" data-time-picker="true"
                                        data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                                </div>
                            </div>

                            {{-- Inventory --}}
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('SKU') }}</label>
                                <div class="col-md-8">
                                    <input type="text" placeholder="{{ translate('SKU (unique)') }}" name="sku"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Stock Quantity') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" min="0" value="100" step="1"
                                        placeholder="{{ translate('Stock Quantity') }}" name="stock"
                                        class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                    class="col-md-3 col-from-label">{{ translate('Low Stock Quantity Warning') }}</label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" min="0" value="1" step="1"
                                        placeholder="{{ translate('Low Stock Threshold') }}" name="low_stock_qty"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Track Inventory') }}</label>
                                <div class="col-md-8">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="track_inventory" value="1" checked>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Variant Combinations --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Variant Combinations') }}</h5>
                        </div>
                        <div class="card-body">
                            <div id="sku_combination"></div>
                        </div>
                    </div>

                    {{-- Shipping --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Shipping Configuration') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Shipping Type') }}</label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="shipping_type"
                                        id="shipping_type">
                                        <option value="flat_rate">{{ translate('Flat Rate') }}</option>
                                        <option value="free">{{ translate('Free Shipping') }}</option>
                                        <option value="local_pickup">{{ translate('Local Pickup') }}</option>
                                    </select>
                                    <small class="text-muted">
                                        {{ translate('Free Shipping / Local Pickup: no delivery charge for this product.') }}
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row" id="shipping_cost_div">
                                <label class="col-md-3 col-from-label">{{ translate('Shipping Cost') }}</label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" min="0" value="0" step="0.01"
                                        placeholder="{{ translate('Shipping Cost') }}" name="shipping_cost"
                                        class="form-control">
                                    <small class="text-muted">
                                        {{ translate('Keep 0 to use the shipping area charge chosen by the customer (e.g. Inside / Outside Dhaka), charged once per order. Enter an amount to charge this product its own delivery cost instead of the area charge.') }}
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Weight (kg)') }}</label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" min="0" value="0" step="0.01"
                                        placeholder="{{ translate('Weight') }}" name="weight" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Dimensions (L x W x H)') }}</label>
                                <div class="col-md-3">
                                    <input type="number" lang="en" min="0" step="0.01"
                                        placeholder="{{ translate('Length') }}" name="length" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" lang="en" min="0" step="0.01"
                                        placeholder="{{ translate('Width') }}" name="width" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" lang="en" min="0" step="0.01"
                                        placeholder="{{ translate('Height') }}" name="height" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SEO --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('SEO Meta Tags') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Meta Title') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="meta_title"
                                        placeholder="{{ translate('Meta Title') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Meta Description') }}</label>
                                <div class="col-md-8">
                                    <textarea name="meta_description" rows="4" class="form-control"
                                        placeholder="{{ translate('Meta Description for SEO') }}"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">{{ translate('Meta Image') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}
                                            </div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="meta_img" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> {{-- End col-lg-10    --}}

                {{-- Right Sidebar --}}
                <div class="col-lg-2">

                    {{-- Status --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Status') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ translate('Status') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="status" value="1" checked>
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ translate('Published') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="is_published" value="1" checked>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Featured & Badges --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Badges') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ translate('Featured') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="is_featured" value="1">
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ translate('Best Selling') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="best_selling" value="1">
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ translate('New Arrival') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="is_new_arrival" value="1">
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ translate("Today's Deal") }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="todays_deal" value="1">
                                        <span></span>
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Has Variant --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Type') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ translate('Has Variants') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="is_variant" id="is_variant" value="1" checked>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Display Order --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Display Settings') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="position">{{ translate('Display Position') }}</label>
                                <input type="number" class="form-control" name="position"
                                    placeholder="{{ translate('Sort order') }}" value="0">
                                <small class="text-muted">{{ translate('Lower numbers display first') }}</small>
                            </div>
                        </div>
                    </div>

                    {{-- Taxes --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Tax Configuration') }}</h5>
                        </div>
                        <div class="card-body" id="tax-container">
                            <div class="tax-row">
                                <div class="form-group">
                                    <label>{{ translate('Tax') }} <span class="text-danger">*</span></label>
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="tax_names[]"
                                            placeholder="{{ translate('Tax Name') }}">
                                        <input type="number" class="form-control" name="tax_values[]"
                                            placeholder="{{ translate('Value') }}" step="0.01">
                                        <select class="form-control" name="tax_types[]">
                                            <option value="percent">{{ translate('Percent') }}</option>
                                            <option value="flat">{{ translate('Flat') }}</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-danger remove-tax" type="button">-</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-info" id="add-tax">+
                                {{ translate('Add Tax') }}</button>
                        </div>
                    </div>

                </div> {{-- End col-lg-2 --}}

                {{-- Submit Buttons --}}
                <div class="col-12">
                    <div class="btn-toolbar float-right mb-3" role="toolbar">
                        <div class="btn-group mr-2">
                            <button type="submit" name="button" value="draft"
                                class="btn btn-warning">{{ translate('Save As Draft') }}</button>
                        </div>
                        <div class="btn-group">
                            <button type="submit" name="button" value="publish"
                                class="btn btn-success">{{ translate('Save & Publish') }}</button>
                        </div>
                    </div>
                </div>

            </div> {{-- End row --}}
        </form>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            // Auto generate slug from product name
            $('#name').on('keyup', function() {
                var name = $(this).val();
                var slug = name.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/--+/g, '-')
                    .trim();
                $('#slug').val(slug);
            });

            // Toggle color selection
            $('input[name="colors_active"]').on('change', function() {
                if (!$(this).is(':checked')) {
                    $('#colors').prop('disabled', true);
                } else {
                    $('#colors').prop('disabled', false);
                }
                AIZ.plugins.bootstrapSelect('refresh');
                update_sku();
            });

            // Hide/show shipping cost based on shipping type
            $('#shipping_type').on('change', function() {
                if ($(this).val() == 'flat_rate') {
                    $('#shipping_cost_div').show();
                } else {
                    $('#shipping_cost_div').hide();
                }
            }).trigger('change');

            // Is variant toggle
            $('#is_variant').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#customer_choice_options').show();
                    $('#choice_attributes').prop('disabled', false);
                } else {
                    $('#customer_choice_options').hide();
                    $('#choice_attributes').prop('disabled', true);
                }
                AIZ.plugins.bootstrapSelect('refresh');
            });

            // Tax row management
            $('#add-tax').on('click', function() {
                var taxHtml = `
                    <div class="tax-row">
                        <div class="form-group">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="tax_names[]" placeholder="Tax Name">
                                <input type="number" class="form-control" name="tax_values[]" placeholder="Value" step="0.01">
                                <select class="form-control" name="tax_types[]">
                                    <option value="percent">Percent</option>
                                    <option value="flat">Flat</option>
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-danger remove-tax" type="button">-</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#tax-container .tax-row:last').after(taxHtml);
            });

            $(document).on('click', '.remove-tax', function() {
                if ($('.tax-row').length > 1) {
                    $(this).closest('.tax-row').remove();
                }
            });

            // Category change - load subcategories
            $('#category_id').on('change', function() {
                var category_id = $(this).val();

                if (!category_id) {
                    $('#subcategory_id').html(
                        '<option value="">{{ translate('Select Sub Category') }}</option>');
                    AIZ.plugins.bootstrapSelect('refresh');
                    return;
                }

                $.ajax({
                    type: "POST", // Changed to POST since your route is POST
                    url: '{{ route('products.subcategories.get.json') }}', // Make sure this route name matches
                    data: {
                        category_id: category_id,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json', // Expect JSON response
                    success: function(response) {
                        var options =
                            '<option value="">{{ translate('Select Sub Category') }}</option>';

                        if (response.success && response.data.length > 0) {
                            $.each(response.data, function(index, subcategory) {
                                options += '<option value="' + subcategory.id + '">' +
                                    subcategory.name + '</option>';
                            });
                        } else {
                            options +=
                                '<option value="" disabled>{{ translate('No subcategories found') }}</option>';
                        }

                        $('#subcategory_id').html(options);
                        AIZ.plugins.bootstrapSelect('refresh');
                    },
                    error: function(xhr) {
                        console.error('Error loading subcategories:', xhr);
                        $('#subcategory_id').html(
                            '<option value="">{{ translate('Error loading subcategories') }}</option>'
                        );
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                });
            });


        });

        // Attribute selection for variants
        $('#choice_attributes').on('change', function() {
            $('#customer_choice_options').html(null);
            var selectedAttributes = $("#choice_attributes option:selected");

            if (selectedAttributes.length > 0) {
                $.each(selectedAttributes, function() {
                    add_more_customer_choice_option($(this).val(), $(this).text());
                });
            }
            update_sku();
        });

        function add_more_customer_choice_option(attribute_id, attribute_name) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{ route('products.add-more-choice-option') }}',
                data: {
                    attribute_id: attribute_id
                },
                success: function(response) {
                    // Check if response is JSON or HTML
                    var html = '';

                    if (typeof response === 'object' && response.success) {
                        // JSON response with data
                        var values = response.data;
                        html = '<div class="form-group row">' +
                            '<div class="col-md-3">' +
                            '<input type="hidden" name="choice_no[]" value="' + attribute_id + '">' +
                            '<input type="text" class="form-control" name="choice[]" value="' + attribute_name +
                            '" readonly>' +
                            '</div>' +
                            '<div class="col-md-8">' +
                            '<input type="text" class="form-control aiz-tag-input" name="choice_options_' +
                            attribute_id +
                            '[]" placeholder="{{ translate('Enter attribute values') }}" data-role="tagsinput">' +
                            '</div>' +
                            '<div class="col-md-1">' +
                            '<button type="button" class="btn btn-sm btn-danger" onclick="delete_row(this)">' +
                            '<i class="las la-trash"></i>' +
                            '</button>' +
                            '</div>' +
                            '</div>';
                    } else {
                        // HTML response with select options
                        var options = response;
                        html = '<div class="form-group row">' +
                            '<div class="col-md-3">' +
                            '<input type="hidden" name="choice_no[]" value="' + attribute_id + '">' +
                            '<input type="text" class="form-control" name="choice[]" value="' + attribute_name +
                            '" readonly>' +
                            '</div>' +
                            '<div class="col-md-8">' +
                            '<select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_' +
                            attribute_id + '[]" multiple>' +
                            options +
                            '</select>' +
                            '</div>' +
                            '<div class="col-md-1">' +
                            '<button type="button" class="btn btn-sm btn-danger" onclick="delete_row(this)">' +
                            '<i class="las la-trash"></i>' +
                            '</button>' +
                            '</div>' +
                            '</div>';
                    }

                    $('#customer_choice_options').append(html);

                    // Initialize tag input or selectpicker
                    if (typeof response === 'object' && response.success) {
                        $('.aiz-tag-input').tagsinput();
                    } else {
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                },
                error: function(xhr) {
                    console.error('Error loading attribute values:', xhr);
                    // Fallback: allow manual input
                    var html = '<div class="form-group row">' +
                        '<div class="col-md-3">' +
                        '<input type="hidden" name="choice_no[]" value="' + attribute_id + '">' +
                        '<input type="text" class="form-control" name="choice[]" value="' + attribute_id +
                        '" readonly>' +
                        '</div>' +
                        '<div class="col-md-8">' +
                        '<input type="text" class="form-control aiz-tag-input" name="choice_options_' +
                        attribute_id +
                        '[]" placeholder="{{ translate('Enter values (comma separated)') }}" data-role="tagsinput">' +
                        '</div>' +
                        '<div class="col-md-1">' +
                        '<button type="button" class="btn btn-sm btn-danger" onclick="delete_row(this)">' +
                        '<i class="las la-trash"></i>' +
                        '</button>' +
                        '</div>' +
                        '</div>';
                    $('#customer_choice_options').append(html);
                    $('.aiz-tag-input').tagsinput();
                }
            });
        }

        $(document).on("change", ".attribute_choice", function() {
            update_sku();
        });

        $('#colors').on('change', function() {
            update_sku();
        });

        $('input[name="regular_price"], input[name="sale_price"]').on('keyup', function() {
            update_sku();
        });

        function update_sku() {
            $.ajax({
                type: "POST",
                url: '{{ route('products.sku_combination') }}',
                data: $('#choice_form').serialize(),
                success: function(data) {
                    $('#sku_combination').html(data);
                    AIZ.uploader.previewGenerate();
                    AIZ.plugins.fooTable();

                    if (data.length > 10) { // Has variants
                        $('#show-hide-div').hide();
                    } else {
                        $('#show-hide-div').show();
                    }
                }
            });
        }

        function delete_row(em) {
            $(em).closest('.form-group.row').remove();
            update_sku();
        }

        function delete_variant(em) {
            $(em).closest('.variant').remove();
        }
    </script>
@endsection
