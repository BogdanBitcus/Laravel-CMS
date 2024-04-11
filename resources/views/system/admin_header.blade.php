<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>LaravelCMS</title>
    <link rel="stylesheet" href="/css/cms/cms.css">
</head>
<body>
<div style="position:fixed;display:none;">Thanks to BTC :) 1B7nhzwUsUutJ7WHk5wL5aRbgfDCtHeU8k</div>

@if (session('message'))
    <div id="js_list_message">{{ session('message') }}</div>
@endif
