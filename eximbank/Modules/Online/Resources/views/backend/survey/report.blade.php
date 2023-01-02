@extends('layouts.backend')

@section('page_title', trans('latraining.report'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.online_course'),
                'url' => route('module.online.management')
            ],
            [
                'name' => $course_name,
                'url' => route('module.online.edit', ['id' => $course_id])
            ],
            [
                'name' => trans('latraining.report').': '.$survey_name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" class="quiz_course_online_report">
        <div class="row">
            <div class="col-md-8 form-inline">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('latraining.enter_code_name_user')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns" id="btn-quiz">
                <div class="pull-right">
                    <a href="{{ route('module.online.survey.export_report', ['id' => $course_id, 'activityId' => $activityId]) }}" class="btn">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export
                    </a>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table ">
            <thead>
                <tr>
                    <th data-field="code" user.code data-width="10%" data-align="center">{{trans('laprofile.employee_code')}}</th>
                    <th data-field="full_name" user.full_name data-width="25%">{{trans('latraining.fullname')}}</th>
                    <th data-field="email" user.full_name data-width="20%">Email</th>
                    <th data-field="title_name" data-width="20%">{{trans('lasetting.title')}}</th>
                    <th data-field="unit_name" data-width="20%" data-align="center">{{trans('lamenu.unit')}}</th>
                    <th data-field="status" data-formatter="status_formatter" data-width="10%" data-align="center">{{trans('latraining.status')}}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function status_formatter(value, row, index) {
            if(row.send == 1) {
                return '<span>{{ trans("ladashboard.completed") }}</span>'
            } else {
                return '<span>{{ trans("ladashboard.uncomplete") }}</span>'
            }
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.survey.report', ['id' => $course_id, 'activityId' => $activityId]) }}',
        });
    </script>
@endsection
