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





});