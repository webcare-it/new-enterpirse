@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Landing Page Details') }}</h1>
                <p class="text-muted mb-0">{{ translate('View landing page information') }}</p>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('landingpages.index') }}" class="btn btn-secondary">
                    <i class="las la-arrow-left"></i> {{ translate('Back to List') }}
                </a>
                <a href="{{ route('landingpages.edit', $landingPage->id) }}" class="btn btn-primary">
                    <i class="las la-edit"></i> {{ translate('Edit Page') }}
                </a>
                <a href="{{ route('landingpages.preview', $landingPage->id) }}" class="btn btn-info" target="_blank">
                    <i class="las la-eye"></i> {{ translate('Preview') }}
                </a>
            </div>
        </div>
    </div>

    @php
        // Decode JSON fields
        $features = is_string($landingPage->features)
            ? json_decode($landingPage->features, true)
            : $landingPage->features ?? [];
        $testimonials = is_string($landingPage->testimonials)
            ? json_decode($landingPage->testimonials, true)
            : $landingPage->testimonials ?? [];
        $faq = is_string($landingPage->faq) ? json_decode($landingPage->faq, true) : $landingPage->faq ?? [];
        $products = $landingPage->products ?? collect();
    @endphp

    <div class="row">
        <div class="col-md-8">
            <!-- Basic Information Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Basic Information') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <tr>
                            <th width="30%">{{ translate('Page Name') }}</th>
                            <td>{{ $landingPage->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Slug') }}</th>
                            <td>{{ $landingPage->slug }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Page Title') }}</th>
                            <td>{{ $landingPage->title ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Sub Title') }}</th>
                            <td>{{ $landingPage->sub_title ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Short Description') }}</th>
                            <td>{{ $landingPage->short_description ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Full Description') }}</th>
                            <td>{!! $landingPage->description ?? 'N/A' !!}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Created At') }}</th>
                            <td>{{ $landingPage->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Last Updated') }}</th>
                            <td>{{ $landingPage->updated_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Hero Section Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Hero Section') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <tr>
                            <th width="30%">{{ translate('Banner Image') }}</th>
                            <td>
                                @if ($landingPage->banner_image)
                                    <img src="{{ uploaded_asset($landingPage->banner_image) }}" alt="Banner"
                                        class="img-fluid" style="max-height: 150px;">
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translate('Mobile Banner Image') }}</th>
                            <td>
                                @if ($landingPage->mobile_banner)
                                    <img src="{{ uploaded_asset($landingPage->mobile_banner) }}" alt="Mobile Banner"
                                        class="img-fluid" style="max-height: 150px;">
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translate('Video Link') }}</th>
                            <td>
                                @if ($landingPage->video_link)
                                    <a href="{{ $landingPage->video_link }}"
                                        target="_blank">{{ $landingPage->video_link }}</a>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Products Card -->
            @if ($products->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Products') }}</h5>
                        <span class="badge badge-primary">{{ $products->count() }} {{ translate('Products') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ translate('Product') }}</th>
                                        <th>{{ translate('Regular Price') }}</th>
                                        <th>{{ translate('Discount Price') }}</th>
                                        <th>{{ translate('Discount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $index => $product)
                                        @php
                                            $regularPrice = $product->pivot->regular_price ?? 0;
                                            $discountPrice = $product->pivot->discount_price ?? null;
                                            $percentage =
                                                $regularPrice > 0 && $discountPrice
                                                    ? round((($regularPrice - $discountPrice) / $regularPrice) * 100)
                                                    : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $product->name }}</strong>
                                                <br>
                                                <small class="text-muted">SKU:
                                                    {{ $product->inventory->sku ?? 'N/A' }}</small>
                                            </td>
                                            <td>৳{{ number_format($regularPrice, 2) }}</td>
                                            <td>
                                                @if ($discountPrice)
                                                    ৳{{ number_format($discountPrice, 2) }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($discountPrice)
                                                    <span class="badge badge-success">{{ $percentage }}% OFF</span>
                                                    <br>
                                                    <small>{{ translate('Save') }}
                                                        ৳{{ number_format($regularPrice - $discountPrice, 2) }}</small>
                                                @else
                                                    <span class="text-muted">No discount</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Features Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Features') }}</h5>
                </div>
                <div class="card-body">
                    @if (is_array($features) && count($features) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="15%">{{ translate('Icon') }}</th>
                                        <th width="35%">{{ translate('Title') }}</th>
                                        <th width="45%">{{ translate('Description') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($features as $index => $feature)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><i class="{{ $feature['icon'] ?? 'las la-star' }} fs-24"></i></td>
                                            <td><strong>{{ $feature['title'] ?? '' }}</strong></td>
                                            <td>{{ $feature['description'] ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="las la-cube fs-40 text-muted"></i>
                            <p class="text-muted mt-2">{{ translate('No features added') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Testimonials Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Testimonials') }}</h5>
                </div>
                <div class="card-body">
                    @if (is_array($testimonials) && count($testimonials) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="25%">{{ translate('Customer') }}</th>
                                        <th width="20%">{{ translate('Position') }}</th>
                                        <th width="15%">{{ translate('Rating') }}</th>
                                        <th width="35%">{{ translate('Comment') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($testimonials as $index => $testimonial)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $testimonial['name'] ?? '' }}</strong></td>
                                            <td>{{ $testimonial['position'] ?? '' }}</td>
                                            <td>
                                                <div class="rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= ($testimonial['rating'] ?? 0))
                                                            <i class="las la-star text-warning"></i>
                                                        @else
                                                            <i class="lar la-star text-muted"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </td>
                                            <td>{{ Str::limit($testimonial['comment'] ?? '', 100) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="las la-comment-slash fs-40 text-muted"></i>
                            <p class="text-muted mt-2">{{ translate('No testimonials added') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- FAQ Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('FAQ Section') }}</h5>
                </div>
                <div class="card-body">
                    @if (is_array($faq) && count($faq) > 0)
                        <div class="accordion" id="faqAccordion">
                            @foreach ($faq as $index => $faqItem)
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button {{ $index != 0 ? 'collapsed' : '' }}"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#faq{{ $index }}">
                                            {{ $faqItem['question'] ?? '' }}
                                        </button>
                                    </h2>
                                    <div id="faq{{ $index }}"
                                        class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            {{ $faqItem['answer'] ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="las la-question-circle fs-40 text-muted"></i>
                            <p class="text-muted mt-2">{{ translate('No FAQ added') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- SEO Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('SEO Information') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <tr>
                            <th width="30%">{{ translate('Meta Title') }}</th>
                            <td>{{ $landingPage->meta_title ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Meta Description') }}</th>
                            <td>{{ $landingPage->meta_description ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Meta Image') }}</th>
                            <td>
                                @if ($landingPage->meta_image)
                                    <img src="{{ uploaded_asset($landingPage->meta_image) }}" alt="Meta Image"
                                        class="img-fluid" style="max-height: 100px;">
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Status Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Publication Status') }}</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        @if ($landingPage->is_published)
                            <span class="badge badge-success">{{ translate('Published') }}</span>
                        @else
                            <span class="badge badge-danger">{{ translate('Draft') }}</span>
                        @endif

                        <div class="mt-3">
                            <button type="button"
                                class="btn btn-sm {{ $landingPage->is_published ? 'btn-warning' : 'btn-success' }}"
                                onclick="toggleStatus({{ $landingPage->id }})">
                                <i class="las la-{{ $landingPage->is_published ? 'eye-slash' : 'eye' }}"></i>
                                {{ $landingPage->is_published ? translate('Unpublish') : translate('Publish') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Countdown Info Card -->
            @if ($landingPage->deadline)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Countdown Timer') }}</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="h4 mb-2">{{ \Carbon\Carbon::parse($landingPage->deadline)->format('d M Y, h:i A') }}
                        </div>
                        @if (\Carbon\Carbon::parse($landingPage->deadline)->isPast())
                            <span class="badge badge-danger">Expired</span>
                        @else
                            <span class="badge badge-success">Active</span>
                            <div class="mt-2 small text-muted">
                                {{ \Carbon\Carbon::parse($landingPage->deadline)->diffForHumans() }} remaining
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Social Proof Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Social Proof') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="h2 mb-0 text-primary">{{ number_format($landingPage->sold_count) }}</div>
                                <div class="text-muted small">{{ translate('Sold Count') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="h2 mb-0 text-success">{{ number_format($landingPage->visitor_count) }}</div>
                                <div class="text-muted small">{{ translate('Visitor Count') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 alert alert-info">
                        <i class="las la-info-circle"></i>
                        {{ translate('Visitor count auto-increments when someone visits the page.') }}
                    </div>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Contact Information') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <tr>
                            <th width="40%">{{ translate('Phone') }}</th>
                            <td>{{ $landingPage->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Email') }}</th>
                            <td>{{ $landingPage->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('WhatsApp') }}</th>
                            <td>
                                @if ($landingPage->whatsapp)
                                    <a href="https://wa.me/{{ $landingPage->whatsapp }}" target="_blank">
                                        {{ $landingPage->whatsapp }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Footer Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Footer Settings') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <tr>
                            <th width="40%">{{ translate('Copyright Text') }}</th>
                            <td>{{ $landingPage->copyright_text ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Quick Actions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('landingpages.edit', $landingPage->id) }}" class="btn btn-primary">
                            <i class="las la-edit"></i> {{ translate('Edit Landing Page') }}
                        </a>
                        <a href="{{ route('landingpages.preview', $landingPage->id) }}" class="btn btn-info"
                            target="_blank">
                            <i class="las la-eye"></i> {{ translate('Preview on Frontend') }}
                        </a>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete-modal"
                            onclick="setDeleteForm('{{ route('landingpages.destroy', $landingPage->id) }}')">
                            <i class="las la-trash"></i> {{ translate('Delete Landing Page') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Delete Landing Page') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="las la-trash text-danger" style="font-size: 48px;"></i>
                    <h4 class="mt-2">{{ translate('Are you sure?') }}</h4>
                    <p>{{ translate('This landing page will be permanently deleted.') }}</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <form id="delete-form" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ translate('Delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function setDeleteForm(url) {
            $('#delete-form').attr('action', url);
        }

        function toggleStatus(id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('landingpages.toggle-status', '') }}/" + id,
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
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }
    </script>

    <style>
        {
            font-size: 14px;
            padding: 8px 16px;
        }

        .fs-24 {
            font-size: 24px;
        }

        .fs-40 {
            font-size: 40px;
        }

        .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .accordion-button:focus {
            box-shadow: none;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .d-grid {
            display: grid;
        }

        .gap-2 {
            gap: 0.5rem;
        }
    </style>
@endsection
