<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn01.boxcdn.net/platform/preview/2.106.0/en-US/preview.css" type="text/css">
        @vite('resources/css/app.css')
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div id="app" class="main-container">
            @yield('content')
        </div>

        <script src="https://cdn01.boxcdn.net/platform/preview/2.106.0/en-US/preview.js"></script>
        @vite('resources/js/app.js')
    </body>
</html>
