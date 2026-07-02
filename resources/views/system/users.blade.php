
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
        <!--<div class="lang">
                <a href="/cms/edit/" class="a">en</a>
                <a href="/cms/edit//ua" class="n">ua</a>
                <div class="clear"></div>
            </div>-->

            <table cellspacing="0" cellpadding="0" class="addmod">
                <tr><th>Modules</th></tr>
                <tr><td><a href="/cms/dashboard" class="">Dashboard</a></td></tr>
                <tr><td><a href="/cms/templates">Templates</a></td></tr>
                <!--<tr><td><a href="/_s/l_langs.php">Переклади</a></td></tr>-->
                <tr><td><a href="/cms/users" class='bold'>CMS users</a></td></tr>
            </table>
        </td>
        <td class="content" valign="top">
            <div class="main">
                <div class="path">

                    <a href='/cms/dashboard'></a>

                </div>
                <div class="clear"></div>
                <br>

                <form action="{{ url('/cms/users/create') }}" method="post">
                    @csrf
                    <input type="submit" class="save"  value="Add user">
                </form>

               <!-- <form action="{{ url('/cms/users/save') }}" method="post">
                    @csrf
                    @method('PUT')
                -->
                    <br>
                    <table cellspacing="0" cellpadding="0" class="list">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <!--<th>Password</th>-->
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>


                        @foreach ($list as $item)
                            <tr>
                                <td style="border-bottom: 1px solid black;">
                                    <!--<textarea name="name_{{ $item->id }}" class="big">{{ $item->name }}</textarea>-->
                                    {{ $item->name }}
                                </td>
                                <td style="border-bottom: 1px solid black;">
                                    <!--<textarea name="email_{{ $item->id }}" class="big">{{ $item->email }}</textarea>-->
                                        {{ $item->email }}
                                </td>
                                <!--<td>
                                    <textarea name="password_{{ $item->id }}" class="big">{{ $item->password }}</textarea>
                                </td>-->
                                <td style="border-bottom: 1px solid black;">
                                    <a href="/cms/users/{{ $item->id }}">
                                        <img style="margin: 0 0 -3px;" src="/img/cms/edit.gif" alt="Edit" >
                                    </a>
                                </td>
                                <td style="border-bottom: 1px solid black;">
                                    @if($item->id > 1)
                                        <img style="margin: 0 0 -3px;" src="/img/cms/del.gif" class="img js_delete_user" data-id="{{ $item->id }}" alt="Delete" title="Delete">
                                    @endif
                                </td>
                            </tr>
                        @endforeach



                    </table>

                    <br>
                    <!--<input type="submit" class="save" value="Save">-->

                <!--</form>-->
            </div>
        </td>
    </tr>
</table>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var deleteLinks = document.querySelectorAll('.js_delete_user');
        deleteLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {

                var itemId = link.getAttribute('data-id');
                if (confirm('Are you sure you want to delete the user?')) {
                    fetch('/cms/users/delete/' + itemId, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        } else {
                            throw new Error('Failed to delete user');
                        }
                    })
                    .then(data => {
                        window.location.reload();
                    })
                    .catch(function(error) {
                        console.error('Error deleting item:', error);
                    });
                }
            });
        });
    });
</script>

@include('system.admin_footer')
