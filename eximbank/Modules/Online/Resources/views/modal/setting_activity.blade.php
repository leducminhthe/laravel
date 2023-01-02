<div class="form-group row">
    <div class="col-md-3 control-label">
        <label for="setting_complete_course_activity_id">
            {{ trans('latraining.activity_completed_first') }}</label>
    </div>
    <div class="col-md-9">
        @php
            $setting_complete_course_activity_id = explode(',', $model->setting_complete_course_activity_id);
        @endphp
        <select class="form-control select2" name="setting_complete_course_activity_id[]" id="setting_complete_course_activity_id" data-placeholder='{{ trans('latraining.choose_activity') }}' multiple >
            <option value=""></option>
            @foreach ($model_other as $other)
                <option value="{{ $other->id }}" {{ in_array($other->id, $setting_complete_course_activity_id) ? 'selected' : ''}}> {{ $other->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-3 control-label">
        <label for="time_type">{{ trans('latraining.limit_time_act') }}</label>
    </div>
    <div class="col-md-9">
        <span><input name="setting_start_date" type="text" class="datetimepicker form-control d-inline-block w-25"
                     placeholder="{{trans('latraining.start_date')}}" autocomplete="off" value="{{ get_date($model->setting_start_date, 'd/m/Y H:i:s') }}"></span>
        <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
        <span><input name="setting_end_date" type="text" class="datetimepicker form-control d-inline-block w-25"
                     placeholder='{{trans("latraining.end_date")}}' autocomplete="off" value="{{ get_date($model->setting_end_date, 'd/m/Y H:i:s') }}"></span>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-3 control-label">
        <label for="setting_score_course_activity_id">{{ trans('latraining.score_activity') }}</label>
    </div>
    <div class="col-md-9">
        <select class="form-control select2" name="setting_score_course_activity_id" id="setting_score_course_activity_id" data-placeholder="{{ trans('latraining.choose_activity') }}">
            <option value=""></option>
            @foreach ($model_other as $other)
                <option value="{{ $other->id }}" {{ $model->setting_score_course_activity_id == $other->id ? 'selected' : ''}}> {{ $other->name }}</option>
            @endforeach
        </select>
        <p></p>
        <span>
            {{ trans('latraining.greater_than_equal_to') }}:
            <input type="text" class="form-control d-inline-block w-25" name="setting_min_score" id="setting_min_score" value="{{ $model->setting_min_score }}">
        </span>
        <span>
            {{ trans('latraining.smaller') }}:
            <input type="text" class="form-control d-inline-block w-25" name="setting_max_score" id="setting_max_score" value="{{ $model->setting_max_score }}">
        </span>
    </div>
</div>

<script type="text/javascript">
    $('.datetimepicker').datetimepicker({
        locale:'vi',
        format: 'DD/MM/YYYY HH:mm:ss'
    });

    $('.select2').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
    })
</script>
