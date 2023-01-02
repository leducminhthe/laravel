@extends('react.layouts.app')
@section('page_title', trans('lapromotion.promotion'))

@section('content')
    <div id="languages"
        data-promotion="{{ trans('lapromotion.promotion') }}"
        data-gift_name="{{ trans('lapromotion.gift_name') }}"
        data-gift_point_filter="{{ trans('lapromotion.gift_point_filter') }}"
        data-ascending="{{ trans('lapromotion.ascending') }}"
        data-decrease="{{ trans('lapromotion.decrease') }}"
        data-redeem_gifts="{{ trans('lapromotion.redeem_gifts') }}"
        data-phone="{{ trans('lapromotion.phone') }}"
        data-gift_over="{{ trans('lapromotion.gift_over') }}"
        data-out_of_date="{{ trans('lapromotion.gift_out_of_date') }}"
        data-location_gift="{{ trans('lapromotion.location_gift') }}"
        data-receiving_period="{{ trans('lapromotion.receiving_period') }}"
        data-morning="{{ trans('lapromotion.morning') }}"
        data-afternoon="{{ trans('lapromotion.afternoon') }}"
        data-evening="{{ trans('lapromotion.evening') }}"
        data-received_date="{{ trans('lapromotion.received_date') }}"
        data-note="{{ trans('lapromotion.note') }}"
        data-quantity="{{ trans('lapromotion.quantity') }}"
        data-period="{{ trans('lapromotion.period') }}"
        data-student="{{ trans('lapromotion.student') }}"
        data-high_cumulative_points="{{ trans('lapromotion.high_cumulative_points') }}"
        data-see_detail="{{ trans('lapromotion.see_detail') }}"
        data-enter_location="{{ trans('lapromotion.enter_location') }}"
        data-enter_phone="{{ trans('lapromotion.enter_phone') }}"
        data-choose_time="{{ trans('lapromotion.choose_time') }}"
        data-fill_info="{{ trans('lapromotion.fill_info') }}"
        data-home_page="{{ trans('lamenu.home_page') }}"
    >
    </div>
    <div id="react" class="sa4d25">
                    
    </div>
@endsection
