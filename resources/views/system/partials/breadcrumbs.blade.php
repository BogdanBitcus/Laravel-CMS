<div class="path">
    @foreach($breadcrumbs as $item)
        @if($item->id==1)
            <a href="{{ route('cms.dashboard.index') }}">{{ $item->name }}</a>
        @elseif($item->id == $page->id)
            <span>{{ $item->name }}</span>
        @else
            <a href="{{ url('/cms/page/'.$item->id) }}">{{ $item->name }}</a>
        @endif
    @endforeach
</div>
<div class="clear"></div>
<br>