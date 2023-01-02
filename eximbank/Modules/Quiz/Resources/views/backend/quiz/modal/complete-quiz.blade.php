<div id="myModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form action="{{ route('module.quiz.add-userpoint-setting-quiz', [$quiz->id]) }}" method="post" class="form-ajax">
        <div class="modal-content">

            <input type="hidden" name="id" value="{{ $model->id }}">
            <input type="hidden" name="item_id" value="{{ $quiz->id }}">
            <input type="hidden" name="item_type" value="4">
            <input type="hidden" name="pkey" value="quiz_complete">
            <input type="hidden" name="note" value="{{ $model->note ? $model->note : 'timecompleted' }}">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('latraining.score_completion_exam') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>{{ trans('latraining.completed') }}</label><br>

                    <div id="form-conditions" class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ trans('latraining.score_conditions') }} <span class="text-danger">*</span></label>
                                <select name="type" class="form-control">
                                    <option value="timecompleted" {{ $model->note == 'timecompleted' ? 'selected' : '' }} > {{ trans('latraining.scoring_over_time') }}</option>
                                    <option value="attempt" {{ $model->note == 'attempt' ? 'selected' : '' }} > {{ trans('latraining.score_scale_number_times') }}</option>
                                    <option value="score" {{ $model->note == 'score' ? 'selected' : '' }} > {{ trans('latraining.score_scale_number_point_achieved') }}</option>
                                    <option value="timefinish" {{ $model->note == 'timefinish' ? 'selected' : '' }} > 
                                        Thang điểm theo hoàn thành sớm nhất
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="form-score" class="row box-hidden">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('latraining.from_score') }}</label>
                                <input type="text" class="form-control is-number" id="min_score" name="min_score" autocomplete="off" value="{{ $model->min_score }}"  placeholder="{{ trans('latraining.from_score') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('latraining.to_score') }}</label>
                                <input type="text" class="form-control is-number" id="max_score" name="max_score" autocomplete="off" value="{{ $model->max_score }}"  placeholder="{{ trans('latraining.to_score') }}">
                            </div>
                        </div>
                    </div>

                    <div id="form-attempt" class="row box-hidden">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('latraining.from_times') }}</label>
                                <input type="text" class="form-control is-number" name="min_score" autocomplete="off" value="{{ ($model->min_score) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('latraining.to_times') }}</label>
                                <input type="text" class="form-control is-number" name="max_score" autocomplete="off" value="{{ ($model->max_score) }}">
                            </div>
                        </div>
                    </div>

                    <div id="form-timecompleted" class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('latraining.from_date') }}</label>
                                        <input type="text" class="form-control datepicker" name="start_date" autocomplete="off" value="{{ $model->start_date ? date('d-m-Y', $model->start_date) : null }}" >
                                        <span class="start_date_error text-danger"></span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ trans('latraining.hour') }}</label>
                                        <select class="form-control" name="formhour">
                                            @for($i=0;$i<24;$i++)
                                                <option value="{{ $i < 10 ? '0' . $i : $i }}" {{ $model->start_date ? (date('H', $model->start_date) == $i ? 'selected' : '') : null }} > {{ $i < 10 ? '0' . $i : $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ trans('latraining.minute') }}</label>
                                        <select class="form-control" name="formmin">
                                            @for($i=0;$i<60;$i++)
                                                <option value="{{ $i < 10 ? '0' . $i : $i }}" {{ $model->start_date ? (date('i', $model->start_date) == $i ? 'selected' : '') : null }} > {{ $i < 10 ? '0' . $i : $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('latraining.to_date') }}</label>
                                        <input type="text" class="form-control datepicker" name="end_date" autocomplete="off" value="{{ $model->end_date ? date('d-m-Y', $model->end_date) : null }}">
                                        <span class="end_date_error text-danger"></span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ trans('latraining.hour') }}</label>
                                        <select class="form-control" name="tohour">
                                            @for($i=0;$i<24;$i++)
                                                <option value="{{ $i < 10 ? '0' . $i : $i }}" {{ $model->end_date ? (date('H', $model->end_date) == $i ? 'selected' : '') : null }} >{{ $i < 10 ? '0' . $i : $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ trans('latraining.minute') }}</label>
                                        <select class="form-control" name="tomin">
                                            @for($i=0;$i<60;$i++)
                                                <option value="{{ $i < 10 ? '0' . $i : $i }}" {{ $model->end_date ? (date('i', $model->end_date) == $i ? 'selected' : '') : null }} >{{ $i < 10 ? '0' . $i : $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="form-timefinish">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-7">
                                    <span>Cách tính điểm: </span>
                                    @if ($quiz->grade_methor == 1)
                                        <span>{{ trans('backend.highest_time') }}</span>
                                    @elseif ($quiz->grade_methor == 2)
                                        <span>{{ trans('backend.medium_score') }}</span>
                                    @elseif ($quiz->grade_methor == 3)
                                        <span>{{ trans('backend.first_time_score') }}</span>
                                    @else
                                        <span>{{ trans('backend.last_point') }}</span>
                                    @endif
                                    <input type="hidden" name="type_timefinish" value="{{ $quiz->grade_methor }}">
                                </div>
                                <div class="col-5 text-right">
                                    <button type="button" class="btn" onclick="addTopHandle()">
                                        <i class="fa fa-plus" aria-hidden="true"></i> <span>Thêm top điểm đạt</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 wrapped_top">
                            @php
                                $numItems = count($timefinish);
                                $i = 0;
                            @endphp     
                            @if (!empty($timefinish))
                                @foreach ($timefinish as $key => $item)
                                    <input type="hidden" name="id_time[]" value="{{ $item->id }}">
                                    <div class="row wrapped_item_top mt-2 item_time_{{ $item->id }}">
                                        <div class="col-7">
                                            <div class="item_top d_flex_align">
                                                <label class="w-25" for="">Hạng {{ $item->rank }}</label>
                                                <input type="number" name="score_time_finish[]" class="form-control ml-2" placeholder="Điểm đạt được" value="{{ $item->score }}">
                                            </div>
                                        </div>
                                        <div class="col-5 text-right">
                                            @if (++$i === $numItems && $item->rank != 1)
                                                <i class="fas fa-trash-alt cursor_pointer item_top_remove_{{ $item->rank }}" onclick="removeItemHandle({{ $item->id }})"></i>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row wrapped_item_top mt-2">
                                    <div class="col-7">
                                        <div class="item_top d_flex_align">
                                            <label class="w-25" for="">Hạng 1</label>
                                            <input type="number" name="score_time_finish[]" class="form-control ml-2" placeholder="Điểm đạt được">
                                        </div>
                                    </div>
                                    <div class="col-5 text-right">
                                    </div>
                                </div>
                            @endif
                            
                        </div>
                    </div>
                </div>

                <div class="form-group wrapped_score">
                    <label>{{ trans('latraining.score') }} <span class="text-danger">*</span></label><br>
                    <input type="text" class="form-control format_float_number" name="pvalue" autocomplete="off"  value="{{ $model->pvalue ? number_format($model->pvalue, 2) : '' }}" placeholder="{{ trans('latraining.score') }}">
                </div>
            </div>

            <div class="modal-footer">
                @if (!isset($result))
                    <button type="submit" class="btn"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                @endif
                <button type="button" class="btn btn-default" data-dismiss="modal"> {{ trans('labutton.close') }}</button>
            </div>
        </div>
        </form>
    </div>
</div>
  <script type="text/javascript">

    var end_date_course = '{{ $end_date_course }}';
    var start_date_course = '{{ $start_date_course }}';

    $( ".datepicker" ).datepicker({
        changeMonth: true,
        numberOfMonths: 1,
        format: "dd-mm-yyyy",
        //startDate : start_date_course,
       // endDate : end_date_course
    });

    var note = $('input[name=note]').val();
    if(note == 'complete') {
        $('#form-conditions').hide('slow');
        $('#form-score').hide('slow').find('input').prop('disabled', true);
        $('#form-attempt').hide('slow').find('input').prop('disabled', true);
        $('#form-timecompleted').hide('slow').find('input').prop('disabled', true);

        $('select[name="type"]').val('');
    }

    var seleted = $('select[name=type]').val();
    $('#form-timefinish').hide().find('input').prop('disabled', true);
    if (seleted == 'timecompleted') {
        $('#form-score').hide('slow').find('input').prop('disabled', true);
        $('#form-attempt').hide('slow').find('input').prop('disabled', true);
        $('#form-timecompleted').show('slow').find('input').prop('disabled', false);
        $('#form-timefinish').hide('slow').find('input').prop('disabled', true);
    }

    if (seleted == 'score') {
        $('#form-score').show('slow').find('input').prop('disabled', false);
        $('#form-attempt').hide('slow').find('input').prop('disabled', true);
        $('#form-timecompleted').hide('slow').find('input').prop('disabled', true);
        $('#form-timefinish').hide('slow').find('input').prop('disabled', true);
    }

    if (seleted == 'attempt') {
        $('#form-score').hide('slow').find('input').prop('disabled', true);
        $('#form-attempt').show('slow').find('input').prop('disabled', false);
        $('#form-timecompleted').hide('slow').find('input').prop('disabled', true);
        $('#form-timefinish').hide('slow').find('input').prop('disabled', true);
    }

    if (seleted == 'timefinish') {
        $('#form-score').hide('slow').find('input').prop('disabled', true);
        $('#form-attempt').hide('slow').find('input').prop('disabled', true);
        $('#form-timecompleted').hide('slow').find('input').prop('disabled', true);
        $('#form-timefinish').show('slow').find('input').prop('disabled', false);
        $('.wrapped_score').hide('slow').find('input').prop('disabled', true);
    } else {
        $('.wrapped_score').show('slow').find('input').prop('disabled', false);
    }

    $('select[name=type]').on('change', function () {
        let seleted = $(this).val();
        if (seleted == 'timecompleted') {
            $('#form-score').hide('slow').find('input').prop('disabled', true);
            $('#form-attempt').hide('slow').find('input').prop('disabled', true);
            $('#form-timecompleted').show('slow').find('input').prop('disabled', false);
            $('#form-timefinish').hide('slow').find('input').prop('disabled', true);
            $('input[name=note]').val('timecompleted');
        }

        if (seleted == 'score') {
            $('#form-score').show('slow').find('input').prop('disabled', false);
            $('#form-attempt').hide('slow').find('input').prop('disabled', true);
            $('#form-timecompleted').hide('slow').find('input').prop('disabled', true);
            $('#form-timefinish').hide('slow').find('input').prop('disabled', true);
            $('input[name=note]').val('score');
        }

        if (seleted == 'attempt') {
            $('#form-score').hide('slow').find('input').prop('disabled', true);
            $('#form-attempt').show('slow').find('input').prop('disabled', false);
            $('#form-timecompleted').hide('slow').find('input').prop('disabled', true);
            $('#form-timefinish').hide('slow').find('input').prop('disabled', true);
            $('input[name=note]').val('attempt');
        }

        if (seleted == 'timefinish') {
            $('#form-score').hide('slow').find('input').prop('disabled', true);
            $('#form-attempt').hide('slow').find('input').prop('disabled', true);
            $('#form-timecompleted').hide('slow').find('input').prop('disabled', true);
            $('#form-timefinish').show('slow').find('input').prop('disabled', false);
            $('.wrapped_score').hide('slow').find('input').prop('disabled', true);
            $('input[name=note]').val('timefinish');
        } else {
            $('.wrapped_score').show('slow').find('input').prop('disabled', false);
        }
    });


    $('input[name=start_date]').on('change', function () {
        var from_date = $(this).val();
        var temp = from_date.split('-');
        var form_hour = $('select[name="formhour"]').val();
        var form_min = $('select[name="formmin"]').val();

        if(temp.length ==3){
            var start_date = temp[2]+'-'+temp[1]+'-'+temp[0]+' '+form_hour+':'+form_min+':00';

            if(start_date < start_date_course || (end_date_course!='' && start_date > end_date_course)){
                $('input[name=start_date]').val('');
                $('.start_date_error').html('Từ ngày phải nằm trong khoảng thời gian kỳ thi');
            }else{
                $('.start_date_error').html('');
            }
        }

    });

    $('input[name=end_date]').on('change', function () {
        var to_date = $(this).val();
        var temp = to_date.split('-');
        var to_hour = $('select[name="tohour"]').val();
        var to_min = $('select[name="tomin"]').val();

        if(temp.length ==3) {
            var end_date = temp[2] + '-' + temp[1] + '-' + temp[0]+' '+to_hour+':'+to_min+':00';
            if (end_date < start_date_course || (end_date_course != '' && end_date > end_date_course)) {
                $('input[name=end_date]').val('');
                $('.end_date_error').html('Đến ngày phải nằm trong khoảng thời gian kỳ thi');
            } else {
                $('.end_date_error').html('');
            }
        }
    });

    function addTopHandle() {
        var count = $('.wrapped_item_top').length + 1;
        var html = `<div class="row wrapped_item_top mt-2 item_top_`+ count +`">
                        <div class="col-7">
                            <div class="item_top d_flex_align">
                                <label class="w-25" for="">Hạng `+ count +`</label>
                                <input type="number" name="score_time_finish[]" class="form-control ml-2" placeholder="Điểm đạt được">
                            </div>
                        </div>
                        <div class="col-5 text-right">
                            <i class="fas fa-trash-alt cursor_pointer item_top_remove_`+ count +`" onclick="removeTopHandle(`+ count +`)"></i>
                        </div>
                    </div>`;
        $('.wrapped_top').append(html);
        if(count > 2) {
            $('.item_top_remove_'+ (count - 1)).hide();
        }
    }

    function removeTopHandle(id) {
        if(id > 2) {
            $('.item_top_remove_'+ (id - 1)).show();
        }
        $('.item_top_'+ id).remove();
    }

    function removeItemHandle(id) {
        $.ajax({
            url: '{{ route("module.quiz.remove_item_time_userpoint", ["quiz_id" => $quiz->id]) }}',
            type: 'post',
            data: {
                id: id,
            }
        }).done(function(data) {
            $('.item_time_'+ id).remove();
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    }
</script>


