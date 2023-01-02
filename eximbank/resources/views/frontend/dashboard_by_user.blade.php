@extends('layouts.app')

@section('page_title', trans('laother.title_project'))

@section('header')
    <script src="{{ asset('js/charts-loader.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <div class="sa4d25 dashboard_user">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 p-0">
                    <div class="ibox-content forum-container">
                        <h2 class="st_title">
                            <a href="/">
                                <i class="uil uil-apps"></i>
                                <span>{{ trans('lamenu.home_page') }}</span>
                            </a>
                            <i class="uil uil-angle-right"></i>
                            <span class="font-weight-bold">{{ trans('ladashboard.my_dashboard') }}</span>
                        </h2>
                    </div>
                </div>
            </div>

            {{--Khóa học Elearning: Hoàn thành sớm--}}
            <div class="row">
                <div class="col-12 p-0">
                    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
                        <div class="card-header">
                            <h2>{{ trans('ladashboard.elearning_course') }}: {{ trans('ladashboard.finished_soon') }}</h2>
                        </div>
                        <div class="card-body p-1">
                            <table class="tDefault table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ trans('latraining.course_name') }}</th>
                                        <th class="text-center">{{ trans('ladashboard.your_result') }}</th>
                                        <th class="text-center">{{ trans('ladashboard.first_time_login') }}</th>
                                        <th class="text-center">{{ trans('ladashboard.study_start_time') }}</th>
                                        <th class="text-center">{{ trans('ladashboard.last_time_finished') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ @$online_course->name }}</td>
                                        <td class="text-center">{{ $online_result ? trans('latraining.completed') : '' }}</td>
                                        <td class="text-center">{{ $online_history_min ? get_date(@$online_history_min->created_at) : '' }}</td>
                                        <td class="text-center">{{ $online_history_min ? get_date(@$online_history_min->created_at, 'H:i:s d/m/Y') : '' }}</td>
                                        <td class="text-center">{{ $online_result ? get_date(@$online_result->updated_at, 'H:i:s d/m/Y') : '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{--Bạn đã tham gia …..  khóa học trực tuyến trong năm--}}
            <div class="row">
                <div class="col-12 p-0">
                    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
                        <div class="card-header">
                            <h2>{{ trans('ladashboard.you_join') }} {{ $count_online_register_by_year }} {{ trans('ladashboard.online_course_year') }} {{ $year_dashboard_online_register }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            {{--Các hoạt động bạn đã tham gia: (Đếm số thôi) (Trong năm)--}}
            @php
                $percent_online = ($dashboard_activity_joined['online']/$dashboard_activity_joined['total'])*100;
                $percent_offline = ($dashboard_activity_joined['offline']/$dashboard_activity_joined['total'])*100;
                $percent_survey = ($dashboard_activity_joined['survey']/$dashboard_activity_joined['total'])*100;
                $percent_quiz = ($dashboard_activity_joined['quiz']/$dashboard_activity_joined['total'])*100;
                $percent_forum_thread = ($dashboard_activity_joined['forum_thread']/$dashboard_activity_joined['total'])*100;
                $percent_training_video = ($dashboard_activity_joined['training_video']/$dashboard_activity_joined['total'])*100;
                $percent_ebook = ($dashboard_activity_joined['ebook']/$dashboard_activity_joined['total'])*100;
            @endphp
            <div class="row">
                <div class="col-12 p-0">
                    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
                        <div class="card-header">
                            <h2>{{ trans('ladashboard.activity_you_join') }} {{ $dashboard_activity_joined['year'] }}</h2>
                        </div>
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-md-2 col-4 text-right">
                                    {{ $dashboard_activity_joined['online'] }} {{ trans('lamenu.online_course') }}
                                </div>
                                <div class="col-md-10 col-8 pl-0">
                                    <div class="progress progress2">
                                        <div class="progress-bar" role="progressbar" style="background-color: #8b1409 !important; width: {{ $percent_online }}%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                            {{ number_format($percent_online, 2) .'%' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-4 text-right">
                                    {{ $dashboard_activity_joined['offline'] }} {{ trans('lamenu.offline_course') }}
                                </div>
                                <div class="col-md-10 col-8 pl-0">
                                    <div class="progress progress2">
                                        <div class="progress-bar" role="progressbar" style="background-color: #FEF200 !important; width: {{ $percent_offline }}%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                            {{ number_format($percent_offline, 2) .'%' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-4 text-right">
                                    {{ $dashboard_activity_joined['survey'] }} {{ trans('lamenu.survey') }}
                                </div>
                                <div class="col-md-10 col-8 pl-0">
                                    <div class="progress progress2">
                                        <div class="progress-bar" role="progressbar" style="background-color: #00ff80 !important; width: {{ $percent_survey }}%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                            {{ number_format($percent_survey, 2) .'%' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-4 text-right">
                                    {{ $dashboard_activity_joined['quiz'] }} {{ trans('lamenu.quiz') }}
                                </div>
                                <div class="col-md-10 col-8 pl-0">
                                    <div class="progress progress2">
                                        <div class="progress-bar" role="progressbar" style="background-color: #1ce3c0 !important; width: {{ $percent_quiz }}%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                            {{ number_format($percent_quiz, 2) .'%' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-4 text-right">
                                    {{ $dashboard_activity_joined['forum_thread'] }} {{ trans('lamenu.forum') }}
                                </div>
                                <div class="col-md-10 col-8 pl-0">
                                    <div class="progress progress2">
                                        <div class="progress-bar" role="progressbar" style="background-color: #0080ff !important; width: {{ $percent_forum_thread }}%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                            {{ number_format($percent_forum_thread, 2) .'%' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-4 text-right">
                                    {{ $dashboard_activity_joined['training_video'] }} {{ trans('lamenu.training_video') }}
                                </div>
                                <div class="col-md-10 col-8 pl-0">
                                    <div class="progress progress2">
                                        <div class="progress-bar" role="progressbar" style="background-color: #004080 !important; width: {{ $percent_training_video }}%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                            {{ number_format($percent_training_video, 2) .'%' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-4 text-right">
                                    {{ $dashboard_activity_joined['ebook'] }} Ebook
                                </div>
                                <div class="col-md-10 col-8 pl-0">
                                    <div class="progress progress2">
                                        <div class="progress-bar" role="progressbar" style="background-color: #008080 !important; width: {{ $percent_ebook }}%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                            {{ number_format($percent_ebook, 2) .'%' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--Bạn đã có ... bài viết--}}
            <div class="row">
                <div class="col-12 p-0">
                    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
                        <div class="card-header">
                            <h2>{{ trans('ladashboard.you_had') }} {{ $count_forum_thread }} {{ trans('ladashboard.post_year') }} {{ $year_dashboard_user_has_post }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            {{--Bài viết bạn đã có …. người like--}}
            <div class="row">
                <div class="col-12 p-0">
                    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
                        <div class="card-header">
                            <h2>{{ trans('ladashboard.post_you_have') }} {{ $count_forum_thread_like }} {{ trans('ladashboard.liker_year') }} {{ $year_dashboard_user_post_with_like }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            {{---Bài post được like nhiều hơn--}}
            @if($forum_thread_like_more)
            <div class="row">
                <div class="col-12 p-0">
                    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
                        <div class="card-header">
                            <h2>{{ trans('ladashboard.post_most_like_year') }} {{ $year_dashboard_you_post_liked_more }}</h2>
                        </div>
                        <div class="card-body p-1">
                            <div class="pl-2">
                                <h6>{{ @$forum_thread_like_more->title }}</h6>
                                <p>
                                    {{ @$forum_thread_like_more->total_like }} <i class="fa fa-thumbs-up"></i>
                                </p>
                                <p>
                                    {!! @$forum_thread_like_more->content !!}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{--Trong Quý …, bạn đã tham gia hơn…. Khóa học trực tuyến (Quý 1: 3 tháng 1 quý)--}}
            <div class="row">
                <div class="col-12 p-0">
                    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
                        <div class="card-header">
                            <h2>{{ trans('ladashboard.during_quater') }} {{ $quarter }}, {{ trans('ladashboard.you_have_join') }} {{ $count_register_by_quarter }} {{ trans('lamenu.online_course') }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            {{--Bạn thuộc Top học viên năng động trong …. Thành viên trong phòng Ban của bạn--}}
            <div class="row">
                <div class="col-12 p-0">
                    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
                        <div class="card-header">
                            <h2>{{ trans('ladashboard.you_top_active') }} {{ $count_result }} {{ trans('ladashboard.member_deparment_year') }} {{ $year_dashboard_top_in_unit }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            {{--Nằm trong Top ... học viên xuất sắc trong năm--}}
            @if ($top_user)
            <div class="row">
                <div class="col-12 p-0">
                    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
                        <div class="card-header">
                            <h2>{{ trans('ladashboard.in_top') }} {{ @$top_user }} {{ trans('ladashboard.excellent_student_year') }} {{ @$year_dashboard_top_user }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{--Đây là biểu đồ tương tác của bạn--}}
            <div class="row">
                <div class="col-12 p-0">
                    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
                        <div class="card-header">
                            <h2>{{ trans('ladashboard.your_interactive_chart') }}</h2>
                        </div>
                        <div class="card-body p-1" style="height: 450px;">
                            <canvas id="barChart" class="chartjs"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 p-0">
                    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
                        <div class="card-header">
                            <h2>{{ trans('ladashboard.you_are_login') }} {{ $history_login }} {{ trans('ladashboard.time_past_year') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 p-0">
                    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
                        <div class="card-header">
                            <h2>{{ trans('ladashboard.you_got') }} {{ $notify_count_user->num_notify }} {{ trans('ladashboard.notificate_system') }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            {{--Cảm ơn Bạn…--}}
            <div class="row">
                <div class="col-12 p-0">
                    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
                        <div class="card-header">
                            <h2 class="text-center">{{ trans('ladashboard.thank_you') }} {{ $full_name }} {{ trans('ladashboard.note_thank_user') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
    var cUser = document.getElementById("barChart");
    if (cUser !== null) {
        var myUChart = new Chart(cUser, {
            type: "bar",
            data: {
                labels: [
                    "{{ __('ladashboard.jan') }}",
                    "{{ __('ladashboard.feb') }}",
                    "{{ __('ladashboard.mar') }}",
                    "{{ __('ladashboard.apr') }}",
                    "{{ __('ladashboard.may') }}",
                    "{{ __('ladashboard.jun') }}",
                    "{{ __('ladashboard.jul') }}",
                    "{{ __('ladashboard.aug') }}",
                    "{{ __('ladashboard.sep') }}",
                    "{{ __('ladashboard.oct') }}",
                    "{{ __('ladashboard.nov') }}",
                    "{{ __('ladashboard.dec') }}",
                ],
                datasets: [
                    {
                        label: "{{ __('ladashboard.onl_course') }}",
                        data: [{{ implode(',',$chart['online']) }}],
                        backgroundColor: "#8b1409",
                    },
                    {
                        label: "{{ __('ladashboard.off_course') }}",
                        data: [{{ implode(',',$chart['offline']) }}],
                        backgroundColor: "#FEF200"
                    },
                    {
                        label: "{{ trans('lamenu.survey') }}",
                        data: [{{ implode(',',$chart['survey']) }}],
                        backgroundColor: "#00ff80"
                    },
                    {
                        label: "{{ trans('lamenu.quiz') }}",
                        data: [{{ implode(',',$chart['quiz']) }}],
                        backgroundColor: "#1ce3c0"
                    },
                    {
                        label: "{{ trans('lamenu.forum') }}",
                        data: [{{ implode(',',$chart['forum_thread']) }}],
                        backgroundColor: "#0080ff"
                    },
                    {
                        label: "{{ trans('ladashboard.training_video') }}",
                        data: [{{ implode(',',$chart['training_video']) }}],
                        backgroundColor: "#004080"
                    },
                    {
                        label: "Ebook",
                        data: [{{ implode(',',$chart['ebook']) }}],
                        backgroundColor: "#008080"
                    },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true
                },
                scales: {
                    xAxes: [
                        {
                            gridLines: {
                                drawBorder: true,
                                display: true,
                            },
                            ticks: {
                                fontColor: "#686f7a",
                                fontFamily: "Roboto, sans-serif",
                                display: true, // hide main x-axis line
                                beginAtZero: true,
                                // callback: function(tick, index, array) {
                                //     return index % 2 ? "" : tick;
                                // }
                            },
                            barPercentage: 1,
                            categoryPercentage: 0.5
                        }
                    ],
                    yAxes: [
                        {
                            gridLines: {
                                drawBorder: true,
                                display: true,
                                color: "#efefef",
                                zeroLineColor: "#efefef"
                            },
                            ticks: {
                                fontColor: "#686f7a",
                                fontFamily: "Roboto, sans-serif",
                                display: true,
                                beginAtZero: true,
                            },
                        }
                    ]
                },

                tooltips: {
                    mode: "index",
                    titleFontColor: "#333",
                    bodyFontColor: "#686f7a",
                    titleFontSize: 12,
                    bodyFontSize: 14,
                    backgroundColor: "rgba(256,256,256,0.95)",
                    displayColors: true,
                    xPadding: 10,
                    yPadding: 7,
                    borderColor: "rgba(220, 220, 220, 0.9)",
                    borderWidth: 2,
                    caretSize: 6,
                    caretPadding: 5
                }
            }
        });
    }
</script>
@endsection
