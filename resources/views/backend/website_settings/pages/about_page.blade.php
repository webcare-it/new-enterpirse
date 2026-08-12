@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ translate('About Page') }}</h1>
        </div>
    </div>

    {{-- About Section selected --}}
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">{{ translate('About Section') }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="hidden" name="types[]" value="about_sections">
                        @php
                            $saved_sections = json_decode(get_setting('about_sections'), true) ?? [];
                        @endphp
                        <select class="form-control aiz-selectpicker" name="about_sections[]" id="about_sections" data-live-search="true" data-selected-text-format="count" multiple required>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ in_array($section->id, $saved_sections) ? 'selected' : '' }}>
                                {{ $section->title }}
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

    <form action="{{ route('about.counter') }}" method="POST">
        @csrf
        @method('PUT')

        @php
            $counterData = json_decode($counterItems->value ?? '[]', true);

            $defaults = [
                ['number' => '', 'title' => ''],
                ['number' => '', 'title' => ''],
                ['number' => '', 'title' => ''],
                ['number' => '', 'title' => ''],
            ];
        @endphp

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Statistics Section</h5>
            </div>

            <div class="card-body">

                <div class="row">

                    @for ($i = 0; $i < 4; $i++)
                        @php
                            $item = $counterData[$i] ?? $defaults[$i];
                        @endphp

                        <div class="col-md-6">
                            <div class="card shadow-sm border mb-4">

                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        Counter {{ $i + 1 }}
                                    </h6>
                                </div>

                                <div class="card-body">

                                    <div class="form-group">
                                        <label>Number <span class="text-danger">*</span></label>

                                        <input type="text" class="form-control"
                                            name="items[{{ $i }}][number]"
                                            value="{{ old('items.' . $i . '.number', $item['number']) }}"
                                            placeholder="50K+">
                                    </div>

                                    <div class="form-group">
                                        <label>Title <span class="text-danger">*</span></label>

                                        <input type="text" class="form-control"
                                            name="items[{{ $i }}][title]"
                                            value="{{ old('items.' . $i . '.title', $item['title']) }}"
                                            placeholder="Happy Customers">
                                    </div>

                                </div>

                            </div>
                        </div>
                    @endfor

                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-success">
                        <i class="las la-save"></i>
                        Save & Update
                    </button>
                </div>

            </div>
        </div>

    </form>

    <div class="card">
        <form class="p-4" action="{{ route('custom-pages.update', $page->slug) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PATCH">
       
            <div class="card-header px-0">
                <h6 class="fw-600 mb-0">{{ translate('Page Content') }}</h6>
            </div>
            <div class="card-body px-0">
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ translate('Title') }} <span
                            class="text-danger">*</span> <i class="las la-language text-danger"
                            title="{{ translate('Translatable') }}"></i></label>
                    <div class="col-sm-10">
                        {{-- Replace getTranslation with direct property access --}}
                        <input type="text" class="form-control" placeholder="{{ translate('Title') }}"
                            name="title" value="{{ $page->title }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ translate('Add Content') }} <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <textarea class="aiz-text-editor form-control" placeholder="{{ translate('Content..') }}"
                            data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link",    "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                            data-min-height="300" name="content" required {{-- Replace getTranslation with direct property access --}}>{!! $page->content !!}</textarea>
                    </div>
                </div>
            </div>

            <div class="card-header px-0">
                <h6 class="fw-600 mb-0">{{ translate('Seo Fields') }}</h6>
            </div>
            <div class="card-body px-0">

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ translate('Meta Title') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Title') }}"
                            name="meta_title" value="{{ $page->meta_title }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ translate('Meta Description') }}</label>
                    <div class="col-sm-10">
                        <textarea class="resize-off form-control" placeholder="{{ translate('Description') }}" name="meta_description">{!! $page->meta_description !!}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ translate('Keywords') }}</label>
                    <div class="col-sm-10">
                        <textarea class="resize-off form-control" placeholder="{{ translate('Keyword, Keyword') }}" name="keywords">{!! $page->keywords !!}</textarea>
                        <small class="text-muted">{{ translate('Separate with coma') }}</small>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ translate('Meta Image') }}</label>
                    <div class="col-sm-10">
                        <div class="input-group " data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">
                                    {{ translate('Browse') }}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="meta_image" class="selected-files"
                                value="{{ $page->meta_image }}">
                        </div>
                        <div class="file-preview">
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">{{ translate('Update Page') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection
