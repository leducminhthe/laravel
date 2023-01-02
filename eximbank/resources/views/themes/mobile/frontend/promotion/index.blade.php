@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.preferential_stores'))

@section('content')
    <div class="container wrraped_promotion">
        <div class="row pt-2 pb-2">
            <div class="col-auto">
                <img src="{{ \App\Models\Profile::avatar() }}" alt="" class="avatar avatar-50">
            </div>
            <div class="col p-0">
                <b>{{ ($profile->gender == 1 ? 'Anh ' : 'Chị ') . $profile->firstname }}</b> <br>
                <img class="point avatar-20" src="{{ asset('images/level/point.png') }}" alt="">
                {{ $promotion_user ? $promotion_user->point .' điểm' : '' }}
            </div>
            <div class="col-auto d-flex m-auto" style="background: #f5f5f5; padding: 5px; border-radius: 5px;">
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.my_promotion') }}', 1, 3)" class="w-100">
                    <img src="/images/svg-backend/svgexport-49.svg" alt="" style="width: 25px;">
                    <span class="ml-2">Quà của tôi</span>
                </a>
            </div>
        </div>
        @if ($max_point->count() > 0)
            <h6>{{ trans('app.top_students_high_scores') }}</h6>
            <div class="row">
                @foreach ($max_point as $item)
                    <div class="col-4 p-0 text-center">
                        <div class="p-1">
                            <img src="{{ image_user($item->avatar) }}" alt="" class="avatar avatar-50 border-0">
                            <p class="small mt-1 mb-1">
                                {{ $item->full_name }}
                            </p>
                            <p class="text-mute">
                                {{ $item->point }}
                                <img class="point avatar-20" src="{{ asset('images/level/point.png') }}" alt="">
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="promotion_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="fade active show" id="nav-all" role="tabpanel">
                            <div class="list-group list-group-flush">
                                @if(count($promotions) > 0)
                                    @foreach($promotions as $promotion)
                                        @include('themes.mobile.frontend.promotion.item')
                                    @endforeach
                                    @include('themes.mobile.layouts.paginate', ['items' => $promotions])
                                @else
                                    <span class="text-center">@lang('app.not_found')</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('themes.mobile.frontend.promotion.modal_gift_exchange')
@endsection

@section('footer')
    <script type="text/javascript">
        $('#nav-tab').on('click', '.nav-item', function () {
            $('a[data-toggle="tab"]').removeClass('active');
        });

        $(document).ready(function(){
            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab-promotion', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab-promotion');
            if(activeTab){
                $('a[data-toggle="tab"]').removeClass('active');
                $('#nav-tab a[href="' + activeTab + '"]').tab('show');
                $('#nav-tab a[href="' + activeTab + '"]').addClass('active');
            }
        });

        var swiper = new Swiper('.promotion-slide', {
            slidesPerView: 3,
            spaceBetween: 0,
            breakpoints: {
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 0,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 0,
                },
                640: {
                    slidesPerView: 2,
                    spaceBetween: 0,
                },
                320: {
                    slidesPerView: 2,
                    spaceBetween: 0,
                }
            }
        });
    </script>
@endsection
