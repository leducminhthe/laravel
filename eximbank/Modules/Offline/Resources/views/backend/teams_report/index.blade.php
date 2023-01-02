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
                'name' => $course->name,
                'url' => route('module.offline.edit', ['id' => $course->id])
            ],
            /*[
                'name' => trans('latraining.classroom'),
                'url' => route('module.offline.class', ['id' => $course->id])
            ],*/
            [
                'name' => trans('latraining.schedule').": ".$class->name,
                'url' => route("module.offline.schedule", [$course->id, $class->id])
            ],
            [
                'name' => trans('latraining.report_teams'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <style>
        #form-search {
            display: flex;
            justify-content: end;
        }
    </style>
    <div role="main" class="form_offline_course">
        @if(isset($errors))
            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
        @endif
        @include('offline::backend.includes.navgroup')
        <br>
        <div class="row">
            <div class="col-12 mb-2">
                <div class="row">
                    <div class="col-6">
                        <h4>Thông tin</h4>
                    </div>
                    <div class="col-6 text-right">
                        <form role="form" enctype="multipart/form-data" id="form-search">
                            <button type="button" id="update_report" class="btn mr-1" onclick="updateReportHandle({{ $course->id }},{{ $class_id }},{{ $schedule_id }})">Update</button>
                            <a id="export_report" href="" class="btn mr-1"><i class="fa fa-download"></i> download</a>
                            <div class="w-auto">
                                <select name="report_id" id="report_id" class="form-control select2" data-placeholder="Báo cáo teams">
                                    <option value=""></option>
                                    @foreach ($all_report as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == $report->id ? 'selected' : '' }}>{{ get_datetime($item->meeting_start) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-3">
                        <p class="mb-0 ml-4 total_participant">
                            <strong>{{ $report->total_participant }}</strong>
                        </p>
                        <p class="mb-0">Người tham dự</p>
                    </div>
                    <div class="col-5">
                        <p class="mb-0 time_report">
                            {{ get_datetime($report->meeting_start) }} - {{ get_datetime($report->meeting_end) }}
                        </p>
                        <p class="mb-0">Thời gian diễn ra</p>
                    </div>
                    <div class="col-4">
                        @php
                            $seconds = strtotime($report->meeting_end) - strtotime($report->meeting_start);
                            $days    = floor($seconds / 86400);
                            $hours   = floor(($seconds - ($days * 86400)) / 3600);
                            $minutes = floor(($seconds - ($days * 86400) - ($hours * 3600))/60);
                            $seconds = floor(($seconds - ($days * 86400) - ($hours * 3600) - ($minutes*60)));
                            $time = ($hours ? $hours. 'h ' : '') . ($minutes ? $minutes. 'm ' : '') . ($seconds ? $seconds. 's' : '');
                        @endphp
                        <p class="mb-0 duration_report">
                            {{ $time }}
                        </p>
                        <p class="mb-0">Thời lượng</p>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover" id="table-report-teams">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="full_name"  >{{ trans('latraining.nam_attendance') }}</th>
                <th data-field="email"   >{{ trans('latraining.email') }}</th>
                <th data-field="join_time" data-align="center">{{ trans('latraining.join_time') }}</th>
                <th data-field="leave_time" data-align="center">{{ trans('latraining.leave_time') }}</th>
                <th data-field="duration" data-align="center">{{ trans('lareport.duration') }}</th>
                <th data-field="role">{{ trans('latraining.role') }}</th>
            </tr>
            </thead>
        </table>
    </div>

<script type="text/javascript">
    var report_first = '{{ $report->id }}'
    var url = "{{ route('module.offline.activity.export_report_teams_info', ['id' => ':id']) }}";
    url = url.replace(':id', report_first)
    $('#export_report').attr("href", url)

    var table = new LoadBootstrapTable({
        url: '{{ route('module.offline.activity.report_teams', ['course_id' => $course->id, 'class_id' => $class_id, 'schedule_id' => $schedule_id]) }}',
        table: "#table-report-teams"
    });

    $('.timepicker').datetimepicker({
        locale:'vi',
        format: 'HH:mm'
    });


    $('#district_id').on('select2:select', function (e) {
        var province = $('#province_id').val();
        var district = $('#district_id').val();
        loadTranginingLocation(province, district)
    });

    $('#report_id').on('change', function() {
        var reportId = $(this).val();
        $.ajax({
            type: 'POST',
            url: '{{ route('module.offline.activity.report_teams_info') }}',
            dataType: 'json',
            data: {
                reportId: reportId
            }
        }).done(function(data) {
            let url_report = "{{ route('module.offline.activity.export_report_teams_info', ['id' => ':id']) }}";
            url_report = url_report.replace(':id', data.report.id)
            console.log(url_report);
            $('#export_report').attr("href", url_report)

            $('.total_participant').html(data.report.total_participant)
            $('.time_report').html(data.time)
            $('.duration_report').html(data.duration)
            $('#form-search').submit();
            return false;
        }).fail(function(data) {
            return false;
        });
    })

    function updateReportHandle(courseId, classId, scheduleId) {
        let item = $('#update_report');
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');

        $.ajax({
            type: 'POST',
            url: '{{ route('module.offline.activity.update_report_teams_info') }}',
            dataType: 'json',
            data: {
                courseId: courseId,
                classId: classId,
                scheduleId: scheduleId,
            }
        }).done(function(data) {
            item.html(oldtext);
            window.location.href = data.redirect
            return false;
        }).fail(function(data) {
            return false;
        });
    }
</script>
@endsection
