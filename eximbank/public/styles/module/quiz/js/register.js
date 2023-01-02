$(document).ready(function() {
    var ids_register = [];
    var ids_register_secondary = [];
    $('#button-register').on('click', function() {
        ids_register = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        let button = $(this);
        let icon = button.find('i').attr('class');
        button.find('i').attr('class', 'fa fa-spinner fa-spin');
        button.prop("disabled", true);

        if (ids_register.length <= 0) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);

            show_message('Vui lòng chọn ít nhất 1 nhân viên', 'error');
            return false;
        }

        $('#modal-part').modal();

        button.find('i').attr('class', icon);
        button.prop("disabled", false);
    });

    $('#button-part').on('click', function(){
        var part_id = $('#part option:selected').val();
        let button = $(this);
        let icon = button.find('i').attr('class');
        button.find('i').attr('class', 'fa fa-spinner fa-spin');
        button.prop("disabled", true);

        if (part_id.length <= 0) {
            show_message('Vui lòng chọn ca thi', 'error');
            button.prop("disabled", false);
            return false;
        }

        $.ajax({
            type: 'POST',
            url: ajax_get_user,
            data: {
                ids: ids_register,
                part_id: part_id,
            },
        }).done(function(data) {
            show_message(data.message, data.status);
            if(data.status == 'success') {
                ids_register = []
            }
            button.find('i').attr('class', icon);
            button.prop("disabled", false);
            $('#part').val('').trigger('change.select2');
            $("#modal-part").modal('hide');
            $(table.table).bootstrapTable('refresh');
            return false;

        }).fail(function(data) {

            show_message(
                'Lỗi hệ thống',
                'error'
            );
            return false;
        });
    });

    $('#button-register-secondary').on('click', function() {
        ids_register_secondary = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        let button = $(this);
        let icon = button.find('i').attr('class');
        button.find('i').attr('class', 'fa fa-spinner fa-spin');
        button.prop("disabled", true);

        if (ids_register_secondary.length <= 0) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);

            show_message('Vui lòng chọn ít nhất 1 nhân viên', 'error');
            return false;
        }

        $('#modal-part').modal();

        button.find('i').attr('class', icon);
        button.prop("disabled", false);
    });

    $('#button-part-secondary').on('click', function(){
        var part_id = $('#part-secondary option:selected').val();
        let button = $(this);
        let icon = button.find('i').attr('class');
        button.find('i').attr('class', 'fa fa-spinner fa-spin');
        button.prop("disabled", true);

        if (part_id.length <= 0) {
            show_message('Vui lòng chọn ca thi', 'error');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: ajax_get_user_secondary,
            data: {
                ids: ids_register_secondary,
                part_id: part_id,
            },
        }).done(function(data) {
            button.find('i').attr('class', icon);
            button.prop("disabled", false);
            $('#part-secondary').val('').trigger('change.select2');
            show_message(
                'Ghi danh thành công',
                'success'
            );
            ids_register_secondary = [];
            $("#modal-part").modal('hide');
            $(table.table).bootstrapTable('refresh');
            return false;

        }).fail(function(data) {

            show_message(
                'Lỗi hệ thống',
                'error'
            );
            return false;
        });
    });
});
