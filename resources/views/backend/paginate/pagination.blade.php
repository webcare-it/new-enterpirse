@php
    $current = $paginator->currentPage();
    $last = $paginator->lastPage();

    $start = max($current - 1, 1);
    $end = min($current + 1, $last);
@endphp

@if ($paginator->hasPages())
    <ul class="custom-pagination">
        <li class="{{ $paginator->onFirstPage() ? 'disabled' : '' }}">
            @if ($paginator->onFirstPage())
                <span>← Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}">← Prev</a>
            @endif
        </li>

        @if ($start > 1)
            <li>
                <a href="{{ $paginator->url(1) }}">1</a>
            </li>

            @if ($start > 2)
                <li class="disabled"><span>...</span></li>
            @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            <li class="{{ $i == $current ? 'active' : '' }}">
                @if ($i == $current)
                    <span>{{ $i }}</span>
                @else
                    <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
                @endif
            </li>
        @endfor

        @if ($end < $last)
            @if ($end < $last - 1)
                <li class="disabled"><span>...</span></li>
            @endif

            <li>
                <a href="{{ $paginator->url($last) }}">{{ $last }}</a>
            </li>
        @endif

        <li class="{{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}">Next →</a>
            @else
                <span>Next →</span>
            @endif
        </li>

    </ul>

    <style>
        .custom-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 25px 0;
        }

        .custom-pagination li a,
        .custom-pagination li span {
            display: block;
            padding: 8px 14px;
            border: 1px solid #e9ecef;
            border-radius: 30px;
            background: #fff;
            color: #495057;
            text-decoration: none;
            transition: .2s;
        }

        .custom-pagination li a:hover {
            background: #f8f9fa;
        }

        .custom-pagination .active span {
            background: #007bff;
            border-color: #007bff;
            color: #fff;
        }

        .custom-pagination .disabled span {
            color: #adb5bd;
            background: #f8f9fa;
        }
    </style>
@endif
