$(document).ready(function(){
    $('.table_setting_complete').on('click', '.remove-setting-item', function () {
        let item = $(this);
        let id = item.data('item');
        let quiz_id = item.data('quiz');

        if (!id) {
            return false;
        }

        if (!confirm('Bạn có chắc chắn muốn xóa mục này?')) {
            return false;
        }

        $.ajax({
            type: "POST",
            url: "/admin-cp/quiz/userpoint-setting/"+ quiz_id +"/delete/"+id,
            dataType: 'json',
            data: {
                'id': id,
            },
            success: function (result) {
                if (result.status) {
                    item.closest('tr').remove();
                }
            }
        });
    });

});