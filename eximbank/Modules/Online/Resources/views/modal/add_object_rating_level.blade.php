<div class="modal fade modal-add-object" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="post" action="{{ route('module.online.rating_level.save_object', ['course_id' => $course->id, 'id' => $online_rating_level->id]) }}" class="form-ajax" id="form-rating-level-object" data-success="submit_success_rating_level_object">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('latraining.add_object') }}: {{ $online_rating_level->rating_name }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-sm-2 control-label">
                            <label>{{ trans('latraining.subjects_evaluated') }}</label>
                        </div>
                        <div class="col-md-10">
                            <label class="radio-inline">
                                <input type="radio" name="object_rating" class="radio-object-rating" value="1" {{ $online_rating_level->object_rating == 1 ? 'checked' : '' }}> {{ trans('latraining.classroom') }}
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="object_rating" class="radio-object-rating" value="2" {{ $online_rating_level->object_rating == 2 ? 'checked' : '' }}> {{ trans('latraining.student') }}
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2 control-label">
                            <label>{{ trans('latraining.evaluation_object') }}</label>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group" id="object_type_1">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="radio-inline">
                                            <input type="checkbox" name="object_type[]" value="1" {{ isset($result_object[1]) ? 'checked' : '' }}> {{ trans('latraining.student') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <span> {{ trans('latraining.time_rating') }}</span>
                                    </div>
                                    <div class="col-10 p-0 form-inline">
                                        <span>
                                            <select name="time_type[1]" id="time_type_1" class="form-control select2" data-placeholder="{{ trans('latraining.choose_time') }}">
                                                <option value=""></option>
                                                <option value="1" {{ isset($result_object[1]['time_type']) && $result_object[1]['time_type'] == 1 ? 'selected' : '' }}> {{ trans('latraining.time_period') }}</option>
                                                <option value="2" {{ isset($result_object[1]['time_type']) && $result_object[1]['time_type'] == 2 ? 'selected' : '' }}> {{ trans('latraining.start_course') }}</option>
                                                <option value="3" {{ isset($result_object[1]['time_type']) && $result_object[1]['time_type'] == 3 ? 'selected' : '' }}> {{ trans('latraining.end_course') }}</option>
                                                <option value="4" {{ isset($result_object[1]['time_type']) && $result_object[1]['time_type'] == 4 ? 'selected' : '' }}> {{ trans('latraining.complete_course') }}</option>
                                            </select>
                                        </span>
                                        <span>
                                            <input name="num_date[1]" type="text" class="form-control is-number" placeholder="{{ trans('latraining.num_date') }}" value="{{ isset($result_object[1]['num_date']) ? $result_object[1]['num_date'] : '' }}">
                                        </span>
                                        <span>
                                            <input name="start_date[1]" type="text" class="datepicker form-control" placeholder="{{trans('latraining.start_date')}}" autocomplete="off" value="{{ isset($result_object[1]['start_date']) ? get_date($result_object[1]['start_date']) : '' }}">
                                        </span>
                                        <span>
                                            <input name="end_date[1]" type="text" class="datepicker form-control" placeholder='{{trans("latraining.end_date")}}' autocomplete="off" value="{{ isset($result_object[1]['end_date']) ? get_date($result_object[1]['end_date']) : '' }}">
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <span>{{ trans('latraining.object_view') }}</span>
                                    </div>
                                    <div class="col-10 p-0 form-inline">
                                        <span>
                                            <select name="object_view_rating[1]" class="form-control select2" data-placeholder="{{ trans('latraining.choose_object_view_rating') }}">
                                                <option value=""></option>
                                                <option value="0" {{ isset($result_object[1]['object_view_rating']) && $result_object[1]['object_view_rating'] == 0 ? 'selected' : 'selected' }}> {{ trans('latraining.no') }}</option>
                                                <option value="1" {{ isset($result_object[1]['object_view_rating']) && $result_object[1]['object_view_rating'] == 1 ? 'selected' : '' }}> {{ trans('latraining.student') }}</option>
                                                <option value="2" {{ isset($result_object[1]['object_view_rating']) && $result_object[1]['object_view_rating'] == 2 ? 'selected' : '' }}> {{ trans('latraining.unit_chief') }}</option>
                                            </select>
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label>{{ trans('latraining.student_complete') }}</label>
                                    </div>
                                    <div class="col-10 p-0">
                                        <label class="radio-inline">
                                            <input type="radio" name="user_completed[1]" value="1" {{ isset($result_object[1]['user_completed']) && $result_object[1]['user_completed'] == 1 ? 'checked' : '' }}> {{ trans('latraining.yes') }}
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="user_completed[1]" value="0" {{ isset($result_object[1]['user_completed']) ? ($result_object[1]['user_completed'] == 0 ? 'checked' : '') : 'checked' }}> {{ trans('latraining.no') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-2" id="object_type_2">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="radio-inline">
                                            <input type="checkbox" name="object_type[]" value="2" {{ isset($result_object[2]) ? 'checked' : '' }}> {{ trans('latraining.unit_chief') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <span>{{ trans('latraining.time_rating') }} </span>
                                    </div>
                                    <div class="col-10 p-0 form-inline">
                                        <span>
                                            <select name="time_type[2]" id="time_type_2" class="form-control select2" data-placeholder="{{ trans('latraining.choose_time') }}">
                                                <option value=""></option>
                                                <option value="1" {{ isset($result_object[2]['time_type']) && $result_object[2]['time_type'] == 1 ? 'selected' : '' }}> {{ trans('latraining.time_period') }}</option>
                                                <option value="2" {{ isset($result_object[2]['time_type']) && $result_object[2]['time_type'] == 2 ? 'selected' : '' }}> {{ trans('latraining.start_course') }}</option>
                                                <option value="3" {{ isset($result_object[2]['time_type']) && $result_object[2]['time_type'] == 3 ? 'selected' : '' }}> {{ trans('latraining.end_course') }}</option>
                                                <option value="4" {{ isset($result_object[2]['time_type']) && $result_object[2]['time_type'] == 4 ? 'selected' : '' }}> {{ trans('latraining.complete_course') }}</option>
                                            </select>
                                        </span>
                                        <span>
                                            <input name="num_date[2]" type="text" class="form-control is-number" placeholder="{{ trans('latraining.num_date') }}" value="{{ isset($result_object[2]['num_date']) ? $result_object[2]['num_date'] : '' }}">
                                        </span>
                                        <span>
                                            <input name="start_date[2]" type="text" class="datepicker form-control" placeholder="{{trans('latraining.start_date')}}" autocomplete="off" value="{{ isset($result_object[2]['start_date']) ? get_date($result_object[2]['start_date']) : '' }}">
                                        </span>
                                        <span>
                                            <input name="end_date[2]" type="text" class="datepicker form-control" placeholder='{{trans("latraining.end_date")}}' autocomplete="off" value="{{ isset($result_object[2]['end_date']) ? get_date($result_object[2]['end_date']) : '' }}">
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <span>{{trans("latraining.object_view")}} </span>
                                    </div>
                                    <div class="col-10 p-0 form-inline">
                                        <span>
                                            <select name="object_view_rating[2]" class="form-control select2" data-placeholder="{{trans("latraining.choose_object_view_rating")}}">
                                                <option value=""></option>
                                                <option value="0" {{ isset($result_object[2]['object_view_rating']) && $result_object[2]['object_view_rating'] == 0 ? 'selected' : 'selected' }}> {{trans("latraining.no")}}</option>
                                                <option value="1" {{ isset($result_object[2]['object_view_rating']) && $result_object[2]['object_view_rating'] == 1 ? 'selected' : '' }}> {{trans("latraining.student")}}</option>
                                                <option value="2" {{ isset($result_object[2]['object_view_rating']) && $result_object[2]['object_view_rating'] == 2 ? 'selected' : '' }}> {{trans("latraining.unit_chief")}}</option>
                                            </select>
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label>{{trans("latraining.student_complete")}}</label>
                                    </div>
                                    <div class="col-10 p-0">
                                        <label class="radio-inline">
                                            <input type="radio" name="user_completed[2]" value="1" {{ isset($result_object[2]['user_completed']) && $result_object[2]['user_completed'] == 1 ? 'checked' : '' }}> {{trans("latraining.yes")}}
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="user_completed[2]" value="0" {{ isset($result_object[2]['user_completed']) && $result_object[2]['user_completed'] == 0 ? 'checked' : 'checked' }}> {{trans("latraining.no")}}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-2" id="object_type_3">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="radio-inline">
                                            <input type="checkbox" name="object_type[]" value="3" {{ isset($result_object[3]) ? 'checked' : '' }}> {{trans("latraining.colleague")}}
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <span>{{trans("latraining.quantity")}} </span>
                                    </div>
                                    <div class="col-10 p-0 form-inline">
                                        <span>
                                            <input type="text" name="num_user[3]" class="form-control is-number" placeholder="{{trans("latraining.quantity")}}" value="{{ isset($result_object[3]['num_user']) ? $result_object[3]['num_user'] : '' }}">
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <span>{{trans("latraining.time_rating")}} </span>
                                    </div>
                                    <div class="col-10 p-0 form-inline">
                                        <span>
                                            <select name="time_type[3]" id="time_type_3" class="form-control select2" data-placeholder="{{trans("latraining.choose_time")}}">
                                                <option value=""></option>
                                                <option value="1" {{ isset($result_object[3]['time_type']) && $result_object[3]['time_type'] == 1 ? 'selected' : '' }}> {{trans("latraining.time_period")}}</option>
                                                <option value="2" {{ isset($result_object[3]['time_type']) && $result_object[3]['time_type'] == 2 ? 'selected' : '' }}> {{trans("latraining.start_course")}}</option>
                                                <option value="3" {{ isset($result_object[3]['time_type']) && $result_object[3]['time_type'] == 3 ? 'selected' : '' }}> {{trans("latraining.end_course")}}</option>
                                                <option value="4" {{ isset($result_object[3]['time_type']) && $result_object[3]['time_type'] == 4 ? 'selected' : '' }}> {{trans("latraining.complete_course")}}</option>
                                            </select>
                                        </span>
                                        <span>
                                            <input name="num_date[3]" type="text" class="form-control is-number" placeholder="{{trans("latraining.num_date")}}" value="{{ isset($result_object[3]['num_date']) ? $result_object[3]['num_date'] : '' }}">
                                        </span>
                                        <span>
                                            <input name="start_date[3]" type="text" class="datepicker form-control" placeholder="{{trans('latraining.start_date')}}" autocomplete="off" value="{{ isset($result_object[3]['start_date']) ? get_date($result_object[3]['start_date']) : '' }}">
                                        </span>
                                        <span>
                                            <input name="end_date[3]" type="text" class="datepicker form-control" placeholder='{{trans("latraining.end_date")}}' autocomplete="off" value="{{ isset($result_object[3]['end_date']) ? get_date($result_object[3]['end_date']) : '' }}">
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <span>{{trans("latraining.object_view")}}</span>
                                    </div>
                                    <div class="col-10 p-0 form-inline">
                                        <span>
                                            <select name="object_view_rating[3]" class="form-control select2" data-placeholder="{{trans("latraining.choose_object_view_rating")}}">
                                                <option value=""></option>
                                                <option value="0" {{ isset($result_object[3]['object_view_rating']) && $result_object[3]['object_view_rating'] == 0 ? 'selected' : 'selected' }}> {{trans("latraining.no")}}</option>
                                                <option value="1" {{ isset($result_object[3]['object_view_rating']) && $result_object[3]['object_view_rating'] == 1 ? 'selected' : '' }}> {{trans("latraining.student")}}</option>
                                                <option value="2" {{ isset($result_object[3]['object_view_rating']) && $result_object[3]['object_view_rating'] == 2 ? 'selected' : '' }}> {{trans("latraining.unit_chief")}}</option>
                                            </select>
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label>{{trans("latraining.student_complete")}}</label>
                                    </div>
                                    <div class="col-10 p-0">
                                        <label class="radio-inline">
                                            <input type="radio" name="user_completed[3]" value="1" {{ isset($result_object[3]['user_completed']) && $result_object[3]['user_completed'] == 1 ? 'checked' : '' }}> {{trans("latraining.yes")}}
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="user_completed[3]" value="0" {{ isset($result_object[3]['user_completed']) && $result_object[3]['user_completed'] == 0 ? 'checked' : 'checked' }}> {{trans("latraining.no")}}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-2" id="object_type_4">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="radio-inline">
                                            <input type="checkbox" name="object_type[]" value="4" {{ isset($result_object[4]) ? 'checked' : '' }}> {{trans("latraining.other")}}
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <select name="user_id[4][]" id="user_id" class="form-control select2" data-placeholder="{{trans("latraining.user")}}" multiple>
                                            <option value=""></option>
                                            @foreach($profile as $item)
                                                <option value="{{ $item->user_id }}" {{ isset($result_object[4]['user_id']) && in_array($item->user_id, explode(',', $result_object[4]['user_id'])) ? 'selected' : '' }}> {{ $item->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <span>{{trans("latraining.time_rating")}} </span>
                                    </div>
                                    <div class="col-10 p-0 form-inline">
                                        <span>
                                            <select name="time_type[4]" id="time_type_4" class="form-control select2" data-placeholder="{{trans("latraining.choose_time")}}">
                                                <option value=""></option>
                                                <option value="1" {{ isset($result_object[4]['time_type']) && $result_object[4]['time_type'] == 1 ? 'selected' : '' }}> {{trans("latraining.time_period")}}</option>
                                                <option value="2" {{ isset($result_object[4]['time_type']) && $result_object[4]['time_type'] == 2 ? 'selected' : '' }}> {{trans("latraining.start_course")}}</option>
                                                <option value="3" {{ isset($result_object[4]['time_type']) && $result_object[4]['time_type'] == 3 ? 'selected' : '' }}> {{trans("latraining.end_course")}}</option>
                                                <option value="4" {{ isset($result_object[4]['time_type']) && $result_object[4]['time_type'] == 4 ? 'selected' : '' }}> {{trans("latraining.complete_course")}}</option>
                                            </select>
                                        </span>
                                        <span>
                                            <input name="num_date[4]" type="text" class="form-control is-number" placeholder="{{trans("latraining.num_date")}}" value="{{ isset($result_object[4]['num_date']) ? $result_object[4]['num_date'] : '' }}">
                                        </span>
                                        <span>
                                            <input name="start_date[4]" type="text" class="datepicker form-control" placeholder="{{trans('latraining.start_date')}}" autocomplete="off" value="{{ isset($result_object[4]['start_date']) ? get_date($result_object[4]['start_date']) : '' }}">
                                        </span>
                                        <span>
                                            <input name="end_date[4]" type="text" class="datepicker form-control" placeholder='{{trans("latraining.end_date")}}' autocomplete="off" value="{{ isset($result_object[4]['end_date']) ? get_date($result_object[4]['end_date']) : '' }}">
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <span>{{trans("latraining.object_view")}}</span>
                                    </div>
                                    <div class="col-10 p-0 form-inline">
                                        <span>
                                            <select name="object_view_rating[4]" class="form-control select2" data-placeholder="{{trans("latraining.choose_object_view_rating")}}">
                                                <option value=""></option>
                                                <option value="0" {{ isset($result_object[4]['object_view_rating']) && $result_object[4]['object_view_rating'] == 0 ? 'selected' : 'selected' }}> {{trans("latraining.no")}}</option>
                                                <option value="1" {{ isset($result_object[4]['object_view_rating']) && $result_object[4]['object_view_rating'] == 1 ? 'selected' : '' }}> {{trans("latraining.student")}}</option>
                                                <option value="2" {{ isset($result_object[4]['object_view_rating']) && $result_object[4]['object_view_rating'] == 2 ? 'selected' : '' }}> {{trans("latraining.unit_chief")}}</option>
                                            </select>
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label>{{trans("latraining.student_complete")}}</label>
                                    </div>
                                    <div class="col-10 p-0">
                                        <label class="radio-inline">
                                            <input type="radio" name="user_completed[4]" value="1" {{ isset($result_object[4]['user_completed']) && $result_object[4]['user_completed'] == 1 ? 'checked' : '' }}> {{trans("latraining.yes")}}
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="user_completed[4]" value="0" {{ isset($result_object[4]['user_completed']) && $result_object[4]['user_completed'] == 1 ? 'checked' : 'checked' }}> {{trans("latraining.no")}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn" id="add-object-rating-level">
                        <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                    </button>
                    <button type="button" id="closed" class="btn" data-dismiss="modal">
                        <i class="fa fa-times-circle"></i> {{ trans('labutton.close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.datepicker').datetimepicker({
        locale:'vi',
        format: 'DD/MM/YYYY'
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
    });
    $('#closed').on('click', function () {
        window.location = '';
    });

    function submit_success_rating_level_object(form){
        window.location = '';
    }

    var object_rating_result = '{{ $online_rating_level->object_rating }}';
    if(object_rating_result == 1){
        $('#object_type_1').show();
        $('#object_type_2').hide();
        $('#object_type_3').hide();
    }
    if(object_rating_result == 2){
        $('#object_type_1').show();
        $('#object_type_2').show();
        $('#object_type_3').show();
    }

    $('.radio-object-rating').on('click', function () {
        var object_rating = $(this).val();
        if (object_rating == 1){
            $('#object_type_1').show();
            $('#object_type_2').hide();
            $('#object_type_3').hide();

            $('#object_type_2 input[name=object_type\\[\\]]').prop("checked", false);
            $('#object_type_2 select[name=time_type\\[2\\]]').val('').trigger('change');
            $('#object_type_2 input[name=num_date\\[2\\]]').val('');
            $('#object_type_2 input[name=start_date\\[2\\]]').val('');
            $('#object_type_2 input[name=end_date\\[2\\]]').val('');
            $('#object_type_2 select[name=object_view_rating\\[2\\]]').val(0).trigger('change');
            $('#object_type_2 input:radio[name=user_completed\\[2\\]]').filter('[value="0"]').attr('checked', true);

            $('#object_type_3 input[name=object_type\\[\\]]').prop("checked", false);
            $('#object_type_3 select[name=time_type\\[3\\]]').val('').trigger('change');
            $('#object_type_3 input[name=num_user\\[3\\]]').val('');
            $('#object_type_3 input[name=num_date\\[3\\]]').val('');
            $('#object_type_3 input[name=start_date\\[3\\]]').val('');
            $('#object_type_3 input[name=end_date\\[3\\]]').val('');
            $('#object_type_3 select[name=object_view_rating\\[3\\]]').val(0).trigger('change');
            $('#object_type_3 input:radio[name=user_completed\\[3\\]]').filter('[value="0"]').attr('checked', true);
        }else{
            $('#object_type_1').show();
            $('#object_type_2').show();
            $('#object_type_3').show();
        }
    });

    $('#object_type_1 input[name=object_type\\[\\]]').on('click', function () {
        if(!$(this).is(':checked')){
            $('#object_type_1 select[name=time_type\\[1\\]]').val('').trigger('change');
            $('#object_type_1 input[name=num_date\\[1\\]]').val('');
            $('#object_type_1 input[name=start_date\\[1\\]]').val('');
            $('#object_type_1 input[name=end_date\\[1\\]]').val('');
            $('#object_type_1 select[name=object_view_rating\\[1\\]]').val(0).trigger('change');
            $('#object_type_1 input:radio[name=user_completed\\[1\\]]').filter('[value="0"]').attr('checked', true);
        }
    });
    $('#object_type_2 input[name=object_type\\[\\]]').on('click', function () {
        if(!$(this).is(':checked')){
            $('#object_type_2 select[name=time_type\\[2\\]]').val('').trigger('change');
            $('#object_type_2 input[name=num_date\\[2\\]]').val('');
            $('#object_type_2 input[name=start_date\\[2\\]]').val('');
            $('#object_type_2 input[name=end_date\\[2\\]]').val('');
            $('#object_type_2 select[name=object_view_rating\\[2\\]]').val(0).trigger('change');
            $('#object_type_2 input:radio[name=user_completed\\[2\\]]').filter('[value="0"]').attr('checked', true);
        }
    });
    $('#object_type_3 input[name=object_type\\[\\]]').on('click', function () {
        if(!$(this).is(':checked')){
            $('#object_type_3 select[name=time_type\\[3\\]]').val('').trigger('change');
            $('#object_type_3 input[name=num_user\\[3\\]]').val('');
            $('#object_type_3 input[name=num_date\\[3\\]]').val('');
            $('#object_type_3 input[name=start_date\\[3\\]]').val('');
            $('#object_type_3 input[name=end_date\\[3\\]]').val('');
            $('#object_type_3 select[name=object_view_rating\\[3\\]]').val(0).trigger('change');
            $('#object_type_3 input:radio[name=user_completed\\[3\\]]').filter('[value="0"]').attr('checked', true);
        }
    });
    $('#object_type_4 input[name=object_type\\[\\]]').on('click', function () {
        if(!$(this).is(':checked')){
            $('#object_type_4 #user_id').val(' ').trigger('change');
            $('#object_type_4 select[name=time_type\\[4\\]]').val('').trigger('change');
            $('#object_type_4 input[name=num_date\\[4\\]]').val('');
            $('#object_type_4 input[name=start_date\\[4\\]]').val('');
            $('#object_type_4 input[name=end_date\\[4\\]]').val('');
            $('#object_type_4 select[name=object_view_rating\\[4\\]]').val(0).trigger('change');
            $('#object_type_4 input:radio[name=user_completed\\[4\\]]').filter('[value="0"]').attr('checked', true);
        }
    });

    var time_type_1 = '{{ isset($result_object[1]['time_type']) ? $result_object[1]['time_type'] : 0 }}';
    if(time_type_1 == 0){
        $('input[name=num_date\\[1\\]]').hide();
        $('input[name=start_date\\[1\\]]').hide();
        $('input[name=end_date\\[1\\]]').hide();
    }
    if (time_type_1 == 1){
        $('input[name=start_date\\[1\\]]').show();
        $('input[name=end_date\\[1\\]]').show();

        $('input[name=num_date\\[1\\]]').hide();
        $('input[name=num_date\\[1\\]]').val('');
    }
    if (time_type_1 > 1){
        $('input[name=start_date\\[1\\]]').hide();
        $('input[name=end_date\\[1\\]]').hide();

        $('input[name=start_date\\[1\\]]').val('');
        $('input[name=end_date\\[1\\]]').val('');

        $('input[name=num_date\\[1\\]]').show();
    }

    $('#time_type_1').on('change', function () {
        var time_type = $('#time_type_1 option:selected').val();

        if (time_type == 1){
            $('input[name=start_date\\[1\\]]').show();
            $('input[name=end_date\\[1\\]]').show();

            $('input[name=num_date\\[1\\]]').hide();
            $('input[name=num_date\\[1\\]]').val('');
        }else if (time_type > 1){
            $('input[name=start_date\\[1\\]]').hide();
            $('input[name=end_date\\[1\\]]').hide();

            $('input[name=start_date\\[1\\]]').val('');
            $('input[name=end_date\\[1\\]]').val('');

            $('input[name=num_date\\[1\\]]').show();
        }else{
            $('input[name=num_date\\[1\\]]').hide();
            $('input[name=start_date\\[1\\]]').hide();
            $('input[name=end_date\\[1\\]]').hide();
        }
    });

    var time_type_2 = '{{ isset($result_object[2]['time_type']) ? $result_object[2]['time_type'] : 0 }}';
    if(time_type_2 == 0){
        $('input[name=num_date\\[2\\]]').hide();
        $('input[name=start_date\\[2\\]]').hide();
        $('input[name=end_date\\[2\\]]').hide();
    }
    if (time_type_2 == 1){
        $('input[name=start_date\\[2\\]]').show();
        $('input[name=end_date\\[2\\]]').show();

        $('input[name=num_date\\[2\\]]').hide();
        $('input[name=num_date\\[2\\]]').val('');
    }
    if (time_type_2 > 1){
        $('input[name=start_date\\[2\\]]').hide();
        $('input[name=end_date\\[2\\]]').hide();

        $('input[name=start_date\\[2\\]]').val('');
        $('input[name=end_date\\[2\\]]').val('');

        $('input[name=num_date\\[2\\]]').show();
    }

    $('#time_type_2').on('change', function () {
        var time_type = $('#time_type_2 option:selected').val();

        if (time_type == 1){
            $('input[name=start_date\\[2\\]]').show();
            $('input[name=end_date\\[2\\]]').show();

            $('input[name=num_date\\[2\\]]').hide();
            $('input[name=num_date\\[2\\]]').val('');
        }else if (time_type > 1){
            $('input[name=start_date\\[2\\]]').hide();
            $('input[name=end_date\\[2\\]]').hide();

            $('input[name=start_date\\[2\\]]').val('');
            $('input[name=end_date\\[2\\]]').val('');

            $('input[name=num_date\\[2\\]]').show();
        }else{
            $('input[name=num_date\\[2\\]]').hide();
            $('input[name=start_date\\[2\\]]').hide();
            $('input[name=end_date\\[2\\]]').hide();
        }
    });

    var time_type_3 = '{{ isset($result_object[3]['time_type']) ? $result_object[3]['time_type'] : 0 }}';
    if(time_type_3 == 0){
        $('input[name=num_date\\[3\\]]').hide();
        $('input[name=start_date\\[3\\]]').hide();
        $('input[name=end_date\\[3\\]]').hide();
    }
    if (time_type_3 == 1){
        $('input[name=start_date\\[3\\]]').show();
        $('input[name=end_date\\[3\\]]').show();

        $('input[name=num_date\\[3\\]]').hide();
        $('input[name=num_date\\[3\\]]').val('');
    }
    if (time_type_3 > 1){
        $('input[name=start_date\\[3\\]]').hide();
        $('input[name=end_date\\[3\\]]').hide();

        $('input[name=start_date\\[3\\]]').val('');
        $('input[name=end_date\\[3\\]]').val('');

        $('input[name=num_date\\[3\\]]').show();
    }

    $('#time_type_3').on('change', function () {
        var time_type = $('#time_type_3 option:selected').val();

        if (time_type == 1){
            $('input[name=start_date\\[3\\]]').show();
            $('input[name=end_date\\[3\\]]').show();

            $('input[name=num_date\\[3\\]]').hide();
            $('input[name=num_date\\[3\\]]').val('');
        }else if (time_type > 1){
            $('input[name=start_date\\[3\\]]').hide();
            $('input[name=end_date\\[3\\]]').hide();

            $('input[name=start_date\\[3\\]]').val('');
            $('input[name=end_date\\[3\\]]').val('');

            $('input[name=num_date\\[3\\]]').show();
        }else{
            $('input[name=num_date\\[3\\]]').hide();
            $('input[name=start_date\\[3\\]]').hide();
            $('input[name=end_date\\[3\\]]').hide();
        }
    });

    var time_type_4 = '{{ isset($result_object[4]['time_type']) ? $result_object[4]['time_type'] : 0 }}';
    if(time_type_4 == 0){
        $('input[name=num_date\\[4\\]]').hide();
        $('input[name=start_date\\[4\\]]').hide();
        $('input[name=end_date\\[4\\]]').hide();
    }
    if (time_type_4 == 1){
        $('input[name=start_date\\[4\\]]').show();
        $('input[name=end_date\\[4\\]]').show();

        $('input[name=num_date\\[4\\]]').hide();
        $('input[name=num_date\\[4\\]]').val('');
    }
    if (time_type_4 > 1){
        $('input[name=start_date\\[4\\]]').hide();
        $('input[name=end_date\\[4\\]]').hide();

        $('input[name=start_date\\[4\\]]').val('');
        $('input[name=end_date\\[4\\]]').val('');

        $('input[name=num_date\\[4\\]]').show();
    }

    $('#time_type_4').on('change', function () {
        var time_type = $('#time_type_4 option:selected').val();

        if (time_type == 1){
            $('input[name=start_date\\[4\\]]').show();
            $('input[name=end_date\\[4\\]]').show();

            $('input[name=num_date\\[4\\]]').hide();
            $('input[name=num_date\\[4\\]]').val('');
        }else if (time_type > 1){
            $('input[name=start_date\\[4\\]]').hide();
            $('input[name=end_date\\[4\\]]').hide();

            $('input[name=start_date\\[4\\]]').val('');
            $('input[name=end_date\\[4\\]]').val('');

            $('input[name=num_date\\[4\\]]').show();
        }else{
            $('input[name=num_date\\[4\\]]').hide();
            $('input[name=start_date\\[4\\]]').hide();
            $('input[name=end_date\\[4\\]]').hide();
        }
    });
</script>
