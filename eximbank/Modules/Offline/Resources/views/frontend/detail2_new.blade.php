@extends('layouts.app')

@section('page_title', trans('lamenu.course'))

@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/emojionearea/emojionearea.min.css') }}">
    <script type="text/javascript" src="{{ asset('vendor/emojionearea/emojionearea.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('styles/module/online/css/detail.css') }}">
@endsection

@section('content')
<style>
    #navbarToggleExternalContent .nav-pills .nav-link{
        color: unset !important;
        background: unset !important;
        background-color: unset !important;
        border-radius: unset !important;
    }
    #navbarToggleExternalContent .nav-pills .nav-link.active{
        border-bottom: 3px solid #007bff;
    }
    .wrapped_content_left {
        height: calc(100vh - 70px);
        -webkit-mask-position: left top;
        overflow-y: unset;
    }
    #info_left {
        overflow-y: scroll;
        height: calc(100vh - 155px);
    }
    #info_left::-webkit-scrollbar {
        width: 5px;
    }
    #info_left::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgb(133 130 130 / 30%);
        background-color: #5553;
    }
    #info_left::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgb(117 111 111 / 30%);
        border-radius: 10px;
    }
    .box-shadow{
        box-shadow: rgb(0 0 0 / 16%) 0px 3px 6px, rgb(0 0 0 / 23%) 0px 3px 6px;
    }
    #carouselCourse .carousel-inner{
        height: calc(100vh - 107px);
    }
    @media (min-width: 576px){
        #carouselCourse{
            height: 100%;
        }
        .iframe_activity {
            height: calc(100vh - 111px);
        }
        .iframe-embed {
            height: 100%;
        }
    }
    #history_activity_scourse{
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 10px;
        background: #f5f5f5;
        cursor: pointer;
        text-align: center;
    }
