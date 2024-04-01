<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>LaravelCMS</title>
    <link rel="stylesheet" href="/css/cms.css">
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
                    <div style='text-align:right;padding:0px 0px 0px 0px;'>Вы вошли как: <b>Name User</b></div>
                    <a href="/en/url" target="_blank" class="site">Public View</a>
                    <a href="/cms/logout" class="exit">Log Out</a>
                </div>
                <div class="clear"></div>
            </div>
        </td>
    </tr>
    <tr>
        <td class="menu" valign="top" align="center">
            <div class="lang">
                <a href="/en/edit/1/" class="a">en</a>
                <a href="/ua/edit/1/" class="n">ua</a>
                <div class="clear"></div>
            </div>

            <table cellspacing="0" cellpadding="0" class="addmod">
                <tr><th>Модулі</th></tr>
                <tr><td><a href="/cms/dashboard">Контент</a></td></tr>
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

<form name="admingu" action="/_s/s_list.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="relocate" value="<?=$_SERVER['REQUEST_URI']?>">
    <input type="hidden" name="table" value="pages">
    <input type="hidden" name="id" value="111111">
    <input type="hidden" name="lang" value="en">

    Страничка:<br />
    <input name="name_en_s" value="name page" class="bigtxt">






    <div style="padding: 10px 0;">
        <div id="infoblock_a" class="display" onclick="showblock('infoblock');">Редактировать текстовую часть</div>
        <div id="seoblock_a" class="display" onclick="showblock('seoblock');">Редактировать SEO-блок</div>
        <div class="clear"></div>
    </div>
    <div id="infoblock" style="display:none;">
        <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table">
            <tr>
                <td>
                    <textarea cols='50' rows='5' id="html_en_s" name="html_en_s">text content</textarea>
                </td>
            </tr>
        </table>
    </div>
    <div id="seoblock" style="display:none;">
        <table cellspacing="0" cellpadding="0" border="0" class="table">
            <tr>
                <td>Title:</td>
                <td><input name="title_en_s" value="seo title" class="smtxt"></td>
            </tr>
            <tr>
                <td>Описание:</td>
                <td><textarea cols='50' rows='5' name="des_en_s">seo description</textarea></td>
            </tr>
            <tr>
                <td>Ключевые&nbsp;слова:</td>
                <td><textarea cols='50' rows='5' name="key_en_s">seo keywords</textarea></td>
            </tr>
        </table>
    </div>


    <script type="text/javascript">ch=1</script>

    <br>
    <table cellspacing="0" cellpadding="0" class="list">
        <tr>
            <th>show</th>
            <th>позиция</th>
            <th>URL</th>
            <th>название</th>
            <th>&nbsp;</th>
            <th>Тип</th>
        </tr>
        <tr>
            <td><input class="enable_all" type="checkbox" value="1" name="enabl"></td>
            <td colspan="100">
                <a href="/_s/n_list_item.php?table=pages&amp;pos=1&amp;parent=0&amp;relocate=<?echo urlencode($_SERVER['REQUEST_URI'])?>" onclick="return go(this.href)"><img src="/_s/public/img/admin/doc-plus.gif" style="margin: 0 0 -2px;" alt="Добавить" > Добавить в начало списка</a>
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
                <a href="/_s/del.php?id=1&amp;table=pages&amp;relocate=<?echo urlencode($_SERVER['REQUEST_URI'])?>" onclick="return confirm('Удалить элемент? ВНИМАНИЕ!!! Удалятся все дочерние/вложеные элементы!') ? go(this.href) : false"><img style="margin: 0 0 -3px;" src="/img/cms/del.gif" alt="удалить"></a>
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

                <a href="/_s/n_list_item.php?table=pages&amp;pos=999999&amp;parent=0&amp;relocate=<?echo urlencode($_SERVER['REQUEST_URI'])?>" onclick="return go(this.href)"><img src="/_s/public/img/admin/doc-plus.gif" style="margin: 0 0 -2px;" alt='Add' > Add to the end</a>

            </td>
        </tr>
    </table>

    <br><input type="submit" class="save" value="Save">

</form>
</div>
</td>
</tr>
</table>
</body>
</html>