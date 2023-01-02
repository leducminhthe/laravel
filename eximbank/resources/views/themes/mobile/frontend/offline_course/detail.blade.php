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
        .rating-star {
            font-size: 0.8rem;
            width: 1.3rem;
            height: 1.3rem;
            position: relative;
            display: block;
            float: left;
        }

        .full-star:before {
            color: #f2b01e;
            content: "\f005";
            position: absolute;
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            left: 0;
        }

        .empty-star:before {
            content: "\f005";
            position: absolute;
            left: 0;
            overflow: hidden;
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
        }
        .reviews_left {
            float: left;
            width: 100%;
            background: #fff;
            padding: 10px;
            border: 1px solid #efefef;
            border-radius: 10px;
        }

        .total_rating {
            display: flex;
            width: 100%;
            font-size: 16px;
            justify-items: center;
            background: #f7f7f7;
            border: 1px solid #efefef;
            padding: 10px 20px;
            border-radius: 20px;
            align-items: center;
        }
        .rating_badge {
            background: #f2b01e;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            text-align: center;
            padding: 7px;
            margin-right: 5px;
        }
        .rating-box {
            color: #dedfe0;
            display: flex;
            flex-basis: 30%;
        }
        ._rate002 {
            font-size: 13px;
            font-weight: 500;
            text-align: left;
            margin-bottom: 0;
            color: #333;
            margin-left: 10px;
            line-height: 22px;
        }
        ._rate004 {
            display: flex;
            cursor: pointer;
            height: auto;
            margin-top: 20px;
            border-radius: 0px;
        }

        .progress {
            display: -ms-flexbox;
            display: flex;
            height: 1rem;
            overflow: hidden;
            font-size: .75rem;
            background-color: #e9ecef;
            border-radius: 0.25rem;
        }
        .progress {
            background-color: #dee2e6 !important;
        }
        .progress1 {
            display: flex;
            flex-basis: 50%;
            margin-right: 10px;
            height: 1.4rem !important;
        }
    </style>
@endsection

@section('content')
    <div class="container p-1">
        <div class="">
            <img src="{{ image_offline($item->image) }}" alt="" class="w-100 thumbnail-image" id="thumbnail-image-course">
        </div>
        <div class="align-self-center p-1" id="info-course-detail">
            <h6 class="mt-1 font-weight-normal">{{ $item->name }}</h6>
            <div class="row m-0">
                <div class="col p-0" data-toggle="modal" data-target="#myModalOfflineRatingStar">
                    @for ($i = 1; $i < 6; $i++)
                        <span class="rating-star full-star"></span>
                    @endfor
                    <span class="vm">{{ $item->avgRatingStar() }}</span>
                </div>
                <div class="col-auto">
                    <i class="material-icons vm">remove_red_eye</i> {{ $item->views .' '. trans('app.view') }}
                </div>
                <div class="col-auto text-right p-0">
                    @if ($get_bookmarked)
                        <a class="mr-1 check_bookmark"
                            href="{{ route('frontend.home.remove_course_bookmark',[$item->id,2,0,'my_course' => $my_course]) }}">
                            <i class="fas fa-heart check-heart fa-2x"></i>
                        </a>
                    @else
                        <a class="mr-1 check_bookmark"
                            href="{{ route('frontend.home.save_course_bookmark',[$item->id,2,0,'my_course' => $my_course]) }}">
                            <i class="far fa-heart fa-2x"></i>
                        </a>
                    @endif
                </div>
            </div>
            @if ($item->template_rating_teacher_id)
                <div class="teaching_organization row d_flex_align mt-2">
                    <div class="col-8">
                        <h6 class="mt-2 text-muted">{{ trans("latraining.teaching_organization") }}: </h6>
                    </div>
                    <div class="col-4 text-right">
                        @if ($organization_user)
                            <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.offline.detail.rating_teacher.edit', ['course_id' => $item->id]) }}', 0, 1)" class="btn link_btn">Xem</a>
                        @else
                            <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.offline.detail.rating_teacher', ['course_id' => $item->id]) }}', 0, 1)" class="btn link_btn">Đánh giá</a>
                        @endif
                    </div>
                </div>
            @endif
            <h6 class="my-1 text-muted">{{ trans('app.info') }}:</h6>
            <div class="ml-2 text-muted">
                <b>@lang('app.time'): </b> {{ get_date($item->start_date) }} @if($item->end_date) {{' - '. get_date($item->end_date) }} @endif <br>
                <b>@lang('app.register_deadline'):</b> {{ get_date($item->register_deadline) }} <br>
                @php
                    switch ($course_time_unit){
                        case 'day': $time_unit = trans('app.day'); break;
                        case 'session': $time_unit = trans('app.session'); break;
                        default : $time_unit = trans('app.hours'); break;
                    }
                @endphp
                <b>@lang('app.duration'): {{ $course_time.' '.$time_unit }}</b>
            </div>
            @if ($item->content)
                <h6 class="my-1 text-muted">{{ trans('app.content') }}:</h6>
                <div class="text-justify text-muted content_detail_offline">
                    {!! $item->content !!}
                </div>
            @endif
            <div class="text-justify mt-2 text-muted">
                @include('themes.mobile.frontend.offline_course.comment')
            </div>
        </div>
    </div>

    <form action="" id="frm-class" method="post" class="form-ajax">
        @csrf
        <input type="hidden" name="class_id" id="class_id" value="">
    </form>
