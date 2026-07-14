document.addEventListener('DOMContentLoaded', function() {

    // dashboard & list begin
    document.body.addEventListener('click', function(e) {

        if (e.target.id === 'infoblock_a') {
            var infoblock = document.getElementById('infoblock');
            if(infoblock){
                if(infoblock.style.display == 'block'){
                    infoblock.style.display = 'none';
                } else {
                    infoblock.style.display = 'block';
                }
            }
        }



        if (e.target.id === 'seoblock_a') {
            var seoblock = document.getElementById('seoblock');
            if(seoblock){
                if(seoblock.style.display == 'block'){
                    seoblock.style.display = 'none';
                } else {
                    seoblock.style.display = 'block';
                }
            }
        }



        /*if (e.target.classList.contains('clllll')) {
            e.preventDefault();
        }*/


    });



    // Hide InfoTips about action
    var js_list_message = document.getElementById('js_list_message');
    if(js_list_message) {
        setTimeout(function () {
            js_list_message.style.display = 'none';
        }, 5000);
    }

    // dashboard & list end


    $( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });



    tinyMCE.init({
        selector: '.js_tinymce',
        remove_trailing_brs: false,

        plugins: 'link image code lists',

        toolbar: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect | link | image | cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,|,image,insertimage,insertfile,|,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor | tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen | insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|",
        relative_urls : false,
        file_browser_callback: RoxyFileBrowser,
        valid_elements: "*[*]",

        force_br_newlines : true,
        force_p_newlines : false,
        forced_root_block : '',
        // Таблица стилей сайта
        /*content_css : "/public/css/style.css",
         menubar : "tools"*/

    });

});



function RoxyFileBrowser(field_name, url, type, win) {

    var roxyFileman = '/tiny_mce/plugins/fileman/index.html?_token='+window.csrfToken;
    if (roxyFileman.indexOf("?") < 0) {
        roxyFileman += "?type=" + type;
    }
    else {
        roxyFileman += "&type=" + type;
    }
    roxyFileman += '&input=' + field_name + '&value=' + win.document.getElementById(field_name).value;
    if(tinyMCE.activeEditor.settings.language){
        roxyFileman += '&langCode=' + tinyMCE.activeEditor.settings.language;
    }
    tinyMCE.activeEditor.windowManager.open({
        file: roxyFileman,
        title: 'Image/File manager',
        width: 850,
        height: 650,
        resizable: "yes",
        plugins: "media",
        inline: "yes",
        close_previous: "no"
    }, {     window: win,     input: field_name    });
    return false;
}



function move(id,val) {
    var s = document.querySelector('input[name="position['+id+']"]').value;
    document.querySelector('input[name="position['+id+']"]').value = Number(s) + Number(val);
}



function check_url(el,id) {
    el.value = el.value.toLowerCase();
    var s = document.querySelector('input[name="addr_'+id+'"]');
    if(s){
        s.value = '';
    }
}



/*function unlock_page_type(id) {
    document.getElementById('type_'+id).disabled=false;
    document.getElementById('type_enable_'+id).style.display='none';
}*/
