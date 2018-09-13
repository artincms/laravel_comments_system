<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title','Comment Manager')</title>

    <!-- global stylesheets -->
    <link href="{{asset('vendor/laravel_comments_system/fonts/IranSans/style.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('vendor/laravel_comments_system/css/build/init_core.min.css') }}" rel="stylesheet" rel="stylesheet">
    <link href="{{ asset('vendor/laravel_comments_system/css/customBackend.css') }}" rel="stylesheet" rel="stylesheet">
    @yield('custom_plugin_style')
    <script type="text/javascript" src="{{asset('vendor/laravel_comments_system/js/build/init_core.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/laravel_comments_system/js/build/init_function.min.js')}}"></script>
    @yield('custom_plugin_js')
    <!-- /theme JS files -->
    @yield('inline_style')
    <script type="text/javascript">
        var base_url = '{{ url('/') }}'
    </script>
</head>
<body style="padding: 1%;direction: rtl">
<div class="container-fluid">
    <div class="row">
        @yield('content')
    </div>
</div>
@yield('javascript')
<script>
    function generate_loader_html(loading_text) {
        var loader_html = '' +
            '<div class="total_loader">' +
            '   <div class="total_loader_content" style="">' +
            '       <div class="spinner_area">' +
            '           <div class="spinner_rects">' +
            '               <div class="rect1"></div>' +
            '               <div class="rect2"></div>' +
            '               <div class="rect3"></div>' +
            '               <div class="rect4"></div>' +
            '               <div class="rect5"></div>' +
            '           </div>' +
            '       </div>' +
            '       <div class="text_area">' + loading_text + '</div>' +
            '   </div>' +
            '</div>';
        return loader_html;
    }
</script>
</body>
</html>