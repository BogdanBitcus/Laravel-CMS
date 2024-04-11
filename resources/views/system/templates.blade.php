
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
                <a href="/cms/dashboard" class="a">en</a>
                <a href="/cms/dashboard" class="n">ua</a>
                <div class="clear"></div>
            </div>

            <table cellspacing="0" cellpadding="0" class="addmod">
                <tr><th>Modules</th></tr>
                <tr><td><a href="/cms/dashboard">Dashboard</a></td></tr>
                <tr><td><a href="/cms/templates" class="bold">Templates</a></td></tr>
                <tr><td><a href="/_s/l_langs.php">Переклади</a></td></tr>
                <tr><td><a href="/_s/l_adm.php">Користувачі CMS</a></td></tr>
            </table>
        </td>
        <td class="content" valign="top">
            <div class="main">

                <br>
                <form action="{{ url('/cms/templates/add') }}" method="post">
                    @csrf
                    <input type="submit" class="save"  value="Add template">
                </form>
                <br>

                <ul style="background: white;padding:10px 20px 20px;border-radius: 10px;">
                    @foreach($templates as $type)
                        <li style="padding: 5px 0 0 0;">
                            <a href="/cms/templates/{{ $type->id }}">
                                <b>{{ $type->name }}</b>&nbsp;<img src="/img/cms/edit.gif" alt="Edit" style="margin: 0px 5px -4px 5px;">
                            </a>

                            <img src="/img/cms/del.gif" alt="Delete" class="js_delete_template" data-id="{{ $type->id }}"  style="margin: 0px 0px -4px 0px;">

                            @if(count( $type->children ))
                                @include('system.partials.templates_children', [ 'children' => $type->children ])
                            @endif
                        </li>
                    @endforeach
                </ul>

            </div>
        </td>
    </tr>
</table>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var deleteLinks = document.querySelectorAll('.js_delete_template');
        deleteLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                //event.preventDefault(); // Відміняємо стандартну дію посилання
                var itemId = link.getAttribute('data-id');
                if (confirm('Delete item?')) {
                    fetch('/cms/templates/delete/' + itemId, {
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
                        throw new Error('Failed to delete template');
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