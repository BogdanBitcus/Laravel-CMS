<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>LaravelCMS</title>
    <link rel="stylesheet" href="/css/cms/cms.css">
</head>
<body>
<table style='width:100%;height:100%;' cellspacing="0" cellpadding="0">
    <tr>
        <td colspan='2' style='height:50px;'>
            <div id="header">
                <div id="lh">
                    <div class="tt">LaravelCMS</div>
                </div>
                <div id="rh" style='padding:0px;'>
                    <div style='text-align:right;'>Hello, <b>{{ $user->name }}</b></div>
                    <a href="/" target="_blank" class="site">Public View</a>
                    <a href="/cms/logout" class="exit">Log Out</a>
                </div>
                <div class="clear"></div>
            </div>
        </td>
    </tr>
    <tr>
        <td class="menu" valign="top" align="center">
            <!--<div class="lang">
                <a href="/cms/edit/1/" class="a">en</a>
                <a href="/cms/edit/1/ua" class="n">ua</a>
                <div class="clear"></div>
            </div>-->

            <table cellspacing="0" cellpadding="0" class="addmod">
                <tr><th>Modules</th></tr>
                <tr><td><a href="/cms/edit/1">Dashboard</a></td></tr>
                <tr><td><a href="/_s/types.php">Структура</a></td></tr>
                <tr><td><a href="/_s/l_langs.php">Переклади</a></td></tr>
                <tr><td><a href="/_s/l_adm.php">Користувачі CMS</a></td></tr>
            </table>
        </td>
        <td class="content" valign="top">
            <div class="main">
                <div class="path">

                    <a href='/en/edit/1/'>page name</a>

                </div>
                <div class="clear"></div>
                <br>

                <form action="{{ url('/cms/save/'.$page->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="1">
                    <input type="hidden" name="lang" value="en">

                    Title:<br />
                    <textarea name="name_s" class="bigtxt">@if (!empty($page->name)){{ json_decode($page->name)->en ?? '' }}@endif</textarea>






                    <div style="padding: 10px 0;">
                        <div id="infoblock_a" class="display">Edit content</div>
                        <div id="seoblock_a" class="display">Edit SEO</div>
                        <div class="clear"></div>
                    </div>
                    <div id="infoblock">
                        <fieldset><legend>Content:</legend>
                            Text:<br>
                            <textarea cols='50' rows='3' id="text_s" name="text_s">@if (!empty($page->text)){{ json_decode($page->text)->en ?? '' }}@endif</textarea>
                        </fieldset>
                    </div>
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




                    <br>
                    <table cellspacing="0" cellpadding="0" class="list">
                        <tr>
                            <th>Show</th>
                            <th>Position</th>
                            <th>URL</th>
                            <th>Name</th>
                            <th>&nbsp;</th>
                            <th>Template</th>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td colspan="100">
                                <a href="/cms/dashboard/addpage/<?echo urlencode($_SERVER['REQUEST_URI'])?>" onclick="return go(this.href)"><img src="/img/cms/doc-plus.gif" style="margin: 0 0 -2px;" alt="Добавить" > Добавить в начало списка</a>
                            </td>
                        </tr>


                        <tr class='tr_hide'>
                            <td valign='middle'>
                                <input type="hidden" name="enable_en_s_1" value="0" >
                                <input type="checkbox" class="img" name="enable_en_s_1" value="1" checked="checked">
                            </td>
                            <td nowrap>
                                <input name="position[1]" value="10" class="small" onchange="ch=1">
                                <input style="margin: 0 0 -7px;" type="image" class="img" src="/img/cms/top.gif" title="вверх" onclick="move(1,-11)">
                                <input style="margin: 0 0 -7px;" type="image" class="img" src="/img/cms/bottom.gif" title="вниз" onclick="move(1,+11)">
                            </td>
                            <td>
                                <input name="url_s_1" class="medium" value="/url/" onchange="ch=1;document.forms.admingu.addr_s_1.value='';" onblur="val_1=document.forms.admingu.url_s_1.value;val2_1 = val_1.toLowerCase();document.forms.admingu.url_s_1.value=val2_1;   ch=1;document.forms.admingu.addr_s_1.value='';">
                                <input name="addr_s_1" type="hidden" value="url">
                            </td>
                            <td>
                                <input name="name_en_s_1" class="big" value="name page id" >
                            </td>
                            <td nowrap>
                                <a href="/en/edit/1/?relocate=/en/edit/1/" onclick="return go(this.href)"><img style="margin: 0 0 -3px;" src="/img/cms/edit.gif" alt="редактировать" ></a>
                                <a href="/_s/del.php?id=1" onclick="return confirm('Delete item? WARNING!!! All child/nested elements will be removed!') ? go(this.href) : false">
                                    <img style="margin: 0 0 -3px;" src="/img/cms/del.gif" alt="Delete">
                                </a>
                            </td>
                            <td nowrap='nowrap'>
                                <select class="select" name="type_s_1" id="type_1" onchange="ch=1;" disabled >
                                    <option value="1" selected="selected">type name</option>
                                </select>

                                <a href="#" id="type_enable_1" onclick="document.getElementById('type_1').disabled=false;document.getElementById('type_enable_1').style.display='none';return false;"><img style="margin: 0 0 -3px;" src="/img/cms/block.gif" border="0" alt="Изменить тип шаблона страницы" class="cursor"></a>
                            </td>
                        </tr>



                        <tr>
                            <td>&nbsp;</td>
                            <td colspan="100">

                                <a href="/_s/n_list_item.php?table=pages&amp;pos=999999&amp;parent=0&amp;relocate=<?echo urlencode($_SERVER['REQUEST_URI'])?>" onclick="return go(this.href)"><img src="/img/cms/doc-plus.gif" style="margin: 0 0 -2px;" alt='Add' > Add to the end</a>

                            </td>
                        </tr>
                    </table>

                    <br><input type="submit" class="save" value="Save">

                </form>
            </div>
        </td>
    </tr>
</table>
<script type="text/javascript" src="/js/cms/cms.js"></script>
</body>
</html>