@extends('laravel_comments_system::backend.layouts.master')
@section('content')
<div class="col-sm-12">
    <ul class="nav nav-tabs nav-tabs-bottom" id="setting_tab" role="tablist">
        <li class="nav-item"><a class="nav-link active" href="#manage_tab_comments" data-toggle="tab"><i class="fas fa-th-list"></i><span class="margin_right_5">مدیریت نظرات</span></a></li>
        <li class="nav-item reply_comment_tab hidden">
            <a href="#reply_comment" class="nav-link paddin_left_30" data-toggle="tab">
                <span class="span_reply_comment_tab">پاسخ به نظر</span>
            </a>
            <button class="close closeTab cancel_reply_to_comment" type="button">×</button>
        </li>
        <li class="nav-item add_setting_tab">
            <a class="nav-link" href="#show_setting" data-toggle="tab">
                <i class="fas fa-cog"></i>
                <span>تنظیمات</span>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="manage_tab_comments">
            <div class="space-20"></div>
            <table id="SysProcessGridData"class="table table-hover" style="width:100%">
            </table>
        </div>
        <div class="tab-pane" id="reply_comment"></div>
        <div class="tab-pane" id="show_setting">
            <div class="space-20"></div>
            <div class="col-sm-12">
                <ul class="nav nav-tabs nav-tabs-bottom" id="setting_tab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" href="#manage_tab" data-toggle="tab"><i class="fas fa-th-list"></i><span class="margin_right_5">مدیریت آیتم ها</span></a></li>
                    <li class="nav-item add_setting_tab">
                        <a class="nav-link" href="#add_setting" data-toggle="tab">
                            <i class="far fa-plus-square"></i>
                            <span>افزودن</span>
                        </a>
                    </li>
                    <li class="nav-item edit_setting_tab hidden">
                        <a href="#edit_setting" class="nav-link paddin_left_30" data-toggle="tab">
                            <span class="span_edit_setting_tab">ویرایش</span>
                        </a>
                        <button class="close closeTab cancel_edit_setting" type="button">×</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="manage_tab">
                        <div>
                            <div class="space-20"></div>
                            <div class="col-xs-12 setting_manager_parrent_div">
                                <table id="SettingManagerGridData" class="table " width="100%"></table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="add_setting">
                        <div class="space-20"></div>
                        <form id="frm_create_comment_item" class="form-horizontal" name="frm_create_comment_item" style="width: 99%;">
                            <div class="form-group row fg_title">
                                <label class="col-sm-2 control-label col-form-label label_post" for="title">
                                    <span class="more_info"></span>
                                    <span class="label_title">عنوان</span>
                                </label>
                                <div class="col-sm-6">
                                    <input name="title" class="form-control" id="setting_title" tab="1">
                                </div>
                                <div class="col-sm-4 messages"></div>
                            </div>
                            <div class="form-group row fg_title">
                                <label class="col-sm-2 control-label col-form-label label_post" for="title">
                                    <span class="more_info"></span>
                                    <span class="label_title">انتخاب دسته</span>
                                </label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="morph_id">
                                        <option value="0">انتخاب نمایید</option>
                                        @foreach($morphs as $morph)
                                        <option value="{{$morph->id}}">{{$morph->text}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4 messages"></div>
                            </div>
                            <div class="clearfixed"></div>
                            <div class="col-12">
                                <button type="submit" class="float-right btn btn-success ml-2"><i class="fa fa-save margin_left_8"></i>افزودن</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="edit_setting"></div>
                </div>
            </div>
        </div>
    </div>
</div>
    @include('laravel_comments_system::backend.helpers.index.create_modal_index')
@endsection
@section('javascript')
    @include('laravel_comments_system::backend.helpers.index.Index_inline_js')
    @include('laravel_comments_system::backend.helpers.setting_inline_js')
@endsection
