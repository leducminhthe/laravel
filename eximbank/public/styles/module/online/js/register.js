$(document).ready(function() {
    $('#button-register').on('click', function() {
        let btn = $(this);
        let current_icon = btn.find('i').attr('class');
        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop("disabled", true);

        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        if (ids.length <= 0) {
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);
            
            show_message('Vui lòng chọn ít nhất 1 nhân viên', 'error');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: ajax_get_user,
            dataType: 'json',
            data: {
                ids: ids
            },
        }).done(function(data) {
            show_message(
                'Ghi danh thành công',
                'success'
            );
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);

            $(table.table).bootstrapTable('refresh');
            return false;
        }).fail(function(data) {
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);
            
            show_message(
                'Lỗi hệ thống',
                'error'
            );
            return false;
        });
    });
});