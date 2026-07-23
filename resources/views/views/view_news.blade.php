
<h1>{{ $page->name }}</h1>

<content>{!! $page->content !!}</content>

@foreach($news as $new)

    <div style="margin:0 0 50px 0;">

        <h2>{{ $new->name }}</h2>
        <div class="date">{{ $new->date }}</div>
        <div><img src="{{ $new->image }}" alt="{{ $new->title }}" width="200"></div>
        <div>{!! $new->custom_anons !!}</div>
        <div><a href="{{ $new->addr }}">Detailed &gt;&gt;</a></div>

    </div>

@endforeach


@if ($news->lastPage() > 1)

    <div class="pagination">

        @if ($news->onFirstPage())
            <span>&laquo;</span>
        @else
            <a href="{{ $news->previousPageUrl() }}">&laquo;</a>
        @endif


        @for ($i = 1; $i <= $news->lastPage(); $i++)

            @if ($i == $news->currentPage())
                <strong>{{ $i }}</strong>
            @else
                <a href="{{ $news->url($i) }}">{{ $i }}</a>
            @endif

        @endfor


        @if ($news->hasMorePages())
            <a href="{{ $news->nextPageUrl() }}">&raquo;</a>
        @else
            <span>&raquo;</span>
        @endif

        <div class="clear"></div>
    </div>


@endif
