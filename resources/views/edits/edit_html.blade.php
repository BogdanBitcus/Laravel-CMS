@include('system.admin_header')

                <div class="path">
                    <a href='/en/edit/1/'>page name</a>
                    <a href='/en/edit/1/'>page name 2</a>
                </div>
                <div class="clear"></div>
                <br>

                <form action="{{ route('cms.page.update', $page) }}" method="post">
                    @csrf
                    @method('PUT')



                    Title:<br />
                    <input name="name" class="bigtxt" value="@if (!empty($page->name)){{ json_decode($page->name)->en ?? '' }}@endif">
                    <br /><br />


                    Show / Hide:<br />
                    <select name="show_s" class="select">
                        <option vajlue="1">SHOW</option>
                        <option vajlue="0">HIDE</option>
                    </select>
                    <br /><br />


                    Url:<br />
                    <input name="url" class="medium" value="{{ $page->url ? $page->url : $page->id }}" onblur="check_url(this,{{ $page->id }});">
                    <input type="hidden" name="addr" value="{{ $page->addr }}">
                    <br /><br />


                    Date:<br />
                    <input name="date" class="medium" value="{{ $page->date }}" class="date">
                    <br /><br />


                    Image:<br />
                    <div class='flex'>
                        <div><input name="image" class="medium" value="{{ $page->image }}" class="image"></div>
                        <div><img src="/img/cms/no_image_icon_50.png" ></div>
                        <div><a class="display">Select Image</a></div>
                    </div>
                    <br /><br />


                    Text:<br />
                    <textarea name="text" class="smtxt">@if (!empty($page->text)){{ json_decode($page->text)->en ?? '' }}@endif</textarea>
                    <br /><br />



                    <div style="padding: 10px 0;">
                        <!--<div id="infoblock_a" class="display">Edit content</div>-->
                        <div id="seoblock_a" class="display">Edit SEO</div>
                        <div class="clear"></div>
                    </div>
                    <!--<div id="infoblock">
                        <fieldset><legend>Content:</legend>
                            Text:<br>
                            <textarea cols='50' rows='3' id="text_s" name="text_s">@if (!empty($page->text)){{ json_decode($page->text)->en ?? '' }}@endif</textarea>
                        </fieldset>
                    </div>-->
                    <div id="seoblock">
                        <fieldset><legend>SEO:</legend>
                            Title:<br>
                            <textarea class="bigtxt" name="seo_title_s">@if (!empty($page->seo_title)){{ json_decode($page->seo_title)->en ?? '' }}@endif</textarea><br><br>
                            Description:<br>
                            <textarea class="bigtxt" name="seo_description_s">@if (!empty($page->seo_description)){{ json_decode($page->seo_description)->en ?? '' }}@endif</textarea><br><br>
                            Keywords:<br>
                            <textarea class="bigtxt" name="seo_keywords_s">@if (!empty($page->seo_keywords)){{ json_decode($page->seo_keywords)->en ?? '' }}@endif</textarea><br><br>
                        </fieldset>
                    </div>

                    <br><br><input type="submit" class="save" value="Save">

                </form>

@include('system.admin_footer')