<div>
    @if(isset($errors))
        @foreach($errors as $error)
            <div class="alert alert-danger">{!! $error !!}</div>
        @endforeach
    @endif
    <div class="row">
        @if($model->lock_course == 0)
            <div class="col-md-12 act-btns">
                <div class="pull-right">
                    <div class="wrraped_register text-right">
                        @canany(['offline-course-register-create'])
                            <div class="btn-group">
                                <button class="btn" id="model-list-template-import"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</button>
                                <button class="btn" id="model-list-import"><i class="fa fa-upload"></i> {{ trans('labutton.import') }}</button>
                                @if ($model->lock_course != 1)
                                    <button type="button" class="btn" onclick="create()">
                                        <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                                    </button>
                                    <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                                @endif
                            </div>
                        @endcanany
                    </div>
                </div>
            </div>
        @endif
    </div>
    <br>

    <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500]" id="list-class">
        <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="code">{{ trans('latraining.class_room_code') }}</th>
                <th data-sortable="true" data-width="25%" data-field="name" data-formatter="class_name_formatter">{{ trans('latraining.class_room_name') }}</th>
                <th data-field="students" data-align="center" data-width="5%">{{ trans('latraining.quantity') }}</th>
                <th data-field="" data-formatter="training_time_formatter">{{ trans('latraining.training_time') }}</th>
                <th data-formatter="schedule_formatter" data-align="center" data-width="5%">{{ trans('latraining.schedule') }}</th>
                <th data-formatter="register_formatter" data-align="center" data-width="5%">{{ trans('latraining.register') }}</th>
                <th data-formatter="attendance_formatter" data-align="center" data-width="5%">{{ trans('latraining.attendance') }}</th>
                <th data-formatter="result_formatter" data-align="center" data-width="5%">{{ trans('latraining.result') }}</th>
                <th data-formatter="evaluate_formatter" data-align="center" data-width="5%">{{ trans('latraining.evaluate') }}</th>
            </tr>
        </thead>
    </table>
