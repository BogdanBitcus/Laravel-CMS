
@include('system.admin_header')

                <div class="path">
                    <a href='{{ route('cms.users.index') }}'>All Users</a>
                </div>

                <div class="clear"></div>
                <br>

                <form action="{{ route('cms.users.update', $user_edit) }}" method="post">
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
                                <input name="name" class="big @error('name') is-invalid @enderror" value="{{ old('name', $user_edit->name) }}">
                                @error('name')
                                <div class="error">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <td>Email:</td>
                            <td>
                                <input name="email" class="big @error('email') is-invalid @enderror" value="{{ old('email', $user_edit->email) }}">
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

                        <!--<tr>
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
                        </tr>-->

                    </table>

                    <br>
                    <input type="submit" class="save" value="Save">

                </form>

@include('system.admin_footer')