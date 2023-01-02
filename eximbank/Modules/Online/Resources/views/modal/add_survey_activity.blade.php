<div class="modal fade modal-add-activity" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('module.online.activity.save', ['id' => $course->id, 'activity' => 8, 'type' => $type]) }}" method="post" class="form-ajax">
                <input type="hidden" name="id" value="{{ $model->id }}">
                <div class="modal-header">
                    <h4 class="modal-title">{{trans('latraining.activiti')}}: {{ trans('lamenu.survey') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="select_lesson_name">{{ trans('latraining.belonging_lesson') }}</label><span style="color:red"> * </span>
                        </div>
                        <div class="col-md-9">
                            @if ($edit == 1)
                                <select class="form-control select2" name="select_lesson_name" id="select_lesson_name" data-placeholder="{{ trans('latraining.choose_lesson') }}">
                                    @foreach ($get_lesson as $lesson)
                                        <option value="{{ $lesson->id }}" {{ $lesson->id == $lessonId ? 'selected' : '' }}>{{ $lesson->lesson_name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" class="form-control" value="{{ $get_lesson->lesson_name }}" readonly>
                                <input type="hidden" name="select_lesson_name" value="{{ $get_lesson->id }}">
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="name">{{trans('latraining.activiti_name')}} <span class="text-danger">*</span></label>
                        </div>

                        <div class="col-md-9">
                            <input type="text" name="name" id="name" class="form-control" value="{{ $model->name }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="description">{{ trans('latraining.description') }}</label>
                        </div>

                        <div class="col-md-9">
                            <textarea name="description" id="description" class="form-control" rows="4">{{ $module->description }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="subject_id">{{ trans('lasurvey.survey_template') }}  <span class="text-danger">*</span></label>
                        </div>

                        <div class="col-md-9">
                            @php
                                /*id của template không phải duy nhất, có thể trùng do sd nhiều lần. Phải check theo hoạt động*/
                                $survey = \Modules\Online\Entities\OnlineSurveyTemplate::where('id', '=', $subject_id)->where('course_id', '=', $course->id)->where('course_activity_id', '=', $model->id)->first();

                                $survey_user = \Modules\Online\Entities\OnlineSurveyUser::where('course_id', '=', $course->id)->where('course_activity_id', '=', $model->id)->first();
                            @endphp
                            @if ($survey_user)
                                <input type="hidden" name="subject_id" value="{{ @$survey->id }}">
                                {{ $survey->name }}
                            @else
                                <select name="subject_id" id="subject_id" class="form-control load-survey-template-course" data-placeholder="-- {{ trans('lasurvey.survey_template') }} --">
                                    @if($survey)
                                        <option value="{{ $survey->id }}">{{ $survey->name }}</option>
                                    @endif
                                </select>
                            @endif
                        </div>
                    </div>

                    @include('online::modal.setting_activity')
                </div>

                <div class="modal-footer">
                    @php
                        $check_history = \Modules\Online\Entities\OnlineCourseActivityHistory::where('course_id', '=', $course->id)->where('course_activity_id', '=', $model->id)->first();
                    @endphp
                    @if(!$check_history || $course->lock_course == 0)
                        <button type="submit" class="btn" id="add-activity"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                    @endif
                    <button type="button" class="btn" data-dismiss="modal" id="closed"><i class="fa fa-times-circle"></i> {{ trans('labutton.close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".load-survey-template-course").select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: '{{ route('module.online.activity.loaddata', ['id' => $course->id, 'func' => 'loadSurveyTemplate']) }}',
            dataType: 'json',
            data: function (params) {

                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    level: $(this).data('level'),
                    parent_id: $(this).data('parent'),
                };

                return query;
            }
        }
    });

    $('#closed').on('click', function () {
       window.location = '';
    });

    CKEDITOR.replace('description', {
        filebrowserImageBrowseUrl: '/filemanager?type=image',
        filebrowserBrowseUrl: '/filemanager?type=file',
        filebrowserUploadUrl : null, //disable upload tab
        filebrowserImageUploadUrl : null, //disable upload tab
        filebrowserFlashUploadUrl : null, //disable upload tab
    });
</script>
