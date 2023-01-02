@extends('quiz::layout.app')

@section('page_title', $quiz->name)

@section('content')
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

    #four-go-quiz .btn-go-quiz{
        background: #00AF50 !important;
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

<div class="row" style="margin-left: 3%; margin-right: 3%">
    <div class="col-12" id="first-info-user">
        <div class="row">
            <div class="col-6 col-md-3">
                <img src="{{ image_quiz($quiz->img) }}" alt="" class="w-100">
            </div>
            <div class="col-6 col-md-9">
                <div class="header_right">
                    <a href="javascript:void(0)" class="opts_account">
                        <img src="{{ $profile->avatar ? image_user($profile->avatar) : asset('/images/design/user_50_50.png') }}" alt="">
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

    <div class="col-12 mt-3 p-0" id="three-info-quiz">
        <div class="row">
            <div class="col-12 col-md-6 info_quiz p-3">
                <h3><i class="fa fa-comment"></i> {{ trans('laother.noted') }} </h3>
                <p class="text-black ml-4">
                    1. {{ trans('laquiz.time_counting') }}. <br>
                    2. {{ trans('laquiz.test_automatic') }}. <br>
                    3. {{ trans('laquiz.note_back_quiz') }}. <br>
                    4. {{ trans('laquiz.note_read_question') }}!
                </p>
                <h3 class="content_quiz"><i class="fa fa-briefcase"></i> {{ (trans('latraining.content')) }}</h3>
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
                <form action="{{ route('module.quiz.doquiz.create_quiz', ['quiz_id' => $quiz->id, 'part_id' => $part->id, 'quiz_by_online' => 1]) }}" method="post">
                    {{ csrf_field() }}

                    <button type="submit" class="btn mt-2 btn-go-quiz">
                        <h3><i class="fa fa-play-circle"></i> {{ strtoupper(trans('laquiz.entry_exam')) }} </h3>
                    </button>

                    @if ($quiz->full_screen == 1)
                        <p class="text-danger mt-2 h4">{{ trans('laquiz.note_full_screen') }}</p>
                    @endif
                </form>
            @else
                <p><b>{{ trans('laquiz.out_do_quiz') }}</b></p>
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

    <script type="text/javascript">
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
                    return '<a target="'+((row.state == "completed") ? "_blank" : "")+'" href="'+ row.review_link +'">'+((row.state == "completed") ? "{{ trans("latraining.review") }}" : "{{ trans("laquiz.entry_exam") }}")+'</a>';
                }
            }

            return '<span class="text-muted">{{ trans("latraining.no_review") }}</span>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.doquiz.attempt_history', ['quiz_id' => $quiz->id, 'part_id' => $part->id]) }}',
        });
    </script>
</div>
@stop
