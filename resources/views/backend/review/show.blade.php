@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0 h6">{{ translate('Review Details') }}</h5>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('reviews.index') }}" class="btn btn-secondary">
                    <i class="las la-arrow-left"></i> {{ translate('Back to Reviews') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Review Information Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Review Information') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">{{ translate('Product') }}</th>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if ($review->product && $review->product->thumbnail)
                                        <img src="{{ uploaded_asset($review->product->thumbnail) }}"
                                            alt="{{ $review->product->name }}" class="size-50px img-fit mr-2">
                                    @endif
                                    <div>
                                        <strong>{{ $review->product->name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">SKU:
                                            {{ $review->product->inventory->sku ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translate('Customer') }}</th>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm mr-2">
                                        @if ($review->user && $review->user->avatar)
                                            <img src="{{ uploaded_asset($review->user->avatar) }}" class="rounded-circle">
                                        @else
                                            <img src="{{ asset('assets/img/avatar-placeholder.png') }}"
                                                class="rounded-circle">
                                        @endif
                                    </div>
                                    <div>
                                        <strong>{{ $review->user->name ?? 'Guest User' }}</strong><br>
                                        <small class="text-muted">{{ $review->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translate('Rating') }}</th>
                            <td>
                                <div class="rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            <i class="las la-star text-warning fs-20"></i>
                                        @else
                                            <i class="lar la-star text-muted fs-20"></i>
                                        @endif
                                    @endfor
                                    <span class="ml-2 font-weight-bold fs-18">{{ $review->rating }}/5</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translate('Comment') }}</th>
                            <td>
                                <div class="p-3 bg-light rounded">
                                    {{ $review->comment }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translate('Review Date') }}</th>
                            <td>{{ \Carbon\Carbon::parse($review->created_at)->format('d M Y, h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Last Updated') }}</th>
                            <td>{{ \Carbon\Carbon::parse($review->updated_at)->format('d M Y, h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Status Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Status') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>{{ translate('Approval Status') }}</th>
                            <td>
                                @if ($review->status == 1)
                                    <span class="badge badge-success">{{ translate('Approved') }}</span>
                                @else
                                    <span class="badge badge-warning">{{ translate('Pending') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translate('Read Status') }}</th>
                            <td>
                                @if ($review->is_read == 1)
                                    <span class="badge badge-info">{{ translate('Read') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ translate('Unread') }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        @if ($review->status == 0)
                            <button type="button" class="btn btn-success btn-block"
                                onclick="updateReviewStatus({{ $review->id }}, 1)">
                                <i class="las la-check-circle"></i> {{ translate('Approve Review') }}
                            </button>
                        @else
                            <button type="button" class="btn btn-secondary btn-block"
                                onclick="updateReviewStatus({{ $review->id }}, 0)">
                                <i class="las la-times-circle"></i> {{ translate('Disapprove Review') }}
                            </button>
                        @endif

                        <button type="button" class="btn btn-danger btn-block mt-2"
                            onclick="deleteReview({{ $review->id }})">
                            <i class="las la-trash"></i> {{ translate('Delete Review') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Info Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Product Information') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>{{ translate('Product Name') }}</th>
                            <td>{{ $review->product->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Category') }}</th>
                            <td>{{ $review->product->category->category_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Price') }}</th>
                            <td>{{ number_format($review->product->price->regular_price ?? 0, 2) }} BDT</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Total Reviews') }}</th>
                            <td>{{ $review->product->reviews->count() ?? 0 }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Avg Rating') }}</th>
                            <td>{{ number_format($review->product->reviews->avg('rating') ?? 0, 1) }}/5</td>
                        </tr>
                    </table>
                    <a href="{{ route('products.show', $review->product_id) }}" class="btn btn-info btn-block mt-2">
                        <i class="las la-eye"></i> {{ translate('View Product') }}
                    </a>
                </div>
            </div>

            <!-- Customer Info Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Customer Information') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>{{ translate('Name') }}</th>
                            <td>{{ $review->user->name ?? 'Guest User' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Email') }}</th>
                            <td>{{ $review->user->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Phone') }}</th>
                            <td>{{ $review->user->phone ?? 'N/A' }}</td>
                        </tr>
                    </table>
                    <a href="" class="btn btn-info btn-block mt-2">
                        <i class="las la-user"></i> {{ translate('View Customer') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function updateReviewStatus(reviewId, status) {
            var statusText = status == 1 ? 'approve' : 'disapprove';

            if (confirm('{{ translate('Are you sure you want to') }} ' + statusText +
                    ' {{ translate('this review?') }}')) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('reviews.update-status') }}",
                    data: {
                        id: reviewId,
                        status: status
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
        }

        function deleteReview(reviewId) {
            if (confirm('{{ translate('Are you sure you want to delete this review?') }}')) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "DELETE",
                    url: "{{ route('reviews.destroy', '') }}/" + reviewId,
                    success: function(response) {
                        if (response.success) {
                            AIZ.plugins.notify('success', response.message);
                            setTimeout(function() {
                                window.location.href = "{{ route('reviews.index') }}";
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
        }
    </script>
@endsection
