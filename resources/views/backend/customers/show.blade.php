@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Customer Details') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    <i class="las la-arrow-left"></i> {{ translate('Back to Customers') }}
                </a>
                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary">
                    <i class="las la-edit"></i> {{ translate('Edit Customer') }}
                </a>
            </div>
        </div>
    </div>

    @php
        // Compute totals from the loaded orders collection
        $totalOrders = $customer->orders->count();
        $totalSpent = $customer->orders->sum('grand_total');
        // Get recent orders (latest 10)
        $recentOrders = $customer->orders->sortByDesc('created_at')->take(10);
    @endphp

    <div class="row">
        <div class="col-md-4">
            <!-- Profile Card -->
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar avatar-xl mb-3">
                        @if ($customer->user && $customer->user->avatar)
                            <img src="{{ uploaded_asset($customer->user->avatar) }}" class="rounded-circle"
                                style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <img src="{{ asset('default/avatar.jpg') }}" class="rounded-circle"
                                style="width: 120px; height: 120px; object-fit: cover;">
                        @endif
                    </div>
                    <h4 class="mb-1">{{ $customer->user->name ?? 'N/A' }}</h4>
                    <p class="text-muted mb-2">ID: #{{ $customer->id }}</p>

                    @if ($customer->banned)
                        <span class="badge badge-danger">{{ translate('Banned') }}</span>
                    @else
                        <span class="badge badge-success">{{ translate('Active') }}</span>
                    @endif

                    <div class="mt-3">
                        <div class="row">
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <div class="text-muted small">{{ translate('Total Orders') }}</div>
                                    <div class="h5 mb-0">{{ $totalOrders }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <div class="text-muted small">{{ translate('Total Spent') }}</div>
                                    <div class="h5 mb-0">৳{{ number_format($totalSpent, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Balance Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Account Balance') }}</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="h1 font-weight-bold {{ $customer->balance > 0 ? 'text-success' : 'text-muted' }}">
                            ৳{{ number_format($customer->balance, 2) }}
                        </div>
                        @if ($customer->balance > 0)
                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                data-target="#adjustBalanceModal">
                                <i class="las la-wallet"></i> {{ translate('Adjust Balance') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Quick Actions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="las la-shopping-cart"></i> {{ translate('View Orders') }}
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="las la-wallet"></i> {{ translate('Transaction History') }}
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="las la-envelope"></i> {{ translate('Send Message') }}
                        </a>
                        @if ($customer->banned)
                            <button type="button" class="list-group-item list-group-item-action text-success"
                                data-toggle="modal" data-target="#activate-modal"
                                onclick="setCustomerData({{ $customer->id }})">
                                <i class="las la-user-check"></i> {{ translate('Activate Customer') }}
                            </button>
                        @else
                            <button type="button" class="list-group-item list-group-item-action text-warning"
                                data-toggle="modal" data-target="#ban-modal"
                                onclick="setCustomerData({{ $customer->id }})">
                                <i class="las la-user-slash"></i> {{ translate('Ban Customer') }}
                            </button>
                        @endif
                        <button type="button" class="list-group-item list-group-item-action text-danger"
                            data-toggle="modal" data-target="#delete-modal"
                            onclick="setDeleteForm('{{ route('customers.destroy', $customer->id) }}')">
                            <i class="las la-trash"></i> {{ translate('Delete Customer') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Personal Information Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Personal Information') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">{{ translate('Full Name') }}</th>
                            <td>{{ $customer->user->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Email Address') }}</th>
                            <td>{{ $customer->user->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Phone Number') }}</th>
                            <td>{{ $customer->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Account Status') }}</th>
                            <td>
                                @if ($customer->banned)
                                    <span class="badge badge-danger">{{ translate('Banned') }}</span>
                                @else
                                    <span class="badge badge-success">{{ translate('Active') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translate('Member Since') }}</th>
                            <td>{{ $customer->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Last Updated') }}</th>
                            <td>{{ $customer->updated_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Address Information Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Address Information') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">{{ translate('Address') }}</th>
                            <td>{{ $customer->address ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Country') }}</th>
                            <td>{{ $customer->country ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('City') }}</th>
                            <td>{{ $customer->city ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('Postal Code') }}</th>
                            <td>{{ $customer->postal_code ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Recent Orders Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Recent Orders') }}</h5>
                    <a href="#" class="btn btn-sm btn-primary">{{ translate('View All Orders') }}</a>
                </div>
                <div class="card-body">
                    @if ($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ translate('Order #') }}</th>
                                        <th>{{ translate('Date') }}</th>
                                        <th>{{ translate('Amount') }}</th>
                                        <th>{{ translate('Status') }}</th>
                                        <th>{{ translate('Payment') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentOrders as $order)
                                        <tr>
                                            <td>#{{ $order->code }}</td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                            <td>৳{{ number_format($order->grand_total, 2) }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-info">{{ ucfirst($order->delivery_status) }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="las la-shopping-cart fs-40 text-muted"></i>
                            <p class="text-muted mt-2">{{ translate('No orders found') }}</p>
                        </div>
                    @endif
                </div>
            </div>


            <!-- Top 5 Most Ordered Products Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Most Ordered Products') }}</h5>
                </div>
                <div class="card-body">
                    @if ($topProducts->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach ($topProducts as $index => $item)
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge badge-secondary mr-2">{{ $index + 1 }}</span>
                                        <strong>{{ $item['product']->name ?? 'N/A' }}</strong>
                                        <small class="text-muted d-block">
                                            {{ translate('Ordered') }} {{ $item['count'] }}
                                            {{ Str::plural('time', $item['count']) }}
                                            @if ($item['total_quantity'] > 0)
                                                , {{ translate('Total Qty') }}: {{ $item['total_quantity'] }}
                                            @endif
                                        </small>
                                    </div>
                                    @if ($item['product'] && $item['product']->thumbnail_img)
                                        <img src="{{ uploaded_asset($item['product']->thumbnail_img) }}"
                                            alt="{{ $item['product']->name }}"
                                            style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <div class="avatar avatar-sm bg-secondary text-white d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px; border-radius: 8px;">
                                            <i class="las la-image"></i>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="las la-box-open fs-40 text-muted"></i>
                            <p class="text-muted mt-2">{{ translate('No orders yet') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- 
                Uncomment and adjust when you add a 'reviews' relationship
                <!-- Recent Reviews Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Recent Reviews') }}</h5>
                        <a href="#" class="btn btn-sm btn-primary">{{ translate('View All Reviews') }}</a>
                    </div>
                    <div class="card-body">
                        @if (isset($customer->reviews) && $customer->reviews->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach ($customer->reviews as $review)
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="font-weight-bold">{{ $review->product->name ?? 'N/A' }}</div>
                                                <div class="rating rating-sm">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $review->rating)
                                                            <i class="las la-star text-warning"></i>
                                                        @else
                                                            <i class="lar la-star text-muted"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <p class="mb-0 small">{{ Str::limit($review->comment, 100) }}</p>
                                            </div>
                                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="las la-comment-slash fs-40 text-muted"></i>
                                <p class="text-muted mt-2">{{ translate('No reviews found') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            --}}
        </div>
    </div>

    <!-- Ban Modal -->
    <div id="ban-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-warning">{{ translate('Ban Customer') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center px-4 pb-4">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 80px; height: 80px; border-radius: 50%; background: rgba(255, 193, 7, 0.1);">
                        <i class="las la-user-slash text-warning" style="font-size: 36px;"></i>
                    </div>
                    <h4 class="fw-bold mb-2">{{ translate('Ban this customer?') }}</h4>
                    <p class="text-muted mb-4">
                        {{ translate('This customer will be banned and cannot login or make purchases.') }}</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-light rounded-pill px-4 py-2" data-dismiss="modal">
                            {{ translate('Cancel') }}
                        </button>
                        <button type="button" class="btn btn-warning rounded-pill px-4 py-2" onclick="confirmBan()">
                            <i class="las la-user-slash me-1"></i> {{ translate('Ban Customer') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activate Modal -->
    <div id="activate-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-success">{{ translate('Activate Customer') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center px-4 pb-4">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 80px; height: 80px; border-radius: 50%; background: rgba(40, 167, 69, 0.1);">
                        <i class="las la-user-check text-success" style="font-size: 36px;"></i>
                    </div>
                    <h4 class="fw-bold mb-2">{{ translate('Activate this customer?') }}</h4>
                    <p class="text-muted mb-4">{{ translate('This customer will be activated and can login again.') }}</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-light rounded-pill px-4 py-2" data-dismiss="modal">
                            {{ translate('Cancel') }}
                        </button>
                        <button type="button" class="btn btn-success rounded-pill px-4 py-2"
                            onclick="confirmActivate()">
                            <i class="las la-user-check me-1"></i> {{ translate('Activate Customer') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Adjust Balance Modal -->
    <div class="modal fade" id="adjustBalanceModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Adjust Customer Balance') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('customers.update-balance', $customer->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{ translate('Current Balance') }}</label>
                            <input type="text" class="form-control"
                                value="৳{{ number_format($customer->balance, 2) }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Adjustment Type') }}</label>
                            <select class="form-control" name="adjustment_type" id="adjustmentType" required>
                                <option value="add">{{ translate('Add to Balance') }}</option>
                                <option value="subtract">{{ translate('Subtract from Balance') }}</option>
                                <option value="set">{{ translate('Set Balance') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Amount') }}</label>
                            <input type="number" step="0.01" class="form-control" name="amount" placeholder="0.00"
                                required>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Reason / Note') }}</label>
                            <textarea class="form-control" name="note" rows="3"
                                placeholder="{{ translate('Optional note for this adjustment') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ translate('Apply Adjustment') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">{{ translate('Delete Customer') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center px-4 pb-4">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 80px; height: 80px; border-radius: 50%; background: rgba(220, 53, 69, 0.1);">
                        <i class="las la-trash text-danger" style="font-size: 36px;"></i>
                    </div>
                    <h4 class="fw-bold mb-2">{{ translate('Are you sure?') }}</h4>
                    <p class="text-muted mb-4">
                        {{ translate('This customer will be permanently deleted. All associated data will be removed.') }}
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-light rounded-pill px-4 py-2" data-dismiss="modal">
                            {{ translate('Cancel') }}
                        </button>
                        <form id="delete-form" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger rounded-pill px-4 py-2">
                                <i class="las la-trash me-1"></i> {{ translate('Delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        let currentCustomerId = null;

        function setDeleteForm(url) {
            $('#delete-form').attr('action', url);
        }

        function setCustomerData(customerId) {
            currentCustomerId = customerId;
        }

        function confirmBan() {
            $('#ban-modal').modal('hide');
            performAction(currentCustomerId, 'ban');
        }

        function confirmActivate() {
            $('#activate-modal').modal('hide');
            performAction(currentCustomerId, 'activate');
        }

        function performAction(customerId, action) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('customers.toggle-ban', '') }}/" + customerId,
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

        // Update adjustment type placeholder
        $('#adjustmentType').on('change', function() {
            var type = $(this).val();
            var placeholder = '';
            if (type == 'add') {
                placeholder = '{{ translate('Amount to add') }}';
            } else if (type == 'subtract') {
                placeholder = '{{ translate('Amount to subtract') }}';
            } else {
                placeholder = '{{ translate('New balance amount') }}';
            }
            $('input[name="amount"]').attr('placeholder', placeholder);
        });
    </script>
@endsection
