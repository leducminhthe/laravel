@extends('quiz::layout.app')

@section('page_title', $quiz->name)

@section('content')
    @php
        $get_color_button = \App\Models\SettingColor::where('name','color_button')->first();

        $color_text_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_button->text;
        $color_hover_text_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_button->hover_text;
        $background_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_button->background;
        $hover_background_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_button->hover_background;
    @endphp
    <style>
        a:hover{
            color: #fff !important;
        }

        ol.breadcrumb{
            color: #246EEC;
            background-color: #fff;
        }

        #first-info-user .row{
            background: white;
            margin-top: 10px;
            border-radius: 10px;
            align-items: center;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        }

        #first-info-user .header_right {
            text-align: center
        }

        #first-info-user .header_right .name_user{
            color: #14498a;
            font-weight: bold;
        }

        #second-name-quiz p{
            border-radius: 10px;
            color: white;
            background: #14498a;
            font-size: 16px;
            padding: 20px;
            font-weight: bold;
        }
        #three-info-quiz .three-info-quiz-1, #three-info-quiz .three-info-quiz-2{
            background: #D9D9D9;
            border-radius: 30px;
            padding: 20px;
            font-weight: bold;
            text-align: center;
        }
        #three-info-quiz h3 {
            color: #14498a;
        }

        #four-go-quiz .btn-go-quiz,
        .btn-send-suggest{
            color: {{ $color_text_button }};
            background: {{ $background_button }};
        }
        #four-go-quiz .btn-go-quiz:hover,
        .btn-send-suggest:hover{
            color: {{ $color_hover_text_button }};
            background: {{ $hover_background_button }};
        }

        #first-info-user .opts_account img{
            width: 100px;
            height: 90px;
            object-fit: cover
        }
        .content_quiz{
            margin-top: 18px !important
        }
        .icon_info_quiz {
            max-height: 80px
        }
        a:hover{
            color: black !important;
        }
    </style>
    <div id="page-navbar" class="clearfix">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="m-2">
                    <a itemprop="url" href="/" class="">
                        {{ trans('lamenu.home_page') }}
                    </a>
                </li>
                <li class="m-2">
                    <a itemprop="url" href="{{ route('quiz_react') }}" class=""> {{ trans('lamenu.quiz_manager') }} </a>
                </li>
                <li class="m-2 name_quiz_part">
                    <a tabindex="0" class="">{{ $quiz->name }}</a>
                </li>
            </ol>
        </nav>
        <div class="breadcrumb-button"></div>
    </div>

    <div class="row" style="margin-left: 3%; margin-right: 3%">
        <div class="col-12" id="first-info-user">
            <div class="row">
                <div class="col-6 col-md-3">
                    <img src="{{ image_quiz($quiz->img) }}" alt="" class="w-100">
                </div>
                <div class="col-6 col-md-9">
                    <div class="header_right">
                        <a href="javascript:void(0)" class="opts_account">
                            <img src="{{ image_user($profile->avatar) }}" alt="">
                        </a>
                        <ul>
                            <li class="mx-2 name_user pt-2">
                                <span>{{ $profile->full_name }}</span> <br>
                                <span>{{ trans('latraining.employee_code') }}: {{ $profile->code }}</span> <br>
                            </li>
                        </ul>
                        <span class="mx-2 name_user">Email: {{ $profile->email }}</span> <br>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 p-0 text-center mt-3" id="second-name-quiz">
           <p>{{ trans('laquiz.welcome_to_exam') }} <br>{{ \Illuminate\Support\Str::upper($quiz->name) }}</p>
        </div>

        <div class="col-12 mt-3" id="three-info-quiz">
            <div class="row">
                <div class="col-12 col-md-6 info_quiz p-3">
                    <h3><i class="fa fa-comment"></i> {{ trans('laother.noted') }} </h3>
                    <p class="text-black ml-4">
                        1. {{ trans('laquiz.time_counting') }}. <br>
                        2. {{ trans('laquiz.test_automatic') }}. <br>
                        3. {{ trans('laquiz.note_back_quiz') }}. <br>
                        4. {{ trans('laquiz.note_read_question') }}!
                    </p>
                    <h3 class="content_quiz"><i class="fa fa-briefcase"></i> {{ \Illuminate\Support\Str::upper(trans('latraining.content')) }}</h3>
                    <p class="text-black ml-4">
                        {!! nl2br(($descriptions_quiz))  !!}
                    </p>
                </div>
                <div class="col-12 col-md-6 px-3 info_test_quiz">
                    <div class="row mx-3 warpped_info">
                        <div class="col-6 info_test">
                            <div class="row m-0 w-100">
                                <div class="col-4 p-0">
                                    <img class="icon_info_quiz" src="{{ asset('images/web-03.png') }}" alt="" width="100%">
                                </div>
                                <div class="col-8 px-1">
                                    <h4 class="my-2">{{ trans('lareport.num_question') }}</h4>
                                    <h3 class="mt-0">{{ $count_quiz_question }} </h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 info_test">
                            <div class="row m-0 w-100">
                                <div class="col-4 p-0">
                                    <img class="icon_info_quiz" src="{{ asset('images/web-04.png') }}" alt="" width="100%">
                                </div>
                                <div class="col-8 px-1">
                                    <h4 class="my-2">{{ trans('latraining.time_quiz') }}</h4>
                                    <h3 class="mt-0">{{ $quiz->limit_time .' '. trans('app.min') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 info_test">
                            <div class="row m-0 w-100">
                                <div class="col-4 p-0">
                                    <img class="icon_info_quiz" src="{{ asset('images/web-05.png') }}" alt="" width="100%">
                                </div>
                                <div class="col-8 px-1">
                                    <h4 class="my-2">{{ trans('latraining.pass_score') }}</h4>
                                    <h3 class="mt-0">{{ $quiz->pass_score }}/{{ $quiz->max_score }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 info_test">
                            <div class="row m-0 w-100">
                                <div class="col-4 p-0">
                                    <img class="icon_info_quiz" src="{{ asset('images/web-06.png') }}" alt="" width="100%">
                                </div>
                                <div class="col-8 px-1">
                                    <h4 class="my-2">{{ trans('latraining.number_test') }}</h4>
                                    <h3 class="mt-0">{{ $quiz->max_attempts == 0 ? trans('latraining.unlimited') : $quiz->max_attempts }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-3 text-center" id="four-go-quiz">
            @if ($block_quiz)
                <p class="h3 text-danger">CẤM THI</p>
            @elseif ($user_locked)
                <p class="h3 text-danger">{{ trans('laquiz.notify_user_locked') }}</p>
            @else
                @if($can_create)
                    <form action="{{ route('module.quiz.doquiz.create_quiz', ['quiz_id' => $quiz->id, 'part_id' => $part->id]) }}" method="post">
                        {{ csrf_field() }}

                        <button type="submit" class="btn mt-2 btn-go-quiz">
                            <h3 class="mb-0">
                                <i class="fa fa-play-circle"></i> {{ strtoupper(trans('laquiz.entry_exam')) }}
                            </h3>
                        </button>

                        @if ($quiz->full_screen == 1)
                            <p class="text-danger mt-2 h4">{{ trans('laquiz.note_full_screen') }}</p>
                        @endif
                    </form>
                @else
                    <p class="text-danger">{{ trans('laquiz.out_do_quiz') }}</p>
                @endif
            @endif
        </div>

        <div class="col-md-12 mt-3">
            <div id="history_quiz">
                <h4><button class="btn"><i class="fa fa-list-ul"></i> {{ strtoupper(trans('laquiz.quiz_history')) }}</button></h4>
                <table class="tDefault table table-hover bootstrap-table text-nowrap table-bordered" id="histories_quiz">
                    <thead>
                        <tr>
                            <th data-formatter="index_formatter" data-align="center">{{ trans('latraining.stt') }}</th>
                            <th data-field="start_date" data-align="center">{{ trans('lareport.start_time') }}</th>
                            <th data-field="end_date" data-align="center">{{ trans('lareport.end_time') }}</th>
                            <th data-field="timer" data-align="center">{{ trans('backend.timer') }}</th>
                            <th data-field="grade" data-align="center">{{ trans('latraining.score') }}</th>
                            <th data-field="status" data-align="center">{{ trans('latraining.status') }}</th>
                            <th data-field="review" data-align="center" data-formatter="review_formatter">{{ trans('latraining.review') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="col-12 mt-3">
            <form action="{{ route('module.quiz.doquiz.user_review_quiz', ['quiz_id' => $quiz->id, 'part_id' => $part->id]) }}" method="post" class="form-ajax">
                <div class="row mb-2">
                    <div class="col-8">
                        <h4><button class="btn"><i class="fa fa-list-ul"></i> {{ strtoupper(trans('laquiz.suggest_quiz')) }}</button></h4>
                    </div>
                    <div class="col-4 text-right">
                        @if (!$quiz_user_review)
                            <button type="submit" class="btn mt-2 btn-send-suggest">{{ trans('labutton.send') }}</button>
                        @endif
                    </div>
                </div>
                @if (!$quiz_user_review)
                    <textarea name="content_review" id="" rows="5" class="form-control w-100" placeholder="{{ trans('laquiz.note_suggest_quiz') }}" required></textarea>
                @else
                    {{ $quiz_user_review->content }}
                @endif
            </form>
        </div>
    </div>

    @if($can_create)
    <div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('module.quiz.doquiz.create_quiz', ['quiz_id' => $quiz->id, 'part_id' => $part->id]) }}" method="post" class="form-ajax">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ trans('laquiz.start_exam') }}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        {{ trans('laquiz.limit_exam') }} <b>{{ $quiz->limit_time .' '. trans('latraining.minute') .'.' }}</b><br>
                        {{ trans('laquiz.note_start_quiz') }} <br>
                        {{ trans('laquiz.note_time_start') }} <br>
                        {{ trans('laquiz.want_to_start') }}
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn"><i class="fa fa-edit"></i> {{ trans('laquiz.do_quiz') }}</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> {{ trans('lacore.cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <script type="text/javascript">

        $("#go-quiz").on('click', function () {
            $("#myModal").modal();
        });

        function index_formatter(value, row, index) {
            return (index + 1);
        }

        function review_formatter(value, row, index) {
            if ('{{ !$user_locked }}' && row.state == "inprogress"){
                return '<a href="'+ row.review_link +'">{{ trans("laquiz.entry_exam") }}</a>';
            }else if (row.after_review == 1 || row.closed_review == 1) {
                if ('{{ $user_locked }}' && row.state == "inprogress"){
                    return '<span class="text-muted">{{ trans("latraining.no_review") }}</span>';
                }else{
                    return '<a href="'+ row.review_link +'">'+((row.state == "completed") ? "{{ trans("latraining.review") }}" : "{{ trans("laquiz.entry_exam") }}")+'</a>';
                }
            }

            return '<span class="text-muted">{{ trans("latraining.no_review") }}</span>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.doquiz.attempt_history', ['quiz_id' => $quiz->id, 'part_id' => $part->id]) }}',
        });
    </script>
@stop
