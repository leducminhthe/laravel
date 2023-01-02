@extends('themes.mobile.layouts.app')

@section('page_title', 'Home')

@section('content')
    {{-- <div class="container">
        <div class="row">
            <div id="carouselExampleSlidesOnly" class="carousel slide mb-3 w-100 carousel_slide_mobile" data-ride="carousel">
                <div class="carousel-inner">
                    @foreach($sliders as $key => $slider)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <img src="{{ image_file($slider->image) }}" alt="" class="w-100" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div> --}}
    <div class="container" id="home_page">
        <style>
            #home .avatar{
                border-radius: unset;
                background-color: unset;
                display: flex;
            }
            #home .col-4{
                padding-left: .25rem!important;
                padding-right: .25rem!important;
            }
            #home .wrapped_item_mobile a p{
                color: #2861a8d9;
            }
            .wrapped_online {
                background: linear-gradient(30deg, #b8ffb3, #f1fff0);
            }
            .wrapped_offline {
                background: linear-gradient(30deg, #fff399, #fffdeb);
            }
            .wrapped_my_course {
                background: linear-gradient(30deg, #aaa1f7, #eeecfd);
            }
            .wrapped_quiz {
                background: linear-gradient(30deg, #f3a5d4, #fdedf6);
            }
            .wrapped_rating {
                background: linear-gradient(30deg, #f0988e, #fdedf6);
            }
            .wrapped_news {
                background: linear-gradient(30deg, #80f9ff, #e6feff);
            }
            .wrapped_libraries {
                background: linear-gradient(30deg, #ffe6fb, #fffafe);
            }
            .wrapped_forums {
                background: linear-gradient(30deg, #fffede, #fffff8);
            }
        </style>
        <div class="row bg-white p-2 shadow" id="home">
            @if($user_type != 2)
                @if (!empty($menuSetting) && in_array('course_3', $menuSetting) || empty($menuSetting))
                    <div class="col-6 mb-1 p-2">
                        <div class="wrapped_item_mobile wrapped_my_course">
                            <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.my_course') }}', 1, 1)" class=" text-center">
                                <div class="avatar avatar-50 no-shadow border-0">
                                    <img src="{{ asset('themes/mobile/img/design/icon_my_course.png') }}" alt="">
                                </div>
                                <p class="my-1 mb-0 title_item">{{ data_locale('Khóa học', 'My Courses') }} {{ data_locale('của tôi', ' ') }}</p>
                                @if ( $count_my_course > 0)
                                    <span class="count_my_course">{{ $count_my_course }}</span>
                                @endif
                            </a>
                        </div>
                    </div>
                @endif
                @if (!empty($menuSetting) && in_array('quiz', $menuSetting) || empty($menuSetting))
                    <div class="col-6 mb-1 p-2">
                        <div class="wrapped_item_mobile wrapped_quiz">
                            <a href="javascript:void(0);" onclick="loadSpinner('{{ route('module.quiz.mobile', 1, 2) }}')" class=" text-center">
                                <div class="avatar avatar-50 no-shadow border-0">
                                    <img src="{{ asset('themes/mobile/img/design/icon_quiz.png') }}" alt="">
                                </div>
                                <p class="my-1 mb-0 title_item">@lang('app.quiz_mobile')</p>
                                @if ($count_quiz > 0)
                                    <span class="count_my_course">{{ $count_quiz }}</span>
                                @endif
                            </a>
                        </div>
                    </div>
                @endif
                @if (!empty($menuSetting) && in_array('course_1', $menuSetting) || empty($menuSetting))
                    <div class="col-6 mb-1 p-2">
                        <div class="wrapped_item_mobile wrapped_online">
                            <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.online.index') }}', 1, 1)" class=" text-center">
                                <div class="avatar avatar-50 no-shadow border-0">
                                    <img src="{{ asset('themes/mobile/img/design/icon_online.png') }}" alt="">
                                </div>
                                <p class="mt-2 mb-1 title_item">{{ trans('laother.register') }} KH Online</p>
                            </a>
                        </div>
                    </div>
                @endif
                @if (!empty($menuSetting) && in_array('course_2', $menuSetting) || empty($menuSetting))
                    <div class="col-6 mb-1 p-2">
                        <div class="wrapped_item_mobile wrapped_offline">
                            <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.offline.index') }}', 1, 1)" class=" text-center">
                                <div class="avatar avatar-50 no-shadow border-0">
                                    <img src="{{ asset('themes/mobile/img/design/icon_offline.png') }}" alt="">
                                </div>
                                <p class="mt-2 mb-1 title_item">{{ trans('laother.register') }} KH Offline</p>
                            </a>
                        </div>
                    </div>
                @endif
                {{-- <div class="col-6 mb-1 p-2">
                    <div class="wrapped_item_mobile wrapped_rating">
                        <a href="{{ route('themes.mobile.faq.frontend.index') }}" class=" text-center">
                            <div class="avatar avatar-50 no-shadow border-0">
                                <img src="{{ asset('themes/mobile/img/design/faq_home.png') }}" alt="">
                            </div>
                            <p class="mt-2 mb-1 title_item">@lang('app.faq')</p>
                        </a>
                    </div>
                </div> --}}
                {{-- <div class="col-6 mb-1 p-2">
                    <div class="wrapped_item_mobile" style="background: #d4fce8">
                        <a href="{{ route('module.frontend.training_by_title') }}" class=" text-center">
                            <div class="avatar avatar-50 no-shadow border-0">

                                <img src="{{ asset('themes/mobile/img/design/icon_roadmap.png') }}" alt="">
                            </div>
                            <p class="mt-2 mb-1 title_item">{{ data_locale('Lộ trình đào tạo', 'Training Roadmap') }}</p>
                        </a>
                    </div>
                </div> --}}
                {{-- <div class="col-6 mb-1 p-2">
                    <div class="wrapped_item_mobile" style="background: #ebe1ff">
                        <a href="{{ route('themes.mobile.frontend.training_process') }}" class=" text-center">
                            <div class="avatar avatar-50 no-shadow border-0">

                                <img src="{{ asset('themes/mobile/img/design/icon_history.png') }}" alt="">
                            </div>
                            <p class="mt-2 mb-1 title_item">{{ data_locale('Lịch sử học tập', 'Learning History') }}</p>
                        </a>
                    </div>
                </div> --}}
                {{--  @if (!empty($menuSetting) && in_array('menu_news', $menuSetting) || empty($menuSetting))
                    <div class="col-6 mb-1 p-2">
                        <div class="wrapped_item_mobile wrapped_news">
                            <a href="{{ route('theme.mobile.news') }}" class="text-center">
                                <div class="avatar avatar-50 no-shadow border-0">
                                    <img src="{{ asset('themes/mobile/img/design/icon_news.png') }}" alt="">
                                </div>
                                <p class="mt-2 mb-1 title_item">{{ data_locale('Tin tức', 'New') }}</p>
                            </a>
                        </div>
                    </div>
                @endif  --}}
                <div class="col-6 mb-1 p-2">
                    <div class="wrapped_item_mobile wrapped_news">
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.saleskit.salekit') }}',1 ,2)" class="text-center">
                            <div class="avatar avatar-50 no-shadow border-0">
                                <img src="{{ asset('themes/mobile/img/design/icon_sale.png') }}" alt="">
                            </div>
                            <p class="mt-2 mb-1 title_item">Sales kit</p>
                        </a>
                    </div>
                </div>

                {{-- DANH SÁCH QUÀ TẶNG --}}
                <div class="col-6 mb-1 p-2">
                    <div class="wrapped_item_mobile wrapped_forums">
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.front.promotion') }}',1 ,3)" class="text-center">
                            <div class="avatar avatar-50 no-shadow border-0">
                                <img src="{{ asset('themes/mobile/img/design/icon_promotion.png') }}" alt="">
                            </div>
                            <p class="mt-2 mb-1 title_item">{{ data_locale('Quà tặng', 'Gift') }}</p>
                        </a>
                    </div>
                </div>

                {{-- QUẢN LÝ ĐƠN VỊ --}}
                @if (\App\Models\Permission::isUnitManagerPermission())
                    <div class="col-6 mb-1 p-2">
                        <div class="wrapped_item_mobile wrapped_libraries">
                            <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.dashboard_unit') }}')" class="text-center">
                                <div class="avatar avatar-50 no-shadow border-0">
                                    <img src="{{ asset('themes/mobile/img/design/clipboard.png') }}" alt="">
                                </div>
                                <p class="mt-2 mb-1 title_item">{{ data_locale('Quản lý đơn vị', 'Unit Manager') }}</p>
                            </a>
                        </div>
                    </div>
                @endif
            @else
                <div class="col-4 mb-2">
                    <div class="wrapped_item_mobile wrapped_news">
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('theme.mobile.news') }}')" class="text-center">
                            <div class="avatar avatar-50 no-shadow border-0">
                                <img src="{{ asset('themes/mobile/img/design/icon_news.png') }}" alt="">
                            </div>
                            <p class="mt-2 mb-1 title_item">{{ data_locale('Tin tức', 'New') }}</p>
                        </a>
                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="wrapped_item_mobile wrapped_libraries">
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.libraries') }}')" class=" text-center">
                            <div class="avatar avatar-50 no-shadow border-0">
                                <img src="{{ asset('themes/mobile/img/design/icon_library.png') }}" alt="">
                            </div>
                            <p class="mt-2 mb-1 title_item">@lang('app.library')</p>
                        </a>
                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="wrapped_item_mobile wrapped_forums">
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.forums') }}')" class=" text-center">
                            <div class="avatar avatar-50 no-shadow border-0">
                                <img src="{{ asset('themes/mobile/img/design/icon_forum.png') }}" alt="">
                            </div>
                            <p class="mt-2 mb-1 title_item">@lang('app.forum')</p>
                        </a>
                    </div>
                </div>
            @endif

            {{-- @if($user_type != 2)
                @if (!empty($menuSetting) && in_array('promotion', $menuSetting) || empty($menuSetting))
                    <div class="col-6 mb-1 p-2">
                        <div class="wrapped_item_mobile" style="background: #ffe6e6">
                            <a href="{{ userThird() ? 'javascript:void(0)' : route('themes.mobile.front.promotion') }}" class="{{ userThird() ? 'userThird' : '' }} text-center">
                                <div class="avatar avatar-50 no-shadow border-0">

                                    <img src="{{ asset('themes/mobile/img/design/icon_promotion.png') }}" alt="">
                                </div>
                                <p class="mt-2 mb-1 title_item">{{ data_locale('Quà tặng', 'Gift') }}</p>
                            </a>
                        </div>
                    </div>
                @endif
            @endif --}}
        </div>
    </div>

    {{-- <div class="container">
        <div class="row bg-white p-2 mt-3 border-rgba">
            <div class="col-12 px-0">
                <div class="card border-0">
                    <div class="row m-0">
                        <div class="col-auto pr-0">
                            <img src="{{ \App\Models\Profile::avatar() }}" alt="" class="avatar avatar-50 border-0">
                        </div>
                        <div class="col align-self-center">
                            <p class="mb-1">
                                {{ $profile->firstname .' có ' }} <span class="color_text">{{ @$promotion->point }}</span> {{ ' điểm tích lũy' }}
                                <span class="float-right">
                                    Xếp hạng
                                </span>
                            </p>
                            @if($promotion_level)
                                <div class="row">
                                    <div class="col-auto pr-0">
                                        <img src="{{ image_file($promotion_level->images) }}" alt="" class="avatar avatar-20 border-0">
                                    </div>
                                    <div class="col align-self-center">
                                        <span class="color_text font-weight-bold">{{ $promotion_level->name }}</span>
                                        <span class="float-right">
                                        <span class="color_text">{{ $user_rank }}</span>{{'/'. $total_user->count() }}
                                    </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
