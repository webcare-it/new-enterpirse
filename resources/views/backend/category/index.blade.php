@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Categories') }}</h1>
            </div>

            <div class="col-md-6 text-md-right">
                <a href="{{ route('dropshipping-category.create') }}" class="btn btn-primary">
                    {{ translate('Add New Category') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="card">

                {{-- Header --}}
                <div class="card-header row gutters-5 align-items-center">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-0 h6">{{ translate('Category List') }}</h5>
                    </div>

                    {{-- Search --}}
                    <div class="col-md-4">
                        <form method="GET">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                    placeholder="{{ translate('Search category...') }}">

                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        {{ translate('Search') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Body --}}
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th width="50">#</th>
                                    <th>{{ translate('Image') }}</th>
                                    <th>{{ translate('Category Name') }}</th>
                                    <th>{{ translate('Icon') }}</th>
                                    <th>{{ translate('Slug') }}</th>
                                    <th width="120" class="text-right">
                                        {{ translate('Action') }}
                                    </th>
                                </tr>
                            </thead>

                            <tbody id="category-sortable">

                                @forelse ($categories as $key => $category)
                                    <tr data-id="{{ $category->id }}">

                                        {{-- Sort Handle --}}
                                        <td class="cursor-move text-center" style="cursor: move;">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#6c757d">
                                                <rect x="2" y="2" width="5" height="5" rx="1.5" />
                                                <rect x="9.5" y="2" width="5" height="5" rx="1.5" />
                                                <rect x="17" y="2" width="5" height="5" rx="1.5" />

                                                <rect x="2" y="9.5" width="5" height="5" rx="1.5" />
                                                <rect x="9.5" y="9.5" width="5" height="5" rx="1.5" />
                                                <rect x="17" y="9.5" width="5" height="5" rx="1.5" />
                                            </svg>
                                        </td>

                                        {{-- Image --}}
                                        <td>
                                            @if ($category->category_image)
                                                @php
                                                    $image = $category->category_image;

                                                    $imageUrl = uploaded_asset($image);

                                                @endphp

                                                <img src="{{ $imageUrl }}" alt="{{ $category->category_name }}"
                                                    class="img-fluid rounded"
                                                    style="height:50px;width:50px;object-fit:cover;">
                                            @else
                                                <span class="badge badge-secondary">
                                                    {{ translate('No Image') }}
                                                </span>
                                            @endif
                                        </td>
                                        {{-- Name --}}
                                        <td>
                                            <strong>{{ $category->category_name }}</strong>
                                        </td>

                                        {{-- Icon --}}
                                        <td>
                                            @if ($category->icon)
                                                <img src=" {{ uploaded_asset($category->icon) }}"
                                                    alt="{{ $category->category_name }}" class="img-fluid rounded"
                                                    style="height:50px;width:50px;object-fit:cover;">
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        {{-- Slug --}}
                                        <td>
                                            <small class="text-muted">
                                                {{ $category->slug }}
                                            </small>
                                        </td>

                                        {{-- Action --}}
                                        <td class="text-right">

                                            {{-- Edit --}}
                                            <a href="{{ route('dropshipping-category.edit', $category->id) }}"
                                                class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                                title="{{ translate('Edit') }}">
                                                <i class="las la-edit"></i>
                                            </a>

                                            {{-- Delete --}}
                                            <button type="button"
                                                class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                                data-href="{{ route('dropshipping-category.destroy', $category->id) }}"
                                                title="{{ translate('Delete') }}">
                                                <i class="las la-trash"></i>
                                            </button>

                                        </td>
                                    </tr>

                                @empty
                                @endforelse

                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="aiz-pagination mt-3">
                        {{ $categories->withQueryString()->links() }}
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

    {{-- JQuery UI --}}
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    {{-- Sortable --}}
    <script>
        $(function() {
            $("#category-sortable").sortable({
                handle: ".cursor-move",
                update: function() {
                    let positions = [];
                    $('#category-sortable tr').each(function(index) {
                        positions.push({
                            id: $(this).data('id'),
                            position: index + 1
                        });
                    });
                    // AJAX call
                    $.ajax({
                        url: "{{ route('dropshipping-category.sort') }}",
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