</style>
    <div class="body_content_detail pl-1 pr-1">
        <div class="row">
            <div class="col-12 box-shadow">
                <div class="row my-2">
                    <div class="col-2 d-flex align-items-center">
                        <a href="{{ route('module.offline.detail_first', [$item->id]) }}"> <i class="fa fa-times"></i> Trở về</a>
                    </div>
                    <div class="col-10 text-center">
                        <h5 class="mb-0">{{ $item->name }}</h5>
                        <p>{{ 'Buổi học '. $offline_schedule->session .': '. get_date($offline_schedule->start_time, 'H:i') .' => '. get_date($offline_schedule->end_time, 'H:i') .' - '. get_date($offline_schedule->lesson_date) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="course_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade {{ $check_activity_course ? 'show active' : '' }}" id="nav-program" role="tabpanel">
                            @php
                                $status = $item->getStatusRegister();
                                $text = status_register_text($status);
                            @endphp
                            <div class="col-12">
                                <div class="row mt-2 show_activity">
                                    <div class="col-12 col-md-3 pl-0 pr-1 show wrapped_content_left" id="navbarToggleExternalContent">
                                        <ul class="nav nav-pills mb-2 box-shadow" role="tablist" id="mTab">
                                            <li class="nav-item">
                                                <a href="#base" class="nav-link" role="tab" data-toggle="tab">Tổng quan</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#content" class="nav-link active" role="tab" data-toggle="tab">{{ trans('latraining.content') }}</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="info_left">
                                            <div id="base" class="tab-pane ml-2">
                                                <div class="my-1 h5"><b>{{ $item->name }}</b></div>
                                                <div class="my-1"><b>{{ trans('latraining.classroom') }}:</b> {{ $offline_class->name }}</div>
                                                <div class="my-1"><b>{{ trans('backend.end_time') }}:</b> {{ get_date($offline_class->end_date) }}</div>
                                                <div class="my-1">
                                                    {{-- Điều kiện hoàn thành --}}
                                                    <h6>{{ trans('latraining.conditions') }}</h6>
                                                    <div class="ml-2">
                                                        @if ($offlineActivity->count() > 0)
                                                            @foreach($offlineActivity as $activity)
                                                                @php
                                                                    if($activity->activity_id == 1){
                                                                        $scorm = \Modules\Offline\Entities\OfflineCourseActivityScorm::find($activity->subject_id);

                                                                        if($scorm->type_result == 1){
                                                                            $note_activity = 'Nhận kết quả hoàn thành từ scorm';
                                                                        }else{
                                                                            $note_activity = '';
                                                                            if($scorm->score_required){
                                                                                $note_activity .= 'Điểm >= '. $scorm->min_score_required .'; ';
                                                                            }
                                                                            if($scorm->status_passed || $scorm->status_completed){
                                                                                $note_activity .= 'Trạng thái: '. ($scorm->status_passed ? 'Đạt': '').($scorm->status_completed ? ' và Hoàn thành' : '');
                                                                            }
                                                                        }
                                                                    }else if(in_array($activity->activity_id, [2,3,4])){
                                                                        $note_activity = 'Tham gia hoạt động';
                                                                    }else if($activity->activity_id == 5){
                                                                        $xapi = \Modules\Offline\Entities\OfflineCourseActivityXapi::find($activity->subject_id);

                                                                        $note_activity = '';
                                                                        if($xapi->score_required){
                                                                            $note_activity .= 'Điểm >= '. $xapi->min_score_required .'; ';
                                                                        }
                                                                        if($xapi->status_passed || $xapi->status_completed){
                                                                            $note_activity .= 'Trạng thái: '. ($xapi->status_passed ? 'Đạt': '').($xapi->status_completed ? ' và Hoàn thành' : '');
                                                                        }
                                                                    }else if($activity->activity_id == 7){
                                                                        $activity_quiz = \Modules\Offline\Entities\OfflineActivityQuiz::find($activity->subject_id);
                                                                        $quiz = \Modules\Quiz\Entities\Quiz::find($activity_quiz->quiz_id);

                                                                        $note_activity = 'Điểm >= '. $quiz->pass_score;
                                                                    }else if($activity->activity_id == 8){
                                                                        $note_activity = 'Gửi bài đánh giá';
                                                                    }
                                                                @endphp
                                                                <p class="mb-0">- {{ $activity->name }}: <b>{{ $note_activity }}</b></p>
                                                            @endforeach
                                                        @else
                                                            {{ trans('backend.not_found') }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="content" class="tab-pane active">
                                                <div class="content_left accordion" id="accordion_table">
                                                    @foreach ($lessons as $lessons_key => $lesson_course)
                                                        <div class="card">
                                                            <div class="card-header p-1" id="heading-{{$lesson_course->id}}">
                                                                <h5 class="mb-0">
                                                                    <button class="w-100 border-0 text-left p-2 lesson-title btn_collapse_{{ $lessons_key + 1 }}" data-toggle="collapse" data-target="#collapse-{{$lesson_course->id}}" aria-expanded="false" aria-controls="collapse-{{$lesson_course->id}}" data-id="{{$lesson_course->id}}">
                                                                        <i class="fas fa-minus-square icon_card mr-1"></i> <b>{{ $lesson_course->lesson_name }}</b>
                                                                    </button>
                                                                </h5>
                                                            </div>

                                                            <div id="collapse-{{$lesson_course->id}}" class="count_collapse collapse_key_{{ $lessons_key + 1 }} collapse {{ ($lessons_key == 0) ? 'show' : ''}}" aria-labelledby="heading-{{$lesson_course->id}}" data-parent="#accordion_table">
                                                                <div class="card-body p-1">
                                                                    @if($lesson_course->activities)
                                                                        @foreach($lesson_course->activities as $key => $activity)
                                                                            <div class="row mx-0 my-1 activityItem activityItemActive line_active_activity_{{ $activity->id }} {{$activity->subject_id == $id_activity_scorm ? 'line-active' : ''}} {{ $key > 0 ? 'border-top' : '' }}">
                                                                                <div class="col-2 px-0 check_finish_activity my-auto">
                                                                                    <span class="opts_account_course" id="check_complete_activity_{{ $activity->id }}" onclick="activityCourse({{ $item->id }},{{ $activity->id }},{{ $activity->lesson_id }},{{ $activity->activity_id }}, {{ $status }})">
                                                                                        @if($activity->isComplete(getUserId(), getUserType()))
                                                                                            <img src="{{ asset('themes/mobile/img/check.png') }}" class="h-auto" width="40%">
                                                                                        @else
                                                                                            <img src="{{ asset('themes/mobile/img/circle.png') }}" class="h-auto" width="40%">
                                                                                        @endif
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-10 px-0 wrapped_activityItem">
                                                                                    @if($activity->activity_id == 1)
                                                                                        @php
                                                                                            $description = \Modules\Offline\Entities\OfflineCourseActivityScorm::find($activity->subject_id, ['description']);
                                                                                        @endphp
                                                                                        <div class="row m-0">
                                                                                            <div class="col-11 pr-1 pt-1 pl-0">
                                                                                                <span class="activityItem link_activity cursor_pointer" onclick="activityCourse({{$item->id}},{{$activity->id}},{{$activity->lesson_id}},{{ $activity->activity_id }}, {{ $status }})">
                                                                                                    <span class="" data-turbolinks="false">
                                                                                                        {{ $activity->name }}
                                                                                                    </span>
                                                                                                </span>
                                                                                            </div>
                                                                                            <div class="col-1 pr-1 pl-0 text-center m-auto">
                                                                                                @if (!empty($description->description))
                                                                                                    <p class="mb-0 cursor_pointer" onclick="showDescription({{$item->id}}, {{$activity->id}}, {{$activity->activity_id}})">
                                                                                                        <i class="fas fa-info-circle"></i>
                                                                                                    </p>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                    @if($activity->activity_id == 2)
                                                                                        @php
                                                                                            $description = \Modules\Offline\Entities\OfflineCourseActivityFile::find($activity->subject_id, ['description']);
                                                                                        @endphp
                                                                                        <div class="row m-0">
                                                                                            <div class="col-11 pl-0 pr-1 my-auto">
                                                                                                <span class="activityItem link_activity cursor_pointer" onclick="activityCourse({{$item->id}},{{$activity->id}},{{$activity->lesson_id}},{{ $activity->activity_id }}, {{ $status }})">
                                                                                                    <span class="" data-turbolinks="false">
                                                                                                        {{ $activity->name }}
                                                                                                    </span>
                                                                                                </span>
                                                                                            </div>
                                                                                            <div class="col-1 pr-1 pl-0 text-center m-auto">
                                                                                                @if (!empty($description->description))
                                                                                                    <p class="mb-0 cursor_pointer" onclick="showDescription({{$item->id}}, {{$activity->id}}, {{$activity->activity_id}})">
                                                                                                        <i class="fas fa-info-circle"></i>
                                                                                                    </p>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                    @if($activity->activity_id == 3)
                                                                                        @php
                                                                                            $description = \Modules\Offline\Entities\OfflineCourseActivityUrl::find($activity->subject_id, ['description']);
                                                                                        @endphp
                                                                                        <div class="row m-0">
                                                                                            <div class="col-11 pl-0 pr-1 my-auto">
                                                                                                <span class="activityItem link_activity cursor_pointer" onclick="activityCourse({{$item->id}},{{$activity->id}},{{$activity->lesson_id}},{{$activity->activity_id}}, {{ $status }})">
                                                                                                    <span class="" data-turbolinks="false">
                                                                                                        {{ $activity->name }}
                                                                                                    </span>
                                                                                                </span>
                                                                                            </div>
                                                                                            <div class="col-1 pr-1 pl-0 text-center m-auto">
                                                                                                @if (!empty($description->description))
                                                                                                    <p class="mb-0 cursor_pointer" onclick="showDescription({{$item->id}}, {{$activity->id}}, {{ $activity->activity_id }})">
                                                                                                        <i class="fas fa-info-circle"></i>
                                                                                                    </p>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                    @if($activity->activity_id == 4)
                                                                                        @php
                                                                                            $get_time_play = \Modules\Offline\Entities\OfflineCourseActivityVideo::findOrFail($activity->subject_id);
                                                                                            $description = \Modules\Offline\Entities\OfflineCourseActivityVideo::find($activity->subject_id, ['description']);
                                                                                        @endphp
                                                                                        <div class="row m-0">
                                                                                            <div class="col-11 pl-0 pr-1 my-auto">
                                                                                                <span class="activityItem link_activity cursor_pointer" onclick="activityCourse({{$item->id}},{{$activity->id}},{{$activity->lesson_id}},{{ $activity->activity_id }}, {{ $status }})">
                                                                                                    <span class="" data-turbolinks="false">
                                                                                                        {{ $activity->name }}
                                                                                                    </span>
                                                                                                    <p class="time_play">{{ $get_time_play->time_play }}</p>
                                                                                                </span>
                                                                                            </div>
                                                                                            <div class="col-1 pr-1 pl-0 text-center m-auto">
                                                                                                @if (!empty($description->description))
                                                                                                    <p class="mb-0 cursor_pointer" onclick="showDescription({{$item->id}}, {{$activity->id}}, {{ $activity->activity_id }})">
                                                                                                        <i class="fas fa-info-circle"></i>
                                                                                                    </p>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                    @if($activity->activity_id == 5)
                                                                                        @php
                                                                                            $description = \Modules\Offline\Entities\OfflineCourseActivityXapi::find($activity->subject_id, ['description']);
                                                                                        @endphp
                                                                                        <div class="row m-0">
                                                                                            <div class="col-11 pl-0 pr-1 my-auto">
                                                                                                <span class="activityItem link_activity cursor_pointer" onclick="activityCourse({{$item->id}},{{$activity->id}},{{$activity->lesson_id}},{{ $activity->activity_id }}, {{ $status }})">
                                                                                                    <span class="" data-turbolinks="false">
                                                                                                        {{ $activity->name }}
                                                                                                    </span>
                                                                                                </span>
                                                                                            </div>
                                                                                            <div class="col-1 pr-1 pl-0 text-center m-auto">
                                                                                                @if (!empty($description->description))
                                                                                                    <p class="mb-0 cursor_pointer" onclick="showDescription({{$item->id}}, {{$activity->id}}, {{ $activity->activity_id }})">
                                                                                                        <i class="fas fa-info-circle"></i>
                                                                                                    </p>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                    @if($activity->activity_id == 7)
                                                                                        @php
                                                                                            $description = \Modules\Offline\Entities\OfflineActivityQuiz::find($activity->subject_id, ['description']);
                                                                                        @endphp
                                                                                        <div class="row m-0">
                                                                                            <div class="col-11 pl-0 pr-1 my-auto">
                                                                                                <span class="activityItem link_activity cursor_pointer" onclick="activityCourse({{$item->id}},{{$activity->id}},{{$activity->lesson_id}},{{$activity->activity_id}}, {{ $status }})">
                                                                                                    <span class="" data-turbolinks="false">
                                                                                                        {{ $activity->name }}
                                                                                                    </span>
                                                                                                </span>
                                                                                            </div>
                                                                                            <div class="col-1 pr-1 pl-0 text-center m-auto">
                                                                                                @if (!empty($description->description))
                                                                                                    <p class="mb-0 cursor_pointer" onclick="showDescription({{$item->id}}, {{$activity->id}}, {{ $activity->activity_id }})">
                                                                                                        <i class="fas fa-info-circle"></i>
                                                                                                    </p>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                    @if($activity->activity_id == 8)
                                                                                        @php
                                                                                            $description = \Modules\Offline\Entities\OfflineCourseActivitySurvey::where(['survey_template_id' => $course_activity->subject_id, 'course_id' => $item->id])->first(['description']);
                                                                                        @endphp
                                                                                        <div class="row m-0">
                                                                                            <div class="col-11 pl-0 pr-1 my-auto">
                                                                                                <span class="activityItem link_activity cursor_pointer" onclick="activityCourse({{$item->id}},{{$activity->id}},{{$activity->lesson_id}},{{$activity->activity_id}}, {{ $status }})">
                                                                                                    <span class="" data-turbolinks="false">
                                                                                                        {{ $activity->name }}
                                                                                                    </span>
                                                                                                </span>
                                                                                            </div>
                                                                                            <div class="col-1 pr-1 pl-0 text-center m-auto">
                                                                                                @if (!empty($description->description))
                                                                                                    <p class="mb-0 cursor_pointer" onclick="showDescription({{$item->id}}, {{$activity->id}}, {{ $activity->activity_id }})">
                                                                                                        <i class="fas fa-info-circle"></i>
                                                                                                    </p>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="" id="history_activity_scourse">
                                                <a href="javascript:void(0)" class="ajax_history_activity_course">Lịch sử hoạt động</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-9 p-0 iframe_activity" id="iframe_activity">
                                        <div class="wrapped_title_setting row m-0">
                                            <div class="wrapped_title_activity col-md-10 col-9 pr-1">
                                                <div class="toogle_menu_left mr-2">
                                                    <button type="button" onclick="toggleMenuLeft(1)" class="btn toggle_menu_left_close">
                                                        <i class="fas fa-arrow-left"></i>
                                                    </button>
                                                    <button type="button" onclick="toggleMenuLeft(0)" class="btn toggle_menu_left_open">
                                                        <i class="fas fa-arrow-right"></i>
                                                    </button>
                                                </div>
                                                <img src="{{ asset('images/icon_activity.png') }}" alt="" width="30px">
                                                <h3 class="title_activity mb-0 ml-1"></h3>
                                            </div>
                                            <div class="wrapped_setting col-md-2 col-3 pr-0">
                                                <div class="wrapped_navigate mr-2 d_flex_align">
                                                    <input type="hidden" class="id_prev" value="">
                                                    <input type="hidden" class="activity_id_prev" value="">
                                                    <input type="hidden" class="lesson_id_prev" value="">
                                                    <input type="hidden" class="id_next" value="">
                                                    <input type="hidden" class="activity_id_next" value="">
                                                    <input type="hidden" class="lesson_id_next" value="">
                                                </div>
                                                <div class="wrapped_zoom">
                                                    <div id="zoom_out">
                                                        <button type="button" class="btn"  onclick='exitFullScreen();'>
                                                            <h5 class="mb-0"><i class="fa fa-search-minus" style="font-size:16px"></i></h5>
                                                        </button>
                                                    </div>
                                                    <div id="zoom_in">
                                                        <button type="button" class="btn"  onclick='launchFullScreen();'>
                                                            <h5 class="mb-0"><i class="fa fa-search-plus" style="font-size:16px"></i></h5>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="carouselCourse" class="carousel slide w-100" data-ride="carousel">
                                            <div class="carousel-inner">
                                                <div class="carousel-item h-100 active" style="background:url('/images/image_default_activity.jpg') no-repeat center; background-size:100% 100%;"></div>
                                            </div>
                                            <div class="start_course">
                                                @if($status == 3)
                                                    <button class="btn button_start">
                                                        <h4 class="mb-0">{{ trans('laother.course_over') }}</h4>
                                                    </button>
                                                @elseif (!empty($register) && $register->status == 1)
                                                    <button class="btn button_start" onclick="startCourse()">
                                                        <h4 class="mb-0">
                                                            {{ $check_activity_active == 0 ? trans('laother.invite_start_course') : trans('laother.invite_continue_course') }}
                                                        </h4>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ajax-loading text-center mt-80">
                                            <div class="spinner-border" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                        <iframe src="" class="iframe-embed w-100" id="iframe-embed-url" allowfullscreen="allowfullscreen" onload="access()" scrolling="auto"></iframe>

                                        <video autoplay id="activity_video" class="pl-2" width="100%" controlsList="nodownload"></video>
                                        <div class="countdow_time_video">
                                            <span>{{ trans('laother.remaining_time_video') }}:</span>
                                            <span class="time_video"></span>
                                        </div>
                                        <input type="hidden" name="activity_video_id" value="">
                                        <input type="hidden" name="online_id" value="">
                                        <input type="hidden" name="type_activity" value="{{ $type_activity }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_description_activity" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mb-0">{{ trans('laother.description_activity') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="content_description_activity">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_stop_video" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body body_content_stop_video">
                    <h4 class="stop_video">{{ trans('laother.video_stopped') }}</h4>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn" onclick="continueVideo()">{{ trans('laother.agree') }}</button>
                    <button type="button" id="closed" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#zoom_out').hide();
        window.Rating = {
            route: '{{ route('module.offline.rating',$item->id) }}',
        };
        var rating = $('.rating');
        ratingStars(rating);

        //BẮT ĐẦU/ TIẾP TỤC KHÓA HỌC
        $('#carouselCourse').show();
        function startCourse() {
            var courseId = '{{ $get_first_activity->course_id }}';
            var aid = '{{ $get_first_activity->id }}';
            var lesson_id = '{{ $get_first_activity->lesson_id }}';
            var type = '{{ $get_first_activity->activity_id }}';
            var status = '{{ $status }}';
            activityCourse(courseId, aid, lesson_id, type, status);
            $('#carouselCourse').hide();
        }

        // GỌI CÁC HOẠT ĐỘNG
        function activityCourse(id, aid, lesson_id, type, status_course = null) {
            document.removeEventListener("visibilitychange", onchange);
            $('.ajax-loading').show();
            $("#activity_video").hide();
            $('#carouselCourse').hide();
            $('#iframe-embed-url').hide();
            $("#activity_video").first().attr('src','')
            $(".countdow_time_video").hide();
            $('input[name="online_id"]').val(id);

            $.ajax({
                type: 'POST',
                url: '{{ route('module.offline.detail.ajax_activity') }}',
                dataType: 'json',
                data: {
                    'id': id,
                    'aid': aid,
                    'lesson_id': lesson_id,
                    'type': type,
                    'status_course': status_course,
                }
            }).done(function(data) {
                $('.ajax-loading').hide();
                if (data.status == 'error' || data.status == 'warning') {
                    show_message(data.message, data.status, data.title)
                    $('.title_activity').html('');
                } else {
                    // Clock.startTimer();
                    countTimeLearn();
                    document.addEventListener("visibilitychange", countTimeLearn);
                    window.addEventListener("beforeunload", saveTimeWhenReloadPage);
                    $('.title_activity').html(data.course_activity.name);
                    $('input[name="type_activity"]').val(type);
                    if(data && (type == 1 || type == 5)) { //scorm or xapi
                        var url_link_activity = "{{ route('module.offline.scorm.play', [$item->id, ':id',':type_activity']) }}";
                        url_link_activity = url_link_activity.replace(':id',data.link.id);
                        url_link_activity = url_link_activity.replace(':type_activity',type);
                        playScromXapi(url_link_activity);
                    } else if (data && type == 4) { //video
                        var activity_video = document.getElementById("activity_video");
                        if(data.required_video_timeout == 1) {
                            $(".countdow_time_video").show();
                            var i = 1;
                            $("#activity_video").click(function() {
                                if (i%2==0) {
                                    this.play();
                                } else {
                                    this.pause();
                                }
                                i+=1;
                            });
                        }else{
                            activity_video.setAttribute("controls","controls");
                        }

                        $('input[name="activity_video_id"]').val(aid);
                        $("#activity_video").show();
                        $("#activity_video").first().attr('src', data.link);
                        $("#activity_video")[0].load();
                        document.addEventListener("visibilitychange", onchange);
                    } else {
                        if(data.msg_error) {
                            show_message(data.msg_error, 'error');
                        } else {
                            if(type == 3 && data.checkPage == 1) { //url
                                window.open(data.link, '_blank');
                            } else {
                                var el = document.getElementById('iframe-embed-url');
                                el.src = '';
                                el.src = data.link;
                                el.onload=function(){
                                    $('#iframe-embed-url').show();
                                };
                            }
                        }
                    }
                    if (data.list_clocked) {
                        $.each(data.list_clocked, function (index, id) {
                            $('#check_complete_activity_'+ id).html(`<img src="{{ asset('themes/mobile/img/circle.png') }}" class="h-auto" width="40%">`);
                        });
                    }
                    if (data.get_activity_completes) {
                        $.each(data.get_activity_completes, function (index, activity_id) {
                            $('#check_complete_activity_'+ activity_id).html(`<img src="{{ asset('themes/mobile/img/check.png') }}" class="h-auto" width="40%">`);
                        });
                    }
                    if (data.activity_prev) {
                        $('.id_prev').val(data.activity_prev.id);
                        $('.activity_id_prev').val(data.activity_prev.activity_id);
                        $('.lesson_id_prev').val(data.activity_prev.lesson_id);
                    }

                    if (data.activity_next) {
                        $('.id_next').val(data.activity_next.id);
                        $('.activity_id_next').val(data.activity_next.activity_id);
                        $('.lesson_id_next').val(data.activity_next.lesson_id);
                    }
                }
                return false;
            }).fail(function(data) {
                show_message("{{ trans('laother.data_error') }}", 'error');
                return false;
            });
        }

        //MỞ GÓI SCORM hoặc XAPI
        function playScromXapi(url_link_activity) {
            $.ajax({
                type: "POST",
                url: url_link_activity,
                dataType: 'json',
                data: {},
                success: function (result) {
                    if (result.status == "success") {
                        var el = document.getElementById('iframe-embed-url');
                        el.src = result.redirect;
                        el.onload=function(){
                            $('.ajax-loading').hide();
                            $('#iframe-embed-url').show();
                        };
                        return false;
                    }
                    show_message(result.message, result.status);
                    return false;
                }
            });
        }

        // SET KÍCH THƯỚC SCROM
        function access() {
            setTimeout(function(){
                var type = $('input[name="type_activity"]').val();
                var iframe = document.getElementById("iframe-embed-url");
                if(type != 2) {
                    var innerDoc1 = iframe.contentDocument || iframe.contentWindow.document;
                    var iframe2 = innerDoc1.getElementById('scorm_object');
                    if(iframe2) {
                        var innerDoc2 = iframe2.contentDocument || iframe2.contentWindow.document;
                        var message_window_slide = innerDoc2.querySelector("#message-window-slide");
                        var message_window_wrapper = innerDoc2.querySelector("#message-window-wrapper");
                        var message_window_heading = innerDoc2.querySelector(".message-window-heading");
                        if(message_window_slide && message_window_wrapper){
                            message_window_slide.style.height = 'auto';
                            message_window_wrapper.style.height = 'auto';
                            if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
                                message_window_heading.style.fontSize = '58%';
                                message_window_heading.style.setProperty('padding', '7px', 'important');
                            }
                        }
                    }
                }
            },300);
        }

        function index_formatter(value, row, index) {
            return (index + 1);
        }

        function time_learn_formatter(value, row, index) {
            return '<span>'+ row.timeStartLearn + (row.timeEndLearn ? ' => '+ row.timeEndLearn : '') +'</span>';
        }

        function review_formatter(value, row, index) {
            if (row.after_review == 1 || row.closed_review == 1) {
                return '<a href="'+ row.review_link +'">{{ trans("latraining.review") }}</a>'
            }
            return '<span class="text-muted">{{ trans("latraining.no_review") }}</span>';
        }

        function launchFullScreen() {
            let FrameId = document.getElementById('iframe_activity');
            if (FrameId.requestFullscreen) {
                FrameId.requestFullscreen();
            } else if (FrameId.mozRequestFullScreen) {
                FrameId.mozRequestFullScreen();
            } else if (FrameId.webkitRequestFullScreen) {
                FrameId.webkitRequestFullScreen();
            } else if (FrameId.msRequestFullscreen) { // IE 11 API
                FrameId.msRequestFullscreen();
            } else {
                let $source = $('#iframe').attr('src');
                window.open($source, "", "fullscreen=no, resizable=1,toolbar=1,titlebar=yes"); // IE 10 and under workaround
                console.log("Fullscreen API is not supported");
            }

            $('.iframe-embed').css('min-height', '90vh')
            $('.toggle_menu_left_close').hide()
            $('#zoom_out').show();
            $('#zoom_in').hide();
        }

        function exitFullScreen() {
            if(document.exitFullscreen) {
                document.exitFullscreen();
            } else if(document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if(document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }

            $('.iframe-embed').css('min-height', '75vh')
            $('.toggle_menu_left_close').show()
            $('#zoom_out').hide();
            $('#zoom_in').show();
        }

        function rating_url_formatter(value, row, index) {
            if(row.rating_level_url){
                return '<a href="'+ row.rating_level_url +'" class="btn">Đánh giá</a>';
            }
            return 'Đánh giá';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.detail.rating_level.getdata', ['id' => $item->id]) }}',
            table: '#table-rating-level',
        });

        $('#btnMenuListActivity').on('click', function () {
            if($('#nav-courses').find('.iframe_activity').hasClass('col-md-9')){
                $( "#navbarToggleExternalContent" ).hide( "slide", { direction: "left" }, 1000, function() {
                    $('#nav-courses').find('.iframe_activity').fadeIn("slow", function() {
                        $(this).removeClass("col-md-9");
                    });
                });
            }else{
                $('#nav-courses').find('.iframe_activity').addClass('col-md-9');
                $("#navbarToggleExternalContent").show("slide", { direction: "left" }, 1000);
            }
            access();
        });

        $('#nav-courses').on('click', '.activityItemActive', function () {
            if(open) {
                if($('#accordion_table').find('.activityItemActive').hasClass('line-active')){
                    $('#accordion_table').find('.activityItemActive').removeClass('line-active')
                }
                $(this).addClass('line-active');
            }
        });

        // BÌNH LUẬN KHÓA HỌC
        $(".textarea_emoji").emojioneArea({
            pickerPosition: "bottom",
            hidePickerOnBlur: false,
            search: false,
        });

        function sendComment(id) {
            var comment_id = $('#id_comment').val();
            console.log(comment_id);
            var content = $('#textarea_comment').val();
            var url = "{{ route('frontend.offline.comment.course_offline', ':id') }}";
            url = url.replace(':id',id);
            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                data: {
                    'id': id,
                    'content': content,
                    'comment_id': comment_id
                }
            }).done(function(data) {
                var html = '';
                if (data.status == "success" && !comment_id) {
                    html = `<div class="review_item item_`+ data.comment.id +`">
                                <div class="review_usr_dt">
                                    <img src="`+ data.profile_user.avatar +`" alt="">
                                    <div class="rv1458">
                                        <h4 class="tutor_name1">
                                            `+ ( data.profile_user.full_name ? data.profile_user.full_name : data.profile_user.name ) +`
                                            <div class="ml-2 rpt100">
                                                <span>
                                                    <a onclick="deleteComment(`+ data.comment.id +`)" class="report145">
                                                        <i class="far fa-trash-alt"></i>
                                                    </a>
                                                </span>
                                                <span>
                                                    <a onclick="editComment(`+ data.comment.id +`)" class="report145">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                </span>
                                            </div>
                                        </h4>
                                        <span class="time_145">`+ data.comment.created_at2 +`</span>
                                    </div>
                                </div>
                                <p class="rvds10 content_`+ data.comment.id +`">`+ data.comment.content +`</p>
                            </div>`;
                    $('.all_comments').prepend(html);
                } else if (data.status == "success" && comment_id) {
                    $('.content_'+ data.comment.id).text(data.comment.content);
                }
                cancelEdit();
            }).fail(function(data) {
                show_message("{{ trans('laother.data_error') }}", 'error');
                return false;
            });
        }

        function deleteComment(id) {
            var confirmText = "{{ trans('laforums.want_to_delete') }}";
            var url = "{{ route('frontend.offline.delete.comment.course_offline', ':id') }}";
            url = url.replace(':id',id);
            if(confirm(confirmText)) {
                $.ajax({
                    type: 'POST',
                    url: url,
                }).done(function(data) {
                    $(".item_"+id).remove();
                    cancelEdit();
                    return false;
                }).fail(function(data) {
                    show_message("{{ trans('laother.data_error') }}", 'error');
                    return false;
                });
            }
        }

        function editComment(id) {
            var text = $('.content_'+id).text();
            $("#textarea_comment").data("emojioneArea").setText(text);
            $('#id_comment').val(id);
            $('.cancel_edit_comment').show();
        }

        function cancelEdit() {
            $("#textarea_comment").data("emojioneArea").setText('');
            $('#id_comment').val('');
            $('.cancel_edit_comment').hide();
        }
        // END COMMENT

        // TỰ ĐỘNG GHI DANH KHI CLICK VÀO HOẠT ĐỘNG KHÓA HỌC ONNLINE
        function registerActivityOnline(courseId, onlineId) {
            let item = $('.link_course_online_'+ onlineId);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');

            $.ajax({
                type: 'POST',
                url: "{{ route('frontend.offline.auto_register_activity_online') }}",
                dataType: 'json',
                data: {
                    'courseId': courseId,
                    'onlineId': onlineId,
                }
            }).done(function(data) {
                item.html(oldtext);
                window.open(data);
                return false;
            }).fail(function(data) {
                show_message("{{ trans('laother.data_error') }}", 'error');
                return false;
            });
        }

        //ĐÁNH DẤU
        function bookmarkHandle(id) {
            $.ajax({
                type: 'POST',
                url: "{{ route('frontend.offline.bookmark_offline') }}",
                dataType: 'json',
                data: {
                    'id': id,
                }
            }).done(function(data) {
                if(data == 1) {
                    $('.bookmark_course').html('<span><i class="uil uil-heart check-heart"></i>{{ trans("laprofile.bookmarked") }}</span>')
                } else {
                    $('.bookmark_course').html('<span><i class="uil uil-heart"></i>{{ trans("laprofile.bookmark") }}</span>')
                }
                return false;
            }).fail(function(data) {
                show_message("{{ trans('laother.data_error') }}", 'error');
                return false;
            });
        }

        // MESSAGE KTRA LINK TEAMS
        function checkLinkHandle(type) {
            if (type == 1) {
                show_message("{{ trans('latraining.time_join_teams_yet') }}", 'warning')
            } else {
                show_message("{{ trans('latraining.participation_time_out_teams') }}", 'warning')
            }
        }
        //////////////////////

        // MÔ TẢ HOẠT ĐỘNG
        function showDescription(course_id, aid, type) {
            $.ajax({
                type: "POST",
                url: '{{ route('module.offline.detail.ajax_description_activity') }}',
                dataType: 'json',
                data: {
                    'course_id': course_id,
                    'aid': aid,
                    'type': type,
                },
                success: function (result) {
                    if (result.status == "success") {
                        $('#content_description_activity').html(result.description);
                        $('#modal_description_activity').modal();
                        return false;
                    }
                    show_message(result.message, result.status);
                    return false;
                }
            });
        }
        //////////////////

        // XEM HẾT VIDEO
        document.getElementById('activity_video').addEventListener('ended', finishVideo, false);
        function finishVideo(e) {
            var id = $('input[name="activity_video_id"]').val();
            var course_id = $('input[name="online_id"]').val();
            $.ajax({
                type: "POST",
                url: "{{ route('module.offline.detail.finish_activity_video') }}",
                dataType: 'json',
                data: {
                    'id': id,
                    'course_id': course_id
                },
                success: function (result) {
                    if (result.get_activity_completes) {
                        $.each(result.get_activity_completes, function (index, activity_id) {
                            $('#check_complete_activity_'+ activity_id).html(`<img src="{{ asset('themes/mobile/img/check.png') }}" class="h-auto" width="40%">`);
                        });
                    }
                    if (result.list_clocked) {
                        $.each(result.list_clocked, function (index, id) {
                            $('#check_complete_activity_'+ id).html(`<img src="{{ asset('themes/mobile/img/circle.png') }}" class="h-auto" width="40%">`);
                        });
                    }
                    return false;
                }
            });
        }
        ////////////////

        // THỜI GIAN CÒN LẠI CỦA VIDEO
        document.getElementById('activity_video').addEventListener('timeupdate', updateCountdown, false);
        function updateCountdown() {
            var video = document.getElementById('activity_video');
            var countdown = Math.round(video.duration - video.currentTime);
            var minutes = Math.floor(countdown / 60);
            var seconds = countdown % 60;
            $('.time_video').html(minutes + ':' + seconds);
        }
        //////////////////////////////

        // DỪNG VIDEO KHI QUA TRANG KHÁC
        var timeout = 200
        var timer = null,
                interval = 1000,
                value = 0;
        function onchange () {
            if (document.hidden) {
                if (timer !== null) return;
                timer = setInterval(function () {
                    value = value + 1;
                    if (value == timeout) {
                        $('#activity_video').trigger('pause');
                        $('#modal_stop_video').modal();
                        clearInterval(timer);
                    }
                }, interval);
            } else {
                value = 0
                clearInterval(timer);
                timer = null
            }
        }
        function continueVideo() {
            $('#activity_video').trigger('play');
            $('#modal_stop_video').modal('hide');
        }
        //////////////////////////////

        // TÍNH TỔNG THỜI GIAN HỌC
        var golbalTimeStop = 0;
        var Clock = {
            totalSeconds: 0,
            startTimer: function () {
                if (!this.interval) {
                    var self = this;
                    this.interval = setInterval(function () {
                        self.totalSeconds += 1;
                    }, 1000);
                }
            },
            pauseTimer: function () {
                golbalTimeStop = Clock.totalSeconds - golbalTimeStop
                clearInterval(this.interval);
                delete this.interval;
            },
        };
        function countTimeLearn() {
            if (document.hidden) {
                let countdown = 0;
                Clock.pauseTimer();
                let countDownSaveTime = setInterval(function () {
                    countdown += 1;
                    if(countdown == 10) {
                        saveTimeLearn(golbalTimeStop);
                        clearInterval(countDownSaveTime)
                    }
                }, 1000);
            } else {
                Clock.startTimer();
            }
        }
        function saveTimeWhenReloadPage() {
            var time = golbalTimeStop ? Clock.totalSeconds - golbalTimeStop : Clock.totalSeconds
            console.log('save: '+ time);
            saveTimeLearn(time)
        }
        function saveTimeLearn(time) {
            let courseId = $('input[name="online_id"]').val();
            $.ajax({
                type: "POST",
                url: "{{ route('module.offline.detail.save_user_time_learn') }}",
                dataType: 'json',
                data: {
                    'time': time,
                    'courseId': courseId
                },
                success: function (result) {
                    return false;
                }
            });
        }
        ///////////////////////////

        // NEXT, PREV HOẠT ĐỘNG
        function navigateActivity(course_id, type, status_course = null) {
            if (type == 0) {
                var aid = $('.id_prev').val();
                var activity_type = $('.activity_id_prev').val();
                var lesson_id = $('.lesson_id_prev').val();
            } else {
                var aid = $('.id_next').val();
                var activity_type = $('.activity_id_next').val();
                var lesson_id = $('.lesson_id_next').val();
            }
            activityCourse(course_id, aid, lesson_id, activity_type, status_course);
            $('#accordion_table').find('.activityItemActive').removeClass('line-active')
            $('.line_active_activity_'+aid).addClass('line-active');
        }
        //////////////////////

        // ĐÓNG MỞ MENU LEFT
        function toggleMenuLeft(type) {
            if(type == 1) {
                $('.toggle_menu_left_open').show();
                $('.toggle_menu_left_close').hide();
                $('.wrapped_activityItem').addClass('d_none');
                $('.card-header').hide();
                $('.wrapped_content_left').removeClass('col-md-3');
                $('.wrapped_content_left').addClass('d_none');
                $('.iframe_activity').removeClass('col-md-9');
                //$('.iframe_activity').addClass('col-md-11');
                $('.check_finish_activity').removeClass('col-2');
                $('.check_finish_activity').addClass('col-12');
                // $('.opts_account_course').addClass('px-3');
                $('.collapse').show();

                //$('.wrapped_content_left').addClass('mw_4');
                //$('.iframe_activity').addClass('mw_96');
                $('.opts_account_course img').addClass('w_70');
            } else {
                $('.toggle_menu_left_open').hide();
                $('.toggle_menu_left_close').show();
                $('.wrapped_activityItem').removeClass('d_none');
                $('.card-header').show();
                $('.wrapped_content_left').addClass('col-md-3');
                $('.wrapped_content_left').removeClass('d_none');
                $('.iframe_activity').addClass('col-md-9');
                //$('.iframe_activity').removeClass('col-md-11');
                $('.check_finish_activity').addClass('col-2');
                $('.check_finish_activity').removeClass('col-12');
                // $('.opts_account_course').removeClass('px-3');

                //$('.wrapped_content_left').removeClass('mw_4');
                //$('.iframe_activity').removeClass('mw_96');
                $('.opts_account_course img').removeClass('w_70');
            }
        }
        ////////////////////

        $('.collapse')
        .on('shown.bs.collapse', function() {
            $(this)
                .parent()
                .find(".icon_card")
                .removeClass("fa-plus-square")
                .addClass("fa-minus-square");
        })
        .on('hidden.bs.collapse', function() {
            $(this)
                .parent()
                .find(".icon_card")
                .removeClass("fa-minus-square")
                .addClass("fa-plus-square");
        });

        // HỌC VIÊN ĐÁNH DẤU HOẠT ĐỘNG
        function userBookmark(course_id, activity_id) {
            var status = $('.status_bookmark_'+ activity_id).val();
            $.ajax({
                type: "POST",
                url: "{{ route('module.online.detail.user_book_mark_activity') }}",
                dataType: 'json',
                data: {
                    'status': status,
                    'activity_id': activity_id,
                    'course_id': course_id
                },
                success: function (result) {
                    $('.status_bookmark_'+ activity_id).val(result.status_bookmark);
                    if (result.status_bookmark == 1) {
                        $('.icon_bookmark_'+ activity_id).removeClass('far fa-bookmark');
                        $('.icon_bookmark_'+ activity_id).addClass('fas fa-bookmark check_bookmark');
                    } else {
                        $('.icon_bookmark_'+ activity_id).removeClass('fas fa-bookmark check_bookmark');
                        $('.icon_bookmark_'+ activity_id).addClass('far fa-bookmark');
                    }
                    return false;
                }
            });
        }
        ///////////////////////////////

        //Lịch sử hoạt động
        $('#history_activity_scourse .ajax_history_activity_course').on('click', function(){
            $('.ajax-loading').show();
            $("#activity_video").hide();
            $('#carouselCourse').hide();
            $('#iframe-embed-url').hide();
            $('.wrapped_title_setting ').hide();
            $("#activity_video").first().attr('src','')
            $(".countdow_time_video").hide();

            var el = document.getElementById('iframe-embed-url');
                el.src = '';
                el.src = "{{ route('module.offline.detail.ajax_history_activity_course', [$item->id, 'schedule_id' => $offline_schedule->id]) }}";
                el.onload=function(){
                    $('.ajax-loading').hide();
                    $('#iframe-embed-url').show();
                };
        });
        ///////////////////////////
    </script>
@endsection
