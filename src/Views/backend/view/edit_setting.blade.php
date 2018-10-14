<div class="space-20"></div>
<form id="frm_edit_comment_item" class="form-horizontal" name="frm_edit_comment_item" style="width: 99%;">
    <input name="item_id" type="hidden" value="{{$settings->id}}">
    <div class="form-group row fg_title">
        <label class="col-sm-2 control-label col-form-label label_post" for="title">
            <span class="more_info"></span>
            <span class="label_title">عنوان</span>
        </label>
        <div class="col-sm-6">
            <input name="title" value="{{$settings->title}}" class="form-control" id="setting_title" tab="1">
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
                    <option value="{{$morph->id}}" @if($morph->id == $settings->id ) selected @endif>{{$morph->text}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-4 messages"></div>
    </div>
    <div class="clearfixed"></div>
    <div class="col-12">
        <button type="submit" class="float-right btn btn-success ml-2"><i class="fa fa-save margin_left_8"></i>ویرایش</button>
        <button type="button" class="float-right btn bg-secondary color_white cancel_edit_setting"><i class="fa fa-times margin_left_8"></i>انصراف</button>
    </div>
</form>