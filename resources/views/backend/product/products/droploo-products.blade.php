@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0 h6">{{ translate('Dropshipping Products List') }}</h5>
            </div>
            <div class="col-md-6 text-md-right product-action-wrap">
                <a href="{{ route('products.create') }}" class="smart-btn add-btn">
                    <i class="las la-plus"></i>
                    {{ translate('Add Product') }}
                </a>
            </div>

          <style>
                .product-action-wrap {
                    display: flex;
                    justify-content: flex-end;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 8px;
                }

                .smart-btn {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 7px;
                    height: 38px;
                    padding: 0 15px;
                    border: 1px solid transparent;
                    border-radius: 10px;
                    font-size: 13px;
                    font-weight: 600;
                    text-decoration: none !important;
                    transition: all 0.2s ease;
                    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
                    cursor: pointer;
                }

                .smart-btn i {
                    font-size: 17px;
                }

                .smart-btn:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 8px 18px rgba(15, 23, 42, 0.13);
                }

                .add-btn {
                    color: #2563eb !important;
                    background: #eff6ff;
                    border-color: #bfdbfe;
                }

                .add-btn:hover {
                    background: #2563eb;
                    color: #fff !important;
                }

                .preview-btn {
                    color: #15803d !important;
                    background: #f0fdf4;
                    border-color: #bbf7d0;
                }

                .preview-btn:hover {
                    background: #16a34a;
                    color: #fff !important;
                }

                .delete-btn {
                    color: #dc2626 !important;
                    background: #fef2f2;
                    border-color: #fecaca;
                }

                .delete-btn:hover {
                    background: #dc2626;
                    color: #fff !important;
                }

                .import-btn-new {
                    position: relative;
                    color: #fff !important;
                    background: linear-gradient(135deg, #0f172a, #2563eb);
                    overflow: hidden;
                }

                .import-btn-new::after {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: -80%;
                    width: 45%;
                    height: 100%;
                    background: rgba(255, 255, 255, 0.25);
                    transform: skewX(-20deg);
                    animation: importShine 2.8s infinite;
                }

                .import-btn-new i,
                .import-btn-new {
                    z-index: 1;
                }

                @keyframes importShine {
                    100% {
                        left: 130%;
                    }
                }

                @media (max-width: 767px) {
                    .product-action-wrap {
                        justify-content: flex-start;
                        margin-top: 12px;
                    }

                    .smart-btn {
                        width: calc(50% - 4px);
                    }
                }
            </style>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('API Connection Information') }}</h5>

                     <a href="/admin/credentials" class="btn btn-light btn-sm shadow-sm">
                        <i class="las la-external-link-alt mr-1"></i>
                        {{ translate('Configure API Credentials') }}
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>{{ translate('Username') }}:</strong> {{ get_setting('droploo_username', 'Not configured') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>{{ translate('App Key') }}:</strong> {{ get_setting('droploo_app_key', 'Not configured') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>{{ translate('App Secret') }}:</strong> {{ get_setting('droploo_app_secret', 'Not configured') }}</p>
                        </div>
                    </div>
                    @if(!get_setting('droploo_username') || !get_setting('droploo_app_key') || !get_setting('droploo_app_secret'))
                        <div class="alert alert-warning">
                            {{ translate('Please configure your Droploo API credentials in Business Settings') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Product List') }}</h5>
            <div class="pull-right">
                <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#filterCollapse"
                    aria-expanded="false">
                    <i class="las la-filter"></i> {{ translate('Filter') }}
                </button>
            </div>
        </div>

        <div class="card-body">
            <!-- Filter Section -->
            <div class="collapse" id="filterCollapse">
                <div class="mb-3 p-3 bg-light rounded">
                    <form action="{{ route('products.index') }}" method="GET" id="filter-form">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ translate('Search') }}</label>
                                    <input type="text" class="form-control" name="search"
                                        value="{{ request('search') }}" placeholder="{{ translate('Search by name...') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ translate('Category') }}</label>
                                    <select class="form-control aiz-selectpicker" name="category_id">
                                        <option value="">{{ translate('All Categories') }}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ translate('Brand') }}</label>
                                    <select class="form-control aiz-selectpicker" name="brand_id">
                                        <option value="">{{ translate('All Brands') }}</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ translate('Status') }}</label>
                                    <select class="form-control aiz-selectpicker" name="status">
                                        <option value="">{{ translate('All') }}</option>
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                                            {{ translate('Active') }}</option>
                                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>
                                            {{ translate('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ translate('Sort By') }}</label>
                                    <select class="form-control aiz-selectpicker" name="sort_by">
                                        <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>
                                            {{ translate('Latest') }}</option>
                                        <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>
                                            {{ translate('Oldest') }}</option>
                                        <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>
                                            {{ translate('Name (A-Z)') }}</option>
                                        <option value="name_desc"
                                            {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>
                                            {{ translate('Name (Z-A)') }}</option>
                                        <option value="price_asc"
                                            {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>
                                            {{ translate('Price (Low to High)') }}</option>
                                        <option value="price_desc"
                                            {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>
                                            {{ translate('Price (High to Low)') }}</option>
                                        <option value="sale_count"
                                            {{ request('sort_by') == 'sale_count' ? 'selected' : '' }}>
                                            {{ translate('Most Sold') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">{{ translate('Apply Filters') }}</button>
                                <a href="{{ route('products.index') }}"
                                    class="btn btn-secondary">{{ translate('Reset') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bulk Action Bar -->
            <div class="mb-3" id="bulk-action-bar" style="display: none;">
                <div class="alert alert-primary">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <i class="las la-check-circle"></i>
                            <span id="selected-count">0</span> {{ translate('products selected') }}
                        </div>
                        <div class="col-md-6 text-md-right">
                            <select class="form-control-sm" id="bulk-action">
                                <option value="">{{ translate('Bulk Action') }}</option>
                                <option value="delete">{{ translate('Delete') }}</option>
                                <option value="publish">{{ translate('Publish') }}</option>
                                <option value="unpublish">{{ translate('Unpublish') }}</option>
                                <option value="featured">{{ translate('Mark as Featured') }}</option>
                                <option value="unfeatured">{{ translate('Remove Featured') }}</option>
                            </select>
                            <button type="button" class="btn btn-sm btn-primary" onclick="showBulkActionModal()">
                                {{ translate('Apply') }}
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="clearSelection()">
                                {{ translate('Clear') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="table-responsive">
                <table class="table table-bordered aiz-table">
                    <thead>
                        <tr>
                            <th width="3%">
                                <label class="aiz-checkbox">
                                    <input type="checkbox" id="select-all" class="select-all-checkbox">
                                    <span class="aiz-square-check"></span>
                                </label>
                            </th>
                            <th width="5%">#</th>
                            <th width="10%">{{ translate('Image') }}</th>
                            <th>{{ translate('Name') }}</th>
                            <th>{{ translate('Category') }}</th>
                            <th>{{ translate('Brand') }}</th>
                            <th>{{ translate('Price') }}</th>
                            <th>{{ translate('Stock') }}</th>
                            <th>{{ translate('Status') }}</th>
                            <th width="10%" class="text-right">{{ translate('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                <td>
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="product-checkbox" value="{{ $product['id'] }}">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </td>
                                <td>
                                    {{ ($products->currentPage() - 1) * $products->perPage() + $key + 1 }}
                                </td>
                                <td>
                                    @if (!empty($product['image']))
                                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}"
                                            class="size-50px img-fit">
                                    @else
                                        <img src="{{ asset('assets/img/placeholder.jpg') }}" alt="No image"
                                            class="size-50px img-fit">
                                    @endif
                                </td>
                                <td>{{ $product['name'] }}</td>
                                <td>{{ $product['category'] ?? 'N/A' }}</td>
                                <td>{{ $product['brand'] ?? 'N/A' }}</td>
                                <td>
                                    @if (isset($product['price']) && $product['price'] > 0)
                                        {{ number_format($product['price'], 2) }} BDT
                                        @if (isset($product['original']) && $product['original'] > $product['price'])
                                            <br>
                                            <small class="text-muted">
                                                <s>{{ number_format($product['original'], 2) }} BDT</s>
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($product['in_stock'])
                                        <span class="badge badge-inline badge-success">{{ translate('In Stock') }}</span>
                                    @else
                                        <span
                                            class="badge badge-inline badge-danger">{{ translate('Out of Stock') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="badge badge-inline {{ $product['in_stock'] ? 'badge-success' : 'badge-danger' }}">
                                        {{ $product['in_stock'] ? translate('Active') : translate('Inactive') }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="d-flex flex-column align-items-end">

                                        @if (in_array((string) $product['id'], $addedProductIds))
                                            <span class="badge badge-inline badge-success mb-2">
                                                {{ translate('Already Added') }}
                                            </span>
                                        @else
                                            <span class="badge badge-inline badge-warning mb-2">
                                                {{ translate('Not Added') }}
                                            </span>

                                            <a href="{{ route('products.droploo.product.add', $product['id']) }}"
                                                class="btn btn-sm btn-icon btn-info"
                                                title="{{ translate('Add Product') }}">
                                                <i class="las la-plus"></i>
                                            </a>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="aiz-pagination">
                {{ $products->links('backend.paginate.pagination') }}
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script type="text/javascript">
        let bulkActionType = null;
        let bulkSelectedIds = [];

        $(document).ready(function() {
            console.log('Document ready - Select All functionality initialized');

            // METHOD 1: Direct click handler for select-all
            $('#select-all').on('click', function(e) {
                console.log('Select All clicked - Method 1');
                var isChecked = $(this).prop('checked');
                $('.product-checkbox').prop('checked', isChecked);
                updateBulkActionBar();
                e.stopPropagation();
            });

            // METHOD 2: Change handler for select-all (fallback)
            $('#select-all').on('change', function() {
                console.log('Select All changed - Method 2');
                var isChecked = $(this).prop('checked');
                $('.product-checkbox').prop('checked', isChecked);
                updateBulkActionBar();
            });

            // METHOD 3: Event delegation for select-all (most robust)
            $(document).on('click', '#select-all', function(e) {
                console.log('Select All clicked - Method 3 (delegation)');
                var isChecked = $(this).prop('checked');
                $('.product-checkbox').prop('checked', isChecked);
                updateBulkActionBar();
            });

            // METHOD 4: Direct handler with .on() for select-all
            $(document).on('change', '#select-all', function() {
                console.log('Select All changed - Method 4 (delegation)');
                var isChecked = $(this).prop('checked');
                $('.product-checkbox').prop('checked', isChecked);
                updateBulkActionBar();
            });

            // Individual checkbox change - update select all state
            $(document).on('change', '.product-checkbox', function() {
                console.log('Individual checkbox changed');
                updateBulkActionBar();
                updateSelectAllState();
            });

            // Also handle click events on individual checkboxes
            $(document).on('click', '.product-checkbox', function() {
                console.log('Individual checkbox clicked');
                setTimeout(function() {
                    updateSelectAllState();
                }, 10);
            });

            // Initial state check
            updateSelectAllState();

            console.log('Total product checkboxes found: ' + $('.product-checkbox').length);
        });

        // Update select all checkbox state
        function updateSelectAllState() {
            var totalCheckboxes = $('.product-checkbox').length;
            var checkedCheckboxes = $('.product-checkbox:checked').length;
            var allChecked = (totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);

            console.log('Updating select all state - Total: ' + totalCheckboxes + ', Checked: ' + checkedCheckboxes +
                ', All checked: ' + allChecked);

            $('#select-all').prop('checked', allChecked);
        }

        // Update bulk action bar visibility
        function updateBulkActionBar() {
            var selected = $('.product-checkbox:checked').length;
            console.log('Selected count: ' + selected);

            if (selected > 0) {
                $('#bulk-action-bar').show();
                $('#selected-count').text(selected);
            } else {
                $('#bulk-action-bar').hide();
            }
        }

        // Clear all selections
        function clearSelection() {
            console.log('Clearing all selections');
            $('.product-checkbox').prop('checked', false);
            $('#select-all').prop('checked', false);
            updateBulkActionBar();
        }

        // Update product status (single)
        function update_status(el, product_id) {
            var status = $(el).is(':checked') ? 1 : 0;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('products.update-status') }}",
                data: {
                    product_id: product_id,
                    status: status
                },
                success: function(response) {
                    AIZ.plugins.notify('success', response.message);
                },
                error: function(xhr) {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }

        // Show bulk action modal
        function showBulkActionModal() {
            bulkSelectedIds = [];
            $('.product-checkbox:checked').each(function() {
                bulkSelectedIds.push($(this).val());
            });

            if (bulkSelectedIds.length > 0) {
                bulkActionType = $('#bulk-action').val();
                if (!bulkActionType) {
                    AIZ.plugins.notify('warning', '{{ translate('Please select an action') }}');
                    return;
                }

                var title = '',
                    message = '',
                    btnClass = '',
                    icon = '';

                if (bulkActionType == 'delete') {
                    title = '{{ translate('Delete Products') }}';
                    message = '{{ translate('Are you sure you want to delete') }} ' + bulkSelectedIds.length +
                        ' {{ translate('selected products? This action cannot be undone.') }}';
                    btnClass = 'btn-danger';
                    icon = 'las la-trash text-danger';
                } else if (bulkActionType == 'publish') {
                    title = '{{ translate('Publish Products') }}';
                    message = '{{ translate('Are you sure you want to publish') }} ' + bulkSelectedIds.length +
                        ' {{ translate('selected products?') }}';
                    btnClass = 'btn-success';
                    icon = 'las la-eye text-success';
                } else if (bulkActionType == 'unpublish') {
                    title = '{{ translate('Unpublish Products') }}';
                    message = '{{ translate('Are you sure you want to unpublish') }} ' + bulkSelectedIds.length +
                        ' {{ translate('selected products?') }}';
                    btnClass = 'btn-warning';
                    icon = 'las la-eye-slash text-warning';
                } else if (bulkActionType == 'featured') {
                    title = '{{ translate('Mark as Featured') }}';
                    message = '{{ translate('Are you sure you want to mark') }} ' + bulkSelectedIds.length +
                        ' {{ translate('selected products as featured?') }}';
                    btnClass = 'btn-info';
                    icon = 'las la-star text-info';
                } else if (bulkActionType == 'unfeatured') {
                    title = '{{ translate('Remove Featured') }}';
                    message = '{{ translate('Are you sure you want to remove featured status from') }} ' + bulkSelectedIds
                        .length + ' {{ translate('selected products?') }}';
                    btnClass = 'btn-secondary';
                    icon = 'las la-star-half-alt text-secondary';
                }

                $('#bulk-action-title').text(title);
                $('#bulk-title').text(title);
                $('#bulk-message').text(message);
                $('#bulk-confirm-btn').removeClass('btn-success btn-danger btn-warning btn-info btn-secondary').addClass(
                    btnClass);
                $('#bulk-icon').removeClass().addClass(icon + ' fs-40');

                $('#bulk-action-modal').modal('show');
            } else {
                AIZ.plugins.notify('warning', '{{ translate('Please select at least one product') }}');
            }
        }

        // Confirm bulk action
        function confirmBulkAction() {
            $('#bulk-action-modal').modal('hide');

            if (bulkActionType == 'delete') {
                bulkDelete(bulkSelectedIds);
            } else if (bulkActionType == 'publish') {
                bulkUpdateStatus(bulkSelectedIds, 1);
            } else if (bulkActionType == 'unpublish') {
                bulkUpdateStatus(bulkSelectedIds, 0);
            } else if (bulkActionType == 'featured') {
                bulkUpdateFeatured(bulkSelectedIds, 1);
            } else if (bulkActionType == 'unfeatured') {
                bulkUpdateFeatured(bulkSelectedIds, 0);
            }
        }

        // Bulk delete products
        function bulkDelete(ids) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('products.bulk-delete') }}",
                data: {
                    ids: ids
                },
                beforeSend: function() {
                    AIZ.plugins.notify('info', '{{ translate('Deleting...') }}');
                },
                success: function(response) {
                    if (response.success) {
                        AIZ.plugins.notify('success', response.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        AIZ.plugins.notify('danger', response.message);
                    }
                },
                error: function(xhr) {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        // Bulk update publish status
        function bulkUpdateStatus(ids, status) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('products.bulk-update-status') }}",
                data: {
                    ids: ids,
                    status: status
                },
                beforeSend: function() {
                    AIZ.plugins.notify('info', '{{ translate('Processing...') }}');
                },
                success: function(response) {
                    if (response.success) {
                        AIZ.plugins.notify('success', response.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        AIZ.plugins.notify('danger', response.message);
                    }
                },
                error: function(xhr) {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        // Bulk update featured status
        function bulkUpdateFeatured(ids, featured) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('products.bulk-update-featured') }}",
                data: {
                    ids: ids,
                    featured: featured
                },
                beforeSend: function() {
                    AIZ.plugins.notify('info', '{{ translate('Processing...') }}');
                },
                success: function(response) {
                    if (response.success) {
                        AIZ.plugins.notify('success', response.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        AIZ.plugins.notify('danger', response.message);
                    }
                },
                error: function(xhr) {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        // Delete confirmation handler
        $(document).on('click', '.confirm-delete', function(e) {
            e.preventDefault();
            var url = $(this).data('href');
            $('#delete-modal').modal('show');
            $('#delete-form').attr('action', url);
        });
    </script>
@endsection
