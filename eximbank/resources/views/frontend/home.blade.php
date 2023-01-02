@extends('layouts.app')

@section('page_title', trans('laother.title_project'))

@section('header')
    <script src="{{ asset('js/charts-loader.js') }}" type="text/javascript"></script>
    <script src="{{asset('styles/vendor/chart/plugin/chartjs-plugin-labels.min.js')}}" type="text/javascript"></script>
@endsection

@section('content')
    <div class="sa4d25 dashboard_user ">
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

            @include('data.course_overview')
            <div class="row">
                <div class="col-12 p-0">
                    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
                        <div class="card-header">
                            <h2>{{ trans('latraining.course_in_out_training_roadmap') }}</h2>
                        </div>
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-12 col-md-5">
                                    <canvas id="course_in_out_training_roadmap" class="chartjs"></canvas>
                                </div>
                                <div class="col-12 col-md-7">
                                    <span class="p-2">{{ trans('latraining.target_year', ['year' => date('Y')]) }}</span>
                                    <div class="pt-2">
                                        <p>
                                            {{ trans('latraining.num_hour_student') }}:
                                            <span class="h5">{{ $totalTime .'/'. $target_manager_by_year['num_hour_student'] }}</span>
                                            {{ trans('latraining.hour') }}
                                        </p>
                                        <p>
                                            {{ trans('latraining.num_course_student') }}:
                                            <span class="h5">{{ $count_complete_course_by_user .'/'. $target_manager_by_year['num_course_student'] }}</span>
                                            {{ trans('latraining.course') }}
                                        </p>
                                        @if ($is_teacher)
                                            <p>
                                                {{ trans('latraining.num_hour_teacher') }}:
                                                <span class="h5">{{ $total_hour_teacher .'/'. $target_manager_by_year['num_hour_teacher'] }}</span>
                                                {{ trans('latraining.hour') }}
                                            </p>
                                            <p>
                                                {{ trans('latraining.num_course_teacher') }}:
                                                <span class="h5">{{ $total_course_teacher .'/'. $target_manager_by_year['num_course_teacher'] }}</span>
                                                {{ trans('latraining.course') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 p-0">
                    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
                        <div class="card-header">
                            <h2>{{ trans('ladashboard.header_user_complete_course', ['count_complete_course_by_user' => $count_complete_course_by_user, 'count_register_course_by_user' => $count_register_course_by_user]) }}</h2>
                        </div>
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-12 col-md-5">
                                    <canvas id="chart_course_by_user" class="chartjs"></canvas>
                                </div>
                                <div class="col-12 col-md-7">
                                    <span class="p-2">{{ trans('ladashboard.course_new') }}</span>
                                    @foreach($get_course_new as $item)
                                        @php
                                            if ($item->type == 1){
                                                $route = route('module.online.detail_online', ['id' => $item->id]);
                                                $type = 'Online';
                                                $img = asset('images/dashboard/graduation-cap.svg');
                                            }else{
                                                $route = route('module.offline.detail', ['id' => $item->id]);
                                                $type = 'Offline';
                                                $img = asset('images/dashboard/training.svg');
                                            }
                                        @endphp
                                        <div class="new_links10">
                                            <div class="row">
                                                <div class="col-2">
                                                    @if($item->image)
                                                        <img src="{{ image_file($item->image) }}" alt="" class="w-100">
                                                    @else
                                                        <img src="{{ $img }}" alt="" class="w-100">
                                                    @endif
                                                </div>
                                                <div class="col-10">
                                                    <a href="{{ $route }}">{{ $item->name }}</a> <br>
                                                    ({{ $item->code }}) <br>
                                                    @lang('ladashboard.time'): {{ get_date($item->start_date) . ($item->end_date ? " - " .get_date($item->end_date) : "") }}
                                                    <span class="float-right">{{ $type }}</span>
                                                    <br>
                                                    @lang('app.register_deadline'): {{ get_date($item->register_deadline) }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 p-0">
                    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
                        <div class="card-header">
                            @php
                                $level_subject_name = '';
                                    foreach($getLevelSubjectByUser as $key => $item){
                                        $count_subject = $count_subject_by_level_subject($item->id);
                                        $count_subject_complete = $count_subject_by_level_subject($item->id, 1);

                                        $level_subject_name .= ($key > 0 ? ', ' : '').($count_subject_complete .'/'. $count_subject.' chuyên đề '. $item->name);
                                    }
                            @endphp
                            <h2>
                                {{ trans('ladashboard.header_user_complete_subject', ['count_complete_subject_by_user' => $count_complete_subject_by_user, 'count_register_subject_by_user' => $count_register_subject_by_user, 'level_subject_name' => ($level_subject_name ? '(' . $level_subject_name .')' : '')]) }}
                            </h2>
                        </div>
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <canvas id="chart_subject_by_user" class="chartjs"></canvas>
                                </div>
                                <div class="col-12 col-md-6">
                                    <span class="p-2">{{ trans('ladashboard.subject_by_training_roadmap') }}</span>
                                    <table id="tableroadmap" class="table table-bordered bootstrap-table table-striped mt-2" style="table-layout: fixed">
                                        <thead>
                                        <tr class="tbl-heading">
                                            <th data-field="subject_name">{{ trans('ladashboard.subject') }}</th>
                                            <th data-field="status" data-align="center">{{ trans('ladashboard.status') }}</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                {{-- Khóa học trong năm: Online, Offline --}}
                @include('data.total_course_chart')

                {{-- Khóa học đã ghi danh trong năm: Online, Offline --}}
                @include('data.course_chart')

                {{-- Thống kê (Hoàn thành/Ghi danh) trong năm: Online, Offline, Quiz--}}
                @include('data.chart')
            </div>
            <div class="row">
                {{-- Khóa học Online của tôi --}}
                <div class="col-xl-6 col-lg-6 col-md-6 pl-0 pr-1">
                    <div class="section3125 mt-2 pt-0 bg-white">
                        <div class="mb-0 w-100 p-3">
                            <span class="p-2">@lang('ladashboard.onl_course')</span>
                            @if ( $my_onl->count() == 5)
                                <span class="float-right">
                                    <a href="{{route('module.frontend.user.my_course',['type'=>1])}}">
                                        {{ trans('ladashboard.view_more') }} <img src="{{asset('images/right-arrow.png')}}" alt="">
                                    </a>
                                </span>
                            @endif
                        </div>
                        <div class="la5lo1">
                            <div class="fcrse_1">
                                <div class="fcrse_content">
                                    @foreach($my_onl as $key => $onl)
                                        <div class="new_links10">
                                            <div class="row">
                                                <div class="col-2">
                                                    <img src="{{ asset('images/dashboard/graduation-cap.svg') }}" alt="" class="w-100">
                                                </div>
                                                <div class="col-10">
                                                    <a href="{{ route('module.online.detail_new',[$onl->id]) }}">
                                                        {{ $onl->name }}
                                                    </a> <br>
                                                    ({{ $onl->code }}) <br>
                                                    @lang('ladashboard.time'): {{ get_date($onl->start_date) . ($onl->end_date ? " - " . get_date($onl->end_date) : "") }}
                                                    <br>
                                                    @lang('ladashboard.register_deadline'): {{ $onl->register_deadline ? get_date($onl->register_deadline) : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Khóa học Offline của tôi --}}
                <div class="col-xl-6 col-lg-6 col-md-6 pl-1 pr-0">
                    <div class="section3125 mt-2 pt-0 bg-white">
                        <div class="mb-0 w-100 p-3">
                            <span class="p-2">@lang('ladashboard.off_course')</span>
                            @if ( $my_off->count() == 5)
                                <span class="float-right">
                                    <a href="{{route('module.frontend.user.my_course',['type'=>2])}}">
                                        {{ trans('ladashboard.view_more') }} <img src="{{asset('images/right-arrow.png')}}" alt="">
                                    </a>
                                </span>
                            @endif
                        </div>
                        <div class="la5lo1">
                            <div class="fcrse_1">
                                <div class="fcrse_content">
                                    @foreach($my_off as $key => $off)
                                        <div class="new_links10">
                                            <div class="row">
                                                <div class="col-2">
                                                    <img src="{{ asset('images/dashboard/training.svg') }}" alt="" class="w-100">
                                                </div>
                                                <div class="col-10">
                                                    <a href="{{ route('module.offline.detail_new',[$off->id]) }}">
                                                        {{ $off->name }}
                                                    </a> <br>
                                                    ({{ $off->code }}) <br>
                                                    @lang('ladashboard.time'): {{ get_date($off->start_date) . ($off->end_date ? " - " .get_date($off->end_date) : "") }}
                                                    <br>
                                                    @lang('ladashboard.register_deadline'): {{ $off->register_deadline ? get_date($off->register_deadline) : '' }}
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 p-0">
                    <div class="section3125 mt-2 pt-0 bg-white">
                        <div class="p-3 ml-3 float-left">
                            <img src="{{ asset('styles/images/unread.svg') }}" alt="" class="w-5">
                            <span class="p-2">@lang('ladashboard.notify')</span>
                        </div>
                        <div class="all_msg_bg">
                            @if ($notify->count() > 0)
                                @foreach($notify as $note)
                                    <div class="channel_my item all__noti5 p-2">
                                        <div class="profile_link">
                                            @if($note->important == 1)
                                                <i class="uil uil-star text-warning"></i>
                                            @endif
                                            <div class="pd_content">
                                                <h6>
                                                    <a href="{{ route('module.notify.view', ['id' => $note->id, 'type' => $note->type]) }}">
                                                        <span class="{{ $note->viewed == 1 ? 'text-black' : 'text-primary' }}">
                                                        {{ $note->subject }}
                                                        </span>
                                                    </a>
                                                </h6>
                                                <span class="nm_time">
                                                    {{ \Illuminate\Support\Carbon::parse($note->created_at)->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="row m-0">
                                    <a class="vbm_btn" href="{{ route('module.notify.index') }}">View All <i class='uil uil-arrow-right'></i></a>
                                </div>
                            @else
                                <div class="channel_my item all__noti5">
                                    <div class="profile_link">
                                        <div class="pd_content">
                                            <h6>@lang('ladashboard.no_notification')</h6>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @php
                    $count_commplete = 0;
                @endphp
                <div class="col-xl-12 col-lg-12 col-md-12 p-0">
                    <div class="section3125 mt-2 pt-0 bg-white">
                        <div class="mb-0 p-3">
                            <span class="p-2">
                                @lang('ladashboard.course_roadmap') (<span id="count-complete">0</span>{{ '/'. $training_roadmap_course->count() }})
                            </span>
                        </div>
                        <div class="la5lo1">
                            <div class="fcrse_1">
                                <div class="fcrse_content">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row m-0">
                                                <div class="col-md-2 col-12 p-1">
                                                    @lang('ladashboard.request')
                                                </div>
                                                <div class="col-md-10 col-12 p-1">
                                                    <div class="progress progress2">
                                                        <div class="progress-bar {{ $training_roadmap_course->count() > 0 ? '' : 'not' }}" role="progressbar" style="width: {{ $training_roadmap_course->count() > 0 ? '100%' : '0%' }}" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                            {{ $training_roadmap_course->count() > 0 ? '100%' : '0%' }} ({{ $training_roadmap_course->count() .' '. trans('ladashboard.course') }})
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row m-0">
                                                <div class="col-md-2 col-12 p-1">
                                                    {{ trans('ladashboard.you') }}
                                                </div>
                                                <div class="col-md-10 col-12 p-1">
                                                    <div class="progress progress2">
                                                        <div class="progress-bar" style="background-color: green !important;" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" id="percent-you">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach($training_roadmap_course as $item)
                                        @if($item->id)
                                            @php
                                                if ($item->course_type == 1){
                                                    $result = \Modules\Online\Entities\OnlineCourse::checkCompleteCourse($item->course_id, profile()->user_id);
                                                    $route = route('module.online.detail_online', ['id' => $item->course_id]);
                                                    $type = 'Online';
                                                }else{
                                                    $result = \Modules\Offline\Entities\OfflineCourse::checkCompleteCourse($item->course_id, profile()->user_id);
                                                    $route = route('module.offline.detail', ['id' => $item->course_id]);
                                                    $type = 'Offline';
                                                }

                                                if ($result == 1){
                                                    $count_commplete++;
                                                }
                                            @endphp
                                            <div class="new_links10">
                                                <div class="row">
                                                    <div class="col-md-1 col-2 pr-0">
                                                        <img src="{{ asset('images/dashboard/graduation-cap.svg') }}" alt="" class="w-100">
                                                    </div>
                                                    <div class="col-md-11 col-10">
                                                        <a href="{{ $route }}">
                                                            {{ $item->name }}
                                                        </a> <br>
                                                        ({{ $item->code }}) <br>
                                                        @lang('ladashboard.time'): {{ \Illuminate\Support\Carbon::parse($item->start_date)->format('d/m/Y') . ($item->end_date ? " - " .\Illuminate\Support\Carbon::parse($item->end_date)->format('d/m/Y') : "") }}
                                                        <span class="float-right">{{ $type }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="element_data"
        data-check_night_mode = "{{ (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 1 : 0 }}"
        data-count_complete = '{{ $count_commplete }}'
        data-training_roadmap_course_count = '{{ $training_roadmap_course->count() }}'
        data-dashboard_course = "{{ trans('ladashboard.course') }}"
        data-not_learned = "{{ trans('ladashboard.not_learned') }}"
        data-uncomplete = "{{ trans('ladashboard.uncomplete') }}"
        data-completed = "{{ trans('ladashboard.completed') }}"
        data-chart_course_by_user = {{ implode(',',$chartCourseByUser['course_by_user']) }}
        data-url_user_roadmap = '{{ route('frontend.home.user_roadmap.getDataRoadmap') }}'
        data-chart_subject_by_user = {{ implode(',', $chartSubjectByUser) }}
        data-in_training_roadmap = "{{ trans('latraining.in_training_roadmap') }}"
        data-out_training_roadmap = "{{ trans('latraining.out_training_roadmap') }}"
        data-chart_training_roadmap = {{ implode(',',$chartCourseInOutTrainingRoadmap) }}
    >
    </div>
    <script src="{{ mix('js/dashboardFrontend.js') }}" type="text/javascript"></script>
@endsection
