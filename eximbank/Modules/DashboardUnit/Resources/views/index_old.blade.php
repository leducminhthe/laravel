@extends('layouts.backend')

@section('page_title', trans('lamenu.dashboard'))
@section('header')
    <script src="{{asset('styles/vendor/chart/Chart.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('styles/js/google_chart.js')}}" type="text/javascript"></script>
    <script src="{{asset('styles/vendor/jqueryplugin/jquery.knob.min.js')}}" type="text/javascript"></script>
    <link rel="stylesheet" href="{{ asset('styles/vendor/ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/module/dashboard/css/dashboard.css') }}">
    <style>
        .select2-container--default .select2-selection--single,
        .select2-selection .select2-selection--single{
            padding: 2px 12px;
        }

        .small-box .inner{
            background-size: contain;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form class="form-inline form-search mb-3" id="form-search">
                <div class="w-25">
                    <select name="area" id="area-2" class="form-control load-area" data-placeholder="-- {{ $level_name_area(2) }} --" data-level="2">
                        @if(isset($area_request))
                            <option value="{{ $area_request->id }}"> {{ $area_request->name }}</option>
                        @endif
                    </select>
                </div>
                <div class="w-25">
                    <div class="wrraped_unit_choose" onclick="chooseUnitHandle({{ session()->get('user_unit') }})">
                        <input type="hidden" name="unit" class="unit_id" value="{{ ($unit_request) ? $unit_request->id : '' }}">
                        <span class="name_unit">
                            @if (($unit_request))
                                <div class="get_name_unit">{{ $unit_request->name }}</div>
                            @else
                                <span class="default_title">-- {{ trans('latraining.choose_unit') }} --</span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="w-25">
                    <select name="unit_type" class="form-control select2" data-placeholder="-- {{ trans('laother.unit_type') }} --">
                        <option value=""></option>
                        @foreach($unit_type as $type)
                            <option value="{{ $type->id }}" {{ isset($unit_type_request) && $unit_type_request == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-25">
                    <input type="text" name="start_date" value="{{ isset($start_date_request) ? $start_date_request : '' }}" class="form-control datepicker w-100" placeholder="{{ trans('laother.start_date') }}">
                </div>
                <div class="w-25">
                    <input type="text" name="end_date" value="{{ isset($end_date_request) ? $end_date_request : '' }}" class="form-control datepicker w-100" placeholder="{{ trans('laother.end_date') }}">
                </div>
                <div class="w-auto">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </div>
            </form>
        </div>
    </div>
    @php
        $area = request()->get('area');
        $unit = request()->get('unit');
        $unit_type = request()->get('unit_type');
        $start_date = request()->get('start_date');
        $end_date = request()->get('end_date');
    @endphp
    <div class="row">
        <div class="col pr-0 col-xs-6">
            <!-- small box -->
            <div class="small-box">
                <div class="inner" style="background: url({{asset('images/design/elearning_dashboard_detail.png')}}) no-repeat center;">
                    <div class="w-50 text-center">
                        <div class="mb-2">@lang('ladashboard.elearning')</div>
                        <h3>{{ $count_online_by_course }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col pr-0 col-xs-6">
            <!-- small box -->
            <div class="small-box">
                <div class="inner" style="background: url({{asset('images/design/offline_dashboard_detail.png')}}) no-repeat center;">
                    <div class="w-50 text-center">
                        <div class="mb-2">@lang('ladashboard.offline')</div>
                        <h3>{{ $count_offline_by_course }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col pr-0 col-xs-6">
            <!-- small box -->
            <div class="small-box">
                <div class="inner" style="background: url({{asset('images/design/user_online_course_dashboard_detail.png')}}) no-repeat center;">
                    <div>
                        <div class="mb-2">@lang('ladashboard.user_by_online')</div>
                        <h3 class="w-50 text-center">{{ $count_user_by_online_course }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col pr-0 col-xs-6">
            <!-- small box -->
            <div class="small-box">
                <div class="inner" style="background: url({{asset('images/design/user_offline_course_dashboard_detail.png')}}) no-repeat center;">
                    <div class="mb-2">@lang('ladashboard.user_by_offline')</div>
                    <h3 class="w-50 text-center">{{ $count_user_by_offline_course }}</h3>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col pr-0 col-xs-6">
            <!-- small box -->
            <div class="small-box">
                <div class="inner" style="background: url({{asset('images/design/quiz_part_dashboard_detail.png')}}) no-repeat center;">
                    <div class="w-50 text-center">
                        <div class="mb-2">@lang('ladashboard.part')</div>
                        <h3>{{ $count_part_by_quiz }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col col-xs-6">
            <!-- small box -->
            <div class="small-box">
                <div class="inner" style="background: url({{asset('images/design/user_quiz_dashboard_detail.png')}}) no-repeat center;">
                    <div class="mb-2">@lang('ladashboard.user_by_quiz')</div>
                    <h3 class="w-50 text-center">{{ $count_user_by_quiz }}</h3>
                </div>
            </div>
        </div>
        <!-- ./col -->
    </div>

    <!-- Thống kê lớp theo loại hình đào tạo -->
    <div class="row mt-2">
        <div class="col-7">
            <div class="card">
                <div class="card-header row m-0 bg-white">
                    <div class="col-10">
                        <h3 class="box-title text-info">{{ trans('ladashboard.chart_course_by_training_type') }}</h3>
                    </div>
                    <div class="col-2 pull-right p-0">
                        <form action="{{ route('module.dashboard_unit.export_dashboard_training_form',['type' => 0]) }}" method="GET">
                            <input type="hidden" name="area_training_form" value="{{ $area }}">
                            <input type="hidden" name="unit_training_form" value="{{ $unit }}">
                            <input type="hidden" name="unit_type_training_form" value="{{ $unit_type }}">
                            <input type="hidden" name="start_date_training_form" value="{{ $start_date }}">
                            <input type="hidden" name="end_date_training_form" value="{{ $end_date }}">
                            <button class="btn" type="submit"><i class="fa fa-download"></i> {{ trans('labutton.export') }}</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="lineChartCourseByTrainingForm" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header bg-white">
                    <h3 class="box-title text-info">{{ trans('ladashboard.chart_course_by_training_type') }}</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="pieChartCourseByTrainingForm" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê CBNV theo loại hình đào tạo -->
    <div class="row mt-2">
        <div class="col-7">
            <div class="card">
                <div class="card-header bg-white row m-0">
                    <div class="col-10">
                        <h3 class="box-title text-primary">{{ trans('ladashboard.chart_user_by_training_type') }}</h3>
                    </div>
                    <div class="col-2 pull-right p-0">
                        <form action="{{ route('module.dashboard_unit.export_dashboard_user_training_form') }}" method="GET">
                            <input type="hidden" name="area_user_training_form" value="{{ $area }}">
                            <input type="hidden" name="unit_user_training_form" value="{{ $unit }}">
                            <input type="hidden" name="unit_type_user_training_form" value="{{ $unit_type }}">
                            <input type="hidden" name="start_user_date_training_form" value="{{ $start_date }}">
                            <input type="hidden" name="end_user_date_training_form" value="{{ $end_date }}">
                            <button class="btn" type="submit"><i class="fa fa-download"></i> {{ trans('labutton.export') }}</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="lineChartUserByTrainingForm" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header bg-white">
                    <h3 class="box-title text-primary">{{ trans('ladashboard.chart_user_by_training_type') }}</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="pieChartUserByTrainingForm" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê lớp theo Tân tuyển/Hiện Hữu -->
    <div class="row mt-2">
        <div class="col-7">
            <div class="card">
                <div class="card-header bg-white row m-0">
                    <div class="col-10">
                        <h3 class="box-title text-secondary">{{ trans('ladashboard.chart_course_by_course_employee') }}</h3>
                    </div>
                    <div class="col-2 pull-right p-0">
                        <form action="{{ route('module.dashboard_unit.export_dashboard_course_employee',['type' => 0]) }}" method="GET">
                            <input type="hidden" name="area_course_employee" value="{{ $area }}">
                            <input type="hidden" name="unit_course_employee" value="{{ $unit }}">
                            <input type="hidden" name="unit_type_course_employee" value="{{ $unit_type }}">
                            <input type="hidden" name="start_date_course_employee" value="{{ $start_date }}">
                            <input type="hidden" name="end_date_course_employee" value="{{ $end_date }}">
                            <button class="btn" type="submit"><i class="fa fa-download"></i> {{ trans('labutton.export') }}</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="lineChartCourseByCourseEmployee" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header bg-white">
                    <h3 class="box-title text-secondary">{{ trans('ladashboard.chart_course_by_course_employee') }}</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="pieChartCourseByCourseEmployee" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê CBNV theo Tân tuyển & Hiện hữu -->
    <div class="row mt-2">
        <div class="col-7">
            <div class="card">
                <div class="card-header bg-white row m-0">
                    <div class="col-10">
                        <h3 class="box-title text-success">{{ trans('ladashboard.chart_user_by_course_employee') }}</h3>
                    </div>
                    <div class="col-2 pull-right p-0">
                        <form action="{{ route('module.dashboard_unit.export_dashboard_user_course_employee') }}" method="GET">
                            <input type="hidden" name="area_user_course_employee" value="{{ $area }}">
                            <input type="hidden" name="unit_user_course_employee" value="{{ $unit }}">
                            <input type="hidden" name="unit_type_user_course_employee" value="{{ $unit_type }}">
                            <input type="hidden" name="start_date_user_course_employee" value="{{ $start_date }}">
                            <input type="hidden" name="end_date_user_course_employee" value="{{ $end_date }}">
                            <button class="btn" type="submit"><i class="fa fa-download"></i> {{ trans('labutton.export') }}</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="lineChartUserByCourseEmployee" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header bg-white">
                    <h3 class="box-title text-success">{{ trans('ladashboard.chart_user_by_course_employee') }}</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="pieChartUserByCourseEmployee" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê số ca thi theo loại kỳ thi -->
    <div class="row mt-2">
        <div class="col-7">
            <div class="card">
                <div class="card-header bg-white row m-0">
                    <div class="col-10">
                        <h3 class="box-title text-danger">{{ trans('ladashboard.chart_part_by_quiz_type') }}</h3>
                    </div>
                    <div class="col-2 pull-right p-0">
                        <form action="{{ route('module.dashboard_unit.export_dashboard_quiz',['type' => 0]) }}" method="GET">
                            <input type="hidden" name="area_quiz" value="{{ $area }}">
                            <input type="hidden" name="unit_quiz" value="{{ $unit }}">
                            <input type="hidden" name="unit_type_quiz" value="{{ $unit_type }}">
                            <input type="hidden" name="start_date_quiz" value="{{ $start_date }}">
                            <input type="hidden" name="end_date_quiz" value="{{ $end_date }}">
                            <button class="btn" type="submit"><i class="fa fa-download"></i> {{ trans('labutton.export') }}</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="lineChartPartByQuizType" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header bg-white">
                    <h3 class="box-title text-danger">{{ trans('ladashboard.chart_part_by_quiz_type') }}</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="pieChartPartByQuizType" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê CBNV thi theo loại kỳ thi -->
    <div class="row mt-2">
        <div class="col-7">
            <div class="card">
                <div class="card-header bg-white row m-0">
                    <div class="col-10">
                        <h3 class="box-title text-warning">{{ trans('ladashboard.chart_user_by_quiz_type') }}</h3>
                    </div>
                    <div class="col-2 pull-right p-0">
                        <form action="{{ route('module.dashboard_unit.export_dashboard_user_quiz') }}" method="GET">
                            <input type="hidden" name="area_user_quiz" value="{{ $area }}">
                            <input type="hidden" name="unit_user_quiz" value="{{ $unit }}">
                            <input type="hidden" name="unit_type_user_quiz" value="{{ $unit_type }}">
                            <input type="hidden" name="start_date_user_quiz" value="{{ $start_date }}">
                            <input type="hidden" name="end_date_user_quiz" value="{{ $end_date }}">
                            <button class="btn" type="submit"><i class="fa fa-download"></i> {{ trans('labutton.export') }}</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="lineChartUserByQuizType" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header bg-white m-0">
                    <h3 class="box-title text-warning">{{ trans('ladashboard.chart_user_by_quiz_type') }}</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="pieChartUserByQuizType" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        google.charts.load('current', {'packages':['corechart', 'line']});

        google.charts.setOnLoadCallback(lineChartCourseByTrainingForm);
        google.charts.setOnLoadCallback(pieChartCourseByTrainingForm);

        google.charts.setOnLoadCallback(lineChartUserByTrainingForm);
        google.charts.setOnLoadCallback(pieChartUserByTrainingForm);

        google.charts.setOnLoadCallback(lineChartCourseByCourseEmployee);
        google.charts.setOnLoadCallback(pieChartCourseByCourseEmployee);

        google.charts.setOnLoadCallback(lineChartUserByCourseEmployee);
        google.charts.setOnLoadCallback(pieChartUserByCourseEmployee);

        google.charts.setOnLoadCallback(lineChartPartByQuizType);
        google.charts.setOnLoadCallback(pieChartPartByQuizType);

        google.charts.setOnLoadCallback(lineChartUserByQuizType);
        google.charts.setOnLoadCallback(pieChartUserByQuizType);

        function lineChartCourseByTrainingForm() {
            let result = @json($lineChartCourseByTrainingForm);
            var data = google.visualization.arrayToDataTable(
                result['content']
            );
            var max = result['check'] ? '' : 100;
            var options = {
                lineWidth: 3,
                legend: {
                    position: 'right',
                    maxLines: 4,
                },
                chartArea:{
                    left: 30,
                },
                vAxis: {
                    viewWindow: {
                        min: 0,
                        max: max
                    },
                },
                hAxis: {
                    title: '{{ trans('ladashboard.month') }}',
                },
            };
            var chart = new google.visualization.LineChart(document.getElementById('lineChartCourseByTrainingForm'));
            chart.draw(data, options);
        }
        function pieChartCourseByTrainingForm() {
            var data = google.visualization.arrayToDataTable(@json($pieChartCourseByTrainingForm));
            var options = {
                title: '',
                pieHole: 0.4,
                pieSliceTextStyle: {
                    color: 'black',
                },
                legend: {position: 'top'},
                pieSliceText: 'value',
            };
            var chart = new google.visualization.PieChart(document.getElementById('pieChartCourseByTrainingForm'));
            chart.draw(data, options);
        }

        function lineChartUserByTrainingForm() {
            let result = @json($lineChartUserByTrainingForm);
            var data = google.visualization.arrayToDataTable(
                result['content']
            );

            var max = result['check'] ? '' : 100;
            var options = {
                lineWidth: 3,
                legend: {
                    position: 'right',
                    maxLines: 4,
                },
                chartArea:{
                    left: 30,
                },
                vAxis: {
                    viewWindow: {
                        min: 0,
                        max: max
                    },
                },
                hAxis: {
                    title: '{{ trans('ladashboard.month') }}',
                },
            };
            var chart = new google.visualization.LineChart(document.getElementById('lineChartUserByTrainingForm'));
            chart.draw(data, options);
        }
        function pieChartUserByTrainingForm() {
            var data = google.visualization.arrayToDataTable(@json($pieChartUserByTrainingForm));
            var options = {
                title: '',
                pieHole: 0.4,
                pieSliceTextStyle: {
                    color: 'black',
                },
                legend: {position: 'top'},
                pieSliceText: 'value',
            };
            var chart = new google.visualization.PieChart(document.getElementById('pieChartUserByTrainingForm'));
            chart.draw(data, options);
        }

        function lineChartCourseByCourseEmployee() {
            let result = @json($lineChartCourseByCourseEmployee);
            var data = google.visualization.arrayToDataTable(
                result['content']
            );

            var max = result['check'] ? '' : 100;
            var options = {
                lineWidth: 3,
                legend: {
                    position: 'right',
                    maxLines: 4,
                },
                chartArea:{
                    left: 30,
                },
                vAxis: {
                    viewWindow: {
                        min: 0,
                        max: max
                    },
                },
                hAxis: {
                    title: '{{ trans('ladashboard.month') }}',
                },
            };
            var chart = new google.visualization.LineChart(document.getElementById('lineChartCourseByCourseEmployee'));
            chart.draw(data, options);
        }
        function pieChartCourseByCourseEmployee() {
            var data = google.visualization.arrayToDataTable(@json($pieChartCourseByCourseEmployee));
            var options = {
                title: '',
                pieHole: 0.4,
                pieSliceTextStyle: {
                    color: 'black',
                },
                legend: {position: 'top'},
                pieSliceText: 'value',
            };
            var chart = new google.visualization.PieChart(document.getElementById('pieChartCourseByCourseEmployee'));
            chart.draw(data, options);
        }

        function lineChartUserByCourseEmployee() {
            let result = @json($lineChartUserByCourseEmployee);
            var data = google.visualization.arrayToDataTable(
                result['content']
            );

            var max = result['check'] ? '' : 100;
            var options = {
                lineWidth: 3,
                legend: {
                    position: 'right',
                    maxLines: 4,
                },
                chartArea:{
                    left: 30,
                },
                vAxis: {
                    viewWindow: {
                        min: 0,
                        max: max
                    },
                },
                hAxis: {
                    title: '{{ trans('ladashboard.month') }}',
                },
            };
            var chart = new google.visualization.LineChart(document.getElementById('lineChartUserByCourseEmployee'));
            chart.draw(data, options);
        }
        function pieChartUserByCourseEmployee() {
            var data = google.visualization.arrayToDataTable(@json($pieChartUserByCourseEmployee));
            var options = {
                title: '',
                pieHole: 0.4,
                pieSliceTextStyle: {
                    color: 'black',
                },
                legend: {position: 'top'},
                pieSliceText: 'value',
            };
            var chart = new google.visualization.PieChart(document.getElementById('pieChartUserByCourseEmployee'));
            chart.draw(data, options);
        }

        function lineChartPartByQuizType() {
            let result = @json($lineChartPartByQuizType);
            var data = google.visualization.arrayToDataTable(
                result['content']
            );

            var max = result['check'] ? '' : 100;
            var options = {
                lineWidth: 3,
                legend: {
                    position: 'right',
                    maxLines: 4,
                },
                chartArea:{
                    left: 30,
                },
                vAxis: {
                    viewWindow: {
                        min: 0,
                        max: max
                    },
                },
                hAxis: {
                    title: '{{ trans('ladashboard.month') }}',
                },
            };
            var chart = new google.visualization.LineChart(document.getElementById('lineChartPartByQuizType'));
            chart.draw(data, options);
        }
        function pieChartPartByQuizType() {
            var data = google.visualization.arrayToDataTable(@json($pieChartPartByQuizType));
            var options = {
                title: '',
                pieHole: 0.4,
                pieSliceTextStyle: {
                    color: 'black',
                },
                legend: {position: 'top'},
                pieSliceText: 'value',
            };
            var chart = new google.visualization.PieChart(document.getElementById('pieChartPartByQuizType'));
            chart.draw(data, options);
        }

        function lineChartUserByQuizType() {
            let result = @json($lineChartUserByQuizType);
            var data = google.visualization.arrayToDataTable(
                result['content']
            );

            var max = result['check'] ? '' : 100;
            var options = {
                lineWidth: 3,
                legend: {
                    position: 'right',
                    maxLines: 4,
                },
                chartArea:{
                    left: 30,
                },
                vAxis: {
                    viewWindow: {
                        min: 0,
                        max: max
                    },
                },
                hAxis: {
                    title: '{{ trans('ladashboard.month') }}',
                },
            };
            var chart = new google.visualization.LineChart(document.getElementById('lineChartUserByQuizType'));
            chart.draw(data, options);
        }
        function pieChartUserByQuizType() {
            var data = google.visualization.arrayToDataTable(@json($pieChartUserByQuizType));
            var options = {
                title: '',
                pieHole: 0.4,
                pieSliceTextStyle: {
                    color: 'black',
                },
                legend: {position: 'top'},
                pieSliceText: 'value',
            };
            var chart = new google.visualization.PieChart(document.getElementById('pieChartUserByQuizType'));
            chart.draw(data, options);
        }
    </script>
    <div class="element_data"
        data-user_unit = '{{ session()->get('user_unit') }}'
        data-url_choose_unit_modal = '{{ route('choose_unit_modal') }}'
        data-url_load_unit_modal = '{{ route('load_unit_modal') }}'
    ></div>
    <script src="{{ mix('js/loadModalChooseUnit.js') }}" type="text/javascript"></script>
    <script>
        function showListReport() {
            $('#modal-list-report').modal();
        }
    </script>
@endsection

