@extends('laravel_comments_system::backend.layouts.master')
@section('content')
    <table id="SysProcessGridData"class="table table-hover" style="width:100%">

    </table>
    @include('laravel_comments_system::backend.helpers.index.create_modal_index')
@endsection
@section('javascript')
    @include('laravel_comments_system::backend.helpers.index.Index_inline_js')
@endsection
