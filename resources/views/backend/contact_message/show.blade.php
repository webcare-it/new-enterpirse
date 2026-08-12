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
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 h6">{{ translate('Contact Message Details') }}</h5>
                    <a href="{{ route('contact-message.index') }}"
                        class="btn btn-secondary btn-sm">{{ translate('Back') }}</a>
                </div>

                <div class="card-body">
                    <p><strong>{{ translate('Full Name') }}:</strong> {{ $contact_message->full_name }}</p>
                    <p><strong>{{ translate('Email') }}:</strong> {{ $contact_message->email }}</p>
                    <p><strong>{{ translate('Subject') }}:</strong> {{ $contact_message->subject }}</p>
                    <p><strong>{{ translate('Message') }}:</strong></p>
                    <p>{{ $contact_message->message }}</p>
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
