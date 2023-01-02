<div class="modal fade modal-add-activity" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('module.online.activity.save', [$course->id, 1, 'type' => $type]) }}" method="post" class="form-ajax">
                <input type="hidden" name="id" value="{{ $model->id }}">
                <input type="hidden" name="subject_id" value="{{ $model->subject_id }}">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('latraining.activiti_scorm') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body" style="width:100%; max-height: 500px; overflow: scroll; overflow-x: hidden;">
                    <h3 class="text-primary">{{ trans('latraining.general_setting') }}</h3>

                    <div class="form-group row">
                        <div class="col-md-3 pl-5">
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
                        <div class="col-md-3 pl-5">
                            <label for="name">{{ trans('latraining.activiti_name') }} <span class="text-danger"> * </span></label>
                        </div>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="name" id="name" value="{{ $model->name }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 pl-5">
                            <label for="description">{{ trans('latraining.description') }}</label>
                        </div>

                        <div class="col-md-9">
                            <textarea name="description" id="description" class="form-control" rows="5">{{ $module->description }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 pl-5">
                            <label for="path">{{ trans('latraining.file') }} <span class="text-danger"> * </span></label>
                        </div>

                        <div class="col-md-9">
                            <a href="javascript:void(0)" id="select-file-activity">{{ trans('latraining.choose_file') }}</a>
                            <br><em id="path-name">{{ $module->warehouse->file_name ?? '' }}</em>
                            <input type="hidden" name="path" id="path" value="{{ $module->path }}">
                            <p class="text-danger m-0">Lưu ý: file hỗ trợ định dạng zip.</p>
                        </div>
                    </div>

                    <h3 class="text-primary">{{ trans('latraining.attemps') }}</h3>
                    <div class="form-group row">
                        <div class="col-md-3 pl-5">
                            <label for="max_attempt">{{ trans('latraining.number_of_attempts') }}</label>
                        </div>

                        <div class="col-md-9">
                            <select name="max_attempt" id="max_attempt" class="form-control">
                                <option value="0">{{ trans('latraining.unlimited') }}</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" @if($module->max_attempt == $i) selected @endif>{{ trans('latraining.times', ['i' => $i]) }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3  pl-5">
                            <label for="new_attempt_required">{{ trans('latraining.force_new_attempt') }}</label>
                        </div>

                        <div class="col-md-9">
                            <select name="new_attempt_required" id="new_attempt_required" class="form-control">
                                <option value="0">{{ trans('latraining.no') }}</option>
                                <option value="1" @if($module->new_attempt_required == 1) selected @endif>{{ trans('latraining.when_attempt_completed_passed_failed') }}</option>
                                <option value="2" @if($module->new_attempt_required == 2) selected @endif>{{ trans('latraining.always') }}</option>
                            </select>
                        </div>
                    </div>

                    <h3 class="text-primary">{{ trans('latraining.score') }}</h3>
                    <div class="form-group row">
                        <div class="col-md-3  pl-5">
                            <label for="what_grade">{{ trans('latraining.scoring_method') }}</label>
                        </div>

                        <div class="col-md-9">
                            <select name="what_grade" id="what_grade" class="form-control">
                                <option value="1" @if($module->what_grade == 1) selected @endif>{{ trans('latraining.highest') }}</option>
                                <option value="2" @if($module->what_grade == 2) selected @endif>{{ trans('latraining.medium') }}</option>
                                <option value="3" @if($module->what_grade == 3) selected @endif>{{ trans('latraining.first') }}</option>
                                <option value="4" @if($module->what_grade == 4) selected @endif>{{ trans('latraining.end') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3  pl-5">
                            <label for="max_score">{{ trans('latraining.maximum_score') }}</label>
                        </div>

                        <div class="col-md-9">
                            <input type="text" name="max_score" id="max_score" class="form-control" value="{{ $module->max_score ?? 0 }}">
                        </div>
                    </div>

                    <h3 class="text-primary">{{ trans('latraining.completion_conditions',['package'=>'scorm']) }}</h3>
                    <div class="form-group row">
                        <div class="offset-3 pl-3">
                            <div>
                                <label>
                                    <input type="radio" value="1" name="type_result" {{ $module->type_result==1?"checked":"" }}>
                                    Nhận kết quả hoàn thành từ scorm
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input type="radio" value="0" name="type_result" {{ !$module->type_result && $module->type_result==0?"checked":"" }}>
                                    Kết quả hoàn thành từ hệ thống (theo các thiết lập sau đây)
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="group-condition-complete-scorm {{$module->type_result==1  ? 'd-md-none':''}}" >
                        <div class="form-group row" >
                            <div class="col-md-3  pl-5">
                                <label for="score_required">{{ trans('latraining.request_to_receive') }}</label>
                            </div>

                            <div class="col-md-9">
                                <input type="checkbox" name="score_required" id="score_required" value="1" @if($module->score_required == 1) checked @endif>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3  pl-5">
                                <label for="min_score_required">{{ trans('latraining.completed_dark_spot') }}</label>
                            </div>

                            <div class="col-md-9">
                                <input type="number" name="min_score_required" id="min_score_required" class="form-control" value="{{ $module->min_score_required ?? 0 }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3  pl-5">
                                <label for="name">{{ trans('latraining.status') }}</label>
                            </div>

                            <div class="col-md-9">
                                {{ trans('latraining.passed') }} <input type="checkbox" name="status_passed" value="1" @if($module->status_passed == 1) checked @endif>
                                {{ trans('latraining.completed') }} <input type="checkbox" name="status_completed" value="1" @if($module->status_completed == 1) checked @endif>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-primary">{{ trans('latraining.access_conditions') }}</h3>
                    @include('online::modal.setting_activity')
                </div>

                <div class="modal-footer">
                    @php
                        $check_history = \Modules\Online\Entities\OnlineCourseActivityHistory::where('course_id', '=', $course->id)->where('course_activity_id', '=', $model->id)->first();
                    @endphp
                    @if(!$check_history || $course->lock_course == 0)
                    <button type="submit" class="btn" id="add-activity"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                    @endif
                    <button type="button" id="closed" class="btn" data-dismiss="modal"><i class="fa fa-times-circle"></i> {{ trans('labutton.close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#select-file-activity").on('click', function () {
        open_filemanager({type: 'scorm'}, function (url, path, name) {
            $("#path-name").html(name);
            $("#path").val(path);
        });
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
    $('input[name="type_result"]').on('change',function(){
       if($(this).val()==1)
           $('.group-condition-complete-scorm').addClass('d-md-none');
           // $('.group-condition-complete-scorm').fadeOut()
        else
           // $('.group-condition-complete-scorm').fadeIn()
            $('.group-condition-complete-scorm').removeClass('d-md-none');
    });
</script>
