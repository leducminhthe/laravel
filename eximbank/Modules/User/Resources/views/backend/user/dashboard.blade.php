@extends('layouts.backend')

@section('page_title', trans('app.dashboard'))
@section('header')
    <script src="{{asset('styles/vendor/chart/Chart.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('styles/js/google_chart.js')}}" type="text/javascript"></script>
    <script src="{{asset('styles/vendor/jqueryplugin/jquery.knob.min.js')}}" type="text/javascript"></script>
    <link rel="stylesheet" href="{{ asset('styles/vendor/ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/module/dashboard/css/dashboard.css') }}">
    <script src="{{asset('styles/vendor/chart/plugin/chartjs-plugin-labels.min.js')}}" type="text/javascript"></script>
    <style>
        .select2-container--default .select2-selection--single,
        .select2-selection .select2-selection--single{
            padding: 2px 12px;
        }

        .small-box .inner{
            background-size: contain;
        }
        .wrapped_chart_course canvas {
            max-height: 400px;
            margin: auto;
        }
        .course_name_chart {
            color: #17a2b8 !important;
        }

        .studying {
            background: #f05555b4;
            color: white;
        }
        .not_learned {
            background: #ebdf0393;
            color: white;
        }
        .completed {
            background: #74d30886;
            color: white;
        }
        .uncomplete {
            background: #63aaf188;
            color: white;
        }

        #bootstraptableOnline .bootstrap-table .fixed-table-container .fixed-table-body,
        #bootstraptableOffline .bootstrap-table .fixed-table-container .fixed-table-body,
        #bootstraptableQuiz .bootstrap-table .fixed-table-container .fixed-table-body
        {
            max-height: 400px !important;
        }
        .bootstrap-table .fixed-table-container .table thead th .th-inner {
            padding: 0.3rem !important;
        }
        .table > thead > tr > th {
            font-weight: unset;
        }
    </style>
