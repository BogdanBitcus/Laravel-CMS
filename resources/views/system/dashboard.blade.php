
@include('system.admin_header')

                @if( ! $page==null)
                <div class="path">
                    <a href='{{ route('cms.dashboard.index') }}'>{{ $page->name }}</a>
                </div>
                @endif

                <div class="clear"></div>
                <br>

                <form action="{{ route('cms.dashboard.addpage') }}" method="post">
                    @csrf
                    <input type="submit" class="save"  value="Add section/page">
                </form>

                <form action="{{ route('cms.dashboard.update') }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="1">
                    <input type="hidden" name="lang" value="en">
                    <input type="hidden" name="slug" value="">






                    <br>
                    <table cellspacing="0" cellpadding="0" class="list">
                        <tr>
                            <th>Show</th>
                            <th>Position</th>
                            <th>URL</th>
                            <th>Name</th>
                            <th>&nbsp;</th>
                            <th>Template</th>
                        </tr>

                        @if( ! $page==null)
                        <tr>
                            <td>
                                <input type="checkbox" name="published_{{ $page->id }}" value="1" {{ $page->published == 1 ? 'checked="checked"' : '' }}>
                            </td>
                            <td>
                                <input name="position[{{ $page->id }}]" value="{{ $page->position }}" class="small">
                                <img style="margin: 0 0 -7px;" src="/img/cms/top.gif" alt="Up" title="Up" onclick="move({{ $page->id }},-11)"><img style="margin: 0 0 -7px;" src="/img/cms/bottom.gif" alt="Down" title="Down" onclick="move({{ $page->id }},+11)">
                            </td>
                            <td>
                                <input name="slug_{{ $page->id }}" class="medium" value="/" disabled="disabled" onblur="check_url(this,{{ $page->id }});">
                            </td>
                            <td>
                                <textarea name="name_{{ $page->id }}" class="big">{{ $page->name }}</textarea>
                            </td>
                            <td>
                                <a href="{{ route('cms.page.index', $page) }}">
                                    <img style="margin: 0 0 -3px;" src="/img/cms/edit.gif" alt="Edit" >
                                </a>
                            </td>
                            <td>
                                <select class="select" name="template_{{ $page->id }}" id="template_{{ $page->id }}" >
                                    @foreach ($templates as $type)
                                        <option value="{{ $type->id }}" {{ $page->template==$type->id ? 'selected="selected"' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        @endif


                        @foreach ($list as $item)
                        <tr>
                            <td>
                                <input type="checkbox" name="published_{{ $item->id }}" value="1" {{ $item->published == 1 ? 'checked="checked"' : '' }}>
                            </td>
                            <td>
                                <input name="position[{{ $item->id }}]" value="{{ $item->position }}" class="small">
                                <img style="margin: 0 0 -7px;" src="/img/cms/top.gif" alt="Up" title="Up" onclick="move({{ $item->id }},-11)"><img style="margin: 0 0 -7px;" src="/img/cms/bottom.gif" alt="Down" title="Down" onclick="move({{ $item->id }},+11)">
                            </td>
                            <td>
                                <input name="slug_{{ $item->id }}" class="medium" value="{{ $item->slug ? $item->slug : $item->id }}" onblur="check_url(this,{{ $item->id }});">
                                <input type="hidden" name="addr_{{ $item->id }}" value="{{ $item->addr }}">
                            </td>
                            <td>
                                <textarea name="name_{{ $item->id }}" class="big">{{ $item->name }}</textarea>
                            </td>
                            <td>
                                <a href="{{ route('cms.page.index', $item) }}">
                                    <img style="margin: 0 0 -3px;" src="/img/cms/edit.gif" alt="Edit" title="Edit" >
                                </a>
                                <img style="margin: 0 0 -3px;" src="/img/cms/del.gif" class="img js_delete_page" data-id="{{ $item->id }}" alt="Delete" title="Delete">
                            </td>
                            <td>
                                <select class="select" name="template_{{ $item->id }}" id="template_{{ $item->id }}">
                                    @foreach ($templates as $type)
                                    <option value="{{ $type->id }}" {{ $item->template==$type->id ? 'selected="selected"' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        @endforeach



                    </table>

                    <br>
                    <input type="submit" class="save" value="Save">

                </form>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var deleteLinks = document.querySelectorAll('.js_delete_page');
        deleteLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                //event.preventDefault(); // Відміняємо стандартну дію посилання
                var url = "{{ route('cms.dashboard.delete', ['page'=>'___ID___']) }}";
                var itemId = link.getAttribute('data-id');
                if (confirm('Delete item? WARNING! All child/nested elements will be removed!')) {
                    fetch(url.replace('___ID___', itemId), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                    }).then(response => {
                        if (response.ok) {
                            return response.json();
                        } else {
                            throw new Error('Failed to delete page');
                        }
                    }).then(data => {
                        //alert(data.message); // it is not necessary
                        window.location.reload();
                    }).catch(function(error) {
                        console.error('Error deleting item:', error);
                    });
                }
            });
        });
    });
</script>

@include('system.admin_footer')
