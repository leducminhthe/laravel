    <div class="row">
        <div class="col-md-9">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('lasetting.object_belong') }}</label>
                </div>
                <div class="col-md-6">
                    <label class="radio-inline"><input type="radio" name="object" value="1" checked> {{ trans('lasetting.unit') }} </label>
                    <label class="radio-inline"><input type="radio" name="object" value="2"> {{ trans('lasetting.title') }} </label>
                    <label class="radio-inline"><input type="radio" name="object" value="3"> {{trans("lasetting.user")}} </label>
                </div>
            </div>
        <form method="post" action="{{ route('module.notify_send.save_object', ['id' => $model->id]) }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data" data-success="submit_success">
            <div id="object-unit">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label> {{ trans('lasetting.unit') }} </label>
                    </div>
                    <div class="col-md-9">
                        <select name="unit_id[]" id="unit_id" class="form-control load-unit" data-placeholder="-- {{ trans('lasetting.unit') }} --" multiple>

                        </select>
                    </div>
                </div>
            </div>
            <div id="object-title">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label> {{ trans('lasetting.title') }} </label>
                    </div>
                    <div class="col-md-9">
                        <select name="title_id[]" id="title_id" class="form-control load-title" data-placeholder="-- {{trans('lasetting.title')}} --" multiple>

                        </select>
                    </div>
                </div>
            </div>
            <div id="object-table">
                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                            <button type="submit" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                    </div>
                </div>
            </div>
        </form>
            <div id="object-user">
                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                        <a class="btn" href="{{ download_template('mau_import_doi_tuong_nguoi_dung.xlsx') }}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                        <button class="btn" id="import-plan" name="task" value="import">
                            <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" id="form-object">
            <div class="text-right">
                <button id="send-object" class="btn"><i class="fa fa-send"></i> {{ trans('labutton.send_notify') }}</button>
                <button id="delete-item" class="btn"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                <button id="delete-user" class="btn"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
            </div>
            <p></p>
            <div id="table-object">
                <table class="tDefault table table-hover bootstrap-table text-nowrap" id="table_object"> 
                    <thead>
                        <tr>
                            <th data-field="state" data-checkbox="true"></th>
                            <th data-field="unit_name"> {{ trans('lamenu.unit') }}</th>
                            <th data-field="title_name">{{ trans('latraining.title') }}</th>
                            <th data-field="time_send" data-align="center">{{ trans('lasetting.date_send') }}</th>
                            <th data-field="send_by" data-align="center">{{ trans('lasetting.user_send') }}</th>
                            <th data-field="status" data-formatter="status_formeter" data-align="center">{{trans('lasetting.status')}}</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="table-user-object">
                <table class="tDefault table table-hover bootstrap-table2 text-nowrap" id="table-user">
                    <thead>
                        <tr>
                            <th data-field="state" data-checkbox="true"></th>
                            <th data-field="profile_code" data-width="5%">{{ trans('backend.employee_code') }}</th>
                            <th data-field="profile_name" data-width="25%">{{ trans('backend.employee_name') }}</th>
                            <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                            <th data-field="parent_unit_name">{{ trans('backend.unit_manager') }}</th>
                            <th data-field="title_name">{{ trans('latraining.title') }}</th>
                            <th data-field="time_send" data-align="center">{{ trans('lasetting.date_send') }}</th>
                            <th data-field="send_by" data-align="center">{{ trans('lasetting.user_send') }}</th>
                            <th data-field="status" data-formatter="status_user_formeter" data-align="center">{{trans('lasetting.status')}}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>


