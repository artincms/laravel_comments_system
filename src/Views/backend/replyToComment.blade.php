@extends('laravel_comments_system::backend.layouts.master')
@section('content')

    <div class="container" id="loaderContainer">
        <div class="alert alert-danger hidden col-md-12" id="show_error">
            <ul id="showCmmentReplyError">
            </ul>
        </div>
        <div class="alert alert-success hidden col-md-12" id="show_success">
            <ul id="showCmmentReplySuccess">
            </ul>
        </div>
        <form class="form-horizontal" id="replyToCommentForm">
            <input type="hidden" class="form-control"name="id" value="{{$commentId}}">
            <div class="form-group row">
                <label class="control-label col-sm-2 label_edit_reply_comment" for="name">Name:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Name..." name="name" value="{{$comment->name}}">
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-2 label_edit_reply_comment" for="email">Email:</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" value="{{$comment->email}}">
                </div>
            </div>
            <div class="form-group row clearfix">
                <label class="control-label col-sm-2 label_edit_reply_comment" for="comment">Comment:</label>
                <div class="col-sm-10 padding_right_4">
                    <textarea class="form-control" id="comment" aria-describedby="sizing-addon3" name="comment">{{$comment->comment}}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-2 label_edit_reply_comment" for="status">Status :</label>
                <div class="col-sm-10">
                    <select id="inputState" class="form-control" name="approved">
                        <option value="0" @if($comment->approved ==0) selected @endif>Disapproval</option>
                        <option value="1" @if($comment->approved ==1) selected @endif>Approved</option>
                    </select>
                </div>
            </div>
            <div class="clearfix"></div>
            <hr>
            <div class="form-group row clearfix">
                <label class="control-label col-sm-2 label_edit_reply_comment" for="comment">Reply:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="comment" aria-describedby="sizing-addon3" placeholder="reply..." name="reply"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button class="hidden" type="submit" id="submitReplyToComment" class="btn btn-default">save</button>
                </div>


            </div>
        </form>
    </div>


@endsection
@section('javascript')
    <script>
        $(document).off("click", '#submitReplyToComment');
        $(document).on('click', '#submitReplyToComment', function (e) {
            e.preventDefault() ;
            var formElement = document.querySelector('#replyToCommentForm');
            var formData = new FormData(formElement);
            $('#replyToCommentForm').append(generate_loader_html('please_wait....'));
            replySave(formData);
        });

        function replySave(FormData) {
            $.ajax({
                type: "POST",
                url: "{{route('LCS.storeReplyToComment')}}",
                data: FormData,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function (res) {
                    if (res.success == true) {
                        $('.total_loader').remove();
                        $('#show_error').addClass('hidden');
                        $('#show_success').removeClass('hidden');
                        $('#showCmmentReplyError').html('');
                        $('#showCmmentReplySuccess').html('');
                        $.each(res.message,function (index,value) {
                            $('#showCmmentReplySuccess').append('<li><span>'+value+'</li>');
                        });
                        if(typeof parent.refresh !== 'undefined')
                        {
                            parent.refresh() ;
                        }
                    }
                    else
                    {
                        $('.total_loader').remove();
                        $('#show_error').removeClass('hidden');
                        $('#show_success').addClass('hidden');
                        $('#showCmmentReplySuccess').html('');
                        $('#showCmmentReplyError').html('');
                        $.each(res.error,function (index,value) {
                            $('#showCmmentReplyError').append('<li><span>'+value+'</li>');
                        });

                    }
                },
                error: function (e) {
                    $('.total_loader').remove();
                    $('#show_error').removeClass('hidden');
                    $('#showCmmentReplyError').html('');
                    $.each(e.responseJSON.errors,function (index,value) {
                        $('#showCmmentReplyError').append('<li><span>'+index+':'+value+'</li>');
                    });
                }
            });
        }

    </script>
@endsection
