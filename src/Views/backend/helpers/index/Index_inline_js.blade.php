<script type="text/javascript">
    $(document).off("click", '#showModalComment');
    $(document).on('click', '#showModalComment', function (e) {
        e.preventDefault();
        var src = $(this).attr('data-href');
        var iframe = $('#modal_iframe_show_comment');
        iframe.attr("src", src);
        iframe.on("load", function () {
            $('#modal_iframe_show_comment').contents().scrollTop(100);
        });
    });

    /*_________________________________________________________________________________________________________________________________*/
    $(document).off("click", '#replyToComment');
    $(document).on('click', '#replyToComment', function (e) {
        e.preventDefault();
        var src = $(this).attr('data-href');
        var iframe = $('#modalIframeShowReplyComment');
        iframe.attr("src", src);
        iframe.on("load", function () {
            $(document).off('click', '#submitReplyComment');
            $(document).on('click', '#submitReplyComment', function (e) {
                var selector = iframe.contents().find("#submitReplyToComment");
                selector.click();
            });
            $(document).off('click', '#submitReplyCommentClose');
            $(document).on('click', '#submitReplyCommentClose', function (e) {
                var selector = iframe.contents().find("#submitReplyToComment");
                selector.click();
                $('#createModalForReplyComment').modal('hide');
            });
        });
    });


    /*_________________________________________________________________________________________________________________________________*/
    var getSysProcessRoute = '{{route('LCS.getCommentDataTable')}} ';
    var sys_process_grid_columns = [
        {
            title: '<input name="select_all" id="select_all" value="1" type="checkbox" class="check toggle_select"/>',
            searchable: false,
            orderable: false,
            width: '1%',
            render: function (data, type, row) {
                return '<input type="checkbox" id="toggleCheckComment" class="check checkComment" data-id="'+row.id+'">';
            }
        },
        {
          title:'Username',
            data:'user.name',
            name:'user.name',
            visible :false

        },
        {
            title:'UserEmail',
            data:'user.email',
            name:'user.email',
            visible :false
        },
        {
            title:'Approve',
            data:'approved',
            name:'approved',
            orderable: false,
            mRender: function (data, type, row) {
                if (row.approved == 0)
                {
                    return '<a id="approvedButton"  data-id="' + row.id + '" data-value="'+row.approved+'"  class="margin_left_5" href=""><i class="fa fa-square-o" aria-hidden="true"></i></a>'  ;
                }
                else
                {
                    return  '<a id="approvedButton"  data-id="' + row.id + '"  data-value="'+row.approved+'"  class="margin_left_5" href=""><i class="fa fa-check-square-o" aria-hidden="true"></i></a>'  ;
                }

            }

        },
        {
            title: "Name",
            data: "name",
            name: "name",
            orderable: false,
            mRender: function (data, type, row) {
               if (!row.name)
               {
                   return row.user.name ;
               }
               else
               {
                   return row.name ;
               }

            }
        },
        {
            title: "Email",
            data: "email",
            name: "email",
            orderable: false,
            mRender: function (data, type, row) {
                if (!row.email)
                {
                    return row.user.email ;
                }
                else
                {
                    return row.email ;
                }

            }
        },
        {
            title: "Comment",
            data: "comment",
            name: "comment"
        },
        {
            title: "Title",
            data: "title",
            name: "title",
            mRender: function (data, type, row) {
                html = '<a href="'+row.url+'">'+row.title+'</a>';
                return html ;
            }
        },
        {
            title: "Url",
            data: "url",
            name: "url",
            searchable: false,
            orderable: false,
            visible :false
        },
        {
            title: "Action",
            data: 'action',
            name: 'action',
            searchable: false,
            orderable: false,
            mRender: function (data, type, row) {
                html =
                    '<a class="margin_left_5" id="showModalComment" data-toggle="modal" data-target="#create_modal_show_comment" data-id="' + row.id + '" href="#" data-href="' + row.url + '"><i class="fa fa-eye"></i></a>' +
                    '<a class="margin_left_5"  id="replyToComment" href="#" data-href="' + getUrl('replyToComment', row.id) + '" data-toggle="modal" data-target="#createModalForReplyComment"> <i class="fa fa-reply"></i></a>'+
                    '<a class="margin_left_5" id="trashComment" data-id="' + row.id + '" href="#"><i class="fa fa-trash"></i></a>' ;
                return html;
            }
        },
    ];

    function getUrl(name, id) {
        var prefix = '{{config('laravel_comments_system.backend_lcs_route_prefix')}}';
        return base_url + '/' + prefix + '/' + name + '/' + id;
    }

    /*_________________________________________________________________________________________________________________________________*/
    $(document).off("click", '#trashComment');
    $(document).on('click', '#trashComment', function (e) {
        e.preventDefault();
        e.preventDefault();
        swal({
            title: '@lang('commentBackend.are_you_sure')',
            text: "@lang('commentBackend.you_wont_be_able_to_revert_this')",
            cancelButtonText: '@lang('commentBackend.no_cancel')',
            confirmButtonText: '@lang('commentBackend.yes_delete_it')',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false,
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
            var id = $(this).attr('data-id');
            trashComment(id);

            }
        })
    });
    function trashComment(id) {
        $.ajax({
            type: "POST",
            url: "{{route('LCS.trashComment')}}",
            dataType: "json",
            data :{
                id:id ,
            },
            success: function (result) {
                if (result.success == true) {
                    swal(
                        '@lang('commentBackend.deleted')!',
                        '@lang('commentBackend.your_file_has_been_deleted')'
                    )
                    refresh();
                }
                else
                {
                    alert('@lang('commentBackend.some_thing_is_wrong')');
                    console.log(result);
                }
            },
            error: function (e) {
            }
        });
    }
    /*_________________________________________________________________________________________________________________________________*/
    $(document).off("click", '#approvedButton');
    $(document).on('click', '#approvedButton', function (e) {
        e.preventDefault() ;
        var id = $(this).attr('data-id');
        var value = $(this).attr('data-value');
        if (value == 0)
        {
            swalTitle ='@lang('commentBackend.you_want_confirm_this_commment')';
            swalButton ='@lang('commentBackend.yes_approve_it')';
        }
        else
        {
            swalTitle ='@lang('commentBackend.you_want_dissaprove_this_commment')';
            swalButton ='@lang('commentBackend.yes_dissapprove_it')';
        }
        id = $(this).attr('data-id');
        swal({
            title: swalTitle,
            cancelButtonText: '@lang('commentBackend.no_cancel')',
            confirmButtonText:swalButton ,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false,
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
            setApproved([id],value);

        }
    })
    });
    function setApproved(id,value) {
        $.ajax({
            type: "POST",
            url: "{{route('LCS.approveComment')}}",
            dataType: "json",
            data :{
                id:id ,value:value
            },
            success: function (result) {
                if (result.success == true) {
                    swal({
                        type: 'success',
                        title: '@lang('commentBackend.operation_is_success')',
                    })

                    refresh();
                }
                else
                {
                    alert('@lang('commentBackend.some_thing_is_wrong')');
                    console.log(result);
                }
            },
            error: function (e) {
            }
        });
    }
    /*_________________________________________________________________________________________________________________________________*/
    $(document).off("click", '#select_all');
    $(document).on('click', '#select_all', function (e) {
        set_selected_all();
        $(this).attr('id','select_none')

    });
    function set_selected_all()
    {
        $('.checkComment').each(function () {
            $(this).addClass('selected');
            $(this).prop('checked', true);
        }) ;
    }
    /*_________________________________________________________________________________________________________________________________*/
    $(document).off("click", '#select_none');
    $(document).on('click', '#select_none', function (e) {
        $('.checkComment').each(function () {
            $(this).removeClass('selected');
            $(this).prop('checked', false);
        });
        $('.toggle_select').attr("id","select_all");
    });

    /*_________________________________________________________________________________________________________________________________*/
    function refresh() {
        SysProcessGridData.ajax.reload();
    }

    /*_________________________________________________________________________________________________________________________________*/
    $( "#create_modal_show_comment" ).on('shown', function(){
        alert("I want this to appear after the modal has opened!");
    });
    /*_________________________________________________________________________________________________________________________________*/
    $( document ).ready(function() {
        dataTablesGrid('#SysProcessGridData', 'SysProcessGridData', getSysProcessRoute, sys_process_grid_columns);
        $('#SysProcessGridData_wrapper').prepend('' +
            '<div id="sysProcessGridTooblar" class="sysProTollbar">' +
            '   <button class="btn btn-danger" id="bulkDelete">Bulk</button>' +
            '   <button class="btn btn-success" id="ApproveAll">Approve</button>' +
            '</div>' +
            '<hr />');
    });

    /*_________________________________________________________________________________________________________________________________*/
    $(document).off("click", '#toggleCheckComment');
    $(document).on('click', '#toggleCheckComment', function (e) {
        $(this).toggleClass('selected');
        if($(this).hasClass('selected') == true)
        {
            $(this).attr("checked", "checked");
        }
        else if($(this).hasClass('selected') == false)
        {
            $(this).removeAttr('checked') ;
        }
    });
    /*_________________________________________________________________________________________________________________________________*/
    $(document).off("click", '#ApproveAll');
    $(document).on('click', '#ApproveAll', function (e) {
       var items = getAllSelected();
        swalTitle ='@lang('commentBackend.you_want_confirm_this_commment')';
        swalButton ='@lang('commentBackend.yes_approve_it')';
        swal({
            title: swalTitle,
            cancelButtonText: '@lang('commentBackend.no_cancel')',
            confirmButtonText:swalButton ,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false,
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
            setApproved(items,0);
            $('.toggle_select').attr('id','select_all');
            $('.toggle_select').prop('checked', false);

        }
        })
    });
    function getAllSelected()
    {
        var items = [];
        $('.selected').each(function(k , v) {
            id=$(this).attr('data-id');
            items.push(id);
        });
        return items ;
    }

</script>
