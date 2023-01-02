@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.in_house'))
@section('header')
    <style>
        @media (max-width: 576px) {
            .iframe_activity,
            .iframe-embed {
                min-height: 100%;
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
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            border: 12px solid #f3f3f3;
            border-radius: 50%;
            border-top: 12px solid #444444;
            width: 70px;
            height: 70px;
            animation: spin 1s linear infinite;
            z-index: 9999;
        }
    </style>
@endsection
@section('content')
    <div class="container pl-0 pr-0">
        <div class="img_online_course_mobile mt-2">
            <img src="{{ image_offline($item->image) }}" alt="" class="w-100 thumbnail-image" id="thumbnail-image-course">
        </div>
        <div class="col-12 align-self-center block-image p-1" id="iframe_activity">
            <div style="z-index:1000; position:absolute; right:5px;">
                <div id="zoom_out" style="display:none;">
                    <a onclick="exitFullScreen();" class="text-warning">
                        <h5>
                            <svg style="width:25px; height:auto;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="512" viewBox="0 0 32 32" width="512">
                                <g id="_81_minimize" data-name="81 minimize"><path d="m13 4a1 1 0 0 1 -1 1h-6a1 1 0 0 0 -1 1v6a1 1 0 0 1 -2 0v-6a3 3 0 0 1 3-3h6a1 1 0 0 1 1 1z" fill="#ffc400"/><path d="m29 20v6a3 3 0 0 1 -3 3h-6a1 1 0 0 1 0-2h6a1 1 0 0 0 1-1v-6a1 1 0 0 1 2 0z" fill="#ffc400"/><path d="m29 12a1 1 0 0 1 -1 1h-8a1 1 0 0 1 -1-1v-8a1 1 0 0 1 2 0v7h7a1 1 0 0 1 1 1z" fill="#ffc400"/><path d="m28.71 4.71-8 8a1 1 0 0 1 -1.42 0 1 1 0 0 1 0-1.42l8-8a1 1 0 1 1 1.42 1.42z" fill="#ffc400"/><g fill="#ffc400"><path d="m13 20v8a1 1 0 0 1 -2 0v-7h-7a1 1 0 0 1 0-2h8a1 1 0 0 1 1 1z"/><path d="m12.71 20.71-8 8a1 1 0 0 1 -1.42 0 1 1 0 0 1 0-1.42l8-8a1 1 0 0 1 1.42 1.42z"/></g></g>
                            </svg>
                        </h5>
                    </a>
                </div>
                <div id="zoom_in" style="display:none;">
                    <a onclick="launchFullScreen();" class="text-warning">
                        <h5>
                            <svg style="width:25px; height:auto;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="512" viewBox="0 0 32 32" width="512">
                                <g id="_82_maximize" data-name="82 maximize"><path d="m13 4a1 1 0 0 1 -1 1h-6a1 1 0 0 0 -1 1v6a1 1 0 0 1 -2 0v-6a3 3 0 0 1 3-3h6a1 1 0 0 1 1 1z" fill="#ffc400"/><path d="m29 20v6a3 3 0 0 1 -3 3h-6a1 1 0 0 1 0-2h6a1 1 0 0 0 1-1v-6a1 1 0 0 1 2 0z" fill="#ffc400"/><path d="m28 4v8a1 1 0 0 1 -2 0v-7h-7a1 1 0 0 1 0-2h8a1 1 0 0 1 1 1z" fill="#ffc400"/><path d="m27.71 4.71-8 8a1 1 0 0 1 -1.42 0 1 1 0 0 1 0-1.42l8-8a1 1 0 1 1 1.42 1.42z" fill="#ffc400"/><g fill="#ffc400"><path d="m13 28a1 1 0 0 1 -1 1h-8a1 1 0 0 1 -1-1v-8a1 1 0 0 1 2 0v7h7a1 1 0 0 1 1 1z"/><path d="m12.71 20.71-8 8a1 1 0 0 1 -1.42 0 1 1 0 0 1 0-1.42l8-8a1 1 0 0 1 1.42 1.42z"/></g></g>
                            </svg>
                        </h5>
                    </a>
                </div>
            </div>
            <iframe src="" class="iframe-embed w-100" id="iframe-embed-url" allowfullscreen="allowfullscreen" scrolling="auto" style="display: none;"></iframe>
            <div id="quiz-iframe"></div>
            <div id="loading"></div>
        </div>
        <div class="card shadow border-0 bg-template mb-2 mt-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 px-3 pb-2 align-self-center text-white p-1" id="info-course-detail">
                        <h6 class="mt-1 font-weight-normal">{{ $item->name }}</h6>
                        <p class="text-justify">{{ \Illuminate\Support\Str::words($item->description, 20) }}</p>
                        <i class="material-icons vm text-warning">star</i> {{ $item->avgRatingStar() }}
                        <span class="float-right">({{ $item->countRatingStar() }} @lang('app.votes'))</span>
                        <br>
                            <i class="material-icons vm">remove_red_eye</i> {{ $item->views .' '. trans('app.view') }}
                            @php
                                switch ($course_time_unit){
                                    case 'day': $time_unit = trans('app.day'); break;
                                    case 'session': $time_unit = trans('app.session'); break;
                                    default : $time_unit = trans('app.hours'); break;
                                }
                            @endphp
                            <span class="float-right"><i class='material-icons vm'>timer</i>
                                @lang('app.duration'): {{ $course_time.' '.$time_unit }}
                            </span>
                        <br>
                            <b>@lang('app.time'): </b> {{ get_date($item->start_date) }} @if($item->end_date) {{' - '. get_date($item->end_date) }} @endif
                        <br>
                            <b>@lang('app.register_deadline'):</b> {{ get_date($item->register_deadline) }}
                            @if($item->getObject())
                                <p><b>@lang('app.trainee_object'):</b> {{ $item->getObject() }}</p>
                            @endif
                        <br>
                        @php
                            $status = $item->getStatusRegister();
                            $text = $text_status($status);
                        @endphp
                        @if($status == 1)
                            <div class="item item-btn">
                                <button type="button" class="btn" id="register_class">{{ $text }}</button>
                            </div>
                        @else
                            <button type="button" class="btn">{{ $text }}</button>
                        @endif
                        <br>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="course_tabs">
                    <div class="col-12 px-0">
                        <div class="swiper-container offline-course-slide">
                            <div class="swiper-wrapper nav-pills mb-2 text-center" id="nav-tab" role="tablist">
                                <a class="swiper-slide nav-item nav-link pl-0 pr-0 active" id="nav-about-tab" data-toggle="tab" href="#nav-program" role="tab" aria-selected="true">Chương trình</a>
                                <a class="swiper-slide nav-item nav-link pl-0 pr-0" id="nav-about-tab" data-toggle="tab" href="#nav-about" role="tab" aria-selected="true">@lang('app.description')</a>
                                <a class="swiper-slide nav-item nav-link pl-0 pr-0" id="nav-courses-tab" data-toggle="tab" href="#nav-courses" role="tab" aria-selected="false">@lang('app.content')</a>
                                <a class="swiper-slide nav-item nav-link pl-0 pr-0" id="nav-reviews-tab" data-toggle="tab" href="#nav-reviews" role="tab" aria-selected="false">@lang('app.comment')</a>
                                <a class="swiper-slide nav-item nav-link pl-0 pr-0" id="nav-rating-level-tab" data-toggle="tab" href="#nav-rating-level" role="tab" aria-selected="true">
                                    {{ trans('latraining.training_evaluation') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="course_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-program" role="tabpanel">
                            <div class="crse_content">
                                <div class="col-12">
                                    @foreach ($offlineActivityOnlines as $lessons_key => $lesson_course)
                                        @php
                                            $online_course = $lesson_course->courseOnline;
                                            $activities = $online_course->getActivities();
                                            $check_setting_join_course = \Modules\Online\Entities\OnlineCourse::checkSettingJoinCourse($online_course->id, $user_id);
                                        @endphp
                                        @foreach($activities as $key => $activity)
                                            @php
                                                $parts = \Modules\Quiz\Entities\QuizPart::checkQuizPartOnline($activity->subject_id);
                                                $checked = $activity->isComplete(profile()->user_id);
                                            @endphp
                                            @if($status == 4)
                                                @if (($activity->activity_id != 2 && userThird()) || !userThird())
                                                    <div class="row mb-2 row_activity row_activity_{{$activity->id}}">
                                                        @if(is_null($parts) && $activity->activity_id == 2)
                                                            <div class="col-10">
                                                                {{ $activity->name }} <br> {{ '('. data_locale('Đã kết thúc hoặc Chưa đăng kí', 'Has ended or Not registered') .')' }}
                                                            </div>
                                                        @else
                                                            @if(isset($parts) && $parts->start_date > date('Y-m-d H:i:s'))
                                                                <div class="col-10">
                                                                    {{ $activity->name .' ('. data_locale('Kỳ thi chưa tới ngày', 'Less exam day') .')' }}
                                                                </div>
                                                            @else
                                                                <div class="col-10 h6" id="activity_name_{{ $activity->id }}" onclick="activityCourse({{$online_course->id}},{{$activity->id}},{{$lesson_course->id}},{{$activity->activity_id}})">{{ $activity->name }}</div>
                                                            @endif
                                                        @endif

                                                        <div class="col-2 float-right" id="check_complete_activity_{{ $activity->id }}">
                                                            @if($checked)
                                                                <img src="{{ asset('themes/mobile/img/check.png') }}" class="avatar avatar-20">
                                                            @else
                                                                <img src="{{ asset('themes/mobile/img/circle.png') }}" class="avatar avatar-20">
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="row mb-2">
                                                    <div class="col-10">
                                                        <span class="instancename">{{ $activity->name }} </span>
                                                    </div>
                                                    <div class="col-2 float-right">
                                                        @if($checked)
                                                            <img src="{{ asset('themes/mobile/img/check.png') }}" class="avatar avatar-20">
                                                        @else
                                                            <img src="{{ asset('themes/mobile/img/circle.png') }}" class="avatar avatar-20">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                    @if ($offlineActivityMettings->count() > 0)
                                        @foreach ($offlineActivityMettings as $key => $activityMetting)
                                            <div class="row mb-2 row_activity line_active_activity_ms_team_{{ $activityMetting->id }}">
                                                <div class="col-10">
                                                    @if ($activityMetting->checkLink != 0)
                                                        <a class="activityItem link_activity" onclick="checkLinkHandle({{ $activityMetting->checkLink }})">
                                                    @else
                                                        <a class="activityItem link_activity" href="{{ $activityMetting->linkMetting }}" target="_blank">
                                                    @endif
                                                            <span>{{ $key + 1 }}./</span>
                                                            <span>
                                                                {{ trans('latraining.session') }} {{ $key + 1 }} ({{ $activityMetting->start_time }} - {{ $activityMetting->end_time }})
                                                            </span>
                                                        </a>
                                                </div>
                                                <div class="col-2 float-right" id="check_complete_activity_ms_team_{{ $activityMetting->id }}">
                                                    @if($activityMetting->check_attendance == 1)
                                                        <img src="{{ asset('themes/mobile/img/check.png') }}" class="avatar avatar-20">
                                                    @else
                                                        <img src="{{ asset('themes/mobile/img/circle.png') }}" class="avatar avatar-20">
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-about" role="tabpanel">
                            <div class="text-justify content_detail_offline">
                                {!! $item->content !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-courses" role="tabpanel">
                            <div class="crse_content">
                                @php
                                    $documents = json_decode($item->document)
                                @endphp
                                @if ( !empty($documents) )
                                    @foreach ($documents as $key => $document)
                                        @if($item->checkPdf( $item->id,$key) )
                                            <a href="javascript:void(0);" onclick="loadSpinner('{{ route('module.offline.view_pdf', ['id' => $item->id, 'key' => $key]) }}', 1, 1)" target="_blank" class="btn click-view-doc mb-2 text-white" data-id="{{$item->id}}" style="background-color: #1B4486; text-align: left;">
                                                <i class="fa fa-eye" aria-hidden="true"></i> {{ basename($document) }}
                                            </a>
                                        @else
                                            <a href="{{ link_download('uploads/'.$document) }}" data-turbolinks="false" class="text-white">
                                                <i class="fa fa-download" aria-hidden="true"></i> {{ basename($document) }}
                                            </a>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-reviews" role="tabpanel">
                            @include('themes.mobile.frontend.offline_course.comment')
                        </div>
                        <div class="tab-pane fade" id="nav-rating-level" role="tabpanel">
                            <table class="tDefault table table-hover bootstrap-table" id="table-rating-level">
                                <thead>
                                <tr>
                                    <th data-field="rating_url" data-formatter="rating_url_formatter" data-align="center">Đánh giá</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="" id="frm-class" method="post" class="form-ajax">
        @csrf
        <input type="hidden" name="class_id" id="class_id" value="">
    </form>
@endsection
@section('modal')
    <div class="modal fade" id="modal-register-class">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('latraining.classroom') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    @if ($classes->count() > 0)
                        @foreach ($classes as $class)
                            @php
                                $user_register = Modules\Offline\Entities\OfflineRegister::whereCourseId($course_id)->where('class_id', $class->id)->count();
                                $schedules = Modules\Offline\Entities\OfflineSchedule::whereCourseId($course_id)->where('class_id', $class->id)->get();
                            @endphp
                            <div class="row mb-2">
                                <div class="col-12">
                                    {{ $class->name .' ('. $class->code .')' }} <br>
                                    {{ trans('latraining.quantity') .': '. $user_register .'/'. $class->students }} <br>
                                    {{ trans('latraining.time').': '. get_date($class->start_date) }} <i class="fa fa-arrow-right"></i> {{ get_date($class->end_date) }}

                                    <div class="ml-3 mt-2">
                                        @foreach ($schedules as $key => $schedule)
                                            @php
                                                $teacher = App\Models\Categories\TrainingTeacher::find($schedule->teacher_main_id);
                                            @endphp
                                            <div class="m-1">
                                                - {{ trans('latraining.session') .' '. ($key+ 1) .': ' }}
                                                {{ get_date($schedule->start_time, 'H:i') .' - '. get_date($schedule->end_time, 'H:i') }}
                                                ({{ get_date($schedule->lesson_date) }}).
                                                {{ trans('latraining.teacher') .': '. $teacher->name .' ('. $teacher->code .')' }}
                                            </div>
                                        @endforeach
                                    </div>

                                    <a href="javascript:void(0);" class="btn mt-2 w-100 p-2" onclick="submitRegisterClass({{$item->id}},{{$class->id}})">
                                        {{ trans('latraining.register') }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    @else
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    <span>@lang('app.not_found')</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        var swiper = new Swiper('.offline-course-slide', {
            slidesPerView: 3,
            spaceBetween: 0,
            breakpoints: {
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 0,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 0,
                },
                640: {
                    slidesPerView: 2,
                    spaceBetween: 0,
                },
                320: {
                    slidesPerView: 2,
                    spaceBetween: 0,
                }
            }
        });

        $('#nav-tab').on('click', '.nav-item', function () {
            $('a[data-toggle="tab"]').removeClass('active');
        });

        $(document).ready(function(){
            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab-offline-course{{$item->id}}', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab-offline-course{{$item->id}}');
            if(activeTab){
                $('a[data-toggle="tab"]').removeClass('active');
                $('#nav-tab a[href="' + activeTab + '"]').tab('show');
                $('#nav-tab a[href="' + activeTab + '"]').addClass('active');
            }
        });

        $('.active_file').on('click', function () {
            $(this).closest('.row').find('.file').prop('checked', true);
        });
        $('.active_url').on('click', function () {
            $(this).closest('.row').find('.url').prop('checked', true);
        });

        // GỌI HOẠT ĐỘNG SCROM
        $('#iframe-embed-url').css('display','none');
        //$('#thumbnail-image-course').css('display','block');
        $('#iframe_activity').hide();

        function activityCourse(id,aid,lesson_id,type) {
            var activity_name = $('#activity_name_'+aid);
            var text_old = activity_name.html();

            activity_name.html('<i class="fa fa-spinner fa-spin"></i>');

            if(type != 8){
                $('#iframe_activity').show();
                $('#loading').show();
                $('#thumbnail-image-course').css('display','none');
                $('#iframe-embed-url').css({'display':'block', 'height':'250px'});
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
                        hide_online();
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
            $('.wrapper').css('padding-bottom', '0px');
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
            $('.wrapper').css('padding-bottom', '80px');
        }
        function hide_online() {
            $('.register_trainee_online').hide('slow');
            $('.info_online_course').hide('slow');
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

        // MỞ LỊCH SỬ SCORM
        $('.table_history').hide();
        function opend_history_scorm(id, key) {
            $('.table_history').hide();
            $('#table_history_'+key).show();
            var url = "{{ route('module.online.attempts', ':id') }}";
            url = url.replace(':id',id);
            var table_scrom = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: url,
                table: '#table-history-scrom-'+id
            });
        }

        function index_formatter(value, row, index) {
            return (index + 1);
        }

        function review_formatter(value, row, index) {
            if (row.after_review == 1 || row.closed_review == 1) {
                return '<a href="'+ row.review_link +'">Xem lại</a>'
            }
            return '<span class="text-muted">Không được xem</span>';
        }

        // MỞ LỊCH SỬ KỲ THÌ
        function opend_history_quiz(quizId, partId, key) {
            $('.table_history').hide();
            $('#table_history_'+key).show();
            var url = "{{ route('module.quiz_mobile.doquiz.data_attempt_history', ['quiz_id' => ':id', 'part_id' => ':partId']) }}";
            url = url.replace(':id',quizId);
            url = url.replace(':partId',partId);
            var table = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: url,
                table: '#table-history-quiz-'+quizId
            });
        }

        function rating_url_formatter(value, row, index) {
            var btn_rating_level_url = '<a href="#" class="btn text-white">Không thể đánh giá</a>';
            if(row.rating_level_url){
                btn_rating_level_url = '<a href="'+ row.rating_level_url +'" class="btn text-white">Đánh giá</a>';
            }

            return '<b>'+ row.rating_name +'</b> <br>' + '{{ trans("latraining.time") }}: ' + row.rating_time + '<br>' + '{{ trans("latraining.status") }} '+ row.rating_status + '<br>' + btn_rating_level_url;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.detail.rating_level.getdata', ['id' => $item->id]) }}',
            table: '#table-rating-level',
        });

        $('#register_class').on('click', function(){
            $('#modal-register-class').modal();
        });

        //Đăng ký lớp học khoá học offline
        function submitRegisterClass(id, class_id) {
            var answer = "{{ trans('laother.note_user_want_register') }}?";
            Swal.fire({
                title: answer,
                showCancelButton: true,
                confirmButtonText: 'Có',
                cancelButtonText: `Không`,
            }).then((result) => {
                if (result.value) {
                    $('#frm-class #class_id').val(class_id);
                    var url_link = "{{ route('module.offline.register_course', ['id' => ':id']) }}";
                    url_link = url_link.replace(':id',id);
                    $('#frm-class').attr('action', url_link);
                    var form = $('#frm-class');
                    form.submit();
                } else {
                    return false;
                }
            });
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
                $('.wrapper').css('padding-bottom', '0px');
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
