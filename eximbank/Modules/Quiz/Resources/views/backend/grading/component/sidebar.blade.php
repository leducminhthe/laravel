<div class="quiz-block">
    <div class="card block-item">
        <div class="card-header">
            <span>{{ trans('latraining.question') }}</span>
        </div>
        <div class="card-body">
            @foreach($questions as $index => $question)
                <a href="javascript:void(0)" class="btn select-question
                @if(@$question['selected']) question-selected
                @endif" id="select-q{{ $question['id'] }}"
                   data-quiz-page="{{ ceil(($question['qindex']) / $quiz->questions_perpage) }}"
                   data-id="{{ $question['id'] }}"
                >
                    <span class="thispageholder"></span>
                    <span class="trafficlight"></span>
                    <span class="accesshide">{{ ($question['qindex']) }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <div class="card block-item">
        <div class="card-body text-center">
            <form action="{{ route('module.quiz.grading.user.complete', [
                'quiz_id' => $quiz->id,
                'part_id' => $part_id,
                'type' => $type,
                'user_id' => $user_id,
                'attempt_id' => $attempt->id
                ]) }}" method="post" class="form-ajax">
                <button type="submit" class="btn"><i class="fa fa-check-circle"></i> Hoàn thành chấm điểm</button>
            </form>
        </div>
    </div>

</div>
