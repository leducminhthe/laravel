<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" class="{{ session()->exists('nightMode') && session()->get('nightMode') == 1 ? 'night-mode' : '' }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, shrink-to-fit=9"
    />
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="turbolinks-cache-control" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, public">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('page_title')</title>

    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{{ image_file(\App\Models\Config::getFavicon()) }}">
    <script>
        window._app_env_ = '{{ config('app.env')}}';
        window.user = {{ @profile()->user_id }};
        window._asset = '{{ asset('') }}';
    </script>
    <script type="text/javascript">
        var base_url = '{{ url('/') }}';
    </script>
    <!-- Stylesheets -->
    <link href="{{ asset('css/font_roboto_400_700_500.css') }}" rel="stylesheet">
    <link href="{{ mix('css/theme.css') }}" rel="stylesheet">
    <script src="{{ mix('js/theme.js') }}" type="text/javascript"></script>

    @livewireStyles
    @yield('header')
    @php
        $get_color_button = $color_button;
        $get_color_link = \App\Models\SettingColor::where('name','color_link')->first();

        $color_link = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_link->text;
        $hover_color_link = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_link->hover_text;

        $color_text_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_button->text;
        $color_hover_text_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_button->hover_text;
        $background_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_button->background;
        $hover_background_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_button->hover_background;

        $user_type = \Modules\Quiz\Entities\Quiz::getUserType();
        $tabs_course = Request::segment(2);
        $tabs = Request::segment(1);

        $color_title = get_config('color_title') ?? "#1b4486";

        $check_tab_course = false;
        if($tabs_course == 'detail-online' || ($tabs == 'offline' && in_array($tabs_course, ['detail', 'detail-new']))){
            $check_tab_course = true;
        }

        $get_color_menu = \App\Models\SettingColor::where('name','color_menu')->first();
        $text_color_menu_active = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_menu->active;
        $background_menu = $get_color_menu->background;

        $bg_menu = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : (get_config('bg_menu') ?? "#fff");
    @endphp
    @if ($check_tab_course)
        <link rel="stylesheet" href="{{ myasset('css/detail_course.css') }}">
    @endif
    {{-- MÀU CHO NÚT NHẤN --}}
    <style type="text/css">
        body {
            background: #F3F6F9 !important
        }
        .body_frontend a {
            color: {{ $color_link }};
        }
        .body_frontend a:hover {
            color: {{ $hover_color_link . ' !important' }};
        }
        #btn_3_gach_left_menu{
            color:  {{ $background_button }};
            border: none !important;
        }
        #btn_3_gach_left_menu:hover i {
            color:  {{ $hover_background_button }};
        }
        .menu_left_frontend #btn_search_user{
            background: {{ $background_button }};
            border-color: {{ $background_button }};
        }
        .menu_left_frontend #btn_search_user:hover{
            background: {{ $hover_background_button }};
        }
        #collapse_menu,
        .search_button_vertical_frontend,
        .menu_bottom,
        .body_content_detail .menu_course,
        .navbar_news .dropdown-submenu a {
            background: {{ $background_button . ' !important' }};
        }
        .menu_bottom:hover,
        .nav-pills .nav-link.active,
        .body_content_detail .nav-pills .nav-link:hover {
            color: white;
            background: {{ $hover_background_button }};
        }
        .body_content_detail .btn {
            color: white;
            background: {{ $background_button }};
        }
        .body_content_detail .icon_card {
            color: {{ $background_button }};
        }
        .body_content_detail .wrapped_navigate .prev_activity:hover,
        .body_content_detail .wrapped_navigate .next_activity:hover {
            color: {{ $hover_background_button }};
        }

        #app-modal .btn,
        .modal .btn,
        .body_frontend .content_frontend .btn {
            border-radius: 5px;
            color: {{ $color_text_button }};
            background: {{ $background_button }};
            margin-left: 3px;
            border: none;
        }
        #app-modal .btn:hover,
        .modal .btn:hover,
        .body_frontend .content_frontend .btn:hover {
            border-radius: 5px;
            color: {{ $color_hover_text_button }};
            background: {{ $hover_background_button }};
        }

        #all_course .progress2 .progress-bar {
            background: {{ $background_button }};
        }

        #all_course .list_course .btn_endcourse,
        #all_course .list_course .btn_complete{
            color: #a0a0a0 !important;
            border: 1px solid #a0a0a0;
            background-color: white;
        }

        #all_course .list_course .btn_gocourse{
            color: #74a9de !important;
            border: 1px solid #74a9de;
            background-color: white;
        }

        #all_course .list_course .btn_register{
            color: #adc63a !important;
            border: 1px solid #adc63a;
            background-color: white;
        }

        #all_course .list_course .btn_register:hover,
        #all_course .list_course .btn_gocourse:hover,
        #all_course .list_course .btn_endcourse:hover,
        #all_course .list_course .btn_complete:hover{
            text-decoration: underline !important;
        }

        #all_course .list_course .btn_register:hover{
            background-color: rgb(173, 198, 58, 0.2);
        }

        #all_course .list_course .btn_gocourse:hover{
            background-color: rgb(116, 169, 222, 0.2);
        }

        #all_course .list_course .btn_complete:hover,
        #all_course .list_course .btn_endcourse:hover{
            background-color: rgb(160, 160, 160, 0.2);
        }

        .bg_title {
            border-radius: 10px;
            background: {{ $color_title }};
        }

        .table > thead > tr > th {
            background: {{ $background_menu . ' !important' }};
            color: {{ $text_color_menu_active . ' !important' }} ;
        }

        .cursor_pointer{
            cursor: pointer;
        }

        .menu_left_frontend .wrapped_menu,
        .menu_left_frontend .sub_menu_child{
            background: {{ $bg_menu . ' !important' }};
        }
    </style>
