<form method="post" action="{{ route('module.course_plan.save', ['course_type' => $course_type]) }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
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
                    <select name="subject_id" id="subject_id" class="form-control load-subject" data-level-subject="{{ $model->level_subject_id }}" data-training-program="{{ $model->training_program_id }}" data-placeholder="-- {{ trans('backend.document') }} --" required>
                        @if(isset($subject))
                            <option value="{{ $subject->id }}" selected> {{ $subject->name }} </option>
                        @endif
                    </select>
                </div>
            </div>
            {{--<div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.course_code')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input name="code" id="code" type="text" class="form-control" value="{{ $model->code }}" required readonly>
                </div>
            </div>--}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.class_name') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
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

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.max_student') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input name="max_student" type="text" class="form-control" value="{{ $model->max_student }}" required>
                </div>
            </div>

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

            {{-- KHU VỰC ĐÀO TẠO --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.training_area') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <select name="training_area_id[]" id="training_area_id" class="form-control select2" data-level="3" multiple data-placeholder="-- {{ trans('latraining.training_area') }} --" required>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" {{ !empty($training_area) && in_array($area->id, $training_area) ? 'selected' : '' }}>{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.training_location')}} </label>
                </div>
                <div class="form-group col-sm-9 m-0">
                    <div class="row">
                        <div class="col-md-4">
                            <select name="province" data-url="{{route('module.offline.filter.location')}}" class="select2 form-control">
                                <option value="">{{trans('backend.choose_province')}}</option>
                                @if($province)
                                    @foreach($province as $item)
                                        <option value="{{$item->id}}" @if($item->id==$model->training_location_province) selected @endif> {{$item->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="district" class="select2 form-control">
                                <option value="">{{trans('backend.choose_district')}}</option>
                                @if($district)
                                    @foreach($district as $item)
                                        <option value="{{$item->id}}" @if($item->id==$model->training_location_district) selected @endif> {{$item->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="training_location_id" data-placeholder="{{ trans('latraining.choose_training_location') }}" class="form-control select2" data-url="{{route('module.offline.filter.traininglocation')}}">
                                <option value="">{{ trans('latraining.choose_training_location') }}</option>
                                @if($training_location)
                                    @foreach($training_location as $item)
                                        <option value="{{$item->id}}" @if($item->id==$model->training_location_id) selected @endif> {{$item->name}}</option>
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
                    <label>{{trans('backend.training_form')}}<span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <select name="training_form_id" id="training_form_id" class="form-control select2" data-placeholder="{{trans('backend.training_form')}}" required>
                        <option value=""></option>
                        @if($training_forms_offline)
                            @foreach($training_forms_offline as $training_form)
                                <option value="{{ $training_form->id }}" {{ $model->training_form_id == $training_form->id ?
                                'selected' : '' }}>{{ $training_form->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            {{-- <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.training_units')}}</label>
                </div>
                <div class="col-md-9">
                    <input type="text" placeholder="{{trans('backend.training_units')}}" name="training_unit" class="form-control" value="{{$model->training_unit}}" />
                </div>
            </div> --}}

            {{-- ĐƠN VỊ TỔ CHỨC --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.organizational_units') }}</label>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-4 col-md-3 pr-0">
                            <select name="training_unit_type" id="type_training_partner" class="type_training_partner select2">
                                <option value="" disabled selected>{{trans('latraining.unit') }}</option>
                                <option value="0" {{ $model->training_unit_type == 0 ? 'selected' : '' }}>
                                    {{ trans('latraining.internal') }}
                                </option>
                                <option value="1" {{ $model->training_unit_type == 1 ? 'selected' : '' }}>
                                    {{ trans('latraining.outside') }}
                                </option>
                            </select>
                        </div>
                        <div class="col-8 col-md-9">
                            <div id="unit_type_training_partner">
                                <select name="training_unit[]" id="choose_unit_training_partner" class="form-control select2" data-placeholder="{{ trans('lamenu.unit') }}" multiple>
                                    <option value=""></option>
                                    @foreach($units as $item)
                                        <option value="{{ $item->id }}" {{ !empty($training_unit) && in_array($item->id, $training_unit) ? 'selected' : '' }}>{{$item->code}} - {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="training_partner">
                                <select name="training_unit[]" id="select_training_partner" class="form-control select2" data-placeholder="{{ trans('lamenu.unit') }}" multiple>
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
                                <option value="0" {{ $model->training_partner_type == 0 ? 'selected' : '' }}>
                                    {{ trans('latraining.internal') }}
                                </option>
                                <option value="1" {{ $model->training_partner_type == 1 ? 'selected' : '' }}>
                                    {{ trans('latraining.outside') }}
                                </option>
                            </select>
                        </div>
                        <div class="col-8 col-md-9">
                            <div id="unit_type_responsable">
                                <select name="training_partner_id[]" id="choose_unit_responsable" class="form-control select2" data-placeholder="{{ trans('lamenu.unit') }}" multiple>
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

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.instructor_type') }}</label>
                </div>
                <div class="col-md-9">
                    <select name="teacher_type_id" id="teacher_type_id" class="form-control load-teacher-type" data-placeholder="-- {{ trans('latraining.instructor_type') }} --">
                        @if(isset($teacher_type))
                            <option value="{{ $teacher_type->id }}" selected> {{ $teacher_type->name }} </option>
                        @endif
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.training_time') }}</label><span class="text-danger"> * </span>
                </div>
                <div class="col-md-9">
                    <span><input name="start_date" type="text" placeholder="{{trans('laother.choose_start_date')}}" class="datepicker form-control
                    d-inline-block w-25" autocomplete="off" value="{{ get_date($model->start_date) }}"></span>

                    <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                    <span><input name="end_date" type="text" placeholder='{{trans("backend.choose_end_date")}}' class="datepicker form-control
                    d-inline-block w-25" autocomplete="off" value="{{ get_date($model->end_date) }}"></span>
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
                    <label>{{ trans('latraining.course_for') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <select name="course_employee" id="course_employee" class="form-control" data-placeholder="-- {{ trans('latraining.course_for') }} --" required>
                        <option value="0" selected> {{trans('latraining.choose')}}</option>
                        <option {{ $model->course_employee =='1' ? 'selected' : '' }} value="1"> {{ trans('latraining.newly_recruited_staff') }}</option>
                        <option {{ $model->course_employee =='2' ? 'selected' : '' }} value="2"> {{ trans('latraining.existing_employees') }}</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.course_action') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <select name="course_action" id="course_action" class="form-control" data-placeholder="-- {{ trans('latraining.course_action') }} --" required>
                        <option value="0" selected> {{trans('latraining.choose')}}</option>
                        <option {{ $model->course_action =='1' ? 'selected' : '' }} value="1"> {{ trans('latraining.plan') }}</option>
                        <option {{ $model->course_action =='2' ? 'selected' : '' }}value="2"> {{ trans('latraining.incurred') }}</option>
                    </select>
                </div>
            </div>

            {{-- TÀI LIỆU --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.document')}}</label>
                </div>
                <div class="col-md-9">
                    <div class="input-group hdtuto control-group lst increment">
                        {{-- <a href="javascript:void(0)" id="select-document">{{trans('backend.choose_document')}}</a>
                        <div id="document-review">
                            @if($model->document)
                                {{ basename($model->document) }}
                            @endif
                        </div>
                        <input name="document" id="document-select" type="text" class="d-none" value="{{ $model->document }}"> --}}
                        <input type="file" name="document[]" class="myfrm form-control w-100 mb-1" multiple style="height:auto;">
                        @if (!empty($documents))
                            @foreach ($documents as $key => $document)
                                <input type="hidden" name="hidden_document[]" class="form-control w-100" id="hidden_document_{{$key}}" value="{{ $document }}">
                                <ul class="title_document" id="title_document_{{$key}}" class="m-2 w-100">
                                    <li class="name_document">{{ $document }} </li>
                                    <li onclick="deleteDocument({{ $key }})" class="delete_document">x</li>
                                </ul>
                            @endforeach
                        @endif
                    </div>
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
                    <textarea name="content" id="content" class="form-control">{!! $model->content  !!}</textarea>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row row-acts-btn">
                <div class="col-sm-12">
                    <div class="btn-group act-btns">
                        @if ($model->status_convert != 1)
                        @can(['course-plan-edit','course-plan-create'])
                            <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                        @endcan
                        @endif
                        <a href="{{ route('module.course_plan.management') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                    </div>
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
                <label>{{ trans('lasuggest_plan.timer') }}</label>
                <div class="input-group">
                    <input name="course_time" type="text" class="form-control" value="{{ if_empty($course_time, '') }}">
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
                <label>{{trans('backend.lesson')}} ({{trans('backend.number_lesson')}})</label>
                <input name="num_lesson" type="text" class="form-control is-number" value="{{ $model->num_lesson }}">
            </div>
            <div class="form-group">
                <label>{{trans('backend.certificate')}} </label>
                <div>
                    <select style="width: 65%;" name="cert_code" id="cert_code" class="form-control d-inline-block"  @if($model->has_cert == 0) disabled @endif >
                        @foreach($certificate as $item)
                            <option value="{{ $item->id }}" {{ $model->cert_code == $item->id ? 'selected' : '' }}>{{ $item->code }}</option>
                        @endforeach
                    </select>
                    <input name="has_cert" id="has_cert" type="checkbox" class="form-custom" value="{{ $model->has_cert?$model->has_cert : 0}}" {{ $model->has_cert == 1 ? 'checked' : '' }}>{{trans("backend.enable")}}
                </div>
            </div>
            <div class="form-group">
                <label>{{trans('backend.evaluate_training_effectiveness')}}</label>
                <div>
                    <select name="action_plan" id="action_plan" class="form-control " data-placeholder="-- Đánh giá hiệu quả đào tạo --">
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
                    <input type="number" placeholder="Thời gian thực hiện" name="plan_app_day" value="{{$model->plan_app_day}}" class="form-control" >
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
            </div>--}}
            <div class="form-group">
                <label class="form-check-label">
                    {{trans('backend.exam')}}
                </label>
                <div>
                    <select name="quiz_id" class="form-control select2 load-quiz" data-course="{{$model->id}}" data-placeholder="{{trans('backend.choose_quiz')}}">
                        <option value="">{{trans('backend.choose_quiz')}}</option>
                        @foreach($quizs as $item)
                            <option {{ $model->quiz_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name
                            }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-check-label">
                    <input type="checkbox" class="" name="commit" id="commit" value="{{ $model->commit }}"  @if($model->commit == 1) checked @endif >
                    {{trans('backend.commitment_training')}}
                </label>

                <div class="pb-1" >
                    <input  @if(!$model->commit) style="display: none;"@endif class="form-control datepicker"
                            name="commit_date" autocomplete="off" value="{{ get_date($model->commit_date) }}"
                            aria-describedby="helpId" placeholder="Nhập ngày cam kết">
                </div>
                <div>
                    <input type="text" @if(!$model->commit) style="display: none;"@endif step="0.1" class="form-control
                    is-number" value="{{ $model->coefficient }}" name="coefficient" id="" placeholder="Nhập hệ số K">
                </div>
            </div>

            {{-- LOẠI ĐƠN VỊ --}}
            {{-- <div class="form-group">
                <label for="unit_type">Loại đơn vị</label>
                <select name="unit_type" id="unit_type" class="form-control select2" data-placeholder="-- Loại đơn vị --">
                    <option value=""></option>
                    @foreach($units_type as $unit_type)
                        <option value="{{ $unit_type->id }}" {{ $unit_type->id == $model->unit_type ? 'selected' : '' }}>{{ $unit_type->name }}</option>
                    @endforeach
                </select>
            </div> --}}

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
    <script src="{{ asset('styles/ckeditor/ckeditor.js') }}"></script>
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
<script>
    function deleteDocument(key) {
        var delete_document = document.getElementById("hidden_document_"+key);
        var title_document = document.getElementById("title_document_"+key);
        delete_document.remove();
        title_document.remove();
    }

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
    })

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

    $('#title_join_id').on('change',function() {
        var get_value = $('#title_join_id').val();
        if (get_value.includes("0")) {
            $('#title_join_id').removeAttr('multiple');
            $('#title_join_id').val(0)
        } else {
            $('#title_join_id').attr('multiple','multiple');
        }
    });

    $('#title_recommend_id').on('change',function() {
        var get_value = $('#title_recommend_id').val();
        if (get_value.includes("0")) {
            $('#title_recommend_id').removeAttr('multiple');
            $('#title_recommend_id').val(0)
        } else {
            $('#title_recommend_id').attr('multiple','multiple');
        }
    });

    $('#commit').on('change', function() {
        if($(this).is(':checked')) {
            $(this).val(1);
            $("input[name=commit_date]").prop('disabled',false).fadeIn();
            $("input[name=coefficient]").prop('disabled',false).fadeIn();
        }
        else {
            $(this).val(0);
            $("input[name=commit_date]").fadeOut();
            $("input[name=commit_date]").val('');
            // $("input[name=commit_date]").prop('disabled',true).fadeOut();

            $("input[name=coefficient]").fadeOut();
            $("input[name=coefficient]").val('');
            // $("input[name=coefficient]").prop('disabled',true).fadeOut();
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
