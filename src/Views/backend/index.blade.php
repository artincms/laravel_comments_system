@extends('laravel_comments_system::backend.layouts.master')
@section('content')
    <h1>Comment Manager</h1>
    <div class="clearfix"></div>
    <table id="SysProcessGridData"class="table table-hover" style="width:100%">
        <thead>
        <tr>
            <th></th>
            <th>id</th>
            <th>name</th>
            <th>email</th>
            <th>comment</th>
            <th>title</th>
            <th>url</th>
            <th>Action</th>

        </tr>
        </thead>
    </table>
    @include('laravel_comments_system::backend.helpers.index.create_modal_index')
@endsection
@section('javascript')
    @include('laravel_comments_system::backend.helpers.index.Index_inline_js')
@endsection
