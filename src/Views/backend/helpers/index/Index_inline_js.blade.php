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
        var item_id = $(this).data('item_id');
        var title = $(this).data('title');
        $('.span_reply_comment_tab').html('پاسخ به نظر : ' + title);
        get_edit_reply_to_comment_form(item_id);
    });

    function get_edit_reply_to_comment_form(item_id) {
        $('#reply_comment').children().remove();
        $('#reply_comment').append(generate_loader_html('لطفا منتظر بمانید...'));
        $.ajax({
            type: "POST",
            url: '{{ route('LCS.getReplyToCommentForm')}}',
            dataType: "json",
            data: {
            item_id: item_id
        },
        success: function (result) {
            $('#reply_comment .total_loader').remove();
            if (result.success) {
                $('#reply_comment').append(result.reply_view);
                $('.reply_comment_tab').removeClass('hidden');
                $('a[href="#reply_comment"]').click();

            }
            else {
            }
        }
    });
    }

    $(document).off("click", ".cancel_reply_to_comment");
    $(document).on("click", ".cancel_reply_to_comment", function () {
        $('a[href="#manage_tab_comments"]').click();
        $('.reply_comment_tab').addClass('hidden');
        $('#reply_comment').html('');
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
            // mRender: function (data, type, row) {
            //     html = '<a href="'+row.url+'">'+row.title+'</a>';
            //     return html ;
            // }
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
                // '    <a class="showModalComment pointer gallery_menu-item" data-target="#create_modal_show_comment" data-item_id="' + full.id + '" data-title="' + full.title + ' ">' +
                // '       <i class="fa fa-eye"></i><span class="ml-2">مشاهده آیتم ها</span>' +
                // '   </a>'+
                '    <a class="replyToComment pointer gallery_menu-item" data-item_id="' + full.id + '" data-title="' + full.title + ' ">' +
                '       <i class="fa fa-reply"></i><span class="ml-2">پاسخ</span>' +
                '   </a>'+
                '    <a class="trashComment pointer gallery_menu-item" data-item_id="' + full.id + '" data-title="' + full.title + ' ">' +
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
        var item_id = $(this).data('item_id');
        var title = $(this).data('title');
        desc = 'بله نظر( ' + title + ' ) را حذف کن !';
        var parameters = {item_id: item_id};
        yesNoAlert('حذف نظر', 'از حذف نظر مطمئن هستید ؟', 'warning', desc, 'لغو', trashComment, parameters);
    });
    function trashComment(params) {
        $.ajax({
            type: "POST",
            url: "{{route('LCS.trashComment')}}",
            dataType: "json",
            data: params,
            success: function (data) {
                if (!data.success) {
                    showMessages(data.message, 'form_message_box', 'error', formElement);
                    showErrors(formElement, data.errors);
                }
                else {
                    menotify('success', data.title, data.message);
                    SysProcessGridData.ajax.reload(null, false);
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
