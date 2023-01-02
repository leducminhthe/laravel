@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.quiz'))

@section('content')
    <div class="container wrapped_quiz">
        @if(count($quizs) > 0)
            <div class="row">
                @foreach($quizs as $item)
                    <div class="col-12 col-sm-6 p-1">
                        <div class="card shadow border-0 mb-2">
                            <div class="card-body">
                                <a href="javascript:void(0);" onclick="loadSpinner('{{ $item->url_go_quiz }}', 1, 2)" class="text-black">
                                <div class="row">
                                    <div class="col-12 title">
                                        <h6 class="mt-1">Bài thi: <strong style="color: #1b4486;">{{ $item->quiz_name }}</strong></h6>
                                    </div>
                                    <div class="col-5 pr-0">
                                        <img src="{{ image_quiz($item->img) }}" alt="" class="mw-100">
                                    </div>
                                    <div class="col-7 align-self-center">
                                        <p class="text-mute">
                                            <b>@lang('app.time'): </b>
                                                {{ get_date($item->start_date, 'H:i d/m/Y') }} <br>
                                                @if($item->end_date)
                                                    {{ get_date($item->end_date, 'H:i d/m/Y') }}
                                                    <br>
                                                @endif
                                            <b>@lang('app.duration'): </b> {{ $item->limit_time .' '. trans('app.min') }} <br>
                                            <b>Điểm đạt: </b> {{ $item->pass_score .'/'. $item->max_score }} <br>
                                            <b>{{ trans('latraining.status') }}: </b>
                                            @if($item->start_date > date('Y-m-d H:i:s'))
                                                <span class="text-warning">{{ trans('app.it_not_time_take_exam') }}</span>
                                            @elseif ($item->end_date && $item->end_date < date('Y-m-d H:i:s'))
                                                @if($item->view_result == 1)
                                                    {{ trans('app.review') }}
                                                @else
                                                    <span class="text-danger">@lang('app.exams_ended')</span>
                                                @endif
                                            @elseif($attempt)
                                                {{ trans('app.exam_taking') }}
                                            @elseif($item->start_date <= date('Y-m-d H:i:s'))
                                                {{ trans('app.going_on') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row">
                <div class="col text-center">
                    <span class="not_found">@lang('app.not_found')</span>
                </div>
            </div>
        @endif
    </div>
@endsection
