@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('About Section') }}</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">

            <form action="{{ route('about.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('About Information') }}</h5>
                    </div>

                    <div class="card-body">
                        <div id="about-items-container">
                            {{-- Existing items --}}
                            @if (isset($aboutItems) && count($aboutItems) > 0)
                                @foreach ($aboutItems as $index => $item)
                                    <div class="about-item card mb-3 p-3" data-index="{{ $index }}">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">{{ translate('Item') }} #{{ $loop->iteration }}</h6>
                                            <button type="button" class="btn btn-sm btn-danger remove-item">
                                                {{ translate('Remove') }}
                                            </button>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ translate('Title') }}</label>
                                            <div class="col-md-9">
                                                <input type="text" name="items[{{ $index }}][title]"
                                                    value="{{ $item->title ?? '' }}" class="form-control"
                                                    placeholder="{{ translate('Enter title') }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ translate('Sub Title') }}</label>
                                            <div class="col-md-9">
                                                <input type="text" name="items[{{ $index }}][sub_title]"
                                                    value="{{ $item->sub_title ?? '' }}" class="form-control"
                                                    placeholder="{{ translate('Enter sub title') }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ translate('Description') }}</label>
                                            <div class="col-md-9">
                                                <textarea name="items[{{ $index }}][description]" class="aiz-text-editor form-control" rows="3"
                                                    placeholder="{{ translate('Enter description') }}">{{ $item->description ?? '' }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ translate('Image') }}</label>
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
                                                    <input type="hidden" name="items[{{ $index }}][image]"
                                                        value="{{ $item->image ?? '' }}" class="selected-files">
                                                </div>
                                                <div class="file-preview box sm mt-2">
                                                    @if (!empty($item->image))
                                                        <img src="{{ uploaded_asset($item->image) }}"
                                                            style="height:60px; object-fit:cover;">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label">{{ translate('Image Position') }}</label>
                                            <div class="col-md-9">
                                                <select name="items[{{ $index }}][image_position]"
                                                    class="form-control aiz-selectpicker">
                                                    <option value="left"
                                                        @if (isset($item->image_position) && $item->image_position == 'left') selected @endif>
                                                        {{ translate('Left') }}</option>
                                                    <option value="right"
                                                        @if (isset($item->image_position) && $item->image_position == 'right') selected @endif>
                                                        {{ translate('Right') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                @endforeach
                            @else
                                {{-- Default empty item --}}
                                <div class="about-item card mb-3 p-3" data-index="0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">{{ translate('Item') }} #1</h6>
                                        <button type="button" class="btn btn-sm btn-danger remove-item">
                                            {{ translate('Remove') }}
                                        </button>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">{{ translate('Title') }}</label>
                                        <div class="col-md-9">
                                            <input type="text" name="items[0][title]" value="{{ old('items.0.title') }}"
                                                class="form-control" placeholder="{{ translate('Enter title') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">{{ translate('Sub Title') }}</label>
                                        <div class="col-md-9">
                                            <input type="text" name="items[0][sub_title]"
                                                value="{{ old('items.0.sub_title') }}" class="form-control"
                                                placeholder="{{ translate('Enter sub title') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">{{ translate('Description') }}</label>
                                        <div class="col-md-9">
                                            <textarea name="items[0][description]" class="form-control" rows="3"
                                                placeholder="{{ translate('Enter description') }}">{{ old('items.0.description') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">{{ translate('Image') }}</label>
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
                                                <input type="hidden" name="items[0][image]"
                                                    value="{{ old('items.0.image') }}" class="selected-files">
                                            </div>
                                            <div class="file-preview box sm mt-2"></div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">{{ translate('Image Position') }}</label>
                                        <div class="col-md-9">
                                            <select name="items[0][image_position]" class="form-control aiz-selectpicker">
                                                <option value="left" selected>{{ translate('Left') }}</option>
                                                <option value="right">{{ translate('Right') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            @endif
                        </div>

                        {{-- Add More Button --}}
                        <div class="text-center mb-3">
                            <button type="button" class="btn btn-primary" id="add-more-btn">
                                <i class="las la-plus"></i> {{ translate('Add More') }}
                            </button>
                        </div>

                    </div> {{-- card-body --}}
                </div> {{-- card --}}

                {{-- Submit --}}
                <div class="text-right mb-3">
                    <button type="submit" class="btn btn-success">
                        {{ translate('Save & Update') }}
                    </button>
                </div>

            </form>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            function reindexItems() {
                $('#about-items-container .about-item').each(function(index) {
                    var $item = $(this);
                    $item.attr('data-index', index);
                    $item.find('h6').text('{{ translate('Item') }} #' + (index + 1));

                    $item.find('input, textarea, select').each(function() {
                        var $input = $(this);
                        var name = $input.attr('name');
                        if (name) {
                            name = name.replace(/items\[\d+\]/, 'items[' + index + ']');
                            $input.attr('name', name);
                        }
                    });
                    $item.find('.selected-files').each(function() {
                        var $input = $(this);
                        var name = $input.attr('name');
                        if (name) {
                            name = name.replace(/items\[\d+\]/, 'items[' + index + ']');
                            $input.attr('name', name);
                        }
                    });
                });
            }

            function getNewItemHtml() {
                return `
                    <div class="about-item card mb-3 p-3" data-index="new">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">{{ translate('Item') }} #</h6>
                            <button type="button" class="btn btn-sm btn-danger remove-item">
                                {{ translate('Remove') }}
                            </button>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Title') }}</label>
                            <div class="col-md-9">
                                <input type="text" name="items[new][title]" class="form-control"
                                    placeholder="{{ translate('Enter title') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Sub Title') }}</label>
                            <div class="col-md-9">
                                <input type="text" name="items[new][sub_title]" class="form-control"
                                    placeholder="{{ translate('Enter sub title') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Description') }}</label>
                            <div class="col-md-9">
                                <textarea name="items[new][description]" class="aiz-text-editor form-control" rows="3"
                                    placeholder="{{ translate('Enter description') }}"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Image') }}</label>
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
                                    <input type="hidden" name="items[new][image]" class="selected-files">
                                </div>
                                <div class="file-preview box sm mt-2"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Image Position') }}</label>
                            <div class="col-md-9">
                                <select name="items[new][image_position]" class="form-control aiz-selectpicker">
                                    <option value="left" selected>{{ translate('Left') }}</option>
                                    <option value="right">{{ translate('Right') }}</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                    </div>
                `;
            }

            $('#add-more-btn').on('click', function() {
                var html = getNewItemHtml();
                $('#about-items-container').append(html);
                reindexItems();
                AIZ.plugins.aizUploader.init();
                AIZ.plugins.aizSelectPicker.init();
            });

            $(document).on('click', '.remove-item', function() {
                var parentItem = $(this).closest('.about-item');
                if ($('.about-item').length > 1) {
                    parentItem.remove();
                    reindexItems();
                } else {
                    alert('{{ translate('You need at least one item.') }}');
                }
            });
            reindexItems();
        });
    </script>
@endsection
