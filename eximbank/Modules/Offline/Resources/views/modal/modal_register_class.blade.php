<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('latraining.classroom') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                @foreach ($register_class as $class)
                <div class="row mb-2">
                    <div class="col-10">
                        {{ $class->name .' ('. $class->code .')' }} <br>
                        {{ trans('latraining.quantity') .': '. $class->students }} <br>
                        {{ trans('latraining.time').': '. get_date($class->start_date) }} <i class="fa fa-arrow-right"></i> {{ get_date($class->end_date) }}
                    </div>
                    <div class="col-2 d-flex align-items-center">
                        @if ($obj == 'register')
                            <a href="{{ route('module.offline.register',['id'=>$course_id,'class_id'=>$class->id]) }}" class="btn"> {{ trans('latraining.register') }}</a>
                        @endif
                        @if ($obj == 'schedule')
                            <a href="{{ route('module.offline.schedule',['id'=>$course_id,'class_id'=>$class->id]) }}" class="btn"> {{ trans('latraining.schedule') }}</a>
                        @endif
                        @if ($obj == 'teacher')
                            <a href="{{ route('module.offline.teacher',['id'=>$course_id,'class_id'=>$class->id]) }}" class="btn"> {{ trans('latraining.teacher') }}</a>
                        @endif
                        @if ($obj == 'attendance')
                            @php
                                $check_schedule = \Modules\Offline\Entities\OfflineSchedule::where(['course_id' => $course_id, 'class_id' => $class->id])->first(['id']);
                                if (isset($check_schedule)) {
                                    $url_attendance = route('module.offline.attendance', ['id' => $course_id, 'class_id' => $class->id]) . '?schedule=' . $check_schedule->id;
                                } else {
                                    $url_attendance = route('module.offline.attendance', ['id' => $course_id, 'class_id' => $class->id]);
                                }
                            @endphp
                            <a href="{{ $url_attendance }}" class="btn"> {{ trans('latraining.attendance') }}</a>
                        @endif
                        @if ($obj == 'training_result')
                            <a href="{{ route('module.offline.result',['id'=>$course_id,'class_id'=>$class->id]) }}" class="btn"> {{ trans('latraining.training_result') }}</a>
                        @endif
                        @if ($obj == 'rating_level_result')
                            <a href="{{ route('module.offline.rating_level',['id'=>$course_id,'class_id'=>$class->id]) }}" class="btn"> {{ trans('latraining.rating_level_result') }}</a>
                        @endif
                    </div>
                </div>
                <hr>
                @endforeach
            </div>
        </div>
    </div>
</div>

