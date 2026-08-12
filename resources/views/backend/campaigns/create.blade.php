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
                <h1 class="h3">{{ translate('Create New') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('campaigns.index') }}" class="btn btn-secondary">
                    <i class="las la-arrow-left"></i> {{ translate('Back to Campaigns') }}
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('campaigns.store') }}" method="POST" enctype="multipart/form-data" id="campaign-form">
        @csrf
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
                                    placeholder="{{ translate('Campaign Name') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Slug') }}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="slug" id="slug"
                                    placeholder="{{ translate('URL Slug (auto-generated if empty)') }}">
                                <small
                                    class="text-muted">{{ translate('Leave empty to auto-generate from campaign name') }}</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="description" rows="4" placeholder="{{ translate('Campaign Description') }}"></textarea>
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
                                    <input type="hidden" name="image" class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
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
                                <input type="date" class="form-control" name="start_date" id="start_date" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('End Date') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="date" class="form-control" name="end_date" id="end_date" required>
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
                                    id="discount_amount" placeholder="{{ translate('Discount Amount') }}" value="0">
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="discount_type" id="discount_type">
                                    <option value="flat">{{ translate('Flat (Fixed Amount)') }}</option>
                                    <option value="percent">{{ translate('Percent (%)') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3"></div>
                            <div class="col-md-8">
                                <small
                                    class="text-muted">{{ translate('Products in this campaign will get this discount') }}</small>
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
                                        data-price="{{ $regularPrice }}"
                                        data-thumbnail="{{ $product->thumbnail ? uploaded_asset($product->thumbnail) : asset('assets/img/placeholder.jpg') }}"
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
                                        <th width="35%">{{ translate('Product Name') }}</th>
                                        <th width="15%">{{ translate('Regular Price') }}</th>
                                        <th width="20%">{{ translate('Campaign Price') }}</th>
                                        <th width="10%">{{ translate('Save') }}</th>
                                        <th width="10%">{{ translate('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="selected-products-body">
                                    <tr id="no-products-row">
                                        <td colspan="6" class="text-center">
                                            <div class="py-3">
                                                <i class="las la-box-open fs-40 text-muted"></i>
                                                <p class="text-muted mb-0">{{ translate('No products selected') }}</p>
                                                <small>{{ translate('Search and add products from above') }}</small>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Hidden input to store selected product IDs -->
                        <input type="hidden" name="products" id="selected-products-input" value="">
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
                                <button type="button" class="btn btn-success active-status" data-status="active">
                                    <i class="las la-check-circle"></i> {{ translate('Active') }}
                                </button>
                                <button type="button" class="btn btn-secondary inactive-status" data-status="inactive">
                                    <i class="las la-times-circle"></i> {{ translate('Inactive') }}
                                </button>
                            </div>
                            <input type="hidden" name="status" id="campaign-status" value="active">
                        </div>
                        <div class="alert alert-info mt-2">
                            <i class="las la-info-circle"></i>
                            {{ translate('Active campaigns will be visible to customers. Inactive campaigns will be hidden.') }}
                        </div>
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Campaign Summary') }}</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>{{ translate('Total Products') }}</th>
                                <td id="total-products-count">0</td>
                            </tr>
                            <tr>
                                <th>{{ translate('Discount') }}</th>
                                <td id="summary-discount">{{ translate('Not set') }}</td>
                            </tr>
                            <tr>
                                <th>{{ translate('Duration') }}</th>
                                <td id="summary-duration">{{ translate('Not set') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="las la-save"></i> {{ translate('Create Campaign') }}
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
        let selectedProducts = [];

        $(document).ready(function() {
            $('#name').on('keyup', function() {
                var name = $(this).val();
                var slug = name.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/--+/g, '-')
                    .trim();
                $('#slug').val(slug);
            });

            $('#start_date, #end_date').on('change', function() {
                updateSummary();
                validateDates();
            });

            $('#discount_amount, #discount_type').on('change', function() {
                updateSummary();
                updateSelectedProductsTable();
            });

            $('.active-status').on('click', function() {
                $('.active-status').removeClass('btn-secondary').addClass('btn-success');
                $('.inactive-status').removeClass('btn-danger').addClass('btn-secondary');
                $('#campaign-status').val('active');
            });

            $('.inactive-status').on('click', function() {
                $('.inactive-status').removeClass('btn-secondary').addClass('btn-danger');
                $('.active-status').removeClass('btn-success').addClass('btn-secondary');
                $('#campaign-status').val('inactive');
            });

            $('#product-search').selectpicker({
                template: {
                    caret: '<span class="caret"></span>'
                }
            });

            $('#product-search').on('change', function() {
                var option = $(this).find('option:selected');
                var productId = $(this).val();

                if (productId && !selectedProducts.find(p => p.id == productId)) {
                    var product = {
                        id: productId,
                        name: option.data('name'),
                        price: parseFloat(option.data('price')) || 0,
                        thumbnail: option.data('thumbnail')
                    };

                    selectedProducts.push(product);
                    updateSelectedProductsTable();
                    updateSummary();
                }

                $('#product-search').val('').selectpicker('refresh');
            });
            updateSummary();
        });

        function updateSelectedProductsTable() {
            var tbody = $('#selected-products-body');
            tbody.empty();

            if (selectedProducts.length === 0) {
                tbody.append(`
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
                $('#selected-products-input').val('');
                return;
            }

            var discountAmount = parseFloat($('#discount_amount').val()) || 0;
            var discountType = $('#discount_type').val();

            selectedProducts.forEach((product, index) => {
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
                        </td>
                        <td>৳${regularPrice.toFixed(2)}</td>
                        <td>
                            <strong class="text-primary">৳${campaignPrice.toFixed(2)}</strong>
                        </td>
                        <td>
                            <span class="badge badge-success">৳${saveAmount.toFixed(2)}</span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-icon btn-danger" onclick="removeProduct(${index})">
                                <i class="las la-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });

            var productIds = selectedProducts.map(p => p.id);
            $('#selected-products-input').val(JSON.stringify(productIds));
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

        function removeProduct(index) {
            selectedProducts.splice(index, 1);
            updateSelectedProductsTable();
            updateSummary();
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
                $('#summary-duration').text(startDate + ' to ' + endDate);
            } else if (startDate) {
                $('#summary-duration').text('From ' + startDate);
            } else if (endDate) {
                $('#summary-duration').text('Until ' + endDate);
            } else {
                $('#summary-duration').text('{{ translate('Not set') }}');
            }
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

            $(this).find('button[type="submit"]').prop('disabled', true).html(
                '<i class="las la-spinner la-spin"></i> {{ translate('Creating...') }}');
        });

        $(window).on('load', function() {
            $('#product-search').selectpicker('refresh');
        });
    </script>
@endsection
