<script type="text/javascript">
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
                    '<a id="showMoalComment" href="#"><i class="fa fa-trash"></i></a>' +
                    '<a href="#"><i class="fa fa-eye"></i></a>' ;
                return html ;
            }
        },
    ] ;
    var more_data = {
        id:1
    }
    dataTablesGrid('#SysProcessGridData', 'SysProcessGridData', getSysProcessRoute, sys_process_grid_columns,more_data,false);
</script>
