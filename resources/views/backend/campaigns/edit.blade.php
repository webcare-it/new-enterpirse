@extends('backend.layouts.app')

@section('content')
    <style>
        .status-btn-group .btn {
            padding: 10px 20px;
            font-weight: 500;
        }

        #selected-products-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .size-50px {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }

        .size-100px {
            width: 100px;
            height: auto;
        }

        .size-40px {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }

        .fs-40 {
            font-size: 40px;
        }

        .d-grid {
            display: grid;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .btn-lg {
            padding: 12px 20px;
            font-size: 16px;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        /* Dropdown image styling */
        .bootstrap-select .dropdown-menu .dropdown-item {
            padding: 8px 12px;
        }

        .product-option {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .product-option img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }

        .product-option .info {
            flex: 1;
        }

        .product-option .name {
            font-weight: 500;
            font-size: 14px;
        }

        .product-option .price {
            font-size: 12px;
            color: #6c757d;
        }
    </style>

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Edit Campaign') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('campaigns.index') }}" class="btn btn-secondary">
                    <i class="las la-arrow-left"></i> {{ translate('Back to Campaigns') }}
                </a>
                <a href="{{ route('campaigns.show', $campaign->id) }}" class="btn btn-info">
                    <i class="las la-eye"></i> {{ translate('View Campaign') }}
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('campaigns.update', $campaign->id) }}" method="POST" enctype="multipart/form-data"
        id="campaign-form">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <!-- Basic Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Basic Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Campaign Name') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="{{ translate('Campaign Name') }}" value="{{ $campaign->name }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Slug') }}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="slug" id="slug"
                                    placeholder="{{ translate('URL Slug (auto-generated if empty)') }}"
                                    value="{{ $campaign->slug }}">
                                <small
                                    class="text-muted">{{ translate('Leave empty to auto-generate from campaign name') }}</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>
                            <div class="col-md-8">
                                <textarea class="form-control aiz-text-editor" name="description" rows="4"
                                    placeholder="{{ translate('Campaign Description') }}">{{ $campaign->description }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Campaign Image') }}</label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="image" class="selected-files"
                                        value="{{ $campaign->image }}">
                                </div>
                                <div class="file-preview box sm">
                                    @if ($campaign->image)
                                        <img src="{{ uploaded_asset($campaign->image) }}" class="size-100px">
                                    @endif
                                </div>
                                <small
                                    class="text-muted">{{ translate('Upload campaign banner image (Recommended size: 800x400)') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campaign Schedule Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Campaign Schedule') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Start Date') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="date" class="form-control" name="start_date" id="start_date"
                                    value="{{ $campaign->start_date->format('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('End Date') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="date" class="form-control" name="end_date" id="end_date"
                                    value="{{ $campaign->end_date->format('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Duration') }}</label>
                            <div class="col-md-8">
                                <div class="alert alert-info mb-0">
                                    <i class="las la-info-circle"></i>
                                    @php
                                        $start = \Carbon\Carbon::parse($campaign->start_date);
                                        $end = \Carbon\Carbon::parse($campaign->end_date);
                                        $days = $start->diffInDays($end);
                                    @endphp
                                    {{ translate('Campaign will run for') }} <strong>{{ $days }}</strong>
                                    {{ translate('days') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Discount Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Discount Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Discount Amount') }}</label>
                            <div class="col-md-5">
                                <input type="number" step="0.01" class="form-control" name="discount_amount"
                                    id="discount_amount" placeholder="{{ translate('Discount Amount') }}"
                                    value="{{ $campaign->discount_amount }}">
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="discount_type" id="discount_type">
                                    <option value="flat" {{ $campaign->discount_type == 'flat' ? 'selected' : '' }}>
                                        {{ translate('Flat (Fixed Amount)') }}
                                    </option>
                                    <option value="percent" {{ $campaign->discount_type == 'percent' ? 'selected' : '' }}>
                                        {{ translate('Percent (%)') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3"></div>
                            <div class="col-md-8">
                                <div class="alert alert-warning">
                                    <i class="las la-exclamation-triangle"></i>
                                    {{ translate('Products in this campaign will get this discount. Existing product prices will not be affected.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Select Products Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Select Products') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>{{ translate('Search Products') }}</label>
                            <select class="form-control aiz-selectpicker" id="product-search" data-live-search="true"
                                data-live-search-style="contains"
                                data-live-search-placeholder="{{ translate('Search products...') }}"
                                data-none-selected-text="{{ translate('Search and select products...') }}">
                                <option value="">{{ translate('Search and select products...') }}</option>
                                @foreach ($products as $product)
                                    @php
                                        $thumbnail = $product->thumbnail
                                            ? uploaded_asset($product->thumbnail)
                                            : asset('assets/img/placeholder.jpg');
                                        $regularPrice = $product->price->regular_price ?? 0;
                                    @endphp
                                    <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                        data-price="{{ $regularPrice }}" data-thumbnail="{{ $thumbnail }}"
                                        data-content="<div class='product-option'><img src='{{ $thumbnail }}'><div class='info'><div class='name'>{{ addslashes($product->name) }}</div><div class='price'>৳{{ number_format($regularPrice, 2) }}</div></div></div>">
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ translate('Search and add products to this campaign') }}</small>
                        </div>

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered" id="selected-products-table">
                                <thead>
                                    <tr>
                                        <th width="10%">{{ translate('Image') }}</th>
                                        <th width="30%">{{ translate('Product Name') }}</th>
                                        <th width="15%">{{ translate('Regular Price') }}</th>
                                        <th width="20%">{{ translate('Campaign Price') }}</th>
                                        <th width="15%">{{ translate('Discount') }}</th>
                                        <th width="10%">{{ translate('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="selected-products-body">
                                    @if ($campaign->products && count($campaign->products) > 0)
                                        @foreach ($campaign->products as $product)
                                            @php
                                                $regularPrice = $product->price->regular_price ?? 0;
                                                $discountAmount = $campaign->discount_amount ?? 0;
                                                $discountType = $campaign->discount_type ?? 'flat';

                                                if ($discountType == 'percent') {
                                                    $campaignPrice =
                                                        $regularPrice - ($regularPrice * $discountAmount) / 100;
                                                } else {
                                                    $campaignPrice = max(0, $regularPrice - $discountAmount);
                                                }
                                                $thumbnail = $product->thumbnail
                                                    ? uploaded_asset($product->thumbnail)
                                                    : asset('assets/img/placeholder.jpg');
                                            @endphp
                                            <tr id="product-row-{{ $product->id }}">
                                                <td>
                                                    <img src="{{ $thumbnail }}" class="size-50px img-fit"
                                                        style="border-radius: 8px;">
                                                </td>
                                                <td>
                                                    <strong>{{ $product->name }}</strong>
                                                    <input type="hidden" name="products[]" value="{{ $product->id }}">
                                                </td>
                                                <td class="regular-price">৳{{ number_format($regularPrice, 2) }}</td>
                                                <td class="campaign-price">
                                                    <strong
                                                        class="text-primary">৳{{ number_format($campaignPrice, 2) }}</strong>
                                                </td>
                                                <td class="save-amount">
                                                    <span class="badge badge-success">
                                                        {{ translate('Save') }}
                                                        ৳{{ number_format($regularPrice - $campaignPrice, 2) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-icon btn-danger"
                                                        onclick="removeProduct({{ $product->id }})">
                                                        <i class="las la-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr id="no-products-row">
                                            <td colspan="6" class="text-center">
                                                <div class="py-3">
                                                    <i class="las la-box-open fs-40 text-muted"></i>
                                                    <p class="text-muted mb-0">{{ translate('No products selected') }}</p>
                                                    <small>{{ translate('Search and add products from above') }}</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Campaign Status') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="d-block">{{ translate('Status') }}</label>
                            <div class="btn-group w-100" role="group">
                                <button type="button"
                                    class="btn {{ $campaign->status == 'active' ? 'btn-success active-status' : 'btn-secondary' }} status-btn"
                                    data-status="active">
                                    <i class="las la-check-circle"></i> {{ translate('Active') }}
                                </button>
                                <button type="button"
                                    class="btn {{ $campaign->status == 'inactive' ? 'btn-danger inactive-status' : 'btn-secondary' }} status-btn"
                                    data-status="inactive">
                                    <i class="las la-times-circle"></i> {{ translate('Inactive') }}
                                </button>
                            </div>
                            <input type="hidden" name="status" id="campaign-status" value="{{ $campaign->status }}">
                        </div>

                        @if ($campaign->isRunning())
                            <div class="alert alert-success mt-2">
                                <i class="las la-play-circle"></i>
                                {{ translate('This campaign is currently running!') }}
                            </div>
                        @elseif($campaign->isUpcoming())
                            <div class="alert alert-info mt-2">
                                <i class="las la-calendar-alt"></i>
                                {{ translate('This campaign will start on') }}
                                {{ $campaign->start_date->format('d M Y') }}
                            </div>
                        @elseif($campaign->hasEnded())
                            <div class="alert alert-secondary mt-2">
                                <i class="las la-flag-checkered"></i>
                                {{ translate('This campaign ended on') }} {{ $campaign->end_date->format('d M Y') }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Campaign Summary Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Campaign Summary') }}</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="50%">{{ translate('Total Products') }}</th>
                                <td id="total-products-count">{{ $campaign->products->count() }}</td>
                            </tr>
                            <tr>
                                <th>{{ translate('Discount') }}</th>
                                <td id="summary-discount">
                                    @if ($campaign->discount_amount)
                                        @if ($campaign->discount_type == 'percent')
                                            {{ $campaign->discount_amount }}% OFF
                                        @else
                                            ৳{{ number_format($campaign->discount_amount, 2) }} OFF
                                        @endif
                                    @else
                                        {{ translate('No discount') }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ translate('Duration') }}</th>
                                <td id="summary-duration">
                                    {{ $campaign->start_date->format('d M Y') }} -
                                    {{ $campaign->end_date->format('d M Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ translate('Status') }}</th>
                                <td>
                                    @if ($campaign->status == 'active')
                                        <span class="badge badge-success">{{ translate('Active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ translate('Inactive') }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="las la-save"></i> {{ translate('Update Campaign') }}
                            </button>
                            <a href="{{ route('campaigns.index') }}" class="btn btn-secondary">
                                <i class="las la-times"></i> {{ translate('Cancel') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script type="text/javascript">
        let selectedProducts = @json($campaign->products->pluck('id')->toArray());
        $(document).ready(function() {
            $('#name').on('keyup', function() {
                var name = $(this).val();
                var slug = name.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/--+/g, '-')
                    .trim();
                if ($('#slug').val() == '') {
                    $('#slug').val(slug);
                }
            });

            $('#start_date, #end_date').on('change', function() {
                validateDates();
                updateSummary();
            });

            $('#discount_amount, #discount_type').on('change', function() {
                updateSummary();
                updateCampaignPrices();
            });

            $('.status-btn').on('click', function() {
                var status = $(this).data('status');
                $('#campaign-status').val(status);

                $('.status-btn').removeClass('btn-success btn-danger').addClass('btn-secondary');
                if (status == 'active') {
                    $('.active-status').addClass('btn-success').removeClass('btn-secondary');
                } else {
                    $('.inactive-status').addClass('btn-danger').removeClass('btn-secondary');
                }
            });

            $('#product-search').selectpicker({
                template: {
                    caret: '<span class="caret"></span>'
                }
            });
            $('#product-search').on('change', function() {
                var option = $(this).find('option:selected');
                var productId = $(this).val();

                if (productId && !selectedProducts.includes(productId)) {
                    var product = {
                        id: productId,
                        name: option.data('name'),
                        price: parseFloat(option.data('price')) || 0,
                        thumbnail: option.data('thumbnail')
                    };

                    selectedProducts.push(productId);
                    addProductToTable(product);
                    updateSummary();
                }
                $('#product-search').val('').selectpicker('refresh');
            });
            updateSummary();
            setTimeout(function() {
                $('#product-search').selectpicker('refresh');
            }, 100);
        });

        function addProductToTable(product) {
            if ($('#no-products-row').length) {
                $('#no-products-row').remove();
            }

            var discountAmount = parseFloat($('#discount_amount').val()) || 0;
            var discountType = $('#discount_type').val();
            var regularPrice = parseFloat(product.price) || 0;
            var campaignPrice = calculateCampaignPrice(regularPrice, discountAmount, discountType);
            var saveAmount = regularPrice - campaignPrice;
            var thumbnailUrl = product.thumbnail || '{{ asset('assets/img/placeholder.jpg') }}';

            var row = `
                <tr id="product-row-${product.id}">
                    <td>
                        <img src="${thumbnailUrl}" 
                             class="size-50px img-fit" 
                             style="border-radius: 8px;"
                             onerror="this.src='{{ asset('assets/img/placeholder.jpg') }}'">
                    </td>
                    <td>
                        <strong>${escapeHtml(product.name)}</strong>
                        <input type="hidden" name="products[]" value="${product.id}">
                    </td>
                    <td class="regular-price">৳${regularPrice.toFixed(2)}</td>
                    <td class="campaign-price">
                        <strong class="text-primary">৳${campaignPrice.toFixed(2)}</strong>
                    </td>
                    <td class="save-amount">
                        <span class="badge badge-success">Save ৳${saveAmount.toFixed(2)}</span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-icon btn-danger" onclick="removeProduct(${product.id})">
                            <i class="las la-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            $('#selected-products-body').append(row);
        }

        function removeProduct(productId) {
            $(`#product-row-${productId}`).remove();
            selectedProducts = selectedProducts.filter(id => id != productId);

            if (selectedProducts.length === 0) {
                $('#selected-products-body').html(`
                    <tr id="no-products-row">
                        <td colspan="6" class="text-center">
                            <div class="py-3">
                                <i class="las la-box-open fs-40 text-muted"></i>
                                <p class="text-muted mb-0">{{ translate('No products selected') }}</p>
                                <small>{{ translate('Search and add products from above') }}</small>
                            </div>
                        </td>
                    </tr>
                `);
            }

            updateSummary();
        }

        function calculateCampaignPrice(regularPrice, discountAmount, discountType) {
            if (!discountAmount || discountAmount <= 0) return regularPrice;

            if (discountType == 'percent') {
                var discountedPrice = regularPrice - (regularPrice * discountAmount / 100);
                return Math.max(0, parseFloat(discountedPrice.toFixed(2)));
            } else {
                return Math.max(0, regularPrice - discountAmount);
            }
        }

        function updateSummary() {
            $('#total-products-count').text(selectedProducts.length);

            var discountAmount = $('#discount_amount').val();
            var discountType = $('#discount_type').val();

            if (discountAmount && parseFloat(discountAmount) > 0) {
                if (discountType == 'percent') {
                    $('#summary-discount').text(discountAmount + '% OFF');
                } else {
                    $('#summary-discount').text('৳' + parseFloat(discountAmount).toFixed(2) + ' OFF');
                }
            } else {
                $('#summary-discount').text('{{ translate('No discount') }}');
            }

            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            if (startDate && endDate) {
                var start = new Date(startDate);
                var end = new Date(endDate);
                var options = {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                };
                $('#summary-duration').text(start.toLocaleDateString('en-US', options) + ' - ' + end.toLocaleDateString(
                    'en-US', options));
            }
        }

        function updateCampaignPrices() {
            var discountAmount = parseFloat($('#discount_amount').val()) || 0;
            var discountType = $('#discount_type').val();

            $('#selected-products-body tr').each(function() {
                var $row = $(this);
                var regularPriceText = $row.find('.regular-price').text();
                var regularPrice = parseFloat(regularPriceText.replace('৳', '')) || 0;
                var campaignPrice = calculateCampaignPrice(regularPrice, discountAmount, discountType);
                var saveAmount = regularPrice - campaignPrice;

                $row.find('.campaign-price').html(
                    `<strong class="text-primary">৳${campaignPrice.toFixed(2)}</strong>`);
                $row.find('.save-amount').html(
                    `<span class="badge badge-success">Save ৳${saveAmount.toFixed(2)}</span>`);
            });
        }

        function validateDates() {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            if (startDate && endDate && startDate > endDate) {
                $('#end_date').addClass('is-invalid');
                AIZ.plugins.notify('danger', '{{ translate('End date must be after start date') }}');
                return false;
            } else {
                $('#end_date').removeClass('is-invalid');
            }
            return true;
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        $('#campaign-form').on('submit', function(e) {
            if (!validateDates()) {
                e.preventDefault();
                return false;
            }

            if (selectedProducts.length === 0) {
                AIZ.plugins.notify('warning',
                    '{{ translate('Please select at least one product for the campaign') }}');
                e.preventDefault();
                return false;
            }

            var discountAmount = $('#discount_amount').val();
            if (discountAmount && parseFloat(discountAmount) > 0) {
                var discountType = $('#discount_type').val();
                if (discountType == 'percent' && parseFloat(discountAmount) > 100) {
                    AIZ.plugins.notify('warning', '{{ translate('Percent discount cannot exceed 100%') }}');
                    e.preventDefault();
                    return false;
                }
            }
        });
    </script>
@endsection

<style>
    .d-grid {
        display: grid;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .btn-lg {
        padding: 12px 20px;
        font-size: 16px;
    }

    .size-50px {
        width: 50px;
        height: 50px;
        object-fit: cover;
    }

    .size-100px {
        width: 100px;
        height: auto;
    }

    .is-invalid {
        border-color: #dc3545;
    }
</style>
