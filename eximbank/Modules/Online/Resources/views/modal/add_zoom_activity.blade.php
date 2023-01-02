<div class="modal fade modal-add-activity" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('module.online.activity.save', ['id' => $course->id, 'activity' => 8, 'type' => $type]) }}" method="post" class="form-ajax">
                <input type="hidden" name="id" value="{{ $model->id }}">
                <input type="hidden" name="subject_id" value="{{ $model->subject_id }}">
                <div class="modal-header">
                    <h4 class="modal-title">{{trans('backend.activiti')}}: {{trans('backend.meeting_zoom')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="select_lesson_name">Thuộc bài học</label><span style="color:red"> * </span>
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
                            <label for="name">{{trans('backend.activiti_name')}}</label>
                        </div>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="name" id="name" value="{{ $model->name }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="description">{{trans('backend.description')}}</label>
                        </div>

                        <div class="col-md-9">
                            <textarea name="description" id="description" class="form-control" rows="4">{{ $module->description }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="description">Thời gian bắt đầu</label>
                        </div>
                        <div class="col-md-9">
                            <span>
                                <input name="start_time" type="text" class="datetimepicker form-control d-inline-block w-25"
                         placeholder="Thời gian bắt đầu" autocomplete="off" value="{{ get_date($model->setting_start_date, 'd/m/Y H:i:s') }}">
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="description">Thời lượng (phút)</label>
                        </div>
                        <div class="col-md-9">
                                <input name="duration" type="text" class="form-control d-inline-block w-25"
                                       placeholder="Thời lượng" autocomplete="off" value="">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    @php
                        $check_history = \Modules\Online\Entities\OnlineCourseActivityHistory::where('course_id', '=', $course->id)->where('course_activity_id', '=', $model->id)->first();
                    @endphp
                    @if(!$check_history || $course->lock_course == 0)
                    <button type="submit" class="btn" id="add-activity"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                    @endif
                    <button type="button" id="closed" class="btn" data-dismiss="modal"><i class="fa fa-times-circle"></i> {{ trans('backend.close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#select-file-activity").on('click', function () {
        open_filemanager({type: 'file'}, function (url, path, name) {
            $("#path-name").html(name);
            $("#path").val(path);
        });
    });

    $('#closed').on('click', function () {
        window.location = '';
    });
    $('.datetimepicker').datetimepicker({
        locale:'vi',
        format: 'DD/MM/YYYY HH:mm:ss'
    });
</script>
