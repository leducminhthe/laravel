<div id="myModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form action="{{ route('module.online.add-userpoint-setting-complete', ['course_id' => $course->id, 'type' => $type]) }}" method="post" class="form-ajax">

            <input type="hidden" name="id" value="{{ $model->id }}">
            <input type="hidden" name="item_id" value="{{ $course->id }}">
            <input type="hidden" name="item_type" value="2">
            <input type="hidden" name="pkey" value="online_complete">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ trans('latraining.score_complete_course') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ trans('latraining.completed') }}</label><br>
                        <div class="row">
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
                    </div>

                    <div class="form-group">
                        <label>{{ trans('latraining.score') }} <span class="text-danger">*</span></label><br>
                        <input type="text" class="form-control format_float_number is-number" name="pvalue" autocomplete="off"  value="{{ $model->pvalue ? number_format($model->pvalue, 2) : '' }}" placeholder="{{ trans('latraining.score') }}">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labutton.close') }}</button>
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


    $('input[name=start_date]').on('change', function () {
        var from_date = $(this).val();
        var temp = from_date.split('-');
        var form_hour = $('select[name="formhour"]').val();
        var form_min = $('select[name="formmin"]').val();

        if(temp.length ==3){
            var start_date = temp[2]+'-'+temp[1]+'-'+temp[0]+' '+form_hour+':'+form_min+':00';

            if(start_date < start_date_course || (end_date_course!='' && start_date > end_date_course)){
                $('input[name=start_date]').val('');
                $('.start_date_error').html('{{ trans('latraining.start_date_error_user_point') }}');
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
                $('.end_date_error').html('{{ trans('latraining.end_date_error_user_point') }}');
            } else {
                $('.end_date_error').html('');
            }
        }
    });
</script>


