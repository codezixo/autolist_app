<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Автопарк</title>

        <!-- Fonts -->

        <!-- Styles -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" />
        @vite('resources/css/app.css')
        <script>
           window.authData = {{ Illuminate\Support\Js::from($authData) }} ;
        </script>
    </head>
    <body class="antialiased">
        <div id="app"></div>
        @vite('resources/js/app.js')
    </body>
</html>
