<form method="post" action="{{ route('module.online.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
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
                    <label for="subject_id">{{ trans('latraining.subject') }}</label><span style="color:red"> * </span>
                </div>
                <div class="col-md-9">
                    <select name="subject_id" id="subject_id" class="form-control load-subject" data-training-program="{{ $model->training_program_id }}" data-placeholder="-- {{ trans('latraining.subject') }} --" required>
                        @if(isset($subject))
                        <option value="{{ $subject->id }}" selected> {{ $subject->name }} </option>
                        @endif
                    </select>
                </div>
            </div>

            {{-- Mã Khóa Học --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.course_code')}}</label><span style="color:red"> * </span>
                </div>
                <div class="col-md-9">
                    <input name="code" id="code" type="text" class="form-control" value="{{ $model->code }}" required readonly>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.course_name')}}</label><span class="text-danger"> * </span>
                </div>
                <div class="col-md-9">
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.type_subject') }}</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="level_subject" class="form-control" value="{{ isset($level_subject) ? $level_subject->name : '' }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.time')}}</label><span class="text-danger"> * </span>
                </div>
                <div class="col-md-9">
                    <span>
                        <input name="start_date" type="text" class="datepicker form-control d-inline-block w-25" placeholder="{{trans('latraining.start_date')}}" autocomplete="off" value="{{ $model->start_date ? get_date($model->start_date) : date('d/m/Y') }}">
                    </span>
                    <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                    <span>
                        <input name="end_date" type="text" class="datepicker form-control d-inline-block w-25" placeholder='{{trans("latraining.end_date")}}' autocomplete="off" value="{{ isset($model->id) ? get_date($model->end_date) : date('t/m/Y') }}">
                    </span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.register_deadline')}}</label>
                </div>
                <div class="col-md-9">
                    <input name="register_deadline" type="text" class="form-control datepicker" placeholder="{{trans('latraining.register_deadline')}}"
                           autocomplete="off" value="{{ get_date($model->register_deadline) }}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="is_limit_time">{{ trans('latraining.limit_time') }}</label>
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
                    <label>{{trans('latraining.training_form')}}<span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <select name="training_form_id" class="form-control select2" id="" required data-placeholder="{{trans('latraining.training_form')}}">
                        <option value=""></option>
                        @if($training_forms)
                            @foreach($training_forms as $training_form)
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
                    <input name="max_grades" type="text" class="form-control is-number" value="{{ $model->max_grades }}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.min_grades') }}</label>
                </div>
                <div class="col-md-9">
                    <input name="min_grades" type="text" class="form-control is-number" value="{{ $model->min_grades }}">
                </div>
            </div>

            {{-- ĐỐI TƯỢNG THAM GIA --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.object_join') }}</label>
                </div>
                <div class="col-md-9">
                    <select name="training_object_id[]" id="training_object_id" class="form-control select2" data-placeholder="-- {{ trans('latraining.object_join') }} --" multiple>
                        @foreach ($training_objects as $training_object)
                            <option value="{{ $training_object->id }}" {{ !empty($get_training_object_id) && in_array($training_object->id, $get_training_object_id) ? 'selected' : '' }}>{{ $training_object->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.course_action') }}</label>
                </div>
                <div class="col-md-9">
                    <select name="course_action" id="course_action" class="form-control" data-placeholder="-- {{ trans('latraining.course_action') }} --" required>
                        <option value="0" {{ empty($model->course_action) ? 'selected' : '' }}>{{ trans('latraining.choose') }}</option>
                        <option {{ $model->course_action == '1' ? 'selected' : '' }} value="1"> {{ trans('latraining.plan') }}</option>
                        <option  {{ $model->course_action == '2' ? 'selected' : '' }} value="2"> {{ trans('latraining.incurred') }}</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="check-in-plan">{{trans('latraining.plan')}}</label>
                </div>
                <div class="col-md-9">
                    <input id="check-in-plan" type="checkbox" value="{{ $model->in_plan ? 1 : 0 }}" {{ $model->in_plan ? 'checked' : '' }}>
                </div>
            </div>

            <div class="form-group row" id="in-plan">
                <div class="col-sm-3 control-label">
                    <label for="in_plan">{{trans('latraining.training_plan')}}</label>
                </div>
                <div class="col-md-9">
                    <select name="in_plan" class="form-control select2" data-placeholder="-- {{trans('latraining.training_plan')}} --">
                        <option value=""></option>
                        @foreach($training_plan as $item)
                            <option value="{{ $item->id }}" {{ $model->in_plan == $item->id ? 'selected' : '' }} > {{ $item->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="description">{{trans('latraining.brief')}}</label>
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
                    @if($permission_save && $model->lock_course == 0 && !$user_invited)
                        <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    @endif
                    <a href="{{ route('module.online.management') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{trans('lamenu.approve_register')}}</label>
                <div>
                    {{-- <label class="radio-inline"><input type="radio" name="auto" value="1" {{ $model->auto == 1 ? 'checked' : '' }}> {{trans('backend.elective')}} </label> --}}
                    <label class="radio-inline"><input type="radio" name="auto" value="0" {{ $model->auto == 0 ? 'checked' : '' }}> {{trans('latraining.obligatory')}} </label>
                    <label class="radio-inline"><input type="radio" name="auto" value="2" {{ $model->auto == 2 ? 'checked' : '' }}> {{trans('latraining.auto')}} </label>
                </div>
            </div>
            <div class="form-group">
                <label>{{trans('latraining.picture')}} (800 x 500)</label>
                <div>
                    <a href="javascript:void(0)" id="select-image">{{trans('latraining.choose_picture')}}</a>
                    <div id="image-review">
                        @if($model->image)
                            <img src="{{ image_file($model->image) }}" alt="">
                        @endif
                    </div>

                    <input name="image" id="image-select" type="hidden" value="{{ $model->image }}">
                </div>
            </div>
            <div class="form-group">
                <label>{{trans('latraining.time')}}</label>
                <div class="input-group">
                    <input name="course_time" type="text" class="form-control" value="{{ $course_time ? $course_time : '' }}">
                    <span class="input-group-addon">
                        <select name="course_time_unit" id="course_time_unit" class="form-control">
                            <option value="day" {{ $course_time_unit == 'day' ? 'selected' : '' }}>{{trans('latraining.date')}}</option>
                            <option value="session" {{ $course_time_unit == 'session' ? 'selected' : '' }}>{{trans('latraining.session')}}</option>
                            <option value="hour" {{ $course_time_unit == 'hour' ? 'selected' : '' }}>{{trans('latraining.hour')}}</option>
                        </select>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label>{{trans('latraining.number_lesson')}}</label>
                <input name="num_lesson" type="number" class="form-control is-number" value="{{ $model->num_lesson }}">
            </div>
            <div class="form-group">
                <label>{{trans('latraining.certificate')}} </label>
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

            {{-- ĐÁNH GIÁ HIỆU QUẢ ĐÀO TẠO --}}
            <div class="form-group">
                <label>{{ trans('lamenu.app_plan_template') }}</label>
                <div>
                    <select name="action_plan" id="action_plan" class="form-control " data-placeholder="-- {{ trans('lamenu.app_plan_template') }} --">
                        <option value="0" {{ $model->action_plan == 0 ? 'selected' : '' }}> {{trans('backend.no')}} </option>
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
                    <input type="number" placeholder="{{ trans('latraining.time_done_after_finished') }}" name="plan_app_day" value="{{ $model->plan_app_day }}" class="form-control" >
                </div>
                <div class="pt-1">
                    <input type="number" placeholder="{{ trans('latraining.time_rate_after_finished') }}" name="plan_app_day_student" value="{{ $model->plan_app_day_student }}" class="form-control" >
                </div>
                <div class="pt-1">
                    <input type="number" placeholder="{{ trans('latraining.time_leader_rate_after_finished') }}" name="plan_app_day_manager" value="{{ $model->plan_app_day_manager }}" class="form-control" >
                </div>
            </div>

            <div class="form-group">
                <label for="unit_id">{{ trans('latraining.leader_registed') }}</label>
                <select name="unit_id[]" id="unit_id" class="form-control select2" data-placeholder="-- {{ trans('latraining.choose_unit') }} --" multiple >
                    <option value=""></option>
                    @foreach($units as $item)
                        <option value="{{ $item->id }}" {{ isset($unit) && in_array($item->id, $unit) ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>{{ trans('latraining.training_calendar_color') }}</label>
                <div class="">
                    <input type="color" name="color" id="color" class="avatar avatar-40 shadow-sm change_color" value="{{ !is_null($model->color) ? $model->color : 'fff' }}"> {{ trans('latraining.background') }}
                    <input type="checkbox" name="i_text" id="i_text" class="ml-1" value="{{ $model->i_text }}" @if($model->i_text == 1) checked @endif>
                    <label for="i_text">{{ trans('latraining.italic') }}</label>
                    <input type="checkbox" name="b_text" id="b_text" class="ml-1" value="{{ $model->b_text }}" @if($model->b_text == 1) checked @endif>
                    <label for="b_text">{{ trans('latraining.bold') }}</label>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="modal fade" id="modal-qrcode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{trans('latraining.survey_code_QR')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    @if ($qrcode_survey_after_course)
                        <div id="qrcode" > {{--style="position:absolute;width: 60vh;height: calc(60vh * 1.414285714)"--}}
                            {!! QrCode::size(300)->generate($qrcode_survey_after_course); !!}
                            <p>{{trans('latraining.scan_code')}}</p>
                        </div>
                    @endif
                    <a href="javascript:void(0)" id="print_qrcode">{{trans('latraining.print_qr_code')}}</a>
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
    CKEDITOR.replace('content', {
        filebrowserImageBrowseUrl: '/filemanager?type=image',
        filebrowserBrowseUrl: '/filemanager?type=file',
        filebrowserUploadUrl : null, //disable upload tab
        filebrowserImageUploadUrl : null, //disable upload tab
        filebrowserFlashUploadUrl : null, //disable upload tab
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

    $('.change_color').on('change', function () {
        var set_color = $(this).val();
    });

    $('#i_text').on('click', function () {
        if($(this).is(':checked')){
            $('#i_text').val(1);
        }else{
            $('#i_text').val(0);
        }
    });

    $('#b_text').on('click', function () {
        if($(this).is(':checked')){
            $('#b_text').val(1);
        }else{
            $('#b_text').val(0);
        }
    });

    $("#select-document").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'files'}, function (url, path) {
            var path2 =  path.split("/");
            var html = `<span>`+ path2[path2.length - 1] +`</span>
                        <div class="trash_document" onclick="deleteDocument()">
                            <i class="fas fa-trash"></i>
                        </div>`;

            $("#document-review").html(html);
            $("#document-select").val(path);
        });
    });

    function deleteDocument() {
        $("#document-review").html('');
        $("#document-select").val('');
    }
</script>
