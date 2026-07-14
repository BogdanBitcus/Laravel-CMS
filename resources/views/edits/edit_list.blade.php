@include('system.admin_header')

@include('system.partials.breadcrumbs')


                <form action="{{ route('cms.page.create', $page) }}" method="post" id="js_add_page">
                    @csrf
                    <input type="hidden" name="position" id="js_add_page_position" value="1">
                    <!--<input type="submit" class="save"  value="Add page">-->
                </form>


                <form action="{{ route('cms.page.update', $page) }}" method="post">
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


                    Title:<br />
                    <textarea name="name" class="bigtxt">@if (!empty($page->name)){{ old('name', $page->name) }}@endif</textarea>
                    @error('name')
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <br /><br />


                    Published / Hide:<br />
                    <select name="published" class="select">
                        <option value="1" @selected($page->published)>PUBLISHED</option>
                        <option value="0" @selected(!$page->published)>HIDE</option>
                    </select>
                    <br /><br />


                    Url:<br />
                    <input name="slug" class="medium" value="{{ $page->slug ? $page->slug : $page->id }}" onblur="check_url(this,{{ $page->id }});">
                    <input type="hidden" name="addr" value="{{ $page->addr }}">
                    @error('slug')
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <br /><br />


                    <div style="padding: 10px 0;">
                        <div id="infoblock_a" class="display">Edit content</div>
                        <div id="seoblock_a" class="display">Edit SEO</div>
                        <div class="clear"></div>
                    </div>
                    @include('system.partials.infoblock')
                    @include('system.partials.seoblock')



                    <br>
                    <table cellspacing="0" cellpadding="0" class="list">
                        <tr>
                            <th>Published</th>
                            <th>Position</th>
                            <th>URL</th>
                            <th>Name</th>
                            <th>&nbsp;</th>
                            <th>Template</th>
                        </tr>

                        <tr>
                            <td colspan="100">
                                <a href="javascript:document.getElementById('js_add_page_position').value=1;document.getElementById('js_add_page').submit();"><img src="/img/cms/doc-plus.gif" style="margin: 0 5px -2px;" alt="Add">Add page to begin</a>
                            </td>
                        </tr>


                        @foreach ($list as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" name="published_{{ $item->id }}" value="1" {{ $item->published == 1 ? 'checked="checked"' : '' }}>
                                </td>
                                <td>
                                    <input name="position[{{ $item->id }}]" value="{{ $item->position }}" class="small">
                                    <img style="margin: 0 0 -7px;" src="/img/cms/top.gif" alt="Up" title="Up" onclick="move({{ $item->id }},-11)"><img style="margin: 0 0 -7px;" src="/img/cms/bottom.gif" alt="Down" title="Down" onclick="move({{ $item->id }},+11)">
                                </td>
                                <td>
                                    <input name="slug_{{ $item->id }}" class="medium" value="{{ $item->slug ? $item->slug : $item->id }}" onblur="check_url(this,{{ $item->id }});">
                                    <input type="hidden" name="addr_{{ $item->id }}" value="{{ $item->addr }}">
                                </td>
                                <td>
                                    <textarea name="name_{{ $item->id }}" class="big">{{ $item->name }}</textarea>
                                </td>
                                <td>
                                    <a href="{{ route('cms.page.index', $item) }}">
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

                        <tr>
                            <td colspan="100">
                                <a href="javascript:document.getElementById('js_add_page_position').value=99999;document.getElementById('js_add_page').submit();"><img src="/img/cms/doc-plus.gif" style="margin: 0 5px -2px;" alt="Add">Add page to end</a>
                            </td>
                        </tr>

                    </table>

                    <br><input type="submit" class="save" value="Save">

                </form>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var deleteLinks = document.querySelectorAll('.js_delete_page');
        deleteLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                //event.preventDefault(); // Відміняємо стандартну дію посилання
                var url = "{{ route('cms.page.delete', ['page'=>'___ID___']) }}";
                var itemId = link.getAttribute('data-id');
                if (confirm('Delete item? WARNING! All child/nested elements will be removed!')) {
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