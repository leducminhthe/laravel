<form method="post" action="{{ route('module.course_plan.save', ['course_type' => $course_type]) }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model->id }}">
    <div class="row">
        <div class="col-md-9">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.training_program')}}<span style="color:red"> * </span></label>
                </div>
                <div class="col-md-9">
                    <select name="training_program_id" id="training_program_id" class="form-control load-training-program"
                            data-placeholder="-- {{trans('latraining.training_program')}} --" required>
                        @if(isset($training_program))
                            <option value="{{ $training_program->id }}" selected> {{ $training_program->name }} </option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="subject_id">{{ trans('backend.subject') }}</label><span style="color:red"> * </span>
                </div>
                <div class="col-md-9">
                    <select name="subject_id" id="subject_id" class="form-control load-subject" data-level-subject="{{ $model->level_subject_id }}" data-training-program="{{ $model->training_program_id }}" data-placeholder="-- {{ trans('backend.subject') }} --" required>
                        @if(isset($subject))
                        <option value="{{ $subject->id }}" selected> {{ $subject->name }} </option>
                        @endif
                    </select>
                </div>
            </div>
            {{--<div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.course_code')}}</label><span style="color:red"> * </span>
                </div>
                <div class="col-md-9">
                    <input name="code" id="code" type="text" class="form-control" value="{{ $model->code }}" required readonly>
                </div>
            </div>--}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.class_name') }}</label><span class="text-danger"> * </span>
                </div>
                <div class="col-md-9">
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.training_time') }}</label><span class="text-danger"> * </span>
                </div>
                <div class="col-md-9">
                    <span><input name="start_date" type="text" class="datepicker form-control d-inline-block w-25"
                                 placeholder="{{trans('laother.choose_start_date')}}" autocomplete="off" value="{{ get_date($model->start_date) }}"></span>
                    <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                    <span><input name="end_date" type="text" class="datepicker form-control d-inline-block w-25"
                                 placeholder='{{trans("backend.choose_end_date")}}' autocomplete="off" value="{{ get_date($model->end_date) }}"></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.register_deadline')}}</label>
                </div>
                <div class="col-md-9">
                    <input name="register_deadline" type="text" class="form-control datepicker" placeholder="{{trans('backend.choose_register_deadline')}}"
                           autocomplete="off" value="{{ get_date($model->register_deadline) }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="check-in-plan">{{ trans('latraining.limit_time') }}</label>
                </div>
                <div class="col-md-9">
                    <input id="is_limit_time" name="is_limit_time" type="checkbox" value="{{ $model->is_limit_time ? 1 : 0 }}" {{ $model->is_limit_time ? 'checked' : '' }}>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.time_limit') }}</label>
                </div>
                <div class="col-md-4">
                    <input name="start_timeday" type="text" class="form-control" placeholder="hh:mm" value="{{ $model->start_timeday }}">
                </div>
                <div class="col-md-4">
                    <input name="end_timeday" type="text" class="form-control" placeholder="hh:mm" value="{{ $model->end_timeday }}">
                </div>
            </div>

            {{-- LOẠI HÌNH ĐÀO TẠO --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="check-in-plan">{{ trans('backend.training_form') }}<span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <select name="training_form_id" class="form-control select2" id="" required>
                        <option value="" disabled selected>{{ trans('backend.training_form') }}</option>
                        @if($training_forms_online)
                            @foreach($training_forms_online as $training_form)
                                <option value="{{ $training_form->id }}" {{ $model->training_form_id == $training_form->id ?
                                'selected' : '' }}>{{ $training_form->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.max_grades') }}</label>
                </div>
                <div class="col-md-9">
                    <input name="max_grades" type="text" class="form-control" value="{{ $model->max_grades }}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.min_grades') }}</label>
                </div>
                <div class="col-md-9">
                    <input name="min_grades" type="text" class="form-control" value="{{ $model->min_grades }}">
                </div>
            </div>

            {{--  <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.title_join') }}</label>
                </div>
                <div class="col-md-9">
                    <select name="title_join_id[]" id="title_join_id" class="form-control select2" data-placeholder="-- {{ trans('latraining.title_join') }} --" multiple>
                        <option value="0" {{ !empty($get_title_join_model_id) && in_array(0, $get_title_join_model_id) ? 'selected' : '' }}>{{ trans('latraining.select_all') }}</option>
                        @foreach ($titles as $title)
                            <option value="{{ $title->id }}" {{ !empty($get_title_join_model_id) && in_array($title->id, $get_title_join_model_id) ? 'selected' : '' }}>{{ $title->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.title_recommend') }}</label>
                </div>
                <div class="col-md-9">
                    <select name="title_recommend_id[]" id="title_recommend_id" class="form-control select2" multiple data-placeholder="-- {{ trans('latraining.title_recommend') }} --">
                        <option value="0" {{ !empty($get_title_recommend_model_id) && in_array(0, $get_title_recommend_model_id) ? 'selected' : '' }}>{{ trans('latraining.select_all') }}</option>
                        @foreach ($titles as $title)
                            <option value="{{ $title->id }}" {{ !empty($get_title_recommend_model_id) && in_array($title->id, $get_title_recommend_model_id) ? 'selected' : '' }}>{{ $title->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>  --}}

            {{-- NHÓM ĐỐI TƯỢNG THAM GIA --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.object_join') }}</label>
                </div>
                <div class="col-md-9">
                    <select name="training_object_id[]" id="training_object_id" class="form-control select2" multiple data-placeholder="-- {{ trans('latraining.object_join') }} --">
                        @foreach ($training_objects as $training_object)
                            <option value="{{ $training_object->id }}" {{ !empty($get_training_object_model_id) && in_array($training_object->id, $get_training_object_model_id) ? 'selected' : '' }}>{{ $training_object->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="description">{{trans('backend.brief')}}</label>
                </div>
                <div class="col-md-9">
                    <textarea name="description" id="description" rows="4" class="form-control">{{ $model->description }}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.description')}}</label>
                </div>
                <div class="col-md-9">
                    <textarea name="content" id="content" class="form-control">{!! $model->content !!}</textarea>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row row-acts-btn">
                <div class="col-sm-12">
                    <div class="btn-group act-btns">
                        @if ($model->status_convert != 1)
                            @can(['course-plan-edit','course-plan-create'])
                                <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                            @endcan
                        @endif

                        <a href="{{ route('module.course_plan.management') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{trans('backend.aprove_course')}} <span class="text-danger"> * </span></label>
                <div>
                    {{--  <label class="radio-inline"><input type="radio" name="auto" value="1" {{ $model->auto == 1 ? 'checked' : '' }}> {{trans('backend.elective')}} </label>  --}}
                    <label class="radio-inline"><input type="radio" name="auto" value="0" {{ $model->auto == 0 ? 'checked' : '' }}> {{trans('backend.obligatory')}} </label>
                    <label class="radio-inline"><input type="radio" name="auto" value="2" {{ $model->auto == 2 ? 'checked' : '' }}> {{trans('latraining.auto')}} </label>
                </div>
            </div>
            <div class="form-group">
                <label>{{trans('backend.picture')}} (300 x 200)</label>
                <div>
                    <a href="javascript:void(0)" id="select-image">{{trans('latraining.choose_picture')}}</a>
                    <div id="image-review">
                        @if($model->image)
                            <img src="{{ image_file($model->image) }}" alt="">
                        @endif
                    </div>

                    <input name="image" id="image-select" type="text" class="d-none" value="{{ $model->image }}">
                </div>
            </div>
            <div class="form-group">
                <label>{{ trans('lareport.duration') }}</label>
                <div class="input-group">
                    <input name="course_time" type="text" class="form-control" value="{{ $course_time ? $course_time : '' }}">
                    <span class="input-group-addon">
                        <select name="course_time_unit" id="course_time_unit" class="form-control">
                            <option value="day" {{ $course_time_unit == 'day' ? 'selected' : '' }}>{{trans('latraining.date')}}</option>
                            <option value="session" {{ $course_time_unit == 'session' ? 'selected' : '' }}>{{trans('backend.session')}}</option>
                            <option value="hour" {{ $course_time_unit == 'hour' ? 'selected' : '' }}>{{trans('backend.hour')}}</option>
                        </select>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label>{{trans('backend.lesson')}} ({{  trans('backend.number_lesson') }})</label>
                <input name="num_lesson" type="text" class="form-control" value="{{ $model->num_lesson }}">
            </div>
            <div class="form-group">
                <label>{{trans('backend.certificate')}} </label>
                <div>
                    <select style="width: 65%;" name="cert_code" id="cert_code" class="form-control d-inline-block"  @if($model->has_cert == 0) disabled @endif >
                        @foreach($certificate as $item)
                            <option value="{{ $item->id }}" {{ $model->cert_code == $item->id ? 'selected' : '' }}>{{ $item->code }}</option>
                        @endforeach
                    </select>
                    <input name="has_cert" id="has_cert" type="checkbox" class="form-custom"  value="{{ $model->has_cert }}"  @if($model->has_cert == 1) checked @endif>
                    {{trans('latraining.enable')}}
                </div>
            </div>
            <div class="form-group">
                <label>{{trans('backend.evaluate_training_effectiveness')}}</label>
                <div>
                    <select name="action_plan" id="action_plan" class="form-control select2" data-placeholder="-- Đánh giá hiệu quả đào tạo --">
                        <option value="0" {{ $model->action_plan == 0 && !is_null($model->action_plan) ? 'selected' : '' }}> {{trans('backend.no')}} </option>
                        <option value="1" {{ $model->action_plan == 1 ? 'selected' : '' }}> {{trans('backend.yes')}} </option>
                    </select>
                </div>
                <div class="pt-1 contain_plan_app_template">
                    @if(isset($plan_app_template))
                        <select name="plan_app_template" class="form-control select2" data-placeholder="{{trans('backend.choose_evaluation_form')}}">
                            <option value="">{{trans('backend.choose_evaluation_form')}}</option>
                            @foreach($plan_app_template as $item)
                                <option value="{{$item->id}}" {{ $model->plan_app_template == $item->id ? 'selected':''}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="pt-1">
                    <input type="number" placeholder="Thời gian thực hiện ({{ trans('latraining.date') }})" name="plan_app_day" value="{{$model->plan_app_day}}" class="form-control" >
                </div>
            </div>
            {{--<div class="form-group">
                <label>{{trans('backend.assessment_after_the_course')}}</label>
                <div>
                    <select name="rating" id="rating" class="form-control select2" data-placeholder="-- {{trans('backend.assessment_after_the_course')}} --">
                        <option value=""></option>
                        <option value="1" {{ $model->rating == 1 ? 'selected' : '' }}> {{trans('backend.yes')}} </option>
                        <option value="0" {{ $model->rating == 0 ? 'selected' : '' }}> {{trans('backend.no')}} </option>
                    </select>
                </div>
            </div>
            <div class="form-group" id="form-template">
                <label for="template_id"> {{trans('backend.choose_evaluation_form')}}</label>
                <div>
                    <select name="template_id" id="template_id" class="form-control select2" data-placeholder="-- {{trans('backend.choose_evaluation_form')}} --">
                    @if(isset($templates))
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}" {{ $model->template_id == $template->id ? 'selected' : '' }}> {{ $template->name }}</option>
                        @endforeach
                    @endif
                    </select>
                    @if($model->template_id)
                    <a href="javscript:void(0)" id="modal_qrcode">{{trans('backend.Qrcode_evaluation_form')}}</a>
                    @endif
                </div>
            </div>--}}

            {{-- LOẠI ĐƠN VỊ --}}
            {{--  <div class="form-group">
                <label for="unit_type">{{ trans('lacategory.unit_type') }}</label>
                <select name="unit_type" id="unit_type" class="form-control select2" data-placeholder="-- {{ trans('lacategory.unit_type') }} --">
                    <option value=""></option>
                    @foreach($units_type as $unit_type)
                        <option value="{{ $unit_type->id }}" {{ $unit_type->id == $model->unit_type ? 'selected' : '' }}>{{ $unit_type->name }}</option>
                    @endforeach
                </select>
            </div>  --}}

            <div class="form-group">
                <label for="unit_id">{{trans('latraining.leader_registed')}}</label>
                <select name="unit_id[]" id="unit_id" class="form-control select2" data-placeholder="-- {{ trans('backend.choose_unit') }} --" multiple >
                    <option value=""></option>
                    @foreach($units as $item)
                        <option value="{{ $item->id }}" {{ isset($unit) && in_array($item->id, $unit) ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</form>
<div class="modal fade" id="modal-qrcode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{trans('backend.survey_code_QR')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    @if ($qrcode_survey_after_course)
                        <div id="qrcode" > {{--style="position:absolute;width: 60vh;height: calc(60vh * 1.414285714)"--}}
                            {!! QrCode::size(300)->generate($qrcode_survey_after_course); !!}
                            <p>{{trans('backend.scan_code')}}</p>
                        </div>
                    @endif
                    <a href="javascript:void(0)" id="print_qrcode">In QR Code</a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('styles/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
    $('#unit_type').on('change', function () {
        var unit_type = $('#unit_type option:selected').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('module.course_plan.ajax_get_unit') }}',
            dataType: 'json',
            data: {
                'unit_type': unit_type,
                '_token': '{{ csrf_token() }}',
            }
        }).done(function(data) {
            let html = '';
            $.each(data, function (i, item){
                html+='<option value='+ item.id +'>'+ item.name +'</option>';
            });
            $("#unit_id").html(html);
        }).fail(function(data) {
            show_message("{{ trans('laother.data_error') }}", 'error');
            return false;
        });
    });

    $('#modal_qrcode').on('click',function () {
        $("#modal-qrcode").modal();
    });
    $('#print_qrcode').on("click", function () {
        $('#qrcode').printThis();
    });
    var item_teacher_evaluation = $('#teacher_evaluation option:selected').val();

    if(item_teacher_evaluation == 1){
        $('#form-teacher').show();
    }else{
        $('#form-teacher').hide();
    }

    $('#teacher_evaluation').on('change', function() {
        var item = $('#teacher_evaluation option:selected').val();

        if(item == 1){
            $('#form-teacher').show();
        }else{
            $('#form-teacher').hide();
        }
    });
    var item_rating = $('#rating option:selected').val();

    if(item_rating == 1){
        $('#form-template').show();
    }else{
        $('#form-template').hide();
    }
    $('#rating').on('change', function() {
        var item = $('#rating option:selected').val();

        if(item == 1){
            $('#form-template').show();
        }else{
            $('#form-template').hide();
        }
    });

    var check_in_plan = $('#check-in-plan').val();

    if(check_in_plan == 1){
        $('#in-plan').show();
    }else{
        $('#in-plan').hide();
    }

    $('#check-in-plan').on('click', function () {
        if ($(this).is(':checked')){
            var check_in_plan = $('#check-in-plan').val(1);
            $('#in-plan').show();
        }else {
            var check_in_plan = $('#check-in-plan').val(0);
            $('#in-plan').hide();
            $('#in_plan option:selected').val(null).trigger('change');
        }

    });

    $('#is_limit_time').on('click', function () {

        if ($(this).is(':checked')){
            $('#is_limit_time').val(1);
            $('input[name="start_timeday"]').prop('required',true);
            $('input[name="end_timeday"]').prop('required',true);
        }else {
            $('#is_limit_time').val(0);
            $('input[name="start_timeday"]').prop('required',false);
            $('input[name="end_timeday"]').prop('required',false);
        }

    });

    $('#title_join_id').on('change',function() {
        var get_value = $('#title_join_id').val();
        if (get_value.includes(0)) {
            $('#title_join_id').removeAttr('multiple');
            $('#title_join_id').val(0)
        } else {
            $('#title_join_id').attr('multiple','multiple');
        }
    });

    $('#title_recommend_id').on('change',function() {
        var get_value = $('#title_recommend_id').val();
        if (get_value.includes(0)) {
            $('#title_recommend_id').removeAttr('multiple');
            $('#title_recommend_id').val(0)
        } else {
            $('#title_recommend_id').attr('multiple','multiple');
        }
    });
</script>
<script>
    CKEDITOR.replace('content', {
        filebrowserImageBrowseUrl: '/filemanager?type=image',
        filebrowserBrowseUrl: '/filemanager?type=file',
        filebrowserUploadUrl : null, //disable upload tab
        filebrowserImageUploadUrl : null, //disable upload tab
        filebrowserFlashUploadUrl : null, //disable upload tab
    });
</script>
