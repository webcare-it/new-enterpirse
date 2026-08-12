@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('All FAQs') }}</h1>
            </div>

            <div class="col-md-6 text-md-right">
                <a href="{{ route('faq.create') }}" class="btn btn-primary">
                    {{ translate('Add New FAQ') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="card">

                {{-- Header --}}
                <div class="card-header row gutters-5">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-0 h6">{{ translate('FAQ List') }}</h5>
                    </div>
                </div>

                {{-- Table --}}
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Question') }}</th>
                                <th>{{ translate('Answer') }}</th>
                                <th>{{ translate('Position') }}</th>
                                <th class="text-right">{{ translate('Action') }}</th>
                            </tr>
                        </thead>

                        <tbody id="faq-sortable">
                            @foreach ($faqs as $faq)
                                <tr data-id="{{ $faq->id }}">
                                    <td class="cursor-move" style="cursor: pointer">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="#6c757d">
                                            <rect x="2" y="2" width="5" height="5" rx="1.5" />
                                            <rect x="9.5" y="2" width="5" height="5" rx="1.5" />
                                            <rect x="17" y="2" width="5" height="5" rx="1.5" />
                                            <rect x="2" y="9.5" width="5" height="5" rx="1.5" />
                                            <rect x="9.5" y="9.5" width="5" height="5" rx="1.5" />
                                            <rect x="17" y="9.5" width="5" height="5" rx="1.5" />
                                        </svg>
                                    </td>

                                    <td>
                                        <strong>{{ $faq->question }}</strong>
                                    </td>

                                    <td>
                                        {{ Str::limit($faq->answer, 80) }}
                                    </td>

                                    <td>
                                        {{ $faq->position }}
                                    </td>

                                    <td class="text-right">
                                        <a href="{{ route('faq.edit', $faq->id) }}"
                                            class="btn btn-soft-primary btn-icon btn-circle btn-sm">
                                            <i class="las la-edit"></i>
                                        </a>

                                        <button type="button"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('faq.destroy', $faq->id) }}">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
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


    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <script>
        $(function() {
            $("#faq-sortable").sortable({
                handle: ".cursor-move",
                update: function() {

                    let positions = [];

                    $('#faq-sortable tr').each(function(index) {
                        positions.push({
                            id: $(this).data('id'),
                            position: index + 1
                        });
                    });

                    // AJAX call
                    $.ajax({
                        url: "{{ route('faq.sort') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            positions: positions
                        },
                        success: function() {
                            AIZ.plugins.notify('success', 'Position updated!');
                        }
                    });
                }
            });
        });
    </script>
@endsection
