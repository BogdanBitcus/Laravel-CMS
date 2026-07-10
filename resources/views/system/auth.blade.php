<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/cms/cms.css">
    <title>Log In to LaravelCMS</title>
</head>
<body class='auth_body'>
<form method=POST name='_auth' action="{{ route('cms.login') }}">
    @csrf
    <div class='auth_div1'>
        <div class='auth_div2'><input type='text' value='' name='email' id="auth_email" placeholder="{{ __('Email') }}" onclick="this.focus();" class='auth_input1'></div>
        <div class='auth_div2'><input type='password' value='' name='password' id="auth_password" placeholder="{{ __('Password') }}" class='auth_input1'></div>
        <div class='auth_div2'><input type='submit' class='auth_input2' value='{{ __('Enter') }}'></div>
        @if (session('error'))
            <p style='color:red;' id="js_auth_error">
                {{ session('error') }}
            </p>
        @endif
    </div>
</form>
<script type="text/javascript">
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
</script>
</body>
</html>