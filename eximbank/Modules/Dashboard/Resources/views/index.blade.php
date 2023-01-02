@extends('layouts.backend')

@section('page_title', trans('lamenu.dashboard'))
@section('header')
    <script src="{{asset('styles/vendor/chart/Chart.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('styles/vendor/jqueryplugin/jquery.knob.min.js')}}" type="text/javascript"></script>
{{--    <script src="{{asset('styles/vendor/chart/plugin/chartjs-plugin-labels.min.js')}}" type="text/javascript"></script>--}}
    {{-- <link rel="stylesheet" href="{{ asset('styles/vendor/ionicons/css/ionicons.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('styles/module/dashboard/css/dashboard.css') }}">
    <style>
        .small-box .inner{
            background-size: contain;
        }
        .select2-container .select2-selection--single {
            border-radius: 5px
        }
    </style>
@endsection
@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.statistic'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection
@section('content')
    <div class="row wrapped_box">
        <div class="col-lg-3 col-xs-6 p-1">
            <!-- small box -->
            <div class="small-box">
                <div class="inner" style="background: url({{asset('images/design/online_dashboard.png')}}) no-repeat center;">
                    <a href=" @can('online-course') {{ route('module.online.management') }} @endcan" class="small-box-footer bg-white">
                        <div class="w-60 text-center">
                            <div class="mb-2">@lang('ladashboard.onl_course')</div>
                            <h3>{{ $total_online_course }}</h3>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6 p-1">
            <!-- small box -->
            <div class="small-box">
                <div class="inner" style="background: url({{asset('images/design/offline_dashboard.png')}}) no-repeat center;">
                    <a href="@can('offline-course') {{ route('module.offline.management') }} @endcan" class="small-box-footer bg-white">
                        <div>
                            <div class="mb-2">@lang('ladashboard.off_course')</div>
                            <h3  class="w-50 text-center">{{$total_offline_course}}</h3>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6 p-1">
            <!-- small box -->
            <div class="small-box">
                <div class="inner" style="background: url({{asset('images/design/user_dashboard.png')}}) no-repeat center;">
                    <a href="@can('user') {{ route('module.backend.user') }} @endcan" class="small-box-footer bg-white">
                        <div class="w-50 text-center">
                            <div class="mb-2">@lang('ladashboard.user')</div>
                            <h3>{{$total_users}}</h3>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6 p-1">
            <!-- small box -->
            <div class="small-box">
                <div class="inner" style="background: url({{asset('images/design/quiz_dashboard.png')}}) no-repeat center;">
                    <a href="@can('quiz') {{ route('module.quiz.manager') }} @endcan" class="small-box-footer bg-white">
                        <div class="w-50 text-center">
                            <div class="mb-2">@lang('ladashboard.quiz')</div>
                            <h3>{{$total_quiz}}</h3>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <div class="row">
        <div class="col-lg-7 ui-sortable">
            <!-- CHART User -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title bg_title p-2">@lang('ladashboard.number_monthly_hits')</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="lineChartUser" style="height:250px"></canvas>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <div class="col-md-5">
            <!-- user realtime online -->
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title bg_title p-2">@lang('ladashboard.browser_device')</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="chart-responsive">
                                <canvas id="pieChart" ></canvas>
                            </div>
                            <!-- ./chart-responsive -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3 no-padding">
                            <ul class="chart-legend clearfix">
                                @php
                                    $total_browser = collect($browser_statistic)->pluck('1')->sum();
                                @endphp
                                @foreach ($browser_statistic as $key=>$item)
                                    <li><span class="{{$item[2]}}">{{$item[0]=='Internet Explorer'?'IE':$item[0]}} {{round($item[1]/$total_browser*100,2)}}%</span></li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer no-padding">
                    @php
                        $device_category = collect($device_category);
                        $device_desktop = \App\Models\VisitsStatistic::where('name','=','desktop')->first();
                        $device_mobile = \App\Models\VisitsStatistic::where('name','=','mobile')->first();
                        $device_tablet = \App\Models\VisitsStatistic::where('name','=','tablet')->first();
                    @endphp
                    <ul class="nav nav-pills nav-stacked">
                        <li>
                            <a href="#">@lang('ladashboard.desktop')
                                <span class="pull-right text-blue" >
                                    <i class="fa fa-desktop fa-2x"></i>
                                    {{$total_device>0 ? round((isset($device_desktop) ? $device_desktop->value : 0) / $total_device*100,2) : 0}}%
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">@lang('ladashboard.mobile')
                                <span class="pull-right" style="color:rgba(0,172,95,0.94)">
                                    <i class="fa fa-mobile-alt fa-2x"></i>
                                    {{$total_device>0 ? round((isset($device_mobile) ? $device_mobile->value : 0) / $total_device*100,2):0}}%
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">@lang('ladashboard.tablet')
                                <span class="pull-right " style="color:rgba(249,88,25,0.89)">
                                    <i class="fa fa-tablet-alt fa-2x"></i>
                                    {{$total_device>0 ? round((isset($device_tablet) ? $device_tablet->value : 0) / $total_device*100,2):0}}%
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /.footer -->
            </div>
            <div class="online-realtime pt-2">
                {{$users_online}} online
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9 connectedSortable ui-sortable">
            <!-- CHART khóa học -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title bg_title p-2">@lang('ladashboard.course_statistics')</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="lineChart" style="height:250px"></canvas>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <div class="col-md-3">
            <p class="text-center">
                <strong class="bg_title p-2">@lang('ladashboard.course_statistics')</strong>
            </p>
            @if(isset($course_statistic))
            <div class="progress-group pt-2">
                <span class="progress-text">@lang('ladashboard.course_held')</span>
                <span class="progress-number"><b>{{$course_statistic->course_held}}</b>/{{$course_statistic->course_total}}</span>
                <div class="progress sm">
                    <div class="progress-bar progress-bar-aqua" style="width: {{$course_statistic->course_total>0?($course_statistic->course_held/$course_statistic->course_total*100):0}}%"></div>
                </div>
            </div>
            <!-- /.progress-group -->
            <div class="progress-group pt-2">
                <span class="progress-text">@lang('ladashboard.course_not_held')</span>
                <span class="progress-number"><b>{{$course_statistic->course_not_held}}</b>/{{$course_statistic->course_total}}</span>

                <div class="progress sm">
                    <div class="progress-bar progress-bar-red" style="width: {{$course_statistic->course_total>0?($course_statistic->course_not_held/$course_statistic->course_total*100):0}}%"></div>
                </div>
            </div>
            <!-- /.progress-group -->
            <div class="progress-group pt-2">
                <span class="progress-text">@lang('ladashboard.course_canceled')</span>
                <span class="progress-number"><b>{{$course_statistic->course_deny}}</b>/{{$course_statistic->course_total}}</span>

                <div class="progress sm">
                    <div class="progress-bar progress-bar-green" style="width: {{$course_statistic->course_total>0?($course_statistic->course_deny/$course_statistic->course_total*100):0}}%"></div>
                </div>
            </div>
            <!-- /.progress-group -->
            <div class="progress-group pt-2">
                <span class="progress-text">@lang('ladashboard.course_pending_approval')</span>
                <span class="progress-number"><b>{{$course_statistic->course_pending}}</b>/{{$course_statistic->course_total}}</span>

                <div class="progress sm">
                    <div class="progress-bar progress-bar-yellow" style="width: {{$course_statistic->course_total>0?($course_statistic->course_pending/$course_statistic->course_total*100):0}}%"></div>
                </div>
            </div>
            <!-- /.progress-group -->
            @endif
        </div>
    </div>
    <div class="row course_lastest">
        <div class="col-md-6">
            <!-- Khóa học online news -->
            <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title bg_title p-2">@lang('ladashboard.latest_online_course')</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <ul class="products-list product-list-in-box">
                    @foreach ($lastest_online_course as $item)
                    <li class="item">
                        <div class="">
                            <a href="{{route('module.online.detail_online', ['id' => $item->id])}}" class="product-title">{{$item->name}}</a>
                            <span class="product-description">{{ $item->created_at ? $item->created_at->diffForHumans() : '' }} - @lang('backend.code'): {{$item->code}}</span>
                        </div>
                    </li>
                    @endforeach
                    <!-- /.item -->
                </ul>
            </div>
            <!-- /.box-body -->
        </div>
        </div>
        <div class="col-md-6">
            <!-- Khóa học offline news -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title bg_title p-2">@lang('ladashboard.latest_offline_course')</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <ul class="products-list product-list-in-box">
                        @foreach ($lastest_offline_course as $item)
                        <li class="item">
                            <div class=" ">
                                <a href="{{route('module.offline.detail', ['id' => $item->id])}}" class="product-title">{{$item->name}}</a>
                                <span class="product-description">{{ $item->created_at ? $item->created_at->diffForHumans() : '' }} - @lang('backend.code'): {{$item->code}}</span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 ui-sortable">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title bg_title p-2">@lang('ladashboard.situation_organizing_exam')</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="barChartQuiz" style="height:250px"></canvas>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <div class="col-md-5">
            <!-- Kỳ thi -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title bg_title p-2">@lang('ladashboard.latest_exam')</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <ul class="products-list product-list-in-box">
                        @foreach ($lastest_quiz as $item)
                        <li class="item">
                            <div class=" ">
                                <a href="{{route('module.quiz.edit', ['id' => $item->id])}}" class="product-title">{{$item->name}}</a>
                                <span class="product-description">{{ $item->created_at ? $item->created_at->diffForHumans() : '' }}</span>
                            </div>
                        </li>
                        @endforeach
                        <!-- /.item -->
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>

    {{-- Học viên hoàn thành khóa học --}}
    <div class="row">
        @php
            $month = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
        @endphp
        <div class="col-lg-9 ui-sortable">
            <div class="box box-info">
                <form action="" id="form_search_complete_course">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="box-title bg_title p-2">@lang('ladashboard.students_complete_course')</h3>
                            </div>
                            <div class="col-12">
                                <div class="row m-0">
                                    <div class="col-2 px-1">
                                        <select name="unit_search" class="title_search load-unit form-control" data-placeholder="Đơn vị" onchange="searchCompleteCourse()"></select>
                                    </div>
                                    <div class="col-2 px-1">
                                        <select name="title_rank_search" class="title_search load-title-rank form-control" data-placeholder="Cấp bậc" onchange="searchCompleteCourse()"></select>
                                    </div>
                                    <div class="col-2 px-1">
                                        <select name="title_search" class="title_search load-title form-control" data-placeholder="Chức danh" onchange="searchCompleteCourse()"></select>
                                    </div>
                                    <div class="col-2 px-1">
                                        <select name="subject_search" class="title_search load-subject form-control" data-placeholder="Chuyên đề" onchange="searchCompleteCourse()"></select>
                                    </div>
                                    <div class="col-2 px-1">
                                        <select name="start_month" class="form-control select2" data-placeholder="Tháng bắt đầu" onchange="searchCompleteCourse()">
                                            <option value=""></option>
                                            @foreach ($month as $item)
                                                <option value="{{ $item }}">{{ trans('ladashboard.month') }} {{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2 px-1">
                                        <select name="end_month" class="form-control select2" data-placeholder="Tháng kết thúc" onchange="searchCompleteCourse()">
                                            <option value=""></option>
                                            @foreach ($month as $item)
                                                <option value="{{ $item }}">{{ trans('ladashboard.month') }} {{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="box-body">
                    <div class="chart" id="chart_search">
                        <canvas id="stackedChartQuiz" style="height:250px"></canvas>
                        <div class="draw_canvas">

                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <div class="col-md-3">
            <!-- Kỳ thi -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title bg_title p-2">@lang('ladashboard.completion_rate')</h3>
                </div>
                <!-- /.box-header -->
                <input type="hidden" class="value_finish_old" value="{{($rate_fail>0 || $rate_finish>0)? round($rate_finish/($rate_fail+$rate_finish)*100,0):0}}">
                <input type="hidden" class="value_fail_old" value="{{($rate_fail>0 || $rate_finish>0)? round($rate_fail/($rate_fail+$rate_finish)*100,0):0}}">

                <div class="box-body">
                    <div class="warrped_old_knob row">
                        <div class="col-md-12 text-center">
                            <input type="text" readonly class="knob knob_finish" value="{{($rate_fail>0 || $rate_finish>0)? round($rate_finish/($rate_fail+$rate_finish)*100,0):0}}%" data-width="90" data-height="90" data-fgColor="#50c772" data-readonly="true">
                            <div class="knob-label">@lang('ladashboard.completed')</div>
                        </div>
                        <div class="col-md-12 text-center">
                            <input type="text" readonly class="knob knob_fail" value="{{($rate_fail>0 || $rate_finish>0) ?round($rate_fail/($rate_fail+$rate_finish)*100,0):0}}%" data-width="90" data-height="90" data-fgColor="#E25775" data-readonly="true">
                            <div class="knob-label">@lang('ladashboard.incomplete')</div>
                        </div>
                    </div>
                    <div class="warrped_knob_search row">

                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>

    {{-- THỐNG KÊ KHÓA HỌC ONLINE TRUY CẬP THEO TỪNG THÁNG --}}
    <div class="row">
        <div class="col-lg-9 ui-sortable">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title bg_title p-2">{{trans('ladashboard.online_course_view_summary')}}</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="statistic_access_online" style="height:250px"></canvas>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>

    {{-- THỐNG KÊ TRUY CẬP TIN TỨC, VIDEO, HÌNH ẢNH --}}
    <div class="row">
        <div class="col-lg-9 ui-sortable">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title bg_title p-2">{{trans('ladashboard.photo_video_new_view_summary')}}</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="statistic_access_news" style="height:250px"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title bg_title p-2">{{trans('ladashboard.quatity')}}</h3>
                </div>
                <div class="box-body">
                    <div class="col-md-12 text-center">
                        <div class="row">
                            <div class="col-6">
                                <svg width="80" height="80">
                                    <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                    <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_video_new}}</text>
                                </svg>
                                <div class="knob-label">{{trans('ladashboard.video')}}</div>
                            </div>
                            <div class="col-6">
                                <svg width="80" height="80">
                                    <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                    <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_image_new}}</text>
                                </svg>
                                <div class="knob-label">{{trans('ladashboard.images')}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <div class="row">
                            <div class="col-6">
                                <svg width="80" height="80">
                                    <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                    <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_post_new}}</text>
                                </svg>
                                <div class="knob-label">{{trans('ladashboard.post')}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     {{-- THỐNG KÊ TRUY CẬP DIỄN ĐÀN --}}
     <div class="row">
        <div class="col-lg-9 ui-sortable">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title bg_title p-2">{{trans('ladashboard.forum_access_summary')}}</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="statistic_access_forums" style="height:250px"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title bg_title p-2">{{trans('ladashboard.quatity')}}</h3>
                </div>
                <div class="box-body">
                    <div class="col-md-12 text-center">
                        <div class="row">
                            <div class="col-6">
                                <svg width="80" height="80">
                                    <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                    <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_forum}}</text>
                                </svg>
                                <div class="knob-label">{{trans('ladashboard.subject')}}</div>
                            </div>
                            <div class="col-6">
                                <svg width="80" height="80">
                                    <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                    <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_forum_post}}</text>
                                </svg>
                                <div class="knob-label">{{trans('ladashboard.post')}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <div class="row">
                            <div class="col-6">
                                <svg width="80" height="80">
                                    <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                    <text class="convert_forum_comment" fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_forum_comment}}</text>
                                </svg>
                                <div class="knob-label">{{trans('ladashboard.comment')}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- THỐNG KÊ TRUY CẬP, SỐ LƯỢNG TÀI LIỆU, EBOOK, AUDIO, SÁCH GIẤY, VIDEO --}}
    <div class="row">
        <div class="col-lg-9 ui-sortable">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title bg_title p-2">{{trans('ladashboard.ebook_audio_video_summary')}}</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="statistic_access_libraries" style="height:250px"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title bg_title p-2">{{trans('ladashboard.quatity')}}</h3>
                </div>
                <div class="box-body">
                    <div class="col-md-12 text-center">
                        <div class="row">
                            <div class="col-6">
                                <svg width="80" height="80">
                                    <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                    <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_video_libraries}}</text>
                                </svg>
                                <div class="knob-label">{{ trans('ladashboard.video') }}</div>
                            </div>
                            <div class="col-6">
                                <svg width="80" height="80">
                                    <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                    <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_book_libraries}}</text>
                                </svg>
                                <div class="knob-label">{{trans('ladashboard.book')}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <div class="row">
                            <div class="col-6">
                                <svg width="80" height="80">
                                    <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                    <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_document_libraries}}</text>
                                </svg>
                                <div class="knob-label">{{trans('ladashboard.document')}}</div>
                            </div>
                            <div class="col-6">
                                <svg width="80" height="80">
                                    <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                    <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_audio_libraries}}</text>
                                </svg>
                                <div class="knob-label">{{trans('ladashboard.audio')}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <div class="row">
                            <div class="col-6">
                                <svg width="80" height="80">
                                    <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                    <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_ebook_libraries}}</text>
                                </svg>
                                <div class="knob-label">{{trans('ladashboard.ebook')}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- THỐNG KÊ TỔNG SỐ GIỜ HỌC THEO CHỨC DANH KPI --}}
    <div class="row">
        <div class="col-lg-12 ui-sortable">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title bg_title p-2">{{trans('ladashboard.dashboard_title_time')}}</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="title_time_kpi" style="height:250px"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var num = $('.convert_forum_comment').text();
        if(num > 999 && num < 1000000){
            var convert_num = (num/1000).toFixed(1) + 'K'; // convert to K for number from > 1000 < 1 million
            $('.convert_forum_comment').html(convert_num);
        }else if(num > 1000000){
            var convert_num = (num/1000000).toFixed(1) + 'M'; // convert to M for number from > 1 million
            $('.convert_forum_comment').html(convert_num);
        }else if(num < 900){
            var convert_num = num; // if value < 1000, nothing to do
            $('.convert_forum_comment').html(convert_num);
        }

        var checkNightMode = "{{ (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 1 : 0 }}";
        var colorLabel = checkNightMode == 1 ? '#dee2e6' : '#333';

        var data_course_online = @json($static_online_course_y);
        var data_course_offline = @json($static_offline_course_y);
        var data_quiz = @json($statistic_quiz);
        var data_course_result_finish = @json($course_result_finish_statistic);
        var data_course_result_fail = @json($course_result_fail_statistic);
        var data_device_category = @json($device_category->pluck('1'));
        var data_visit_statistic = @json($visit_statistic);
        var data_visit_statistic_online = @json($data_visit_statistic_online);
        var data_visit_statistic_news = @json($data_visit_statistic_news);
        var data_visit_statistic_libraries = @json($data_visit_statistic_libraries);
        var data_visit_statistic_forums = @json($data_visit_statistic_forums);

        var offline_course = "@lang('ladashboard.off_course')";
        var online_course = "@lang('ladashboard.onl_course')";
        var completed = "@lang('ladashboard.completed')";
        var incomplete = "@lang('ladashboard.incomplete')";
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

        var nameTitleTime = @json($nameTitleTime);
        var totalTimeTitle = @json($totalTimeTitle);

        function searchCompleteCourse() {
            $('#chart_search').find('#stackedChartQuiz').hide();
            $('#chart_search').find('.draw_canvas').html('');
            $('#chart_search').find('.draw_canvas').append('<canvas id="stackedChartQuizSearch" style="height:250px"></canvas>');
            $('.warrped_old_knob').hide();
            $('.warrped_knob_search').html('');
            $('.warrped_knob_search').append(`<div class="col-12 text-center">
                                                <canvas id="completion_rate_1" class="m-auto" style="height:100px; width:100px;"></canvas>
                                                <div class="knob-label">@lang('ladashboard.completed')</div>
                                            </div>
                                            <div class="col-12 text-center">
                                                <canvas id="completion_rate_2" class="m-auto" style="height:100px; width:100px;"></canvas>
                                                <div class="knob-label">@lang('ladashboard.incomplete')</div>
                                            </div>`);
            var chartCanvas = document.getElementById("stackedChartQuizSearch");
            $.ajax({
                type: "POST",
                url: "{{ route('module.dashboard.search_course_complete') }}",
                data: $('#form_search_complete_course').serialize(),
                success: function (result) {
                    if (result.checkSearch == 0) {
                        $('#chart_search').find('#stackedChartQuiz').show();
                        $('#chart_search').find('.draw_canvas').html('');
                        $('.warrped_old_knob').show();
                        $('.warrped_knob_search').html('');
                    } else {
                        drawChartKnob(result.totalRateFinish, result.totalRateFail)
                        var myUChart = new Chart(chartCanvas, {
                            type: "bar",
                            data: {
                                labels: [t1, t2, t3, t4, t5, t6, t7, t8, t9, t10, t11, t12],
                                datasets: [
                                    {
                                        label: completed,
                                        backgroundColor: "#1b4486",
                                        data: result.totalCourseFinishArray,
                                    },
                                    {
                                        label: incomplete,
                                        backgroundColor: "#FEF200",
                                        data: result.totalCourseNotFailArray,
                                    },
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                legend: {
                                    labels: {
                                        fontColor: colorLabel
                                    },
                                    display: true
                                },
                                scales: {
                                    yAxes: [
                                        {
                                            ticks: {
                                                beginAtZero: true,
                                            }
                                        }
                                    ]
                                },
                            }
                        });
                    }
                }
            });
        }

        function drawChartKnob(totalRateFinish, totalRateFail) {
            for (var index = 1; index < 3; index++) {
                var completionRate = document.getElementById("completion_rate_"+ index);
                var percent = index == 1 ? totalRateFinish : totalRateFail;
                var backgroundColor = index == 1 ? '#50c772' : "#E25775";
                var label = index == 1 ? completed : incomplete;
                var myChartCircle = new Chart(completionRate, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            label: label,
                            percent: percent,
                            backgroundColor: [backgroundColor]
                        }]
                    },
                    plugins: [{
                        beforeInit: function beforeInit(chart) {
                            var dataset = chart.data.datasets[0];
                            chart.data.labels = [dataset.label];
                            dataset.data = [dataset.percent, 100 - dataset.percent];
                        }
                    }, {
                        beforeDraw: function beforeDraw(chart) {
                            var width = chart.chart.width,
                                height = chart.chart.height,
                                ctx = chart.chart.ctx;
                            ctx.restore();
                            var fontSize = (height / 100).toFixed(2);
                            ctx.font = fontSize + "em sans-serif";
                            ctx.fillStyle = colorLabel;
                            ctx.textBaseline = "middle";
                            var text = parseFloat(chart.data.datasets[0].percent).toFixed(1) + "%",
                                textX = Math.round((width - ctx.measureText(text).width) / 2),
                                textY = height / 2;
                            ctx.fillText(text, textX, textY);
                            ctx.save();
                        }
                    }],
                    options: {
                        responsive: false,
                        legend: {
                            labels: {
                                fontColor: colorLabel
                            },
                            display: false
                        },
                        hover: {
                            mode: null
                        },
                        tooltips: {
                            enabled: false
                        }
                    }
                });
            }
        }

    </script>
    <script src="{{ Module::asset('dashboard:js/app.js?v=1') }}" type="text/javascript"></script>
@endsection
