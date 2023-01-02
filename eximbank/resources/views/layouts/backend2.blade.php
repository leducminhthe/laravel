<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
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
    <link href="{{ mix('css/backend.css') }}" rel="stylesheet">
    <script src="{{ mix('js/reactjs.js') }}" type="text/javascript"></script>
    @yield('header')
    <style type="text/css">
        .wrapper{
            padding: unset;
        }

    </style>
</head>

<body>
    @include('layouts.backend.left_menu')
    <!-- Body Start -->
    <div class="wrapper _bg4586 wrapper_backend {{ session()->exists('close_open_menu_backend') && session()->get('close_open_menu_backend') == 0 ? 'wrapper__minify' : '' }}">
        @include('layouts.backend.top_menu')

        <div class="body_content body_backend pt-5" style="left: {{ session()->exists('close_open_menu_backend') && session()->get('close_open_menu_backend') == 0 ? '50px' : '' }}">
            <div class="container-fluid container_backend pt-3">
                <div class="row mb-3 mt-2 bg-white">
                    <div class="col-md-12 px-0 custom_menu_horizontal">
                        @yield('breadcrumb')
                    </div>
                </div>
                <div class="row bg-white backend-container pt-3">
                    <div id="app" class="col-md-12 pb-3">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="app-modal"></div>
    <!-- Body End -->
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    <script src="{{ mix('js/appReact.js') }}"></script>
    <script src="{{ mix('js/theme2.js') }}" type="text/javascript"></script>
    @yield('footer')
</body>
</html>
