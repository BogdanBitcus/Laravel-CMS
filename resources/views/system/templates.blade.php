
@include('system.admin_header')

                <br>
                <form action="{{ route('cms.templates.add') }}" method="post">
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


<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var deleteLinks = document.querySelectorAll('.js_delete_template');
        deleteLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                //event.preventDefault(); // Відміняємо стандартну дію посилання
                var itemId = link.getAttribute('data-id');
                if (confirm('Delete item?')) {
                    const url = "{{ route('cms.templates.delete', ['id' => '__ID__']) }}";
                    fetch(url.replace('__ID__', itemId), {
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
                            throw new Error('Failed to delete template');
                        }
                    })
                    .then(data => {
                        //alert(data.message); // it is not necessary
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