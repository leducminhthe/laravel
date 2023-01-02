<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" class="{{ session()->exists('nightMode') && session()->get('nightMode') == 1 ? 'night-mode' : '' }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=9">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--<meta name="turbolinks-cache-control" content="no-cache">--}}
    <title>@yield('page_title')</title>

    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{{ image_file(\App\Models\Config::getFavicon()) }}">
    <script type="text/javascript">
        var base_url = '{{ url('/') }}';
        window._app_env_ = '{{ config('app.env')}}';
    </script>
    <!-- Stylesheets -->
    <link href="{{ asset('css/font_roboto_400_700_500.css') }}" rel="stylesheet">
    <link href="{{ mix('css/backend.css') }}" rel="stylesheet">
    <script src="{{ mix('js/backend.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/ckeditor_4.16.2/ckeditor.js') }}" type="text/javascript"></script>

    <link rel="stylesheet" href="{{ asset('vendor/emojionearea/emojionearea.min.css') }}">
    <script type="text/javascript" src="{{ asset('vendor/emojionearea/emojionearea.min.js') }}"></script>

    @livewireStyles
    @yield('header')

    @php
        $get_color_button = $color_button;
        $get_lighter_color = $lighter_background_color_button;
        $get_lighter_hover_color = $lighter_background_hover_color_button;

        $get_color_link = $color_link;
        $color_link = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#c8cdd3' : $get_color_link->text;
        $hover_color_link = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#c8cdd3' : $get_color_link->hover_text;

        $color_text_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#c8cdd3' : $get_color_button->text;
        $color_hover_text_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#c8cdd3' : $get_color_button->hover_text;
        $background_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_button->background;
        $hover_background_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_button->hover_background;

        $color_title = get_config('color_title') ?? "#1b4486";

        $get_color_menu = $color_menu;
        $text_color_menu_active = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_menu->active;
        $background_menu = $get_color_menu->background;

        $bg_menu = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : (get_config('bg_menu') ?? "#fff");
    @endphp
    {{-- MÀU CHO NÚT NHẤN --}}
    <style type="text/css">
        .body_content a {
            color: {{ $color_link }};
        }
        .body_content a:hover {
            color: {{ $hover_color_link }};
        }
        .visible-links
        .border_breadcum,
        .nav-pills .nav-link.active,
        .nav-pills .show > .nav-link
        {
            background: {{ $background_button . ' !important' }};
        }
        #app-modal .btn,
        .btn {
            color: {{ $color_text_button . ' !important' }};
            background: linear-gradient(to right, {{ $background_button }}, {{ $get_lighter_color }});
            border: none;
            margin-left: 3px !important;
        }
        #app-modal .btn:hover,
        .btn:hover {
            border-radius: 5px;
            color: {{ $color_hover_text_button . ' !important' }};
            background: linear-gradient(to right, {{ $hover_background_button }}, {{ $get_lighter_hover_color }});
        }
        #my-course .tab_crse .nav-link{
            background-size: 202% !important;
            background: linear-gradient(to left, white 50%, {{ $hover_background_button }} 50%) right;
        }
        #list-training #my-course .tab_crse .nav-link{
            background-size: 202% !important;
            background: linear-gradient(to left, white 50%, {{ $hover_background_button }} 50%) right;
        }

        #form_online_course .btn_link_online,
        #form_offline_course .btn_link_offline,
        .form_offline_course .btn_link_offline {
            background-size: 202% !important;
            background: linear-gradient(to left, {{ $background_button }} 50%, {{ $hover_background_button }} 50%) right ;
            color: {{ $color_text_button . ' !important' }};
        }
        #collapse_menu{
            background: #fff;
            border: unset !important;
            color: {{ $color_text_button }};
        }
        #collapse_menu:hover, #collapse_menu:hover i{
            background: {{ $hover_background_button }} !important;
            border: unset !important;
            color: {{ $color_hover_text_button }} !important;
        }
        #collapse_menu i{
            color: {{ $background_button }};
        }

        /*Menu chi tiết nhân viên trong quản trị*/
        #menu_user_backend .btn svg {
            fill: {{ $color_text_button }};
        }
        #menu_user_backend .btn:hover svg {
            fill: {{ $color_hover_text_button }};
        }
        /*****************************************/

        .bg-active{
            background: {{ $hover_background_button }} !important;
            color: {{ $color_hover_text_button }} !important;
        }

        .table > thead > tr > th {
            background: {{ $background_menu . ' !important' }};
            color: {{ $text_color_menu_active . ' !important' }} ;
        }
        .table > thead > tr > th .color_table {
            color: {{ $text_color_menu_active  }} ;
        }

        .menu_left_backend .wrapped_menu,
        .menu_left_backend .sub_menu_child{
            background: {{ $bg_menu . ' !important' }};
        }
    </style>
</head>

<body class="wrraped_body_backend">
    @include('layouts.backend.left_menu', ['get_color_menu' => $get_color_menu, 'profile_view' => $profile_view])
    <!-- Body Start -->
    <div class="wrapper _bg4586 wrapper_backend {{ session()->exists('close_open_menu_backend') && session()->get('close_open_menu_backend') == 0 ? 'wrapper__minify' : '' }}">
        @include('layouts.backend.top_menu', ['get_color_menu' => $get_color_menu, 'profile_view' => $profile_view])

        <div class="body_content body_backend pt-5" style="left: {{ session()->exists('close_open_menu_backend') && session()->get('close_open_menu_backend') == 0 ? '50px' : '230px' }}">
            <div class="container-fluid container_backend pt-3">
                <div class="row mb-3 mt-2 bg-white">
                    <div class="col-md-12 px-0 custom_menu_horizontal">
                        @yield('breadcrumb')
                    </div>
                </div>
                @yield('content_first')
                <div class="row bg-white backend-container pt-3">
                    <div class="col-md-12 pb-3">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        {{-- @include('layouts.backend.footer') --}}
    </div>

    <div id="app-modal"></div>
    <div class="element_data"
        data-user_unit = '{{ session()->get('user_unit') }}'
        data-url_choose_unit_modal = '{{ route('choose_unit_modal') }}'
        data-url_load_unit_modal = '{{ route('load_unit_modal') }}'
        data-choose_unit = '-- {{ trans('latraining.choose_unit') }} --'
    >
    </div>
    <!-- Body End -->
    <script src="{{ mix('js/backend2.js') }}" type="text/javascript"></script>
    <script src="{{ mix('js/loadModalChooseUnit.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $('.datetime-month').datepicker({
            format: 'mm/yyyy',
        });
        $('.modal .datetimepicker').datetimepicker({
            locale: 'vi',
            format: 'DD/MM/YYYY'
        });
        $(".form-validate").validate({
            onfocusout: false,
            highlight: function (element, errorClass, validClass) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
                } else {
                    elem.addClass(errorClass);
                }
            },
            unhighlight: function (element, errorClass, validClass) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
                } else {
                    elem.removeClass(errorClass);
                }
            },
            errorPlacement: function (error, element) {
                return true;
            }
        });
        var editor = $('#editor');
        if(editor.length > 0){
            CKEDITOR.replace( 'editor' );
        }

        var scrollTrigger = 60,
        backToTop = function () {

        };

        // backToTop();
        $(window).on('scroll', function () {
            backToTop();
        });
    </script>

    @livewireScripts
    @yield('footer')
</body>
</html>
