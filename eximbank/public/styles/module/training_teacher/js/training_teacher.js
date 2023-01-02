$(document).ready(function () {
    var type = $('#type').val();

    if(type == 1){
        $('#training_partner').hide();
        $('#code').attr('readonly', true);
        $('#name').attr('readonly', true);
        $('#email').attr('readonly', true);
    }else {
        $('#training_partner').show();
        $('#code').attr('readonly', false);
        $('#name').attr('readonly', false);
        $('#email').attr('readonly', false);
    }

    $('#type').on('change', function() {
        var item = $('#type option:selected').val();
        if(item == 1){
            $('#form-internal').show();
            $('#training_partner').hide();
            $('#code').attr('readonly', true);
            $('#name').attr('readonly', true);
            $('#email').attr('readonly', true);
        }else{
            $('#form-internal').hide();
            $('#training_partner').show();
            $('#code').val('');
            $('#name').val('');
            $('#email').val('');
            $('#phone').val('');
            $('#unit').val('');
            $('#title').val('');
            $('#code').attr('readonly', false);
            $('#name').attr('readonly', false);
            $('#email').attr('readonly', false);
        }
    });
    $('#user_id').on('change', function() {
        var ids = $('#user_id option:selected').val();
        $.ajax({
            url: ajax_get_user,
            type: 'post',
            data: { 
                ids: ids,
            },
            success: function (ids) {
                return false;
            }
        }).done(function(data) {
            $('#code').val(data.code);
            $('#name').val(data.name);
            $('#email').val(data.email);
            $('#phone').val(data.phone);
            $('#unit').val(data.unit);
            $('#title').val(data.title);
            return false;
        }).fail(function(data) {
            
            Swal.fire(
                '',
                'Lỗi hệ thống',
                'error'
            );
            return false;
        });
    });
});