@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('All Blogs') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('blog.create') }}" class="btn btn-primary">
                    <span>{{ translate('Add New Blog') }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-md-0 h6">{{ translate('Blogs') }}</h5>
                    </div>
                    <div class="col-md-4">
                        <form class="" id="sort_blogs" action="" method="GET">
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
                                <th>Slug</th>
                                <th>Thumbnail</th>
                                <th>Author</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($blogs as $key => $blog)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $blog->blog_title }}</td>
                                    <td>{{ $blog->slug }}</td>
                                    <td>
                                        @if ($blog->thumbnail)
                                            <img src="{{ uploaded_asset($blog->thumbnail) }}" alt="Thumbnail"
                                                class="img-fluid" style="max-height: 50px;">
                                        @endif
                                    </td>
                                    <td>{{ $blog->user->name }}</td>
                                    <td>{{ $blog->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('blog.edit', $blog->id) }}"
                                            class="btn btn-icon btn-soft-primary btn-circle">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="{{ route('blog.show', $blog->id) }}"
                                            class="btn btn-icon btn-soft-info btn-circle">
                                            <i class="las la-eye"></i>
                                        </a>

                                        <button type="button"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('blog.destroy', ['id' => $blog->id]) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $blogs->appends(request()->input())->links() }}
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
        function sort_blogs(el) {
            $('#sort_blogs').submit();
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
