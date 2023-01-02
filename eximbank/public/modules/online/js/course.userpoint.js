$(document).ready(function(){
    var type = $('.type').val();
    $('#frm_other').on('submit', function(e){
        var issubmit = true;
        var checkst= $('.promotion-status').map(function(){
            var id =$(this).data("id");
            if($(this).val()==1){
                var val =  $('.pp'+id).val();
                if(val==0 || val=='') {
                    $('.pp'+id).css('border-color', 'red');
                    issubmit = false;
                }
                else $('.pp'+id).css('border-color', '');
            }else $('.pp'+id).css('border-color', '');
        }).get();

        if(issubmit==false)
            e.preventDefault();
        else $('.promotion-point').css('border-color', '');
    });

    $('.promotion-status').on('change', function () {
        if ($(this).val() == 1) {
            $(this).closest('tr').find('.promotion-point').val('');
            $(this).closest('tr').find('.promotion-point').prop('readonly', false);
        }
        else {
            $(this).closest('tr').find('.promotion-point').val('');
            $(this).closest('tr').find('.promotion-point').prop('readonly', true);
        }
    });

    $('.table_setting_complete').on('click', '.remove-setting-item', function () {
        let item = $(this);
        let id = item.data('item');
        let course_id = item.data('course');

        if (!id) {
            return false;
        }

        if (!confirm('Bạn có chắc chắn muốn xóa mục này?')) {
            return false;
        }

        $.ajax({
            type: "POST",
            url: "/admin-cp/online/userpoint-setting/"+ course_id +"/delete/"+ type + "/" + id,
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