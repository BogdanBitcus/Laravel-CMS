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
        <div><img src="{{ old('image', $page->image ?: '/img/cms/no_image_icon_50.png') }}" id="img_image" style="max-height: 50px;max-width: 50px;" ></div>
        <div><a class="display" onclick="openCustomRoxy('image')">Select Image</a></div>
    </div>
    @error('image')
    <div class="error">{{ $message }}</div>
    @enderror
    <br /><br />

    <!-- CUSTOM FIELDS BEGIN -->

    Short anons text:<br />
    <textarea name="custom_anons" class="smtxt js_tinymce___" style="height: 50px;">{!! old('custom_anons', $page->custom_anons) !!}</textarea>
    @error('custom_anons')
    <div class="error">{{ $message }}</div>
    @enderror
    <br /><br />

    <!-- CUSTOM FIELDS END -->


    New text:<br />
    <textarea name="content" class="smtxt js_tinymce">{!! old('content', $page->content) !!}</textarea>
    @error('content')
    <div class="error">{{ $message }}</div>
    @enderror
    <br /><br />



    <div style="padding: 10px 0;">
        <div id="seoblock_a" class="display">Edit SEO</div>
        <div class="clear"></div>
    </div>
    @include('system.partials.seoblock')


    <br><br><input type="submit" class="save" value="Save">

</form>

@include('system.admin_footer')