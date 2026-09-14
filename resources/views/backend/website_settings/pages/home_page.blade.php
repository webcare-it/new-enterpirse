@extends('backend.layouts.app')
@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Home Page Settings') }}</h1>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- LEFT:  Home Page Settings --}}
        <div class="col-md-6">
            {{-- Home Slider --}}
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Home Sliders') }}</h6>
                </div>
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                        <div class="alert alert-info">
                            {{ translate('Banner size will be 1980x360') }}
                        </div>
                        <a style="text-decoration: underline" href="{{ route('slider.index') }}"
                            class="">{{ translate('Manage Sliders') }}</a>
                    </div>
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="hidden" name="types[]" value="home_sliders">
                                @php
                                    $saved_slider = json_decode(get_setting('home_sliders'), true) ?? [];
                                @endphp
                                <select class="form-control aiz-selectpicker" name="home_sliders[]" id="sliders"
                                    data-live-search="true" data-selected-text-format="count" multiple required>
                                    @foreach ($sliders as $s)
                                        <option value="{{ $s->id }}"
                                            {{ in_array($s->id, $saved_slider) ? 'selected' : '' }}>
                                            {{ $s->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Campaign selected --}}
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Home Campaigns') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">

                            <div class="alert alert-info">
                                {{ translate('The campaigns selected here will be displayed on the home page.') }}
                            </div>
                            <a style="text-decoration: underline" href="{{ route('campaigns.index') }}"
                                class="">{{ translate('Manage Campaigns') }}</a>
                        </div>
                        @csrf
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="hidden" name="types[]" value="home_campaigns">
                                @php
                                    $saved_campaign = json_decode(get_setting('home_campaigns'), true) ?? [];
                                @endphp
                                <select class="form-control aiz-selectpicker" name="home_campaigns[]" id="campaigns"
                                    data-live-search="true" data-selected-text-format="count" multiple>
                                    @foreach ($campaigns as $c)
                                        <option value="{{ $c->id }}"
                                            {{ in_array($c->id, $saved_campaign) ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Home Section Products --}}
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Home Section Products') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        <div class="alert alert-info">
                            {{ translate('Select products for each section of the home page.') }}
                        </div>
                        @csrf

                        <div class="form-group">
                            <label
                                class="col-md-12 control-label">{{ translate('Selected Best Selling Products') }}</label>
                            <div class="col-md-12">
                                <input type="hidden" name="types[]" value="h_best_s_products">
                                @php
                                    $saved_best_selling = json_decode(get_setting('h_best_s_products'), true) ?? [];
                                @endphp
                                <select class="form-control aiz-selectpicker" name="h_best_s_products[]"
                                    id="best_selling_products" data-live-search="true" data-selected-text-format="count"
                                    multiple>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ in_array($product->id, $saved_best_selling) ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label
                                class="col-md-12 control-label">{{ translate("Selected Today's Deals Products") }}</label>
                            <div class="col-md-12">
                                <input type="hidden" name="types[]" value="h_todays_d_products">
                                @php
                                    $saved_today_deals = json_decode(get_setting('h_todays_d_products'), true) ?? [];
                                @endphp
                                <select class="form-control aiz-selectpicker" name="h_todays_d_products[]"
                                    id="todays_deal_products" data-live-search="true" data-selected-text-format="count"
                                    multiple>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ in_array($product->id, $saved_today_deals) ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label
                                class="col-md-12 control-label">{{ translate('Selected New Arrivals Products') }}</label>
                            <div class="col-md-12">
                                <input type="hidden" name="types[]" value="h_new_a_products">
                                @php
                                    $saved_new_arrival = json_decode(get_setting('h_new_a_products'), true) ?? [];
                                @endphp
                                <select class="form-control aiz-selectpicker" name="h_new_a_products[]"
                                    id="new_arrival_products" data-live-search="true" data-selected-text-format="count"
                                    multiple>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ in_array($product->id, $saved_new_arrival) ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12 control-label">{{ translate('Selected Featured Products') }}</label>
                            <div class="col-md-12">
                                <input type="hidden" name="types[]" value="h_featured_products">
                                @php
                                    $saved_featured = json_decode(get_setting('h_featured_products'), true) ?? [];
                                @endphp
                                <select class="form-control aiz-selectpicker" name="h_featured_products[]"
                                    id="featured_products" data-live-search="true" data-selected-text-format="count"
                                    multiple>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ in_array($product->id, $saved_featured) ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Home Categories --}}
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Home Categories') }}</h6>
                    <a style="text-decoration: underline" href="{{ route('dropshipping-category.index') }}"
                        class="">{{ translate('Manage Categories') }}</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="hidden" name="types[]" value="home_categories">
                                @php
                                    $saved_categories = json_decode(get_setting('home_categories'), true) ?? [];
                                @endphp
                                <select class="form-control aiz-selectpicker" name="home_categories[]" id="categories"
                                    data-live-search="true" data-selected-text-format="count" multiple>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ in_array($category->id, $saved_categories) ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label
                                class="col-md-12 control-label">{{ translate("How many category's products to show") }}</label>
                            <div class="col-md-12">
                                <input type="hidden" name="types[]" value="h_category_p_limit">
                                <input type="number" min="6" name="h_category_p_limit" class="form-control"
                                    placeholder="{{ translate("How many category's products to show") }}"
                                    value="{{ get_setting('h_category_p_limit') ?? 6 }}">
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Home Section Products --}}
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Home Section Blogs') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        <div class="alert alert-info">
                            {{ translate('Select blogs for each section of the home page.') }}
                        </div>
                        @csrf

                        <div class="form-group">
                            <label class="col-md-12 control-label">{{ translate('Selected Blogs') }}</label>
                            <div class="col-md-12">
                                <input type="hidden" name="types[]" value="home_blogs">
                                @php
                                    $saved_blogs = json_decode(get_setting('home_blogs'), true) ?? [];
                                @endphp
                                <select class="form-control aiz-selectpicker" name="home_blogs[]" id="home_blogs"
                                    data-live-search="true" data-selected-text-format="count" multiple>
                                    @foreach ($blogs as $blog)
                                        <option value="{{ $blog->id }}"
                                            {{ in_array($blog->id, $saved_blogs) ? 'selected' : '' }}>
                                            {{ $blog->blog_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- RIGHT: All Sections  --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('All Sections') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th width="50">{{ translate('Sort') }}</th>
                                    <th>{{ translate('Order') }}</th>
                                    <th>{{ translate('Title') }}</th>
                                    <th>{{ translate('Key') }}</th>
                                    <th>{{ translate('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody id="section-sortable">
                                @forelse ($sectionconfigs as $key => $sectionconfig)
                                    <tr data-id="{{ $sectionconfig->id }}">
                                        <td class="cursor-move" style="cursor: move;">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#6c757d">
                                                <rect x="2" y="2" width="5" height="5" rx="1.5" />
                                                <rect x="9.5" y="2" width="5" height="5" rx="1.5" />
                                                <rect x="17" y="2" width="5" height="5" rx="1.5" />
                                                <rect x="2" y="9.5" width="5" height="5" rx="1.5" />
                                                <rect x="9.5" y="9.5" width="5" height="5" rx="1.5" />
                                                <rect x="17" y="9.5" width="5" height="5" rx="1.5" />
                                            </svg>
                                        </td>
                                        <td>{{ $sectionconfig->order }}</td>
                                        <td>
                                            <span class="editable-title" data-id="{{ $sectionconfig->id }}"
                                                data-original="{{ $sectionconfig->title }}">
                                                {{ $sectionconfig->title }}
                                            </span>
                                        </td>
                                        <td><strong>{{ $sectionconfig->key }}</strong></td>
                                        <td>
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input type="checkbox" class="status-toggle"
                                                    data-id="{{ $sectionconfig->id }}"
                                                    {{ $sectionconfig->isActive ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            {{ translate('No sections found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="aiz-pagination mt-3">
                        {{ $sectionconfigs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        $(function() {
            $("#section-sortable").sortable({
                handle: ".cursor-move",
                update: function() {
                    let orders = [];
                    $('#section-sortable tr').each(function(index) {
                        orders.push({
                            id: $(this).data('id'),
                            order: index + 1
                        });
                    });
                    $.ajax({
                        url: "{{ route('sectionconfig.sort') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            orders: orders
                        },
                        success: function(response) {
                            AIZ.plugins.notify('success', 'Order updated successfully!');
                        },
                        error: function() {
                            AIZ.plugins.notify('danger', 'Something went wrong.');
                        }
                    });
                }
            });
        });

        $(document).on('change', '.status-toggle', function() {
            let checkbox = $(this);
            let id = checkbox.data('id');
            let status = checkbox.prop('checked') ? 1 : 0;

            $.ajax({
                url: "{{ route('sectionconfig.toggleStatus') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    isActive: status
                },
                success: function(response) {
                    AIZ.plugins.notify('success', 'Status updated!');
                },
                error: function() {
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    AIZ.plugins.notify('danger', 'Failed to update status.');
                }
            });
        });

        $(document).on('click', '.editable-title', function() {
            let $span = $(this);
            if ($span.hasClass('editing')) return;

            let currentText = $span.text().trim();
            let id = $span.data('id');

            let $input = $('<input>', {
                type: 'text',
                value: currentText,
                class: 'form-control form-control-sm editable-input',
                style: 'display:inline-block; width:auto; min-width:120px;',
                'data-id': id,
                'data-original': currentText
            });

            $span.replaceWith($input);
            $input.focus().select();

            $input.on('blur', function() {
                saveTitle($(this));
            });

            $input.on('keydown', function(e) {
                if (e.key === 'Enter') {
                    $(this).blur();
                }
                if (e.key === 'Escape') {
                    let original = $(this).data('original');
                    $(this).replaceWith(createSpan(id, original, original));
                }
            });
        });

        function saveTitle($input) {
            let id = $input.data('id');
            let newTitle = $input.val().trim();
            let original = $input.data('original');

            if (newTitle === '') {
                newTitle = original;
            }

            $.ajax({
                url: "{{ route('sectionconfig.updateTitle') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    title: newTitle
                },
                success: function(response) {
                    AIZ.plugins.notify('success', response.message);
                    $input.replaceWith(createSpan(id, response.title, response.title));
                },
                error: function() {
                    AIZ.plugins.notify('danger', 'Failed to update title.');
                    $input.replaceWith(createSpan(id, original, original));
                }
            });
        }

        function createSpan(id, text, original) {
            return $('<span>', {
                class: 'editable-title',
                'data-id': id,
                'data-original': original || text,
                text: text
            });
        }
    </script>
    <style>
        .editable-title {
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            transition: background 0.2s;
            display: inline-block;
            min-width: 60px;
        }

        .editable-title:hover {
            background: #f1f3f5;
        }

        .editable-input {
            display: inline-block !important;
            width: auto !important;
            min-width: 120px;
            padding: 4px 8px;
            height: auto;
        }
    </style>
@endsection
