<div class="modal fade modal-add-activity" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('module.offline.activity.save', [$course->id, 'activity' => 3]) }}" method="post" class="form-ajax">
                <input type="hidden" name="id" value="{{ $model->id }}">
                <input type="hidden" name="subject_id" value="{{ $model->subject_id }}">

                <div class="modal-header">
                    <h4 class="modal-title">{{trans('backend.activiti')}}: Zoom</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
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
                            <label for="name">{{trans('latraining.classroom')}}</label>
                        </div>

                        <div class="col-md-9">
                            <select name="class_id" id="class_id" class="form-control select2" data-placeholder="{{ trans('latraining.class_name') }}">
                                <option value=""></option>
                                @if ($list_class)
                                    @foreach ($list_class as $class)
                                        <option value="{{ $class->id }}" {{ $module->class_id == $class->id ? 'selected' : '' }}> {{ $class->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="name">{{trans('latraining.schedule')}}</label>
                        </div>

                        <div class="col-md-9">
                            <select name="schedule_id" id="schedule_id" class="form-control select2" data-placeholder="{{ trans('latraining.schedule') }}">
                                <option value=""></option>
                                @if ($schedule)
                                    <option value={{ $schedule->id }} selected > 
                                        {{ get_date($schedule->start_time, 'H:i') .' => '. get_date($schedule->end_time, 'H:i') .' - '. get_date($schedule->lesson_date) }}
                                    </option>
                                @endif
                            </select>
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="description">Thời gian bắt đầu</label>
                        </div>
                        <div class="col-md-9">
                            <span>
                                <input name="start_time" type="text" class="datetimepicker form-control d-inline-block w-25" placeholder="Thời gian bắt đầu" autocomplete="off" value="{{ get_date($model->setting_start_date, 'd/m/Y H:i:s') }}">
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="description">Thời lượng (phút)</label>
                        </div>
                        <div class="col-md-9">
                            <input name="duration" type="text" class="form-control d-inline-block w-25" placeholder="Thời lượng" autocomplete="off" value="">
                        </div>
                    </div> --}}
                </div>

                <div class="modal-footer">
                    @if($course->lock_course == 0)
                    <button type="submit" class="btn" id="add-activity"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                    @endif
                    <button type="button" id="closed" class="btn" data-dismiss="modal"><i class="fa fa-times-circle"></i> {{ trans('backend.close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#class_id').on('change', function(){
        var class_id = $("#class_id option:selected").val();

        $.ajax({
            url: "{{ route('module.offline.activity.loaddata', ['course_id' => $course->id, 'func' => 'loadSchedule']) }}",
            type: 'post',
            data: {
                class_id: class_id
            },
        }).done(function(data) {
            if(data.results) {
                let html = '<option value=""></option>';
                $.each(data.results, function (i, item){
                    html+='<option value='+ item.id +'>'+ item.start_time +' => '+ item.end_time +' - '+ item.lesson_date +'</option>';
                });
                $('#schedule_id').html(html);
            }

            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });

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
    $('.datetimepicker').datetimepicker({
        locale:'vi',
        format: 'DD/MM/YYYY HH:mm:ss'
    });
</script>
