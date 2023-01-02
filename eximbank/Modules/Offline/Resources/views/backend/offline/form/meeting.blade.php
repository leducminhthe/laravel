<div class="row">
    <div class="col-md-8">
        <div class="">
            <form action="{{ route('module.offline.meeting.save', ['id' => $course->id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="id" value="{{ $course->id }}">
                <input type="hidden" name="activity_id" value="{{ $activity_id }}">
                <input type="hidden" name="subject_id" value="{{ $subject_id }}">
                <div class="body">
                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-md-offset-3  col-md-3 control-label">
                            <label for="name">{{trans('latraining.meeting_online_by')}}</label>
                        </div>
                        <div class="col-md-6">
                            <label class="pr-2"><input type="radio" name="meeting_type" {{$meetingType==1?"checked":""}} value="1"> Zoom</label>
                            <label><input type="radio" name="meeting_type" value="2" {{$meetingType==2?"checked":""}}> Miscrosoft teams</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-md-offset-3  col-md-3 control-label">
                            <label for="name">{{trans('backend.activiti_name')}}</label>
                        </div>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="name" id="name" value="{{ $meeting->topic }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-md-offset-3 col-md-3 control-label">
                            <label for="description">{{trans('backend.description')}}</label>
                        </div>

                        <div class="col-md-6">
                            <textarea name="description" id="description" class="form-control" rows="4">{{ $meeting->description }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class=" col-md-3 control-label">
                            <label for="description">Thời gian bắt đầu</label>
                        </div>
                        <div class="col-md-6">
                            <span>
                                <input name="start_time" type="text" class="datetimepicker form-control d-inline-block w-40"
                                       placeholder="Thời gian bắt đầu" autocomplete="off" value="{{ get_date($meeting->start_time, 'd/m/Y H:i:s') }}">
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class=" col-md-3 control-label">
                            <label for="description">Thời lượng (phút)</label>
                        </div>
                        <div class="col-md-6">
                            <input name="duration" type="text" class="form-control d-inline-block w-40"
                                   placeholder="Thời lượng" autocomplete="off" value="{{ $meeting->duration }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6"></div>
                    <div class="col-md-6">
                        @if($course->lock_course == 0)
                            <button type="submit" class="btn" id="add-activity"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                        @endif
                    </div>
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
