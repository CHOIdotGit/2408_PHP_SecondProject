<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <script src="{{ asset('js/app.js') }}" defer> </script>
        <title>Communyte</title>
    </head>
    <body>
        <div id="app">
            <APP-Component></APP-Component>
        </div>
    </body>
</html>
