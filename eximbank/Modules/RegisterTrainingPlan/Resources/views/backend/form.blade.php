@extends('layouts.backend')

@section('page_title', $page_title)

@section('header')
    <link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
    <script src="{{asset('styles/vendor/jqueryplugin/printThis.js')}}"></script>
    <style>
        table tbody th {
            font-weight: normal !important;
        }
    </style>
@endsection

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.register_training_plan'),
                'url' => route('module.register_training_plan.management')
            ],
            [
                'name' => $page_title,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<div role="main">
    <div class="tPanel">
        <div class="tab-content">
            <form method="post" action="{{ route('module.register_training_plan.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
                <input type="hidden" name="id" value="{{ $model->id }}">
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{trans('latraining.training_program')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select name="training_program_id" id="training_program_id" class="form-control load-training-program" data-notcode="1" data-placeholder="-- {{trans('latraining.training_program')}} --" required>
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
                                <select name="subject_id" id="subject_id" class="form-control load-subject" data-notcode="1" data-level-subject="{{ $model->level_subject_id }}" data-training-program="{{ $model->training_program_id }}" data-placeholder="-- {{ trans('latraining.subject') }} --" required>
                                    @if(isset($subject))
                                        <option value="{{ $subject->id }}" selected> {{ $subject->name }} </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('latraining.course_name') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
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

                        {{-- LOẠI HÌNH ĐÀO TẠO --}}
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{trans('backend.training_form')}}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select name="training_form_id" id="training_form_id" class="form-control select2" data-placeholder="{{trans('backend.training_form')}}" required>
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
                        {{-- THÊM GIẢNG VIÊN --}}
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>
                                    {{trans('latraining.teacher')}}
                                </label>
                            </div>
                            <div class="col-md-9">
                                <select name="teacher_id[]" id="teacher_id" class="form-control load-user-company" data-placeholder="-- {{trans('latraining.teacher')}} --" multiple>
                                    @if (isset($teachers))
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->user_id }}" selected >{{ $teacher->full_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="target">{{trans('backend.target')}}</label>
                            </div>
                            <div class="col-md-9">
                                <textarea name="target" id="target" rows="4" class="form-control">{{ $model->target }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{trans('latraining.content')}}</label>
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
                                    @if ($model->send != 1)
                                        <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                                    @endif
                                    <a href="{{ route('module.register_training_plan.management') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('app.training_form') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="course_type" id="course_type" class="form-control" data-placeholder="-- {{ trans('app.training_form') }} --" required>
                                    <option {{ $model->course_type =='1' ? 'selected' : '' }} value="1"> {{ trans('latraining.online') }}</option>
                                    <option {{ $model->course_type =='2' ? 'selected' : '' }} value="2"> {{ trans('latraining.offline') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('lasuggest_plan.timer') }}</label>
                            <div class="input-group">
                                <input name="course_time" type="text" class="form-control" value="{{ if_empty($course_time, '') }}">
                                <span class="input-group-addon">
                                    <select name="course_time_unit" id="course_time_unit" class="form-control">
                                        <option value="">Chọn</option>
                                        <option value="day" {{ $course_time_unit == 'day' ? 'selected' : '' }}>{{trans('latraining.date')}}</option>
                                        <option value="session" {{ $course_time_unit == 'session' ? 'selected' : '' }}>{{trans('backend.session')}}</option>
                                        <option value="hour" {{ $course_time_unit == 'hour' ? 'selected' : '' }}>{{trans('backend.hour')}}</option>
                                    </select>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('latraining.course_for') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="course_employee" id="course_employee" class="form-control" data-placeholder="-- {{ trans('latraining.course_for') }} --" required>
                                    <option value="0" selected> {{trans('latraining.choose')}}</option>
                                    <option {{ $model->course_employee =='1' ? 'selected' : '' }} value="1"> {{ trans('latraining.newly_recruited_staff') }}</option>
                                    <option {{ $model->course_employee =='2' ? 'selected' : '' }} value="2"> {{ trans('latraining.existing_employees') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('latraining.course_belong_to') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="course_belong_to" id="course_belong_to" class="form-control select2" data-placeholder="-- {{ trans('latraining.course_belong_to') }} --" required>
                                    <option {{ $model->course_belong_to =='1' ? 'selected' : '' }} value="1"> {{ trans('latraining.internal') }}</option>
                                    <option {{ $model->course_belong_to =='2' ? 'selected' : '' }} value="2"> {{ trans('latraining.cross_training') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{trans('latraining.max_student')}}</label>
                            <div class="input-group">
                                <input name="max_student" type="number" class="form-control" value="{{ $model->max_student }}">
                            </div>
                        </div>
                    </div>
                </div>
                <script src="{{ asset('styles/ckeditor/ckeditor.js') }}"></script>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        $('#training_program_id').on('change', function () {
            var training_program_id = $('#training_program_id option:selected').val();
            $("#level_subject_id").empty();
            $("#level_subject_id").data('training-program', training_program_id);
            $('#level_subject_id').trigger('change');

            $("#subject_id").empty();
            $("#subject_id").data('training-program', training_program_id);
            $("#subject_id").data('level-subject', '');
            $('#subject_id').trigger('change');
        });

        $('#level_subject_id').on('change', function () {
            var training_program_id = $('#training_program_id option:selected').val();
            var level_subject_id = $('#level_subject_id option:selected').val();
            $("#subject_id").empty();
            $("#subject_id").data('training-program', training_program_id);
            $("#subject_id").data('level-subject', level_subject_id);
            $('#subject_id').trigger('change');
        });

        $('#subject_id').on('change', function() {
            var subject_name = $('#subject_id option:selected').text();
            $("input[name=name]").val(subject_name);
        });

        CKEDITOR.replace('content', {
            filebrowserImageBrowseUrl: '/filemanager?type=image',
            filebrowserBrowseUrl: '/filemanager?type=file',
            filebrowserUploadUrl : null, //disable upload tab
            filebrowserImageUploadUrl : null, //disable upload tab
            filebrowserFlashUploadUrl : null, //disable upload tab
        });
    </script>
</div>
@stop
