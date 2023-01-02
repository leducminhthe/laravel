@extends('quiz::layout.app')

@section('page_title', $quiz->name)
@section('content')
    <link rel="stylesheet" href="{{ mix('styles/module/quiz/css/doquiz.css') }}">

    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        #questions video {
            width: 50%;
            height: auto;
        }

        #questions img {
            max-width: 100% !important;
            height: auto !important;
        }

        #quiz-content #modal-check-user-question .datepicker {
            box-sizing: border-box;
        }

        .flag-item {
            position: relative;
            top: -23px;
            left: -11px;
        }
        .flag-item:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            border-style: solid;
            border-width: 10px;
            border-color: yellow transparent transparent yellow;
        }
        .fa-flag-red{
            color: red;
        }
        .noselect {
            -webkit-touch-callout: none; /* iOS Safari */
            -webkit-user-select: none; /* Safari */
            -khtml-user-select: none; /* Konqueror HTML */
            -moz-user-select: none; /* Old versions of Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none; /* Non-prefixed version, currently supported by Chrome, Edge, Opera and Firefox */
        }
        #quiz-content .btn {
            border: 1px solid #e0e3eb;
        }
        #quiz-content .question-item .answer p{
            margin-bottom: 0;
        }
        #quiz-content .menu-left{
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9;
            {{--  overflow-y: scroll;  --}}
            overflow-x: hidden;
        }
        #quiz-content .menu-left::-webkit-scrollbar {
            width: 14px;
            border-radius: 10px;
        }
        #quiz-content .menu-left::-webkit-scrollbar-corner {
            background-color: transparent;
        }

        #quiz-content .menu-left::-webkit-scrollbar-thumb {
            height: 6px;
            border: 4px solid transparent;
            background-clip: padding-box;
            -webkit-border-radius: 7px;
            background-color: rgba(0, 0, 0, 0.15);
            -webkit-box-shadow: inset -1px -1px 0 rgb(0 0 0 / 5%), inset 1px 1px 0 rgb(0 0 0 / 5%);
        }

        #quiz-content .menu-right{
            position: absolute;
            right: 0;
            margin-top: {{ isMobile() ? ($quiz->webcam_require == 1 && !$attempt_finish ? '412px' : '255px') : '0px' }}
        }

        #quiz-content #loading {
            display: none;
        }
        .qtext span,
        .qtext span p,
        .answer label p {
            color: #242424;
        }
    </style>
    <div class="row" id="quiz-content">
        <div id="loading"></div>

        <div class="col-md-3 menu-left">
            @include('quiz::quiz.component.sidebar')

            <input type="hidden" name="" id="webcam_require" value="{{ $quiz->webcam_require }}">
            @if($quiz->webcam_require == 1 && !$attempt_finish)
                <div class="col-12 p-0">
                    <div class="card block-item">
                        <div class="card-header p-0 text-center">
                            <span>Webcam</span>
                        </div>
                        <div class="card-body text-center p-0">
                            <video id="video" width="100%" height="110" autoplay playsinline></video>
                            <canvas id="canvas" width="640" height="480" class="d-none"></canvas>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-9 quiz-{{ $quiz->id }} menu-right">
            <form method="post" action="" id="form-question">
                <div class="card">
                    <div class="card-header mt-1">
                        <div class="text-center mb-1 button-page">
                            <button type="button" class="btn button-back"><i class="fa fa-mail-reply"></i> {{ trans('labutton.back') }}</button> |
                            <button type="button" class="btn button-next">{{ trans('labutton.next') }} <i class="fa fa-mail-forward"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="questions"></div>
                    </div>
                    <div class="card-footer">
                        <div class="text-center mt-1 button-page">
                            <button type="button" class="btn button-back"><i class="fa fa-mail-reply"></i> {{ trans('labutton.back') }}</button> |
                            <button type="button" class="btn button-next">{{ trans('labutton.next') }} <i class="fa fa-mail-forward"></i></button>
                        </div>
                    </div>
                </div>
            </form>

            <form action="{{ route('module.quiz.doquiz.submit', ['quiz_id' => $quiz->id, 'part_id' => $part_id, 'attempt_id' => $attempt->id, 'quiz_by_online' => $quiz_by_online]) }}" method="post" class="form-ajax text-center" id="form-submit" data-success="submit_success">
                <div class="card">
                    @if(!$attempt_finish)
                    <div class="card-header">
                        <b>Bạn đã trả lời</b> <span class="text-danger"><span class="num-question-selected">0</span>{{ '/'. count($questions) }}</span> câu hỏi trong kỳ thi <b>{{ $quiz->name }}</b>
                    </div>
                    @endif
                    <div class="card-body">
                        @if(!$attempt_finish)
                            <p>{{ trans('laquiz.note_submit') }} <b>{{ trans('laquiz.submit_exam') }}</b></p>
                            <p>{{ trans('laquiz.note_review_exam') }} <b>{{ trans('laquiz.review_exam') }}</b></p>

                            <p id="camera-text"></p>
                        @else
                            <p>{{ trans('laquiz.note_submitted_exam') }} <b>{{ trans('laquiz.review_exam') }}</b> {{ trans('laquiz.review_my_exam') }}</p>
                        @endif
                        <p></p>
                        <button type="button" class="btn button-back"><i class="fa fa-mail-reply"></i> {{ trans('laquiz.review_exam') }}</button>
                        @if($attempt_finish)
                            @php
                                //Check xem có phải xem kỳ thi trong khoá Online không
                                if($quiz->quiz_type == 1){
                                    $return_exam_screen = route('module.online.detail_online', [$quiz->course_id]);
                                }else{
                                    $return_exam_screen = route('module.quiz.doquiz.index', [ 'quiz_id' => $quiz->id, 'part_id' => $part_id]);
                                }
                            @endphp
                            <a href="{{ $return_exam_screen }}" class="btn"><i class="fa fa-mail-reply"></i> {{ trans('laquiz.return_exam_screen') }}</a>
                        @else
                            <button type="button" class="btn send-quiz"><i class="fa fa-send-o"></i> {{ trans('laquiz.submit_exam') }}</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <template id="question-template">
        <div class="question-item" id="q{qid}" data-qid="{qid}">
            <input type="hidden" name="q[]" value="{qid}">
            <div class="row">
                <div class="col-md-2 p-0">
                    <div class="info">
                        <h3 class="no">
                            {{--  <a href="javascript:void(0)" class="flag mr-1" data-id="{qid}" data-flag="{flag}">
                                <img src="{{ asset('images/flag.png') }}" alt="" class="{class_flag}">
                            </a>  --}}
                            <strong>{{ trans('latraining.question') }} <span class="qno">{index}:</span></strong>
                            @if($quiz_setting && ($quiz_setting->after_test_yes_no == 1 && $attempt_finish == 1) || ($quiz_setting->exam_closed_yes_no == 1 && (date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                                {check_selected}
                            @endif
                        </h3>
                    </div>
                </div>

                <div class="col-md-10 p-0">
                    <div class="content noselect">
                        <div class="formulation clearfix">
                            <div class="qtext">
                                <b><span lang="DE">{name}</span></b>

                                <div id="image-area w-100" style="position: relative;">
                                    {image_drag_drop}
                                    {drop_image}
                                </div>
                            </div>
                            <div class="ablock">
                                <div class="prompt">{prompt}</div>
                                <div class="answer">
                                    {answers}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="answer-template-matching">
        <div class="r{index}">
            <input type="hidden" name="q_{qid}[]" value="{id}">
            <label class="m-l-1">
                {{-- <span class="answernumber">{index_text} </span> --}}
                <span lang="VN">{title}</span>
            </label>
            @if($quiz_setting && $attempt_finish)
                <select name="matching_{qid}[{id}]" class="selected-answer" data-answer="{id}" @if($attempt_finish) disabled @endif>
                    <option value="{matching}">{matching}</option>
                </select>
                @if(($quiz_setting->after_test_yes_no == 1 && $attempt_finish == 1) || ($quiz_setting->exam_closed_yes_no == 1 && (date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                    {correct}
                @endif
            @else
                <select name="matching_{qid}[{id}]" class="selected-answer" data-answer="{id}">
                    {option}
                </select>
            @endif
        </div>
    </template>

    <template id="matching-feedback-template">
        @if($attempt_finish && $quiz_setting)
            @if(($quiz_setting->after_test_general_feedback == 1 && $attempt_finish == 1) || ($quiz_setting->exam_closed_general_feedback == 1 && (date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                <p></p>
                {feedback}
            @endif
            @if(($quiz_setting->after_test_correct_answer == 1 && $attempt_finish == 1) || ($quiz_setting->exam_closed_correct_answer == 1 && (date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                <p></p>
                <div class="card">
                    <div class="card-header bg-info text-white">
                        {{ trans('latraining.right_answer') }}
                    </div>
                    <textarea type="text" class="form-control" @if($attempt_finish) disabled @endif>{correct_answer}</textarea>
                </div>
            @endif
        @endif
    </template>

    {{-- TRẮC NGHIỆM --}}
    <template id="answer-template-chosen">
        <div class="r{index}">
            <label for="q{qindex}:choice{index}" class="ml-1 d-flex">
                <input type="{input_type}" name="q_{qid}[]" value="{id}" id="q{qindex}:choice{index}" class="selected-answer mr-2" data-answer="{id}" {checked} @if($attempt_finish) disabled @endif>
                {{-- <span class="answernumber pl-1">{index_text} </span> --}}
                {title}
                {image_answer}
                @if($attempt_finish && $quiz_setting)
                    @if(($quiz_setting->after_test_yes_no == 1 && $attempt_finish == 1)|| ($quiz_setting->exam_closed_yes_no == 1 && (date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                        {correct}
                    @endif
                @endif
            </label>
            @if($attempt_finish && $quiz_setting)
                @if(($quiz_setting->after_test_specific_feedback == 1  && $attempt_finish == 1) || ($quiz_setting->exam_closed_specific_feedback == 1 &&(date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                    {feedback}
                @endif
            @endif
        </div>
    </template>

    {{-- ĐÁP ÁN ĐÚNG TRẮC NGHIỆM  --}}
    <template id="correct-answer-template-chosen">
        @if($quiz_setting)
            @if(($quiz_setting->after_test_correct_answer == 1 && $attempt_finish == 1) || ($quiz_setting->exam_closed_correct_answer == 1 && (date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                <p></p>
                <div class="card">
                    <div class="card-header bg-info text-white">
                        {{ trans('latraining.right_answer') }}
                    </div>
                    <div class="card-body">
                        {correct_answer}
                    </div>
                </div>
            @endif
        @endif
    </template>

    <template id="answer-template-essay">
        <input id="qf_{qid}" type="file" data-answer="{id}" class="selected-answer file-essay" accept=".xlsx, .pdf, .docx" @if($attempt_finish) disabled @endif>
        <div>
            <a href="{link_file_essay}" class="">{file_essay}</a>
        </div>
        <textarea class="form-control selected-answer" name="q_{qid}[]" rows="5" data-answer="{id}" @if($attempt_finish) disabled @endif>{text_essay}</textarea>
        @if($attempt_finish && $quiz_setting)
            @if(($quiz_setting->after_test_general_feedback == 1 && $attempt_finish == 1) || ($quiz_setting->exam_closed_general_feedback == 1 && (date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                <p></p>
                {feedback}
            @endif
        @endif
    </template>

    <template id="qqcategory-template">
        <h3 class="question-title">
            <div class="row">
                <div class="col-md-10">
                    {name}
                </div>
                <div class="col-md-2 text-right">
                    {percent} %
                </div>
            </div>
        </h3>
    </template>

    <template id="fill-in-template">
        <div class="r{index}">
            <label for="q{qindex}:choice{index}" class="m-l-1">
                {{-- <span class="answernumber">{index_text} </span> --}}
                <span lang="VN">{title}</span>
            </label>
            <textarea class="form-control selected-answer" name="q_{qid}[]" rows="5" id="q{qindex}:choice{index}" data-answer="{id}" @if($attempt_finish) disabled @endif>{text_essay}</textarea>
            @if($attempt_finish && $quiz_setting)
                @if(($quiz_setting->after_test_specific_feedback == 1  && $attempt_finish == 1) || ($quiz_setting->exam_closed_specific_feedback == 1 &&(date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                    {feedback}
                @endif
            @endif
        </div>
    </template>

    <template id="fill-in-correct-template">
        <div class="r{index}">
            <label for="q{qindex}:choice{index}" class="m-l-1">
                {{-- <span class="answernumber">{index_text} </span> --}}
                <span lang="VN">{title}</span>
            </label>
            <textarea class="form-control selected-answer" name="q_{qid}[]" rows="5" id="q{qindex}:choice{index}" data-answer="{id}" @if($attempt_finish) disabled @endif>{text_essay}</textarea>
            @if($attempt_finish && $quiz_setting)
                @if(($quiz_setting->after_test_specific_feedback == 1  && $attempt_finish == 1) || ($quiz_setting->exam_closed_specific_feedback == 1 &&(date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                    {feedback}
                @endif
            @endif
        </div>
    </template>

    <template id="select_word_correct">
        <div class="r{index}">
            <div class="qtext">
                {name}
            </div>
        </div>
    </template>

    <template id="drag_drop_marker">
        {name}
    </template>

    <template id="drag_drop_image">
        {name}
    </template>

    <template id="drag_drop_document">
        {name}
    </template>

    <div id="modal-check-user-question" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <form id="check-user-question">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ trans('laquiz.please_answer_question') }}:</h4>
                        {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
                    </div>
                    <div class="modal-body">
                        @php
                            $arr = [
                                'code' => 'Mã số NV của bạn là gì',
                                'identity_card' => 'CMND của bạn',
                                'month' => 'Bạn sinh vào tháng mấy',
                                'day' => 'Bạn sinh vào ngày mấy',
                                'year' => 'Bạn sinh vào năm mấy',
                                'join_company' => 'Ngày bạn vào làm là ngày nào',
                                'phone' => 'Số điện thoại của bạn',
                                'unit_code' => 'Lựa chọn Đơn vị trực tiếp bạn đang làm việc',
                                'title_code' => 'Lựa chọn Chức danh của bạn',
                            ];
                            $key = array_rand($arr, 1);
                            $titles = \App\Models\Categories\Titles::where('status', '=', 1)->get();
                            $unit = \App\Models\Categories\Unit::select(['id','name','code'])->where('status', '=', 1)->get();
                        @endphp
                        <input type="hidden" name="key" value="{{ $key }}" class="item">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="title">{{ $arr[$key] }}</div>
                                <div class="content">
                                    <input name="answer" id="question_orther" type="text" class="form-control">
                                    <select name="answer" class="form-control select2" id="unit">
                                        <option value=""></option>
                                        @foreach($unit as $item)
                                            <option value="{{ $item->code }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="answer" class="form-control select2" id="title">
                                        <option value=""></option>
                                        @foreach($titles as $item)
                                            <option value="{{ $item->code }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" id="check-user">{{ trans('labutton.send') }}</button>
                        {{--<button type="button" class="btn btn-default" id="refresh-question">Đổi câu</button>--}}
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="error_quiz" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border">
                <div class="modal-body">
                    <h6 class="mt-2 error_quiz" id="error_note_quiz" style="color: red">
                    </h6>
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn w-100" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>

    <div id="element_quiz"
        data-not_allow_error = "{!!__('app.qrcode_NotAllowedError')!!}"
        data-not_found_error = "{!!__('app.qrcode_NotFoundError')!!}"
        data-not_suppor_error = "{!!__('app.qrcode_NotSupportedError')!!}"
        data-not_readable_error = "{!!__('app.qrcode_NotReadableError') !!}"
        data-over_constrained_error = "{!!__('app.qrcode_OverconstrainedError')!!}"
        data-qrcode_error_unknow = "{!!__('app.qrcode_error_unknow')!!}"
    >
    </div>

    <script type="text/javascript">
        document.oncontextmenu = new Function("return false");
        $('body').bind('cut copy paste', function(event) {
            event.preventDefault();
        });
        var base_url = '{{ url('/') }}';
        var session_time = {{ config('session.lifetime') }};
        var quiz_id = '{{ $quiz->id }}';
        var attempt_id = {{$attempt->id}};
        var quiz_url = '{{ route('module.quiz.doquiz.do_quiz', [
            'quiz_id' => $quiz->id,
            'part_id' => $part_id,
            'attempt_id' => $attempt->id,
        ]) }}';
        var qqcategory = jQuery.parseJSON('{!! json_encode($qqcategory) !!}');
        var questions_perpage = '{{ $quiz->questions_perpage }}';
        var lang = '{{ App::getLocale() }}';
        var disable = {{$attempt_finish}};
        var context = '';
        var total_question = {{ count($questions) }};
        var full_screen = {{ $quiz->full_screen }};
        var is_mobile = '{{ url_mobile() }}';
        var list_attempt = '{{ route('module.quiz.doquiz.index', [
            'quiz_id' => $quiz->id,
            'part_id' => $part_id,
        ]) }}';

        $('.select2').select2({
            allowClear: true,
            dropdownAutoWidth : true,
            width: '100%',
            placeholder: function(params) {
                return {
                    id: null,
                    text: params.placeholder,
                }
            },
        });

        // DỪNG KHI QUA TRANG KHÁC
        var num_hidden = {{ $quiz->new_tab + 1 }};
        if({{ $quiz->new_tab }} > 0 && !disable){
            document.addEventListener("visibilitychange", onchange);
            function onchange () {
                if (document.hidden) {
                    num_hidden -= 1;

                    Swal.fire({
                        title: 'Thông báo',
                        text: "Bạn đã vi phạm quy chế Mở tab khác khi thi. Bài thi sẽ bị khoá lại nếu còn vi phạm!",
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK!',
                    });

                    if(num_hidden == 0){
                        Swal.fire({
                            title: 'Thông báo',
                            text: "Bạn đã vi phạm quy chế Mở tab khác khi thi. Bài thi sẽ bị khoá lại!",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!',
                        }).then((result) => {

                            let formData = $("#form-question").serialize();
                            $.ajax({
                                type: 'POST',
                                url: quiz_url + '/save-locked',
                                dataType: 'json',
                                data: formData
                            }).done(function (data) {
                                if (data.status === "error") {
                                    show_message('Không thể lưu đáp án của bạn', 'error');
                                }

                                window.location = data.redirect;

                            }).fail(function (data) {
                                show_message('Không thể lưu đáp án của bạn', 'error');
                                disabled_button(0);
                            });
                        });
                    }
                }
            }
        }

        if({{ isMobile() }}){
            var swiper = new Swiper('.quiz-slide', {
                slidesPerView: 'auto',
                spaceBetween: 0,
            });
        }
        var text_quiz = '{{ $text_quiz }}';
        if (text_quiz != 1) {
            var time_user_lock = setInterval(function() {
                console.log({{ $user_lock ? $user_lock : session('user_lock_'.$quiz_register->id) }});
                if({{ $user_lock ? $user_lock : session('user_lock_'.$quiz_register->id) }} == 1){
                    clearInterval(time_user_lock);

                    $('.send-quiz').prop('disabled', true);
                    $('.button-next').prop('disabled', true);
                    $('.button-back').prop('disabled', true);

                    show_message('Bạn đã vi phạm. Không thể làm bài', 'error');

                    window.location = list_attempt;
                    return false;
                }
            }, 1000);
        }
    </script>

    <script src="{{ asset('js/webcam.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ mix('styles/module/quiz/js/doquiz.js') }}"></script>
@stop
