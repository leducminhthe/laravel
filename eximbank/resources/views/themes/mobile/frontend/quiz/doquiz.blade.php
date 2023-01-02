@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.quiz'))

@section('content')
<link rel="stylesheet" href="{{ mix('styles/module/quiz/css/doquiz.css') }}">
<script src="{{ asset('js/webcam.min.js') }}" type="text/javascript"></script>
<style>
    #quiz-content #modal-check-user-question .datepicker {
        box-sizing: border-box;
    }

    #questions .qtext img {
        width: 100% !important;
        height: 50% !important;
    }

    #questions{
        overflow-y: auto;
        max-height: 100%;
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

    #info-number-question .card-header{
        padding: 3px 0;
        text-align: center;
    }
    #info-number-question .btn{
        background-color: #0c5596;
        border-color: #0c5596;
    }

    #quiz-content .quiz-block .select-question{
        padding: 5px;
        width: 13.5%;
    }

    @media(min-width: 480px){
         #quiz-content .quiz-block .select-question{
            padding: 5px;
            width: 13.5%;
        }
    }
    #quiz-content .div-flex{
        position: fixed;
        top: 0;
        z-index: 9999;
        margin-top: 54px;
    }

    #list-questions{
        margin-top: {{ $quiz->webcam_require == 1 && !$attempt_finish ? '295px' : '155px' }}
    }
    .card{
        border-radius: unset;
        border: unset;
    }

    .wrapper{
        padding-bottom: 0;
    }
