<div id="seoblock">
    <fieldset><legend>SEO:</legend>
        Title:<br>
        <textarea class="bigtxt" name="seo_title_s">@if (!empty($page->seo_title)){{ $page->seo_title }}@endif</textarea><br><br>
        Description:<br>
        <textarea class="bigtxt" name="seo_description_s">@if (!empty($page->seo_description)){{ $page->seo_description }}@endif</textarea><br><br>
        Keywords:<br>
        <textarea class="bigtxt" name="seo_keywords_s">@if (!empty($page->seo_keywords)){{ $page->seo_keywords }}@endif</textarea><br><br>
    </fieldset>
</div>