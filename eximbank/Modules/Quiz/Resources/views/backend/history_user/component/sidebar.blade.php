<div class="quiz-block">
    <div class="card block-item" id="info-number-question">
        <div class="card-header">
            <span>{{ trans('latraining.question') }}</span>: <span class="font-weight-bold"> <span id="num-question-selected">0</span>{{ '/'. count($questions) }}</span>
        </div>
        <div class="card-body">
            @foreach($questions as $index => $question)
                <a href="javascript:void(0)" class="btn select-question @if(@$question['selected']) question-selected @endif" id="select-q{{ $question['id'] }}" data-quiz-page="{{ ceil(($question['qindex']) / $quiz->questions_perpage) }}" data-id="{{ $question['id'] }}">
                    <span class="thispageholder"></span>
                    <span class="trafficlight"></span>
                    <span class="accesshide">{{ $question['qindex'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
