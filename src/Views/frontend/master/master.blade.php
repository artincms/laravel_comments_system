<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/comment.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    @yield('add_css');
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    @yield('add_js');
</head>
<body>
<div id="container-fluid">
    <div class="row">
        @yield('content')
    </div>
    @yield('content_javascript')
</div>
</body>
</html>
