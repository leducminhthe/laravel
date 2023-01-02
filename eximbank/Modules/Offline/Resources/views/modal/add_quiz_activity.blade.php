<div class="modal fade modal-add-activity" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('module.offline.activity.save', [$course->id, 7, 'class_id' => $class_id, 'schedule_id' => $schedule_id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="id" value="{{ $model->id }}">
                <input type="hidden" name="lesson_id" value="{{ $lesson_id }}">

                <div class="modal-header">
                    <h4 class="modal-title">{{trans('latraining.activiti_quiz')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
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
                            <label for="subject_id">{{trans('latraining.quiz')}} <span class="text-danger">*</span></label>
                        </div>

                        <div class="col-md-9">
                            @php
                                $quiz_activity = \Modules\Offline\Entities\OfflineActivityQuiz::find($model->subject_id, ['quiz_id']);
                                $quiz = \Modules\Quiz\Entities\Quiz::where('id', '=', $quiz_activity->quiz_id)->first();
                                $check_result = \Modules\Quiz\Entities\QuizResult::where('quiz_id', $quiz->id)->exists();
                            @endphp
                            @if ($check_result)
                                <input type="text" class="form-control" value="{{ $quiz->name }}" readonly>
                                <input type="hidden" name="subject_id" id="" value="{{ $quiz->id }}">
                            @else
                                <select name="subject_id" id="subject_id" class="form-control load-quiz-course" data-placeholder="-- {{ trans('latraining.choose_quiz') }} --" readonly>
                                    @if($quiz)
                                        <option value="{{ $quiz->id }}">{{ $quiz->name }}</option>
                                    @endif
                                </select>
                            @endif
                            <p class="description"><a target="_blank" href="{{ route('module.offline.quiz.create', ['course_id' => $course->id, 'quiz_type_by_offline' => 'activity_quiz_id']) }}" class="form-text text-info">{{ trans('latraining.add_new_quiz') }}</a></p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    @if($course->lock_course == 0)
                        <button type="submit" class="btn" id="add-activity"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                    @endif
                    <button type="button" class="btn" data-dismiss="modal" id="closed"><i class="fa fa-times-circle"></i> {{ trans('labutton.close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".load-quiz-course").select2({
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
            url: '{{ route('module.offline.activity.loaddata', ['course_id' => $course->id, 'func' => 'loadQuiz']) }}',
            dataType: 'json',
            data: function (params) {

                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    level: $(this).data('level'),
                    parent_id: $(this).data('parent'),
                    schedule_id: {{ $schedule_id }},
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
