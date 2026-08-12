@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">{{ translate('Website Pages') }}</h1>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 fw-600">{{ translate('All pages') }}</h6>
            <a href="{{ route('custom-pages.create') }}" class="btn btn-primary">{{ translate('Add new page') }}</a>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th width="50">{{ translate('Sort') }}</th>
                        <th data-breakpoints="lg">#</th>
                        <th>{{ translate('Name') }}</th>
                        <th data-breakpoints="md">{{ translate('URL') }}</th>
                        <th class="text-right">{{ translate('Actions') }}</th>
                    </tr>
                </thead>
                <tbody id="brand-sortable">
                    @foreach ($pages as $key => $page)
                        <tr data-id="{{ $page->id }}">

                            <td class="cursor-move" style="cursor: move;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="#6c757d">
                                    <rect x="2" y="2" width="5" height="5" rx="1.5" />
                                    <rect x="9.5" y="2" width="5" height="5" rx="1.5" />
                                    <rect x="17" y="2" width="5" height="5" rx="1.5" />
                                    <rect x="2" y="9.5" width="5" height="5" rx="1.5" />
                                    <rect x="9.5" y="9.5" width="5" height="5" rx="1.5" />
                                    <rect x="17" y="9.5" width="5" height="5" rx="1.5" />
                                </svg>

                            </td>

                            <td>{{ $loop->iteration }}</td>

                            @if ($page->type == 'home')
                                <td><a href="{{ env('APP_URL') }}pages/{{ $page->slug }}"
                                        class="text-reset">{{ translate($page->title) }}</a>
                                </td>
                                <td>{{ env('APP_URL') }}</td>
                            @else
                                <td><a href="{{ env('APP_URL') }}pages/{{ $page->slug }}"
                                        class="text-reset">{{ $page->title }}</a></td>
                                <td>{{ env('APP_URL') }}pages/{{ $page->slug }}</td>
                            @endif

                            <td class="text-right">
                                @if ($page->type == 'home')
                                    <a href="{{ route('main.page') }}"
                                        class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">
                                        <i class="las la-pen"></i>
                                    </a>
                                @elseif ($page->type == 'about_us')
                                    <a href="{{ route('website.about.page') }}"
                                        class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">
                                        <i class="las la-pen"></i>
                                    </a>
                                @else
                                    <a href="{{ route('custom-pages.edit', ['custom_page' => $page->slug, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                        class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">
                                        <i class="las la-pen"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection



@section('script')
    {{-- Delete --}}
    <script>
        $(document).on('click', '.confirm-delete', function(e) {
            e.preventDefault();
            $('#delete-modal').modal('show');
            $('#delete-form').attr('action', $(this).data('href'));
        });
    </script>

    {{-- jQuery UI --}}
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    {{-- Sortable --}}
    <script>
        $(function() {
            $("#brand-sortable").sortable({
                handle: ".cursor-move",
                update: function() {
                    let positions = [];
                    $('#brand-sortable tr').each(function(index) {
                        positions.push({
                            id: $(this).data('id'),
                            position: index + 1
                        });
                    });
                    // AJAX call
                    $.ajax({
                        url: "{{ route('website.pages.sort') }}",
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
