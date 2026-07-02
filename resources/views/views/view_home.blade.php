<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $page->name }}</title>

    </head>
    <body class="antialiased">


    <h1>{{ $page->name }}</h1>

    <content>{{ $page->text }}</content>


    <br><br>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})

    </body>
</html>
