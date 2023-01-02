<div role="main">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <label class="col-sm-3 control-label">{{trans('backend.exams_name')}} <span class="text-danger">*</span></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="name_part" value="">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 control-label">{{trans('backend.time')}} <span class="text-danger">*</span></label>
                <div class="col-sm-6">
                    <div>
                        @php
                            $date_now_add_1 = Carbon\Carbon::now()->addDay()->format('d/m/Y');
                        @endphp
                        <input name="start_date" type="text" class="datepicker form-control w-25 d-inline-block date-custom" placeholder="{{trans('backend.start')}}" autocomplete="off" value="{{ $date_now_add_1 }}">
                        <select name="start_hour" id="start_hour" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 0; $i < 24; $i++)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{ $ii }}" {{ $i == 8 ? 'selected' : '' }}> {{ $ii }}</option>
                            @endfor
                        </select>
                        {{trans('backend.hour')}}
                        <select name="start_min" id="start_min" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 0; $i < 60; $i++)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{ $ii }}"> {{ $ii }}</option>
                            @endfor
                        </select>
                        {{trans('backend.minutes')}}
                    </div>
                    <div>
                        <input name="end_date" type="text" class="datepicker form-control w-25 d-inline-block date-custom" placeholder="{{trans('backend.over')}}" autocomplete="off" value="{{ $date_now_add_1 }}">
                        <select name="end_hour" id="end_hour" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 0; $i < 24; $i++)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{ $ii }}" {{ $i == 11 ? 'selected' : '' }}> {{ $ii }}</option>
                            @endfor
                        </select>
                        {{trans('backend.hour')}}
                        <select name="end_min" id="end_min" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 0; $i < 60; $i++)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{ $ii }}" {{ $i == 59 ? 'selected' : '' }}> {{ $ii }}</option>
                            @endfor
                        </select>
                        {{trans('backend.minutes')}}
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    @if ($quiz_type_by_offline != 'activity_quiz_id')
                        @canany(['quiz-create', 'quiz-edit'])
                            <button type="button" class="btn save-part">
                                <i class="fa fa-plus-circle"></i> {{trans('labutton.add_new')}}
                            </button>
                        @endcanany
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="tDefault table table-hover text-nowrap" id="table-part">
                <thead>
                    <tr>
                        <th data-field="name" data-align="center">{{trans('backend.exams_name')}}</th>
                        <th data-field="start_date" data-align="center" data-formatter="start_date_formatter">{{trans('backend.start')}}</th>
                        <th data-field="end_date" data-align="center" data-formatter="end_date_formatter">{{trans('backend.over')}}</th>
                        {{-- <th data-align="center" data-formatter="time_extension_formatter">{{ trans('labutton.extension') }}</th> --}}
                        <th data-align="center" data-formatter="edit_formatter">{{ trans('labutton.edit') }}</th>
                        <th data-field="action" data-formatter="action_delete_part_formatter" data-align="center" data-width="5%">{{trans('labutton.delete')}}</th>
                        <th data-formatter="get_qrcode" data-align="center" data-width="10%">Qr code</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-qrcode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('latraining.quiz_code') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div id="qrcode-wrap">
                        <div id="qrcode"></div>
                        <p>{{ trans('laquiz.scan_code_enter_exam') }}</p>
                    </div>
                    {{--  <a href="javascript:void(0)" id="print_qrcode">{{ trans('latraining.print_qr_code') }}</a>  --}}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-update-part" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('labutton.extension') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <input type="hidden" name="part_id" value="">
                    <input name="end_date" type="text" class="datepicker-modal form-control w-25 d-inline-block date-custom" placeholder="{{trans('backend.over')}}" autocomplete="off" value="">
                    <select name="end_hour" id="end_hour" class="form-control d-inline-block w-25 date-custom">
                        @for($i = 0; $i < 24; $i++)
                            @php $ii = $i < 10 ? '0'. $i : $i @endphp
                            <option value="{{$ii}}">{{$ii}}</option>
                        @endfor
                    </select>
                    {{trans('backend.hour')}}
                    <select name="end_min" id="end_min" class="form-control d-inline-block w-25 date-custom">
                        @for($i = 0; $i < 60; $i++)
                            @php $ii = $i < 10 ? '0'. $i : $i @endphp
                            <option value="{{$ii}}">{{$ii}}</option>
                        @endfor
                    </select>
                    {{trans('backend.minutes')}}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                <button type="button" class="btn update-part"> <i class="fa fa-save"></i> {{ trans('labutton.extension') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit-part" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('labutton.edit') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <input type="hidden" name="part_id" value="">
                    <div class="form-group row">
                        <label class="col-sm-3 control-label">{{trans('backend.exams_name')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="name_part" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label">{{trans('backend.time')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div>
                                <input name="start_date" type="text" class="datepicker-modal form-control w-25 d-inline-block date-custom" placeholder="{{trans('backend.start')}}" autocomplete="off" value="">
                                <select name="start_hour" id="start_hour" class="form-control d-inline-block w-25 date-custom">
                                    @for($i = 0; $i < 24; $i++)
                                        @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                        <option value="{{ $ii }}"> {{ $ii }}</option>
                                    @endfor
                                </select>
                                {{trans('backend.hour')}}
                                <select name="start_min" id="start_min" class="form-control d-inline-block w-25 date-custom">
                                    @for($i = 0; $i < 60; $i++)
                                        @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                        <option value="{{ $ii }}"> {{ $ii }}</option>
                                    @endfor
                                </select>
                                {{trans('backend.minutes')}}
                            </div>
                            <div>
                                <input name="end_date" type="text" class="datepicker-modal form-control w-25 d-inline-block date-custom" placeholder="{{trans('backend.over')}}" autocomplete="off" value="">
                                <select name="end_hour" id="end_hour" class="form-control d-inline-block w-25 date-custom">
                                    @for($i = 0; $i < 24; $i++)
                                        @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                        <option value="{{ $ii }}"> {{ $ii }}</option>
                                    @endfor
                                </select>
                                {{trans('backend.hour')}}
                                <select name="end_min" id="end_min" class="form-control d-inline-block w-25 date-custom">
                                    @for($i = 0; $i < 60; $i++)
                                        @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                        <option value="{{ $ii }}"> {{ $ii }}</option>
                                    @endfor
                                </select>
                                {{trans('backend.minutes')}}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <span class="text-danger">Lưu ý: Thời gian kết thúc ca thi phải lớn hơn (thời gian làm bài của kỳ thi + thời gian hiện tại + 10p)</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                <button type="button" class="btn edit-part"> <i class="fa fa-save"></i> {{ trans('labutton.update') }}</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click','.qrcode',function () {
        var qrcode =$(this).find('.qrcode_hide').html();
        $('#qrcode-wrap #qrcode').html(qrcode);
        $("#modal-qrcode").modal();
    });
    $('#print_qrcode').on("click", function () {
        $('#qrcode').printThis();
    });
    function start_date_formatter(value, row, index) {
        return row.start_date;
    }
    function get_qrcode(value, row, index) {
        return '<a href="javascript:void(0)" class="qrcode"><i class="fas fa-qrcode"></i><div class="qrcode_hide" style="visibility:hidden; display: none">'+row.qrcode+'</div></a>';
    }
    function end_date_formatter(value, row, index) {
        return row.end_date;
    }

    function action_delete_part_formatter(value, row, index) {
        return '<a href="javascript:void(0)" class="remove-item" data-id="'+ row.id +'"><i class="fa fa-trash text-danger"></i></a>';
    }

    function time_extension_formatter(value, row, index) {
        return '<a href="javascript:void(0)" class="update-part" data-id="'+ row.id +'" data-end_d="'+ row.end_d +'" data-end_h="'+ row.end_h +'" data-end_i="'+ row.end_i +'"><i class="fa fa-clock text-info"></i></a>';
    }

    function edit_formatter(value, row, index) {
        return '<a href="javascript:void(0)" class="edit-part" data-result="'+ row.quiz_result +'" data-name="'+row.name+'" data-id="'+ row.id +'" data-start_d="'+ row.start_d +'" data-start_h="'+ row.start_h +'" data-start_i="'+ row.start_i +'" data-end_d="'+ row.end_d +'" data-end_h="'+ row.end_h +'" data-end_i="'+ row.end_i +'"><i class="fa fa-edit text-info"></i></a>';
    }

    var table_part = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.quiz.edit.getpart', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.quiz.edit.removepart', ['id' => $model->id]) }}',
        table: '#table-part',
    });

    $('.save-part').on('click', function() {
        let button = $(this);
        let icon = button.find('i').attr('class');

        button.find('i').attr('class', 'fa fa-spinner fa-spin');
        button.prop("disabled", true);

        var name_part = $('input[name=name_part]').val();
        var start_date = $('input[name=start_date]').val();
        var end_date = $('input[name=end_date]').val();
        var start_hour = $('#start_hour option:selected').val();
        var start_min = $('#start_min option:selected').val();
        var end_hour = $('#end_hour option:selected').val();
        var end_min = $('#end_min option:selected').val();

        if (!name_part) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);
            show_message('Tên không được để trống', 'error');
            return false;
        }
        if (!start_date) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);
            show_message('Thời gian bắt đầu không được trống', 'error');
            return false;
        }
        // if (!end_date) {
        //     setTimeout(function(){
        //         button.find('i').attr('class', icon);
        //         button.prop("disabled", false);
        //     }, 500);
        //     show_message('Thời gian kết thúc không được trống', 'error');
        //     return false;
        // }
        $.ajax({
            url: '{{ route('module.quiz.edit.savepart', ['id' => $model->id])}}',
            type: 'post',
            data: {
                name_part: name_part,
                start_date : start_date,
                end_date : end_date,
                start_hour : start_hour,
                start_min : start_min,
                end_hour : end_hour,
                end_min : end_min,
            },
        }).done(function(data) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);
            if(data.status == 'error'){
                show_message(data.message, 'error');
                return false;
            }else{
                show_message('Thêm thành công', 'success');
                $(table_part.table).bootstrapTable('refresh');

                $('input[name=name_part]').val('');
                $('input[name=start_date]').val('');
                $('input[name=end_date]').val('');
                $('#start_hour').val('01');
                $('#start_min').val('00');
                $('#end_hour').val('01');
                $('#end_min').val('00');
                return false;
            }

        }).fail(function(data) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $('#table-part').on('click', '.update-part', function () {
        var part_id = $(this).data('id');
        var end_d = $(this).data('end_d');
        var end_h = $(this).data('end_h');
        var end_i = $(this).data('end_i');

        $('#modal-update-part').modal();
        $('#modal-update-part input[name=part_id]').val(part_id);
        $('#modal-update-part input[name=end_date]').val(end_d);
        $('#modal-update-part #end_hour').val(end_h);
        $('#modal-update-part #end_min').val(end_i);
    });

    $('#modal-update-part').on('click', '.update-part', function () {
        let button = $(this);
        let icon = button.find('i').attr('class');

        button.find('i').attr('class', 'fa fa-spinner fa-spin');
        button.prop("disabled", true);

        var part_id = $('#modal-update-part input[name=part_id]').val();
        var end_date = $('#modal-update-part input[name=end_date]').val();
        var end_hour = $('#modal-update-part #end_hour option:selected').val();
        var end_min = $('#modal-update-part #end_min option:selected').val();

        $.ajax({
            url: '{{ route('module.quiz.edit.updatepart', ['id' => $model->id])}}',
            type: 'post',
            data: {
                part_id: part_id,
                end_date : end_date,
                end_hour : end_hour,
                end_min : end_min,
            },
        }).done(function(data) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);
            if(data.status == 'error'){
                show_message(data.message, 'error');
                return false;
            }else{
                show_message('Gia hạn thành công', 'success');
                $(table_part.table).bootstrapTable('refresh');

                $('#modal-update-part input[name=part_id]').val('');
                $('#modal-update-part input[name=end_date]').val('');
                $('#modal-update-part #end_hour').val('01');
                $('#modal-update-part #end_min').val('00');

                $('#modal-update-part').modal('hide');

                return false;
            }

        }).fail(function(data) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $('#table-part').on('click', '.edit-part', function () {
        var result = $(this).data('result');
        var part_id = $(this).data('id');
        var part_name = $(this).data('name');

        var start_d = $(this).data('start_d');
        var start_h = $(this).data('start_h');
        var start_i = $(this).data('start_i');

        var end_d = $(this).data('end_d');
        var end_h = $(this).data('end_h');
        var end_i = $(this).data('end_i');

        $('#modal-edit-part').modal();
        $('#modal-edit-part input[name=part_id]').val(part_id);
        $('#modal-edit-part input[name=name_part]').val(part_name);

        $('#modal-edit-part input[name=start_date]').val(start_d);
        $('#modal-edit-part #start_hour').val(start_h);
        $('#modal-edit-part #start_min').val(start_i);

        $('#modal-edit-part input[name=end_date]').val(end_d);
        $('#modal-edit-part #end_hour').val(end_h);
        $('#modal-edit-part #end_min').val(end_i);

        if(result == 1) {
            $('#modal-edit-part input[name=start_date]').prop('disabled', true);
            $('#modal-edit-part #start_hour').prop('disabled', true);
            $('#modal-edit-part #start_min').prop('disabled', true);
        } else {
            $('#modal-edit-part input[name=start_date]').prop('disabled', false);
            $('#modal-edit-part #start_hour').prop('disabled', false);
            $('#modal-edit-part #start_min').prop('disabled', false);
        }
    });

    $('#modal-edit-part').on('click', '.edit-part', function () {
        let button = $(this);
        let icon = button.find('i').attr('class');

        button.find('i').attr('class', 'fa fa-spinner fa-spin');
        button.prop("disabled", true);

        var part_id = $('#modal-edit-part input[name=part_id]').val();
        var part_name = $('#modal-edit-part input[name=name_part]').val();

        var start_date = $('#modal-edit-part input[name=start_date]').val();
        var start_hour = $('#modal-edit-part #start_hour option:selected').val();
        var start_min = $('#modal-edit-part #start_min option:selected').val();

        var end_date = $('#modal-edit-part input[name=end_date]').val();
        var end_hour = $('#modal-edit-part #end_hour option:selected').val();
        var end_min = $('#modal-edit-part #end_min option:selected').val();

        $.ajax({
            url: '{{ route('module.quiz.edit.edit_part', ['id' => $model->id])}}',
            type: 'post',
            data: {
                part_id: part_id,
                part_name: part_name,
                start_date: start_date,
                start_hour: start_hour,
                start_min: start_min,
                end_date : end_date,
                end_hour : end_hour,
                end_min : end_min,
            },
        }).done(function(data) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);
            if(data.status == 'error'){
                show_message(data.message, 'error');
                return false;
            }else{
                show_message('Chỉnh sửa thành công', 'success');
                $(table_part.table).bootstrapTable('refresh');

                $('#modal-edit-part input[name=part_id]').val('');
                $('#modal-edit-part input[name=name_part]').val('');

                $('#modal-edit-part input[name=start_date]').val('');
                $('#modal-edit-part #start_hour').val('01');
                $('#modal-edit-part #start_min').val('00');

                $('#modal-edit-part input[name=end_date]').val('');
                $('#modal-edit-part #end_hour').val('01');
                $('#modal-edit-part #end_min').val('00');

                $('#modal-edit-part').modal('hide');

                return false;
            }

        }).fail(function(data) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $('.modal .datepicker-modal').datetimepicker({
        locale: 'vi',
        format: 'DD/MM/YYYY'
    });
</script>
