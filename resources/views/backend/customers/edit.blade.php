@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Edit Customer') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    <i class="las la-arrow-left"></i> {{ translate('Back to Customers') }}
                </a>
                <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-info">
                    <i class="las la-eye"></i> {{ translate('View Customer') }}
                </a>
            </div>
        </div>
    </div>

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
                            <img src="{{ asset('assets/img/avatar-placeholder.png') }}" class="rounded-circle"
                                style="width: 120px; height: 120px; object-fit: cover;">
                        @endif
                    </div>
                    <h4 class="mb-1">{{ $customer->full_name }}</h4>
                    <p class="text-muted mb-2">ID: #{{ $customer->id }}</p>

                    @if ($customer->banned)
                        <span class="badge badge-danger badge-lg">{{ translate('Banned') }}</span>
                    @else
                        <span class="badge badge-success badge-lg">{{ translate('Active') }}</span>
                    @endif

                    <div class="mt-3">
                        <div class="row">
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <div class="text-muted small">{{ translate('Total Orders') }}</div>
                                    <div class="h5 mb-0">{{ $totalOrders ?? 0 }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <div class="text-muted small">{{ translate('Total Spent') }}</div>
                                    <div class="h5 mb-0">৳{{ number_format($totalSpent ?? 0, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Balance Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Account Balance') }}</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="h1 font-weight-bold {{ $customer->balance > 0 ? 'text-success' : 'text-muted' }}">
                            ৳{{ number_format($customer->balance, 2) }}
                        </div>
                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                            data-target="#adjustBalanceModal">
                            <i class="las la-wallet"></i> {{ translate('Adjust Balance') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Status Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Account Status') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="d-block">{{ translate('Customer Status') }}</label>
                        <div class="btn-group w-100" role="group">
                            <button type="button"
                                class="btn {{ !$customer->banned ? 'btn-success active' : 'btn-outline-success' }}"
                                onclick="toggleBan({{ $customer->id }}, false)">
                                <i class="las la-user-check"></i> {{ translate('Active') }}
                            </button>
                            <button type="button"
                                class="btn {{ $customer->banned ? 'btn-danger active' : 'btn-outline-danger' }}"
                                onclick="toggleBan({{ $customer->id }}, true)">
                                <i class="las la-user-slash"></i> {{ translate('Banned') }}
                            </button>
                        </div>
                    </div>
                    <div class="alert alert-info mt-3">
                        <i class="las la-info-circle"></i>
                        {{ translate('Banned customers cannot login or make purchases.') }}
                    </div>
                </div>
            </div>

            <!-- Delete Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6 text-danger">{{ translate('Danger Zone') }}</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        {{ translate('Once deleted, all customer data including orders and reviews will be permanently removed.') }}
                    </p>
                    <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#delete-modal"
                        onclick="setDeleteForm('{{ route('customers.destroy', $customer->id) }}')">
                        <i class="las la-trash"></i> {{ translate('Delete Customer') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Edit Form -->
            <form action="{{ route('customers.update', $customer->id) }}" method="POST" id="customer-form">
                @csrf
                @method('PUT')

                <!-- Personal Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Personal Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Full Name') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name"
                                    value="{{ $customer->user->name ?? '' }}" placeholder="{{ translate('Full Name') }}"
                                    required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Email Address') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="email" class="form-control" name="email"
                                    value="{{ $customer->user->email ?? '' }}"
                                    placeholder="{{ translate('Email Address') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Phone Number') }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="phone"
                                    value="{{ $customer->phone ?? '' }}" placeholder="{{ translate('Phone Number') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Password') }}</label>
                            <div class="col-md-9">
                                <input type="password" class="form-control" name="password"
                                    placeholder="{{ translate('Leave empty to keep current password') }}">
                                <small
                                    class="text-muted">{{ translate('Only fill if you want to change password') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Address Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Address') }}</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="address" rows="2" placeholder="{{ translate('Street Address') }}">{{ $customer->address ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Country') }}</label>
                            <div class="col-md-9">
                                <select class="form-control aiz-selectpicker" name="country" data-live-search="true">
                                    <option value="">{{ translate('Select Country') }}</option>
                                    <option value="Bangladesh"
                                        {{ ($customer->country ?? '') == 'Bangladesh' ? 'selected' : '' }}>Bangladesh
                                    </option>
                                    <option value="India" {{ ($customer->country ?? '') == 'India' ? 'selected' : '' }}>
                                        India</option>
                                    <option value="Pakistan"
                                        {{ ($customer->country ?? '') == 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                                    <option value="USA" {{ ($customer->country ?? '') == 'USA' ? 'selected' : '' }}>USA
                                    </option>
                                    <option value="UK" {{ ($customer->country ?? '') == 'UK' ? 'selected' : '' }}>UK
                                    </option>
                                    <option value="Canada" {{ ($customer->country ?? '') == 'Canada' ? 'selected' : '' }}>
                                        Canada</option>
                                    <option value="Australia"
                                        {{ ($customer->country ?? '') == 'Australia' ? 'selected' : '' }}>Australia
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('City') }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="city"
                                    value="{{ $customer->city ?? '' }}" placeholder="{{ translate('City') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Postal Code') }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="postal_code"
                                    value="{{ $customer->postal_code ?? '' }}"
                                    placeholder="{{ translate('Postal Code') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Balance Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Balance Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Current Balance') }}</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">BDT</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" name="balance"
                                        value="{{ $customer->balance }}" placeholder="{{ translate('Balance') }}">
                                </div>
                                <small class="text-muted">{{ translate('Set customer wallet balance') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="card">
                    <div class="card-body text-right">
                        <button type="submit" name="button" value="update" class="btn btn-primary">
                            <i class="las la-save"></i> {{ translate('Update Customer') }}
                        </button>
                        <button type="button" class="btn btn-secondary"
                            onclick="window.location.href='{{ route('customers.index') }}'">
                            <i class="las la-times"></i> {{ translate('Cancel') }}
                        </button>
                    </div>
                </div>
            </form>
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
        $(document).ready(function() {
            // Form validation
            $('#customer-form').on('submit', function(e) {
                var name = $('input[name="name"]').val();
                var email = $('input[name="email"]').val();

                if (name.trim() == '') {
                    AIZ.plugins.notify('danger', '{{ translate('Name is required') }}');
                    e.preventDefault();
                    return false;
                }

                if (email.trim() == '') {
                    AIZ.plugins.notify('danger', '{{ translate('Email is required') }}');
                    e.preventDefault();
                    return false;
                }

                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    AIZ.plugins.notify('danger', '{{ translate('Please enter a valid email address') }}');
                    e.preventDefault();
                    return false;
                }
            });
        });

        function setDeleteForm(url) {
            $('#delete-form').attr('action', url);
        }

        function toggleBan(customerId, ban) {
            var action = ban ? 'ban' : 'activate';
            var button = $(event.target).closest('button');

            if (confirm('{{ translate('Are you sure you want to') }} ' + action + ' {{ translate('this customer?') }}')) {
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
