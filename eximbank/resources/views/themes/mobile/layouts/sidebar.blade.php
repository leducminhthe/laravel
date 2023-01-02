<div class="sidebar">
    <div class="pt-1 pb-2 mb-1 bg-white">
        <div class="row">
            <div class="col text-center">
                @if(!userThird())
                    @php
                        $logo = \App\Models\LogoModel::where('status',1)->first(['image']);
                    @endphp
                <img src="{{ image_file(@$logo->image, 'logo') }}" alt="" class="header-logo">
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        @php
            $backgroundMenu = \App\Models\Config::where('name', 'bg_menu')->first()->value;
            $colorMenu = $colorMenu->text;
        @endphp
        <style>
            .wrapped_text_icon {
                display: inline-block;
            }
            .icons-menu {
                width: 20px;
                height: 20px;
                margin-right: 10px;
                float: left;
                background: {{ $colorMenu }};
            }
            .deeppurple-theme .sidebar {
                background:  {{ $backgroundMenu }};
            }
            .deeppurple-theme .sidebar .main-menu .list-group-item {
                color: {{ $colorMenu }}
            }
        </style>
        <div class="col">
            <div class="list-group main-menu">
                @php
                    $count_course = \App\Models\CourseView::where('status', 1)->count();
                    $count_daily_video = \Modules\DailyTraining\Entities\DailyTrainingVideo::where(function($sub){
                        $sub->where('category_id', 1);
                        $sub->orWhere('approve', 1);
                    })->count();
                    $courseView = new \App\Models\CourseView();
                    $count_my_course = $courseView->countMyCourse();

                    $profile = profile();
                    $getMenuSetting = \App\Models\MenuSetting::where('title_id', $profile->title_id)->pluck('menu_value')->toArray();
                    $menuSetting = (!empty($getMenuSetting) && profile()->user_id > 2) ? $getMenuSetting : [];
                @endphp
                {{-- @if (\App\Models\Permission::isUnitManagerPermission())
                    <select class="form-control select2" name="user-unit" id="user-unit-top" data-minimum-results-for-search="Infinity" data-allowClear="false" role="button" data-url="{{ route('backend.save_select_unit')}}">
                        @foreach($userUnits as $index =>$item)
                            {{$selected = $item->id == session('user_unit') ? 'selected' : ''}}
                            <option value="{{$item->id}}" {{$selected}}>{{$item->name}}  - {{$item->code}}</option>
                        @endforeach
                    </select>
                @endif --}}
                @if(!in_array($routeName, ['themes.mobile.frontend.home', 'frontend.home']))
                    <a href="{{ route('themes.mobile.frontend.home') }}" class="list-group-item list-group-item-action">
                        <div class="icons-menu"
                            style="-webkit-mask: url({{ asset('themes/mobile/img/home.png') }}) no-repeat;
                            mask: url({{ asset('themes/mobile/img/home.png') }}) no-repeat;
                            -webkit-mask-size: 20px 20px;">
                        </div>
                        <span>Home</span>
                        <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                    </a>
                @endif
                @if(!userThird() && \App\Models\User::canPermissionTrainingOrganization())
                    <a href="{{ route('themes.mobile.frontend.approve_course.course') }}" class="list-group-item list-group-item-action">
                        <div class="icons-menu"
                            style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-8.svg') }}) no-repeat;
                            mask: url({{ asset('images/svg-backend/svgexport-8.svg') }}) no-repeat;
                            -webkit-mask-size: 20px 20px;">
                        </div>
                        <span>@lang('app.approve_register') ({{ $count_course }})</span>
                        <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                    </a>
                @endif
                @if(\App\Models\Permission::isTeacher())
                    <a href="{{ route('theme.mobile.frontend.attendance') }}" class="list-group-item list-group-item-action">
                        <div class="icons-menu"
                            style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-95.svg') }}) no-repeat;
                            mask: url({{ asset('images/svg-backend/svgexport-95.svg') }}) no-repeat;
                            -webkit-mask-size: 20px 20px;">
                        </div>
                        <span>Scan @lang('app.attendance') ({{ trans('lamenu.offline_course') }})</span>
                        <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                    </a>
                @endif
                @if(!userThird())
                    @if (!empty($menuSetting) && in_array('course_1', $menuSetting) || empty($menuSetting))
                        <a href="{{ route('themes.mobile.frontend.online.index') }}" class="list-group-item list-group-item-action">
                            <div class="wrapped_text_icon">
                                <div class="icons-menu"
                                    style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-4.svg') }}) no-repeat;
                                    mask: url({{ asset('images/svg-backend/svgexport-4.svg') }}) no-repeat;
                                    -webkit-mask-size: 20px 20px;">
                                </div>
                                <span>{{ trans('laother.register') }} KH Online</span>
                            </div>
                            <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                        </a>
                    @endif
                    @if (!empty($menuSetting) && in_array('course_2', $menuSetting) || empty($menuSetting))
                        <a href="{{ route('themes.mobile.frontend.offline.index') }}" class="list-group-item list-group-item-action">
                            <div class="wrapped_text_icon">
                                <div class="icons-menu"
                                    style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-5.svg') }}) no-repeat;
                                    mask: url({{ asset('images/svg-backend/svgexport-5.svg') }}) no-repeat;
                                    -webkit-mask-size: 20px 20px;">
                                </div>
                                <span>{{ trans('laother.register') }} KH Offline</span>
                            </div>
                            <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                        </a>
                    @endif
                    @if (!empty($menuSetting) && in_array('course_3', $menuSetting) || empty($menuSetting))
                        <a href="{{ route('themes.mobile.frontend.my_course') }}" class="list-group-item list-group-item-action">
                            <div class="icons-menu"
                                style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-5.svg') }}) no-repeat;
                                mask: url({{ asset('images/svg-frontend/svgexport-5.svg') }}) no-repeat;
                                -webkit-mask-size: 20px 20px;">
                            </div>
                            <span>@lang('app.my_course') ({{ $count_my_course }})</span>
                            <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                        </a>
                    @endif
                    @if (!empty($menuSetting) && in_array('quiz', $menuSetting) || empty($menuSetting))
                        <a href="{{ route('module.quiz.mobile') }}" class="list-group-item list-group-item-action">
                            <div class="icons-menu"
                                style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-34.svg') }}) no-repeat;
                                mask: url({{ asset('images/svg-frontend/svgexport-34.svg') }}) no-repeat;
                                -webkit-mask-size: 20px 20px;">
                            </div>
                            <span>@lang('app.quiz_mobile') ({{ $count_quiz }})</span>
                            <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                        </a>
                    @endif
                @endif
                @if (!empty($menuSetting) && in_array('menu_news', $menuSetting) || empty($menuSetting))
                    <a href="{{ route('theme.mobile.news')  }}" class="list-group-item list-group-item-action">
                        <div class="icons-menu"
                            style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-3.svg') }}) no-repeat;
                            mask: url({{ asset('images/svg-frontend/svgexport-3.svg') }}) no-repeat;
                            -webkit-mask-size: 20px 20px;">
                        </div>
                        <span>@lang('app.news')</span>
                        <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                    </a>
                @endif
                @if(userThird())
                    <a href="{{ route('themes.mobile.libraries') }}" class="list-group-item list-group-item-action">
                        <div class="icons-menu"
                            style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-13.svg') }}) no-repeat;
                            mask: url({{ asset('images/svg-frontend/svgexport-13.svg') }}) no-repeat;
                            -webkit-mask-size: 20px 20px;">
                        </div>
                        <span>@lang('app.library')</span>
                        <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                    </a>
                    <a href="{{ route('themes.mobile.frontend.forums') }}" class="list-group-item list-group-item-action">
                        <div class="icons-menu"
                            style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-19.svg') }}) no-repeat;
                            mask: url({{ asset('images/svg-frontend/svgexport-19.svg') }}) no-repeat;
                            -webkit-mask-size: 20px 20px;">
                        </div>
                        <span>@lang('app.forum')</span>
                        <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                    </a>
                @endif
                <a href="javascript:void(0)" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#colorscheme">
                    <div class="icons-menu"
                        style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-86.svg') }}) no-repeat;
                        mask: url({{ asset('images/svg-backend/svgexport-86.svg') }}) no-repeat;
                        -webkit-mask-size: 20px 20px;">
                    </div>
                    <span>@lang('app.color_scheme')</span>
                    <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                </a>
                {{-- <a href="{{ route('logout') }}" class="list-group-item list-group-item-action">
                    <div class="icons-menu"
                        style="-webkit-mask: url({{ asset('images/svg-frontend/logout.svg') }}) no-repeat;
                        mask: url({{ asset('images/svg-frontend/logout.svg') }}) no-repeat;
                        -webkit-mask-size: 20px 20px;">
                    </div>
                    <span>@lang('app.logout')</span>
                    <span class="float-right"><i class="material-icons">keyboard_arrow_right</i></span>
                </a> --}}
            </div>
        </div>
    </div>
</div>
