<div role="main" class="form_setting_time">
    <form method="post" action="{{ route('backend.experience_navigate.save') }}" class="form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    <button type="submit" class="btn btn_save_time" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    <button type="button" class="btn" onclick="addSession()"><i class="fa fa-plus-circle"></i> &nbsp;{{ trans('laother.more_session') }}</button>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <br>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('lasetting.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="all_session">
                        <div class="form-group row">
                            <div class="col-md-3 pr-0 control-label">
                                <label for="object">{{ trans('laother.set_date') }}</label>
                            </div>
                            <div class="col-md-8 wrraper_item d_flex_align">
                                <input name="start_date" type="text" class="datepicker form-control start_date" placeholder="{{trans('latraining.start_date')}}" value="{{ $model->start_date }}" required>
                                <span class="fa fa-arrow-right px-2"></span>
                                <input name="end_date" type="text" class="datepicker form-control end_date" placeholder="{{trans('latraining.end_date')}}" value="{{ $model->end_date }}" required>
                            </div>
                        </div>
                        @if ($model->id)
                            @foreach ($get_time_experience_navigate as $key => $item)
                                <div class="form-group row" id="session_{{ $key + 1 }}">
                                    <input type="hidden" name="time_id[]" value="{{ $item->id }}">
                                    <div class="col-md-3 pr-0 control-label session">
                                        <label for="content">{{ trans('laother.display_time') }} {{ $key + 1 }}:</label>
                                    </div>
                                    <div class="col-md-8 wrraper_item d_flex_align">
                                        <input type="hidden" name="experience_navigate_id[]" value="{{ $item->id }}">
                                        <input name="time_1[]" type="text" class="form-control timepicker d-inline-block time_1" onblur="checkTime({{ $key + 1 }})" placeholder="{{ trans('laother.choose_start_time') }}" required value="{{ $item->time_start }}">
                                        <span class="fa fa-arrow-right px-2"></span>
                                        <input name="time_2[]" type="text" class="form-control timepicker d-inline-block time_2 mr-2" onblur="checkTime({{ $key + 1 }})" placeholder="{{ trans('laother.choose_end_time') }}" required value="{{ $item->time_end }}">
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="form-group row" id="session_1">
                                <div class="col-md-3 pr-0 control-label session">
                                    <label for="content">{{ trans('laother.display_time') }} 1:</label>
                                </div>
                                <div class="col-md-8 wrraper_item d_flex_align">
                                    <input type="hidden" name="experience_navigate_id[]" value="">
                                    <input name="time_1[]" type="text" class="form-control timepicker d-inline-block time_1" onblur="checkTime(1)" placeholder="{{ trans('laother.choose_start_time') }}" required value="05:00:00">
                                    <span class="fa fa-arrow-right px-2"></span>
                                    <input name="time_2[]" type="text" class="form-control timepicker d-inline-block time_2 mr-2" onblur="checkTime(1)" placeholder="{{ trans('laother.choose_end_time') }}" required value="11:00:00">
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 pr-0 control-label">
                            <label for="object">{{ trans('latraining.maximum_impressions') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="number" name="total_count" class="form-control mr-2 total_count_navigate" placeholder="{{ trans('latraining.maximum_impressions') }}" value="{{ $model->total_count }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 pr-0 control-label">
                            <label for="object">{{ trans('laother.maximum_impressions_day') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="number" name="date_count" class="form-control mr-2 date_count_navigate" placeholder="{{ trans('laother.maximum_impressions_day') }}" value="{{ $model->date_count }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $('.timepicker').datetimepicker({
        locale:'vi',
        format: 'HH:mm'
    });
    function addSession() { 
        var numItems = $('.session').length;
        var html = `<div class="form-group row" id="session_`+ (numItems + 1) +`">
                        <div class="col-md-3 pr-0 control-label session">
                            <label for="content">{{ trans("laother.display_time") }} `+ (numItems + 1) +`:</label>
                        </div>
                        <div class="col-md-8 wrraper_item">
                            <input name="time_1[]" type="text" class="form-control timepicker d-inline-block time_1" onblur="checkTime(`+ (numItems + 1) +`)" placeholder="{{ trans('laother.choose_start_time') }}" required value="'22:00:00'">
                            <span class="fa fa-arrow-right px-1"></span>
                            <input name="time_2[]" type="text" class="form-control timepicker d-inline-block time_2 mr-2" onblur="checkTime(`+ (numItems + 1) +`)" placeholder="{{ trans('laother.choose_end_time') }}" required value="'23:00:00'">
                        </div>
                        <div class="col-md-1 delete_session"><i class="fa fa-trash" onclick="removeSession(`+(numItems + 1)+`)"></i></div>
                    </div>`;
        $('.all_session').append(html);
        $('.timepicker').datetimepicker({
            locale:'vi',
            format: 'HH:mm'
        });
    }

    function removeSession(item) {
        $('#session_'+item).remove();
    }

    function checkTime(id) {
        var error = 0;
        var value_time_1 = $('#session_'+id).find('.time_1').val();
        var value_time_2 = $('#session_'+id).find('.time_2').val();
        if (!value_time_1 || !value_time_2) {
            show_message('{{ trans("laother.please_choose_time") }}', 'error');
            error = 1;
        } else {
            if (value_time_1 >= value_time_2) {
                show_message('{{ trans("laother.invalid_time") }}', 'error');
                error = 1;
            } else {
                var numItems = $('.session').length;
                for (let index = 0; index < numItems; index++) {
                    var item = index + 1;
                    var value_index_time_1 = $('#session_'+item).find('.time_1').val();
                    var value_index_time_2 = $('#session_'+item).find('.time_2').val();
                    if (item != id && id < item) {
                        if (value_time_1 >= value_index_time_1 || value_time_2 >= value_index_time_1) {
                            show_message('{{ trans("laother.invalid_time") }} ', 'error');
                            error = 1;
                        }
                    } else if (item != id && id > item) {
                        if (value_time_1 <= value_index_time_2 || value_time_2 <= value_index_time_2) {
                            show_message('{{ trans("laother.invalid_time") }} ', 'error');
                            error = 1;
                        }
                    } 
                } 
            }
        }
        if (error == 1) {
            $('.btn_save_time').attr('disabled',true);
        } else {
            $('.btn_save_time').attr('disabled',false);
        }
    }
</script>