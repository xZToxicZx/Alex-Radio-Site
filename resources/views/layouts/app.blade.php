<!DOCTYPE html>
<html class="h-100" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="description" content="TreeHouseRadio, playing all the best tunes with your favorite presenters!">
    <meta name="tags" content="radio, treehouseradio, radio station, charts">
    <meta name="author" content="MM Developments">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="h-100">
    <div id="app" class="h-100 d-flex flex-column">
        <panel v-if="$route.path.match('panel') == 'panel'" :site="site"></panel>
        <index v-else :site="site"></index>
        <notifications group="alert" position='bottom right'></notifications>

        <footer class="mt-auto py-3 bg-dark text-white-50">
            <div class="container text-center">
                <p>&copy; 2019 @{{ site.domain }}</p>
            </div>
        </footer>
    </div>
</body>
</html>
