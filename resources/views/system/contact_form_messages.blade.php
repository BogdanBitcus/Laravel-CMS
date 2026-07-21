
@include('system.admin_header')

@include('system.partials.pagination')


    <table cellspacing="0" cellpadding="0" class="list">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Status</th>
            <th>Delete</th>
        </tr>

        @foreach ($list as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->message }}</td>
                <td><span style="color:@if($item->status=='new') orange @elseif($item->status=='sent') green @elseif($item->status=='error') red @endif" >{{ $item->status }}</span></td>
                <td>
                    <img style="margin: 0 0 -3px;" src="/img/cms/del.gif" class="img js_delete_message" data-id="{{ $item->id }}" alt="Delete" title="Delete">
                </td>
            </tr>
        @endforeach

    </table>


@include('system.partials.pagination')


<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var deleteLinks = document.querySelectorAll('.js_delete_message');
        deleteLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                var url = "{{ route('cms.message.delete', ['message'=>'___ID___']) }}";
                var itemId = link.getAttribute('data-id');
                if (confirm('Delete item?')) {
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
                            throw new Error('Failed to delete message');
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
