<!DOCTYPE html>
<html lang="vi-vn">
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
        window._asset = '{{ asset('') }}';
    </script>
    <script type="text/javascript">
        var base_url = '{{ url('/') }}';
    </script>

    <!-- Stylesheets -->
    <link href="{{ mix('css/appReact.css') }}" rel="stylesheet">
    @yield('header')
</head>
<body>
    <!-- Body Start -->
    <div class="main">
        @yield('content')
    </div>
    <!-- Body End -->
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    <script src="{{ mix('js/appReact.js') }}"></script>
    <div id="app">
    </div>
    @yield('footer')
    <div id="app-modal"></div>
    {{-- <script src="{{ mix('js/app.js') }}" defer type="text/javascript"></script> --}}
</body>
</html>
