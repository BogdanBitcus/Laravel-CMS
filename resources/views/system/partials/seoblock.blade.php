<div id="seoblock">
    <fieldset><legend>SEO:</legend>
        Title:<br>
        <textarea class="bigtxt" name="seo_title">@if (!empty($page->seo_title)){{ old('seo_title', $page->seo_title) }}@endif</textarea>
        @error('seo_title')
        <div class="error">{{ $message }}</div>
        @enderror
        <br><br>

        Description:<br>
        <textarea class="bigtxt" name="seo_description">@if (!empty($page->seo_description)){{ old('seo_description', $page->seo_description) }}@endif</textarea>
        @error('seo_description')
        <div class="error">{{ $message }}</div>
        @enderror
        <br><br>

        Keywords:<br>
        <textarea class="bigtxt" name="seo_keywords">@if (!empty($page->seo_keywords)){{ old('seo_keywords', $page->seo_keywords) }}@endif</textarea>
        @error('seo_keywords')
        <div class="error">{{ $message }}</div>
        @enderror
        <br><br>

    </fieldset>
</div>