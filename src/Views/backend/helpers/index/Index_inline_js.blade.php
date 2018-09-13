<script type="text/javascript">
    $(document).off("click", '.showModalComment');
    $(document).on('click', '.showModalComment', function (e) {
        e.preventDefault();
        var src = $(this).attr('data-href');
        var iframe = $('#modal_iframe_show_comment');
        iframe.attr("src", src);
        iframe.on("load", function () {
            $('#modal_iframe_show_comment').contents().scrollTop(100);
        });
    });

    /*_________________________________________________________________________________________________________________________________*/
    $(document).off("click", '.replyToComment');
    $(document).on('click', '.replyToComment', function (e) {
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
            width: '5%',
            data: 'id',
            title: 'ردیف',
            searchable: false,
            sortable: false,
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            data: "title",
            name: "title",
            title: 'عنوان',
            mRender: function (data, type, row) {
                html = '<a href="'+row.url+'">'+row.title+'</a>';
                return html ;
            }
        },
        {
            data:'user.name',
            name:'user.name',
            title: 'نام کاربر',

        },
        {
            data:'user.email',
            name:'user.email',
            title: 'ایمیل کاربر',
        },
        {
            data:'approved',
            name:'approved',
            title: 'تایید  نظر',
            mRender: function (data, type, full) {
                var ch = '';
                if (parseInt(full.approved))
                    ch = 'checked';
                else
                    ch = '';
                return '<input class="styled " id="' + full.id + '" type="checkbox" name="special" data-item_id="' + full.id + '"  onchange="change_is_active_portfolio(this)"' + ch + '>'
            }
        },
        {
            data: "comment",
            name: "comment",
            title: "نظر",
    },
    {
        width: '7%',
        searchable: false,
        sortable: false,
        data: 'action', name: 'action', 'title': 'عملیات',
        mRender: function (data, type, full) {
            return '' +
                '<div class="gallerty_menu float-right pointer" onclick="set_fixed_dropdown_menu(this)" data-toggle="dropdowns">' +
                '<span>' +
                '   <em class="fas fa-caret-down"></em>' +
                '   <i class="fas fa-bars"></i> ' +
                '</span>' +
                '  <div class="dropdown_gallery hidden">' +
                '    <a class="showModalComment pointer gallery_menu-item" data-target="#create_modal_show_comment" data-item_id="' + full.id + '" data-title="' + full.title + ' ">' +
                '       <i class="fa fa-eye"></i><span class="ml-2">مشاهده آیتم ها</span>' +
                '   </a>'+
                '    <a class="replyToComment pointer gallery_menu-item" data-item_id="' + full.id + '" data-title="' + full.title + ' ">' +
                '       <i class="fa fa-reply"></i><span class="ml-2">پاسخ</span>' +
                '   </a>'+
                '    <a class="btn_trash_portfolio_related pointer gallery_menu-item" data-item_id="' + full.id + '" data-title="' + full.title + ' ">' +
                '       <i class="fa fa-trash"></i><span class="ml-2">حذف</span>' +
                '   </a>'+
            '  </div>' +
            '</div>';

        }
    },
    ];

    function getUrl(name, id) {
        var prefix = '{{config('laravel_comments_system.backend_lcs_route_prefix')}}';
        return base_url + '/' + prefix + '/' + name + '/' + id;
    }

    /*_________________________________________________________________________________________________________________________________*/
    $(document).off("click", '.trashComment');
    $(document).on('click', '.trashComment', function (e) {
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
        // $('#SysProcessGridData_wrapper').prepend('' +
        //     '<div id="sysProcessGridTooblar" class="sysProTollbar">' +
        //     '   <button class="btn btn-danger" id="bulkDelete">Bulk</button>' +
        //     '   <button class="btn btn-success" id="ApproveAll">Approve</button>' +
        //     '</div>' +
        //     '<hr />');
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

    function change_is_active_portfolio(input) {
        var checked = input.checked;
        var item_id = input.id;
        var parameters = {is_active: checked, item_id: item_id};
        yesNoAlert('تغییر وضعیت نظر', 'از تغییر وضعیت نظر مطمئن هستید ؟', 'warning', 'بله، وضعیت نظر را تغییر بده!', 'لغو', set_portfolio_is_active, parameters, remove_checked, parameters);
    }

    function set_portfolio_is_active(params) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: "{{route('LCS.approveComment')}}",
            data: params,
            success: function (result) {
            if (result.success) {
                menotify('success', result.title, result.message);
            }
            else {

            }
        }
    });
    }

    function remove_checked(params) {
        var $this = $('#' + params.item_id);
        if (params.is_active) {
            $this.prop('checked', false);
        }
        else {
            $this.prop('checked', true);
        }
    }

    $(document).off("click", '#show_setting');
    $(document).on('click', '#show_setting', function (e) {
        var src = $(this).data('iframe_src');
        var iframe = $('#modalIframeShowReplyComment');
        iframe.attr("src", src);
    });

</script>
