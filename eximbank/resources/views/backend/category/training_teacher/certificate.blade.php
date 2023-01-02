
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-12 mb-2">
                <div class="float-right">
                    <button class="btn cursor_pointer" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                    <button type="button" class="btn btn-demo" onclick="create()">
                        <i class="fas fa-plus-circle"></i>
                        <span>{{ trans('laprofile.add_certificate') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row" id="course">
    <div class="col-md-12">
        <table class="tDefault table table-hover bootstrap-table" id="table_cert_teacher">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-formatter="name_formatter">{{ trans('laprofile.certificate_name') }}</th>
                    <th data-field="name_school">{{ trans('laprofile.certificate_school') }}</th>
                    <th data-field="rank" data-align="center">{{ trans('laprofile.rank') }}</th>
                    <th data-field="time_start" data-align="center">{{ trans('laprofile.study_time') }}</th>
                    <th data-field="date_license" data-align="center">{{ trans('laprofile.date_issue') }}</th>
                    <th data-field="date_effective" data-align="center">{{ trans('laprofile.effective_date') }}</th>
                    <th data-field="score" data-align="center">{{ trans('latraining.score') }}</th>
                    <th data-field="result" data-align="center">{{ trans('latraining.result') }}</th>
                    <th data-field="note" data-align="center">{{ trans('latraining.note') }}</th>
                    <th data-field="status" data-formatter="status_formatter" data-align="center">{{ trans('latraining.efficiency') }}</th>
                    <th data-field="certificate" data-formatter="certificate_formatter" data-align="center">{{ trans('laprofile.attach') }}</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="" class="form-ajax" role="form" enctype="multipart/form-data" id="form_save" onsubmit="return false;">
                <input type="hidden" name="id" value="">
                <div class="modal-header">
                    <div class="btn-group">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                    </div>
                    <div class="btn-group act-btns">
                        @canany(['category-teacher-create', 'category-teacher-edit'])
                            <button type="button" id="btn_save" onclick="saveForm(event)" class="btn save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                        @endcanany
                        <button data-dismiss="modal" aria-label="Close" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="name_certificate">{{ trans('laprofile.certificate_name') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input name="name_certificate" type="text" class="form-control" value="" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="name_school">{{ trans('laprofile.certificate_school') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input name="name_school" type="text" class="form-control" value="" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="rank">{{ trans('laprofile.rank') }}</label>
                                </div>
                                <div class="col-md-9">
                                    <input name="rank" type="text" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('laprofile.certificate_image') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <a href="javascript:void(0)" id="select-image"> {{trans('latraining.choose_picture')}}</a>
                                    <div id="image-review">
                                    </div>
                                    <input name="certificate" id="image-select" type="hidden" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('laprofile.study_time') }}</label>
                                </div>
                                <div class="col-md-9">
                                    <span>
                                        <input name="time_start"
                                            type="text"
                                            class="datepicker form-control w-25"
                                            placeholder="{{trans('laother.choose_start_date')}}"
                                            autocomplete="off" value=""
                                        >
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('laprofile.date_issue') }}</label>
                                </div>
                                <div class="col-md-9">
                                    <span>
                                        <input name="date_license"
                                            type="text" class="datepicker form-control w-25"
                                            placeholder="{{trans('laother.choose_start_date')}}"
                                            autocomplete="off" value=""
                                        >
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('laprofile.effective_date') }}</label>
                                </div>
                                <div class="col-md-9">
                                    <span>
                                        <input name="date_effective"
                                            type="text" class="datepicker form-control w-25"
                                            placeholder="{{trans('laother.choose_start_date')}}"
                                            autocomplete="off" value=""
                                        >
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="score">{{ trans('latraining.score') }}</label>
                                </div>
                                <div class="col-md-9">
                                    <input name="score" type="text" class="form-control is-number" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="result">{{ trans('latraining.result') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input name="result" type="text" class="form-control" value="" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('lacategory.efficiency') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <label class="radio-inline">
                                        <input id="enable" required type="radio" name="status" value="1" checked>{{ trans("latraining.still_effect") }}
                                    </label>
                                    <label class="radio-inline">
                                        <input id="disable" required type="radio" name="status" value="0" >{{ trans("laother.expire") }}
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="note">{{ trans('latraining.note') }}</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="note" id="note" placeholder="{{ trans('latraining.note') }}" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-show-certificate" tabindex="-1" role="dialog" aria-labelledby="modal-import-user" aria-hidden="true">
    <div class="modal-dialog modal_my_certificate" role="document">
        <div class="modal-content">
            <div class="modal-body body_my_certificate">

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    function name_formatter(value, row, index) {
        return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name_certificate +'</a>' ;
    }

    function certificate_formatter(value, row, index) {
        return '<span class="cursor_pointer" onclick="showCertificate(' + row.id + ')"> <i class="fa fa-image"></i></span>';
    }

    function status_formatter(value, row, index){
        return row.status == 1 ? '<span class="text-success">{{ trans("latraining.still_effect") }}</span>' : '<span class="text-danger">{{ trans("laother.expire") }}</span>';
    }

    var table_cert_teacher = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: "{{ route('backend.category.training_teacher.certificate.getdata', ['teacher_id' => $model->id]) }}",
        remove_url: "{{ route('backend.category.training_teacher.certificate.remove', ['teacher_id' => $model->id]) }}",
        table: '#table_cert_teacher',
    });

    function edit(id){
        let item = $('#edit_'+id);
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
        $.ajax({
            url: "{{ route('backend.category.training_teacher.certificate.edit', ['teacher_id' => $model->id]) }}",
            type: 'post',
            data: {
                id: id,
            }
        }).done(function(data) {
            item.html(oldtext);
            $('#exampleModalLabel').html('{{ trans('labutton.edit') }}');
            $('input[name=id]').val(data.model.id);
            $('input[name=name_certificate]').val(data.model.name_certificate);
            $('input[name=name_school]').val(data.model.name_school);
            $('input[name=rank]').val(data.model.rank);
            $('input[name=time_start]').val(data.model.time_start);
            $('input[name=date_license]').val(data.model.date_license);
            $('input[name=date_effective]').val(data.model.date_effective);
            $('input[name=score]').val(data.model.score);
            $('input[name=result]').val(data.model.result);
            $('#note').val(data.model.note);

            if (data.model.status == 1) {
                $('#enable').prop('checked', true);
                $('#disable').prop('checked', false);
            } else {
                $('#enable').prop('checked', false);
                $('#disable').prop('checked', true);
            }

            $("input[name=certificate]").val(data.model.certificate);
            $("#image-review").html('<img class="w-100" src="'+ data.path_image +'" alt="">');

            $('#myModal2').modal();
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    }

    function saveForm(event) {
        let item = $('.save');
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
        $('.save').attr('disabled',true);

        let formData = $("#form_save").serialize();

        event.preventDefault();
        $.ajax({
            url: "{{ route('backend.category.training_teacher.certificate.save', ['teacher_id' => $model->id]) }}",
            type: 'post',
            data: formData
        }).done(function(data) {
            item.html(oldtext);
            $('.save').attr('disabled',false);

            if (data && data.status == 'success') {
                $('#myModal2').modal('hide');

                show_message(data.message, data.status);
                $(table_cert_teacher.table).bootstrapTable('refresh');
            } else {
                show_message(data.message, data.status);
            }
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    }

    function create() {
        $("input[name=id]").val('');
        $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
        $('input[name=name_certificate]').val('');
        $('input[name=name_school]').val('');
        $('input[name=rank]').val('');
        $('input[name=time_start]').val('');
        $('input[name=date_license]').val('');
        $('input[name=date_effective]').val('');
        $('input[name=score]').val('');
        $('input[name=result]').val('');
        $('#note').val('');
        $("input[name=certificate]").val('');
        $("#image-review").html('<img src="" alt="">');

        $('#enable').prop('checked', true);
        $('#disable').prop('checked', false);

        $('#myModal2').modal();
    }

    $("#select-image").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-review").html('<img class="w-100" src="'+ path +'">');
            $("#image-select").val(path);
        });
    });

    function showCertificate(id) {
        $.ajax({
            type: "POST",
            url: "{{ route('backend.category.training_teacher.certificate.show_image', ['teacher_id' => $model->id]) }}",
            dataType: 'json',
            data: {
                'id': id,
            },
            success: function (result) {
                if (result.status == "success") {
                    $('.body_my_certificate').html('<img class="w-100" src="'+ result.img +'" alt="">');
                    $('#modal-show-certificate').modal();
                }

                show_message(result.message, result.status);
                return false;
            }
        });
    }
</script>
