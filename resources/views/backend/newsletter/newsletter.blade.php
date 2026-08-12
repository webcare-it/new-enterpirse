@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Newsletter Subscribers') }}</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="card">

                {{-- Header --}}
                <div class="card-header row gutters-5">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-0 h6">{{ translate('All Subscribers') }}</h5>
                    </div>
                </div>

                {{-- Table --}}
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Email') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Subscribed At') }}</th>
                                <th>{{ translate('Created') }}</th>
                                <th class="text-right">{{ translate('Action') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($newsletters as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    {{-- Email --}}
                                    <td>
                                        <strong>{{ $item->email }}</strong>
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" class="status-toggle" data-id="{{ $item->id }}"
                                                {{ $item->status ? 'checked' : '' }}>
                                            <span></span>
                                        </label>
                                    </td>

                                    {{-- Subscribed At --}}
                                    <td>
                                        {{ $item->subscribed_at ?? '-' }}
                                    </td>

                                    {{-- Created --}}
                                    <td>
                                        {{ $item->created_at->format('d M Y') }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-right">

                                        {{-- Delete --}}
                                        <button type="button"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('newsletter.destroy', $item->id) }}">
                                            <i class="las la-trash"></i>
                                        </button>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        {{ translate('No subscribers found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="aiz-pagination mt-3">
                        {{ $newsletters->links() }}
                    </div>

                </div>

            </div>

        </div>
    </div>
@endsection

{{-- Delete Modal --}}
@section('modal')
    @include('modals.delete_modal')
@endsection

{{-- Script --}}
@section('script')
    <script>
        // DELETE
        $(document).on('click', '.confirm-delete', function(e) {
            e.preventDefault();
            $('#delete-modal').modal('show');
            $('#delete-form').attr('action', $(this).data('href'));
        });

        // STATUS TOGGLE (AJAX)
        $(document).on('change', '.status-toggle', function() {
            let id = $(this).data('id');
            let status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: "{{ route('newsletter.status') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    status: status
                },
                success: function() {
                    AIZ.plugins.notify('success', 'Status updated');
                }
            });
        });
    </script>
@endsection
