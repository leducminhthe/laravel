@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.onl_course'))

@section('content')
    <div class="container mt-2" id="detail-online">
        <h6 class="text-center">{{ $get_activity_quiz_scorm->name }}</h6>
        @if ($activity_history_scorm)
            @foreach ($activity_history_scorm as $history_scorm)
                <div class="card p-1 mb-1 shadow border">
                    {{ trans('app.start_date') }}: {{ $history_scorm->start_date }} <br>
                    {{ trans('app.end_date') }}: {{ $history_scorm->end_date }} <br>
                    {{ trans('app.score') }}: {{ $history_scorm->grade }} <br>
                </div>
            @endforeach
            <div class="row mt-2">
                <div class="col-6">
                    @if($activity_history_scorm->previousPageUrl())
                    <a href="{{ $activity_history_scorm->previousPageUrl() }}" class="bp_left">
                        <i class="material-icons">navigate_before</i> @lang('app.previous')
                    </a>
                    @endif
                </div>
                <div class="col-6 text-right">
                    @if($activity_history_scorm->nextPageUrl())
                    <a href="{{ $activity_history_scorm->nextPageUrl() }}" class="bp_right">
                        @lang('app.next') <i class="material-icons">navigate_next</i>
                    </a>
                    @endif
                </div>
            </div>
        @elseif ($activity_history_quiz)
            @foreach ($activity_history_quiz as $history_quiz)
                <div class="card p-1 mb-1 shadow border">
                    {{ trans('app.start_date') }}: {{ $history_quiz->start_date }} <br>
                    {{ trans('app.end_date') }}: {{ $history_quiz->end_date }} <br>
                    {{ trans('app.score') }}: {{ $history_quiz->grade }} <br>
                    {{ trans('app.status') }}: {{ $history_quiz->status }} <br>
                </div>
            @endforeach
            <div class="row mt-2">
                <div class="col-6">
                    @if($activity_history_quiz->previousPageUrl())
                    <a href="{{ $activity_history_quiz->previousPageUrl() }}" class="bp_left">
                        <i class="material-icons">navigate_before</i> @lang('app.previous')
                    </a>
                    @endif
                </div>
                <div class="col-6 text-right">
                    @if($activity_history_quiz->nextPageUrl())
                    <a href="{{ $activity_history_quiz->nextPageUrl() }}" class="bp_right">
                        @lang('app.next') <i class="material-icons">navigate_next</i>
                    </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