@endsection

@section('content_footer')
    @php
        $status = $item->getStatusRegister();
        $text = $text_status($status);
    @endphp
    <div class="row m-0">
        <div class="col-6 p-0 text-center d-flex justify-content-center align-items-center">
            <a href="{{ $url_quiz ? $url_quiz : 'javascript:void(0)' }}" class="">
                <div style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-34.svg') }}) no-repeat center;
                    -webkit-mask-size: 20px 20px;
                    width: 20px;
                    height: 20px;
                    margin: 0 auto;
                    background: {{ $url ? '#0c2188' : '#919193' }}"
                ></div>
                <div class="" style="color: {{ $url ? '#0c2188' : '#919193' }}">{{ trans('lamenu.quiz') }}</div>
            </a>
        </div>
        <div class="col-6 p-0">
            @if($status == 1)
                <div class="item item-btn">
                    <button type="button" class="btn bg-template" id="register_class">{{ $text }}</button>
                </div>
            @elseif ($status == 4)
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.offline.detail.go_activity', ['course_id'=>$item->id]) }}', 0, 1)" class="">
                    <button type="button" class="btn bg-template">
                        {{ $text }}
                    </button>
                </a>
            @else
                <button type="button" class="btn bg-template">{{ $text }}</button>
            @endif
        </div>
    </div>
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

    <div class="modal fade" id="myModalOfflineRatingStar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('app.rating')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="student_reviews">
                        <div class="reviews_left">
                            <div class="total_rating">
                                <div class="_rate001 rating_badge">{{ $item->avgRatingStar() }}</div>
                                <div class="rating-box">
                                    @for ($i = 1; $i < 6; $i++)
                                        <span class="rating-star
                                            @if(!$isRating) empty-star rating
                                            @elseif($isRating && $isRating->num_star >= $i) full-star
                                            @endif" data-value="{{ $i }}">
                                        </span>
                                    @endfor
                                </div>
                                <div class="_rate002">{{ $isRating ? trans('laother.you_rated') : "" }}</div>
                            </div>

                            <div class="_rate003">
                                <div class="_rate004">
                                    @php
                                        $star5 = \Modules\Offline\Entities\OfflineRating::getRatingValue($item->id,5);
                                    @endphp
                                    <div class="progress progress1">
                                        <div class="progress-bar w-{{ $star5 }}" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="rating-box">
                                        @for ($i = 0; $i < 5; $i++)
                                            <span class="rating-star full-star"></span>
                                        @endfor
                                    </div>
                                    <div class="_rate002">{{ $star5 }}%</div>
                                </div>
                                <div class="_rate004">
                                    <div class="progress progress1">
                                        @php
                                            $star4 = \Modules\Offline\Entities\OfflineRating::getRatingValue($item->id,4);
                                        @endphp
                                        <div class="progress-bar w-{{ $star4 }}" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="rating-box">
                                        @for ($i = 0; $i < 5; $i++)
                                            <span class="rating-star @if ($i < 4)
                                                full-star
                                            @else
                                                empty-star
                                            @endif "></span>
                                        @endfor
                                    </div>
                                    <div class="_rate002">{{ $star4 }}%</div>
                                </div>
                                <div class="_rate004">
                                    <div class="progress progress1">
                                        @php
                                            $star3 = \Modules\Offline\Entities\OfflineRating::getRatingValue($item->id,3);
                                        @endphp
                                        <div class="progress-bar w-{{ $star3 }}" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="rating-box">
                                        @for ($i = 0; $i < 5; $i++)
                                            <span class="rating-star @if ($i < 3)
                                                full-star
                                            @else
                                                empty-star
                                            @endif "></span>
                                        @endfor
                                    </div>
                                    <div class="_rate002">{{ $star3 }}%</div>
                                </div>

                                <div class="_rate004">
                                    <div class="progress progress1">
                                        @php
                                            $star2 = \Modules\Offline\Entities\OfflineRating::getRatingValue($item->id,2);
                                        @endphp
                                        <div class="progress-bar w-{{ $star2 }}" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="rating-box">
                                        @for ($i = 0; $i < 5; $i++)
                                            <span class="rating-star @if ($i < 2)
                                                full-star
                                            @else
                                                empty-star
                                            @endif "></span>
                                        @endfor
                                    </div>
                                    <div class="_rate002">{{ $star2 }}%</div>
                                </div>
                                <div class="_rate004">
                                    <div class="progress progress1">
                                        @php
                                            $star1 = \Modules\Offline\Entities\OfflineRating::getRatingValue($item->id,1);
                                        @endphp
                                        <div class="progress-bar w-{{ $star1 }}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="rating-box">
                                        @for ($i = 0; $i < 5; $i++)
                                            <span class="rating-star @if ($i < 1)
                                                full-star
                                            @else
                                                empty-star
                                            @endif "></span>
                                        @endfor
                                    </div>
                                    <div class="_rate002">{{ $star1 }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">
        var rating = $('.rating');
        ratingStars(rating);
        function ratingStars(element) {
            element.on('mouseover',function () {
                var onStar = parseInt($(this).data('value'), 10);
                $(this).parent().children('.rating-star').each(function(e){
                    if (e < onStar) {
                        $(this).addClass('full-star');
                    }
                    else {
                        $(this).removeClass('full-star');
                    }
                });
            }).on('mouseout', function(){
                $(this).each(function(e){
                    $(this).removeClass('full-star');
                });
            })

            element.on("click", function(){
                var onStar = parseInt($(this).data('value'), 10);
                var stars = $(this).parent().children('.rating-star');
                for (i = 0; i < stars.length; i++) {
                    $(stars[i]).removeClass('full-star');
                }

                for (i = 0; i < onStar; i++) {
                    $(stars[i]).addClass('selected');
                    $(stars[i]).addClass('text-warning');
                    $(stars[i]).removeClass('full-star');
                }

                var ratingValue = parseInt($('.rating-star.selected').last().data('value'), 10);
                sendRatingStars(ratingValue);
            })

            function sendRatingStars(ratingValue) {
                var url = '{{ route('module.offline.rating',$item->id) }}';
                var my_course = '{{ $my_course }}';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        'star':ratingValue,
                        'my_course': my_course
                    },
                    dataType: 'json',
                    success: function(data) {
                        Swal.fire({
                            title: data.message,
                        });

                        window.location = data.redirect;
                    }
                })
            }
        };

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
