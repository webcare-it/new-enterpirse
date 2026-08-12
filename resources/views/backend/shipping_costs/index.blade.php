@extends('backend.layouts.app')

@section('content')
    @include('backend.section._tabs')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('All Shipping Costs') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('shipping_costs.create') }}" class="btn btn-primary">
                    <span>{{ translate('Add New Shipping Cost') }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-md-0 h6">{{ translate('Shipping Costs') }}</h5>
                    </div>
                    <div class="col-md-4">
                        <form class="" id="sort_shipping_costs" action="" method="GET">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="search"
                                    name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                                    placeholder="{{ translate('Type name & Enter') }}">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Name') }}</th>
                                <th>{{ translate('Amount') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Created At') }}</th>
                                <th class="text-right">{{ translate('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shipping_costs as $key => $cost)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $cost->name }}</td>
                                    <td>{{ number_format($cost->amount, 2) }}</td>
                                    <td>
                                        @if ($cost->status == 1)
                                            <span class="badge badge-success">{{ translate('Active') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ translate('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $cost->created_at->format('d M Y') }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('shipping_costs.edit', $cost->id) }}"
                                            class="btn btn-icon btn-soft-primary btn-sm btn-circle">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('shipping_costs.destroy', $cost->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{-- {{ $shipping_costs->appends(request()->input())->links() }} --}}
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
    <script type="text/javascript">
        function sort_shipping_costs(el) {
            $('#sort_shipping_costs').submit();
        }

        // Delete confirmation handler
        $(document).on('click', '.confirm-delete', function(e) {
            e.preventDefault();
            var url = $(this).data('href');
            $('#delete-modal').modal('show');
            $('#delete-form').attr('action', url);
        });
    </script>
@endsection
