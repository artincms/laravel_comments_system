<script type="text/javascript">
    $(document).off("click", '#showModalComment');
    $(document).on('click', '#showModalComment', function (e){
        e.preventDefault() ;
        var src = $(this).attr('data-href') ;
        var iframe = $('#modal_iframe_show_comment');
        iframe.attr("src",src);
        setTimeout(function() {
            var $contents = $('#modal_iframe_show_comment').contents();
            $contents.scrollTo($contents.find('#item_comment_10'));
        }, 2000);
    });

    var getSysProcessRoute = '{{route('LCS.getCommentDataTable')}} ';
    var sys_process_grid_columns =[
        {
            title: '<input name="select_all" id="select_all" value="1" type="checkbox" class="check toggle_select"/>',
            searchable: false,
            orderable: false,
            width: '1%',
            className: 'dt-body-center',
            render: function (data, type, full, meta) {
                return '<input type="checkbox" class="check">';
            }
        },
        {
            title: "ID",
            data: "id",
            name: "id"
        },
        {
            title: "Name",
            data: "name",
            name: "name"
        },
        {
            title: "Email",
            data: "email",
            name: "email"
        },
        {
            title: "Comment",
            data: "comment",
            name: "comment"
        },
        {
            title: "Title",
            data: "title",
            name: "title"
        },
        {
            title: "Url",
            data: "url",
            name: "url"
        },
        {
            title: "Action",
            data: 'action',
            name: 'action',
            mRender: function (data, type, row) {
                html =
                    '<a id="trashCommetn" href="#"><i class="fa fa-trash"></i></a>' +
                    '<a id="showModalComment" data-toggle="modal" data-target="#create_modal_show_comment" data-id="'+row.id+'" href="#" data-href="'+row.url+'"><i class="fa fa-eye"></i></a>' ;
                return html ;
            }
        },
    ] ;
    var more_data = {
        id:1
    }
    dataTablesGrid('#SysProcessGridData', 'SysProcessGridData', getSysProcessRoute, sys_process_grid_columns,more_data,false);
</script>
