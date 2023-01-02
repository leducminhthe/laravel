$(document).ready(function () {
    $('#user-unit-top').on('change', function (e) {
        e.preventDefault();
        let url = $(this).data('url');
        let data = {'unit-select':$(this).val()};
        $.ajax({
            type: 'post',
            url,
            dataType: 'json',
            data
        }).done(function(response) {
            location.href = response.redirect;
            return false;
        }).fail(function(response) {
            show_message('DATA ERROR', 'error');
            return false;
        });
    });
    $('.approved').on('click',function (e) {
        e.preventDefault();
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        var status = $(this).data('status');
        let $this = $(this);
        let model = $this.data('model');
        let courseId = $this.data('course_id');
        let approveAll = $this.data('approve_all');
        let classId = $this.data('class_id');

        let item = $('#dropdownMenuButton');
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i>');

        if (ids.length <= 0 && !approveAll) {
            item.html(oldtext);
            show_message('Vui lòng chọn ít nhất dòng dữ liệu', 'error');
            return false;
        }
        if (!status){
            item.html(oldtext);
            load_modal_approve_note(model,$this);
            return  false;
        }

        item.prop("disabled", true);
        $.ajax({
            url: base_url +'/admin-cp/approve',
            type: 'post',
            // dataType: 'json',
            data: {
                ids: ids,
                status: status,
                model: model,
                courseId: courseId,
                approveAll: approveAll,
                classId: classId,
            }
        }).done(function(result) {
            console.log(result);
            item.html(oldtext);
            item.prop("disabled", false);

            if (result.status == 'success'){
                $this.closest('.wrapper').find('.bootstrap-table').bootstrapTable('refresh');
            }
            show_message(result.message, result.status);

            return false;
        }).fail(function(result) {
            show_message('Lỗi dữ liệu', 'error');

            item.find('i').attr('class', current_icon);
            item.prop("disabled", false);

            return false;
        });
    });

    $(document).on('click','#update-note-approved',function () {
        var model = $(this).data('model');
        var table = $('input[name="table"]').val();
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        var note = $('#txta-note-approved').val();

        let btn = $(this);
        let current_icon = btn.find('i').attr('class');
        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop("disabled", true);

        $.ajax({
            url: base_url +'/admin-cp/approve',
            type: 'post',
            data: {
                ids: ids,
                status: 0,
                note:note,
                model:model
            }
        }).done(function(result) {

            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);

            $('body').find('.bootstrap-table').bootstrapTable('refresh');
            $('.modal-note-approved').modal('hide');

            show_message(result.message, result.status);

            return false;
        }).fail(function(result) {

            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);

            show_message('Lỗi dữ liệu', 'error');

            return false;
        });
    });

    $(document).on('click','.load-modal-approved-step',function () {
        var unit_id = $(this).data('parent_unit');
        var $id = $(this).data('id');
        let model = $(this).data('model');
        let item = $(this);
        let text = item.html();
        item.html('<i class ="fa fa-spinner fa-spin"></i>');
        item.find('i').prop("disabled", true);
        $.ajax({
            type: 'POST',
            url: base_url +'/admin-cp/approve/modal-step-approved',
            dataType: 'html',
        }).done(function(data) {
            $("#app-modal").html(data);
            $('.table-approved-step').bootstrapTable("destroy");
            $('.table-show-permission-approve').bootstrapTable("destroy");
            var tableApprovedStep = new LoadBootstrapTable({
                table: '.table-approved-step',
                url: base_url+'/admin-cp/approve/get-approved-step?model='+model+'&model_id='+$id,
            });
            var tableShowPermissionApprove = new LoadBootstrapTable({
                table: '.table-show-permission-approve',
                url: base_url + '/admin-cp/approve/get-permission-approve?model='+model+'&unit_id='+unit_id,
            });
            $('#modal-approved-step').modal();
            item.html(text);
        }).fail(function(data) {
            item.html(text);
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });
});

