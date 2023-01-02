@php
    $date = date('Y-m-d H:i:s')
@endphp
<div class="list-group-item wrapped_promotions">
    <div class="row align-items-center">
        <div class="col-auto align-self-center pr-0 pl-0">
            <img src="{{ image_promotion($promotion->images) }}" alt="" class="" style="width: 100px;">
        </div>
        <div class="col pr-0">
            <h6 class="title_promotion mb-1 @if($promotion_user && $promotion_user->point >= $promotion->point) font-weight-bold @else '' @endif text-left">
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.front.promotion.detail', ['id' => $promotion->id]) }}', 1, 3)">
                    {{ $promotion->name }}
                </a>
            </h6>
            <p class="text-mute mb-1">
                {{ $promotion->point }}
                <img class="avatar-20 point" src="{{ asset('images/level/point.png') }}" alt="">
                <span class="float-right">
                    @if ($promotion->amount == 0)
                        <span>(Hết hàng)</span>
                    @else
                        @lang('app.quantity'): <strong>{{ $promotion->amount }}</strong>
                    @endif
                </span>
            </p>
            <div class="promotion_period">
                <button class="get_promotion w-50 btn" onclick="promotionHandel({{$promotion->id}})">
                    <p>Đổi quà</p>
                </button>
            </div>
        </div>
    </div>
</div>

@section('footer')
    <script>
        function promotionHandel(id) {
            $('#modal_get_promotion').modal();
            $('.promotionId').val(id)
        }
    </script>
@endsection


