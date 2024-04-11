
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

                <FORM action="{{ url('/cms/templates/save') }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type=hidden name=id value="{{ $template->id }}">
                    <fieldset>
                        <legend>Settings:</legend>
                        Name: <input name='name' value="{{ $template->name }}">

                        <br><br>
                        Parent:
                        <select name='parent'>
                            <option value=''>-</option>

                        </select>

                        <br><br>
                        Edit template:
                        <select name='admin_tpl'>
                            <option value=''>-</option>
                            @foreach ($admin_tpl as $edit)
                                <option value='{{ $edit['value'] }}' {{ $edit['selected'] }}>{{ $edit['filename'] }}</option>
                            @endforeach
                        </select>

                        <br><br>
                        View template:
                            <select name='view_tpl'>
                            <option value=''>-</option>
                            @foreach ($view_tpl as $view)
                                <option value='{{ $view['value'] }}' {{ $view['selected'] }}>{{ $view['filename'] }}</option>
                            @endforeach
                        </select>

                    </fieldset>
                    <br>
                    <input type=submit value='Save'>
                </form>




            </div>
        </td>
    </tr>
</table>


@include('system.admin_footer')