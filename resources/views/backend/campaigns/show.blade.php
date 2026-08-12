@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Campaign Details') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('campaigns.index') }}" class="btn btn-secondary">
                    <i class="las la-arrow-left"></i> {{ translate('Back to Campaigns') }}
                </a>
                <a href="{{ route('campaigns.edit', $campaign->id) }}" class="btn btn-primary">
                    <i class="las la-edit"></i> {{ translate('Edit Campaign') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Campaign Information Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Campaign Information') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">{{ translate('Campaign Name') }}</th>
                            <td>{{ $campaign->name }}
                </div>
                </tr>
                <tr>
                    <th>{{ translate('Slug') }}</th>
                    <td>{{ $campaign->slug }}
            </div>
            </tr>
            <tr>
                <th>{{ translate('Description') }}</th>
                <td>{!! $campaign->description !!}
        </div>
        </tr>
        <tr>
            <th>{{ translate('Created At') }}</th>
            <td>{{ $campaign->created_at->format('d M Y, h:i A') }}
    </div>
    </tr>
    <tr>
        <th>{{ translate('Last Updated') }}</th>
        <td>{{ $campaign->updated_at->format('d M Y, h:i A') }}</div>
    </tr>
    </table>
    </div>
    </div>

    <!-- Campaign Schedule Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Campaign Schedule') }}</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="30%">{{ translate('Start Date') }}</th>
                    <td>{{ \Carbon\Carbon::parse($campaign->start_date)->format('d M Y') }}
        </div>
        </tr>
        <tr>
            <th>{{ translate('End Date') }}</th>
            <td>{{ \Carbon\Carbon::parse($campaign->end_date)->format('d M Y') }}
    </div>
    </tr>
    <tr>
        <th>{{ translate('Duration') }}</th>
        <td>
            @php
                $start = \Carbon\Carbon::parse($campaign->start_date);
                $end = \Carbon\Carbon::parse($campaign->end_date);
                $days = $start->diffInDays($end);
            @endphp
            {{ $days }} {{ translate('days') }}
            <br>
            <small class="text-muted">
                {{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}
            </small>
            </div>
    </tr>
    </table>
    </div>
    </div>

    <!-- Discount Information Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Discount Information') }}</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="30%">{{ translate('Discount Type') }}</th>
                    <td>
                        @if ($campaign->discount_type == 'percent')
                            <span class="badge badge-info">{{ translate('Percentage') }}</span>
                        @else
                            <span class="badge badge-info">{{ translate('Flat') }}</span>
                        @endif
        </div>
        </tr>
        <tr>
            <th>{{ translate('Discount Amount') }}</th>
            <td>
                @if ($campaign->discount_type == 'percent')
                    <span class="badge badge-primary">{{ $campaign->discount_amount }}% OFF</span>
                @else
                    <span class="badge badge-primary">৳{{ number_format($campaign->discount_amount, 2) }} OFF</span>
                @endif
    </div>
    </tr>
    </table>
    </div>
    </div>
    </div>

    <div class="col-md-4">
        <!-- Campaign Image Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Campaign Image') }}</h5>
            </div>
            <div class="card-body text-center">
                @if ($campaign->image)
                    <img src="{{ uploaded_asset($campaign->image) }}" alt="{{ $campaign->name }}"
                        class="img-fluid rounded" style="max-height: 200px; width: auto;">
                @else
                    <div class="bg-light p-5 rounded">
                        <i class="las la-image fs-60 text-muted"></i>
                        <p class="text-muted mb-0">{{ translate('No image uploaded') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Campaign Status Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Campaign Status') }}</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    @if ($campaign->status == 'active')
                        @if ($campaign->isRunning())
                            <span class="badge badge-success">{{ translate('Active & Running') }}</span>
                        @elseif($campaign->isUpcoming())
                            <span class="badge badge-info">{{ translate('Active & Upcoming') }}</span>
                        @elseif($campaign->hasEnded())
                            <span class="badge badge-secondary">{{ translate('Active & Ended') }}</span>
                        @endif
                    @else
                        <span class="badge badge-danger">{{ translate('Inactive') }}</span>
                    @endif

                    <div class="mt-3">
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" style="width: {{ $campaign->getProgressPercentage() }}%">
                            </div>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            {{ number_format($campaign->getProgressPercentage(), 1) }}% {{ translate('completed') }}
                        </small>
                    </div>

                    <div class="row mt-3">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <div class="small text-muted">{{ translate('Remaining') }}</div>
                                <div class="h5 mb-0">{{ $campaign->getRemainingDays() }} {{ translate('days') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <div class="small text-muted">{{ translate('Elapsed') }}</div>
                                <div class="h5 mb-0">{{ $campaign->getElapsedDays() }} {{ translate('days') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Quick Stats') }}</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border rounded p-3">
                            <div class="h2 mb-0 text-primary">{{ $campaign->products->count() }}</div>
                            <div class="text-muted small">{{ translate('Total Products') }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3">
                            <div class="h2 mb-0 text-success">
                                @if ($campaign->discount_type == 'percent')
                                    {{ $campaign->discount_amount }}%
                                @else
                                    ৳{{ number_format($campaign->discount_amount, 0) }}
                                @endif
                            </div>
                            <div class="text-muted small">{{ translate('Discount') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Products in Campaign Card -->
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Products in this Campaign') }}</h5>
            <span class="badge badge-primary ml-2">{{ $campaign->products->count() }} {{ translate('Products') }}</span>
        </div>
        <div class="card-body">
            @if ($campaign->products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered aiz-table">
                        <thead>
                            <tr>
                                <th width="5%">Sl</th>
                                <th width="10%">{{ translate('Image') }}</th>
                                <th width="25%">{{ translate('Product Name') }}</th>
                                <th width="15%">{{ translate('Regular Price') }}</th>
                                <th width="15%">{{ translate('Campaign Price') }}</th>
                                <th width="15%">{{ translate('Discount') }}</th>
                                <th width="15%">{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($campaign->products as $key => $product)
                                @php
                                    $regularPrice = $product->price->regular_price ?? 0;
                                    $discountAmount = $campaign->discount_amount ?? 0;
                                    $discountType = $campaign->discount_type ?? 'flat';

                                    if ($discountType == 'percent') {
                                        $campaignPrice = $regularPrice - ($regularPrice * $discountAmount) / 100;
                                    } else {
                                        $campaignPrice = max(0, $regularPrice - $discountAmount);
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}
                </div>
                <td>
                    @if ($product->thumbnail)
                        <img src="{{ uploaded_asset($product->thumbnail) }}" alt="{{ $product->name }}"
                            class="size-50px img-fit" style="border-radius: 8px;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px; border-radius: 8px;">
                            <i class="las la-image text-muted"></i>
                        </div>
                    @endif
        </div>
        <td>
            <strong>{{ $product->name }}</strong>
            <br>
            <small class="text-muted">SKU: {{ $product->inventory->sku ?? 'N/A' }}</small>
    </div>
    <td>
        ৳{{ number_format($regularPrice, 2) }}
        </div>
    <td>
        <strong class="text-primary">৳{{ number_format($campaignPrice, 2) }}</strong>
        </div>
    <td>
        <span class="badge badge-success">
            {{ translate('Save') }} ৳{{ number_format($regularPrice - $campaignPrice, 2) }}
        </span>
        </div>
    <td>
        <div class="btn-group" role="group">
            <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-icon btn-info"
                title="{{ translate('View Product') }}">
                <i class="las la-eye"></i>
            </a>
            <button type="button" class="btn btn-sm btn-icon btn-danger"
                onclick="removeProduct({{ $campaign->id }}, {{ $product->id }})"
                title="{{ translate('Remove from Campaign') }}">
                <i class="las la-trash"></i>
            </button>
        </div>
        </div>
        </tr>
        @endforeach
        </tbody>
        </table>
        </div>
    @else
        <div class="text-center py-5">
            <i class="las la-box-open fs-60 text-muted"></i>
            <h5 class="text-muted mt-3">{{ translate('No products in this campaign') }}</h5>
            <p class="text-muted">{{ translate('Add products to this campaign to see them here') }}</p>
            <a href="{{ route('campaigns.edit', $campaign->id) }}" class="btn btn-primary">
                <i class="las la-plus"></i> {{ translate('Add Products') }}
            </a>
        </div>
        @endif
        </div>
        </div>

        <!-- Add More Products Button -->
        @if ($campaign->status == 'active' && !$campaign->hasEnded())
            <div class="text-center mt-3">
                <a href="{{ route('campaigns.edit', $campaign->id) }}" class="btn btn-primary">
                    <i class="las la-plus"></i> {{ translate('Add More Products to Campaign') }}
                </a>
            </div>
        @endif
    @endsection

    @section('modal')
        <!-- Remove Product Modal -->
        <div id="remove-product-modal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">{{ translate('Remove Product') }}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="las la-exclamation-triangle text-warning" style="font-size: 48px;"></i>
                        <h4 class="mt-2">{{ translate('Are you sure?') }}</h4>
                        <p>{{ translate('This product will be removed from the campaign.') }}</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="button" class="btn btn-danger"
                            onclick="confirmRemoveProduct()">{{ translate('Remove') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('script')
        <script type="text/javascript">
            let removeCampaignId = null;
            let removeProductId = null;

            function removeProduct(campaignId, productId) {
                removeCampaignId = campaignId;
                removeProductId = productId;
                $('#remove-product-modal').modal('show');
            }

            function confirmRemoveProduct() {
                $('#remove-product-modal').modal('hide');

                // Build the URL manually with the parameters
                var url = "{{ url('admin/campaigns') }}/" + removeCampaignId + "/remove-product/" + removeProductId;

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "DELETE",
                    url: url,
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
        </script>
    @endsection

    <style>
        {
            font-size: 14px;
            padding: 8px 16px;
        }

        .size-50px {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }

        .fs-60 {
            font-size: 60px;
        }

        .progress {
            background-color: #e9ecef;
            border-radius: 10px;
        }

        .progress-bar {
            border-radius: 10px;
            transition: width 0.5s ease;
        }
    </style>