</div>
<div class="modal right fade" id="modal-class" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="" autocomplete="off"  class="form-ajax form-horizontal" role="form" enctype="multipart/form-data" id="form_save" onsubmit="return false;">
                <input type="hidden" name="id" value="">
                <div class="modal-header">
                    <div class="btn-group">
                        <h5 class="modal-title" id="exampleModalLabelClass"></h5>
                    </div>
                    <div class="btn-group act-btns">
                        @canany('offline-course-edit')
                            <button type="button" id="btn_save" onclick="save(event)" class="btn save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                        @endcanany
                        <button data-dismiss="modal" aria-label="Close" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-sm-3 pr-0 control-label">
                                    <label>{{ trans('latraining.classroom') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-8">
                                    <input name="name" type="text" class="form-control" placeholder="{{ trans('latraining.classroom') }}" value="" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 pr-0 control-label">
                                    <label>{{ trans('lasuggest_plan.number_student') }}</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="number" id="quantity" name="students" min="1" max="{{ $model->max_student }}" class="form-control" placeholder="{{ trans('lasuggest_plan.number_student') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 pr-0 control-label">
                                    <label>{{ trans('latraining.training_time') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-8 d_flex_align">
                                    <span>
                                        <input type="text"
                                            class="form-control datepicker_class"
                                            name="start_date"
                                            autocomplete="off"
                                            placeholder="Ngày bắt đầu"
                                        >
                                    </span>
                                    <span class="fa fa-arrow-right mx-1"></span>
                                    <span>
                                        <input type="text"
                                            class="form-control datepicker_class"
                                            name="end_date"
                                            autocomplete="off"
                                            placeholder="Ngày kết thúc"
                                        >
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 pr-0 control-label">
                                    <label>{{ trans('latraining.monitoring_staff') }}</label>
                                </div>
                                <div class="col-md-8 d_flex_align">
                                    <select name="monitoring_staff[]" id="monitoring_staff" class="form-control load-user" data-placeholder="{{ trans('latraining.monitoring_staff') }}" multiple>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row wrap-location">
                                <div class="col-sm-3 pr-0 control-label">
                                    <label>{{trans('latraining.training_location')}} </label>
                                </div>
                                <div class="form-group col-md-8 m-0">
                                    <div class="row">
                                        <div class="col-12 mb-1">
                                            <select name="province" id="province_id" data-url="{{route('module.offline.filter.location')}}" class="select2 form-control" data-placeholder="{{trans('latraining.choose_province')}}">
                                                <option value=""></option>
                                                @foreach($province as $item)
                                                    <option value="{{ $item->code }}" @if($item->code == $model->training_location_province) selected @endif> {{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 mb-1">
                                            <select name="district" id="district_id" class="select2 form-control" data-placeholder="{{trans('latraining.choose_district')}}">
                                                @foreach($district as $item)
                                                    <option value="{{$item->id}}" @if( $item->id == $model->training_location_district) selected @endif> {{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <select name="training_location_id" id="training_location_id" data-placeholder="{{trans('latraining.choose_training_location')}}" class="form-control select2" data-url="{{route('module.offline.filter.traininglocation')}}">
                                                @foreach($training_location as $item)
                                                    <option value="{{$item->id}}" @if($item->id == $model->training_location_id) selected @endif> {{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-template-import" tabindex="-1" role="dialog" aria-labelledby="modal_template_import" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_template_import">{{ trans('laprofile.import_template') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-md-7 control-label"> {{ trans('latraining.schedule') }}</div>
                    <div class="col-md-5">
                        <a class="btn" href="{{ route('module.offline.export_template_schedule',['courseId' => $model->id]) }}">
                            <i class="fa fa-download"></i>
                            {{ trans('lacore.import_template') }}
                        </a>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-7 control-label"> {{ trans('latraining.register') }}</div>
                    <div class="col-md-5">
                        <a class="btn" href="{{ download_template('mau_import_ghi_danh_lop_hoc.xlsx') }}"><i class="fa fa-download"></i>
                            {{ trans('labutton.import_template') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="modal-import" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-import">{{ trans('laprofile.import') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-md-7 control-label"> {{ trans('latraining.schedule') }}</div>
                    <div class="col-md-5">
                        <button class="btn" id="import-schedule" type="button"><i class="fa fa-upload"></i>{{ trans('labutton.import') }}</button>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-7 control-label"> {{ trans('latraining.register') }}</div>
                    <div class="col-md-5">
                        <button class="btn" id="import-register" type="button"><i class="fa fa-upload"></i> {{ trans('labutton.import') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-import-schedule" tabindex="-1" role="dialog" aria-labelledby="modal_schedule" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" action="" autocomplete="off"  class="form-ajax form-horizontal" role="form" enctype="multipart/form-data" id="form_import" onsubmit="return false;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_schedule">{{ trans('labutton.import') }} {{ trans('latraining.schedule') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="file" name="file_schedule" id="file_schedule" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    <button type="button" onclick="importSchedule(event)" class="btn btn_import">{{ trans('labutton.import') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal-import-register" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" action="" autocomplete="off"  class="form-ajax form-horizontal" role="form" enctype="multipart/form-data" id="form_import" onsubmit="return false;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ trans('labutton.import') }} {{ trans('latraining.register') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="file" name="file_register" id="file_register" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    <button type="button" onclick="importRegister(event)" class="btn btn_import_register">{{ trans('labutton.import') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal-preview-import" tabindex="-1" role="dialog" aria-labelledby="modalPreviewImport" aria-hidden="true">
    <div class="modal-dialog modal_preview_import" role="document">
        <form method="post" action="" autocomplete="off"  class="form-ajax form-horizontal" role="form" enctype="multipart/form-data" id="form_import" onsubmit="return false;">
            <div class="modal-content">
                <div class="modal-header d_flex" style="justify-content: center;">
                    <h3 class="modal-title" id="modalPreviewImport">Danh sách Import</h3>
                </div>
                <div class="modal-body">
                    <table class="tDefault table table-hover" id="list-data-import">
                        <thead>
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Mã lớp học</th>
                                <th class="text-center">Ngày bắt đầu</th>
                                <th class="text-center">Thời gian bắt đầu</th>
                                <th class="text-center">Thời gian kết thúc</th>
                                <th class="text-center">giảng viên chính</th>
                                <th class="text-center">trợ giảng</th>
                                <th class="text-center">Chi phí/giờ <br> (giảng viên chính)</th>
                                <th class="text-center">Chi phí/giờ <br> (trợ giảng)</th>
                                <th class="text-center">Địa điểm đào tạo</th>
                                <th class="text-center">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="body_table_import text-center">

                        </tbody>
                    </table>
                    <hr>
                    <div class="row">
                        <div class="col-6 import_success">
                            <span>Thành công: <span class="total_success"></span></span>
                        </div>
                        <div class="col-6 import_fail">
                            <span>Thất bại: <span class="total_fail"></span></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="saveImportSchedule(0)" class="btn btn_deny_import" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    <button type="button" onclick="saveImportSchedule(1)" class="btn btn_save_import">{{ trans('labutton.save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal-preview-import-register" tabindex="-1" role="dialog" aria-labelledby="modalPreviewImportRegister" aria-hidden="true">
    <div class="modal-dialog modal_preview_import" role="document">
        <form method="post" action="" autocomplete="off"  class="form-ajax form-horizontal" role="form" enctype="multipart/form-data" id="form_import" onsubmit="return false;">
            <div class="modal-content">
                <div class="modal-header d_flex" style="justify-content: center;">
                    <h3 class="modal-title" id="modalPreviewImportRegister">Danh sách Import</h3>
                </div>
                <div class="modal-body">
                    <table class="tDefault table table-hover" id="list-data-import">
                        <thead>
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Mã nhân viên</th>
                                <th class="text-center">Mã lớp học</th>
                                <th class="text-center">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="body_table_import_register text-center">

                        </tbody>
                    </table>
                    <hr>
                    <div class="row">
                        <div class="col-6 import_success">
                            <span>Thành công: <span class="total_success_register"></span></span>
                        </div>
                        <div class="col-6 import_fail">
                            <span>Thất bại: <span class="total_fail_register"></span></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="saveImportRegister(0)" class="btn btn_deny_import_register" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    <button type="button" onclick="saveImportRegister(1)" class="btn btn_save_import_register">{{ trans('labutton.save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    function info_formatter(value, row, index) {
        return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.info_url+'"> <i class="fa fa-info-circle"></i></a>';
    }
    function class_name_formatter(value, row, index) {
        return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
    }
    function evaluate_formatter(value, row, index) {
        return '<a href="'+row.evaluate_url+'"><i class="fa fa-star"></i></a>'
    }
    function register_formatter(value, row, index) {
        return '<a href="'+row.register_url+'"><i class="fa fa-user-plus"></i></a>'
    }
    function result_formatter(value, row, index) {
        return '<a href="'+row.result_url+'"><i class="fa fa-briefcase"></i></a>'
    }
    function schedule_formatter(value, row, index) {
        return '<a href="'+row.schedule_url+'"><i class="far fa-calendar-alt"></i></a>'
    }
    function attendance_formatter(value, row, index) {
        return '<a href="'+row.attendance_url+'"><i class="fa fa-user-circle"></i></a>'
    }
    function status_formatter(value, row, index) {
        if (value == 1) {
            return '<span class="text-success">{{ trans("latraining.approved") }}</span>';
        }
        else if (value == 0) {
            return '<span class="text-danger">{{ trans("latraining.deny") }}</span>';
        }
        else {
            return '<span class="text-warning">{{ trans("latraining.not_approved") }}</span>';
        }

    }
    function training_time_formatter(value, row, index) {
        return row.start_date+' <i class="fa fa-arrow-right"></i> '+row.end_date;
    }
    function approved_formatter(value, row, index) {
        return value? `<a href="javascript:void(0)" data-id="${row.id}" data-model="el_offline_register" class="text-success font-weight-bold load-modal-approved-step">${value}</a>`:'-';
    }
    function unit_status_formatter(value, row, index) {
        return row.status_level_1 == 1 ? '<span class="text-primary">{{ trans("latraining.approved") }}</span>' : row.status_level_1 == 0 ? '<span ' +
            'class="text-danger">{{ trans("latraining.deny") }}</span>' : '<span class="text-warning">{{ trans("latraining.not_approved") }}</span>';
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.offline.class.getdata', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.offline.class.remove_class', ['courseId' => $model->id]) }}',
        table: '#list-class',
        form_search: '#form-search-user'
    });

    $('#model-list-import').on('click', function () {
        $('#modal-import').modal();
    });

    $('#model-list-template-import').on('click', function () {
        $('#modal-template-import').modal();
    });

    function save(event) {
        let item = $('.save');
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
        $('.save').attr('disabled',true);
        event.preventDefault();
        $.ajax({
            url: "{{ route('module.offline.class.save_class', ['courseId' => $model->id]) }}",
            type: 'post',
            data: $('#form_save').serialize()
        }).done(function(data) {
            item.html(oldtext);
            $('.save').attr('disabled',false);
            if (data && data.status == 'success') {
                $('#modal-class').modal('hide');
                show_message(data.message, data.status);
                $(table.table).bootstrapTable('refresh');
            } else {
                show_message(data.message, data.status);
            }
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    }

    var province_id = '{{ $model->training_location_province }}';
    var html_district_old = $("#district_id").html();
    var html_training_location_old = $("#training_location_id").html();
    function edit(id){
        let item = $('#edit_'+id);
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
        $.ajax({
            url: "{{ route('module.offline.class.edit_class', ['courseId' => $model->id]) }}",
            type: 'post',
            data: {
                id: id,
            }
        }).done(function(data) {
            item.html(oldtext);
            $('#exampleModalLabelClass').html('{{ trans('labutton.edit') }}');
            $("input[name=id]").val(data.model.id);
            $("input[name=name]").val(data.model.name);
            $("input[name=students]").val(data.model.students);
            $("input[name=start_date]").val(data.model.start_date);
            $("input[name=end_date]").val(data.model.end_date);

            $("#monitoring_staff").html('');
            if (data.monitoringStaff) {
                $.each(data.monitoringStaff, function (index, value) {
                    $("#monitoring_staff").append('<option value="'+ value.user_id +'" selected>'+ value.code + ' - ' +  value.full_name +'</option>');
                });
            }

            if(data.province_id) {
                $('#province_id').val(data.province_id).trigger('change');
            } else {
                $('#province_id').val('').trigger('change');
            }
            
            if (data.district) {
                $("#district_id").html('')
                $.each(data.district, function (index, value) {
                    var selected = value.id == data.district_id ? 'selected' : '';
                    $("#district_id").append('<option value="'+ value.id +'" '+ selected +'>'+ value.name +'</option>');
                });
            } else {
                $("#district_id").html('')
            }

            if (data.training_location) {
                $("#training_location_id").html('')
                $.each(data.training_location, function (index, value) {
                    var selected = value.id == data.training_location_id ? 'selected' : '';
                    $("#training_location_id").append('<option value="'+ value.id +'" '+ selected +'>'+ value.name +'</option>');
                });
            } else {
                $("#training_location_id").html('')
            }

            $('#modal-class').modal();
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    }

    function create() {
        $('#exampleModalLabelClass').html('{{ trans('labutton.add_new') }}');
        $('input[name="id"]').val('');
        $('input[name="name"]').val('');
        $('input[name="students"]').val('');
        $('input[name="start_date"]').val('').trigger('change');
        $('input[name="end_date"]').val('').trigger('change');
        $('#province_id').val(province_id).trigger('change');
        $('#district_id').html(html_district_old);
        $('#training_location_id').html(html_training_location_old);
        $("#monitoring_staff").html('');
        $('#modal-class').modal();
    }

    $('#import-schedule').on('click', function() {
        $('#modal-import-schedule').modal();
        $('#modal-import').modal('hide');
    });

    $('#import-register').on('click', function() {
        $('#modal-import-register').modal();
        $('#modal-import').modal('hide');
    });

    function importSchedule(event) {
        let item = $('.btn_import');
        let oldtext = item.html();
        item.attr('disabled',true)
        item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
        event.preventDefault();
        var file = $('#file_schedule')[0].files[0];
        var data = new FormData()
        data.append('import_file', file)
        $.ajax({
            url: "{{ route('module.offline.import_schedule', ['courseId' => $model->id]) }}",
            type: 'post',
            dataType: 'json',
            data: data,
            cache:false,
            contentType: false,
            processData: false
        }).done(function(data) {
            item.html(oldtext);
            item.attr('disabled',false)
            var html = '';
            data.data.forEach(element => {
                html += `<tr>
                            <th>`+ element[0] +`</th>
                            <th>`+ element[1] +`</th>
                            <th>`+ element[2] +`</th>
                            <th>`+ element[3] +`</th>
                            <th>`+ element[4] +`</th>
                            <th>`+ element[5] +`</th>
                            <th>`+ element[6] +`</th>
                            <th>`+ element[7] +`</th>
                            <th>`+ element[8] +`</th>
                            <th>`+ element[9] +`</th>
                            <th>`+ element[10] +`</th>
                        </tr>`
            });
            $('.body_table_import').html(html)
            $('.total_success').html(data.total_success)
            $('.total_fail').html(data.total_fail)
            $('#modal-preview-import').modal({backdrop: 'static', keyboard: false})
            $('#modal-import-schedule').modal('hide');
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    }

    function saveImportSchedule(type) {
        if(type == 0) {
            var item = $('.btn_deny_import');
        } else {
            var item = $('.btn_save_import');
        }
        let oldtext = item.html();
        item.attr('disabled',true)
        item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
        $.ajax({
            url: "{{ route('module.offline.save_import_schedule', ['courseId' => $model->id]) }}",
            type: 'post',
            data: {
                type: type
            },
        }).done(function(data) {
            item.html(oldtext);
            item.attr('disabled',false)
            $('#modal-preview-import').modal('hide');
            show_message(data.message, data.status)
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    }

    function importRegister(event) {
        let item = $('.btn_import_register');
        let oldtext = item.html();
        item.attr('disabled',true)
        item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
        event.preventDefault();
        var file = $('#file_register')[0].files[0];
        var data = new FormData()
        data.append('import_file', file)
        $.ajax({
            url: "{{ route('module.offline.import_register_class', ['courseId' => $model->id]) }}",
            type: 'post',
            dataType: 'json',
            data: data,
            cache:false,
            contentType: false,
            processData: false
        }).done(function(data) {
            item.html(oldtext);
            item.attr('disabled',false)
            var html = '';
            data.data.forEach(element => {
                html += `<tr>
                            <th>`+ element[0] +`</th>
                            <th>`+ element[1] +`</th>
                            <th>`+ element[2] +`</th>
                            <th>`+ element[3] +`</th>
                        </tr>`
            });
            $('.body_table_import_register').html(html)
            $('.total_success_register').html(data.total_success)
            $('.total_fail_register').html(data.total_fail)
            $('#modal-preview-import-register').modal({backdrop: 'static', keyboard: false})
            $('#modal-import-register').modal('hide');
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    }

    function saveImportRegister(type) {
        if(type == 0) {
            var item = $('.btn_deny_import_register');
        } else {
            var item = $('.btn_save_import_register');
        }
        let oldtext = item.html();
        item.attr('disabled',true)
        item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
        $.ajax({
            url: "{{ route('module.offline.save_import_register_class', ['courseId' => $model->id]) }}",
            type: 'post',
            data: {
                type: type
            },
        }).done(function(data) {
            item.html(oldtext);
            item.attr('disabled',false)
            $('#modal-preview-import-register').modal('hide');
            show_message(data.message, data.status)
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    }

    var courseStartDate = '{{ get_date($model->start_date, "Y-m-d") }}';
    var courseEndDate = '{{ get_date($model->end_date, "Y-m-d") }}';
    $( "#modal-class .datepicker_class" ).datetimepicker({
        locale: 'vi',
        format: 'DD/MM/YYYY',
        minDate: courseStartDate,
        maxDate: courseEndDate,
    });

    // ĐỊA ĐIỂM ĐÀO TẠO
    $('#province_id').on('select2:select', function (e) {
        $('#training_location_id').html('');
        var province_code = $('#province_id').val();
        $.ajax({
            url: "{{ route('backend.category.district.filter') }}",
            type: 'get',
            data: {
                province_id: province_code,
            }
        }).done(function(result) {
            if (result && result.length) {
                let html = '';
                html += '<option value="" disabled selected>Chọn Quận huyện</option>'
                $.each(result, function (i, item){
                    html+='<option value='+ item.id +'>'+ item.name +'</option>';
                });
                $('#district_id').html(html);
            } else {
                $('#district_id').html('<option></option>')
            }
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $('#district_id').on('select2:select', function (e) {
        var province = $('#province_id').val();
        var district = $('#district_id').val();
        loadTranginingLocation(province, district)
    });

    function loadTranginingLocation(province, district){
        $.ajax({
            type: "GET",
            url: $('select[name=training_location_id]').data('url'),
            dataType: 'json',
            data: {
                province_id: province,
                district_id: district
            },
            success: function (result) {
                if (result && result.length) {
                    let html = '';
                    html += '<option value="" disabled selected>Chọn Quận huyện</option>'
                    $.each(result, function (i, item){
                        html += '<option value='+ item.id +'>'+ item.name +'</option>';
                    });
                    $('#training_location_id').html(html);
                } else {
                    $('#training_location_id').html('<option></option>')
                }
            }
        });
    }
</script>
