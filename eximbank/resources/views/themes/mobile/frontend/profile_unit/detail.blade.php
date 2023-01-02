@extends('themes.mobile.layouts.app')

@section('page_title', 'Tình hình học tập nhân viên')
@section('header')
    <script src="{{asset('styles/vendor/chart/Chart.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('styles/vendor/chart/plugin/chartjs-plugin-labels.min.js')}}" type="text/javascript"></script>
@endsection
@section('content')
    <style>
        .icon {
            width: 45px;
            height: 32px;
            background: white;
        }
    </style>
    <div class="container mt-2">
        <div class="row m-0">
            <div class="col-6 pr-1 pl-0">
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.profile_unit.training_by_title',['user_id' => $profile->user_id]) }}')" class="btn w-100 d_flex_align">
                    <div class="icon" style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-160.svg') }}) no-repeat;
                        mask: url({{ asset('images/svg-backend/svgexport-160.svg') }}) no-repeat;
                        -webkit-mask-size: 45px 28px;">
                    </div>
                    <span>Lộ trình đào tạo</span>
                </a>
            </div>
            <div class="col-6 pl-1 pr-0">
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.profile_unit.training_process',['user_id' => $profile->user_id]) }}')" class="btn w-100 d_flex_align">
                    <div class="icon" style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-162.svg') }}) no-repeat;
                        mask: url({{ asset('images/svg-backend/svgexport-162.svg') }}) no-repeat;
                        -webkit-mask-size: 45px 28px;">
                    </div>
                    <span>Quá trình học</span>
                </a>
            </div>
        </div>
    </div>
    <div class="container mt-2">
        <div class="card shadow overflow-hidden">
            <div class="card-header d_flex_align">
                {{ trans('app.user_info') }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h6 class="mb-1">{{ $profile->full_name .' ('. $profile->code .')' }}</h6>
                        <p class="mb-0" style="font-size: 85%">
                            @lang('lamenu.unit'): {{ $profile->unit_name }}
                            <br>
                            @lang('app.title'): {{ $profile->title_name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--  Biểu đồ tròn khoá online  --}}
    <div class="container mt-2">
        <div class="card shadow overflow-hidden">
            <div class="card-header">
                {{ trans('lacategory.onl_course') }}
                <span class="link_rating float-right">
                    <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.profile_unit.detail_model', ['user_id' => $profile->user_id, 1]) }}')" class="">
                        <span>Chi tiết</span>
                        <i class="material-icons">navigate_next</i>
                    </a>
                </span>
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

    {{--  Biểu đồ tròn khoá offline  --}}
    <div class="container mt-2">
        <div class="card shadow overflow-hidden">
            <div class="card-header">
                {{ trans('lacategory.off_course') }}
                <span class="link_rating float-right">
                    <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.profile_unit.detail_model', ['user_id' => $profile->user_id, 2]) }}')" class="">
                        <span>Chi tiết</span>
                        <i class="material-icons">navigate_next</i>
                    </a>
                </span>
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

    {{--  Biểu đồ tròn kỳ thi  --}}
    <div class="container mt-2">
        <div class="card shadow overflow-hidden">
            <div class="card-header">
                {{ trans('lacategory.quiz') }}
                <span class="link_rating float-right">
                    <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.profile_unit.detail_model', ['user_id' => $profile->user_id, 3]) }}')" class="">
                        <span>Chi tiết</span>
                        <i class="material-icons">navigate_next</i>
                    </a>
                </span>
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

    <div class="element_data"
        data-course_online = {{ implode(',', $pieChartOnlineCourse) }}
        data-course_offline = {{ implode(',', $pieChartOfflineCourse) }}
        data-quiz = {{ implode(',', $pieChartQuiz) }}
    ></div>
@stop

@section('footer')
    <script type="text/javascript">
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
                    position: 'bottom',

                },
                showTooltips: true,
                plugins: {
                    labels: {
                        render: (args) => {
                            return args.value
                        }
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
                    position: 'bottom',

                },
                showTooltips: true,
                plugins: {
                    labels: {
                        render: (args) => {
                            return args.value
                        }
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
                    position: 'bottom',

                },
                showTooltips: true,
                plugins: {
                    labels: {
                        render: (args) => {
                            return args.value
                        }
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
@stop
