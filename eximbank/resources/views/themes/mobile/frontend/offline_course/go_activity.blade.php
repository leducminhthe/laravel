@extends('themes.mobile.layouts.app')

@section('page_title', 'Hoạt động bài học')

@section('header')
    <style>
        @media (max-width: 576px) {
            .iframe_activity,
            .iframe-embed {
                min-height: 100vh;
            }
        }
        @media (min-width: 576px) {
            .iframe_activity,
            .iframe-embed {
                min-height: 100vh;
            }
        }
        #loading {
            display: none;
        }
        .dropdown-toggle::after {
            display:none;
        }
        #carouselCourse{
            width: 100%;
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection

@section('header_activity')
<div class="menu-activity bg-template p-1" id="menu-activity">
    @foreach ($offlineActivityOnlines as $lessons_key => $lesson_course)
        @php
            $online_course = $lesson_course->courseOnline;
            $activities = $online_course->getActivities();
        @endphp
        <h6 class="my-1">{{ $lesson_course->nameOnlineCourse }}</h6>
        <div class="wrapped_activity">
            @foreach($activities as $key => $activity)
                @php
                    $parts = \Modules\Quiz\Entities\QuizPart::checkQuizPartOnline($activity->subject_id);
                    $checked = $activity->isComplete(profile()->user_id);

                    if($lessons_key == 0 && $key == 0){
                        $get_first_activity = \Modules\Online\Entities\OnlineCourseActivity::find($activity->id);
                    }
                @endphp
                @if (($activity->activity_id != 2 && userThird()) || !userThird())
                    <div class="row_activity row_activity_{{$activity->id}} py-1 pl-2">
                        <span class="" id="check_complete_activity_{{ $activity->id }}">
                            @if($checked)
                                <img src="{{ asset('themes/mobile/img/check.png') }}" class="avatar avatar-20">
                            @else
                                <img src="{{ asset('themes/mobile/img/circle.png') }}" class="avatar avatar-20">
                            @endif
                        </span>
                        @if(is_null($parts) && $activity->activity_id == 2)
                            {{ $activity->name }} <br> {{ '('. data_locale('Đã kết thúc hoặc Chưa đăng kí', 'Has ended or Not registered') .')' }}
                        @else
                            @if(isset($parts) && $parts->start_date > date('Y-m-d H:i:s'))
                                {{ $activity->name .' ('. data_locale('Kỳ thi chưa tới ngày', 'Less exam day') .')' }}
                            @else
                                <span class="h6" id="activity_name_{{ $activity->id }}" onclick="activityCourse({{$online_course->id}},{{$activity->id}},{{$activity->lesson_id}},{{$activity->activity_id}})">{{ $activity->name }}</span>
                            @endif
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    @endforeach
    @if ($offlineActivityMettings->count() > 0)
        <h6 class="my-1">Ms Team</h6>
        @foreach ($offlineActivityMettings as $key => $activityMetting)
            <div class="wrapped_team pl-1">
                <div class="row_activity line_active_activity_ms_team_{{ $activityMetting->id }} p-1">
                    <span class="" id="check_complete_activity_ms_team_{{ $activityMetting->id }}">
                        @if($activityMetting->check_attendance == 1)
                            <img src="{{ asset('themes/mobile/img/check.png') }}" class="avatar avatar-20">
                        @else
                            <img src="{{ asset('themes/mobile/img/circle.png') }}" class="avatar avatar-20">
                        @endif
                    </span>
                    <span class="">
                        @if ($activityMetting->checkLink != 0)
                            <a class="activityItem link_activity text-white" onclick="checkLinkHandle({{ $activityMetting->checkLink }})">
                        @else
                            <a class="activityItem link_activity text-white" href="{{ $activityMetting->linkMetting }}" target="_blank">
                        @endif
                                <span>{{ $key + 1 }}./</span>
                                <span>
                                    {{ trans('latraining.session') }} {{ $key + 1 }} ({{ $activityMetting->start_time }} - {{ $activityMetting->end_time }})
                                </span>
                            </a>
                    </span>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection

