<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" class="{{ session()->exists('nightMode') && session()->get('nightMode') == 1 ? 'night-mode' : '' }}">
<head>
    @php
        $user_type = \Modules\Quiz\Entities\Quiz::getUserType();

        $get_color_button = $color_button;
        $get_color_link = \App\Models\SettingColor::where('name','color_link')->first();

        $color_link = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_link->text;
        $hover_color_link = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_link->hover_text;

        $color_text_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_button->text;
        $color_hover_text_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_button->hover_text;
        $background_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_button->background;
        $hover_background_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_button->hover_background;

        $get_color_menu = \App\Models\SettingColor::where('name','color_menu')->first();
        $text_color_menu_active = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_menu->active;
        $background_menu = $get_color_menu->background;

        $bg_menu = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : (get_config('bg_menu') ?? "#fff");
    @endphp

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
    <link href="{{ mix('css/reactjs.css') }}" rel="stylesheet">
    <script src="{{ mix('js/reactjs.js') }}" type="text/javascript"></script>
    <link href="{{ mix('css/appReact.css') }}" rel="stylesheet">

    @yield('header')

    {{-- MÀU CHO NÚT NHẤN --}}
    <style type="text/css">
        body {
            background: none !important;
        }
        .body_frontend a {
            color: {{ $color_link }};
        }
        .body_frontend a:hover {
            color: {{ $hover_color_link }};
        }
        #btn_3_gach_left_menu {
            color:  {{ $background_button }};
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
        .menu_new_insdie .nav-link:hover,
        .navbar_news .dropdown-submenu a,
        .bg-comment-suggest {
            background: {{ $background_button . ' !important' }};
        }

        .menu_new_insdie .navbar-brand:hover svg {
            fill: {{ $background_button }};
        }

        .menu_bottom:hover,
        .menu_new_insdie .dropdown-submenu:hover a{
            color: white;
            background: {{ $hover_background_button . ' !important' }};
        }

        .body_frontend .content_frontend .btn {
            border-radius: 5px;
            color: {{ $color_text_button }};
            background: {{ $background_button }};
            margin-left: 3px;
            border: none;
        }
        .body_frontend .content_frontend .btn:hover {
            border-radius: 5px;
            color: {{ $color_hover_text_button }};
            background: {{ $hover_background_button }};
        }
        .w-50px{
            width: 50px !important;
        }

        .table > thead > tr > th,
        .ant-table-thead > tr > th {
            background: {{ $background_menu  }};
            color: {{ $text_color_menu_active . ' !important' }} ;
        }

        .menu_left_frontend .wrapped_menu,
        .menu_left_frontend .sub_menu_child{
            background: {{ $bg_menu . ' !important' }};
        }
      </style>
</head>
<body>
    @php
        $survey_popup = getSurveyPopup();
        $tabs = Request::segment(1);
        $tabs_2 = Request::segment(2);
        $check_survey_popup = session()->get('survey_popup_time')->format('Y-m-d H:i:s') >= now()->format('Y-m-d H:i:s');

        // kiểm tra không phải trang đang làm khảo sát
        $check_do_survey = !in_array($tabs_2, ['user','online','edit-user','edit-user-online']);
    @endphp

    @if ($check_do_survey)
        @include('layouts.top_menu')
        @include('layouts.top_banner')
        @include('layouts.left_menu')
    @endif

    <!-- Body Start -->
    <div class="body_frontend pb-5 {{ $check_do_survey ? 'wrapper' : '' }} _bg4586 {{ session()->exists('close_open_menu_frontend') && session()->get('close_open_menu_frontend') == 0 ? 'wrapper__minify' : '' }}">
        <div class="content_frontend">
            @yield('content')
        </div>
        @if($user_type == 1)
            @include('layouts.menu_bottom',['get_color_button' => $get_color_button])
        @endif
    </div>

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
    >
    </div>
    
    <!-- Body End -->
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    <script src="{{ mix('js/appReact.js') }}"></script>
    <script src="{{ mix('js/theme2.js') }}" type="text/javascript"></script>
    {{-- <div id="app">
        <div id="mod-chat">
            @include('layouts.chat')
        </div>
    </div> --}}
    @yield('footer')
    <div id="app-modal"></div>
    {{-- <script src="{{ mix('js/app.js') }}" defer type="text/javascript"></script> --}}
</body>
</html>
