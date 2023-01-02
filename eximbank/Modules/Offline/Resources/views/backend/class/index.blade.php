@extends('layouts.backend')

@section('page_title', trans('latraining.classroom'))

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
                'name' => $offline->name,
                'url' => route('module.offline.edit', ['id' => $course_id])
            ],
            [
                'name' => trans('latraining.classroom'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        @if(isset($errors))
            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
        @endif
            <div class="row">
                @if($offline->id && !$user_invited)
                    @php
                        $total_rergister = \Modules\Offline\Entities\OfflineRegister::where('course_id', $offline->id)->count();
                        $total_rergister_approved = \Modules\Offline\Entities\OfflineRegister::where('course_id', $offline->id)->where('status', 1)->count();
                        $total_teacher = \Modules\Offline\Entities\OfflineTeacher::where('course_id', $offline->id)->count();
                        $total_monitoring_staff = \Modules\Offline\Entities\OfflineMonitoringStaff::where('course_id', $offline->id)->count();
                        $total_attendance = \Modules\Offline\Entities\OfflineAttendance::where('course_id', $offline->id)->groupBy('user_id')->get('user_id')->count();
                        $total_result = \Modules\Offline\Entities\OfflineResult::where('course_id', $offline->id)->where('result', 1)->count();
                        $total_rating_level_result = \Modules\Rating\Entities\RatingLevelCourse::where('course_id', $offline->id)->where('course_type', 2)->where('send', 1)->groupBy('user_id')->get('user_id')->count();
                    @endphp
                    <div class="col-md-12 text-center">
                        @canany(['offline-course-create', 'offline-course-edit'])
                        <a href="{{ route('module.offline.edit',['id' => $offline->id]) }}" class="btn">
                            <div><i class="fa fa-info"></i></div>
                            <div>{{ trans('latraining.info') }}</div>
                        </a>
                        @endcanany
                        @canany(['offline-course-teacher'])
                            <a href="{{ route('module.offline.teacher', ['id' => $offline->id,'class_id'=>$class->id]) }}" class="btn">
                                <div><i class="fas fa-chalkboard-teacher"></i> ({{ $total_teacher }})</div>
                                <div>{{ trans('latraining.teacher') }}</div>
                            </a>
                        @endcanany
                            <a href="{{ route('module.offline.monitoring_staff', ['id' => $offline->id]) }}"
                            class="btn">
                                <div><i class="fa fa-user"></i> ({{ $total_monitoring_staff }})</div>
                                <div>{{ trans('latraining.monitoring_staff') }}</div>
                            </a>
                        @canany(['offline-course-attendance'])
                            @php
                                $check_schedule = \Modules\Offline\Entities\OfflineSchedule::where(['course_id' => $offline->id, 'class_id' => $class->id])->first(['id']);
                                if (isset($check_schedule)) {
                                    $url_attendance = route('module.offline.attendance', ['id' => $offline->id, 'class_id' => $class->id]) . '?schedule=' . $check_schedule->id;
                                } else {
                                    $url_attendance = route('module.offline.attendance', ['id' => $offline->id, 'class_id' => $class->id]);
                                }
                            @endphp
                            <a href="{{ $url_attendance }}" class="btn">
                                <div><i class="fa fa-user-circle"></i> ({{ $total_attendance .'/'. $total_rergister_approved }})</div>
                                <div>{{ trans('latraining.attendance') }}</div>
                            </a>
                        @endcanany
                        @canany(['offline-course-result'])
                            <a href="{{ route('module.offline.result', ['id' => $offline->id]) }}" class="btn">
                                <div><i class="fa fa-briefcase"></i> ({{ $total_result .'/'. $total_rergister_approved }})</div>
                                <div>{{ trans('latraining.training_result') }}</div>
                            </a>
                        @endcanany
                        @can('offline-course-rating-level-result')
                            <a href="{{ route('module.offline.rating_level.list_report', [$offline->id]) }}" class="btn">
                                <div><i class="fa fa-star"></i> ({{ $total_rating_level_result .'/'. $total_rergister_approved }})</div>
                                <div>{{ trans('latraining.rating_level_result') }}</div>
                            </a>
                        @endcan
                    </div>
                @endif
            </div>
            <br>
            <div class="row">
                @if($offline->lock_course == 0)
                <div class="col-md-12 act-btns">
                    <div class="pull-right">
                        @include('offline::backend.register.filter_register')
                        <div class="wrraped_register text-right">
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> {{ trans('labutton.task') }}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="min-width: 14rem;">
                                        @canany(['offline-course-register-approve'])
                                            @if(!$user_invited)
                                                <a class="dropdown-item p-1 approved" href="javascript:void(0)" data-model="el_offline_register" data-status="1" style="cursor: pointer;">
                                                    <svg class="w_15" xmlns="http://www.w3.org/2000/svg" id="Icons" enable-background="new 0 0 128 128" height="512" viewBox="0 0 128 128" width="512"><path id="Check_Mark" d="m64 128c-35.289 0-64-28.711-64-64s28.711-64 64-64 64 28.711 64 64-28.711 64-64 64zm0-120c-30.879 0-56 25.121-56 56s25.121 56 56 56 56-25.121 56-56-25.121-56-56-56zm-9.172 78.828 40-40c1.563-1.563 1.563-4.094 0-5.656s-4.094-1.563-5.656 0l-37.172 37.172-13.172-13.172c-1.563-1.563-4.094-1.563-5.656 0s-1.563 4.094 0 5.656l16 16c.781.781 1.805 1.172 2.828 1.172s2.047-.391 2.828-1.172z"/></svg> {{ trans('labutton.approve') }}
                                                </a>
                                                <a class="dropdown-item p-1 approved" href="javascript:void(0)" data-model="el_offline_register" data-status="0" style="cursor: pointer;">
                                                    <svg class="w_15" xmlns="http://www.w3.org/2000/svg" height="512" viewBox="0 0 16 16" width="512"><g id="_19" data-name="19"><path d="m8 16a8 8 0 1 1 8-8 8 8 0 0 1 -8 8zm0-15a7 7 0 1 0 7 7 7 7 0 0 0 -7-7z"/><path d="m8.71 8 3.14-3.15a.49.49 0 0 0 -.7-.7l-3.15 3.14-3.15-3.14a.49.49 0 0 0 -.7.7l3.14 3.15-3.14 3.15a.48.48 0 0 0 0 .7.48.48 0 0 0 .7 0l3.15-3.14 3.15 3.14a.48.48 0 0 0 .7 0 .48.48 0 0 0 0-.7z"/></g></svg> {{ trans('labutton.deny') }}
                                                </a>
                                            @endif
                                        @endcanany
                                    </div>
                                </div>
                            </div>
                            @canany(['offline-course-register-create'])
                                <div class="btn-group">
                                    <button type="button" class="btn" onclick="create()">
                                        <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                                    </button>
                                    <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                                </div>
                            @endcanany
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <br>

            <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500]" id="list-class">
                <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="code">{{ trans('latraining.class_room_code') }}</th>
                        <th data-sortable="true" data-width="25%" data-field="name"  >{{ trans('latraining.class_room_name') }}</th>
                        <th data-field="students" data-align="center" data-width="5%">{{ trans('latraining.quantity') }}</th>
                        <th data-field="" data-formatter="training_time_formatter">{{ trans('latraining.training_time') }}</th>
                        <th data-formatter="teacher_formatter" data-align="center" data-width="5%">{{ trans('latraining.teacher') }}</th>
                        <th data-formatter="register_formatter" data-align="center" data-width="5%">{{ trans('latraining.register') }}</th>
                        <th data-formatter="attendance_formatter" data-align="center" data-width="5%">{{ trans('latraining.attendance') }}</th>
                        <th data-formatter="result_formatter" data-align="center" data-width="5%">{{ trans('latraining.training_result') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="modal right fade" id="modal-class" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="" autocomplete="off"  class="form-ajax form-horizontal" role="form" enctype="multipart/form-data" id="form_save" onsubmit="return false;">
                    <div class="modal-header">
                        <div class="btn-group">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                        </div>
                        <div class="btn-group act-btns">
                            @canany(['category-unit-create', 'category-unit-edit'])
                                <button type="button" id="btn_save" onclick="saveForm(event)" class="btn save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                            @endcanany
                            <button data-dismiss="modal" aria-label="Close" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="tPanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label>{{ trans('latraining.classroom') }} <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-7">
                                            <input name="name" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label>{{ trans('latraining.quantity') }} <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-7">
                                            <input name="students" type="text" class="form-control" value="" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label>{{ trans('latraining.training_time') }}</label>
                                        </div>
                                        <div class="col-md-7" id="type_unit">
                                            <span>
                                                <input name="start_date" type="text" placeholder="Ngày bắt đầu" class="datepicker_class form-control d-inline-block w-30" autocomplete="off">
                                            </span>
                                            <span class="fa fa-arrow-right page_speed_709547088"></span>
                                            <span>
                                                <input name="end_date" type="text" placeholder="Ngày kết thúc" class="datepicker_class form-control d-inline-block w-30" autocomplete="off">
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('.datepicker_class').datepicker({
            format: 'dd/mm/yyyy',
        });
        function info_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.info_url+'"> <i class="fa fa-info-circle"></i></a>';
        }
        function result_formatter(value, row, index) {
            return '<a href="'+row.result_url+'"><i class="fa fa-briefcase"></i></a>'
        }
        function register_formatter(value, row, index) {
            return '<a href="'+row.register_url+'"><i class="fa fa-user-plus"></i></a>'
        }
        function attendance_formatter(value, row, index) {
            return '<a href="'+row.attendance_url+'"><i class="fa fa-user-circle"></i></a>'
        }
        function teacher_formatter(value, row, index) {
            return '<a href="'+row.teacher_url+'"><i class="fas fa-chalkboard-teacher"></i></a>'
        }
        function status_formatter(value, row, index) {
            if (value == 1) {
                return '<span class="text-success">{{ trans("latraining.approved") }}</span>';
            }
            else if (value == 0) {
                return '<span class="text-danger">{{ trans("latraining.deny") }}</span>';
            }
            else {
                return '<span class="text-warning">{{ trans("latraining.not_approved") }}</span>';
            }

        }
        function training_time_formatter(value, row, index) {
            return row.start_date+' <i class="fa fa-arrow-right"></i> '+row.end_date;
        }
        function approved_formatter(value, row, index) {
            return value? `<a href="javascript:void(0)" data-id="${row.id}" data-model="el_offline_register" class="text-success font-weight-bold load-modal-approved-step">${value}</a>`:'-';
        }
        function unit_status_formatter(value, row, index) {
            return row.status_level_1 == 1 ? '<span class="text-primary">{{ trans("latraining.approved") }}</span>' : row.status_level_1 == 0 ? '<span ' +
                'class="text-danger">{{ trans("latraining.deny") }}</span>' : '<span class="text-warning">{{ trans("latraining.not_approved") }}</span>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.getdata', ['id' => $course_id]) }}',
            remove_url: '{{ route('module.offline.register.remove', ['id' => $course_id]) }}',
            table: '#list-class',
            form_search: '#form-search-user'
        });
        function create() {
            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            $('input[name="code"]').val('').trigger("reset");
            $('input[name="students"]').val('').trigger('change');
            $('input[name="start_date"]').val('').trigger('change');
            $('input[name="end_date"]').val('').trigger('change');
            $('#modal-class').modal();
        }
    </script>
@endsection
