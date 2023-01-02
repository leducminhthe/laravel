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
                        <input name="start_date" type="text" class="datepicker form-control w-25 d-inline-block date-custom" placeholder="{{trans('backend.start')}}" autocomplete="off" value="">
                        <select name="start_hour" id="start_hour" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 1 ; $i <= 24 ; $i++)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{$ii}}">{{$ii}}</option>
                            @endfor
                        </select>
                        {{trans('backend.hour')}}
                        <select name="start_min" id="start_min" class="form-control d-inline-block  w-25 date-custom">
                            @for($i = 0; $i <= 60; $i += 5)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{$ii}}">{{$ii}}</option>
                            @endfor
                        </select>
                        {{trans('backend.minutes')}}
                    </div>
                    <div>
                        <input name="end_date" type="text" class="datepicker form-control w-25 d-inline-block date-custom" placeholder="{{trans('backend.over')}}" autocomplete="off" value="">
                        <select name="end_hour" id="end_hour" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 01 ; $i <= 24 ; $i++)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{$ii}}">{{$ii}}</option>
                            @endfor
                        </select>
                        {{trans('backend.hour')}}
                        <select name="end_min" id="end_min" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 0; $i <= 60; $i += 5)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{$ii}}">{{$ii}}</option>
                            @endfor
                        </select>
                        {{trans('backend.minutes')}}
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <button type="button" class="btn save-part"><i class="fa fa-plus-circle"></i> {{trans('labutton.add_new')}}</button>
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
                        <th data-field="action" data-formatter="action_formatter" data-align="center" data-width="5%">{{trans('labutton.delete')}}</th>
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
                <h5 class="modal-title" id="exampleModalLabel">Mã bài thi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div id="qrcode-wrap">
                        <div id="qrcode"></div>
                        <p>Quét mã để vào thi.</p>
                    </div>
                    <a href="javascript:void(0)" id="print_qrcode">In QR Code</a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
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

    function action_formatter(value, row, index) {
        return '<a href="javascript:void(0)" class="remove-item" data-id="'+ row.id +'"><i class="fa fa-trash text-danger"></i></a>';
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.quiz_educate_plan.edit.getpart', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.quiz_educate_plan.edit.removepart', ['id' => $model->id]) }}',
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
            url: '{{ route('module.quiz_educate_plan.edit.savepart', ['id' => $model->id])}}',
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
                $(table.table).bootstrapTable('refresh');
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
