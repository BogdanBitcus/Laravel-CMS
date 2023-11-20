<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Enter to ADMINZONE</title>

</head>
<body style='background:url("/img/cms/body_bg.jpg") repeat-x 0px 0px;padding:0px;margin:0px;' onLoad='focuuss();'>
<form method=POST name='_auth' action="">
    @csrf
    <div style='width:300px;margin:200px auto 0 auto;'>
        <div style='margin:0px 0px 10px 0px;'><input type='text' value='' name='_email' id="_email" placeholder="{{ __('Email') }}" onclick="this.focus();" style='width:100%;border:1px solid #808080;'></div>
        <div style='margin:0px 0px 10px 0px;'><input type='password' value='' name='_password' id="_password" placeholder="{{ __('Password') }}" style='width:100%;border:1px solid #808080;'></div>
        <div style='margin:0px 0px 10px 0px;'><input type='submit' style='width:100%;' value='{{ __('Enter') }}'></div>
        {{--<? if($_auth_error==1){ ?><p style='color:red;'>Не правильный пароль!</p><?}?>--}}
    </div>
</form>

<script>
    function focuuss()
    {
        document.getElementById('_password').focus();
    }
</script>

</body>
</html>