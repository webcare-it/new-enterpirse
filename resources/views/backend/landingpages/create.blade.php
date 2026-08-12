@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Create Landing Page') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('landingpages.index') }}" class="btn btn-secondary">
                    <i class="las la-arrow-left"></i> {{ translate('Back to Landing Pages') }}
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('landingpages.store') }}" method="POST" enctype="multipart/form-data" id="landingpage-form">
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
                            <label class="col-md-3 col-from-label">{{ translate('Page Name') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="{{ translate('Page Name') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Slug') }}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="slug" id="slug"
                                    placeholder="{{ translate('URL Slug (auto-generated if empty)') }}">
                                <small
                                    class="text-muted">{{ translate('Leave empty to auto-generate from page name') }}</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Page Title') }}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="title"
                                    placeholder="{{ translate('Page Title (H1)') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Sub Title') }}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="sub_title"
                                    placeholder="{{ translate('Sub Title (H2)') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Short Description') }}</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="short_description" rows="3"
                                    placeholder="{{ translate('Brief description for the page') }}"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Full Description') }}</label>
                            <div class="col-md-8">
                                <textarea class="form-control aiz-text-editor" name="description"
                                    placeholder="{{ translate('Detailed description with HTML support') }}"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hero Section Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Hero Section') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Banner Image') }}</label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="banner_image" class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                                <small class="text-muted">{{ translate('Recommended size: 1920x800px') }}</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Mobile Banner Image') }}</label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="mobile_banner" class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                                <small
                                    class="text-muted">{{ translate('Recommended size: 768x600px (for mobile devices)') }}</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Video Link') }}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="video_link"
                                    placeholder="{{ translate('YouTube or Vimeo video URL') }}">
                                <small class="text-muted">{{ translate('Optional: Add a background video') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Products') }}</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="add-product">
                            <i class="las la-plus"></i> {{ translate('Add Product') }}
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="products-container">
                            <div class="product-row row mb-3">
                                <div class="col-md-5">
                                    <select class="form-control aiz-selectpicker product-select"
                                        name="products[0][product_id]" data-live-search="true">
                                        <option value="">{{ translate('Select Product') }}</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" step="0.01" class="form-control"
                                        name="products[0][regular_price]" placeholder="{{ translate('Regular Price') }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" step="0.01" class="form-control"
                                        name="products[0][discount_price]"
                                        placeholder="{{ translate('Discount Price') }}">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-icon btn-danger remove-product">
                                        <i class="las la-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <small
                            class="text-muted">{{ translate('Add products with custom pricing for this landing page') }}</small>
                    </div>
                </div>

                <!-- Countdown Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Countdown Timer') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Offer Deadline') }}</label>
                            <div class="col-md-8">
                                <input type="datetime-local" class="form-control" name="deadline">
                                <small
                                    class="text-muted">{{ translate('Optional: Show countdown timer on the page') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Features') }}</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="add-feature">
                            <i class="las la-plus"></i> {{ translate('Add Feature') }}
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="features-container">
                            <div class="feature-row row mb-3">
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="features[0][icon]"
                                        placeholder="{{ translate('Icon Class') }}">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="features[0][title]"
                                        placeholder="{{ translate('Feature Title') }}">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="features[0][description]"
                                        placeholder="{{ translate('Feature Description') }}">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-icon btn-danger remove-feature">
                                        <i class="las la-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonials Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Testimonials') }}</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="add-testimonial">
                            <i class="las la-plus"></i> {{ translate('Add Testimonial') }}
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="testimonials-container">
                            <div class="testimonial-row row mb-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="testimonials[0][name]"
                                        placeholder="{{ translate('Customer Name') }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="testimonials[0][position]"
                                        placeholder="{{ translate('Position / Company') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="testimonials[0][rating]"
                                        placeholder="{{ translate('Rating (1-5)') }}">
                                </div>
                                <div class="col-md-3">
                                    <textarea class="form-control" name="testimonials[0][comment]" rows="2"
                                        placeholder="{{ translate('Testimonial Comment') }}"></textarea>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-icon btn-danger remove-testimonial">
                                        <i class="las la-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('FAQ Section') }}</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="add-faq">
                            <i class="las la-plus"></i> {{ translate('Add FAQ') }}
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="faq-container">
                            <div class="faq-row row mb-3">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="faq[0][question]"
                                        placeholder="{{ translate('Question') }}">
                                </div>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="faq[0][answer]" rows="2" placeholder="{{ translate('Answer') }}"></textarea>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-icon btn-danger remove-faq">
                                        <i class="las la-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('SEO Meta Tags') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Meta Title') }}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="meta_title"
                                    placeholder="{{ translate('Meta Title for SEO') }}">
                                <small class="text-muted">{{ translate('Recommended length: 50-60 characters') }}</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Meta Description') }}</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="meta_description" rows="3"
                                    placeholder="{{ translate('Meta Description for SEO') }}"></textarea>
                                <small
                                    class="text-muted">{{ translate('Recommended length: 150-160 characters') }}</small>
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
                                    <input type="hidden" name="meta_image" class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                                <small
                                    class="text-muted">{{ translate('Image for social media sharing (Recommended: 1200x630px)') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Publication Status') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" name="is_published" value="1" checked>
                                <span class="">{{ translate('') }}</span>
                            </label>
                        </div>
                        <div class="alert alert-info mt-2">
                            <i class="las la-info-circle"></i>
                            {{ translate('Published pages will be visible to customers. Draft pages are hidden.') }}
                        </div>
                    </div>
                </div>

                <!-- Social Proof Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Social Proof') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>{{ translate('Sold Count') }}</label>
                            <input type="number" class="form-control" name="sold_count" value="0" min="0">
                            <small class="text-muted">{{ translate('Number of items sold (for trust badge)') }}</small>
                        </div>

                        <div class="form-group">
                            <label>{{ translate('Visitor Count') }}</label>
                            <input type="number" class="form-control" name="visitor_count" value="0"
                                min="0">
                            <small class="text-muted">{{ translate('Initial visitor count (auto-increments)') }}</small>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Contact Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>{{ translate('Phone Number') }}</label>
                            <input type="text" class="form-control" name="phone"
                                placeholder="{{ translate('Contact phone number') }}">
                        </div>

                        <div class="form-group">
                            <label>{{ translate('Email Address') }}</label>
                            <input type="email" class="form-control" name="email"
                                placeholder="{{ translate('Contact email address') }}">
                        </div>

                        <div class="form-group">
                            <label>{{ translate('WhatsApp Number') }}</label>
                            <input type="text" class="form-control" name="whatsapp"
                                placeholder="{{ translate('WhatsApp number with country code') }}">
                        </div>
                    </div>
                </div>

                <!-- Footer Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Footer Settings') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>{{ translate('Copyright Text') }}</label>
                            <input type="text" class="form-control" name="copyright_text"
                                placeholder="{{ translate('Copyright text for footer') }}">
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="las la-save"></i> {{ translate('Create Landing Page') }}
                            </button>
                            <a href="{{ route('landingpages.index') }}" class="btn btn-secondary">
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
        let productIndex = 1;
        let featureIndex = 1;
        let testimonialIndex = 1;
        let faqIndex = 1;

        $(document).ready(function() {
            // Auto generate slug from page name
            $('#name').on('keyup', function() {
                var name = $(this).val();
                var slug = name.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/--+/g, '-')
                    .trim();
                $('#slug').val(slug);
            });

            // Add Product Row
            $('#add-product').on('click', function() {
                var newRow = `
                <div class="product-row row mb-3">
                    <div class="col-md-5">
                        <select class="form-control aiz-selectpicker product-select" name="products[${productIndex}][product_id]" data-live-search="true">
                            <option value="">{{ translate('Select Product') }}</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" class="form-control" name="products[${productIndex}][regular_price]" 
                            placeholder="{{ translate('Regular Price') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" class="form-control" name="products[${productIndex}][discount_price]" 
                            placeholder="{{ translate('Discount Price') }}">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-icon btn-danger remove-product">
                            <i class="las la-trash"></i>
                        </button>
                    </div>
                </div>
            `;
                $('#products-container').append(newRow);
                $('.product-select').selectpicker('refresh');
                productIndex++;
            });

            // Remove Product Row
            $(document).on('click', '.remove-product', function() {
                if ($('.product-row').length > 1) {
                    $(this).closest('.product-row').remove();
                }
            });

            // Add Feature Row
            $('#add-feature').on('click', function() {
                var newRow = `
                <div class="feature-row row mb-3">
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="features[${featureIndex}][icon]"
                            placeholder="{{ translate('Icon Class') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="features[${featureIndex}][title]"
                            placeholder="{{ translate('Feature Title') }}">
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="features[${featureIndex}][description]"
                            placeholder="{{ translate('Feature Description') }}">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-icon btn-danger remove-feature">
                            <i class="las la-trash"></i>
                        </button>
                    </div>
                </div>
            `;
                $('#features-container').append(newRow);
                featureIndex++;
            });

            // Remove Feature Row
            $(document).on('click', '.remove-feature', function() {
                $(this).closest('.feature-row').remove();
            });

            // Add Testimonial Row
            $('#add-testimonial').on('click', function() {
                var newRow = `
                <div class="testimonial-row row mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="testimonials[${testimonialIndex}][name]"
                            placeholder="{{ translate('Customer Name') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="testimonials[${testimonialIndex}][position]"
                            placeholder="{{ translate('Position / Company') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="testimonials[${testimonialIndex}][rating]"
                            placeholder="{{ translate('Rating (1-5)') }}">
                    </div>
                    <div class="col-md-3">
                        <textarea class="form-control" name="testimonials[${testimonialIndex}][comment]" rows="2"
                            placeholder="{{ translate('Testimonial Comment') }}"></textarea>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-icon btn-danger remove-testimonial">
                            <i class="las la-trash"></i>
                        </button>
                    </div>
                </div>
            `;
                $('#testimonials-container').append(newRow);
                testimonialIndex++;
            });

            // Remove Testimonial Row
            $(document).on('click', '.remove-testimonial', function() {
                $(this).closest('.testimonial-row').remove();
            });

            // Add FAQ Row
            $('#add-faq').on('click', function() {
                var newRow = `
                <div class="faq-row row mb-3">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="faq[${faqIndex}][question]"
                            placeholder="{{ translate('Question') }}">
                    </div>
                    <div class="col-md-6">
                        <textarea class="form-control" name="faq[${faqIndex}][answer]" rows="2"
                            placeholder="{{ translate('Answer') }}"></textarea>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-icon btn-danger remove-faq">
                            <i class="las la-trash"></i>
                        </button>
                    </div>
                </div>
            `;
                $('#faq-container').append(newRow);
                faqIndex++;
            });

            // Remove FAQ Row
            $(document).on('click', '.remove-faq', function() {
                $(this).closest('.faq-row').remove();
            });

            // Form validation
            $('#landingpage-form').on('submit', function(e) {
                var name = $('#name').val();
                if (!name) {
                    AIZ.plugins.notify('warning', '{{ translate('Please enter page name') }}');
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>

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

        .product-row .select2 {
            width: 100% !important;
        }
    </style>
@endsection
