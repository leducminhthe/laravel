<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ trans('latraining.classroom') }}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                
                <form action="" id="frm-class" method="post" class="form-ajax">
                    <input type="hidden" name="class_id" id="class_id" value="">
                </form>
                @if ($classes->count() > 0)
                    @foreach ($classes as $class)
                        @php
                            $user_register = Modules\Offline\Entities\OfflineRegister::whereCourseId($course_id)->where('class_id', $class->id)->count();
                            $schedules = Modules\Offline\Entities\OfflineSchedule::whereCourseId($course_id)->where('class_id', $class->id)->get();
                        @endphp
                        <div class="row mb-2">
                            <div class="col-10 ">
                                {{ $class->name .' ('. $class->code .')' }} <br>
                                {{ trans('latraining.quantity') .': '. $user_register .'/'. $class->students }} <br>
                                {{ trans('latraining.time').': '. get_date($class->start_date) }} <i class="fa fa-arrow-right"></i> {{ get_date($class->end_date) }}

                                <div class="ml-3 mt-2">
                                    @foreach ($schedules as $key => $schedule)
                                        @php
                                            $teacher = App\Models\Categories\TrainingTeacher::find($schedule->teacher_main_id);
                                            $new_teachers = \Modules\Offline\Entities\OfflineNewTeacher::query()
                                            ->select(['teacher.name', 'teacher.code'])
                                            ->from('el_offline_new_teacher as new_teacher')
                                            ->join('el_training_teacher as teacher', 'teacher.id', '=', 'new_teacher.new_teacher_id')
                                            ->where('new_teacher.schedule_id', $schedule->id)
                                            ->get();
                                        @endphp
                                        <div class="m-1">
                                            - {{ trans('latraining.session') .' '. ($key+ 1) .': ' }}
                                            {{ get_date($schedule->start_time, 'H:i') .' - '. get_date($schedule->end_time, 'H:i') }}
                                            ({{ get_date($schedule->lesson_date) }})
                                            <p class="mb-0 pl-4">{{ trans('latraining.teacher') .': '. $teacher->name .' ('. $teacher->code .')' }}</p>
                                            @foreach ($new_teachers as $new_teacher)
                                                <p class="mb-0 pl-4">{{ trans('latraining.teacher') .': '. $new_teacher->name .' ('. $new_teacher->code .')' }}</p>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-2 d-flex align-items-center">
                                <a href="javascript:void(0);" class="btn btn-info" onclick="submitRegisterClass({{$course_id}},{{$class->id}})">
                                    {{ trans('latraining.register') }}
                                </a>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                @else
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center">
                                <span>@lang('app.not_found')</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>
<script>
    //Đăng ký lớp học khoá học offline
    function submitRegisterClass(id, class_id) {
        var answer = window.confirm("{{ trans('laother.note_user_want_register') }}?");
        if (answer) {
            $('#frm-class #class_id').val(class_id);
            var url_link = "{{ route('module.offline.register_course', ['id' => ':id']) }}";
            url_link = url_link.replace(':id',id);
            $('#frm-class').attr('action', url_link);
            var form = $('#frm-class');
            form.submit();
        }
    }
</script>