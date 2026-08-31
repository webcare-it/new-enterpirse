@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Edit Product') }}</h5>
    </div>

    <div class="">
        <form class="form form-horizontal mar-top" action="{{ route('products.droploo.product.store', $product->id) }}"
            method="POST" enctype="multipart/form-data" id="choice_form">
            @csrf
            @method('PUT')

            <div class="row gutters-5">
                <div class="col-lg-8">

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
                                        placeholder="{{ translate('Product Name') }}" value="{{ $product->name }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Slug') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="slug" id="slug"
                                        placeholder="{{ translate('Product URL Slug (auto-generated if empty)') }}"
                                        value="{{ $product->slug }}">
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
                                            <option value="{{ $category->id }}"
                                                {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->category_name }}
                                            </option>
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
                                        @foreach ($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}"
                                                {{ $product->subcategory_id == $subcategory->id ? 'selected' : '' }}>
                                                {{ $subcategory->name }}
                                            </option>
                                        @endforeach
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
                                            <option value="{{ $brand->id }}"
                                                {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Unit') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="unit"
                                        placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}"
                                        value="{{ $product->unit }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Barcode') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="barcode"
                                        placeholder="{{ translate('Barcode') }}" value="{{ $product->barcode }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Badge Name') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="badge_name"
                                        placeholder="{{ translate('e.g., Hot, New, Sale') }}"
                                        value="{{ $product->badge_name }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Batch No') }}</label>
                                <div class="col-md-8">
                                    <input type="number" class="form-control" name="batch_no"
                                        placeholder="{{ translate('Batch Number') }}" value="{{ $product->batch_no }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Tags') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control aiz-tag-input" name="tags"
                                        placeholder="{{ translate('Type and hit enter to add a tag') }}"
                                        value="{{ $product->tags_array ? implode(',', $product->tags_array) : '' }}">
                                    <small
                                        class="text-muted">{{ translate('Used for search. Input words customers can find this product by.') }}</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Short Description') }}</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="short_description" rows="3"
                                        placeholder="{{ translate('Brief product summary') }}">{{ $product->short_description }}</textarea>
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
                                    <textarea class="aiz-text-editor" name="description">{!! $product->description !!}</textarea>
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
                                        <input type="hidden" name="photos" class="selected-files"
                                            value="{{ $product->photos ? implode(',', json_decode($product->photos, true)) : '' }}">
                                    </div>
                                    <div class="file-preview box sm">
                                        @if ($product->photos)
                                            @php $photos = json_decode($product->photos, true) @endphp
                                            @foreach ($photos as $photo)
                                                <div class="file-preview-item">
                                                    <img src="{{ uploaded_asset($photo) }}" class="size-60px">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
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
                                        <input type="hidden" name="thumbnail_img" class="selected-files"
                                            value="{{ $product->thumbnail }}">
                                    </div>
                                    <div class="file-preview box sm">
                                        @if ($product->thumbnail)
                                            <img src="{{ uploaded_asset($product->thumbnail) }}" class="size-60px">
                                        @endif
                                    </div>
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
                                        placeholder="{{ translate('YouTube or Vimeo video link') }}"
                                        value="{{ $product->video_link }}">
                                    <small class="text-muted">{{ translate('Use proper video URL') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Product Price & Inventory --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Price & Inventory') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Purchase Price') }}</label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" min="0" step="0.01"
                                        placeholder="{{ translate('Purchase Price') }}" name="purchase_price"
                                        class="form-control" value="{{ $product->price->purchase_price ?? 0 }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Regular Price') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" min="0" step="0.01"
                                        placeholder="{{ translate('Regular Price') }}" name="regular_price"
                                        class="form-control" required value="{{ $product->price->regular_price ?? 0 }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Wholesale Price') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" min="0" step="0.01"
                                        placeholder="{{ translate('Wholesale Price') }}" name="wholesale_price"
                                        class="form-control" required
                                        value="{{ $product->price->wholesale_price ?? 0 }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Discount') }}</label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" step="0.01"
                                        placeholder="{{ translate('Discount') }}" name="discount" class="form-control"
                                        value="{{ $product->price->discount ?? 0 }}">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control aiz-selectpicker" name="discount_type">
                                        <option value="flat"
                                            {{ ($product->price->discount_type ?? '') == 'flat' ? 'selected' : '' }}>
                                            {{ translate('Flat') }}
                                        </option>
                                        <option value="percent"
                                            {{ ($product->price->discount_type ?? '') == 'percent' ? 'selected' : '' }}>
                                            {{ translate('Percent') }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Discount Date Range') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control aiz-date-range" name="date_range"
                                        placeholder="{{ translate('Select Date Range') }}" data-time-picker="true"
                                        data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off"
                                        value="{{ $product->price->discount_start && $product->price->discount_end ? \Carbon\Carbon::parse($product->price->discount_start)->format('d-m-Y H:i:s') . ' to ' . \Carbon\Carbon::parse($product->price->discount_end)->format('d-m-Y H:i:s') : '' }}">
                                </div>
                            </div> --}}

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('SKU') }}</label>
                                <div class="col-md-8">
                                    <input type="text" placeholder="{{ translate('SKU (unique)') }}" name="sku"
                                        class="form-control" value="{{ $product->inventory->sku ?? '' }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Stock Quantity') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" min="0" step="1"
                                        placeholder="{{ translate('Stock Quantity') }}" name="stock"
                                        class="form-control" required value="{{ $product->inventory->stock ?? 0 }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                    class="col-md-3 col-from-label">{{ translate('Low Stock Quantity Warning') }}</label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" min="0" step="1"
                                        placeholder="{{ translate('Low Stock Threshold') }}" name="low_stock_qty"
                                        class="form-control" value="{{ $product->inventory->low_stock_qty ?? 1 }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Track Inventory') }}</label>
                                <div class="col-md-8">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="track_inventory" value="1"
                                            {{ $product->inventory->track_inventory ?? false ? 'checked' : '' }}>
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
                            <div id="sku_combination">
                                @include('backend.product.products.variant_combinations', [
                                    'combinations' => $combinations,
                                ])
                            </div>
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
                                        <option value="flat_rate"
                                            {{ ($product->shipping->shipping_type ?? '') == 'flat_rate' ? 'selected' : '' }}>
                                            {{ translate('Flat Rate') }}
                                        </option>
                                        <option value="free"
                                            {{ ($product->shipping->shipping_type ?? '') == 'free' ? 'selected' : '' }}>
                                            {{ translate('Free Shipping') }}
                                        </option>
                                        <option value="local_pickup"
                                            {{ ($product->shipping->shipping_type ?? '') == 'local_pickup' ? 'selected' : '' }}>
                                            {{ translate('Local Pickup') }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row" id="shipping_cost_div">
                                <label class="col-md-3 col-from-label">{{ translate('Shipping Cost') }}</label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" min="0" step="0.01"
                                        placeholder="{{ translate('Shipping Cost') }}" name="shipping_cost"
                                        class="form-control" value="{{ $product->shipping->shipping_cost ?? 0 }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Weight (kg)') }}</label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" min="0" step="0.01"
                                        placeholder="{{ translate('Weight') }}" name="weight" class="form-control"
                                        value="{{ $product->shipping->weight ?? 0 }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Dimensions (L x W x H)') }}</label>
                                <div class="col-md-3">
                                    <input type="number" lang="en" min="0" step="0.01"
                                        placeholder="{{ translate('Length') }}" name="length" class="form-control"
                                        value="{{ $product->shipping->length ?? 0 }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" lang="en" min="0" step="0.01"
                                        placeholder="{{ translate('Width') }}" name="width" class="form-control"
                                        value="{{ $product->shipping->width ?? 0 }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" lang="en" min="0" step="0.01"
                                        placeholder="{{ translate('Height') }}" name="height" class="form-control"
                                        value="{{ $product->shipping->height ?? 0 }}">
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
                                        placeholder="{{ translate('Meta Title') }}"
                                        value="{{ $product->seo->meta_title ?? '' }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Meta Description') }}</label>
                                <div class="col-md-8">
                                    <textarea name="meta_description" rows="4" class="form-control"
                                        placeholder="{{ translate('Meta Description for SEO') }}">{{ $product->seo->meta_description ?? '' }}</textarea>
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
                                        <input type="hidden" name="meta_img" class="selected-files"
                                            value="{{ $product->seo->meta_image ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm">
                                        @if ($product->seo && $product->seo->meta_image)
                                            <img src="{{ uploaded_asset($product->seo->meta_image) }}"
                                                class="size-60px">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> {{-- End col-lg-8 --}}

                {{-- Right Sidebar --}}
                <div class="col-lg-4">

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
                                        <input type="checkbox" name="status" value="1"
                                            {{ $product->status == 1 ? 'checked' : '' }}>
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ translate('Published') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="is_published" value="1"
                                            {{ $product->is_published ? 'checked' : '' }}>
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
                                        <input type="checkbox" name="is_featured" value="1"
                                            {{ $product->is_featured ? 'checked' : '' }}>
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ translate('Best Selling') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="best_selling" value="1"
                                            {{ $product->best_selling ? 'checked' : '' }}>
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ translate('New Arrival') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="is_new_arrival" value="1"
                                            {{ $product->is_new_arrival ? 'checked' : '' }}>
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ translate("Today's Deal") }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="todays_deal" value="1"
                                            {{ $product->todays_deal ? 'checked' : '' }}>
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
                                    placeholder="{{ translate('Sort order') }}" value="{{ $product->position ?? 0 }}">
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
                            @forelse($product->taxes as $index => $tax)
                                <div class="tax-row">
                                    <div class="form-group">
                                        <label>{{ translate('Tax') }} <span class="text-danger">*</span></label>
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="tax_names[]"
                                                placeholder="{{ translate('Tax Name') }}"
                                                value="{{ optional($tax->tax)->name ?? '' }}">
                                            <input type="number" class="form-control" name="tax_values[]"
                                                placeholder="{{ translate('Value') }}" step="0.01"
                                                value="{{ $tax->value ?? 0 }}">
                                            <select class="form-control" name="tax_types[]">
                                                <option value="percent"
                                                    {{ ($tax->tax_type ?? '') == 'percent' ? 'selected' : '' }}>
                                                    {{ translate('Percent') }}
                                                </option>
                                                <option value="flat"
                                                    {{ ($tax->tax_type ?? '') == 'flat' ? 'selected' : '' }}>
                                                    {{ translate('Flat') }}
                                                </option>
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-danger remove-tax" type="button">-</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
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
                            @endforelse
                            <button type="button" class="btn btn-sm btn-info" id="add-tax">+
                                {{ translate('Add Tax') }}</button>
                        </div>
                    </div>

                </div> {{-- End col-lg-4 --}}

                {{-- Submit Buttons --}}
                <div class="col-12">
                    <div class="btn-toolbar float-right mb-3" role="toolbar">
                        <div class="btn-group mr-2">
                            <button type="submit" name="button" value="draft"
                                class="btn btn-warning">{{ translate('Save As Draft') }}</button>
                        </div>
                        <div class="btn-group">
                            <button type="submit" name="button" value="update"
                                class="btn btn-primary">{{ translate('Update Product') }}</button>
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
                if ($('#slug').val() == '' || $('#slug').val() == $('#slug').data('original')) {
                    $('#slug').val(slug);
                }
            });

            // Store original slug
            $('#slug').data('original', $('#slug').val());

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
                    type: "GET",
                    url: '{{ route('products.subcategories.get.json', '') }}/' + category_id,
                    success: function(response) {
                        var options =
                            '<option value="">{{ translate('Select Sub Category') }}</option>';

                        if (response.success && response.data.length > 0) {
                            $.each(response.data, function(index, subcategory) {
                                var selected = subcategory.id ==
                                    {{ $product->subcategory_id ?? 0 }} ? 'selected' :
                                    '';
                                options += '<option value="' + subcategory.id + '" ' +
                                    selected + '>' +
                                    subcategory.name + '</option>';
                            });
                        }

                        $('#subcategory_id').html(options);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                });
            });
        });

        // Attribute selection for variants
        $('#choice_attributes').on('change', function() {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function() {
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
            update_sku();
        });

        function add_more_customer_choice_option(i, name) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{ route('products.add-more-choice-option') }}',
                data: {
                    attribute_id: i
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    $('#customer_choice_options').append(`
                        <div class="form-group row">
                            <div class="col-md-3">
                                <input type="hidden" name="choice_no[]" value="${i}">
                                <input type="text" class="form-control" name="choice[]" value="${name}" readonly>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" 
                                    name="choice_options_${i}[]" multiple>
                                    ${obj}
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-sm btn-danger" onclick="delete_row(this)">
                                    <i class="las la-trash"></i>
                                </button>
                            </div>
                        </div>
                    `);
                    AIZ.plugins.bootstrapSelect('refresh');
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
