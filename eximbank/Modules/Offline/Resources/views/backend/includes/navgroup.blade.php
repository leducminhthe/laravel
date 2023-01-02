<div class="row">
    @if($course->id)
        @php
            $total_rergister = \Modules\Offline\Entities\OfflineRegister::where('course_id', $course->id)->count();
            $total_rergister_approved = \Modules\Offline\Entities\OfflineRegister::where('course_id', $course->id)->where('status', 1)->count();
            $total_teacher = \Modules\Offline\Entities\OfflineTeacher::where('course_id', $course->id)->count();
            $total_monitoring_staff = \Modules\Offline\Entities\OfflineMonitoringStaff::where('course_id', $course->id)->count();
            $total_attendance = \Modules\Offline\Entities\OfflineAttendance::where('course_id', $course->id)->groupBy('user_id')->get('user_id')->count();
            $total_result = \Modules\Offline\Entities\OfflineResult::where('course_id', $course->id)->where('result', 1)->count();
            $total_rating_level_result = \Modules\Rating\Entities\RatingLevelCourse::where('course_id', $course->id)->where('course_type', 2)->where('send', 1)->groupBy('user_id')->get('user_id')->count();
            $check_class = \Modules\Offline\Entities\OfflineCourseClass::whereCourseId($course->id)->count();
        @endphp
        <div class="col-md-12 text-center">
            <a href="{{ route('module.offline.edit', ['id' => $course->id]) }}" class="btn_link_offline">
                <div><i class="fa fa-info"></i></div>
                <div>{{ trans('latraining.info') }}</div>
            </a>
            @if ($check_class > 1)
                <a href="javascript:void(0)" class="load-modal btn_link_offline" data-url="{{ route('module.offline.modal_class', ['course_id' => $course->id, 'obj' => 'schedule']) }}"  >
                    <div><i class="far fa-calendar-alt"></i></div>
                    <div>{{ trans('latraining.schedule') }}</div>
                </a>
                @canany(['offline-course-register'])
                    <a href="javascript:void(0)" class="load-modal btn_link_offline" data-url="{{ route('module.offline.modal_class', ['course_id' => $course->id, 'obj' => 'register']) }}"  >
                        <div><i class="fa fa-user-plus"></i></div>
                        <div>{{ trans('latraining.register') }}</div>
                    </a>
                @endcanany
                {{-- @canany(['offline-course-teacher'])
                    <a href="javascript:void(0)" class="load-modal" data-url="{{ route('module.offline.modal_class', ['course_id' => $course->id, 'obj' => 'teacher']) }}" class="btn_link_offline">
                        <div><i class="fas fa-chalkboard-teacher"></i></div>
                        <div>{{ trans('latraining.teacher') }}</div>
                    </a>
                @endcanany --}}
                @canany(['offline-course-attendance'])
                    <a href="javascript:void(0)" class="load-modal btn_link_offline" data-url="{{ route('module.offline.modal_class', ['course_id' => $course->id, 'obj' => 'attendance']) }}"  >
                        <div><i class="fa fa-user-circle"></i></div>
                        <div>{{ trans('latraining.attendance') }}</div>
                    </a>
                @endcanany
                @canany(['offline-course-result'])
                    <a href="javascript:void(0)" class="load-modal btn_link_offline" data-url="{{ route('module.offline.modal_class', ['course_id' => $course->id, 'obj' => 'training_result']) }}"  >
                        <div><i class="fa fa-briefcase"></i></div>
                        <div>{{ trans('latraining.training_result') }}</div>
                    </a>
                @endcanany
                @can('offline-course-rating-level-result')
                    <a href="javascript:void(0)" class="load-modal btn_link_offline" data-url="{{ route('module.offline.modal_class', ['course_id' => $course->id, 'obj' => 'rating_level_result']) }}"  >
                        <div><i class="fa fa-star"></i></div>
                        <div>{{ trans('latraining.rating_level_result') }}</div>
                    </a>
                @endcan
            @else
                <a href="{{ route('module.offline.schedule', ['id' => $course->id,'class_id'=>$class->id]) }}" class="btn_link_offline">
                    <div><i class="far fa-calendar-alt"></i></div>
                    <div>{{ trans('latraining.schedule') }}</div>
                </a>
                @canany(['offline-course-register'])
                    <a href="{{ route('module.offline.register', ['id' => $course->id,'class_id'=>$class->id]) }}" class="btn_link_offline">
                        <div><i class="fa fa-user-plus"></i></div>
                        <div>{{ trans('latraining.register') }}</div>
                    </a>
                @endcanany
                {{-- @canany(['offline-course-teacher'])
                    <a href="{{ route('module.offline.teacher', ['id' => $course->id, 'class_id'=>$class->id]) }}" class="btn_link_offline">
                        <div><i class="fas fa-chalkboard-teacher"></i></div>
                        <div>{{ trans('latraining.teacher') }}</div>
                    </a>
                @endcanany --}}
                @canany(['offline-course-attendance'])
                    @php
                        $check_schedule = \Modules\Offline\Entities\OfflineSchedule::where(['course_id' => $course->id, 'class_id' => $class->id])->first(['id']);
                        if (isset($check_schedule)) {
                            $url_attendance = route('module.offline.attendance', ['id' => $course->id, 'class_id' => $class->id]) . '?schedule=' . $check_schedule->id;
                        } else {
                            $url_attendance = route('module.offline.attendance', ['id' => $course->id, 'class_id' => $class->id]);
                        }
                    @endphp
                    <a href="{{ $url_attendance }}" class="btn_link_offline">
                        <div><i class="fa fa-user-circle"></i></div>
                        <div>{{ trans('latraining.attendance') }}</div>
                    </a>
                @endcanany
                @canany(['offline-course-result'])
                    <a href="{{ route('module.offline.result', ['id' => $course->id, 'class_id'=>$class->id]) }}" class="btn_link_offline">
                        <div><i class="fa fa-briefcase"></i></div>
                        <div>{{ trans('latraining.training_result') }}</div>
                    </a>
                @endcanany
                @can('offline-course-rating-level-result')
                    <a href="{{ route('module.offline.rating_level', [$course->id, 'class_id'=>$class->id]) }}" class="btn_link_offline">
                        <div><i class="fa fa-star"></i></div>
                        <div>{{ trans('latraining.rating_level_result') }}</div>
                    </a>
                @endcan
            @endif

            <a href="{{ route('module.offline.quiz', [$course->id]) }}" class="btn_link_offline">
                <div><i class="fa fa-question-circle"></i></div>
                <div>{{ trans('latraining.quiz_list') }}</div>
            </a>
            <a href="{{ route('module.offline.teaching_organization.index', [$course->id]) }}" class="btn_link_offline">
                <div><i class="fa fa-star"></i></div>
                <div>{{trans("latraining.teaching_organization")}}</div>
            </a>
        </div>
    @endif
</div>
