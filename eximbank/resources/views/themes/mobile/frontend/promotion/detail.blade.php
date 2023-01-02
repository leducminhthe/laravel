@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.preferential_stores'))

@section('content')
    <div class="container wrapped_promotion">
        <div class="row">
            <div class="col-12 promotion_image">
                <img src="{{ image_promotion($promotion->images) }}" alt="" class="" width="100%" height="100%">
            </div>
            <div class="col-12 mb-2 mt-2">
                <h5><strong>{{ $promotion->name }}</strong></h5>
            </div>
            <div class="col-4">
                <p class="mb-1">
                    Điểm: {{ $promotion->point }}
                    <img class="avatar-20 point" src="{{ asset('images/level/point.png') }}" alt="">
                </p>
                <p class="mb-1">
                    @if ($promotion->amount == 0)
                        <span>(Hết hàng)</span>
                    @else
                        @lang('app.quantity'): <strong>{{ $promotion->amount }}</strong>
                    @endif
                </p>
            </div>
            <div class="col-8 end_date">
                <p class="mb-1">@lang('app.end_date')</p>
                <p class="mb-1"><b>{{ \Carbon\Carbon::parse($promotion->period)->format('d/m/Y') }}</b></p>
            </div>
        </div>
        <hr>
        <div class="row rules">
            <div class="col-12">
                <h6>Thể lệ</h6>
            </div>
            <div class="col-12">
                {!! $promotion->rules !!}
            </div>
        </div>
    </div>
    <div class="gift_exchange">
        <button class="get_promotion btn" onclick="promotionHandel({{$promotion->id}})">
            <p>Đổi quà</p>
        </button>
    </div>
@endsection

@section('modal')
    @include('themes.mobile.frontend.promotion.modal_gift_exchange')
@endsection

@section('footer')
    <script>
        function promotionHandel(id) {
            $('#modal_get_promotion').modal();
            $('.promotionId').val(id)
        }
    </script>
@endsection

