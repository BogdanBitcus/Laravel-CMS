
@include('system.admin_header')

<table style='width:100%;height:100%;' cellspacing="0" cellpadding="0">
    <tr>
        <td colspan='2' style='height:50px;'>
            <div id="header">
                <div id="lh">
                    <div class="tt">LaravelCMS</div>
                </div>
                <div id="rh" style='padding:0px;'>
                    <div style='text-align:right;'>Hello, <b>{{ $user->name }}</b></div>
                    <a href="/" target="_blank" class="site">Public View</a>
                    <a href="/cms/logout" class="exit">Log Out</a>
                </div>
                <div class="clear"></div>
            </div>
        </td>
    </tr>
    <tr>
        <td class="menu" valign="top" align="center">
            <div class="lang">
                <a href="/cms/edit/{{ $page->id }}" class="a">en</a>
                <a href="/cms/edit/{{ $page->id }}/ua" class="n">ua</a>
                <div class="clear"></div>
            </div>

            <table cellspacing="0" cellpadding="0" class="addmod">
                <tr><th>Modules</th></tr>
                <tr><td><a href="/cms/dashboard" class="{{ $page->id==1 ? 'bold' : '' }}">Dashboard</a></td></tr>
                <tr><td><a href="/cms/templates">Templates</a></td></tr>
                <tr><td><a href="/_s/l_langs.php">Переклади</a></td></tr>
                <tr><td><a href="/_s/l_adm.php">Користувачі CMS</a></td></tr>
            </table>
        </td>
        <td class="content" valign="top">
            <div class="main">
                <div class="path">

                    <a href='/cms/dashboard'>{{ $page->name }}</a>

                </div>
                <div class="clear"></div>
                <br>

                <form action="{{ url('/cms/dashboard/addpage') }}" method="post">
                    @csrf
                    <input type="submit" class="save"  value="Add section/page">
                </form>

                <form action="{{ url('/cms/dashboard/save') }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="1">
                    <input type="hidden" name="lang" value="en">






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

                        <tr>
                            <td>
                                <input type="checkbox" name="show_{{ $page->id }}" value="1" {{ $page->show == 1 ? 'checked="checked"' : '' }}>
                            </td>
                            <td>
                                <input name="position[{{ $page->id }}]" value="{{ $page->position }}" class="small">
                                <img style="margin: 0 0 -7px;" src="/img/cms/top.gif" alt="Up" title="Up" onclick="move({{ $page->id }},-11)"><img style="margin: 0 0 -7px;" src="/img/cms/bottom.gif" alt="Down" title="Down" onclick="move({{ $page->id }},+11)">
                            </td>
                            <td>
                                <input name="url_{{ $page->id }}" class="medium" value="/" disabled="disabled" onblur="check_url(this,{{ $page->id }});">
                            </td>
                            <td>
                                <textarea name="name_{{ $page->id }}" class="big">{{ $page->name }}</textarea>
                            </td>
                            <td>
                                <a href="/cms/edit/{{ $page->id }}">
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



                        @foreach ($list as $item)
                        <tr>
                            <td>
                                <input type="checkbox" name="show_{{ $item->id }}" value="1" {{ $item->show == 1 ? 'checked="checked"' : '' }}>
                            </td>
                            <td>
                                <input name="position[{{ $item->id }}]" value="{{ $item->position }}" class="small">
                                <img style="margin: 0 0 -7px;" src="/img/cms/top.gif" alt="Up" title="Up" onclick="move({{ $item->id }},-11)"><img style="margin: 0 0 -7px;" src="/img/cms/bottom.gif" alt="Down" title="Down" onclick="move({{ $item->id }},+11)">
                            </td>
                            <td>
                                <input name="url_{{ $item->id }}" class="medium" value="{{ $item->url ? $item->url : $item->id }}" onblur="check_url(this,{{ $item->id }});">
                                <input type="hidden" name="addr_{{ $item->id }}" value="{{ $item->addr }}">
                            </td>
                            <td>
                                <textarea name="name_{{ $item->id }}" class="big">{{ $item->name }}</textarea>
                            </td>
                            <td>
                                <a href="/cms/edit/{{ $item->id }}">
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
            </div>
        </td>
    </tr>
</table>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var deleteLinks = document.querySelectorAll('.js_delete_page');
        deleteLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                //event.preventDefault(); // Відміняємо стандартну дію посилання
                var itemId = link.getAttribute('data-id');
                if (confirm('Delete item? WARNING! All child/nested elements will be removed!')) {
                    fetch('/cms/dashboard/delete/' + itemId, {
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
