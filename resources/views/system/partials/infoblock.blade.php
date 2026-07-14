<div id="infoblock">
    <fieldset><legend>Content:</legend>
        Content:<br>
        <textarea cols='50' rows='3' id="content" name="content">@if (!empty($page->content)){{ old('content', $page->content) }}@endif</textarea>
        @error('content')
        <div class="error">{{ $message }}</div>
        @enderror
    </fieldset>
</div>