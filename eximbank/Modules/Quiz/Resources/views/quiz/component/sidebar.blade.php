@php
    if(url_mobile()){
        $w_100 = 'w-100';

        $url_submit = route('module.quiz_mobile.doquiz.submit', ['quiz_id' => $quiz->id, 'part_id' => $part_id, 'attempt_id' => $attempt->id, 'quiz_by_online' => $quiz_by_online]);

        //Check xem có phải xem kỳ thi trong khoá Online không
        if($quiz->quiz_type == 1){
            $return_exam_screen = route('themes.mobile.frontend.online.detail', [$quiz->course_id]);
        }else{
            $return_exam_screen = route('module.quiz_mobile.doquiz.index', [ 'quiz_id' => $quiz->id, 'part_id' => $part_id]);
        }
    }else{
        $url_submit = route('module.quiz.doquiz.submit', ['quiz_id' => $quiz->id, 'part_id' => $part_id, 'attempt_id' => $attempt->id, 'quiz_by_online' => $quiz_by_online]);
        $w_100 = '';

        //Check xem có phải xem kỳ thi trong khoá Online không
        if($quiz->quiz_type == 1){
            $return_exam_screen = route('module.online.detail_online', [$quiz->course_id]);
        }else{
            $return_exam_screen = route('module.quiz.doquiz.index', [ 'quiz_id' => $quiz->id, 'part_id' => $part_id]);
        }
    }
@endphp
<div class="quiz-block">
    <div class="card block-item" id="info-number-question">
        @if(!url_mobile())
        <div class="card-header">
            <span>{{ trans('latraining.question') }}</span>: <span class="font-weight-bold"> <span id="num-question-selected">0</span>{{ '/'. count($questions) }}</span>
        </div>
        @endif
        <div class="card-body p-2">
            @if (!empty($questions))
                @if (url_mobile() || isMobile())
                <div class="swiper-container quiz-slide">
                    <div class="swiper-wrapper">
                @endif

                    @foreach($questions as $index => $question)
                        <a href="javascript:void(0)" class="btn select-question swiper-slide
                        @if(@$question['selected']) question-selected
                        @endif" id="select-q{{ $question['id'] }}"
                        data-quiz-page="{{ ceil(($question['qindex']) / $quiz->questions_perpage) }}"
                        data-id="{{ $question['id'] }}"
                        >
                            <span class="thispageholder"></span>
                            <span class="trafficlight"></span>
                            <span class="accesshide">{{ ($question['qindex']) }}</span>
                            @if(@$question['selected'] && $attempt_finish && (($quiz_setting && $quiz_setting->after_test_yes_no == 1) && $attempt_finish == 1) || (($quiz_setting && $quiz_setting->exam_closed_yes_no == 1) && (date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))))
                                <div class="flag-item-{{ $question['score'] == ($question['score_group'] * $question['max_score']) ? 'success' : 'error' }}"></div>
                            @endif
                        </a>
                    @endforeach

                    @if (url_mobile() || isMobile())
                        </div>
                </div>
                @endif
            @endif
            @if (!$attempt_finish)
                <hr>
                <form action="{{ $url_submit }}" method="post" class="form-ajax text-center mb-2" data-success="submit_success" id="submit_sidebar">
                    <button type="button" class="btn send-quiz {{ $w_100 }}" data-action="save"><i class="fa fa-send-o"></i> {{ trans('laquiz.submit_exam') }}</button>
                </form>
            @endif
        </div>
    </div>

    @if(!url_mobile())
        @if(!$attempt_finish)
            <div class="card block-item">
                <div class="card-header">
                    <span>{{trans('latraining.time_quiz')}}</span>
                </div>
                <div class="card-body">
                    <div id="clockdiv"></div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center">
                    <a href="{{ $return_exam_screen }}" class="btn">
                        <i class="fa fa-mail-reply"></i>
                        {{ trans('laquiz.return_exam_screen') }}
                    </a>
                </div>
            </div>
        @endif
    @endif
</div>

<script type="text/javascript">
    @if($attempt_finish)
        var countDownDate = null;
        var times_shooting_webcam = null;
        var times_shooting_question = null;
    @else
        var countDownDate = new Date("{{ date('D, d M y H:i:s', $attempt->timestart + (intval($quiz->limit_time) * 60)) }}").getTime();
        var timeServer = new Date("{{ date('D, d M y H:i:s') }}");
        var startTime = new Date("{{ date('D, d M y H:i:s') }}");
console.log(new Date("{{ date('D, d M y H:i:s', $attempt->timestart) }}"));
console.log(new Date("{{ date('D, d M y H:i:s', $attempt->timestart + (intval($quiz->limit_time) * 60)) }}"));
        @if($quiz->times_shooting_webcam && $quiz->webcam_require == 1)
            var time_wecam = (countDownDate - startTime.getTime())/{{ intval($quiz->times_shooting_webcam) }};
            var times_shooting_webcam = (Math.floor(time_wecam/1000)*1000) + (Math.floor(Math.random() * 5000)); //Thời gian bắt đầu chụp random từng ng
            var num_times_shooting_webcam = Math.floor(time_wecam/1000)*1000;

            console.log(times_shooting_webcam, num_times_shooting_webcam);
        @else
            var times_shooting_webcam = null;
        @endif

        @if($quiz->times_shooting_question && $quiz->question_require == 1)
            var time_question = (countDownDate - startTime.getTime())/{{ intval($quiz->times_shooting_question + 1) }};
            var times_shooting_question = Math.floor(time_question/1000)*1000;
            var num_times_shooting_question = Math.floor(time_question/1000)*1000;
        @else
            var times_shooting_question = null;
        @endif
    @endif

    @if($part->end_date)
        var enddate = new Date("{{ date('D, d M y H:i:s', strtotime($part->end_date) + 59) }}").getTime();
    @else
        var enddate = null;
    @endif
</script>
