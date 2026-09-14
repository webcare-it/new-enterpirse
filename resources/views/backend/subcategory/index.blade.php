@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Sub Categories') }}</h1>
            </div>

            <div class="col-md-6 text-md-right">
                <a href="{{ route('subcategory.create') }}" class="btn btn-primary">
                    {{ translate('Add New Subcategory') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="card">

                {{-- Header --}}
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('All Sub Categories') }}</h5>
                </div>

                {{-- Table --}}
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table aiz-table mb-0">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ translate('Image') }}</th>
                                    <th>{{ translate('Name') }}</th>
                                    <th>{{ translate('Slug') }}</th>
                                    <th>{{ translate('Category') }}</th>
                                    <th>{{ translate('Date') }}</th>
                                    <th class="text-right">{{ translate('Action') }}</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse ($subcategories as $key => $sub)
                                    <tr>

                                        {{-- SL --}}
                                        <td>{{ $key + 1 }}</td>

                                        {{-- Image --}}
                                        <td>
                                            @if ($sub->image)
                                                <img src="{{ uploaded_asset($sub->image) }}"
                                                    style="height:45px; width:45px; object-fit:cover; border-radius:6px;">
                                            @else
                                                <span class="badge badge-secondary">
                                                    {{ translate('No Image') }}
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Name --}}
                                        <td>
                                            <strong>{{ $sub->name }}</strong>
                                        </td>

                                        {{-- Slug --}}
                                        <td>
                                            {{ $sub->slug }}
                                        </td>

                                        {{-- Category --}}
                                        <td>
                                            @if ($sub->category)
                                                <span class="badge badge-info">
                                                    {{ $sub->category->category_name }}
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    {{ translate('No Category') }}
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Date --}}
                                        <td>
                                            {{ $sub->created_at->format('d M Y') }}
                                        </td>

                                        {{-- Action --}}
                                        <td class="text-right">

                                            {{-- Edit --}}
                                            <a href="{{ route('subcategory.edit', $sub->id) }}"
                                                class="btn btn-soft-primary btn-icon btn-circle btn-sm">
                                                <i class="las la-edit"></i>
                                            </a>

                                            {{-- Delete --}}
                                            <button type="button"
                                                class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                                data-href="{{ route('subcategory.destroy', $sub->id) }}">
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
                        {{ $subcategories->links() }}
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
        $(document).on('click', '.confirm-delete', function(e) {
            e.preventDefault();

            $('#delete-modal').modal('show');
            $('#delete-form').attr('action', $(this).data('href'));
        });
    </script>
@endsection
