<div class="infinite-scroll">
    <div class="col-md-12">
        <div class="_14d25 mt-0">
            <div class="row">
                @foreach($orders as $order)
                    {{--{{ debug($order) }}--}}
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="fcrse_1">
                            <div class="promotion-images">
                                <a href="#"><img src="{{ image_promotion($order->images) }}" alt=""></a>
                            </div>
                            <div class="tutor_content_dt">
                                <div class="tutor150 mt-2">
                                    <a href="#" class="tutor_name">{{ $order->name }}</a>
                                    <div class="mef78" title="Verify">
                                        <i class="uil uil-check-circle"></i>
                                    </div>
                                </div>
                                <div class="tutor_cate"><a href="#">{{ $order->group_name }}</a></div>
                                <button class="btn btn_adcart">
                                    {{ $order->point }}
                                    <img class="point w-5" src="{{ asset('images/level/point.png') }}" alt="">
                                </button>
                                <div class="tut1250">
                                    <p class="vdt15 mb-1"><strong>@lang('laprofile.quantity') : {{ $order->quantity }}</strong></p>
                                    <p class="vdt15 mb-2"><strong>@lang('laprofile.period') : {{ \Carbon\Carbon::parse($order->period)->format('d-m-Y') }}</strong></p>
                                </div>
                                <div class="auth1lnkprce">
                                    <p class="cr1fot"><a href="#">@lang('laprofile.status'): {{ $order->status }}</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    {{ $orders->links() }}
</div>
