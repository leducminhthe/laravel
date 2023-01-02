@php
    $tabs = Request::segment(1);
    if ($tabs == 'user' || $tabs == 'all-course' || $tabs == 'library' || $tabs == 'libraries') {
        $tab_3 = Request::segment(2);
    }
    $user_type = \Modules\Quiz\Entities\Quiz::getUserType();

    $text_color_menu = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_menu->text;
    $text_color_menu_active = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_menu->active;
    $hover_text_color_menu = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_menu->hover_text;
    $background_menu = $get_color_menu->background;
    $hover_background_menu = $get_color_menu->hover_background;
    $background_menu_child = $get_color_menu->background_child;

    $itemMenu3 = ['daily_training', 'survey', 'rating_level', 'library', 'book', 'ebook', 'document', 'audio', 'video', 'forum', 'suggest', 'topic_situation', 'coaching_teacher'];
    $itemMenu4 = ['promotion', 'usermedal', 'usermedal_history', 'user_point_history', 'my_promotion'];
    $itemMenu5 = ['guide', 'pdf', 'guide_video', 'guide_post', 'faq'];
    $itemLibrary = ['book', 'ebook', 'audio', 'video', 'document', 'salekit'];

@endphp
<style>
    .sub_menu--link:hover span,
    .menu_child--item:hover .menu_child--link span,
    .menu--link:hover .tilte_item_menu span {
        color: {{ $hover_text_color_menu }};
    }
    .menu--link:hover .menu--icon {
        background: {{ $hover_text_color_menu }};
    }
    .menu_child--item:hover,
    .sub_menu--link:hover,
    .menu--link:hover .tilte_item_menu {
        background: {{ $hover_background_menu }};
    }
    .color_num,
    .menu--item .menu--label,
    .menu_child--link,
    .sub_menu--link {
        color: {{ $text_color_menu }};
    }
    .active_menu_child > a,
    .active_menu_child > label {
        border-right: 5px solid {{ $background_menu }};
    }
    .menu--icon,
    .menu_icon_child,
    .sub_menu_child_item{
        background-color: {{ $text_color_menu }};
    }
    .active_menu_color .tilte_item_menu {
        border-radius: 10px;
        background: {{ $background_menu }};
    }
    .active_menu_color .menu--icon {
        background-color: {{ $text_color_menu_active }};
    }
    .active_menu_color .tilte_item_menu span {
        color: {{ $text_color_menu_active }};
    }
    .w_50 .active_menu_color {
        border-radius: 10px;
        background: {{ $background_menu . ' !important' }};
    }
    .title_time a {
        font-size: 15px;
    }
    .total_time_learn .title_time strong {
        padding: 2px 8px;
        border-radius: 10px;
        color: white !important;
        background: {{ $background_button }};
    }
    .total_time_learn strong .time {
        color: {{ $background_button . ' !important' }};
    }
    .all_item_menu_frontend  .menu_child {
        width: 240px;
        background: {{ $background_menu_child }};
    }
