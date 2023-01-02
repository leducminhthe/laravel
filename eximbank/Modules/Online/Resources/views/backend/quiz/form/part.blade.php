<div role="main">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <label class="col-sm-3 control-label">{{trans('latraining.name_part')}} <span class="text-danger">*</span></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="name_part" value="">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 control-label">{{trans('latraining.time')}} <span class="text-danger">*</span></label>
                <div class="col-sm-6">
                    <div>
                        <input name="start_date" type="text" class="datepicker form-control w-25 d-inline-block date-custom" placeholder="{{trans('latraining.start')}}" autocomplete="off" value="">
                        <select name="start_hour" id="start_hour" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 0; $i <= 23; $i++)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{$ii}}">{{$ii}}</option>
                            @endfor
                        </select>
                        {{trans('latraining.hour')}}
                        <select name="start_min" id="start_min" class="form-control d-inline-block  w-25 date-custom">
                            @for($i = 0; $i <= 59; $i += 5)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{$ii}}">{{$ii}}</option>
                            @endfor
                        </select>
                        {{trans('latraining.minutes')}}
                    </div>
                    <div>
                        <input name="end_date" type="text" class="datepicker form-control w-25 d-inline-block date-custom" placeholder="{{trans('latraining.over')}}" autocomplete="off" value="">
                        <select name="end_hour" id="end_hour" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 0; $i <= 23; $i++)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{$ii}}">{{$ii}}</option>
                            @endfor
                        </select>
                        {{trans('latraining.hour')}}
                        <select name="end_min" id="end_min" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 0; $i <= 59; $i += 5)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{$ii}}">{{$ii}}</option>
                            @endfor
                        </select>
                        {{trans('latraining.minutes')}}
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <button type="button" class="btn save-part"><i class="fa fa-save"></i> {{ trans('labutton.save')}}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="tDefault table table-hover text-nowrap" id="table-part">
                <thead>
                    <tr>
                        <th data-field="name" data-align="center">{{trans('latraining.name_part')}}</th>
                        <th data-field="start_date" data-align="center" data-formatter="start_date_formatter">{{trans('latraining.start')}}</th>
                        <th data-field="end_date" data-align="center" data-formatter="end_date_formatter">{{trans('latraining.over')}}</th>
                        <th data-align="center" data-formatter="action_part_formatter">{{trans('latraining.action')}}</th>
                    </tr>
                </thead>
            </table>

        </div>
    </div>
</div>

<script type="text/javascript">
    function start_date_formatter(value, row, index) {
        return row.start_date;
    }
    function end_date_formatter(value, row, index) {
        return row.end_date;
    }

    function action_part_formatter(value, row, index) {
        return '<a href="javascript:void(0)" class="edit-item" data-name_part="'+ row.name +'" data-start_date="'+ row.startdate +'" data-end_date="'+ row.enddate +'" data-start_hour="'+ row.start_hour +'" data-start_min="'+ row.start_min +'" data-end_hour="'+ row.end_hour +'" data-end_min="'+ row.end_min +'"><i class="fa fa-edit text-info"></i></a>';
    }

    var table_part = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.online.quiz.edit.getpart', ['course_id' => $course_id, 'id' => $model->id]) }}',
        table: '#table-part',
    });

    $('#table-part').on('click', '.edit-item',function () {
        var name_part = $(this).data('name_part');
        var start_date = $(this).data('start_date');
        var end_date = $(this).data('end_date');
        var start_hour = $(this).data('start_hour');
        var start_min = $(this).data('start_min');
        var end_hour = $(this).data('end_hour');
        var end_min = $(this).data('end_min');

        $('input[name=name_part]').val(name_part);
        $('input[name=start_date]').val(start_date);
        $('input[name=end_date]').val(end_date);
        $('#start_hour').val(start_hour).trigger('change');
        $('#start_min').val(start_min).trigger('change');
        $('#end_hour').val(end_hour).trigger('change');
        $('#end_min').val(end_min).trigger('change');
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
        $.ajax({
            url: '{{ route('module.online.quiz.edit.savepart', ['course_id' => $course_id, 'id' => $model->id]) }}',
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
</script>