@section('content')
    <div class="container pl-0 pr-0" id="detail-online">
        <h6 class="info_course m-1 text-center">{{  $item->name }}</h6>
        <div class="align-self-center block-image" id="iframe_activity">
            <div style="z-index:1000; position:absolute; right:5px;">
                <div id="zoom_out" style="display:none;">
                    <a onclick="exitFullScreen();" class="text-warning">
                        <h5>
                            {{-- <i class="fa fa-search-minus" style="font-size:16px"></i> --}}
                            <svg style="width:25px; height:auto;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="512" viewBox="0 0 32 32" width="512">
                                <g id="_81_minimize" data-name="81 minimize"><path d="m13 4a1 1 0 0 1 -1 1h-6a1 1 0 0 0 -1 1v6a1 1 0 0 1 -2 0v-6a3 3 0 0 1 3-3h6a1 1 0 0 1 1 1z" fill="#ffc400"/><path d="m29 20v6a3 3 0 0 1 -3 3h-6a1 1 0 0 1 0-2h6a1 1 0 0 0 1-1v-6a1 1 0 0 1 2 0z" fill="#ffc400"/><path d="m29 12a1 1 0 0 1 -1 1h-8a1 1 0 0 1 -1-1v-8a1 1 0 0 1 2 0v7h7a1 1 0 0 1 1 1z" fill="#ffc400"/><path d="m28.71 4.71-8 8a1 1 0 0 1 -1.42 0 1 1 0 0 1 0-1.42l8-8a1 1 0 1 1 1.42 1.42z" fill="#ffc400"/><g fill="#ffc400"><path d="m13 20v8a1 1 0 0 1 -2 0v-7h-7a1 1 0 0 1 0-2h8a1 1 0 0 1 1 1z"/><path d="m12.71 20.71-8 8a1 1 0 0 1 -1.42 0 1 1 0 0 1 0-1.42l8-8a1 1 0 0 1 1.42 1.42z"/></g></g>
                            </svg>
                        </h5>
                    </a>
                </div>
                <div id="zoom_in" style="display:none;">
                    <a onclick="launchFullScreen();" class="text-warning">
                        <h5>
                            {{-- <i class="fa fa-search-plus" style="font-size:16px"></i> --}}
                            <svg style="width:25px; height:auto;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="512" viewBox="0 0 32 32" width="512">
                                <g id="_82_maximize" data-name="82 maximize"><path d="m13 4a1 1 0 0 1 -1 1h-6a1 1 0 0 0 -1 1v6a1 1 0 0 1 -2 0v-6a3 3 0 0 1 3-3h6a1 1 0 0 1 1 1z" fill="#ffc400"/><path d="m29 20v6a3 3 0 0 1 -3 3h-6a1 1 0 0 1 0-2h6a1 1 0 0 0 1-1v-6a1 1 0 0 1 2 0z" fill="#ffc400"/><path d="m28 4v8a1 1 0 0 1 -2 0v-7h-7a1 1 0 0 1 0-2h8a1 1 0 0 1 1 1z" fill="#ffc400"/><path d="m27.71 4.71-8 8a1 1 0 0 1 -1.42 0 1 1 0 0 1 0-1.42l8-8a1 1 0 1 1 1.42 1.42z" fill="#ffc400"/><g fill="#ffc400"><path d="m13 28a1 1 0 0 1 -1 1h-8a1 1 0 0 1 -1-1v-8a1 1 0 0 1 2 0v7h7a1 1 0 0 1 1 1z"/><path d="m12.71 20.71-8 8a1 1 0 0 1 -1.42 0 1 1 0 0 1 0-1.42l8-8a1 1 0 0 1 1.42 1.42z"/></g></g>
                            </svg>
                        </h5>
                    </a>
                </div>
            </div>
            <iframe src="" class="iframe-embed w-100" id="iframe-embed-url" allowfullscreen="allowfullscreen" scrolling="auto" style="display: none;"></iframe>
            <div id="quiz-iframe"></div>
            <div id="loading">
                <img class="center img_gift img_gift_loading1" src="{{ asset('themes/mobile/img/loading1.gif') }}" width="300px" alt="loading">
            </div>
        </div>
        <div id="carouselCourse">
            <button class="btn button_start" onclick="startCourse()">
                <h6 class="mb-0">
                    {{ trans('laother.invite_start_course') }}
                </h6>
            </button>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">

        //BẮT ĐẦU/ TIẾP TỤC KHÓA HỌC
        $('#carouselCourse').show();
        function startCourse() {
            var courseId = '{{ $get_first_activity->course_id }}';
            var aid = '{{ $get_first_activity->id }}';
            var lesson_id = '{{ $get_first_activity->lesson_id }}';
            var type = '{{ $get_first_activity->activity_id }}';

            if(!courseId && !aid && !lesson_id && !type){
                show_message('Không có hoạt động', 'warning');
                return false;
            }

            activityCourse(courseId, aid, lesson_id, type);
            $('#carouselCourse').hide();
            $('.info_course').hide();
            $('#menu-activity').hide();
        }
        // GỌI HOẠT ĐỘNG SCROM
        $('#iframe-embed-url').css('display','none');

        function activityCourse(id,aid,lesson_id,type) {
            $('#menu-activity').hide();
            $('#carouselCourse').hide();
            $('.info_course').hide();

            var activity_name = $('#activity_name_'+aid);
            var text_old = activity_name.html();

            activity_name.html('<i class="fa fa-spinner fa-spin"></i>');

            if(type != 8){
                $('#loading').show();
                $('#iframe-embed-url').css({'display':'block'});
                $('#quiz-iframe').html('');
            }
            $('#zoom_out').hide();
            $('#zoom_in').hide();

            $.ajax({
                type: 'POST',
                url: '{{ route('module.online.detail.ajax_activity') }}',
                dataType: 'json',
                data: {
                    'id': id,
                    'aid': aid,
                    'lesson_id': lesson_id,
                    'type': type,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function(data) {
                activity_name.html(text_old);

                if (data.status == 'error') {
                    show_message(data.message, data.status)
                } else {
                    $('.row_activity').removeClass('active_activty_mobile');
                    var el = document.getElementById('iframe-embed-url');
                    el.src = '';
                    if (data) {
                        $('#loading').hide();

                        if(type == 8){
                            $('.img_gift').hide()
                            $('.img_gift_default').show()
                            $('#loading_gift').show();
                            $('body').addClass('bg_white');
                            $('.body_mobile').hide();
                            window.location.href = data.link;
                        }

                        if (type == 1 || type == 7) {
                            var url_link = "{{ route('module.online.scorm.play', [':course_id', ':id', ':type_activity']) }}";
                            let $activity_id =  data.link.id;

                            url_link = url_link.replace(':course_id',id);
                            url_link = url_link.replace(':id',data.link.id);
                            url_link = url_link.replace(':type_activity',type);

                            playScromXapi(url_link);

                            $('#zoom_out').hide();
                            $('#zoom_in').show();
                        } else if(type == 2){
                            $('#iframe-embed-url').css('display','none');
                            $('#quiz-iframe').load(data.link);
                        } else if(type != 8){
                            el.src = data.link;
                            localStorage.setItem('activity-active', el.src);
                        }

                        $('.row_activity_'+aid).addClass('active_activty_mobile');
                    }
                    if (data.get_activity_completes) {
                        $.each(data.get_activity_completes, function (index, value) {
                            $('#check_complete_activity_'+value.activity_id).html(`<img src="{{ asset('themes/mobile/img/check.png') }}" class="avatar avatar-20">`);
                        });
                    }
                }
                return false;
            }).fail(function(data) {
                activity_name.html(text_old);

                show_message("{{ data_locale('Lỗi dữ liệu', 'Data error') }}", 'error');
                return false;
            });
        }

        //MỞ GÓI SCORM
        function playScromXapi(url_link) {
            $.ajax({
                type: "POST",
                url: url_link,
                dataType: 'json',
                data: {},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    if (result.status == "success") {
                        //console.log(result.redirect);
                        var el = document.getElementById('iframe-embed-url');
                        el.src = result.redirect;

                        localStorage.setItem('activity-active', el.src);
                        /*window.location.href = result.redirect*/
                        return false;
                    }
                    show_message(result.message, result.status);
                    return false;
                }
            });
        }

        function launchFullScreen() {
            $('#zoom_out').show();
            $('#zoom_in').hide();

            $('#homepage .header').hide();
            $('#homepage .footer').hide();
            $('#homepage #info-course-detail').hide();
            $('#homepage .item-activity').hide();

            $('#detail-online').css({'padding-top':'0px', 'height': '100vh'});
            $('#detail-online .card').hide();
            $('#detail-online .card').removeClass('mb-2 mt-2');
            $('#detail-online .img_online_course_mobile').removeClass('mt-2');
            $('#detail-online #thumbnail-image-course').removeClass('picture_course');
            $('#detail-online #thumbnail-image-course').css({'height': '85vh'});
            $('.wrapper').css({'padding-bottom':'0px', 'padding-top':'0px'});
        }
        function exitFullScreen() {
            $('#zoom_out').hide();
            $('#zoom_in').show();

            $('#homepage .header').show();
            $('#homepage .footer').show();
            $('#homepage #info-course-detail').show();
            $('#homepage .item-activity').show();

            $('#detail-online').css({'padding-top':'54px', 'height': 'unset'});
            $('#detail-online .card').show();
            $('#detail-online .card').addClass('mb-2 mt-2');
            $('#detail-online .img_online_course_mobile').addClass('mt-2');
            $('#detail-online #thumbnail-image-course').addClass('picture_course');
            $('.wrapper').css({'padding-bottom':'80px'});
        }
        function hide_online() {
            $('.register_trainee_online').hide('slow');
            $('.info_online_course').hide('slow');
        }

        window.addEventListener("orientationchange", event => {
            if(event.target.screen.orientation.angle == 0){
                $('#zoom_out').hide();
                $('#zoom_in').show();

                $('#homepage .header').show();
                $('#homepage .footer').show();
                $('#homepage #info-course-detail').show();
                $('#homepage .item-activity').show();

                $('#detail-online').css({'padding-top':'54px', 'height': 'unset'});
                $('#detail-online .card').show();
                $('#detail-online .card').addClass('mb-2 mt-2');
                $('#detail-online .img_online_course_mobile').addClass('mt-2');
                $('#detail-online #thumbnail-image-course').addClass('picture_course');
                $('.wrapper').css('padding-bottom', '80px');
            }else{
                var activityActive = localStorage.getItem('activity-active');
                if (activityActive){
                    $('#thumbnail-image-course').css('display','none');
                    $('#iframe-embed-url').css({'display':'block'});

                    var el = document.getElementById('iframe-embed-url');
                    el.src = activityActive;
                }

                $('#zoom_out').show();
                $('#zoom_in').hide();

                $('#homepage .header').hide();
                $('#homepage .footer').hide();
                $('#homepage #info-course-detail').hide();
                $('#homepage .item-activity').hide();

                $('#detail-online').css({'padding-top':'0px', 'height': '100vh'});
                $('#detail-online .card').hide();
                $('#detail-online .card').removeClass('mb-2 mt-2');
                $('#detail-online .img_online_course_mobile').removeClass('mt-2');
                $('#detail-online #thumbnail-image-course').removeClass('picture_course');
                $('#detail-online #thumbnail-image-course').css({'height': '85vh'});
                $('.wrapper').css({'padding-bottom':'0px', 'padding-top':'0px'});
            }
        });

        function checkLinkHandle(type) {
            if (type == 1) {
                show_message("{{ trans('latraining.time_join_teams_yet') }}", 'warning')
            } else {
                show_message("{{ trans('latraining.participation_time_out_teams') }}", 'warning')
            }
        }
    </script>
@endsection
