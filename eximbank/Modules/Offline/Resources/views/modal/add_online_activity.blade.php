<div class="modal fade modal-add-activity" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('module.offline.activity.save', [$course->id, 'activity' => 1]) }}" method="post" class="form-ajax">
                <input type="hidden" name="id" value="{{ $model->id }}">

                <div class="modal-header">
                    <h4 class="modal-title">{{trans('latraining.activiti_online')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="name">{{trans('latraining.activiti_name')}} <span class="text-danger">*</span></label>
                        </div>

                        <div class="col-md-9">
                            <input type="text" name="name" id="name" class="form-control" value="{{ $model->name }}" required>
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
                            <label for="subject_id">{{trans('latraining.course')}} <span class="text-danger">*</span></label>
                        </div>

                        <div class="col-md-9">
                            <select name="subject_id" id="subject_id" class="form-control select2" data-placeholder="-- {{ trans('latraining.course') }} --" required>
                                <option value=""></option>
                                @foreach ($online_courses as $online)
                                    <option value="{{ $online->id }}" {{ $model->subject_id == $online->id ? 'selected' : '' }}> {{ $online->name .' ('. $online->code .')' }}</option>
                                @endforeach
                            </select>
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

    $('.select2').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
    });

    $('#closed').on('click', function () {
       window.location = '';
    });

    // CKEDITOR.replace('description', {
    //     filebrowserImageBrowseUrl: '/filemanager?type=image',
    //     filebrowserBrowseUrl: '/filemanager?type=file',
    //     filebrowserUploadUrl : null, //disable upload tab
    //     filebrowserImageUploadUrl : null, //disable upload tab
    //     filebrowserFlashUploadUrl : null, //disable upload tab
    // });
</script>
