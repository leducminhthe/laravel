<div class="modal fade" id="modal-schedule" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content p-1">
            <form id="form-schedule" action="{{ route('module.offline.save_schedule', ['id' => $model->id]) }}" method="post" class="form-ajax" data-success="submit_success_schedule">
                <input type="hidden" name="schedule_parent_id" value="{{ $schedule_parent_id }}">
                <div class="row p-1">
                    <div class="col-md-4">
                        <label>{{ trans('latraining.start_time') }}</label>
                        <input type="text" class="form-control" autocomplete="off" value="{{ $start_time }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>{{ trans('latraining.end_time') }}</label>
                        <input type="text" class="form-control" autocomplete="off"  value="{{ $end_time }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>{{ trans('latraining.lesson_date') }}</label>
                        <input name="lesson_date" type="text" class="form-control" autocomplete="off" value="{{ $lesson_date }}" readonly>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-8">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('latraining.lesson_time') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <span><input name="start_time" type="text" required class="form-control time_picker d-inline-block w-25" placeholder="{{ trans('latraining.start_time') }}" autocomplete="off" value=""></span>
                                <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                                <span><input name="end_time" type="text" required class="form-control time_picker d-inline-block w-25" placeholder="{{ trans('latraining.end_time') }}" autocomplete="off" value=""></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('latraining.teacher') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select name="teacher_id" id="teacher_id" class="form-control select2" required>
                                    <option value="">{{ trans('latraining.choose_teacher') }}</option>
                                    @foreach($teachers_offline as $teacher)
                                        <option value="{{ $teacher->id }}"> {{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="cost_teacher">{{ trans('latraining.cost_hour') }}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="cost_teacher" class="form-control is-number" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="teacher_type">{{ trans('latraining.teacher_type') }}</label>
                            </div>
                            <div class="col-md-9">
                                <select name="teacher_type" id="teacher_type" class="form-control select2">
                                    <option value="1"> {{ trans('latraining.main_lecturer') }}</option>
                                    <option value="2"> {{ trans('latraining.tutors') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                        @if($model->lock_course == 0)
                        <button type="submit" class="btn"><i class="fa fa-plus-circle"></i> &nbsp;{{ trans('labutton.add_new') }} </button>
                        @endif
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-md-12" id="schedule">
                    <div class="text-right">
                        @if($model->lock_course == 0)
                        <button id="delete-schedule" class="btn"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endif
                    </div>
                    <p></p>
                    <table class="tDefault table table-hover" id="table-schedule">
                        <thead>
                            <tr>
                                <th data-field="state" data-checkbox="true"></th>
                                <th data-field="day" data-align="center" data-formatter="day_formatter">{{ trans('latraining.session') }}</th>
                                <th data-field="time" data-align="center" data-formatter="time_formatter">{{ trans('latraining.time') }}</th>
                                <th data-field="teacher_name">{{ trans('latraining.teacher') }}</th>
                                <th data-field="cost_teacher">{{ trans('latraining.cost') }}</th>
                                <th data-field="teacher_type">{{ trans('latraining.type') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <script type="text/javascript">
                function day_formatter(value, row, index) {
                    return (index + 1);
                }
                function time_formatter(value, row, index) {
                    return row.start_time +' <i class="fa fa-arrow-right"></i> ' + row.end_time;
                }

                var table_schedule = new LoadBootstrapTable({
                    url: '{{ route('module.offline.get_schedule', ['id' => $model->id]) }}?schedule_parent_id={{ $schedule_parent_id }}',
                    remove_url: '{{ route('module.offline.remove_schedule', ['id' => $model->id]) }}',
                    detete_button: '#delete-schedule',
                    table: "#table-schedule"
                });

                $('.time_picker').datetimepicker({
                    locale:'vi',
                    format: 'HH:mm'
                });

                function submit_success_schedule(form) {
                    $("#form-schedule select[name=teacher_id]").val(null).trigger('change');
                    $("#form-schedule input[name=cost_teacher]").val(null).trigger('change');
                    table_schedule.refresh();
                }
            </script>
        </div>
    </div>
</div>
