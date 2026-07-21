@if ($list->lastPage() > 1)

    <div class="pagination">

        @if ($list->onFirstPage())
            <span>&laquo;</span>
        @else
            <a href="{{ $list->previousPageUrl() }}">&laquo;</a>
        @endif


        @for ($i = 1; $i <= $list->lastPage(); $i++)

            @if ($i == $list->currentPage())
                <strong>{{ $i }}</strong>
            @else
                <a href="{{ $list->url($i) }}">{{ $i }}</a>
            @endif

        @endfor


        @if ($list->hasMorePages())
            <a href="{{ $list->nextPageUrl() }}">&raquo;</a>
        @else
            <span>&raquo;</span>
        @endif

        <div class="clear"></div>
    </div>


@endif







<!--
@php
    $start = max(1, $list->currentPage() - 3);
    $end = min($list->lastPage(), $list->currentPage() + 3);
@endphp

<div class="pagination">

    @if (!$list->onFirstPage())
        <a href="{{ $list->previousPageUrl() }}">&laquo;</a>
    @endif

    @if ($start > 1)
        <a href="{{ $list->url(1) }}">1</a>
        @if ($start > 2)
            ...
        @endif
    @endif

    @for ($i = $start; $i <= $end; $i++)
        @if ($i == $list->currentPage())
            <strong>{{ $i }}</strong>
        @else
            <a href="{{ $list->url($i) }}">{{ $i }}</a>
        @endif
    @endfor

    @if ($end < $list->lastPage())
        @if ($end < $list->lastPage() - 1)
            ...
        @endif
        <a href="{{ $list->url($list->lastPage()) }}">{{ $list->lastPage() }}</a>
    @endif

    @if ($list->hasMorePages())
        <a href="{{ $list->nextPageUrl() }}">&raquo;</a>
    @endif

</div>-->