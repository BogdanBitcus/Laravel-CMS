<ul>
    @foreach($children as $child)
        <li style="padding: 5px 0 0 0;">
            <a href="/cms/templates/{{ $child->id }}">
                <b>{{ $child->name }}</b>&nbsp;<img src="/img/cms/edit.gif" alt="Edit" style="margin: 0px 5px -4px 5px;">
            </a>

            <img src="/img/cms/del.gif" alt="Delete" class="js_delete_template" data-id="{{ $child->id }}"  style="margin: 0px 0px -4px 0px;">

            @if(count( $child->children ))
                @include('system.partials.templates_children', [ 'children' => $child->children ])
            @endif
        </li>
    @endforeach
</ul>
