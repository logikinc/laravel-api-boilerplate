<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>

        <link href="{{ elixir('/css/app.css') }}" rel="stylesheet">
        <script>
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
                'siteName'  => config('app.name'),
                'apiDomain' => config('app.url').'/api'
            ]) !!}
        </script>
    </head>

    <body>

        <div id="app">
            <app></app>
        </div>

        <script src="{{ elixir('/js/app.js') }}"></script>
    </body>
</html>
