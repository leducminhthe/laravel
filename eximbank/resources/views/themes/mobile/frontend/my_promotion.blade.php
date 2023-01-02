@extends('themes.mobile.layouts.app')

@section('page_title', trans('backend.promotion_history'))

@section('content')
    <div class="container wrraped_promotion">
        <div class="row">
            <div class="col-12">
                <div class="list-group list-group-flush">
                    @if(count($promotion_orders) > 0)
                        @foreach($promotion_orders as $promotion)
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto align-self-center pr-0 pl-0">
                                    <img src="{{ image_promotion($promotion->images) }}" alt="" class="" style="width: 100px;">
                                </div>
                                <div class="col pr-0">
                                    <h6 class="font-weight-bold text-left name_promotion">{{ $promotion->name }}</h6>
                                    <p class="text-mute mb-1">
                                        {{ $promotion->group_name }}
                                        <br>
                                    </p>
                                    <div class="row">
                                        <div class="col-6">
                                            {{ $promotion->point }}
                                            <img class="avatar-20 point" src="{{ asset('images/level/point.png') }}" alt="">
                                        </div>
                                        <div class="col-6 text-right">
                                            {{ $promotion->status }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @include('themes.mobile.layouts.paginate', ['items' => $promotion_orders])
                    @else
                        <span class="text-center">@lang('app.not_found')</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
