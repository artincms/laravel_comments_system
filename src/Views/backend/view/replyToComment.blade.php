        <div class="space-20"></div>
        <form class="form-horizontal" id="replyToCommentForm">
            <input type="hidden" class="form-control" name="encode_id" value="{{$comment->encode_id}}">
            <div class="form-group row fg_title">
                <label class="col-sm-2 control-label col-form-label label_post" for="title">
                    <span class="more_info"></span>
                    <span class="label_title">نام :</span>
                </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="نام ..." name="name" value="{{$comment->name}}">
                </div>
                <div class="col-sm-4 messages"></div>
            </div>
            <div class="form-group row fg_title">
                <label class="col-sm-2 control-label col-form-label label_post" for="title">
                    <span class="more_info"></span>
                    <span class="label_title"> ایمیل :</span>
                </label>
                <div class="col-sm-6">
                    <input type="email" class="form-control" id="email" placeholder="ایمیل خود را وارد نمایید .." name="email" value="{{$comment->email}}">
                </div>
                <div class="col-sm-4 messages"></div>
            </div>
            <div class="form-group row fg_title">
                <label class="col-sm-2 control-label col-form-label label_post" for="title">
                    <span class="more_info"></span>
                    <span class="label_title">نظر کاربر</span>
                </label>
                <div class="col-sm-6">
                    <textarea class="form-control" id="comment_reply" aria-describedby="sizing-addon3" name="comment">{{$comment->comment}}</textarea>
                </div>
                <div class="col-sm-4 messages"></div>
            </div>
            <div class="form-group row fg_title">
                <label class="col-sm-2 control-label col-form-label label_post" for="title">
                    <span class="more_info"></span>
                    <span class="label_title">وضعیت :</span>
                </label>
                <div class="col-sm-6">
                    <select id="inputState" class="form-control" name="approved">
                        <option value="0" @if($comment->approved ==0) selected @endif>عدم تایید</option>
                        <option value="1" @if($comment->approved ==1) selected @endif>تایید</option>
                    </select>                </div>
                <div class="col-sm-4 messages"></div>
            </div>
            <div class="clearfix"></div>
            <hr>
            <div class="form-group row fg_title">
                <label class="col-sm-2 control-label col-form-label label_post" for="title">
                    <span class="more_info"></span>
                    <span class="label_title">پاسخ به نظر :</span>
                </label>
                <div class="col-sm-6">
                    <textarea class="form-control"  id="reply_to_comment" aria-describedby="sizing-addon3" placeholder="پاسخ به نظر ..." name="reply"></textarea>
                </div>
                <div class="col-sm-4 messages"></div>
            </div>
            <div class="clearfixed"></div>
            <div class="col-12">
                <button type="submit" class="float-right btn btn-success ml-2"><i class="fa fa-save margin_left_8"></i>ارسال</button>
                <button type="button" class="float-right btn bg-secondary color_white cancel_reply_to_comment"><i class="fa fa-times margin_left_8"></i>انصراف</button>
            </div>
        </form>
    <script>
        var create_comment_reply_constraints = {
            name: {
                presence: {message: '^<strong>نام ضروی است.</strong>'}
            },
        };
        var reply_to_comment_form_id = document.querySelector("#replyToCommentForm");
        init_validatejs(reply_to_comment_form_id, create_comment_reply_constraints, ajax_func_reply_to_comment);
        function ajax_func_reply_to_comment(formElement) {
            var formData = new FormData(formElement);
            $.ajax({
                type: "POST",
                url: "{{route('LCS.storeReplyToComment')}}",
                dataType: "json",
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    $('#replyToCommentForm .total_loader').remove();
                    if (data.success) {
                        menotify('success', data.title, data.message);
                        SysProcessGridData.ajax.reload(null, false);
                        $('a[href="#manage_tab_comments"]').click();
                        $('.reply_comment_tab').addClass('hidden');
                        $('#reply_comment').html('');
                    }
                    else {
                        showMessages(data.message, 'form_message_box', 'error', formElement);
                        showErrors(formElement, data.errors);

                    }
                }
            });
        }
    </script>
