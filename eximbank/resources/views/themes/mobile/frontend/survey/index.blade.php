@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.survey'))

@section('content')
    <div class="container wrraped_survey">
        @if(count($surveys) > 0 && (\App\Models\Profile::usertype() != 2))
            @foreach($surveys as $item)
                @php
                    $survey = $item->users->where('id',auth()->id())->first();
                    $format_endate = \Carbon\Carbon::parse($item->end_date)->format('Y-m-d');
                    $date = date('Y-m-d');
                @endphp
                <div class="row bg-white mb-2">
                    <div class="col-12">
                        <h6 class="mt-1 font-weight-bold page_title">{{ $item->name }}</h6>
                    </div>
                    <div class="col-5 text-center pr-0">
                        <img alt="{{ $item->name }}" class="lazy w-100" src="{{ image_survey(@$item->image) }}" height="150px" style="object-fit: cover">
                    </div>
                    <div class="col-7 align-self-center info">
                        <p style="font-size: 85%">
                            <b>@lang('app.time') : </b>
                            <strong>{{ \Carbon\Carbon::parse($item->start_date)->format('H:i d/m/Y') }}</strong>
                            <br>
                            <b>@lang('app.end') : </b>
                            <strong>{{ \Carbon\Carbon::parse($item->end_date)->format('H:i d/m/Y') }}</strong>
                            <br>
                            @if($survey && $survey->pivot->send == 1)
                                <b>@lang('app.status') : </b> @lang('app.completed')
                            @endif

                            <span class="float-left">
                                @if(\Carbon\Carbon::now()->diffInHours($item->start_date) <= 3 && \Carbon\Carbon::now()->diffInHours($item->start_date) > 0)
                                    @lang('app.upcoming')
                                @elseif($item->start_date > date('Y-m-d H:i:s'))
                                @elseif (!$survey && ($date > $format_endate))
                                    <button type="button" class="btn float-right">@lang('app.end_survey')</button>
                                @elseif (!$survey && ($item->start_date <= date('Y-m-d H:i:s')))
                                    <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.survey.user', ['id' => $item->id]) }}')" class="btn text-white">
                                        @lang('app.take_survey')
                                    </a>
                                @elseif ($survey && $survey->pivot->send == 1)
                                    <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.survey.user.edit', ['id' => $item->id]) }}')" class="btn text-white">
                                       @lang('app.view_survey')
                                    </a>
                                @else
                                    <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.survey.user.edit', ['id' => $item->id]) }}')" class="btn text-white">
                                        @lang('app.edit_survey')
                                    </a>
                                @endif
                            </span>
                        </p>
                    </div>
                </div>
            @endforeach
            @include('themes.mobile.layouts.paginate', ['items' => $surveys])
        @else
            <div class="row">
                <div class="col text-center">
                    <span>@lang('app.not_found')</span>
                </div>
            </div>
        @endif
    </div>
@endsection
