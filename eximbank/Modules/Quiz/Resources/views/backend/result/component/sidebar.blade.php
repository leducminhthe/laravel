<div class="quiz-block">
    <div class="card block-item" id="info-number-question">
        <div class="card-header">
            <span>{{ trans('latraining.question') }}</span>: <span class="font-weight-bold"> <span id="num-question-selected">0</span>{{ '/'. count($questions) }}</span>
            @if (isset($attempt))
                <span class="float-right">{{ trans('latraining.score') }}:
                    <span class="font-weight-bold">{{ ($attempt->sumgrades ? $attempt->sumgrades : '') }}</span>
                </span>
            @endif

        </div>
        <div class="card-body">
            @foreach($questions as $index => $question)
                <a href="javascript:void(0)" class="btn select-question @if(@$question['selected']) question-selected @endif" id="select-q{{ $question['id'] }}" data-quiz-page="{{ ceil(($question['qindex']) / $quiz->questions_perpage) }}" data-id="{{ $question['id'] }}">
                    <span class="thispageholder"></span>
                    <span class="trafficlight"></span>
                    <span class="accesshide">{{ $question['qindex'] }}</span>
                    @if(@$question['selected'])
                        <div class="flag-item-{{ $question['score'] == ($question['score_group'] * $question['max_score']) ? 'success' : 'error' }}"></div>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>
