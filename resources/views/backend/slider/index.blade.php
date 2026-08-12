@extends('backend.layouts.app')

@section('content')
    @include('backend.section._tabs')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('All sliders') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('slider.create') }}" class="btn btn-primary">
                    <span>{{ translate('Add New slider') }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-md-0 h6">{{ translate('sliders') }}</h5>
                    </div>
                    <div class="col-md-4">
                        <form class="" id="sort_sliders" action="" method="GET">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="search"
                                    name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                                    placeholder="{{ translate('Type name & Enter') }}">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Title</th>
                                <th>Button Name</th>
                                <th>Image</th>
                                <th>Date</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sliders as $key => $slider)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $slider->title }}</td>
                                    <td>
                                        <a href="{{ $slider->button_link }}" class="btn btn-primary btn-sm"
                                            target="_blank">{{ $slider->button_name }}</a>
                                    </td>

                                    <td>
                                        @if ($slider->photos)
                                            <img src="{{ uploaded_asset($slider->photos) }}" alt="Thumbnail Image"
                                                class="img-fluid" style="max-height: 50px;">
                                        @endif
                                    </td>
                                    <td>{{ $slider->created_at->format('d M Y') }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('slider.edit', $slider->id) }}"
                                            class="btn btn-icon btn-soft-primary btn-sm btn-circle">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('slider.destroy', ['id' => $slider->id]) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $sliders->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection



@section('script')
    <script type="text/javascript">
        function sort_sliders(el) {
            $('#sort_sliders').submit();
        }

        // Delete confirmation handler
        $(document).on('click', '.confirm-delete', function(e) {
            e.preventDefault();
            var url = $(this).data('href');
            $('#delete-modal').modal('show');
            $('#delete-form').attr('action', url);
        });
    </script>
@endsection
