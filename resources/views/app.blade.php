<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'App') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('./favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('./favicon/favicon-16x16.png') }}">


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



        @routes
            @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia



        @if(env('SSLCZ_TESTMODE'))
            <script>
                (function (window, document) {
                    var loader = function () {
                        var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
                        script.src = "https://sandbox.sslcommerz.com/embed.min.js?" + Math.random().toString(36).substring(7);
                        tag.parentNode.insertBefore(script, tag);
                    };
                    window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
                })(window, document);
            </script>
        @else
            <script>
                (function (window, document) {
                    var loader = function () {
                        var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
                        script.src = "https://seamless-epay.sslcommerz.com/embed.min.js?" + Math.random().toString(36).substring(7);
                        tag.parentNode.insertBefore(script, tag);
                    };
                    window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
                })(window, document);
            </script>
        @endif

    </body>
</html>