@endsection
@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.user'),
                'url' => route('module.backend.user')
            ],
            [
                'name' => $full_name .': '. trans('app.dashboard'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="col-12 mb-3 wrapped_chart_course">
            <div class="shadow-sm mb-3 row mt-2">
                <div class="col-6">
                    <div class="card shadow overflow-hidden">
                        <div class="card-header p-1">
                            {{ trans('lacategory.onl_course') }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <canvas id="chart_course_online" style="height:100px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <table id="bootstraptable_1" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{ route('module.backend.user.dashboard.data_online', ['user_id' => $user_id]) }}" >
                        <thead>
                            <tr class="tbl-heading">
                                <th data-formatter="info_formatter">{{ trans('app.info') }}</th>
                                <th data-formatter="status_formatter" data-align="center" data-width="5%">{{ trans('app.status') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <div class="shadow-sm mb-3 row mt-2">
                <div class="col-6">
                    <div class="card shadow overflow-hidden">
                        <div class="card-header p-1">
                            {{ trans('lacategory.off_course') }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <canvas id="chart_course_offline" style="height:100px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <table id="bootstraptable_2" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{ route('module.backend.user.dashboard.data_offline', ['user_id' => $user_id]) }}" >
                        <thead>
                            <tr class="tbl-heading">
                                <th data-formatter="info_formatter">{{ trans('app.info') }}</th>
                                <th data-formatter="status_formatter" data-align="center" data-width="5%">{{ trans('app.status') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <div class="shadow-sm mb-3 row mt-2">
                <div class="col-6">
                    <div class="card shadow overflow-hidden">
                        <div class="card-header p-1">
                            {{ trans('lacategory.quiz') }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <canvas id="chart_quiz" style="height:100px"></canvas>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <table id="bootstraptable_3" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{ route('module.backend.user.dashboard.data_quiz', ['user_id' => $user_id]) }}" >
                        <thead>
                            <tr class="tbl-heading">
                                <th data-formatter="info_formatter">{{ trans('app.info') }}</th>
                                <th data-formatter="status_formatter" data-align="center" data-width="5%">{{ trans('app.status') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="element_data"
            data-course_online = {{ implode(',', $pieChartOnlineCourse) }}
            data-course_offline = {{ implode(',', $pieChartOfflineCourse) }}
            data-quiz = {{ implode(',', $pieChartQuiz) }}
        ></div>
    </div>

    <script type="text/javascript">
        function info_formatter(value, row, index) {
            return (index + 1) +'./ '+ row.name + '<br>' + row.code + '<br>' + row.time;
        }
        function status_formatter(value, row, index){
            return '<div style="height: 30px; justify-content: center;" class="w-100 d-flex align-items-center '+row.bg_color+'">'+ row.percent +'</div>';
        }

        var table_1 = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: $('#bootstraptable_1').data('url'),
            table: '#bootstraptable_1',
        });
        var table_2 = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: $('#bootstraptable_2').data('url'),
            table: '#bootstraptable_2',
        });
        var table_3 = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: $('#bootstraptable_3').data('url'),
            table: '#bootstraptable_3',
        });

        var colorLabel = '#333';

        //Biểu đồ tròn khoá online
            var get_data_course_online = $('.element_data').attr('data-course_online');
            var get_data_course_online_array = get_data_course_online.split(",");

            var chart_course_online = document.getElementById("chart_course_online").getContext('2d');
            var data_chart_course_online = {
                labels: ['{{ trans("laother.studying") }}', '{{ trans("ladashboard.not_learned") }}', '{{ trans("latraining.completed") }}', '{{ trans("ladashboard.uncomplete") }}'],
                datasets: [{
                    backgroundColor: [
                        '#F05555',
                        "#f9ee1a",
                        "#76C123",
                        "#99ccff",
                    ],
                    fill: false,
                    data: get_data_course_online_array,
                }]
            };
            var options_chart_course_online = {
                legend: {
                    labels: {
                        fontColor: colorLabel,
                        fontSize: 13
                    },
                    display: true,
                    position: 'right',

                },
                showTooltips: true,
                plugins: {
                    labels: {
                        render: (args) => {
                            return args.value
                        },
                        fontColor: '#fff',
                        fontSize: 20
                    }
                }
            };
            var chartOnlineCourse = new Chart(chart_course_online, {
                type: 'pie',
                data: data_chart_course_online,
                options: options_chart_course_online
            });
        ////////////////////////////////////////////////////////

        //Biểu đồ tròn khoá offline
            var get_data_course_offline = $('.element_data').attr('data-course_offline');
            var get_data_course_offline_array = get_data_course_offline.split(",");

            var chart_course_offline = document.getElementById("chart_course_offline").getContext('2d');
            var data_chart_course_offline = {
                labels: ['{{ trans("laother.studying") }}', '{{ trans("ladashboard.not_learned") }}', '{{ trans("latraining.completed") }}', '{{ trans("ladashboard.uncomplete") }}'],
                datasets: [{
                    backgroundColor: [
                        '#F05555',
                        "#f9ee1a",
                        "#76C123",
                        "#99ccff",
                    ],
                    fill: false,
                    data: get_data_course_offline_array,
                }]
            };
            var options_chart_course_offline = {
                legend: {
                    labels: {
                        fontColor: colorLabel,
                        fontSize: 13
                    },
                    display: true,
                    position: 'right',

                },
                showTooltips: true,
                plugins: {
                    labels: {
                        render: (args) => {
                            return args.value
                        },
                        fontColor: '#fff',
                        fontSize: 20
                    }
                }
            };
            var chartOfflineCourse = new Chart(chart_course_offline, {
                type: 'pie',
                data: data_chart_course_offline,
                options: options_chart_course_offline
            });
        ////////////////////////////////////////////////////////

        //Biểu đồ tròn khoá offline
            var get_data_quiz = $('.element_data').attr('data-quiz');
            var get_data_quiz_array = get_data_quiz.split(",");

            var chart_quiz = document.getElementById("chart_quiz").getContext('2d');
            var data_chart_quiz = {
                labels: ['Chưa thi', '{{ trans("latraining.completed") }}', '{{ trans("ladashboard.uncomplete") }}'],
                datasets: [{
                    backgroundColor: [
                        "#f9ee1a",
                        "#76C123",
                        "#99ccff",
                    ],
                    fill: false,
                    data: get_data_quiz_array,
                }]
            };
            var options_chart_quiz = {
                legend: {
                    labels: {
                        fontColor: colorLabel,
                        fontSize: 13
                    },
                    display: true,
                    position: 'right',

                },
                showTooltips: true,
                plugins: {
                    labels: {
                        render: (args) => {
                            return args.value
                        },
                        fontColor: '#fff',
                        fontSize: 20
                    }
                }
            };
            var chartOfflineCourse = new Chart(chart_quiz, {
                type: 'pie',
                data: data_chart_quiz,
                options: options_chart_quiz
            });
        ////////////////////////////////////////////////////////
    </script>

@endsection
