@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Payment Systems') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('paymentsystem.create') }}" class="btn btn-primary">
                    {{ translate('Add New Payment System') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('All Payment Systems') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ translate('Image') }}</th>
                                    <th>{{ translate('Title') }}</th>
                                    <th>{{ translate('Type') }}</th>
                                    <th>{{ translate('Default') }}</th>
                                    <th>{{ translate('Date') }}</th>
                                    <th class="text-right">{{ translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($paymentSystems as $key => $paymentSystem)
                                    <tr>
                                        <td>{{ $paymentSystems->firstItem() + $key }}</td>
                                        <td>
                                            @if ($paymentSystem->image)
                                                <img src="{{ uploaded_asset($paymentSystem->image) }}"
                                                    style="height:50px; width:50px; object-fit:cover; border-radius:6px;">
                                            @else
                                                <span class="badge badge-secondary">{{ translate('No Image') }}</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ $paymentSystem->title }}</strong></td>
                                        <td>{{ $paymentSystem->type }}</td>
                                        <td>
                                            @if ($paymentSystem->is_default)
                                                <span class="badge badge-success">{{ translate('Yes') }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ translate('No') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $paymentSystem->created_at->format('d M Y') }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('paymentsystem.edit', $paymentSystem->id) }}"
                                                class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                                title="{{ translate('Edit') }}">
                                                <i class="las la-edit"></i>
                                            </a>
                                            <button type="button"
                                                class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                                data-href="{{ route('paymentsystem.destroy', $paymentSystem->id) }}"
                                                title="{{ translate('Delete') }}">
                                                <i class="las la-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            {{ translate('No payment systems found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="aiz-pagination mt-3">
                        {{ $paymentSystems->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script>
        $(document).on('click', '.confirm-delete', function(e) {
            e.preventDefault();
            $('#delete-modal').modal('show');
            $('#delete-form').attr('action', $(this).data('href'));
        });
    </script>
@endsection
