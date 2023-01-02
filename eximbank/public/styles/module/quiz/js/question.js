$(document).ready(function () {

    $('.status').on('click', function () {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        var status = $(this).data('status');

        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất 1 câu hỏi', 'error');
            return false;
        }

        var btnsubmit = $(this);
        var oldText = btnsubmit.text();
        var currentIcon = btnsubmit.find('i').attr('class');
        var exists = btnsubmit.find('i').length;
        if (exists>0)
            btnsubmit.find('i').attr('class', 'fa fa-spinner fa-spin');
        else
            btnsubmit.html('<i class="fa fa-spinner fa-spin"></i>'+oldText);

        btnsubmit.prop("disabled", true);

        $.ajax({
            url: ajax_status,
            type: 'post',
            data: {
                ids: ids,
                status: status
            }
        }).done(function(data) {
            $(table.table).bootstrapTable('refresh');

            if (exists>0)
                btnsubmit.find('i').attr('class', currentIcon);
            else
                btnsubmit.html(oldText);
            btnsubmit.prop("disabled", false);

            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');

            if (exists>0)
                btnsubmit.find('i').attr('class', currentIcon);
            else
                btnsubmit.html(oldText);
            btnsubmit.prop("disabled", false);

            return false;
        });
    });
});
