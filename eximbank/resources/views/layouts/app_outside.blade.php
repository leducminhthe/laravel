<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=9">
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

    <link href="{{ asset('vendor/bootstrap-4.4.1/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/OwlCarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/OwlCarousel/assets/owl.theme.default.min.css') }}" rel="stylesheet">
    <script src="{{ asset('vendor/ckeditor_4.16.2/ckeditor.js') }}" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" href="{{ asset('css/cdnjs_cloudflare_v4.7.0_font-awesome.min.css') }}">
    <link href="{{ asset('css/outside.css') }}" rel="stylesheet">
    <link href="{{ asset('styles/bxslider/jquery.bxslider.css') }}" rel="stylesheet">
    <script src="{{ mix('js/theme.js') }}" type="text/javascript"></script>
    @php
        $get_color_menu = \App\Models\SettingColor::where('name','color_menu')->first();

        $text_color_menu = $get_color_menu->text;
        $text_color_menu_active = $get_color_menu->active;
        $hover_text_color_menu = $get_color_menu->hover_text;
        $hover_background_menu = $get_color_menu->hover_background;
    @endphp
    @livewireStyles
    @yield('header')
    <style type="text/css">
        .has-sub a, 
        .has-child a{
            color: {{ $text_color_menu }};
        }
        .no-action:hover,
        .has-child:hover{
            border-radius: 10px;
            background: {{ $hover_background_menu }};
        }
        .no-action:hover a,
        .has-child:hover a{
            color: {{ $hover_text_color_menu }};
        }

        .title_cate_left a:hover{
            border-radius: 5px;
            background: {{ $hover_background_menu }};
            color: {{ $text_color_menu }};
        }

        .second-menu{
            background: #e9ecef;
        }

        body {
            font-size: 14px
        }

        .body_outside a:hover h5,
        .body_outside a:hover h6,
        .body_outside a:hover p {
            color: {{ $text_color_menu .' !important' }};
        }

        .btn_to_top {
            background: {{ $hover_background_menu }};
        }
        .btn_to_top i {
            color: {{ $text_color_menu }};
        }
    </style>
</head>
<body>
    @include('layouts.top_menu_outside')
    <!-- Body Start -->
    <div class="fix-content" id="home-page" style="opacity: 1;">
        @yield('content')
        <button class="btn btn_to_top" type="button" onclick="topFunction()">
            <i class="fa fa-caret-up" aria-hidden="true"></i>
        </button>
    </div>

    <script src="{{ asset('vendor/bootstrap-4.4.1/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/OwlCarousel/owl.carousel.min.js') }}"></script>

    <script>
        // LÊN ĐẦU TRANG
        $('.btn_to_top').hide();
        function topFunction() {
            $('html,body').animate({ scrollTop: 0 }, 'slow');
            return false; 
        }

        window.onscroll = function() {scrollFunction()};
        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                $('.btn_to_top').show();
            } else {
                $('.btn_to_top').hide();
            }
        } 
        
        $(document).ready(function(){
            $(".has-sub").hover(function () {
                $(this).find('.sub-menu-drop').addClass('active');
                $(this).find('.sub-menu-drop').find('.has-child').addClass('active');
            }, function () {
                $(this).find('.sub-menu-drop').removeClass('active');
                $(this).find('.sub-menu-drop').find('.has-child').removeClass('active');
            });
        });
        $('.menu_bottom').hide();
        if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
        // true for mobile device
            // window.onscroll = function (e) {
            //     if(window.scrollY == 0){
            //         //$('.inner-header').show();
            //         //$('.menu_top').show();
            //         //$('.menu_bottom').hide();
            //         $('.second-menu').css('top','150px');
            //         $('.banner_outside').css('margin-top','210px');
            //     } else {
            //         //$('.inner-header').hide();
            //         //$('.menu_top').hide();
            //         //$('.menu_bottom').show();
            //         $('.second-menu').css('top','0px');
            //         $('.banner_outside').css('margin-top','160px');
            //     }
            // }
        }else{
        // false for not mobile device
            // window.onscroll = function (e) {
            //     if(window.scrollY == 0){
            //         $('.inner-header').show();
            //         $('.menu_top').show();
            //         $('.menu_bottom').hide();
            //         $('.second-menu').css('top','60px');
            //     } else {
            //         $('.inner-header').hide();
            //         $('.menu_top').hide();
            //         $('.menu_bottom').show();
            //         $('.second-menu').css('top','0px');
            //     }
            // }
        }

        $('.datepicker').datetimepicker({
            locale:'vi',
            format: 'DD-MM-YYYY'
        });
    </script>
    <!-- Body End -->
    @livewireScripts
</body>
</html>