</style>
<div class="menu_left_frontend display_menu_left_frontend {{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'w_50' : '' }}">
    <div class="button_wrapper_menu display_menu_left_frontend">
        <button class="btn btn_vertical_menu_frontend" id="btn_3_gach_left_menu">
            <i class="uil uil-bars collapse_menu--icon"></i>
            <span class="collapse_menu--label"></span>
        </button>
        @if (userCan('user') || \App\Models\Permission::isUnitManager() )
            <input type="text" name="search_user" class="search_user border pl-1 pr-1 pt-2 pb-2 rounded form_search_user" style="width:60%; display:none; font-size: x-small;" placeholder="Nhập MSNV / Họ tên"/>
            <button type="button" class="btn search_user show_search_user" id="btn_search_user" style="float: right;margin-top: 9px;margin-right: 3px;">
                <i class="fas fa-search"></i>
            </button>
        @endif
    </div>
    <div class="wrapped_menu">
        <ul class="all_item_menu_frontend {{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'w_500' : '' }}">
            <li id="menu_home"
                class="{{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'w_50' : '' }} menu--item menu--item__has_sub_menu @if ($tabs == '') active_menu_item active_menu_color @endif
            ">
                <a href="{{ route('frontend.home_after_login') }}" class="menu--link mb-0">
                    <div class="menu--icon icon_menu_parent"
                        style="-webkit-mask: url({{ asset('images/home-page.png') }}) no-repeat;
                        mask: url({{ asset('images/home-page.png') }}) no-repeat;
                        -webkit-mask-size: 20px 20px;">
                    </div>
                    <div class="tilte_item_menu {{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'd_none' : '' }}">
                        <span class="menu--label">{{ trans('lamenu.home_page') }}</span>
                    </div>
                </a>
            </li>
            {{-- Bản tin đào tạo --}}
            @if (!empty($menuSetting) && in_array('menu_news', $menuSetting) || empty($menuSetting))
                <li id="menu_news"
                    class="{{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'w_50' : '' }} menu--item menu--item__has_sub_menu @if ($tabs == 'news-react') active_menu_item  @endif
                ">
                    <a href="{{ route('news_react') }}" class="menu--link mb-0">
                        <div class="menu--icon icon_menu_parent"
                            style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-3.svg') }}) no-repeat;
                            mask: url({{ asset('images/svg-frontend/svgexport-3.svg') }}) no-repeat;
                            -webkit-mask-size: 20px 20px;">
                        </div>
                        <div class="tilte_item_menu {{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'd_none' : '' }}">
                            <span class="menu--label">{{ trans('lamenu.training_news') }}</span>
                        </div>
                    </a>
                </li>
            @endif

            {{--  Thư viện Salekit  --}}
            <li id="menu_salekit"
                class="{{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'w_50' : '' }} menu--item menu--item__has_sub_menu @if ($tabs == 'saleskit') active_menu_item active_menu_color @endif
            ">
                <a href="{{ route('module.frontend.saleskit.salekit') }}" class="menu--link mb-0">
                    <div class="menu--icon icon_menu_parent"
                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-15.svg') }}) no-repeat;
                        mask: url({{ asset('images/svg-frontend/svgexport-15.svg') }}) no-repeat;
                        -webkit-mask-size: 20px 20px;">
                    </div>
                    <div class="tilte_item_menu {{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'd_none' : '' }}">
                        <span class="menu--label">Sales Kit</span>
                    </div>
                </a>
            </li>

            {{-- KHÓA HỌC --}}
            @if (!empty($menuSetting) && (in_array('course_3', $menuSetting) || in_array('course_1', $menuSetting) || in_array('course_2', $menuSetting)) || empty($menuSetting))
                <div class="warraped_menu_item">
                    <li class="{{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'w_50' : '' }} menu--item menu--item__has_sub_menu" id="menu_2" onclick="showMenuChild('menu_2')">
                        <label class="menu--link mb-0">
                            <div class="menu--icon icon_menu_parent"
                                style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-4.svg') }}) no-repeat;
                                mask: url({{ asset('images/svg-frontend/svgexport-4.svg') }}) no-repeat;
                                -webkit-mask-size: 20px 20px;">
                            </div>
                            <div class="tilte_item_menu {{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'd_none' : '' }}">
                                <span class="menu--label">{{ trans('lamenu.course') }}</span>
                                <i class="fa fa-chevron-down icon_menu_parent" id="icon_parent_menu_2"></i>
                            </div>
                        </label>
                    </li>
                    <ul class="menu_child child_menu_2" id="child_menu_2" data-id="2">
                        @if (!empty($menuSetting) && in_array('course_4', $menuSetting) || empty($menuSetting))
                            <li class="menu_child--item @if ($tabs == 'all-course' && $tab_3 == 4) active_menu_child  @endif">
                                <a href="{{ route('frontend.all_course',['type' => 4]) }}" class="menu_child--link ">
                                    <div class="menu_icon_child mr-2"
                                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-5.svg') }}) no-repeat;
                                        mask: url({{ asset('images/svg-frontend/svgexport-5.svg') }}) no-repeat;
                                        -webkit-mask-size: 20px 20px;">
                                    </div>
                                    <span>Khóa học đánh dấu
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (!empty($menuSetting) && in_array('course_3', $menuSetting) || empty($menuSetting))
                            <li class="menu_child--item @if ($tabs == 'all-course' && $tab_3 == 3) active_menu_child  @endif">
                                <a href="{{ route('frontend.all_course',['type' => 3]) }}" class="menu_child--link ">
                                    <div class="menu_icon_child mr-2"
                                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-5.svg') }}) no-repeat;
                                        mask: url({{ asset('images/svg-frontend/svgexport-5.svg') }}) no-repeat;
                                        -webkit-mask-size: 20px 20px;">
                                    </div>
                                    <span>{{ trans('latraining.my_course') }}
                                        <span class="{{ $count_course_register>0 ? 'color_num' : '' }}">
                                            ({{ $count_course_register }})
                                        </span>
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (!empty($menuSetting) && in_array('course_1', $menuSetting) || empty($menuSetting))
                            <li class="menu_child--item @if ($tabs == 'all-course' && $tab_3 == 1) active_menu_child  @endif" onmouseover="hoverSubmenu(1)" onmouseout="outHover(1)" id="1">
                                <label class="menu_child--link">
                                    <a href="{{ route('frontend.all_course',['type' => 1]) }}" class="menu_child--link">
                                        <div class="menu_icon_child mr-2"
                                            style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-6.svg') }}) no-repeat;
                                            mask: url({{ asset('images/svg-frontend/svgexport-6.svg') }}) no-repeat;
                                            -webkit-mask-size: 20px 20px;">
                                        </div>
                                        <span>{{ trans('laother.register') }} KH Online</span>
                                    </a>
                                </label>
                                <i class="fa fa-chevron-right"></i>

                                <ul class="sub_menu_child">
                                    @foreach ($trainingPrograms as $trainingProgram)
                                        <li class="sub_menu--item item_online_training">
                                            <a href="{{ route('frontend.all_course',['type' => 1]).'?trainingProgramId='. $trainingProgram->id }}" class="sub_menu--link">
                                                <div class="sub_menu_child_item mr-2"
                                                    style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-7.svg') }}) no-repeat;
                                                    mask: url({{ asset('images/svg-frontend/svgexport-7.svg') }}) no-repeat;
                                                    -webkit-mask-size: 20px 20px;">
                                                </div>
                                                <span>{{ $trainingProgram->name }}
                                                    <span class="{{ $trainingProgram->countCourse > 0 ? 'color_num' : '' }}">
                                                        ({{ $trainingProgram->countCourse }})
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                        @if (!empty($menuSetting) && in_array('course_2', $menuSetting) || empty($menuSetting))
                            <li class="menu_child--item @if ($tabs == 'all-course' && $tab_3 == 2) active_menu_child  @endif">
                                <a href="{{ route('frontend.all_course',['type' => 2]) }}" class="menu_child--link ">
                                    <div class="menu_icon_child mr-2"
                                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-8.svg') }}) no-repeat;
                                        mask: url({{ asset('images/svg-frontend/svgexport-8.svg') }}) no-repeat;
                                        -webkit-mask-size: 20px 20px;">
                                    </div>
                                    <span>{{ trans('laother.register') }} KH Offline
                                        <span class="{{ $count_course_offline>0 ? 'color_num' : '' }}">
                                            ({{ $count_course_offline }})
                                        </span>
                                    </span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif

            {{-- CỘNG TÁC --}}
            @if (!empty($menuSetting) && !empty(array_intersect($menuSetting, $itemMenu3)) || empty($menuSetting))
                <div class="warraped_menu_item">
                    <li class="{{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'w_50' : '' }} menu--item menu--item__has_sub_menu" id="menu_3" onclick="showMenuChild('menu_3')">
                        <label class="menu--link mb-0">
                            <div class="menu--icon icon_menu_parent"
                                style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-9.svg') }}) no-repeat;
                                mask: url({{ asset('images/svg-frontend/svgexport-9.svg') }}) no-repeat;
                                -webkit-mask-size: 20px 20px;">
                            </div>
                            <div class="tilte_item_menu {{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'd_none' : '' }}">
                                <span class="menu--label">{{ trans('lamenu.collaboration') }}</span>
                                <i class="fa fa-chevron-down icon_menu_parent" id="icon_parent_menu_3"></i>
                            </div>
                        </label>
                    </li>
                    <ul class="menu_child child_menu_3" id="child_menu_3" data-id="3">
                        @if (!empty($menuSetting) && in_array('daily_training', $menuSetting) || empty($menuSetting))
                            <li class="menu_child--item daily-training-react">
                                <a href="{{ route('daily_training_react',['type' => 0]) }}" class="menu_child--link">
                                    <div class="menu_icon_child mr-2"
                                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-10.svg') }}) no-repeat;
                                        mask: url({{ asset('images/svg-frontend/svgexport-10.svg') }}) no-repeat;
                                        -webkit-mask-size: 20px 20px;">
                                    </div>
                                    <span>Video sharing
                                        <span class="{{ $count_daily_video>0 ? 'color_num' : '' }}">
                                            ({{ $count_daily_video }})
                                        </span>
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (!empty($menuSetting) && in_array('survey', $menuSetting) || empty($menuSetting))
                            <li class="menu_child--item survey-react">
                                <a href="{{ route('survey_react') }}" class="menu_child--link ">
                                    <div class="menu_icon_child mr-2"
                                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-11.svg') }}) no-repeat;
                                        mask: url({{ asset('images/svg-frontend/svgexport-11.svg') }}) no-repeat;
                                        -webkit-mask-size: 20px 20px;">
                                    </div>
                                    <span>{{ trans('lamenu.survey') }}
                                        <span class="{{ $count_survey>0 ? 'color_num' : '' }}">
                                            ({{ $count_survey }})
                                        </span>
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (!empty($menuSetting) && in_array('rating_level', $menuSetting) || empty($menuSetting))
                            <li class="menu_child--item rating-level">
                                <a href="{{ route('module.rating_level') }}" class="menu_child--link ">
                                    <div class="menu_icon_child mr-2"
                                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-12.svg') }}) no-repeat;
                                        mask: url({{ asset('images/svg-frontend/svgexport-12.svg') }}) no-repeat;
                                        -webkit-mask-size: 20px 20px;">
                                    </div>
                                    <span>{{ trans('lamenu.kirkpatrick_model') }}
                                        <span class="{{ $count_rating_level>0 ? 'color_num' : '' }}">
                                            ({{ $count_rating_level }})
                                        </span>
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (!empty($menuSetting) && !empty(array_intersect($menuSetting, $itemLibrary)) || empty($menuSetting))
                            <li class="menu_child--item library" onmouseover="hoverSubmenu(4)" onmouseout="outHover(4)" id="4">
                                <label class="menu_child--link">
                                    <div class="menu_icon_child mr-2"
                                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-13.svg') }}) no-repeat;
                                        mask: url({{ asset('images/svg-frontend/svgexport-13.svg') }}) no-repeat;
                                        -webkit-mask-size: 20px 20px;">
                                    </div>
                                    <span>{{ trans('lamenu.library') }}</span>
                                </label>
                                <i class="fa fa-chevron-right"></i>
                                <ul class="sub_menu_child">
                                    @if (!empty($menuSetting) && in_array('book', $menuSetting) || empty($menuSetting))
                                        <li class="sub_menu--item">
                                            <a href="{{ route('library',['type' => 1]) }}" class="sub_menu--link">
                                                <div class="sub_menu_child_item mr-2"
                                                    style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-14.svg') }}) no-repeat;
                                                    mask: url({{ asset('images/svg-frontend/svgexport-14.svg') }}) no-repeat;
                                                    -webkit-mask-size: 20px 20px;">
                                                </div>
                                                <span>{{ trans('lalibrary.book') }}
                                                    <span class="{{ $count_book>0 ? 'color_num' : '' }}">
                                                        ({{ $count_book }})
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                    @endif
                                    @if (!empty($menuSetting) && in_array('ebook', $menuSetting) || empty($menuSetting))
                                        <li class="sub_menu--item">
                                            <a href="{{ route('library',['type' => 2]) }}" class="sub_menu--link">
                                                <div class="sub_menu_child_item mr-2"
                                                    style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-15.svg') }}) no-repeat;
                                                    mask: url({{ asset('images/svg-frontend/svgexport-15.svg') }}) no-repeat;
                                                    -webkit-mask-size: 20px 20px;">
                                                </div>
                                                <span>{{ trans('lamenu.ebook') }}
                                                    <span class="{{ $count_ebook>0 ? 'color_num' : '' }}">
                                                        ({{ $count_ebook }})
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                    @endif
                                    @if (!empty($menuSetting) && in_array('document', $menuSetting) || empty($menuSetting))
                                        <li class="sub_menu--item">
                                            <a href="{{ route('library',['type' => 3]) }}" class="sub_menu--link">
                                                <div class="sub_menu_child_item mr-2"
                                                    style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-16.svg') }}) no-repeat;
                                                    mask: url({{ asset('images/svg-frontend/svgexport-16.svg') }}) no-repeat;
                                                    -webkit-mask-size: 20px 20px;">
                                                </div>
                                                <span>{{ trans('lamenu.document') }}
                                                    <span class="{{ $count_document>0 ? 'color_num' : '' }}">
                                                        ({{ $count_document }})
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                    @endif
                                    @if (!empty($menuSetting) && in_array('video', $menuSetting) || empty($menuSetting))
                                        <li class="sub_menu--item">
                                            <a href="{{ route('library',['type' => 4]) }}" class="sub_menu--link">
                                                <div class="sub_menu_child_item mr-2"
                                                    style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-17.svg') }}) no-repeat;
                                                    mask: url({{ asset('images/svg-frontend/svgexport-17.svg') }}) no-repeat;
                                                    -webkit-mask-size: 20px 20px;">
                                                </div>
                                                <span>Video
                                                    <span class="{{ $count_video>0 ? 'color_num' : '' }}">
                                                        ({{ $count_video }})
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                    @endif
                                    @if (!empty($menuSetting) && in_array('audio', $menuSetting) || empty($menuSetting))
                                        <li class="sub_menu--item">
                                            <a href="{{ route('library',['type' => 5]) }}" class="sub_menu--link">
                                                <div class="sub_menu_child_item mr-2"
                                                    style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-18.svg') }}) no-repeat;
                                                    mask: url({{ asset('images/svg-frontend/svgexport-18.svg') }}) no-repeat;
                                                    -webkit-mask-size: 20px 20px;">
                                                </div>
                                                <span>{{ trans('lamenu.audio') }}
                                                    <span class="{{ $count_audiobook>0 ? 'color_num' : '' }}">
                                                        ({{ $count_audiobook }})
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        @if (!empty($menuSetting) && in_array('forums', $menuSetting) || empty($menuSetting))
                            <li class="menu_child--item forums-react">
                                <a href="{{ route('forums_react') }}" class="menu_child--link">
                                    <div class="menu_icon_child mr-2"
                                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-19.svg') }}) no-repeat;
                                        mask: url({{ asset('images/svg-frontend/svgexport-19.svg') }}) no-repeat;
                                        -webkit-mask-size: 20px 20px;">
                                    </div>
                                    <span>{{ trans('lamenu.forum') }}
                                        <span class="{{ $count_forum>0 ? 'color_num' : '' }}">
                                            ({{ $count_forum }})
                                        </span>
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (!empty($menuSetting) && in_array('suggest', $menuSetting) || empty($menuSetting))
                            <li class="menu_child--item suggest-react">
                                <a href="{{ route('suggest_react') }}" class="menu_child--link">
                                    <div class="menu_icon_child mr-2"
                                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-20.svg') }}) no-repeat;
                                        mask: url({{ asset('images/svg-frontend/svgexport-20.svg') }}) no-repeat;
                                        -webkit-mask-size: 20px 20px;">
                                    </div>
                                    <span>{{ trans('lamenu.suggestion') }}
                                        <span class="{{ $count_suggest>0 ? 'color_num' : '' }}">
                                            ({{ $count_suggest }})
                                        </span>
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (!empty($menuSetting) && in_array('topic_situation', $menuSetting) || empty($menuSetting))
                            <li class="menu_child--item topic-situation-react">
                                <a href="{{ route('topic_situation_react') }}" class="menu_child--link">
                                    <div class="menu_icon_child mr-2"
                                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-21.svg') }}) no-repeat;
                                        mask: url({{ asset('images/svg-frontend/svgexport-21.svg') }}) no-repeat;
                                        -webkit-mask-size: 20px 20px;">
                                    </div>
                                    <span>{{ trans('lamenu.situations_proccessing') }}
                                        <span class="{{ $count_topic_situation>0 ? 'color_num' : '' }}">
                                            ({{ $count_topic_situation }})
                                        </span>
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (!empty($menuSetting) && in_array('coaching_teacher', $menuSetting) || empty($menuSetting))
                            <li class="menu_child--item coaching-teacher">
                                <a href="{{ route('module.coaching.frontend') }}" class="menu_child--link">
                                    <div class="menu_icon_child mr-2"
                                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-22.svg') }}) no-repeat;
                                        mask: url({{ asset('images/svg-frontend/svgexport-22.svg') }}) no-repeat;
                                        -webkit-mask-size: 20px 20px;">
                                    </div>
                                    <span>{{ trans('lamenu.coaching_teacher') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                        {{-- <li class="menu_child--item">
                            <a href="{{ route('social_network') }}" class="menu_child--link">
                                <svg class="menu_icon_child mr-2" xmlns="http://www.w3.org/2000/svg" id="Layer_2" height="512" viewBox="0 0 24 24" width="512" data-name="Layer 2"><path d="m18 15a3.989 3.989 0 0 0 -3.222 1.65l-5.015-3.343a3.722 3.722 0 0 0 0-2.614l5.015-3.343a3.985 3.985 0 1 0 -.778-2.35 3.883 3.883 0 0 0 .044.435l-5.391 3.594a4 4 0 1 0 0 5.942l5.391 3.594a3.883 3.883 0 0 0 -.044.435 4 4 0 1 0 4-4zm0-12a2 2 0 1 1 -2 2 2 2 0 0 1 2-2zm-12 11a2 2 0 1 1 2-2 2 2 0 0 1 -2 2zm12 7a2 2 0 1 1 2-2 2 2 0 0 1 -2 2z"/></svg>
                                <span>@lang('lamenu.social_network') (build)</span>
                            </a>
                        </li>
                        <li class="menu_child--item">
                            <a href="{{ config('app.social_url') }}/login_el.php?user_social={{ session()->get('user_social') }}" class="menu_child--link " target="_bank">
                                <svg class="menu_icon_child mr-2" xmlns="http://www.w3.org/2000/svg" id="Layer_2" height="512" viewBox="0 0 24 24" width="512" data-name="Layer 2"><path d="m18 15a3.989 3.989 0 0 0 -3.222 1.65l-5.015-3.343a3.722 3.722 0 0 0 0-2.614l5.015-3.343a3.985 3.985 0 1 0 -.778-2.35 3.883 3.883 0 0 0 .044.435l-5.391 3.594a4 4 0 1 0 0 5.942l5.391 3.594a3.883 3.883 0 0 0 -.044.435 4 4 0 1 0 4-4zm0-12a2 2 0 1 1 -2 2 2 2 0 0 1 2-2zm-12 11a2 2 0 1 1 2-2 2 2 0 0 1 -2 2zm12 7a2 2 0 1 1 2-2 2 2 0 0 1 -2 2z"/></svg>
                                <span>@lang('lamenu.social_network')</span>
                            </a>
                        </li>  --}}
                    </ul>
                </div>
            @endif

            {{-- ĐIỄM TÍCH LŨY --}}
            @if (!empty($menuSetting) && !empty(array_intersect($menuSetting, $itemMenu4)) || empty($menuSetting))
                <div class="warraped_menu_item">
                    <li class="{{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'w_50' : '' }} menu--item menu--item__has_sub_menu" id="menu_4" onclick="showMenuChild('menu_4')">
                        <label class="menu--link mb-0">
                            <div class="menu--icon icon_menu_parent"
                                style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-23.svg') }}) no-repeat;
                                mask: url({{ asset('images/svg-frontend/svgexport-23.svg') }}) no-repeat;
                                -webkit-mask-size: 20px 20px;">
                            </div>
                            <div class="tilte_item_menu {{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'd_none' : '' }}">
                                <span class="menu--label">{{ trans('lamenu.accumulated_points') }}</span>
                                <i class="fa fa-chevron-down icon_menu_parent" id="icon_parent_menu_4"></i>
                            </div>
                        </label>
                    </li>
                    <ul class="menu_child child_menu_4" id="child_menu_4" data-id="4">
                        @if (!empty($menuSetting) && in_array('promotion', $menuSetting) || empty($menuSetting))
                            <li class="menu_child--item promotion-react">
                                <a href="{{ route('promotion_react') }}" class="menu_child--link ">
                                    <div class="menu_icon_child mr-2"
                                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-24.svg') }}) no-repeat;
                                        mask: url({{ asset('images/svg-frontend/svgexport-24.svg') }}) no-repeat;
                                        -webkit-mask-size: 20px 20px;">
                                    </div>
                                    <span>{{ trans('lamenu.study_promotion_program') }}
                                        <span class="{{ $count_promotion>0 ? 'color_num' : '' }}">
                                            ({{ $count_promotion }})
                                        </span>
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (!empty($menuSetting) && in_array('usermedal', $menuSetting) || empty($menuSetting))
                            <li class="menu_child--item usermedal">
                                <a href="{{ route('module.frontend.usermedal.list') }}" class="menu_child--link">
                                    <div class="menu_icon_child mr-2"
                                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-25.svg') }}) no-repeat;
                                        mask: url({{ asset('images/svg-frontend/svgexport-25.svg') }}) no-repeat;
                                        -webkit-mask-size: 20px 20px;">
                                    </div>
                                    <span>{{ trans('lamenu.emulation_program') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (!empty($menuSetting) && in_array('usermedal_history', $menuSetting) || empty($menuSetting))
                            <li class="menu_child--item usermedal-history">
                                <a href="{{ route('module.frontend.usermedal.history') }}" class="menu_child--link">
                                    <div class="menu_icon_child mr-2"
                                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-26.svg') }}) no-repeat;
                                        mask: url({{ asset('images/svg-frontend/svgexport-26.svg') }}) no-repeat;
                                        -webkit-mask-size: 20px 20px;">
                                    </div>
                                    <span>{{ trans('latraining.medal_history') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (!empty($menuSetting) && in_array('user_point_history', $menuSetting) || empty($menuSetting))
                            <li class="menu_child--item userpoint">
                                <a href="{{ route('module.frontend.userpoint.history') }}" class="menu_child--link">
                                    <div class="menu_icon_child mr-2"
                                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-27.svg') }}) no-repeat;
                                        mask: url({{ asset('images/svg-frontend/svgexport-27.svg') }}) no-repeat;
                                        -webkit-mask-size: 20px 20px;">
                                    </div>
                                    <span>{{ trans('latraining.get_point_history') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (!empty($menuSetting) && in_array('my_promotion', $menuSetting) || empty($menuSetting))
                            <li class="menu_child--item">
                                <a href="{{ route('module.frontend.user.my_promotion') }}" class="menu_child--link">
                                    <div class="menu_icon_child mr-2"
                                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-28.svg') }}) no-repeat;
                                        mask: url({{ asset('images/svg-frontend/svgexport-28.svg') }}) no-repeat;
                                        -webkit-mask-size: 20px 20px;">
                                    </div>
                                    <span>{{ trans('lamenu.purchase_history') }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif

            {{-- KẾ HOẠCH CỦA TÔI --}}
            <div class="warraped_menu_item">
                <li class="{{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'w_50' : '' }} menu--item menu--item__has_sub_menu" id="menu_1" onclick="showMenuChild('menu_1')">
                    <label class="menu--link mb-0">
                        <div class="menu--icon icon_menu_parent"
                            style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-29.svg') }}) no-repeat;
                            mask: url({{ asset('images/svg-frontend/svgexport-29.svg') }}) no-repeat;
                            -webkit-mask-size: 20px 20px;">
                        </div>
                        <div class="tilte_item_menu {{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'd_none' : '' }}">
                            <span class="menu--label">{{ trans('lamenu.my_plan') }}</span>
                            <i class="fa fa-chevron-down icon_menu_parent" id="icon_parent_menu_1"></i>
                        </div>
                    </label>
                </li>
                <ul class="menu_child child_menu_1" id="child_menu_1" data-id="1">
                    @if (!empty($menuSetting) && in_array('info', $menuSetting) || empty($menuSetting))
                        <li class="menu_child--item @if ($tabs == 'user' && $tab_3 == 'info') active_menu_child  @endif">
                            <a href="{{ route('module.frontend.user.info') }}" class="menu_child--link">
                                <div class="menu_icon_child mr-2"
                                    style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-30.svg') }}) no-repeat;
                                    mask: url({{ asset('images/svg-frontend/svgexport-30.svg') }}) no-repeat;
                                    -webkit-mask-size: 20px 20px;">
                                </div>
                                <span>{{ trans('latraining.info') }}</span>
                            </a>
                        </li>
                    @endif
                    <li class="menu_child--item @if ($tabs == 'dashboard') active_menu_child  @endif">
                        <a href="{{ route('frontend.home') }}" class="menu_child--link">
                            <div class="menu_icon_child mr-2"
                                style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-31.svg') }}) no-repeat;
                                mask: url({{ asset('images/svg-frontend/svgexport-31.svg') }}) no-repeat;
                                -webkit-mask-size: 20px 20px;">
                            </div>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    @if (!empty($menuSetting) && in_array('dashboard_by_user', $menuSetting) || empty($menuSetting))
                        <li class="menu_child--item dashboard_by_user">
                            <a href="{{ route('frontend.dashboard_by_user') }}" class="menu_child--link">
                                <div class="menu_icon_child mr-2"
                                    style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-32.svg') }}) no-repeat;
                                    mask: url({{ asset('images/svg-frontend/svgexport-32.svg') }}) no-repeat;
                                    -webkit-mask-size: 20px 20px;">
                                </div>
                                <span>{{ trans('lamenu.summary') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (!empty($menuSetting) && in_array('calendar', $menuSetting) || empty($menuSetting))
                        <li class="menu_child--item calendar">
                            <a href="{{ route('frontend.calendar') }}" class="menu_child--link">
                                <div class="menu_icon_child mr-2"
                                    style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-33.svg') }}) no-repeat;
                                    mask: url({{ asset('images/svg-frontend/svgexport-33.svg') }}) no-repeat;
                                    -webkit-mask-size: 20px 20px;">
                                </div>
                                <span>{{ trans('lamenu.training_calendar') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (!empty($menuSetting) && in_array('quiz', $menuSetting) || empty($menuSetting))
                        <li class="menu_child--item quiz-react">
                            <a href="{{ route('quiz_react') }}" class="menu_child--link">
                                <div class="menu_icon_child mr-2"
                                    style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-34.svg') }}) no-repeat;
                                    mask: url({{ asset('images/svg-frontend/svgexport-34.svg') }}) no-repeat;
                                    -webkit-mask-size: 20px 20px;">
                                </div>
                                <span>{{ trans('lamenu.quiz_manager') }} <span class="{{ $count_quiz>0 ? 'color_num' : '' }}">({{ $count_quiz }})</span></span>
                            </a>
                        </li>
                    @endif
                    @if (!empty($menuSetting) && in_array('note', $menuSetting) || empty($menuSetting))
                        <li class="menu_child--item note-react">
                            <a href="{{ route('note_react') }}" class="menu_child--link">
                                <div class="menu_icon_child mr-2"
                                    style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-35.svg') }}) no-repeat;
                                    mask: url({{ asset('images/svg-frontend/svgexport-35.svg') }}) no-repeat;
                                    -webkit-mask-size: 20px 20px;">
                                </div>
                                <span>{{ trans('latraining.my_note') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (!empty($menuSetting) && in_array('interaction_history', $menuSetting) || empty($menuSetting))
                        <li class="menu_child--item interaction_history">
                            <a href="{{ route('frontend.interaction_history') }}" class="menu_child--link">
                                <div class="menu_icon_child mr-2"
                                    style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-36.svg') }}) no-repeat;
                                    mask: url({{ asset('images/svg-frontend/svgexport-36.svg') }}) no-repeat;
                                    -webkit-mask-size: 20px 20px;">
                                </div>
                                <span>{{ trans('lamenu.interaction_history') }}</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            {{-- TRỢ GIÚP --}}
            @if (!empty($menuSetting) && !empty(array_intersect($menuSetting, $itemMenu5)) || empty($menuSetting))
                <div class="warraped_menu_item">
                    <li class="{{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'w_50' : '' }} menu--item menu--item__has_sub_menu" id="menu_5" onclick="showMenuChild('menu_5')">
                        <label class="menu--link mb-0">
                            <div class="menu--icon icon_menu_parent"
                                style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-37.svg') }}) no-repeat;
                                mask: url({{ asset('images/svg-frontend/svgexport-37.svg') }}) no-repeat;
                                -webkit-mask-size: 20px 20px;">
                            </div>
                            <div class="tilte_item_menu {{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'd_none' : '' }}">
                                <span class="menu--label">{{ trans('lamenu.support') }}</span>
                                <i class="fa fa-chevron-down icon_menu_parent" id="icon_parent_menu_5"></i>
                            </div>
                        </label>
                    </li>
                    <ul class="menu_child child_menu_5" id="child_menu_5" data-id="5">
                        @if (!empty($menuSetting) && in_array('pdf', $menuSetting) || empty($menuSetting))
                            <li class="menu_child--item guide-react">
                                <a href="{{ route('guide_react',['type' => 1]) }}" class="menu_child--link">
                                    <div class="menu_icon_child mr-2"
                                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-39.svg') }}) no-repeat;
                                        mask: url({{ asset('images/svg-frontend/svgexport-39.svg') }}) no-repeat;
                                        -webkit-mask-size: 20px 20px;">
                                    </div>
                                    <span>{{ trans('lamenu.guide') }}
                                        <span class="{{ $count_guide_pdf>0 ? 'color_num' : '' }}">
                                            ({{ $count_guide_pdf }})
                                        </span>
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (!empty($menuSetting) && in_array('faq', $menuSetting) || empty($menuSetting))
                            <li class="menu_child--item faq-react">
                                <a href="{{ route('faq_react') }}" class="menu_child--link ">
                                    <div class="menu_icon_child mr-2"
                                        style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-41.svg') }}) no-repeat;
                                        mask: url({{ asset('images/svg-frontend/svgexport-41.svg') }}) no-repeat;
                                        -webkit-mask-size: 20px 20px;">
                                    </div>
                                    <span>{{ trans('lamenu.faq') }}
                                        <span class="{{ $count_faq>0 ? 'color_num' : '' }}">
                                            ({{ $count_faq }})
                                        </span>
                                    </span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif
        </ul>
        <div class="total_time_learn">
            <hr>
            <div class="row m-0">
                <div class="col-3 pr-0 icon_time">
                    <img src="{{ asset('images/clock.png') }}" alt="" width="30px">
                </div>
                <div class="col-9 pl-0">
                    <h4 class="mb-1 title_time">
                        <a target="blank" href="{{ route('module.category.kpi_tempalte.show_kpi') }}">
                            <strong>{{ trans('lamenu.hours_learned_total') }}</strong>
                        </a>
                    </h4>
                    <p class="total_time mt-2">
                        <a href="{{ route('frontend.detail_total_time_user') }}">
                            <span class="ml-2 cursor_pointer">
                                <strong><span class="time">{{ $totalTimeLearnInYear }}</span> {{ trans('lamenu.hours_per_year') }}</strong>
                            </span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="tabs" value="{{ $tabs }}">
<input type="hidden" id="search" value="{{ route('frontend.search_user') }}">
<input type="hidden" id="close_open" value="{{ route('frontend.close_open_menu') }}">
<input type="hidden" id="kpi_template" value="{{ route('module.category.kpi_tempalte.show_kpi') }}">
