@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0 h6">{{ translate('Product Details') }}</h5>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
                    <i class="las la-edit"></i> {{ translate('Edit Product') }}
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="las la-arrow-left"></i> {{ translate('Back to List') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Product Information Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Product Information') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">{{ translate('Product Name') }}</th>
                            <td>{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Slug') }}</th>
                            <td>{{ $product->slug }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Category') }}</th>
                            <td>{{ $product->category->category_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Sub Category') }}</th>
                            <td>{{ $product->subcategory->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Brand') }}</th>
                            <td>{{ $product->brand->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Unit') }}</th>
                            <td>{{ $product->unit ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Barcode') }}</th>
                            <td>{{ $product->barcode ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Badge Name') }}</th>
                            <td>
                                @if ($product->badge_name)
                                    <span class="badge badge-info">{{ $product->badge_name }}</span>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translate('Batch No') }}</th>
                            <td>{{ $product->batch_no ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Tags') }}</th>
                            <td>
                                @if ($product->tags)
                                    @php
                                        // Decode tags properly
                                        $tagsData = json_decode($product->tags, true);
                                        $tags = [];
                                        if (is_array($tagsData)) {
                                            foreach ($tagsData as $tag) {
                                                if (is_array($tag) && isset($tag['value'])) {
                                                    $tags[] = $tag['value'];
                                                } elseif (is_string($tag)) {
                                                    $tags[] = $tag;
                                                }
                                            }
                                        }
                                    @endphp
                                    @foreach ($tags as $tag)
                                        <span class="badge badge-secondary">{{ $tag }}</span>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translate('Short Description') }}</th>
                            <td>{{ $product->short_description ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Full Description') }}</th>
                            <td>{!! $product->description !!}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Video Link') }}</th>
                            <td>
                                @if ($product->video_link)
                                    <a href="{{ $product->video_link }}" target="_blank">{{ $product->video_link }}</a>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Product Images Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Product Images') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="font-weight-bold">{{ translate('Thumbnail Image') }}</label>
                            <div class="mt-2">
                                <img src="{{ uploaded_asset($product->thumbnail) }}" alt="{{ $product->name }}"
                                    class="img-fluid" style="max-width: 150px;">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <label class="font-weight-bold">{{ translate('Gallery Images') }}</label>
                            <div class="row mt-2">
                                @if ($product->photos)
                                    @php $photos = json_decode($product->photos, true) @endphp
                                    @if (is_array($photos))
                                        @foreach ($photos as $photo)
                                            <div class="col-md-3 mb-2">
                                                <img src="{{ uploaded_asset($photo) }}" alt="Gallery" class="img-fluid"
                                                    style="max-width: 100px;">
                                            </div>
                                        @endforeach
                                    @endif
                                @else
                                    <div class="col-12">{{ translate('No gallery images') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Price Information Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Price Information') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">{{ translate('Purchase Price') }}</th>
                            <td>{{ number_format($product->price->purchase_price ?? 0, 2) }} BDT</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Regular Price') }}</th>
                            <td>{{ number_format($product->price->regular_price ?? 0, 2) }} BDT</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Sale Price') }}</th>
                            <td>
                                @if ($product->price && $product->price->sale_price)
                                    {{ number_format($product->price->sale_price, 2) }} BDT
                                    @if ($product->price->sale_price < $product->price->regular_price)
                                        <span class="badge badge-success ml-2">{{ translate('On Sale') }}</span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translate('Discount') }}</th>
                            <td>
                                @if ($product->price && $product->price->discount > 0)
                                    {{ number_format($product->price->discount, 2) }}
                                    ({{ ucfirst($product->price->discount_type) }})
                                @else
                                    {{ translate('No discount') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translate('Discount Period') }}</th>
                            <td>
                                @if ($product->price && $product->price->discount_start && $product->price->discount_end)
                                    {{ \Carbon\Carbon::parse($product->price->discount_start)->format('d M Y H:i') }}
                                    {{ translate('to') }}
                                    {{ \Carbon\Carbon::parse($product->price->discount_end)->format('d M Y H:i') }}
                                @else
                                    {{ translate('No date range set') }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Inventory Information Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Inventory Information') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">{{ translate('SKU') }}</th>
                            <td>{{ $product->inventory->sku ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Stock Quantity') }}</th>
                            <td>
                                <span
                                    class="badge {{ ($product->inventory->stock ?? 0) > 0 ? 'badge-success' : 'badge-danger' }}">
                                    {{ $product->inventory->stock ?? 0 }} {{ translate('units') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translate('Low Stock Threshold') }}</th>
                            <td>{{ $product->inventory->low_stock_qty ?? 1 }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Track Inventory') }}</th>
                            <td>
                                @if ($product->inventory && $product->inventory->track_inventory)
                                    <span class="badge badge-success">{{ translate('Yes') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ translate('No') }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Shipping Information Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Shipping Information') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">{{ translate('Shipping Type') }}</th>
                            <td>{{ ucfirst(str_replace('_', ' ', $product->shipping->shipping_type ?? 'flat_rate')) }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Shipping Cost') }}</th>
                            <td>{{ number_format($product->shipping->shipping_cost ?? 0, 2) }} BDT</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Weight') }}</th>
                            <td>{{ $product->shipping->weight ?? 0 }} kg</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Dimensions') }}</th>
                            <td>
                                L: {{ $product->shipping->length ?? 0 }} cm,
                                W: {{ $product->shipping->width ?? 0 }} cm,
                                H: {{ $product->shipping->height ?? 0 }} cm
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
                    <h5 class="mb-0 h6">{{ translate('Product Status') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>{{ translate('Status') }}</th>
                            <td>
                                @if ($product->status == 1)
                                    <span class="badge badge-success">{{ translate('Active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ translate('Inactive') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translate('Published') }}</th>
                            <td>
                                @if ($product->is_published)
                                    <span class="badge badge-success">{{ translate('Published') }}</span>
                                @else
                                    <span class="badge badge-warning">{{ translate('Draft') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translate('Product Type') }}</th>
                            <td>
                                @if ($product->is_variant)
                                    <span class="badge badge-info">{{ translate('Variable Product') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ translate('Simple Product') }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Product Badges Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Product Badges') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <strong>{{ translate('Featured') }}</strong>
                            <br>
                            @if ($product->is_featured)
                                <span class="badge badge-success">{{ translate('Yes') }}</span>
                            @else
                                <span class="badge badge-secondary">{{ translate('No') }}</span>
                            @endif
                        </div>
                        <div class="col-6">
                            <strong>{{ translate('Best Selling') }}</strong>
                            <br>
                            @if ($product->best_selling)
                                <span class="badge badge-success">{{ translate('Yes') }}</span>
                            @else
                                <span class="badge badge-secondary">{{ translate('No') }}</span>
                            @endif
                        </div>
                        <div class="col-6 mt-2">
                            <strong>{{ translate('New Arrival') }}</strong>
                            <br>
                            @if ($product->is_new_arrival)
                                <span class="badge badge-success">{{ translate('Yes') }}</span>
                            @else
                                <span class="badge badge-secondary">{{ translate('No') }}</span>
                            @endif
                        </div>
                        <div class="col-6 mt-2">
                            <strong>{{ translate("Today's Deal") }}</strong>
                            <br>
                            @if ($product->todays_deal > 0)
                                <span class="badge badge-success">{{ number_format($product->todays_deal, 2) }} BDT</span>
                            @else
                                <span class="badge badge-secondary">{{ translate('No') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Variants Card -->
            @if ($product->is_variant && $product->variants && count($product->variants) > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Variants') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ translate('SKU') }}</th>
                                        <th>{{ translate('Price') }}</th>
                                        <th>{{ translate('Quantity') }}</th>
                                        <th>{{ translate('Discount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product->variants as $variant)
                                        <tr>
                                            <td>{{ $variant->sku }}</td>
                                            <td>{{ number_format($variant->price, 2) }} BDT</td>
                                            <td>{{ $variant->quantity }} {{ translate('units') }}</td>
                                            <td>
                                                @if ($variant->discount > 0)
                                                    {{ number_format($variant->discount, 2) }}
                                                    ({{ ucfirst($variant->discount_type) }})
                                                @else
                                                    N/A
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

            <!-- SEO Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('SEO Information') }}</h5>
                </div>
                <div class="card-body">
                    @if ($product->seo && ($product->seo->meta_title || $product->seo->meta_description))
                        <table class="table table-bordered">
                            <tr>
                                <th>{{ translate('Meta Title') }}</th>
                                <td>{{ $product->seo->meta_title ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ translate('Meta Description') }}</th>
                                <td>{{ $product->seo->meta_description ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ translate('Meta Image') }}</th>
                                <td>
                                    @if ($product->seo && $product->seo->meta_image)
                                        <img src="{{ uploaded_asset($product->seo->meta_image) }}" alt="Meta Image"
                                            style="max-width: 100px;">
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        </table>
                    @else
                        <p class="text-muted">{{ translate('No SEO information available') }}</p>
                    @endif
                </div>
            </div>

            <!-- Meta Information Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Meta Information') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>{{ translate('Total Sales') }}</th>
                            <td>{{ $product->num_of_sale ?? 0 }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Position') }}</th>
                            <td>{{ $product->position ?? 0 }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Created At') }}</th>
                            <td>{{ \Carbon\Carbon::parse($product->created_at)->format('d M Y, h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Last Updated') }}</th>
                            <td>{{ \Carbon\Carbon::parse($product->updated_at)->format('d M Y, h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>


            <!-- Recent Reviews Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Recent Product Reviews') }}</h5>
                    <a href="{{ route('reviews.index', ['product_id' => $product->id]) }}"
                        class="btn btn-sm btn-primary">
                        {{ translate('View All') }}
                    </a>
                </div>
                <div class="card-body">
                    @if ($product->reviews && $product->reviews->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach ($product->reviews->take(5) as $review)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm mr-2">
                                                @if ($review->user && $review->user->avatar)
                                                    <img src="{{ uploaded_asset($review->user->avatar) }}"
                                                        class="rounded-circle">
                                                @else
                                                    <img src="{{ asset('default/avatar.jpg') }}" class="rounded-circle">
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">
                                                    {{ $review->user->name ?? 'Guest User' }}
                                                </div>
                                                <div class="rating rating-sm">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $review->rating)
                                                            <i class="las la-star text-warning"></i>
                                                        @else
                                                            <i class="lar la-star text-muted"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div class="mt-2">
                                        <p class="mb-1">
                                            {{ Str::limit($review->comment, 100) }}
                                        </p>
                                        <div class="mt-1">
                                            @if ($review->status == 1)
                                                <span class="badge badge-success">{{ translate('Approved') }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ translate('Pending') }}</span>
                                            @endif
                                            @if ($review->is_read == 1)
                                                <span class="badge badge-info">{{ translate('Read') }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ translate('Unread') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Review Summary Stats -->
                        <div class="mt-3 pt-3 border-top">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="h5 mb-0">{{ number_format($product->reviews->avg('rating'), 1) }}</div>
                                    <div class="text-muted small">{{ translate('Avg Rating') }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="h5 mb-0">{{ $product->reviews->count() }}</div>
                                    <div class="text-muted small">{{ translate('Total Reviews') }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="h5 mb-0">{{ $product->reviews->where('status', 0)->count() }}</div>
                                    <div class="text-muted small">{{ translate('Pending') }}</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="las la-comment-slash fs-40 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">{{ translate('No reviews yet for this product') }}</p>
                        </div>
                    @endif
                </div>
            </div>



        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
