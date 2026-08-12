@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Brands') }}</h1>
            </div>

            <div class="col-md-6 text-md-right">
                <a href="{{ route('brand.create') }}" class="btn btn-primary">
                    {{ translate('Add New Brand') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('All Brands') }}</h5>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th width="50">{{ translate('Sort') }}</th>
                                    <th>{{ translate('Brand Image') }}</th>
                                    <th>{{ translate('Background Image') }}</th>
                                    <th>{{ translate('Name') }}</th>
                                    <th>{{ translate('Slug') }}</th>
                                    <th>{{ translate('Position') }}</th>
                                    <th>{{ translate('Description') }}</th>
                                    <th>{{ translate('Date') }}</th>
                                    <th class="text-right">{{ translate('Action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="brand-sortable">

                                @forelse ($brands as $key => $brand)
                                    <tr data-id="{{ $brand->id }}">
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
                                        <td>
                                            @if ($brand->brand_image)
                                                <img src="{{ uploaded_asset($brand->brand_image) }}"
                                                    style="height:50px; width:50px; object-fit:cover; border-radius:6px;">
                                            @else
                                                <span class="badge badge-secondary">
                                                    {{ translate('No Image') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($brand->bg_image)
                                                <img src="{{ uploaded_asset($brand->bg_image) }}"
                                                    style="height:50px; width:80px; object-fit:cover; border-radius:6px;">
                                            @else
                                                <span class="badge badge-secondary">
                                                    {{ translate('No Image') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $brand->name }}</strong>
                                        </td>
                                        <td>
                                            {{ $brand->slug }}
                                        </td>
                                        <td>
                                            {{ $brand->position }}
                                        </td>
                                        <td>
                                            {{ \Illuminate\Support\Str::limit($brand->description, 50) }}
                                        </td>
                                        <td>
                                            {{ $brand->created_at->format('d M Y') }}
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('brand.edit', $brand->id) }}"
                                                class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                                title="{{ translate('Edit') }}">
                                                <i class="las la-edit"></i>
                                            </a>
                                            <button type="button"
                                                class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                                data-href="{{ route('brand.destroy', $brand->id) }}"
                                                title="{{ translate('Delete') }}">
                                                <i class="las la-trash"></i>
                                            </button>

                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">
                                            {{ translate('No brands found') }}
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>
                    </div>
                    <div class="aiz-pagination mt-3">
                        {{ $brands->links() }}
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
                        url: "{{ route('brand.sort') }}",
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
