@include('system.admin_header')

@include('system.partials.breadcrumbs')

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
                    <input name="name" class="bigtxt" value="@if (!empty($page->name)){{ old('name', $page->name) }}@endif">
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
                    <input name="slug" class="medium" value="{{ old('slug', $page->slug ? $page->slug : $page->id) }}" onblur="check_url(this,{{ $page->id }});">
                    <input type="hidden" name="addr" value="{{ old('addr', $page->addr) }}">
                    @error('slug')
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <br /><br />


                    Date:<br />
                    <input name="date" class="medium datepicker" value="{{ old('date', $page->date) }}">
                    @error('date')
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <br /><br />


                    Image:<br />
                    <div class='flex'>
                        <div><input type="text" name="image" id="path_image" class="medium" value="{{ old('image', $page->image) }}"></div>
                        <div><img src="/img/cms/no_image_icon_50.png" id="img_image" style="max-height: 50px;max-width: 50px;" ></div>
                        <div><a class="display" onclick="openCustomRoxy('image')">Select Image</a></div>
                    </div>
                    @error('image')
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <br /><br />


                    Image (mob):<br />
                    <div class='flex'>
                        <div><input type="text" name="mobile_image" id="path_mobile_image" class="medium image" value="{{ old('mobile_image', $page->mobile_image) }}"></div>
                        <div><img src="/img/cms/no_image_icon_50.png" id="img_mobile_image" style="max-height: 50px;max-width: 50px;" ></div>
                        <div><a class="display" onclick="openCustomRoxy('mobile_image')">Select Image</a></div>
                    </div>
                    @error('mobile_image')
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <br /><br />


                    Content:<br />
                    <textarea name="content" class="smtxt js_tinymce">{!! old('content', $page->content) !!}</textarea>
                    @error('content')
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <br /><br />



                    <!-- CUSTOM FIELDS BEGIN -->

                    Custom short text:<br />
                    <input name="custom_name" class="bigtxt" value="{{ old('custom_name', $page->custom_name) }}">
                    @error('custom_name')
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <br /><br />

                    Custom Image:<br />
                    <div class='flex'>
                        <div><input type="text" name="custom_image" id="path_custom_image" class="medium image" value="{{ old('custom_image', $page->custom_image) }}"></div>
                        <div><img src="/img/cms/no_image_icon_50.png" id="img_custom_image" style="max-height: 50px;max-width: 50px;" ></div>
                        <div><a class="display" onclick="openCustomRoxy('custom_image')">Select Image</a></div>
                    </div>
                    @error('custom_image')
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <br /><br />

                    Custom Content:<br />
                    <textarea name="custom_content" class="smtxt js_tinymce">{!! old('custom_content', $page->custom_content) !!}</textarea>
                    @error('custom_content')
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <br /><br />

                    <!-- CUSTOM FIELDS END -->




                    <div style="padding: 10px 0;">
                        <div id="seoblock_a" class="display">Edit SEO</div>
                        <div class="clear"></div>
                    </div>
                    @include('system.partials.seoblock')


                    <br><br><input type="submit" class="save" value="Save">

                </form>

@include('system.admin_footer')