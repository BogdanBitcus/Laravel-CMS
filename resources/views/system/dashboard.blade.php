<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>LaravelCMS</title>
    <link rel="stylesheet" href="/css/cms/cms.css">
</head>
<body>
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
                <tr><td><a href="/_s/types.php">Структура</a></td></tr>
                <tr><td><a href="/_s/l_langs.php">Переклади</a></td></tr>
                <tr><td><a href="/_s/l_adm.php">Користувачі CMS</a></td></tr>
            </table>
        </td>
        <td class="content" valign="top">
            <div class="main">
                <div class="path">

                    <a href='/en/edit/1/'>page name</a>

                </div>
                <div class="clear"></div>
                <br>

                <form action="{{ url('/cms/dashboard/addpage') }}" method="post">
                    @csrf
                    <input type="submit" class="save"  value="Add section/page">
                </form>

                <form action="{{ url('/cms/save/'.$page->id) }}" method="post">
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
                                <input type="checkbox" name="show_s_{{ $page->id }}" value="1" {{ $page->show == 1 ? 'checked="checked"' : '' }}>
                            </td>
                            <td>
                                <input name="position[{{ $page->id }}]" value="{{ $page->position }}" class="small">
                                <img style="margin: 0 0 -7px;" src="/img/cms/top.gif" alt="Up" title="Up" onclick="move({{ $page->id }},-11)"><img style="margin: 0 0 -7px;" src="/img/cms/bottom.gif" alt="Down" title="Down" onclick="move({{ $page->id }},+11)">
                            </td>
                            <td>
                                <input name="url_s_{{ $page->id }}" class="medium" value="/" disabled="disabled" onblur="check_url(this);">
                            </td>
                            <td>
                                <textarea name="name_s_{{ $page->id }}" class="big">{{ $page->name }}</textarea>
                            </td>
                            <td>
                                <a href="/cms/edit/{{ $page->id }}">
                                    <img style="margin: 0 0 -3px;" src="/img/cms/edit.gif" alt="Edit" >
                                </a>
                            </td>
                            <td>
                                <select class="select" name="type_s__{{ $page->id }}" id="type_{{ $page->id }}" disabled="disabled" >
                                    <option value="1" selected="selected">type name</option>
                                </select>

                                <a href="javascript:void(0);" id="type_enable_{{ $page->id }}" onclick="unlock_page_type({{ $page->id }});">
                                    <img style="margin: 0 0 -3px;" src="/img/cms/block.gif" alt="Change page type" class="cursor">
                                </a>
                            </td>
                        </tr>



                        @foreach ($list as $item)
                        <tr>
                            <td>
                                <input type="checkbox" name="show_s_{{ $item->id }}" value="1" {{ $item->show == 1 ? 'checked="checked"' : '' }}>
                            </td>
                            <td>
                                <input name="position[{{ $item->id }}]" value="{{ $item->position }}" class="small">
                                <img style="margin: 0 0 -7px;" src="/img/cms/top.gif" alt="Up" title="Up" onclick="move({{ $item->id }},-11)"><img style="margin: 0 0 -7px;" src="/img/cms/bottom.gif" alt="Down" title="Down" onclick="move({{ $item->id }},+11)">
                            </td>
                            <td>
                                <input name="url_s_{{ $item->id }}" class="medium" value="{{ $item->url ? $item->url : $item->id }}" onblur="check_url(this);">
                            </td>
                            <td>
                                <textarea name="name_s_{{ $item->id }}" class="big">{{ $item->name }}</textarea>
                            </td>
                            <td>
                                <a href="/cms/edit/{{ $item->id }}">
                                    <img style="margin: 0 0 -3px;" src="/img/cms/edit.gif" alt="Edit" title="Edit" >
                                </a>
                                <img style="margin: 0 0 -3px;" src="/img/cms/del.gif" class="img js_delete_page" data-id="{{ $item->id }}" alt="Delete" title="Delete">
                            </td>
                            <td>
                                <select class="select" name="type_s__{{ $item->id }}" id="type_{{ $item->id }}" disabled="disabled" >
                                    <option value="1" selected="selected">type name</option>
                                </select>

                                <a href="javascript:void(0);" id="type_enable_{{ $item->id }}" onclick="unlock_page_type({{ $item->id }});">
                                    <img style="margin: 0 0 -3px;" src="/img/cms/block.gif" alt="Change page type" class="cursor">
                                </a>
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
                    fetch('/cms/delete/' + itemId, {
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
                        alert(data.message);
                        window.location.reload();
                    }).catch(function(error) {
                        console.error('Error deleting item:', error);
                    });
                }
            });
        });
    });
</script>
<script type="text/javascript" src="/js/cms/cms.js"></script>
</body>
</html>