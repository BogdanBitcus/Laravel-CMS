
    @foreach($children as $child)

        <option value='{{ $child->id }}' {{ $parent==$child->id ? "selected=selected" : "" }}>{{ $separate.$child->name }}</option>

            @if(count( $child->children ))
                @include('system.partials.templates_children_options', [ 'children' => $child->children , 'separate'=>$separate."--", 'parent'=>$parent])
            @endif

    @endforeach