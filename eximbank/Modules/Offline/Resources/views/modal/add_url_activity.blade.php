<div class="modal fade modal-add-activity" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('module.offline.activity.save', [$course->id, 3, 'class_id' => $class_id, 'schedule_id' => $schedule_id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="subject_id" value="{{ $model->subject_id }}">
                <input type="hidden" name="id" value="{{ $model->id }}">
                <input type="hidden" name="lesson_id" value="{{ $lesson_id }}">
                
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('latraining.activiti_url') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="name">{{ trans('latraining.activiti_name') }} <span class="text-danger">*</span></label>
                        </div>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="name" id="name" value="{{ $model->name }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="path">{{ trans('latraining.link') }} <span class="text-danger">*</span></label>
                        </div>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="url" id="url" value="{{ $module->url }}">
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
                            <label for="page">Cửa sổ trang mới</label>
                        </div>

                        <div class="col-md-9">
                            <input type="checkbox" name="page" class="cursor_pointer" id="page" {{ $module->page == 1 ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    @php
                        $check_history = \Modules\Offline\Entities\OfflineCourseActivityHistory::where('course_id', '=', $course->id)->where('course_activity_id', '=', $model->id)->first();
                    @endphp
                    @if(!$check_history || $course->lock_course == 0)
                        <button type="submit" class="btn" id="add-activity"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                    @endif

                    <button type="button" id="closed" class="btn" data-dismiss="modal"><i class="fa fa-times-circle"></i> {{ trans('labutton.close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
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
