
@include('system.admin_header')

                <div class="path">
                    <a href='{{ route('cms.dashboard.index') }}'></a>
                </div>

                <div class="clear"></div>
                <br>

                <form action="{{ route('cms.users.create') }}" method="post">
                    @csrf
                    <input type="submit" class="save"  value="Add user">
                </form>

               <!-- <form action="{ { url('/cms/users/save') } }" method="post">
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
                                    <a href="{{ route('cms.users.edit',$item) }}">
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

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var deleteLinks = document.querySelectorAll('.js_delete_user');
        deleteLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                const url = "{{ route('cms.users.delete', ['id'=>'___ID___']) }}";
                var itemId = link.getAttribute('data-id');
                if (confirm('Are you sure you want to delete the user?')) {
                    fetch(url.replace('___ID___', itemId), {
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
