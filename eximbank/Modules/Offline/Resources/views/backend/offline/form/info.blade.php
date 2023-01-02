<form method="post" action="{{ route('module.offline.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="unit_id" value="{{ $model->unit_id ? $model->unit_id : $is_unit }}">
    <input type="hidden" name="id" value="{{ $model->id }}">
    <div class="row">
        <div class="col-md-9">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.training_program')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <select name="training_program_id" id="training_program_id" class="form-control load-training-program" data-placeholder="-- {{trans('latraining.training_program')}} --" required>
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
                    <select name="subject_id" id="subject_id" class="form-control load-subject" {{--data-level-subject="{{ $model->level_subject_id }}"--}} data-training-program="{{ $model->training_program_id }}" data-placeholder="-- {{ trans('latraining.subject') }} --" required>
                        @if(isset($subject))
                            <option value="{{ $subject->id }}" selected> {{ $subject->name }} </option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.course_code')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input name="code" id="code" type="text" class="form-control" value="{{ $model->code }}" required readonly>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.course_name')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.type_subject')}}</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="level_subject" class="form-control" value="{{ isset($level_subject) ? $level_subject->name : '' }}" readonly>
                </div>
            </div>

            {{-- THÊM GIẢNG VIÊN --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>
                        {{trans('latraining.teacher')}}
                        <span class="text-danger">*</span>
                        @if (isset($getTrainingTeacher))
                            <span class="cursor_pointer" onclick="openModalTeacher({{ $model->id }})"><i class="fas fa-info-circle"></i></span>
                        @endif
                    </label>
                </div>
                <div class="col-md-9">
                    <select name="teacher_course[]" id="teacher_course" class="form-control load-user" data-placeholder="-- {{trans('latraining.teacher')}} --" multiple required>
                        @if (isset($getTrainingTeacher))
                            @foreach ($getTrainingTeacher as $teacher)
                                <option value="{{ $teacher->user_id }}" selected>{{ $teacher->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            {{--  <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.max_grades')}}</label>
                </div>
                <div class="col-md-9">
                    <input name="max_grades" type="number" class="form-control is-number" value="{{ $model->max_grades }}">
                </div>
            </div>  --}}


            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.pass_score')}}</label>
                </div>
                <div class="col-md-9">
                    <input name="min_grades" type="number" class="form-control is-number" value="{{ $model->min_grades }}">
                </div>
            </div>

            {{--  <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.max_student')}}</label>
                </div>
                <div class="col-md-9">
                    <input name="max_student" type="number" class="form-control" value="{{ $model->max_student }}">
                </div>
            </div>  --}}

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.group_object_join')}}</label>
                </div>
                <div class="col-md-9">
                    <select name="training_object_id[]" id="training_object_id" class="form-control select2" data-placeholder="-- {{trans('latraining.group_object_join')}} --" multiple>
                        @foreach ($training_objects as $training_object)
                            <option value="{{ $training_object->id }}" {{ !empty($get_training_object_id) && in_array($training_object->id, $get_training_object_id) ? 'selected' : '' }}>{{ $training_object->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- KHU VỰC ĐÀO TẠO --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.training_area') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <select name="training_area_id[]" id="training_area_id" class="form-control load-area-all" autocomplete="off" multiple data-placeholder="--{{trans('latraining.training_area')}}--" required>
                        <option value="">{{trans('lacategory.area_choose')}}</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" {{ !empty($training_area) && in_array($area->id, $training_area) ? 'selected' : '' }}>{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.training_location')}} </label>
                </div>
                <div class="form-group col-sm-9 m-0">
                    <div class="row">
                    <div class="col-md-4">
                        <select name="province" id="province_id" data-url="{{route('module.offline.filter.location')}}" class="select2 form-control">
                            <option value="">{{trans('latraining.choose_province')}}</option>
                            @foreach($province as $item)
                                <option value="{{ $item->code }}" @if($item->code == $model->training_location_province) selected @endif> {{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="district" id="district_id" class="select2 form-control">
                            <option value="">{{trans('latraining.choose_district')}}</option>
                            @if($district)
                                @foreach($district as $item)
                                    <option value="{{$item->id}}" @if( $item->id == $model->training_location_district) selected @endif> {{$item->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="training_location_id" id="training_location_id" data-placeholder="{{trans('latraining.choose_training_location')}}" class="form-control
                        select2" data-url="{{route('module.offline.filter.traininglocation')}}">
                            <option value="">{{ trans('latraining.choose_training_location') }}</option>
                            @if($training_location)
                                @foreach($training_location as $item)
                                    <option value="{{$item->id}}" @if($item->id == $model->training_location_id) selected @endif> {{$item->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                </div>
            </div>

            {{-- LOẠI HÌNH ĐÀO TẠO --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.training_form')}}<span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <select name="training_form_id" id="training_form_id" class="form-control select2" data-placeholder="{{trans('latraining.training_form')}}" required>
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

            {{-- ĐƠN VỊ TỔ CHỨC --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.organizational_units')}}</label>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-4 col-md-3 pr-0">
                            <select name="training_unit_type" id="type_training_partner" class="type_training_partner select2">
                                <option value="" disabled selected>{{trans('latraining.organizational_units')}}</option>
                                <option value="0" {{ $model->training_unit_type == 0 ? 'selected' : '' }}> {{trans('latraining.internal')}}</option>
                                <option value="1" {{ $model->training_unit_type == 1 ? 'selected' : '' }}> {{trans('latraining.outside')}}</option>
                            </select>
                        </div>
                        <div class="col-8 col-md-9">
                            <div id="unit_type_training_partner">
                                <select name="training_unit[]" id="choose_unit_training_partner" class="form-control select2" data-placeholder="{{ trans('latraining.unit') }}" multiple>
                                    <option value=""></option>
                                    @foreach($units as $item)
                                        <option value="{{ $item->id }}" {{ !empty($training_unit) && in_array($item->id, $training_unit) ? 'selected' : '' }}>{{$item->code}} - {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="training_partner">
                                <select name="training_unit[]" id="select_training_partner" class="form-control select2" data-placeholder="{{ trans('latraining.unit') }}" multiple>
                                    @foreach ($training_partners as $tp)
                                        <option value="{{ $tp->id }}" {{ !empty($training_unit) && in_array($tp->id, $training_unit) ? 'selected' : '' }}>{{$tp->code}} - {{ $tp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ĐƠN VỊ PHỐI HỢP --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.coordinating_unit') }}</label>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-4 col-md-3 pr-0">
                            <select name="training_partner_type" id="type_responsable" class="type_responsable select2">
                                <option value="" disabled selected></option>
                                <option value="0" {{ $model->training_partner_type == 0 ? 'selected' : '' }}> {{trans('latraining.internal')}}</option>
                                <option value="1" {{ $model->training_partner_type == 1 ? 'selected' : '' }}> {{trans('latraining.outside')}}</option>
                            </select>
                        </div>
                        <div class="col-8 col-md-9">
                            <div id="unit_type_responsable">
                                <select name="training_partner_id[]" id="choose_unit_responsable" class="form-control select2" data-placeholder="{{ trans('latraining.unit') }}" multiple>
                                    @foreach($units as $item)
                                        <option value="{{ $item->id }}" {{ !empty($training_partner) && in_array($item->id, $training_partner) ? 'selected' : '' }}>{{$item->code}} - {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="responsable">
                                <select name="training_partner_id[]" id="select_responsable" class="form-control select2" multiple>
                                    @foreach($training_partners as $tp)
                                        <option value="{{ $tp->id }}" {{ !empty($training_partner) && in_array($tp->id, $training_partner) ? 'selected' : '' }}>{{$tp->code}} - {{ $tp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--  <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.instructor_type') }}</label>
                </div>
                <div class="col-md-9">
                    <select name="teacher_type_id" id="teacher_type_id" class="form-control load-teacher-type" data-placeholder="--{{ trans('latraining.instructor_type') }}--" >
                    @if(isset($teacher_type))
                        <option value="{{ $teacher_type->id }}" selected> {{ $teacher_type->name }} </option>
                    @endif
                    </select>
                </div>
            </div>  --}}

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.time')}}</label><span class="text-danger"> * </span>
                </div>
                <div class="col-md-9">
                    <span><input name="start_date" type="text" placeholder="{{trans('latraining.start_date')}}" class="datepicker form-control
                    d-inline-block w-25" autocomplete="off" value="{{ $model->start_date ? get_date($model->start_date) : date('d/m/Y') }}"></span>

                    <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                    <span><input name="end_date" type="text" placeholder='{{trans("latraining.end_date")}}' class="datepicker form-control
                    d-inline-block w-25" autocomplete="off" value="{{ $model->end_date ? get_date($model->end_date) : date('t/m/Y') }}"></span>
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
                    <label>{{trans('latraining.course_for')}} </label>
                </div>
                <div class="col-md-9">
                    <select name="course_employee" id="course_employee" class="form-control" data-placeholder="-- {{trans('latraining.course_for')}} --">
                        <option value="0" selected> {{trans('latraining.choose')}}</option>
                        <option {{ $model->course_employee =='1' ? 'selected' : '' }} value="1"> {{trans('latraining.newly_recruited_staff')}}</option>
                        <option {{ $model->course_employee =='2' ? 'selected' : '' }} value="2"> {{trans('latraining.existing_employees')}}</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.implementation_course')}}</label>
                </div>
                <div class="col-md-9">
                    <select name="course_action" id="course_action" class="form-control" data-placeholder="-- {{trans('latraining.implementation_course')}} --" required>
                        <option value="0" {{ empty($model->course_action) ? 'selected' : '' }}> {{trans('latraining.choose')}}</option>
                        <option {{ $model->course_action =='1' ? 'selected' : '' }} value="1"> {{trans('latraining.plan')}}</option>
                        <option  {{ $model->course_action =='2' ? 'selected' : '' }} value="2"> {{trans('latraining.incurred')}}</option>
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
                    <select name="in_plan" id="in_plan" class="form-control select2" data-placeholder="-- {{trans('latraining.choose_plan')}} --">
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
                    <textarea name="content" id="content" class="form-control">{!! $model->content  !!}</textarea>
                </div>
            </div>

        </div>
        <div class="col-md-3">
            <div class="row row-acts-btn">
                <div class="col-sm-12">
                    <div class="btn-group act-btns">
                    @if(userCan(['offline-course-create', 'offline-course-edit']) && $model->lock_course == 0 && !$user_invited)
                        <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                    @endif
                        <a href="{{ route('module.offline.management') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                    </div>
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
                <label>{{trans('latraining.study_time')}}</label>
                <div class="input-group">
                    <input name="course_time" type="text" class="form-control" value="{{ if_empty($course_time, '') }}">
                    <span class="input-group-addon">
                        <select name="course_time_unit" id="course_time_unit" class="form-control">
                            <option value="day" {{ $course_time_unit == 'day' ? 'selected' : '' }}> {{trans('latraining.date')}}</option>
                            <option value="session" {{ $course_time_unit == 'session' ? 'selected' : '' }}> {{trans('latraining.session')}}</option>
                            <option value="hour" {{ $course_time_unit == 'hour' ? 'selected' : '' }}> {{trans('latraining.hour')}}</option>
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
                    <input name="has_cert" id="has_cert" type="checkbox" class="form-custom" value="{{ $model->has_cert?$model->has_cert : 0}}" {{ $model->has_cert == 1 ? 'checked' : '' }}>{{trans("latraining.enable")}}
                </div>
            </div>

            {{-- ĐÁNH GIÁ HIỆU QUẢ ĐÀO TẠO --}}
            <div class="form-group">
                <label>{{trans("lamenu.app_plan_template")}}</label>
                <div>
                    <select name="action_plan" id="action_plan" class="form-control " data-placeholder="-- {{trans("lamenu.app_plan_template")}} --">
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
                    <input type="number" placeholder="{{trans("latraining.time_done_after_finished")}}" name="plan_app_day" value="{{ $model->plan_app_day }}" class="form-control" >
                </div>
                <div class="pt-1">
                    <input type="number" placeholder="{{trans("latraining.time_rate_after_finished")}}" name="plan_app_day_student" value="{{ $model->plan_app_day_student }}" class="form-control" >
                </div>
                <div class="pt-1">
                    <input type="number" placeholder="{{trans("latraining.time_leader_rate_after_finished")}}" name="plan_app_day_manager" value="{{ $model->plan_app_day_manager }}" class="form-control" >
                </div>
            </div>

            {{-- <div class="form-group">
                <label>{{trans('backend.assessment_after_the_course')}}</label>
                <div>
                    <select name="rating" id="rating" class="form-control select2" data-placeholder="-- {{trans('backend.assessment_after_the_course')}} --">
                        <option value=""></option>
                        <option value="1" {{ $model->rating == 1 ? 'selected' : '' }}> {{trans('backend.yes')}} </option>
                        <option value="0" {{ $model->rating == 0 ? 'selected' : '' }}> {{trans('backend.no')}} </option>
                    </select>
                </div>
            </div>
            <div id="form-template">
                <div class="form-group">
                    <label for="template_id"> {{trans('backend.choose_evaluation_form')}} </label>
                    <div>
                        <select name="template_id" id="template_id" class="form-control select2" data-placeholder="-- {{trans('backend.choose_evaluation_form')}} --">
                            @if(isset($templates))
                                <option value=""></option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}" {{ $model->template_id == $template->id ? 'selected' : '' }}> {{ $template->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <a href="javscript:void(0)" id="modal_qrcode">{{trans('backend.Qrcode_evaluation_form')}}</a>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{trans('latraining.end_date')}}</label>
                    <input name="rating_end_date" type="text" class="form-control datepicker" placeholder="{{trans('latraining.end_date')}}" autocomplete="off" value="{{ get_date($model->rating_end_date) }}">
                </div>
            </div> --}}

            {{--  Kỳ thi đầu vào  --}}
            {{--  @if ($model->entrance_quiz_id)
            <div class="form-group">
                <label class="form-check-label">
                    {{trans("latraining.first_quiz")}}
                </label>
                <div>
                    <input type="hidden" name="entrance_quiz_id" value="{{ $model->entrance_quiz_id }}">
                    <div class="m-1">{{ $entrance_quiz->name }}</div>
                    <a href="javscript:void(0)" id="modal_qrcode_entrance_quiz">QR Code</a>
                </div>
            </div>
            @endif  --}}

            {{--  Kỳ thi cuối khoá  --}}
            {{-- @if ($model->quiz_id)
            <div class="form-group">
                <label class="form-check-label">
                    {{trans("latraining.final_quiz")}}
                </label>
                <div>
                    <input type="hidden" name="quiz_id" value="{{ $model->quiz_id }}">
                    <input type="text" class="form-control" value="{{ $end_quiz->name }}" readonly>
                    //<a href="javscript:void(0)" id="modal_qrcode_quiz">QR Code</a>
                </div>
            </div>
            @endif  --}}

            {{--  Công tác tố chức giảng dạy  --}}
            <div class="form-group">
                <label class="form-check-label">
                    {{trans("latraining.teaching_organization")}}
                </label>
                <div>
                    <select name="template_rating_teacher_id" id="template_rating_teacher_id" class="form-control select2" data-placeholder="-- {{trans('backend.choose_evaluation_form')}} --">
                        @if (isset($offline_teaching_organization_template))
                            <option value="{{ $offline_teaching_organization_template->id }}"> {{ $offline_teaching_organization_template->name }}</option>
                        @elseif(isset($templates_rating_teacher))
                            <option value=""></option>
                            @foreach($templates_rating_teacher as $template)
                                <option value="{{ $template->id }}" {{ $model->template_rating_teacher_id == $template->id ? 'selected' : '' }}> {{ $template->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-check-label">
                    <input type="checkbox" class="" name="commit" id="commit" value="{{ $model->commit }}"  @if($model->commit == 1) checked @endif >
                    {{trans('latraining.commitment_training')}}
                </label>

                <div class="pb-1" >
                    <input  @if(!$model->commit) style="display: none;" @endif class="form-control datepicker"
                            name="commit_date" autocomplete="off" value="{{ get_date($model->commit_date) }}"
                            aria-describedby="helpId" placeholder="{{trans('latraining.enter_commit_date')}}">
                </div>
                <div>
                    <input type="text" @if(!$model->commit) style="display: none;" @endif step="0.1" class="form-control
                    is-number" value="{{ $model->coefficient }}" name="coefficient" id="" placeholder="{{trans('latraining.enter_coefficient')}}">
                </div>
            </div>

            <div class="form-group">
                <label for="unit_id">{{trans("latraining.leader_registed")}}</label>
                <select name="unit_id[]" id="unit_id" class="form-control select2" data-placeholder="-- {{ trans('latraining.choose_unit') }} --" multiple >
                    <option value=""></option>
                    @foreach($units as $item)
                        <option value="{{ $item->id }}" {{ isset($unit) && in_array($item->id, $unit) ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-check-label">
                    <input type="checkbox" class="" name="enter_student_cost" id="enter_student_cost" value="{{ $model->enter_student_cost }}" @if($model->enter_student_cost == 1) checked @endif >
                    {{ trans('latraining.students_enter_costs') }}
                </label>
            </div>
            <div class="form-group">
                <label>{{trans("latraining.training_calendar_color")}}</label>
                <div class="">
                    <input type="color" name="color" id="color" class="avatar avatar-40 shadow-sm change_color" value="{{ !is_null($model->color) ? $model->color : 'fff' }}"> {{trans("latraining.background")}}
                    <input type="checkbox" name="i_text" id="i_text" class="ml-1" value="{{ $model->i_text }}" @if($model->i_text == 1) checked @endif> <label for="i_text"> {{trans("latraining.italic")}}</label>
                    <input type="checkbox" name="b_text" id="b_text" class="ml-1" value="{{ $model->b_text }}" @if($model->b_text == 1) checked @endif> <label for="b_text"> {{trans("latraining.bold")}}</label>
                </div>
            </div>
            <div class="form-group">
                <label>{{trans("latraining.training_link")}}</label>
                <div class="">
                    <input type="text" name="link_go_course" id="link_go_course" class="form-control" value="{{ $model->link_go_course }}">
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('styles/ckeditor/ckeditor.js') }}"></script>
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

<div class="modal fade" id="modal-qrcode-quiz" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">QR code thi cuối khoá</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    @if ($qrcode_end_quiz)
                        <div id="qrcode" >
                            {!! QrCode::size(300)->generate($qrcode_end_quiz); !!}
                            <p>{{trans('latraining.scan_code')}}</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-teacher" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">{{trans('lacategory.list_teacher')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="tDefault table table-hover">
                    <thead>
                        <tr>
                            <th data-formatter="note_formatter">TNT</th>
                            <th data-sortable="true" data-field="teacher_code">{{ trans('laother.teacher_code') }}</th>
                            <th data-sortable="true" data-field="teacher_name">{{ trans('latraining.fullname') }}</th>
                            <th data-field="teacher_email">{{ trans('latraining.email') }}</th>
                            <th data-field="teacher_phone">{{ trans('latraining.phone') }}</th>
                            <th data-field="note" data-formatter="note_formatter">{{ trans('latraining.note') }}</th>
                        </tr>
                    </thead>
                    <tbody class="body_table_teacher">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    var template_rating_teacher_id = '{{ $model->template_rating_teacher_id }}'
    if(template_rating_teacher_id) {
        $('#template_rating_teacher_id').prop('disabled', true);
    }
    function deleteDocument(key) {
        var delete_document = document.getElementById("hidden_document_"+key);
        var title_document = document.getElementById("title_document_"+key);
        delete_document.remove();
        title_document.remove();
    }

    $('#modal_qrcode_quiz').on('click',function () {
        $("#modal-qrcode-quiz").modal();
    });

    $('#modal_qrcode').on('click',function () {
        $("#modal-qrcode").modal();
    });
    $('#print_qrcode').on("click", function () {

        $('#qrcode').printThis({header: null,printContainer: true,importStyle: true});
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

    $('#enter_student_cost').on('click', function () {
        if ($(this).is(':checked')){
            $('#enter_student_cost').val(1);
        }else {
            $('#enter_student_cost').val(0);
        }
    });

    // SELECT ĐƠN VỊ TỔ CHỨC/PHỐI HỢP
    var training_partner_type = '<?php echo $model->training_unit_type ?>';
    var responsable_type = '<?php echo $model->training_partner_type ?>';
    if(training_partner_type == 0) {
        $('#unit_type_training_partner').css('display','block');
        $('#training_partner').css('display','none');
        $("#select_training_partner").prop('disabled', true);
        $("#choose_unit_training_partner").prop('disabled', false);
    } else {
        $('#unit_type_training_partner').css('display','none');
        $('#training_partner').css('display','block');
        $("#choose_unit_training_partner option").val();
        $("#select_training_partner").prop('disabled', false);
    }

    if(responsable_type == 0) {
        $('#unit_type_responsable').css('display','block');
        $('#responsable').css('display','none');
        $("#select_responsable").prop('disabled', true);
        $("#choose_unit_responsable").prop('disabled', false);
    } else {
        $('#unit_type_responsable').css('display','none');
        $('#responsable').css('display','block');
        $("#choose_unit_responsable").prop('disabled', true);
        $("#select_responsable").prop('disabled', false);
    }

    // ĐƠN VỊ TỔ CHỨC
    $('#type_training_partner').on('change', function() {
        if ( $("#type_training_partner").val() == 0 ) {
            $('#unit_type_training_partner').css('display','block');
            $('#training_partner').css('display','none');
            $("#select_training_partner").prop('disabled', true);
            $("#choose_unit_training_partner").prop('disabled', false);
        } else {
            $('#unit_type_training_partner').css('display','none');
            $('#training_partner').css('display','block');
            $("#choose_unit_training_partner").prop('disabled', true);
            $("#select_training_partner").prop('disabled', false);
        }
    })

    // ĐƠN VỊ PHỐI HỢP
    $('#type_responsable').on('change', function() {
        if ( $("#type_responsable").val() == 0 ) {
            $('#unit_type_responsable').css('display','block');
            $('#responsable').css('display','none');
            $("#select_responsable").prop('disabled', true);
            $("#choose_unit_responsable").prop('disabled', false);
        } else {
            $('#unit_type_responsable').css('display','none');
            $('#responsable').css('display','block');
            $("#choose_unit_responsable").prop('disabled', true);
            $("#select_responsable").prop('disabled', false);
        }
    })

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

    // MODAL GIẢNG VIÊN
    function openModalTeacher(id) {
        var modelLockCourse = '{{ $model ? $model->lock_course : 0 }}';
        $.ajax({
            url: '{{ route('module.offline.ajax_get_teacher') }}',
            type: 'post',
            data: {
                id: id,
            },
        }).done(function(data) {
            var html = '';
            data.forEach(element => {
                html += `<tr>
                            <th><input type="checkbox" id="check_tnt_`+ element.course_teacher_id +`" class="cursor_pointer" onClick="checkTNTHandle(`+ element.course_teacher_id +`)" `+ (element.tnt == 1 && 'checked') +`></th>
                            <th>`+ element.code +`</th>
                            <th>`+ element.name +`</th>
                            <th>`+ element.email +`</th>
                            <th>`+ element.phone +`</th>
                            <th><textarea class="form-control w-100" `+ (modelLockCourse == 1 && 'readonly') +` id="note_`+ element.course_teacher_id +`" rows="2" onblur="saveNoteTeacher(`+ element.course_teacher_id +`)">`+ (element.note ? element.note : '') +`</textarea></th>
                        </tr>`
            });
            $('.body_table_teacher').html(html)
            return false;
        }).fail(function(data) {
            show_message(
                'Lỗi hệ thống',
                'error'
            );
            return false;
        });
        $('#modal-teacher').modal();
    }

    // LƯU GHI CHÚ CHO GIẢNG VIÊN
    function saveNoteTeacher(id) {
        var note = $('#note_' + id).val();
        $.ajax({
            url: '{{ route('module.offline.ajax_save_teacher_note') }}',
            type: 'post',
            data: {
                id: id,
                note: note,
            },
        }).done(function(data) {
            return false;
        }).fail(function(data) {
            show_message(
                'Lỗi hệ thống',
                'error'
            );
            return false;
        });
        $('#modal-teacher').modal();
    }

    $('#province_id').on('change',function (e) {
        $('#training_location_id').html('');
        var province_code = $('#province_id').val();
        $.ajax({
            url: "{{ route('backend.category.district.filter') }}",
            type: 'get',
            data: {
                province_id: province_code,
            }
        }).done(function(result) {
            if (result && result.length) {
                let html = '';
                html += '<option value="" disabled selected>Chọn Quận huyện</option>'
                $.each(result, function (i, item){
                    html+='<option value='+ item.id +'>'+ item.name +'</option>';
                });
                $('#district_id').html(html);
            } else {
                $('#district_id').html('<option></option>')
            }
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

    $('#district_id').on('change',function (e) {
        var province = $('#province_id').val();
        var district = $('#district_id').val();
        loadTranginingLocation(province, district)
    });

    function loadTranginingLocation(province, district){
        $.ajax({
            type: "GET",
            url: $('select[name=training_location_id]').data('url'),
            dataType: 'json',
            data: {
                province_id: province,
                district_id: district
            },
            success: function (result) {
                if (result && result.length) {
                    let html = '';
                    html += '<option value="" disabled selected>Chọn Quận huyện</option>'
                    $.each(result, function (i, item){
                        html += '<option value='+ item.id +'>'+ item.name +'</option>';
                    });
                    $('#training_location_id').html(html);
                } else {
                    $('#training_location_id').html('<option></option>')
                }
            }
        });
    }

    function checkTNTHandle(courseTeacherId) {
        var check = 0;
        if($('#check_tnt_' + courseTeacherId).is(":checked")) {
            check = 1;
        }
        $.ajax({
            type: "POST",
            url: "{{ route('module.offline.ajax_save_teacher_tnt') }}",
            dataType: 'json',
            data: {
                courseTeacherId: courseTeacherId,
                check: check
            },
            success: function (result) {
                return false;
            }
        });
    }
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