</style>
    <div class="row mx-0" id="quiz-content">
        @php
            $url_submit = route('module.quiz_mobile.doquiz.submit', ['quiz_id' => $quiz->id, 'part_id' => $part_id, 'attempt_id' => $attempt->id, 'quiz_by_online' => $quiz_by_online]);

            //Check xem có phải xem kỳ thi trong khoá Online không
            if($quiz->quiz_type == 1){
                $return_exam_screen = route('themes.mobile.frontend.online.detail', [$quiz->course_id]);
            }else{
                $return_exam_screen = route('module.quiz_mobile.doquiz.index', [ 'quiz_id' => $quiz->id, 'part_id' => $part_id]);
            }
        @endphp
        <div class="col-12 p-0 div-flex">
            <div class="col-12 p-0">
                @include('quiz::quiz.component.sidebar')
            </div>

            <input type="hidden" name="" id="webcam_require" value="{{ $quiz->webcam_require }}">
            @if($quiz->webcam_require == 1 && !$attempt_finish)
                <div class="col-12 p-0">
                    <div class="card block-item">
                        <div class="card-header p-0 text-center">
                            <span>Webcam</span>
                        </div>
                        <div class="card-body text-center p-0">
                            <video id="video" width="250" height="110" autoplay playsinline></video>
                            <canvas id="canvas" width="640" height="480" class="d-none"></canvas>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ $url_submit }}" method="post" class="form-ajax text-center" data-success="submit_success" id="submit_sidebar">
                <div class="card">
                    <div class="card-header p-1">
                        <div class="text-center mb-1 button-page">
                            <button type="button" class="btn button-back float-left">
                                <i class="fa fa-arrow-left"></i> {{ trans('backend.back') }}
                            </button>
                            @if(!$attempt_finish)
                                <button type="button" class="btn send-quiz" data-action="save" style="background: #5ed0fb">
                                    <i class="fa fa-paper-plane"></i> {{ trans('laquiz.submit_exam') }}
                                </button>
                            @else
                                <a href="{{ $return_exam_screen }}" class="btn">
                                    <i class="fa fa-mail-reply"></i>
                                    {{ trans('laquiz.return_exam_screen') }}
                                </a>
                            @endif
                            <button type="button" class="btn button-next float-right">
                                {{ trans('backend.next') }} <i class="fa fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-12 quiz-{{ $quiz->id }} p-0" id="list-questions">
            <form method="post" action="" id="form-question">
                <div class="card">
                    <div class="card-body p-1">
                        <div id="questions"></div>
                    </div>
                </div>
            </form>

            <form action="{{ route('module.quiz_mobile.doquiz.submit', ['quiz_id' => $quiz->id, 'part_id' => $part_id, 'attempt_id' => $attempt->id, 'quiz_by_online' => $quiz_by_online]) }}" method="post" class="form-ajax text-center" id="form-submit" data-success="submit_success">
                <div class="card">
                    @if(!$attempt_finish)
                    <div class="card-header">
                        <b>Bạn đã trả lời</b> <span class="text-danger"><span class="num-question-selected">0</span>{{ '/'. count($questions) }}</span> câu hỏi trong kỳ thi <b>{{ $quiz->name }}</b>
                    </div>
                    @endif
                    <div class="card-body">
                        @if(!$attempt_finish)
                            <p>Để nộp bài vui lòng nhấn nút <b>Nộp bài thi</b></p>
                            <p>Để xem lại bài thi vui lòng nhấn nút <b>Xem lại bài</b></p>
                        @else
                            <p>Bài thi của bạn đã được nộp, nhấn nút <b>Xem lại bài</b> để xem lại bài làm của mình</p>
                        @endif
                        <p></p>
                        <button type="button" class="btn button-back"><i class="fa fa-mail-reply"></i> Xem lại bài</button>
                        @if($attempt_finish)
                            @php
                                //Check xem có phải xem kỳ thi trong khoá Online không
                                if($quiz->quiz_type == 1){
                                    $return_exam_screen = route('themes.mobile.frontend.online.detail', [$quiz->course_id]);
                                }else{
                                    $return_exam_screen = route('module.quiz_mobile.doquiz.index', [ 'quiz_id' => $quiz->id, 'part_id' => $part_id]);
                                }
                            @endphp
                            <a href="{{ $return_exam_screen }}" class="btn">
                                <i class="fa fa-mail-reply"></i> Trở về màn hình kỳ thi
                            </a>
                        @else
                            <button type="button" class="btn send-quiz"><i class="fa fa-send-o"></i> Nộp bài thi</button>
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
                        <h6 class="no">
                            {{--  <a href="javascript:void(0)" class="mr-1 flag" data-id="{qid}" data-flag="{flag}">
                                <i class="fa fa-flag {class_flag}" aria-hidden="true"></i>
                            </a>  --}}
                            {{ trans('latraining.question') }}
                            <span class="qno">{index}</span>
                        </h6>
                        <div class="questionflag editable"></div>
                    </div>
                </div>

                <div class="col-md-10">
                    <div class="content">
                        <div class="formulation clearfix">
                            <div class="qtext">
                                <b><span lang="DE">{name}</span></b>
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
                {{--  <span class="answernumber">{index_text}. </span>  --}}
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
                        Câu trả lời đúng
                    </div>
                    <textarea type="text" class="form-control" @if($attempt_finish) disabled @endif>{correct_answer}</textarea>
                </div>
            @endif
        @endif
    </template>

    <template id="answer-template-chosen">
        <div class="r{index}">
            <label for="q{qindex}:choice{index}" class="ml-1 d-flex">
                <div class="row w-100">
                    <div class="col-1 pl-2 pr-0">
                        <input type="{input_type}" name="q_{qid}[]" value="{id}" id="q{qindex}:choice{index}" class="selected-answer mr-2" data-answer="{id}" {checked} @if($attempt_finish) disabled @endif>
                    </div>
                    <div class="col-11 px-1">
                        {title}
                        {image_answer}
                    </div>
                </div>
                @if($attempt_finish && $quiz_setting)
                    @if(($quiz_setting->after_test_yes_no == 1 && $attempt_finish == 1)|| ($quiz_setting->exam_closed_yes_no == 1 && (date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                        {correct}
                    @endif
                @endif
                {{--  <span class="answernumber">{index_text}. </span>  --}}
            </label>
            @if($attempt_finish && $quiz_setting)
                @if(($quiz_setting->after_test_specific_feedback == 1  && $attempt_finish == 1) || ($quiz_setting->exam_closed_specific_feedback == 1 &&(date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                    {feedback}
                @endif
            @endif
        </div>
    </template>

    <template id="correct-answer-template-chosen">
        @if($quiz_setting)
            @if(($quiz_setting->after_test_correct_answer == 1 && $attempt_finish == 1) || ($quiz_setting->exam_closed_correct_answer == 1 && (date('H:i') > get_date($max_end_date, 'H:i') && (date('Y-m-d') > get_date($max_end_date, 'Y-m-d')))) )
                <p></p>
                <div class="card">
                    <div class="card-header bg-info text-white">
                        Câu trả lời đúng
                    </div>
                    <div class="card-body">
                        {correct_answer}
                    </div>
                </div>
            @endif
        @endif
    </template>

    <template id="answer-template-essay">
        <input id="qf_{qid}" type="file" data-answer="{id}" class="selected-answer file-essay" accept=".xlsx, .pdf, .docx">
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
                {{--  <span class="answernumber">{index_text}. </span>  --}}
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
                {{--  <span class="answernumber">{index_text} </span>  --}}
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

    <div id="element_app"
        data-quiz_url = "{{ route('module.quiz_mobile.doquiz.do_quiz', [
            'quiz_id' => $quiz->id,
            'part_id' => $part_id,
            'attempt_id' => $attempt->id,
        ]) }}"
        @if ($quiz->quiz_type == 1)
            data-list_attempt = "{{ route('themes.mobile.frontend.online.detail', [$quiz->course_id]) }}"
        @else
            data-list_attempt = "{{ route('module.quiz_mobile.doquiz.index', [
                'quiz_id' => $quiz->id,
                'part_id' => $part_id,
            ]) }}"
        @endif
    >
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
@endsection

