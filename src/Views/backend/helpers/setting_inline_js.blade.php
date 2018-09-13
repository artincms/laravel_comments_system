<script type="text/javascript">
    window['setting_grid_columns'] = [
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
            data: 'id',
            name: 'id',
            title: 'آی دی',
            visible: false
        },
        {
            width: '20%',
            data: 'title',
            name: 'title',
            title: 'عنوان',
        },
        {
            width: '25%',
            data: 'created_by',
            name: 'created_by',
            title: 'ایجاد شده توسط',
            mRender: function (data, type, full) {
                if (full.user && full.user.name) {
                    return '<span>' + full.user.name + '<span>';
                }
                else
                    return '<span><span>';
            }
        },
        {
            width: '5%',
            data: 'is_active',
            name: 'is_active',
            title: 'وضعیت',
            mRender: function (data, type, full) {
                var ch = '';
                if (parseInt(full.is_active))
                    ch = 'checked';
                else
                    ch = '';
                return '<input class="styled " id="' + full.id + '" type="checkbox" name="special" data-item_id="' + full.id + '"  onchange="change_is_active_setting(this)"' + ch + '>'
            }
        },
        {
            width: '10%',
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
                    '   <a class="btn_edit_settings pointer gallery_menu-item" data-item_id="' + full.id + '" data-title="' + full.title + '">' +
                    '       <i class="fa fa-edit"></i><span class="ml-2">ویرایش</span>' +
                    '   </a>' +
                    '    <a class="btn_trash_settings pointer gallery_menu-item" data-item_id="' + full.id + '" data-title="' + full.title + ' ">' +
                    '       <i class="fa fa-trash"></i><span class="ml-2">حذف</span>' +
                    '   </a>'
                '  </div>' +
                '</div>';

            }
        }
    ];
    $(document).ready(function () {
        var getCommentItemRoute = '{{ route('LCS.getCommentItemDatatable') }}';
        dataTablesGrid('#SettingManagerGridData', 'SettingManagerGridData', getCommentItemRoute, setting_grid_columns);
    });
    var create_comment_settings_constraints = {
        title: {
            presence: {message: '^<strong>عنوان فرم ضروی است.</strong>'}
        },
        morph_id: {
            presence: {message: '^<strong>انتخاب دسته الزامی است.</strong>'},
            exclusion: {
                within: {0: "bo-select"},
                message: "^<strong>انتخاب دسته الزامی است.</strong>"
            }
        }
    }
    var create_settings_form_id = document.querySelector("#frm_create_comment_item");
    init_validatejs(create_settings_form_id, create_comment_settings_constraints, ajax_func_create_comment_settings);

    function ajax_func_create_comment_settings(formElement) {
        var formData = new FormData(formElement);
        $.ajax({
            type: "POST",
            url: '{{ route('LCS.createCommentItems')}}',
            dataType: "json",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
            $('#frm_create_comment_item .total_loader').remove();
            if (data.success) {
                clear_form_elements('#frm_create_comment_item');
                menotify('success', data.title, data.message);
                SettingManagerGridData.ajax.reload(null, false);
                $('a[href="#manage_tab"]').click();
            }
            else {
                showMessages(data.message, 'form_message_box', 'error', formElement);
                showErrors(formElement, data.errors);
            }
        }
    });
    }

    $(document).off("click", ".btn_edit_settings");
    $(document).on("click", ".btn_edit_settings", function () {
        var item_id = $(this).data('item_id');
        var title = $(this).data('title');
        $('.span_edit_setting_tab').html('ویرایش : ' + title);
        get_edit_settings_form(item_id);
    });

    function get_edit_settings_form(item_id) {
        $('#edit_setting').children().remove();
        $('#edit_setting').append(generate_loader_html('لطفا منتظر بمانید...'));
        $.ajax({
            type: "POST",
            url: '{{ route('LCS.getEditSettingsForm')}}',
            dataType: "json",
            data: {
            item_id: item_id
        },
        success: function (result) {
            $('#edit_setting .total_loader').remove();
            if (result.success) {
                $('#edit_setting').append(result.setting_edit_view);
                $('.edit_setting_tab').removeClass('hidden');
                $('a[href="#edit_setting"]').click();

                var edit_settting_form_id = document.querySelector("#frm_edit_comment_item");
                init_validatejs(edit_settting_form_id, create_comment_settings_constraints, ajax_func_edit_setting);
            }
            else {
            }
        }
    });
    }

    function ajax_func_edit_setting(formElement) {
        var formData = new FormData(formElement);
        $.ajax({
            type: "POST",
            url: '{{ route('LCS.editSetting')}}',
            dataType: "json",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
            $('#frm_edit_portfolio .total_loader').remove();
            if (data.is_active == -1) {
                showMessages(data.message, 'form_message_box', 'error', formElement);
                showErrors(formElement, data.errors);
            }
            else {
                menotify('success', data.title, data.message);
                SettingManagerGridData.ajax.reload(null, false);
                $('a[href="#manage_tab"]').click();
                $('.edit_setting_tab').addClass('hidden');
                $('#edit_setting').html('');
            }
        }
    });
    }

    $(document).off("click", ".cancel_edit_setting");
    $(document).on("click", ".cancel_edit_setting", function () {
        $('a[href="#manage_tab"]').click();
        $('.edit_setting_tab').addClass('hidden');
        $('#edit_setting').html('');
    });

    /*___________________________________________________Change is_active_____________________________________________________________________*/
    function change_is_active_setting(input) {
        var checked = input.checked;
        var item_id = input.id;
        var parameters = {is_active: checked, item_id: item_id};
        yesNoAlert('تغییر وضعیت آیتم', 'از تغییر وضعیت آیتم مطمئن هستید ؟', 'warning', 'بله، وضعیت آیتم را تغییر بده!', 'لغو', set_setting_is_active, parameters, remove_checked, parameters);
    }

    function set_setting_is_active(params) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '{!!  route('LCS.changeSettingStatus') !!}',
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

    $(document).off("click", ".btn_trash_settings");
    $(document).on("click", ".btn_trash_settings", function () {
        var item_id = $(this).data('item_id');
        var title = $(this).data('title');
        desc = 'بله مجموعه( ' + title + ' ) را حذف کن !';
        var parameters = {item_id: item_id};
        yesNoAlert('حذف مجموعه', 'از حذف مجموعه مطمئن هستید ؟', 'warning', desc, 'لغو', trash_setting, parameters);
    });

    function trash_setting(params) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '{!!  route('LCS.trashSetting') !!}',
            data: params,
            success: function (data) {
            if (!data.success) {
                showMessages(data.message, 'form_message_box', 'error', formElement);
                showErrors(formElement, data.errors);
            }
            else {
                menotify('success', data.title, data.message);
                SettingManagerGridData.ajax.reload(null, false);
            }
        }
    });
    }
</script>