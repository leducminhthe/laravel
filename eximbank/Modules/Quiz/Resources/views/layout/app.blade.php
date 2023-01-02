<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=9">
    <meta name="description" content="itechco">
    <meta name="author" content="itechco">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--<meta name="turbolinks-cache-control" content="no-cache">--}}
    <title>@yield('page_title')</title>
    <script type="text/javascript">
        var base_url = '{{ url('/') }}';
    </script>
    <link rel="icon" type="image/png" href="{{ image_file(\App\Models\Config::getFavicon()) }}">

{{--    <link href="http://fonts.googleapis.com/css?family=Roboto:400,700,500" rel="stylesheet">--}}
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
{{--    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>--}}
    <script src="{{ asset('js/theme.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/ckeditor_4.16.2/ckeditor.js') }}" type="text/javascript" ></script>

    @livewireStyles
    @yield('header')
    <style>
        @media (max-width: 823px) {
            .header .header_right{
                display: none;
            }
        }
        .footer{
            position: fixed;
            bottom: 0;
        }
        .faq1256{
            padding-bottom: 50px;
        }
    </style>
</head>

<body>
@php
    $routeName = Route::currentRouteName();
    $user_type = \Modules\Quiz\Entities\Quiz::getUserType();
@endphp

<div class="">
    <div class="faq1256">
        @yield('content')
    </div>

    @include('layouts.footer')
</div>

<script src="{{ asset('js/theme2.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    // var lazyLoadInstance = new LazyLoad({
    //     // Your custom settings go here
    // });
    var editor = $('#editor');
    if(editor.length > 0){
        // CKEDITOR.replace( 'editor' );
        CKEDITOR.replace('editor', {
            filebrowserImageBrowseUrl: '/filemanager?type=image',
            filebrowserBrowseUrl: '/filemanager?type=file',
            filebrowserUploadUrl : null, //disable upload tab
            filebrowserImageUploadUrl : null, //disable upload tab
            filebrowserFlashUploadUrl : null, //disable upload tab
        });
    }

</script>
@livewireScripts
@yield('footer')
</body>
</html>
