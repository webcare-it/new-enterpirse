@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="align-items-center">
        <h1 class="h3">{{translate('All Visitors')}}</h1>
    </div>
</div>

<style>
    .status-badge {
        display: block !important;
        width: 100% !important;
        text-align: center !important;
        padding: 0.5rem 0 !important;
    }
</style>


<div class="card">
    <form class="" id="sort_visitors" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-0 h6">{{translate('Visitors')}}</h5>
            </div>

            </div>
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>{{translate('IP Address')}}</th>
                        <th data-breakpoints="lg">{{translate('Device/Browser')}}</th>
                        <th data-breakpoints="lg">{{translate('Visit Count')}}</th>
                        <th data-breakpoints="lg">{{translate('Last Visit')}}</th>
                        <th>{{translate('Status')}}</th>
                        <th>{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $key => $log)
                        @if ($log != null)
                            <tr>
                                <td>{{$log->ip_address}}</td>
                                <td>{{$log->device ?? 'Unknown'}}</td>
                                <td>{{$log->counts}}</td>
                                <td>{{ $log->updated_at->format('d M Y, H:i') }}</td>
                                <td>
                                    @if($log->is_blocked == 1)
                                        <span class="badge badge-danger">{{translate('Blocked')}}</span>
                                    @else
                                        <span class="badge badge-success">{{translate('Active')}}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($log->is_blocked != 1)
                                    <form action="{{route('admin.visitor_log.block', $log->id)}}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-soft-danger btn-icon btn-circle btn-sm" title="{{ translate('Block this IP') }}">
                                            <i class="las la-ban"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{route('admin.visitor_log.unblock', $log->id)}}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('Unblock this IP') }}">
                                            <i class="las la-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('admin.visitor_log.destroy', $log->id)}}" title="{{ translate('Delete') }}">
                                        <i class="las la-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $logs->appends(request()->input())->links() }}
            </div>
        </div>
    </form>
</div>


@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        // Delete confirmation handler
        $(document).on('click', '.confirm-delete', function(e) {
            e.preventDefault();
            var url = $(this).data('href');
            $('#delete-modal').modal('show');
            $('#delete-form').attr('action', url);
        });
    </script>
@endsection 