@section('modal')
    <div id="modal-check-user-question" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form id="check-user-question">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Mời bạn trả lời các câu hỏi sau:</h4>
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
                            $titles = \App\Models\Categories\Titles::select(['id','name','code'])->where('status', '=', 1)->get();
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
@endsection

@section('footer')
    <script type="text/javascript">
        document.oncontextmenu = new Function("return false");
        $('body').bind('cut copy paste', function(event) {
            event.preventDefault();
        });
        var base_url = '{{ url('/') }}';
        var session_time = {{ config("session.lifetime") }};
        var quiz_id = '{{ $quiz->id }}';
        var attempt_id = {{ $attempt->id }};
        var qqcategory = jQuery.parseJSON('{!! json_encode($qqcategory) !!}');
        var questions_perpage = '{{ $quiz->questions_perpage }}';
        var lang = '{{ App::getLocale() }}';
        var disable = {{ $attempt_finish }};
        var context = '';
        var total_question = {{ count($questions) }};
        var full_screen = {{ $quiz->full_screen }};
        var is_mobile = '{{ url_mobile() }}';
        var list_attempt = $('#element_app').attr('data-list_attempt');
        var quiz_url = $('#element_app').attr('data-quiz_url');

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
        var num_hidden = {{ $quiz->new_tab }};
        if(num_hidden > 0 && !disable){
            document.addEventListener("visibilitychange", onchange);
            function onchange () {
                if (document.hidden) {
                    num_hidden -= 1;

                    Swal.fire({
                        title: 'Thông báo',
                        text: "Bạn đã vi phạm quy chế thi. Bài thi sẽ bị khoá lại nếu còn vi phạm!",
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK!',
                    });

                    if(num_hidden == 0){
                        Swal.fire({
                            title: 'Thông báo',
                            text: "Bạn đã vi phạm quy chế thi. Bài thi sẽ bị khoá lại!",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!',
                        });

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
                    }
                }
            }
        }

        var swiper = new Swiper('.quiz-slide', {
            slidesPerView: 'auto',
            spaceBetween: 0,
        });

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
    </script>

    <script type="text/javascript" src="{{ mix('styles/module/quiz/js/doquiz.js') }}"></script>
@endsection
