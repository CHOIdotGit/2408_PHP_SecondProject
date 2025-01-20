<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <script src="{{ asset('js/app.js') }}" defer> </script>
        {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}

        <link rel="icon" type="image/png" sizes="96x96" href="/img/favicon-96x96.png">
        <title>moamoa</title>
    </head>
    <body>
        <div id="app">
            <APP-Component></APP-Component>
            
        </div>
        
    </body>
</html>
