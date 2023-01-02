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

    <script type="text/javascript">
        var base_url = '{{ url('/') }}';
    </script>
    <!-- Stylesheets -->
    <link href="{{ asset('css/font_roboto_400_700_500.css') }}" rel="stylesheet">
    <link href="{{ mix('css/theme.css') }}" rel="stylesheet">
    <script src="{{ mix('js/theme.js') }}" type="text/javascript"></script>
    @php
        $get_color_button = \App\Models\SettingColor::where('name','color_button')->first();;
        $get_color_link = \App\Models\SettingColor::where('name','color_link')->first();

        $color_link = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_link->text;
        $hover_color_link = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_link->hover_text;

        $color_text_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_button->text;
        $color_hover_text_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_button->hover_text;
        $background_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_button->background;
        $hover_background_button = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_button->hover_background;

        $color_title = get_config('color_title') ?? "#1b4486";
    @endphp
    @yield('header')
    <style>
        .content_frontend .btn {
            border-radius: 5px;
            color: {{ $color_text_button }};
            background: {{ $background_button }};
            margin-left: 3px;
            border: none;
        }
        .content_frontend .btn:hover {
            border-radius: 5px;
            color: {{ $color_hover_text_button }};
            background: {{ $hover_background_button }};
        }
    </style>
</head>
<body>
    <div class="content_frontend">
        @yield('content')
    </div>

    <!-- Body End -->
    <script src="{{ mix('js/theme2.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $('.datetimepicker').datetimepicker({
            locale:'vi',
            format: 'DD/MM/YYYY HH:mm'
        });
        $('.datetimepicker-timeonly').datetimepicker({
            locale:'vi',
            format: 'LT'
        });
        $('.datepicker').datetimepicker({
            locale:'vi',
            format: 'DD/MM/YYYY'
        });

        $('.select2').select2({
            allowClear: true,
            dropdownAutoWidth : true,
            width: '100%',
            placeholder: function(params) {
                return {
                    id: null,
                    text: params.placeholder,
                }
            },
        });
    </script>
    @yield('footer')
</body>
</html>
