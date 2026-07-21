<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>LaravelCMS</title>
    <link rel="stylesheet" href="/css/cms/cms.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.2/themes/base/jquery-ui.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="text/javascript">
        window.csrfToken = '{{ csrf_token() }}';
    </script>

</head>
<body>
<div style="position:fixed;display:none;">Thanks to BTC :) 1B7nhzwUsUutJ7WHk5wL5aRbgfDCtHeU8k</div>

@if (session('message'))
    <div id="js_list_message">{{ session('message') }}</div>
@endif

<table style='width:100%;height:100%;' cellspacing="0" cellpadding="0">
    <tr>
        <td colspan='2' style='height:50px;'>
            <div id="header">
                <div id="lh">
                    <div class="tt">LaravelCMS</div>
                </div>
                <div id="rh" style='padding:0px;'>
                    <div style='text-align:right;'>Hello, <b>{{ $user->name }}</b></div>
                    @if( isset($page->addr) )
                        <a href="{{ url($page->addr) }}" target="_blank" class="site">Public View</a>
                    @endif
                    <a href="{{ route('cms.logout') }}" class="exit">Log Out</a>
                </div>
                <div class="clear"></div>
            </div>
        </td>
    </tr>
    <tr>
        <td class="menu" valign="top" align="center">
            <!--<div class="lang">
                <a href="/cms/edit/" class="a">en</a>
                <a href="/cms/edit//ua" class="n">ua</a>
                <div class="clear"></div>
            </div>-->

            <table cellspacing="0" cellpadding="0" class="addmod">
                <tr><th>Modules</th></tr>
                <tr><td><a href="{{ route('cms.dashboard.index') }}" class="{{ request()->routeIs('cms.dashboard.*') ? 'bold' : '' }}">Dashboard</a></td></tr>
                <tr><td><a href="{{ route('cms.templates.index') }}" class="{{ request()->routeIs('cms.templates.*') ? 'bold' : '' }}">Templates</a></td></tr>
                <tr><td><a href="{{ route('cms.users.index') }}"     class='{{ request()->routeIs('cms.users.*') ? 'bold' : '' }}'>    CMS users</a></td></tr>
                <tr><td><a href="{{ route('cms.messages.index') }}"  class='{{ request()->routeIs('cms.messages.index') ? 'bold' : '' }}'> Contact form Messages</a></td></tr>
            </table>
        </td>
        <td class="content" valign="top">
            <div class="main">