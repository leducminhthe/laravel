<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
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
    <title>Mạng xã hội</title>

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
    <style>
        body {
            background: #F0F2F5 !important;
        }
    </style>
</head>
<body class="body_social">
    <!-- Body Start -->
    <div class="content_social">
        <div id="link_image"
            data-book="{{ trans('app.book') }}"
        >
        </div>
        <div id="react" class="sa4d25 wrapped_social_network">     
        </div>
    </div>

    <!-- Body End -->
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    <script src="{{ mix('js/appReact.js') }}"></script>
</body>
</html>