<div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <form action="{{ route('module.notify_send.import_object', ['id' => $model->id]) }}" method="post" class="form-ajax">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ trans('lasetting.import_user') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    <div class="form-group row mt-2">
                        <div class="col-md-4">
                            <label for="">Chọn khóa chính <span class="text-danger">(*)</span></label>
                        </div>
                        <div class="col-md-8">
                            <label class="radio-inline">
                                <input type="radio" name="type_import" class="mr-1" value="1" checked>
                                {{ trans('latraining.employee_code') }}
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="type_import" class="mr-1" value="2">
                                Username
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="type_import" class="mr-1" value="3">
                                Email
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    <button type="submit" class="btn">{{ trans('labutton.import') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    function status_formeter(value, row, index) {
        return value == 1 ? '{{ trans("backend.sent") }}' : '{{ trans("backend.unsent") }}';
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.notify_send.get_object', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.notify_send.remove_object', ['id' => $model->id]) }}',
        detete_button: '#delete-item',
        table: '#table_object'
    });

    function status_user_formeter(value, row, index) {
        return value == 1 ? '{{ trans("backend.sent") }}' : '{{ trans("backend.unsent") }}';
    }

    var table_user = new LoadBootstrapTable({
        url: '{{ route('module.notify_send.get_user_object', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.notify_send.remove_object', ['id' => $model->id]) }}',
        detete_button: '#delete-user',
        table: '#table-user'
    });
</script>

<script type="text/javascript">
    function submit_success(form) {
        $("#object-title select[name=title_id\\[\\]]").val(null).trigger('change');
        $("#object-unit select[name=unit_id\\[\\]]").val(null).trigger('change');
        table.refresh();
        table_user.refresh();
    }

    $('#import-plan').on('click', function() {
        $('#modal-import').modal();
    });

    var object = $("input[name=object]").val();
    if (object == 1) {
        $("#object-table").show('slow');
        $("#object-unit").show('slow');
        $("#object-title").hide('slow');
        $("#object-user").hide('slow');
        $("#table-object").show('slow');
        $("#table-user-object").hide('slow');
        $('#delete-item').show();
        $('#delete-user').hide();
    } else if (object == 2) {
        $("#object-table").show('slow');
        $("#object-unit").hide('slow');
        $("#object-title").show('slow');
        $("#object-user").hide('slow');
        $("#table-object").show('slow');
        $("#table-user-object").hide('slow');
        $('#delete-item').show();
        $('#delete-user').hide();
    } else {
        $("#object-table").hide('slow');
        $("#object-unit").hide('slow');
        $("#object-title").hide('slow');
        $("#object-user").show('slow');
        $("#table-object").hide('slow');
        $("#table-user-object").show('slow');
        $('#delete-item').hide();
        $('#delete-user').show();
    }

    $("input[name=object]").on('change', function () {
        var object = $(this).val();
        if (object == 1) {
            $("#object-table").show('slow');
            $("#object-unit").show('slow');
            $("#object-title").hide('slow');
            $("#object-user").hide('slow');
            $("#object-title select[name=title_id\\[\\]]").val(null).trigger('change');
            $("#table-object").show('slow');
            $("#table-user-object").hide('slow');
            $('#delete-item').show();
            $('#delete-user').hide();
        }
        else if (object == 2) {
            $("#object-table").show('slow');
            $("#object-unit").hide('slow');
            $("#object-title").show('slow');
            $("#object-user").hide('slow');
            $("#object-unit select[name=unit_id\\[\\]]").val(null).trigger('change');
            $("#table-object").show('slow');
            $("#table-user-object").hide('slow');
            $('#delete-item').show();
            $('#delete-user').hide();
        }
        else {
            $("#object-table").hide('slow');
            $("#object-unit").hide('slow');
            $("#object-title").hide('slow');
            $("#object-user").show('slow');
            $("#object-title select[name=title_id\\[\\]]").val(null).trigger('change');
            $("#object-unit select[name=unit_id\\[\\]]").val(null).trigger('change');
            $("#table-object").hide('slow');
            $("#table-user-object").show('slow');
            $('#delete-item').hide();
            $('#delete-user').show();
        }
    });

    $('#send-object').on('click', function () {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất 1 đối tượng', 'error');
            return false;
        }

        $.ajax({
            url: "{{ route('module.notify_send.send_object', ['id' => $model->id]) }}",
            type: 'post',
            data: {
                ids: ids,
            }
        }).done(function(data) {
            show_message(data.message, data.status);
            table.refresh();
            table_user.refresh();
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

</script>
