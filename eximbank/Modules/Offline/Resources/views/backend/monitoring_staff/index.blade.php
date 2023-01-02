@extends('layouts.backend')

@section('page_title', trans('latraining.monitoring_staff'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training_organizations'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.offline_course'),
                'url' => route('module.offline.management')
            ],
            [
                'name' => $page_title,
                'url' => route('module.offline.edit', ['id' => $course->id])
            ],
            /*[
                'name' => trans('latraining.classroom'),
                'url' => route('module.offline.class', ['id' => $course->id])
            ],*/
            [
                'name' => trans('latraining.monitoring_staff_class').": ".$class->name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" id="monitoring_staff_offline">
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
                @endphp
                <div class="col-md-12 text-center">
                    @canany(['offline-course-create', 'offline-course-edit'])
                        <a href="{{ route('module.offline.edit', ['id' => $course->id]) }}" class="btn">
                            <div><i class="fa fa-info"></i></div>
                            <div>{{ trans('latraining.info') }}</div>
                        </a>
                    @endcanany
                    @canany(['offline-course-register'])
                        <a href="{{ route('module.offline.register', ['id' => $course->id]) }}" class="btn">
                            <div><i class="fa fa-edit"></i> ({{ $total_rergister_approved .'/'. $total_rergister }})</div>
                            <div>{{ trans('latraining.register') }}</div>
                        </a>
                    @endcanany
                    @canany(['offline-course-teacher'])
                        <a href="{{ route('module.offline.teacher', ['id' => $course->id,'class_id'=>$class->id]) }}" class="btn">
                            <div><i class="fa fa-inbox"></i> ({{ $total_teacher }})</div>
                            <div>{{ trans('latraining.teacher') }}</div>
                        </a>
                    @endcanany
                    @canany(['offline-course-attendance'])
                        @php
                            $check_schedule = \Modules\Offline\Entities\OfflineSchedule::where(['course_id' => $course->id, 'class_id' => $class->id])->first(['id']);
                            if (isset($check_schedule)) {
                                $url_attendance = route('module.offline.attendance', ['id' => $course->id, 'class_id' => $class->id]) . '?schedule=' . $check_schedule->id;
                            } else {
                                $url_attendance = route('module.offline.attendance', ['id' => $course->id, 'class_id' => $class->id]);
                            }
                        @endphp
                        <a href="{{ $url_attendance }}" class="btn">
                            <div><i class="fa fa-user"></i> ({{ $total_attendance .'/'. $total_rergister_approved }})</div>
                            <div>{{ trans('latraining.attendance') }}</div>
                        </a>
                    @endcanany
                    @canany(['offline-course-result'])
                        <a href="{{ route('module.offline.result', ['id' => $course->id, 'class_id'=>$class->id]) }}" class="btn">
                            <div><i class="fa fa-briefcase"></i> ({{ $total_result .'/'. $total_rergister_approved }})</div>
                            <div>{{ trans('latraining.training_result') }}</div>
                        </a>
                    @endcanany
                    @can('offline-course-rating-level-result')
                        <a href="{{ route('module.offline.rating_level', ['id'=>$course->id,'class_id'=>$class->id]) }}" class="btn">
                            <div><i class="fa fa-star"></i> ({{ $total_rating_level_result .'/'. $total_rergister_approved }})</div>
                            <div>{{ trans('latraining.rating_level_result') }}</div>
                        </a>
                    @endcan
                </div>
            @endif
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                @include('offline::backend.monitoring_staff.filter')
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @if($course->lock_course == 0)
                        <a href="javascript:void(0)" id="import_monitoring_staff" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="code">{{ trans('latraining.employee_code') }}</th>
                    <th data-sortable="true" data-width="25%" data-field="name" data-formatter="name_formatter">{{ trans('latraining.employee_name') }}</th>
                    <th data-field="email">{{ trans('latraining.email') }}</th>
                    <th data-field="title_name">{{ trans('latraining.title') }}</th>
                    <th data-field="unit_name">{{ trans('latraining.work_unit') }}</th>
                    <th data-field="unit_manager">{{ trans('latraining.unit_manager') }}</th>
                    <th data-field="note" data-formatter="note_formatter">{{ trans('latraining.note') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.offline.save_monitoring_staff', ['id' => $course->id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="unit" value="{{ $course->unit_id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> {{ trans('latraining.monitoring_staff') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('latraining.cadres') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select name="user_id" id="user_id" class="form-control load-user" data-placeholder="{{ trans('latraining.choose_cadres') }}" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        @if($course->lock_course == 0)
                        <button type="submit" class="btn">{{trans('labutton.save')}}</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.get_monitoring_staff', ['id' => $course->id]) }}',
            remove_url: '{{ route('module.offline.remove_monitoring_staff', ['id' => $course->id]) }}'
        });

        function name_formatter(value, row, index) {
            return row.lastname +' ' + row.firstname;
        }

        function note_formatter(value, row, index) {
            return '<textarea type="text" name="note" data-id="'+ row.id +'" class="form-control change-note" {{ $course->lock_course == 0 ? '' : 'readonly' }}>'+ (row.note ? row.note : "") +'</textarea>';
        }

        $('#import_monitoring_staff').on('click', function() {
            $('#modal-import').modal();
        });

        $('#monitoring_staff_offline').on('change', '.change-note', function() {
            var note = $(this).val();
            var off_monitoring_staff_id = $(this).data('id');

            $.ajax({
                url: '{{ route('module.offline.monitoring_staff.save_note', ['id' => $course->id]) }}',
                type: 'post',
                data: {
                    note: note,
                    off_monitoring_staff_id : off_monitoring_staff_id,
                },

            }).done(function(data) {

                return false;

            }).fail(function(data) {

                show_message(
                    'Lỗi hệ thống',
                    'error'
                );
                return false;
            });
        });
    </script>
@endsection
