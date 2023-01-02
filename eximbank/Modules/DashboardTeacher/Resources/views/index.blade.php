@extends('layouts.backend')

@section('page_title', 'Dashboard')

@section('header')
    <script src="{{asset('styles/vendor/chart/Chart.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('styles/vendor/jqueryplugin/jquery.knob.min.js')}}" type="text/javascript"></script>
{{--    <script src="{{asset('styles/vendor/chart/plugin/chartjs-plugin-labels.min.js')}}" type="text/javascript"></script>--}}
    {{-- <link rel="stylesheet" href="{{ asset('styles/vendor/ionicons/css/ionicons.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('styles/module/dashboard/css/dashboard.css') }}">
@endsection

@section('content')
    <div class="sa4d25 dashboard_teacher">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 p-0">
                    <div class="ibox-content forum-container">
                        <h2 class="st_title"><i class="uil uil-apps"></i>
                            <span class="font-weight-bold">@lang('lamenu.dashboard')</span>
                        </h2>
                    </div>
                </div>
            </div>

            @include('dashboardteacher::course_overview')

            {{-- THỐNG KÊ KHÓA CHƯA GIẢNG TRONG NĂM --}}
            <div class="row">
                <div class="col-lg-8 connectedSortable ui-sortable">
                    <!-- CHART khóa học -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title bg_title p-2">@lang('ladashboard.statistics_unclassified_courses_year')</h3>
                        </div>
                        <div class="box-body p-1">
                            <div class="chart">
                                <canvas id="lineChart" style="height:250px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <p class="text-center">
                        @php
                            $year = date('Y');
                        @endphp
                        <strong class="bg_title p-2">Chỉ tiêu năm {{ $year }}</strong>
                    </p>
                    @if($kpiHoursTeacher > 0 && $kpiCourseTeacher > 0)
                        <div class="progress-group pt-2">
                            <span class="progress-text">{{ trans('latraining.num_course_teacher') }}</span>
                            <span class="progress-number"><b>{{ $countCourse }}</b>/{{ $kpiCourseTeacher }}</span>
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-aqua" style="width: {{ ($countCourse / $kpiCourseTeacher) * 100 }}%"></div>
                            </div>
                        </div>
                        <!-- /.progress-group -->
                        <div class="progress-group pt-2">
                            <span class="progress-text">{{ trans('latraining.num_hour_teacher') }}</span>
                            <span class="progress-number"><b>{{ $totalTimeTaught }}</b>/{{ $kpiHoursTeacher }}</span>
            
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-red" style="width: {{ ($totalTimeTaught / $kpiHoursTeacher) * 100 }}%"></div>
                            </div>
                        </div>
                        <!-- /.progress-group -->
                    @endif
                </div>
            </div>

            {{-- SỐ LỚP VÀ GIỜ GIẢNG THEO TỪNG THÁNG TRONG NĂM --}}
            <div class="row">
                <div class="col-6">
                    <!-- CHART Lớp học -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title bg_title p-2">@lang('ladashboard.number_class_year')</h3>
                        </div>
                        <div class="box-body p-1">
                            <div class="chart">
                                <canvas id="barChartClass" style="height:250px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <!-- CHART giờ giảng -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title bg_title p-2">@lang('ladashboard.number_hours_year')</h3>
                        </div>
                        <div class="box-body p-1">
                            <div class="chart">
                                <canvas id="lineChartHours" style="height:250px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- THỐNG KÊ CHI PHÍ THEO TỪNG THÁNG TRONG NĂM --}}
            <div class="row">
                <div class="col-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title bg_title p-2">@lang('ladashboard.expense_statistics_each_month_year')</h3>
                        </div>
                        <div class="box-body p-1">
                            <div class="chart">
                                <canvas id="barChartCostTeacher" style="height:250px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script>
        var data_course_not_taught_month = @json($courseNotTaughtMonth);
        var data_month_now = @json($monthNow);
        var data_time_taught_month = @json($timeTaughtMonth);
        var data_class_month = @json($classMonth);
        var data_cost_taught_month = @json($costTaughtMonth);
        var course = "@lang('lamenu.course')";
        var hours = "@lang('latraining.hour')";
        var classroom = "@lang('latraining.classroom')";
        var cost = "@lang('lacategory.cost')";
       
        var t1 = "@lang('ladashboard.jan')";
        var t2 = "@lang('ladashboard.feb')";
        var t3 = "@lang('ladashboard.mar')";
        var t4 = "@lang('ladashboard.apr')";
        var t5 = "@lang('ladashboard.may')";
        var t6 = "@lang('ladashboard.jun')";
        var t7 = "@lang('ladashboard.jul')";
        var t8 = "@lang('ladashboard.aug')";
        var t9 = "@lang('ladashboard.sep')";
        var t10 = "@lang('ladashboard.oct')";
        var t11 = "@lang('ladashboard.nov')";
        var t12 = "@lang('ladashboard.dec')";
        
        var lineChartData = {
            labels: data_month_now,
            datasets: [
                {
                    label: course,
                    borderColor: "#FEF200",
                    fill: false,
                    data: data_course_not_taught_month
                },
            ]
        };
        var lineChartOptions = {
            responsive: true,
            legend: {
                position: 'top'
            },
            title: {
                display: false,
                text: 'Thống kê khóa chưa giảng dạy trong năm'
            }
        };
        var canvas = document.getElementById("lineChart");
        var lineChartCanvas = canvas.getContext('2d');
        var lineChart = new Chart(lineChartCanvas, {
            type: 'line',
            data: lineChartData,
            options: lineChartOptions
        });

        // CHART LỚP GIẢNG
        var barChartClass = {
            labels: [t1, t2, t3, t4, t5, t6, t7, t8, t9, t10, t11, t12],
            datasets: [
                {
                    label: classroom,
                    backgroundColor: "#1b4486",
                    fill: false,
                    data: data_class_month
                },
            ]
        };
        var barChartClassOptions = {
            responsive: true,
            legend: {
                position: 'top'
            },
            title: {
                display: false,
                text: 'Số lớp giảng trong năm'
            }
        };
        var canvas = document.getElementById("barChartClass");
        var barChartClassCanvas = canvas.getContext('2d');
        var barChartClassTeacher = new Chart(barChartClassCanvas, {
            type: 'bar',
            data: barChartClass,
            options: barChartClassOptions
        });

        // CHART GIỜ GIẢNG
        var lineChartHours = {
            labels: [t1, t2, t3, t4, t5, t6, t7, t8, t9, t10, t11, t12],
            datasets: [
                {
                    label: hours,
                    borderColor: "#FEF200",
                    fill: false,
                    data: data_time_taught_month
                },
            ]
        };
        var lineChartHoursOptions = {
            responsive: true,
            legend: {
                position: 'top'
            },
            title: {
                display: false,
                text: 'Số giờ giảng trong năm'
            }
        };
        var canvas = document.getElementById("lineChartHours");
        var lineChartHoursCanvas = canvas.getContext('2d');
        var lineChartHoursTeacher = new Chart(lineChartHoursCanvas, {
            type: 'line',
            data: lineChartHours,
            options: lineChartHoursOptions
        });

        // CHART TỔNG CHI PHÍ
        var barChartCost = {
            labels: [t1, t2, t3, t4, t5, t6, t7, t8, t9, t10, t11, t12],
            datasets: [
                {
                    label: cost,
                    backgroundColor: "#1b4486",
                    fill: false,
                    data: data_cost_taught_month
                },
            ]
        };
        var barChartCostOptions = {
            responsive: true,
            legend: {
                position: 'top'
            },
            tooltips: { 
                mode: 'label', 
                label: 'mylabel', 
                callbacks: { 
                    label: function(tooltipItem, data) { 
                        return tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); 
                    }, 
                }, 
            }, 
            scales: { 
                yAxes: [{ 
                    ticks: { 
                        callback: function(label, index, labels) { return label/1000; }, 
                        beginAtZero:true, 
                        fontSize: 10, 
                    }, 
                    gridLines: { 
                        display: false 
                    }, 
                    scaleLabel: {  
                        display: true, 
                        labelString: '000\'vnđ', 
                        fontSize: 10, 
                    } 
                }], 
                xAxes: [{ 
                    ticks: { 
                        beginAtZero: true, 
                        fontSize: 10 
                    }, 
                    gridLines: { 
                        display:false 
                    }, 
                    scaleLabel: { 
                        display: true, 
                        fontSize: 10, 
                    } 
                }] 
            } 
        };
        var canvas = document.getElementById("barChartCostTeacher");
        var barChartCostCanvas = canvas.getContext('2d');
        var barChartCostTeacher = new Chart(barChartCostCanvas, {
            type: 'bar',
            data: barChartCost,
            options: barChartCostOptions
        });
    </script>
@endsection
