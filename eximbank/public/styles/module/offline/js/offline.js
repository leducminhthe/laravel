$(document).ready(function () {

    $('#training_program_id').on('change', function () {
        var training_program_id = $('#training_program_id option:selected').val();
        /*$("#level_subject_id").empty();
        $("#level_subject_id").data('training-program', training_program_id);
        $('#level_subject_id').trigger('change');*/

        $("#subject_id").empty();
        $("#subject_id").data('training-program', training_program_id);
        $('#subject_id').trigger('change');
    });

    $('#level_subject_id').on('change', function () {
        var training_program_id = $('#training_program_id option:selected').val();
        var level_subject_id = $('#level_subject_id option:selected').val();
        $("#subject_id").empty();
        $("#subject_id").data('training-program', training_program_id);
        $("#subject_id").data('level-subject', level_subject_id);
        $('#subject_id').trigger('change');
    });

    $('#subject_id').on('change', function() {
        var subject_id = $('#subject_id option:selected').val();
        var subject_name = $('#subject_id option:selected').text();
        var id = $('input[name=id]').val()
        $.ajax({
            url: ajax_get_course_code,
            type: 'post',
            data: {
                subject_id: subject_id,
                id: id,
            },
        }).done(function(data) {
            var d = new Date();
            if(subject_id != null){
                $('#code').val(data.course_code);
                $("input[name=name]").val(subject_name);
                $('#level_subject').val(data.level_subject_name).trigger('change');
                $('#description').text(data.description);
                $('#color').val(data.color);
                $('#i_text').val(data.i_text);
                $('#b_text').val(data.b_text);
                CKEDITOR.instances['content'].setData(data.content);

                if(id.length <= 0){
                    $('#training_program_id').html($('<option>', {
                        value: data.training_program_id,
                        text: data.training_program_code +' - '+ data.training_program_name,
                    }));

                    $("input[name=image]").val(data.image);
                    $("#image-review").html('<img class="w-100" src="'+ data.path_image +'" alt="">');
                }

                if (data.i_text == 1) {
                    $('#i_text').prop( 'checked', true )
                } else {
                    $('#i_text').prop( 'checked', false )
                }

                if (data.b_text == 1) {
                    $('#b_text').prop( 'checked', true )
                } else {
                    $('#b_text').prop( 'checked', false )
                }
            }
            return false;
        }).fail(function(data) {

            show_message(
                'Lỗi hệ thống',
                'error'
            );
            return false;
        });
    });

    $('#has_cert').on('change', function() {
        if($(this).is(':checked')) {
            $("#cert_code").prop('disabled', false);
            $("input[name=has_cert]").val(1);
        }
        else {
            $("#cert_code").prop('disabled', true);
            $("input[name=has_cert]").val(0);
        }
    });

    $('#commit').on('change', function() {
        if($(this).is(':checked')) {
            $(this).val(1);
            $("input[name=commit_date]").prop('disabled',false).fadeIn();
            $("input[name=coefficient]").prop('disabled',false).fadeIn();
        }
        else {
            $(this).val(0);
            $("input[name=commit_date]").fadeOut();
            $("input[name=commit_date]").val('');
            // $("input[name=commit_date]").prop('disabled',true).fadeOut();

            $("input[name=coefficient]").fadeOut();
            $("input[name=coefficient]").val('');
            // $("input[name=coefficient]").prop('disabled',true).fadeOut();
        }
    });

    $('.approve').on('click', function () {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        var status = $(this).data('status');

        if (ids.length <= 0) {
            show_message('{{ trans("lacore.min_one_course") }}', 'error');
            return false;
        }

        $.ajax({
            url: base_url +'/admin-cp/offline/approve',
            type: 'post',
            data: {
                ids: ids,
                status: status
            }
        }).done(function(data) {
            $(table.table).bootstrapTable('refresh');
            $('.btn_action_table').toggle(false);
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $("#send-mail-approve").on('click', function () {
        let item = $('#dropdownMenuButton');
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i>');

        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

        if (ids.length <= 0) {
            show_message('{{ trans("lacore.min_one_course") }}', 'error');
            item.html(oldtext);
            return false;
        }

        Swal.fire({
            title: '',
            text: 'Gửi mail cho cấp duyệt yêu cầu duyệt khóa học',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý!',
            cancelButtonText: 'Hủy!',
        }).then((result) => {
            if (result.value) {
                item.prop("disabled", true);
                $.ajax({
                    url: base_url +'/admin-cp/offline/send-mail-approve',
                    type: 'post',
                    data: {
                        ids: ids,
                    }
                }).done(function(data) {
                    item.html(oldtext);
                    item.prop("disabled", false);
                    show_message(data.message, data.status);
                    table.refresh();
                    $('.btn_action_table').toggle(false);
                    return false;
                }).fail(function(data) {
                    show_message('Lỗi hệ thống', 'error');
                    return false;
                });
            } else {
                item.html(oldtext);
            }
        });
    });

    $("#send-mail-change").on('click', function () {
        let item = $('#dropdownMenuButton');
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i>');

        let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

        if (ids.length <= 0) {
            show_message('{{ trans("lacore.min_one_course") }}', 'error');
            item.html(oldtext);
            return false;
        }

        Swal.fire({
            title: '',
            text: 'Gửi mail báo khóa học đã được thay đổi?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý!',
            cancelButtonText: 'Hủy!',
        }).then((result) => {
            if (result.value) {
                item.prop("disabled", true);
                $.ajax({
                    url: base_url +'/admin-cp/offline/send-mail-change',
                    type: 'post',
                    data: {
                        ids: ids,
                    }
                }).done(function(data) {
                    show_message(data.message, data.status);
                    item.html(oldtext);
                    item.prop("disabled", false);
                    $('.btn_action_table').toggle(false);
                    table.refresh();
                    return false;
                }).fail(function(data) {
                    show_message('Lỗi hệ thống', 'error');
                    return false;
                });
            } else {
                item.html(oldtext);
            }
        });
    });

    $("#select-image").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-review").html('<img src="'+ path +'">');
            $("#image-select").val(path);
        });
    });

    $("#select-document").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'file'}, function (url, path) {
            var path2 =  path.split("/");
            $("#document-review").html(path2[path2.length - 1]);
            $("#document-select").val(path);
        });
    });

    $('#action_plan').on('change', function() {

        if($(this).val()==1) {
            $(".contain_plan_app_template").fadeIn();
            $('input[name=plan_app_day]').fadeIn();
            $('input[name=plan_app_day_student]').fadeIn();
            $('input[name=plan_app_day_manager]').fadeIn();
        }
        else {
            $("select[name=plan_app_template]").val(0).trigger('change');
            $(".contain_plan_app_template").fadeOut();
            $("input[name=plan_app_day]").val('');
            $('input[name=plan_app_day]').fadeOut();

            $("input[name=plan_app_day_student]").val('');
            $('input[name=plan_app_day_student]').fadeOut();

            $("input[name=plan_app_day_manager]").val('');
            $('input[name=plan_app_day_manager]').fadeOut();
        }

    }).trigger('change');

    // Laraberg.init('content', {
    //     height: '300px',
    //     laravelFilemanager: {prefix: '/filemanager'},
    //     sidebar: true,
    // });

    // SAO CHÉP KHÓA HỌC
    $('.copy').on('click', function () {
        let item = $('#dropdownMenuButton');
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i>');

        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

        if (ids.length <= 0) {
            show_message('{{ trans("lacore.min_one_course") }}', 'error');
            item.html(oldtext);
            return false;
        }
        item.prop("disabled", true);
        $.ajax({
            url: base_url +'/admin-cp/offline/copy',
            type: 'post',
            data: {
                ids: ids,
            }
        }).done(function(data) {
            item.html(oldtext);
            item.prop("disabled", false);
            
            $(table.table).bootstrapTable('refresh');
            $('.btn_action_table').toggle(false);
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });
});
