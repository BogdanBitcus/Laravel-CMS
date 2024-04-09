document.addEventListener('DOMContentLoaded', function() {

    // auth page begin
    function focuuss() {
        var auth_email = document.getElementById('auth_email');
        var js_auth_error = document.getElementById('js_auth_error');
        if(auth_email){
            auth_email.focus();
        }
        setTimeout(function(){
            if(js_auth_error){
                js_auth_error.style.display = 'none';
            }
        },5000);
    }
    focuuss();
    // auth page end



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
    // dashboard & list end


});



function move(id,val) {
    var s = document.querySelector('input[name="position['+id+']"]').value;
    document.querySelector('input[name="position['+id+']"]').value = Number(s) + Number(val);
}



function check_url(el) {
    el.value = el.value.toLowerCase();
}



function unlock_page_type(id) {
    document.getElementById('type_'+id).disabled=false;
    document.getElementById('type_enable_'+id).style.display='none';
}
