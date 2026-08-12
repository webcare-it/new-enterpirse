@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">

            <!-- Blog Info -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Blog Details') }}</h5>
                </div>

                <div class="card-body">
                    <h3 class="mb-3">{{ $blog->blog_title }}</h3>

                    @if ($blog->tags)
                        <div class="mb-3">
                            @foreach (explode(',', $blog->tags) as $tag)
                                <span class="badge badge-info mr-1 mb-1">
                                    {{ trim($tag) }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="fw-bold">{{ translate('Thumbnail Image') }}</label><br>
                        <img src="{{ uploaded_asset($blog->thumbnail) }}" alt="Thumbnail" class="img-fluid rounded"
                            style="max-width:200px;">
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">{{ translate('Main Image') }}</label><br>
                        <img src="{{ uploaded_asset($blog->main_image) }}" alt="Main Image" class="img-fluid rounded"
                            style="max-width:400px;">
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">{{ translate('Short Description') }}</label>
                        <p>{{ $blog->short_description }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">{{ translate('Long Description') }}</label>
                        <div>
                            {!! $blog->long_description !!}
                        </div>
                    </div>

                </div>
            </div>

            <!-- SEO Section -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('SEO Information') }}</h5>
                </div>

                <div class="card-body">

                    <!-- Meta Title -->
                    <div class="mb-3">
                        <label class="fw-bold">{{ translate('Meta Title') }}</label>
                        <p>{{ $blog->meta_title }}</p>
                    </div>

                    <!-- Meta Description -->
                    <div class="mb-3">
                        <label class="fw-bold">{{ translate('Meta Description') }}</label>
                        <p>{{ $blog->meta_description }}</p>
                    </div>

                    <!-- Meta Image -->
                    <div class="mb-3">
                        <label class="fw-bold">{{ translate('Meta Image') }}</label><br>
                        <img src="{{ uploaded_asset($blog->meta_image) }}" alt="Meta Image" class="img-fluid rounded"
                            style="max-width:200px;">
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
