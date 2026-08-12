@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Team Members') }}</h1>
            </div>

            <div class="col-md-6 text-md-right">
                <a href="{{ route('team.create') }}" class="btn btn-primary">
                    {{ translate('Add New Member') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="card">

                {{-- Header --}}
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('All Team Members') }}</h5>
                </div>

                {{-- Table --}}
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Avatar') }}</th>
                                <th>{{ translate('Name') }}</th>
                                <th>{{ translate('Designation') }}</th>
                                <th>{{ translate('Position') }}</th>
                                <th>{{ translate('Date') }}</th>
                                <th class="text-right">{{ translate('Action') }}</th>
                            </tr>
                        </thead>

                        <tbody id="team-sortable">
                            @forelse ($teams as $key => $team)
                                <tr data-id="{{ $team->id }}">
                                    <td class="cursor-move" style="cursor: pointer;">
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
                                        @if ($team->avatar)
                                            <img src="{{ uploaded_asset($team->avatar) }}"
                                                style="height:50px; width:50px; object-fit:cover; border-radius:50%;">
                                        @else
                                            <span class="badge badge-secondary">No Image</span>
                                        @endif
                                    </td>

                                    <td><strong>{{ $team->name }}</strong></td>
                                    <td>{{ $team->designation ?? '-' }}</td>
                                    <td>{{ $team->position ?? '-' }}</td>
                                    <td>{{ $team->created_at->format('d M Y') }}</td>

                                    <td class="text-right">
                                        <a href="{{ route('team.edit', $team->id) }}"
                                            class="btn btn-soft-primary btn-icon btn-circle btn-sm">
                                            <i class="las la-edit"></i>
                                        </a>

                                        <button type="button"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('team.destroy', $team->id) }}">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        {{ translate('No team members found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>

                    <div class="aiz-pagination mt-3">
                        {{ $teams->appends(request()->input())->links() ?? '' }}
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
            $("#team-sortable").sortable({
                handle: ".cursor-move",
                update: function() {

                    let positions = [];

                    $('#team-sortable tr').each(function(index) {
                        positions.push({
                            id: $(this).data('id'),
                            position: index + 1
                        });
                    });

                    // AJAX call
                    $.ajax({
                        url: "{{ route('team.sort') }}",
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
