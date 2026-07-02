
@include('system.admin_header')

                <FORM action="{{ url('/cms/templates/save/'.$template->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <fieldset>
                        <legend>Template settings:</legend>
                        Name: <input name='name' value="{{ $template->name }}">

                        <br><br>
                        Parent:
                        <select name='parent'>
                            <option value='0'>-</option>
                            @foreach($templates as $type)
                                <option value='{{ $type->id }}' {{ $template->parent==$type->id ? "selected=selected" : "" }} >{{ $type->name }}</option>
                                    @if(count( $type->children ))
                                        @include('system.partials.templates_children_options', [ 'children' => $type->children , 'separate'=>"--", 'parent'=>$template->parent])
                                    @endif
                            @endforeach
                        </select>

                        <br><br>
                        Edit template:
                        <select name='admin_tpl'>
                            <option value=''>-</option>
                            @foreach ($admin_tpl as $edit)
                                <option value='{{ $edit['value'] }}' {{ $edit['selected'] ? "selected=selected" : "" }}>{{ $edit['filename'] }}</option>
                            @endforeach
                        </select>

                        <br><br>
                        View template:
                        <select name='view_tpl'>
                            <option value=''>-</option>
                            @foreach ($view_tpl as $view)
                                <option value='{{ $view['value'] }}' {{ $view['selected'] ? "selected=selected" : "" }}>{{ $view['filename'] }}</option>
                            @endforeach
                        </select>

                    </fieldset>
                    <br>
                    <input type=submit value='Save'>
                </form>

@include('system.admin_footer')