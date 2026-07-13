@include('system.admin_header')

@include('system.partials.breadcrumbs')

                <form action="{{ route('cms.page.update', $page) }}" method="post">
                    @csrf
                    @method('PUT')


                    Title:<br />
                    <input name="name" class="bigtxt" value="@if (!empty($page->name)){{ $page->name }}@endif">
                    <br /><br />


                    Show / Hide:<br />
                    <select name="show_s" class="select">
                        <option vajlue="1" @if($page->show==1) selected="selected"@endif>SHOW</option>
                        <option vajlue="0" @if($page->show==0) selected="selected"@endif>HIDE</option>
                    </select>
                    <br /><br />


                    Url:<br />
                    <input name="url" class="medium" value="{{ $page->url ? $page->url : $page->id }}" onblur="check_url(this,{{ $page->id }});">
                    <input type="hidden" name="addr" value="{{ $page->addr }}">
                    <br /><br />


                    Date:<br />
                    <input name="date" class="medium datepicker" value="{{ $page->date }}">
                    <br /><br />


                    Image:<br />
                    <div class='flex'>
                        <div><input type="text" name="image" id="path_image" class="medium" value="{{ $page->image }}"></div>
                        <div><img src="/img/cms/no_image_icon_50.png" id="img_image" style="max-height: 50px;max-width: 50px;" ></div>
                        <div><a class="display" onclick="openCustomRoxy('image')">Select Image</a></div>
                    </div>
                    <br /><br />


                    Image (mob):<br />
                    <div class='flex'>
                        <div><input type="text" name="image_mob" id="path_image_mob" class="medium image" value="{{ $page->image_mob }}"></div>
                        <div><img src="/img/cms/no_image_icon_50.png" id="img_image_mob" style="max-height: 50px;max-width: 50px;" ></div>
                        <div><a class="display" onclick="openCustomRoxy('image_mob')">Select Image</a></div>
                    </div>
                    <br /><br />


                    Text:<br />
                    <textarea name="text" class="smtxt js_tinymce">@if (!empty($page->text)){{ $page->text }}@endif</textarea>
                    <br /><br />



                    <div style="padding: 10px 0;">
                        <div id="seoblock_a" class="display">Edit SEO</div>
                        <div class="clear"></div>
                    </div>
                    @include('system.partials.seoblock')


                    <br><br><input type="submit" class="save" value="Save">

                </form>

@include('system.admin_footer')