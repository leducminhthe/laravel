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
                'name' => trans('latraining.report') .' Elearning: Buổi '. $schedule->session,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" class="form_offline_course">
        @include('offline::backend.includes.navgroup')
        <br>
        <div class="row mb-1">
            <div class="col-md-6">
                @include('offline::backend.elearning_report.filter')
            </div>
        </div>
        <table class="tDefault table table-hover" id="table-report-elearning">
            <thead>
            <tr>
                <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="3%"> # </th>
                <th data-field="full_name" data-formatter="user_formatter">{{ trans("latraining.fullname") }}</th>
                @foreach($activities as $activity)
                    <th data-field="activity_{{$activity->id}}" data-width="10%" data-align="center">
                        {{ trans("latraining.activiti") }} {{ $activity->name }}
                    </th>

                    @if ($activity->activity_id == 1)
                        <th data-field="score_{{$activity->id}}" data-width="10%" data-align="center">{{ trans("latraining.score") }} {{ $activity->name }}</th>
                    @endif
                @endforeach
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index+1)
        }
        function user_formatter(value, row, index) {
            return row.full_name +'<br> ('+ row.code +') <br>'+ row.email;
        }

        var table = new LoadBootstrapTable({
            url: '{{ route('module.offline.activity.report_elearning', ['course_id' => $course->id, 'class_id' => $class_id, 'schedule_id' => $schedule_id]) }}',
            table: "#table-report-elearning"
        });
    </script>
@endsection
