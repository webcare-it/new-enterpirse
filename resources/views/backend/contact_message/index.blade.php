@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Contact Messages') }}</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="card">

                {{-- Header --}}
                <div class="card-header row gutters-5">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-0 h6">{{ translate('All Messages') }}</h5>
                    </div>
                </div>

                {{-- Table --}}
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Name') }}</th>
                                <th>{{ translate('Email') }}</th>
                                <th>{{ translate('Subject') }}</th>
                                <th>{{ translate('Message') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Date') }}</th>
                                <th class="text-right">{{ translate('Action') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($contact_messages as $key => $msg)
                                <tr class="{{ $msg->status == 'unread' ? 'bg-light' : '' }}">

                                    <td>{{ $key + 1 }}</td>
                                    <td><strong>{{ $msg->full_name }}</strong></td>
                                    <td>{{ $msg->email }}</td>
                                    <td>{{ $msg->subject }}</td>
                                    <td>
                                        {{ \Illuminate\Support\Str::limit($msg->message, 60) }}
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $msg->status == 'unread' ? 'danger' : 'success' }}">
                                            {{ ucfirst($msg->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $msg->created_at->format('d M Y') }}
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('contact-message.show', $msg->id) }}"
                                            class="btn btn-soft-info btn-icon btn-circle btn-sm" title="View">
                                            <i class="las la-eye"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('contact-message.destroy', $msg->id) }}" title="Delete">
                                            <i class="las la-trash"></i>
                                        </button>

                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        {{ translate('No messages found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

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
        $(document).on('click', '.confirm-delete', function(e) {
            e.preventDefault();
            $('#delete-modal').modal('show');
            $('#delete-form').attr('action', $(this).data('href'));
        });
    </script>
@endsection
