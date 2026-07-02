
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

                    <a href='/cms/users'>All Users</a>

                </div>
                <div class="clear"></div>
                <br>


                <form action="{{ url('/cms/users/save/'.$user_edit->id ) }}" method="post">
                    @csrf
                    @method('PUT')

                    <br>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <table cellspacing="0" cellpadding="0" class="list">

                        <tr>
                            <td>Name:</td>
                            <td>
                                <input name="name" class="big" value="{{ old('name', $user_edit->name) }}">
                                @error('name')
                                <div class="error">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <td>Email:</td>
                            <td>
                                <input name="email" class="big" value="{{ old('email', $user_edit->email) }}">
                                @error('email')
                                <div class="error">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <td>Password (new):</td>
                            <td>
                                <input name="password" type="password" class="big" value="">
                            </td>
                        </tr>
                        <tr>
                            <td>Password (confirm):</td>
                            <td>
                                <input name="password_confirmation" type="password" class="big" value="">
                            </td>
                        </tr>

                        <tr>
                            <td>Admin:</td>
                            <td>
                                <select name="is_admin">
                                    <option value="1" {{ old('is_admin', $user_edit->is_admin) == 1 ? "selected='selected'" : '' }}>
                                        YES
                                    </option>
                                    <option value="0" {{ old('is_admin', $user_edit->is_admin) == 0 ? "selected='selected'" : '' }}>
                                        NO
                                    </option>
                                </select>
                            </td>
                        </tr>

                    </table>

                    <br>
                    <input type="submit" class="save" value="Save">

                </form>
            </div>
        </td>
    </tr>
</table>

@include('system.admin_footer')
