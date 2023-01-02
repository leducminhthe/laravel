@extends('themes.mobile.layouts.app')

@section('page_title', trans('latraining.training_evaluation'))

@section('content')
    <div class="container">
        @if(count($list_rating_levels) > 0 && (\App\Models\Profile::usertype() != 2))
            @foreach($list_rating_levels as $item)
                <div class="row bg-white mb-2">
                    <div class="col-12">
                        <h6 class="mt-1 font-weight-bold" style="color: #1b4486;">{{ $item->rating_name }}</h6>
                    </div>
                    <div class="col-4 text-center pr-0">
                        <img alt="{{ $item->rating_name }}" class="lazy w-100" src="{{ asset('themes/mobile/img/evaluate_512.png') }}">
                    </div>
                    <div class="col-8 align-self-center">
                        <p style="font-size: 85%">
                            <b>@lang('app.time') : </b> <strong>{{ $item->rating_time }}</strong>
                            <br>
                            <b>@lang('app.status') : </b> <strong>{{ $item->rating_status }}</strong>
                            <br>
                            @if($item->rating_level_url)
                                <a href="javascript:void(0);" onclick="loadSpinner('{{ $item->rating_level_url }}')" class="btn text-white"> Đánh giá</a>
                            @else
                                <a href="#" class="btn text-white">Không thể đánh giá</a>
                            @endif
                            <br>
                            @if($item->colleague)
                                <a href="javascript:void(0)" class="btn load-modal text-white" data-url="{{ $item->modal_object_colleague_url }}"> <i class="material-icons">person_add</i></a>
                            @endif
                        </p>
                    </div>
                </div>
            @endforeach
        @else
            <div class="row">
                <div class="col text-center">
                    <span class="not_found">@lang('app.not_found')</span>
                </div>
            </div>
        @endif
    </div>
@stop

@section('footer')
    <script type="text/javascript">
    </script>
@stop
