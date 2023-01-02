<div class="container">
    <div class="row">
        <div class="col">
            <div class="card quiz">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ trans('app.exam_info') }}</h5>
                    <p class="card-text">
                        {{ data_locale('Số lần thi cho phép: ', 'The number of times allowed: ') }}
                        <span class="text-danger">
                            @if($quiz->max_attempts > 0)
                                {{ $quiz->max_attempts .' '. trans('app.times') }}
                            @else
                                {{ trans('app.unlimited') }}
                            @endif
                        </span>
                        <br>
                        {{ data_locale('Kỳ thi được mở lúc', 'Exam is open at') .': ' }} <span class="text-danger">{{ get_date($part->start_date, 'H:i d/m/Y') }}</span>
                        <br>
                        @if($part->end_date)
                            {{ data_locale('Kỳ thi sẽ đóng lúc', 'Exam will close at').': ' }} <span class="text-danger">{{ get_date($part->end_date, 'H:i d/m/Y') }}</span>
                            <br>
                        @endif
                        {{ trans('app.time_exam') .': ' }} <span class="text-danger">{{ $quiz->limit_time .' '. trans('app.min') }}</span>
                    </p>
                    @if ($block_quiz)
                        <p class="text-danger">CẤM THI</p>
                    @elseif ($user_locked)
                        <p class="text-danger">{{ trans('laquiz.notify_user_locked') }}</p>
                    @else
                        @if($can_create)
                            <form action="{{ route('module.quiz_mobile.doquiz.create_quiz', ['quiz_id' => $quiz->id, 'part_id' => $part->id, 'quiz_by_online' => 1]) }}" method="post">
                                {{ csrf_field() }}
                                <button type="submit" class="btn">
                                    <p class="h6"><i class="fa fa-play-circle"></i> {{ strtoupper(trans('laquiz.entry_exam')) }} </p>
                                </button>
                            </form>
                        @else
                            <p><b>{{ data_locale('Bạn đã hết số lần làm bài cho kỳ thi này', 'You have run out of exams for this exam') }}</b></p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
