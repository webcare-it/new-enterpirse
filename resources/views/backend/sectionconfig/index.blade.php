@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Section Configurations') }}</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('All Sections') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th width="50">{{ translate('Sort') }}</th>
                                    <th>{{ translate('ID') }}</th>
                                    <th>{{ translate('Title') }}</th>
                                    <th>{{ translate('Key') }}</th>
                                    <th>{{ translate('Order') }}</th>
                                    <th>{{ translate('Status') }}</th>
                                    <th>{{ translate('Created At') }}</th>
                                </tr>
                            </thead>
                            <tbody id="section-sortable">
                                @forelse ($sectionconfigs as $key => $sectionconfig)
                                    <tr data-id="{{ $sectionconfig->id }}">
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
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            {{-- Inline editable title --}}
                                            <span class="editable-title" data-id="{{ $sectionconfig->id }}"
                                                data-original="{{ $sectionconfig->title }}">
                                                {{ $sectionconfig->title }}
                                            </span>
                                        </td>
                                        <td><strong>{{ $sectionconfig->key }}</strong></td>
                                        <td>{{ $sectionconfig->order }}</td>
                                        <td>
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input type="checkbox" class="status-toggle"
                                                    data-id="{{ $sectionconfig->id }}"
                                                    {{ $sectionconfig->isActive ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td>{{ $sectionconfig->created_at->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            {{ translate('No sections found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="aiz-pagination mt-3">
                        {{ $sectionconfigs->links() }}
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
    {{-- Delete script (if needed) --}}
    <script>
        $(document).on('click', '.confirm-delete', function(e) {
            e.preventDefault();
            $('#delete-modal').modal('show');
            $('#delete-form').attr('action', $(this).data('href'));
        });
    </script>

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    {{-- Sortable --}}
    <script>
        $(function() {
            $("#section-sortable").sortable({
                handle: ".cursor-move",
                update: function() {
                    let orders = [];
                    $('#section-sortable tr').each(function(index) {
                        orders.push({
                            id: $(this).data('id'),
                            order: index + 1
                        });
                    });
                    $.ajax({
                        url: "{{ route('sectionconfig.sort') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            orders: orders
                        },
                        success: function(response) {
                            AIZ.plugins.notify('success', 'Order updated successfully!');
                        },
                        error: function() {
                            AIZ.plugins.notify('danger', 'Something went wrong.');
                        }
                    });
                }
            });
        });
    </script>

    {{-- Toggle Status --}}
    <script>
        $(document).on('change', '.status-toggle', function() {
            let checkbox = $(this);
            let id = checkbox.data('id');
            let status = checkbox.prop('checked') ? 1 : 0;

            $.ajax({
                url: "{{ route('sectionconfig.toggleStatus') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    isActive: status
                },
                success: function(response) {
                    AIZ.plugins.notify('success', 'Status updated!');
                },
                error: function() {
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    AIZ.plugins.notify('danger', 'Failed to update status.');
                }
            });
        });
    </script>

    {{-- Inline Title Editing --}}
    <script>
        $(document).on('click', '.editable-title', function() {
            let $span = $(this);
            if ($span.hasClass('editing')) return;

            let currentText = $span.text().trim();
            let id = $span.data('id');

            // Replace span with an input
            let $input = $('<input>', {
                type: 'text',
                value: currentText,
                class: 'form-control form-control-sm editable-input',
                style: 'display:inline-block; width:auto; min-width:120px;',
                'data-id': id,
                'data-original': currentText
            });

            $span.replaceWith($input);
            $input.focus().select();

            // Save on blur (click outside) or Enter key
            $input.on('blur', function() {
                saveTitle($(this));
            });

            $input.on('keydown', function(e) {
                if (e.key === 'Enter') {
                    $(this).blur();
                }
                if (e.key === 'Escape') {
                    let original = $(this).data('original');
                    $(this).replaceWith(createSpan(id, original, original));
                }
            });
        });

        function saveTitle($input) {
            let id = $input.data('id');
            let newTitle = $input.val().trim();
            let original = $input.data('original');

            if (newTitle === '') {
                newTitle = original;
            }

            $.ajax({
                url: "{{ route('sectionconfig.updateTitle') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    title: newTitle
                },
                success: function(response) {
                    AIZ.plugins.notify('success', response.message);
                    $input.replaceWith(createSpan(id, response.title, response.title));
                },
                error: function() {
                    AIZ.plugins.notify('danger', 'Failed to update title.');
                    $input.replaceWith(createSpan(id, original, original));
                }
            });
        }

        function createSpan(id, text, original) {
            return $('<span>', {
                class: 'editable-title',
                'data-id': id,
                'data-original': original || text,
                text: text
            });
        }
    </script>

    {{-- Styles for inline editing --}}
    <style>
        .editable-title {
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            transition: background 0.2s;
            display: inline-block;
            min-width: 60px;
        }

        .editable-title:hover {
            background: #f1f3f5;
        }

        .editable-input {
            display: inline-block !important;
            width: auto !important;
            min-width: 120px;
            padding: 4px 8px;
            height: auto;
        }
    </style>
@endsection
