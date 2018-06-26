<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link href="{{ asset('vendor/laravel_comments_system/packages/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('vendor/laravel_comments_system/packages/bootstrap/js/bootstrap.min.js')}}"></script>

    <!-- Scripts -->
    <script src="{{ asset('js/components/build/comment.min.js') }}" defer></script>
</head>
<body>
    <div id="comments">
        <laravel_comments_system :target_model_name="'App\\Article'" :target_id="1" :target_parent_column_name="'parent_id'" :user-id="{{LCS_getUserId()}}" ></laravel_comments_system>
    </div>
</body>