</head>
<body>
    <input type="hidden" id="user-id" value="{{ @profile()->user_id }}">
    <!-- Body Start -->
    @if (!$check_tab_course)
        @include('layouts.top_menu')
        @include('layouts.top_banner')
        @include('layouts.left_menu')

        <div class="body_frontend wrapper _bg4586 {{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'wrapper__minify' : '' }}">
            <div class="content_frontend">
                @yield('content')
            </div>
            @if($user_type == 1 && !$check_tab_course)
                @include('layouts.menu_bottom',['get_color_button' => $get_color_button])
            @endif
        </div>
    @else
        <div class="{{ $tabs_course != 'detail-online' && 'mt-5' }}">
            @yield('content')
        </div>
    @endif

    @php
        $survey_popup = getSurveyPopup();
        $check_survey_popup = (session()->get('survey_popup_time') && session()->get('survey_popup_time')->format('Y-m-d H:i:s')) >= now()->format('Y-m-d H:i:s');
    @endphp

    @foreach ($survey_popup as $popup_key => $popup)
        <div class="modal fade modal-survey-popup">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="step js-steps-content" id="step{{ $popup->id }}">
                            <h4>{{ 'Bạn có muốn thực hiện khảo sát "'. $popup->name .'"' }}</h4>
                            <div class="d-flex">
                                <button type="button" class="btn" onclick="update_num_popup({{ $popup->id }})" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn" onclick="update_num_popup({{ $popup->id }})">
                                    <a href="{{ route('survey_react') }}" class="text-white">OK</a>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @include('layouts.modal_config_login')

    <div id="element_app"
        data-tabs = "{{ $tabs }}"
        data-check_navigate = "{{ $check_navigate }}"
        data-check_session_navigate = "{{ session()->has('close_experience_navigate') ? session()->get('close_experience_navigate') : '' }}"
        data-url_case_1 = '{{ route("module.frontend.user.my_career_roadmap") }}'
        data-url_case_2 = "{{ route('frontend.all_course',['type' => 3]) }}"
        data-url_case_3 = "{{ route('frontend.all_course',['type' => 3]) }}"
        data-url_case_4 = "{{ route('forums_react') }}"
        data-url_case_5 = "{{ route('frontend.all_course',['type' => 1]) }}"
        data-url_case_6 = "{{ route('news_react') }}"
        data-url_case_7 = "{{ route('module.frontend.user.info') }}"
        data-url_case_8 = "{{ route('library',['type' => 2]) }}"
        data-url_case_9 = "{{ route('daily_training_react',['type' => 0]) }}"
        data-save_experience_navigate = "{{ route('frontend.save_experience_navigate') }}"
        data-update_survey_popup = "{{ route('frontend.update_survey_popup') }}"
        data-check_survey_popup = "{{ $check_survey_popup }}"
        data-app_localce = '{{ \App::getLocale() }}'
    >
    </div>

    <!-- Body End -->
    <script src="{{ mix('js/theme2.js') }}" type="text/javascript"></script>
    @livewireScripts
{{--    <div id="app">--}}
{{--        <div id="mod-chat">--}}
{{--            <roomuser />--}}
{{--        </div>--}}
{{--    </div>--}}
    @yield('footer')
    <div id="app-modal"></div>
    {{-- <script src="{{ mix('js/app.js') }}" defer type="text/javascript"></script> --}}
</body>
</html>
