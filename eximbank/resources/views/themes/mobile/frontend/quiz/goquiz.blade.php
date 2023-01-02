@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.quiz'))

@section('content')
    <div class="container" id="quiz_page">
        <div class="row my-2">
            <div class="col">
                <div class="card quiz">
                    <div class="card-body text-center pl-0 pr-0">
                        <h5 class="card-title">{{ trans('app.exam_info') }}</h5>
                        <h6 class="mt-1">{{ $quiz->name }}</h6>
                        <p class="card-text mb-0">
                            {{ data_locale('Số lần thi cho phép: ', 'The number of times allowed: ') }}
                            <span class="text-danger">
                                @if($quiz->max_attempts > 0)
                                    {{ $quiz->max_attempts .' '. trans('app.times') }}
                                @else
                                    {{ trans('app.unlimited') }}
                                @endif
                            </span>
                        </p>
                        <p class="card-text mb-0">
                            {{ data_locale('Kỳ thi được mở lúc', 'Exam is open at').': ' }} <span class="text-danger">{{ get_date($part->start_date, 'H:i d/m/Y') }}</span>
                        </p>
                        @if($part->end_date)
                            <p class="card-text mb-0">
                                {{ data_locale('Kỳ thi sẽ đóng lúc', 'Exam will close at').': ' }} <span class="text-danger">{{ get_date($part->end_date, 'H:i d/m/Y') }}</span>
                            </p>
                        @endif
                        <p class="card-text">
                            {{ trans('app.time_exam').': ' }} <span class="text-danger">{{ $quiz->limit_time .' '. trans('app.min') }}</span>
                        </p>

                        @if ($block_quiz)
                            <p class="text-danger">CẤM THI</p>
                        @elseif ($user_locked)
                            <p class="text-danger">{{ trans('laquiz.notify_user_locked') }}</p>
                        @else
                            @if($can_create)
                                <a href="javascript:void(0)" class="btn" data-toggle="modal" data-target="#goquiz">
                                    <i class="fa fa-edit"></i>
                                    {{ data_locale('Vào làm bài thi', 'Into the test') }}
                                </a>
                            @else
                                <p><b>{{ data_locale('Bạn đã hết số lần làm bài cho kỳ thi này', 'You have run out of exams for this exam') }}</b></p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mx-0 my-3">
            <a href="javascript:void(0);" onclick="loadSpinner('{{ route('module.quiz_mobile.doquiz.attempt_history', ['quiz_id' => $quiz->id, 'part_id' => $part->id]) }}', 1, 2)" class="d_flex_align w-100">
                <div class="col-10 pl-0 d_flex_align">
                    <div class="icon_quiz">
                        <div class="icon" style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-37.svg') }}) no-repeat;
                            mask: url({{ asset('images/svg-backend/svgexport-37.svg') }}) no-repeat;
                            -webkit-mask-size: 45px 27px;">
                        </div>
                    </div>
                    <h5 class="ml-2">{{ trans('app.history_summary') }}</h5>
                </div>
                <div class="col-2 text-right pr-0">
                    <i class="material-icons">navigate_next</i>
                </div>
            </a>
        </div>

        <div class="row mx-0 my-3">
            <a href="javascript:void(0)" onclick="showModalQuizReview()" class="d_flex_align w-100">
                <div class="col-10 pl-0 d_flex_align">
                    <div class="icon_quiz">
                        <div class="icon" style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-157.svg') }}) no-repeat;
                            mask: url({{ asset('images/svg-backend/svgexport-157.svg') }}) no-repeat;
                            -webkit-mask-size: 45px 27px;">
                        </div>
                    </div>
                    <h5 class="ml-2">{{ trans('lamenu.suggestion') }}</h5>
                </div>
                <div class="col-2 text-right pr-0">
                    <i class="material-icons">navigate_next</i>
                </div>
            </a>
        </div>
    </div>
@endsection

@section('modal')
    <div class="modal fade " id="goquiz" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border">
                <form action="{{ route('module.quiz_mobile.doquiz.create_quiz', ['quiz_id' => $quiz->id, 'part_id' => $part->id]) }}" method="post" class="">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">{{ data_locale('Bắt đầu bài thi', 'Start the exam') }}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" data-dismiss="modal">
                        {{ data_locale('Bài kiểm tra có giới hạn thời gian là', 'The test has a time limit of') }} <b>{{ $quiz->limit_time .' '. trans('app.min') .'.' }}</b><br>
                        {{ data_locale('Thời gian sẽ được tính từ thời điểm bạn bắt đầu bài làm của mình và bạn phải gửi trước khi hết hạn.', 'Time will be counted from the time you start your assignment and you must submit before it expires.') }} <br>
                        {{ data_locale('Thời gian vẫn sẽ tính kể cả khi bạn thoát hoặc đóng trình duyệt.', 'Time will still count even when you exit or close the browser.') }} <br>
                        {{ data_locale('Bạn có chắc chắn muốn bắt đầu ngay bây giờ không?', 'Are you sure you want to get started now?') }}
                        @if ($quiz->webcam_require == 1)
                            <h6 class="mt-2" style="color: red">
                                Lưu ý: Bài thi cần phải bật webcam, nếu không bạn sẽ không thấy câu hỏi bài thi. Hãy chắc chắn thiết bị của bạn có webcam hoặc đã bật nó
                            </h6>
                        @endif
                    </div>
                    <div class="modal-footer text-center">
                        <button type="submit" class="btn w-100 p-2" id="goto_doquiz">
                            <i class="fa fa-edit"></i> {{ data_locale('Làm bài thi', 'Take the test') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="quiz_review" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('module.quiz_mobile.doquiz.user_review_quiz', ['quiz_id' => $quiz->id, 'part_id' => $part->id]) }}" method="post" class="form-ajax w-100">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('lamenu.suggestion') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <textarea name="content_review" id="" rows="5" class="form-control w-100" placeholder="Bạn có góp ý gì sau bài thi này?" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn mt-2 text-white w-100 p-2">
                            {{ trans('labutton.send') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script>
        function showModalQuizReview() {
            $('#quiz_review').modal({backdrop: 'static', keyboard: false});
        }

        $('#goto_doquiz').on('click', function(){
            $('#loader').show();

            $('#goto_doquiz').closest("form").submit(function (e) {
                $('#goto_doquiz').attr("disabled", true);
            });
        });
    </script>
@endsection
