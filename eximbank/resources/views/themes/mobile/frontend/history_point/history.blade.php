@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.point_accumulation_history'))

@section('content')
    <div class="container wrraped_point_accumulation_history">
        <div class="row">
            <div class="col-12 px-0">
                <div class="swiper-container career-slide">
                    <div class="swiper-wrapper nav-pills mb-2 text-center" id="nav-tab" role="tablist">
                        <a class="swiper-slide nav-item nav-link active pl-0 pr-0" id="nav-course-tab" data-toggle="tab" href="#nav-course" role="tab" aria-selected="true">
                            <span>
                                <strong>
                                    {{ trans('lamenu.course') }}
                                </strong>
                            </span>
                        </a>
                        {{--  <a class="swiper-slide nav-item nav-link pl-0 pr-0" id="nav-video-tab" data-toggle="tab" href="#nav-video" role="tab" aria-selected="true">
                            <span>
                                <strong>
                                    {{ trans('lamenu.document') }} video
                                </strong>
                            </span>
                        </a>  --}}
                        <a class="swiper-slide nav-item nav-link pl-0 pr-0" id="nav-point-tab" data-toggle="tab" href="#nav-point" role="tab" aria-selected="true">
                            <span>
                                <strong>
                                    Điểm tặng
                                </strong>
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 px-0">
                <div class="career_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade active show" id="nav-course" role="tabpanel">
                            <div class="list-group list-group-flush">
                                @if(count($get_history_course) > 0)
                                    @foreach($get_history_course as $item)
                                        <div class="row mx-0 mb-1 bg-white shadow border">
                                            <div class="col-auto align-self-center">
                                                <img src="{{ asset('themes/mobile/img/heo.png') }}" alt="" class="avatar-40">
                                            </div>
                                            <div class="col pl-0">
                                                <p class="text-mute mb-0">
                                                    {!! ($item->content) !!}
                                                </p>
                                                <p class="mt-1 mb-0">
                                                    <span class="text-info">{{ $item->name }}</span>
                                                    <span class="text-mute float-right">
                                                        @if($item->point)
                                                            +{{ $item->point }} <img class="point avatar-20" src="{{ asset('images/level/point.png') }}" alt="">
                                                        @endif
                                                    </span>
                                                </p>
                                                <p class="text-mute mt-1 mb-1">
                                                    {{ ($item->datecreated) }}
                                                    <span class="float-right">
                                                        @if ($item->type_promotion == 1)
                                                            Click to earn
                                                        @else
                                                            Learn to earn
                                                        @endif
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="row mt-2">
                                        <div class="col-12 px-0 text-right">
                                            {{ $get_history_course->links('themes/mobile/layouts/pagination') }}
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <span>@lang('app.not_found')</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-video" role="tabpanel">
                            <div class="list-group list-group-flush">
                                @if(count($get_history_video) > 0)
                                    @foreach($get_history_video as $item)
                                        @php
                                            $score_view = \Modules\DailyTraining\Entities\DailyTrainingVideo::getScoreView($item->view);
                                            $score_comment = \Modules\DailyTraining\Entities\DailyTrainingVideo::getScoreComment($item->id);
                                        @endphp
                                        <div class="row mb-1 bg-white shadow border">
                                            <div class="col-auto align-self-center">
                                                <img src="{{ asset('themes/mobile/img/video.png') }}" alt="" class="avatar-40">
                                            </div>
                                            <div class="col pl-0">
                                                <p class="mb-0">{{ $item->name }}</p>
                                                <p class="text-mute mt-1 mb-0">
                                                    {{ get_date($item->created_at) }}
                                                </p>
                                                <p class="text-mute mt-1 mb-1">
                                                    @if($score_view)
                                                        {{ $score_view }} <img class="point avatar-20" src="{{ asset('images/level/point.png') }}" alt="">
                                                        <span class="float-right">
                                                            {{ trans('app.view') }}
                                                        </span>
                                                    @endif
                                                </p>
                                                <p class="text-mute mt-1 mb-1">
                                                    @if($score_comment)
                                                        {{ $score_comment }} <img class="point avatar-20" src="{{ asset('images/level/point.png') }}" alt="">
                                                        <span class="float-right">
                                                            {{ trans('app.comment') }}
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="row mt-2">
                                        <div class="col-12 px-0 text-right">
                                            {{ $get_history_video->links('themes/mobile/layouts/pagination') }}
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <span>@lang('app.not_found')</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-point" role="tabpanel">
                            <div class="list-group list-group-flush">
                                @if(count($donate_points) > 0)
                                    @foreach ($donate_points as $donate_point)
                                        <div class="row mx-0 mb-1 bg-white shadow border">
                                            <div class="col-auto align-self-center">
                                                <img src="{{ asset('themes/mobile/img/promotion.png') }}" alt="" class="avatar-40">
                                            </div>
                                            <div class="col pl-0">
                                                <p class="mb-0" style="line-height: 20px;">{{ $donate_point->note }}</p>
                                                <p class="text-mute mt-1 mb-1">
                                                    +{{ $donate_point->score }} <img class="point avatar-20" src="{{ asset('images/level/point.png') }}" alt="">
                                                    <span class="small float-right">
                                                        {{ get_date($donate_point->created_at, 'd/m/Y H:i:s') }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center">
                                        <span>@lang('app.not_found')</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        $('#nav-tab').on('click', '.nav-item', function () {
            $('a[data-toggle="tab"]').removeClass('active');
        });

        $(document).ready(function(){
            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab-training-by-title', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab-training-by-title');
            if(activeTab){
                $('a[data-toggle="tab"]').removeClass('active');
                $('#nav-tab a[href="' + activeTab + '"]').tab('show');
                $('#nav-tab a[href="' + activeTab + '"]').addClass('active');
            }
        });

        var swiper = new Swiper('.career-slide', {
            slidesPerView: 'auto',
            spaceBetween: 0,
            breakpoints: {
                1024: {
                    slidesPerView: 2,
                    spaceBetween: 0,
                },
                768: {
                    slidesPerView: 2,
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
