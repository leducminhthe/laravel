<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=9">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, public">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@lang('app.login')</title>

    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{{ image_file(@$favicon->value) }}">
    <script type="text/javascript">
        base_url = '{{ url('/') }}';
        window._app_env_ = '{{ config('app.env')}}';
    </script>

    <!-- Stylesheets -->
    <link href="{{ asset('css/font_roboto_400_700_500.css') }}" rel='stylesheet'>
    <link rel="preload" href="{{ mix('css/theme.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ mix('css/theme.css') }}">
    </noscript>
    <link href="{{ asset('css/login_web.css') }}" rel="stylesheet">

    @php
        $get_color_button = \App\Models\SettingColor::where('name','color_button')->first();

        $color_text_button = $get_color_button->text;
        $color_hover_text_button = $get_color_button->hover_text;
        $background_button = $get_color_button->background;
        $hover_background_button = $get_color_button->hover_background;

        $isMobile = isMobile();
    @endphp
    <style type="text/css">
        .bg-login{
            color: {{ $color_text_button . ' !important' }};
            background: {{ $background_button }};
        }
        .bg-login:hover {
            color: {{ $color_hover_text_button . ' !important' }};
            background: {{ $hover_background_button }};
        }
    </style>
</head>

<body class="login-page">
<!-- Signup Start -->
    <div id="carouselExampleControls" class="carousel slide w-100" data-ride="carousel">
        <div class="carousel-inner vh-100">
            @if($img->count() == 0 || $isMobile)
                <div class="carousel-item h-100 active" style="background:url({{ asset('/images/background_default.webp')}}) no-repeat center; background-size:cover"></div>
            @else
                @foreach($img as $key => $slider)
                    <div class="carousel-item h-100 {{ $key == 0 ? 'active' : '' }}" style="background:url({{ $img ? image_file($slider->image) : asset('/images/background_default.webp')}}) no-repeat center; background-size:cover"></div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="row" id="page-login">
        <div class="col-12 wrraped_login">
            <div class="login-box">
                <div class="card wrapped_login_form">
                    <div class="body">
                        <div class="logo">
                            <a href="javascript:void(0);">
                                <img src="{{ image_file(@$logo->image, 'logo') }}" width="100%" height="100%">
                            </a>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h6 class="title-login">{{ trans('laother.welcome_elearning') }}</h6>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-2 pr-1">
                                <h1 class="mb-5">{{ trans('laother.hello') }}</h1>
                                <div class="text-danger">{{session('message')}}</div>
                                <form action="{{ route('login') }}" method="post" id="frmLogin"  autocomplete="off">
                                    @csrf
                                    <input type="hidden" name="language" value="{{ $language }}">
                                    <div class="ui search focus mt-15">
                                        <div class="wrraped_input">
                                            <input class="user_name_login" type="text" name="username" id="id_email" required maxlength="64" placeholder="@lang('backend.user_name')" onfocusout="outUsername()" value="{{ session()->has('username') ? session()->get('username') : '' }}">
                                            <i class="uil uil-user icon icon2 icon_username" style="color: #ca9500; opacity: 1;"></i>
                                        </div>
                                    </div>
                                    <div class="ui search focus mt-15">
                                        <div class="wrraped_input">
                                            <input class="password_login" type="password" name="password" value="" id="id_password" required maxlength="64" placeholder="@lang('backend.pass')" onfocusout="outPassword()">
                                            <i class="uil uil-key-skeleton-alt icon icon2 icon_password" style="color: #ca9500; opacity: 1;"></i>
                                        </div>
                                    </div>
                                    {{--@if (request()->session()->has('login_attempts'))
                                        @php $attempts = request()->session()->get('login_attempts'); @endphp
                                        @if ($attempts > 99)
                                            <div class="row mt-15">
                                                <div class="col-sm-12 text-center">
                                                    {!! NoCaptcha::renderJs() !!}
                                                    {!! NoCaptcha::display() !!}
                                                </div>
                                            </div>
                                        @endif
                                    @endif--}}
                                    {{--  @if (request()->session()->has('login_attempts'))
                                        @php $attempts = request()->session()->get('login_attempts'); @endphp
                                        @if ($attempts > 5)
                                    <div class="captcha row mt-15">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                            @captcha
                                            <input type="text" id="captcha" class="form-control" name="captcha" placeholder="Nhập mã bảo vệ" style="margin: auto" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                        @endif
                                    @endif  --}}
                                    <div class="row mt-15 mb-4">
                                        <div class="col-12 text-center">
                                            @php
                                                $login = strtoupper(trans('laother.login_web'));
                                            @endphp
                                            <button class="btn bg-login">{{ $login }}</button>
                                        </div>
                                        <div class="col-12 text-center mt-1">
                                            <a class="cursor_pointer" onclick="forgetPassHandle(1)">Quên mật khẩu</a>
                                        </div>
                                    </div>
                                </form>
                                <div class="text-center mt-15">
                                    <a class="btn_link_app"
                                        @if($app_android)
                                            href="{{ $app_android->link ?? link_download('uploads/'.$app_android->file) }}"
                                            @if ($app_android->link)
                                                target="_blank"
                                            @endif
                                        @endif
                                    >
                                        <img src="{{ asset('images/btn_google_play.webp') }}" alt="" width="100%" height="100%">
                                    </a>
                                    <a class="btn_link_app"
                                        @if($app_apple) href="{{ $app_apple->link ?? link_download('uploads/'.$app_apple->file) }}"
                                            @if ($app_apple->link)
                                                target="_blank"
                                            @endif
                                        @endif
                                    >
                                        <img src="{{ asset('images/btn_app_store.webp') }}" alt="" width="100%" height="100%">
                                    </a>
                                </div>
                            </div>
                            <div class="pr-0 col-lg-6 col-md-6 text-center img-login">
                                <img class="img-responsive m-0" src="{{ asset('images/design/hethong_web.gif') }}" width="100%" height="100%">
                            </div>
                        </div>
                    </div>
                    <div class="languages">
                        @if ($language == 'en')
                            <span>USA</span>
                        @else
                            <span>Việt Nam</span>
                        @endif
                    </div>
                </div>
                <div class="card wrapped_forget_pass py-3">
                    <div class="body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-1">
                                        <a class="cursor_pointer back_login" onclick="forgetPassHandle(0)">
                                            <i class="fas fa-arrow-left"></i>
                                        </a>
                                    </div>
                                    <div class="col-10 text-center">
                                        <h3>Đặt lại mật khẩu</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mx-0 mt-4">
                            <div class="col-1"></div>
                            <div class="col-10 text-center">
                                <input type="text" class="form-control my-3" id="forget_username" placeholder="Nhập username">
                                <input type="email" class="form-control" id="forget_email" placeholder="Nhập email">
                                <button class="btn bg-login mt-4" onclick="sendForgetPass()">Gửi</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Signup End -->

<div id="app-modal"></div>
<script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
<script src="{{ mix('js/theme.js') }}"></script>
<script type="text/javascript">
    var url = '<?php echo $url ?>';
    $("#id_email").on('click',function(){
        $('.icon_username').hide();
        $('.user_name_login').attr('style', 'padding-left: 2em !important');
    });

    $("#id_password").on('click',function(){
        $('.icon_password').hide();
        $('.password_login').attr('style', 'padding-left: 2em !important');
    });

    function outUsername() {
        $('.icon_username').show();
        $('.user_name_login').attr('style', 'padding-left: 4em !important');
    }

    function outPassword() {
        $('.icon_password').show();
        $('.password_login').attr('style', 'padding-left: 4em !important');
    }

    $("#reset-pass").on('click', function () {
        let url = $(this).data('url');
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'html',
            data: {},
        }).done(function(data) {
            $("#app-modal").html(data);
            $("#app-modal #modal-reset-pass").modal();

        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
        return false;
    });

    $('.datepicker').datetimepicker({
        locale: 'vi',
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

    $('#frmLogin').submit(function () {
        var form = $('#frmLogin');
        var btnsubmit = form.find("button:focus");
        var oldText = btnsubmit.text();
        var exists = btnsubmit.find('i').length;
        if (exists>0)
            btnsubmit.find('i').attr('class', 'fa fa-spinner fa-spin');
        else
            btnsubmit.html('<i class="fa fa-spinner fa-spin"></i>'+oldText);
        btnsubmit.prop("disabled", true);
    })


    function forgetPassHandle(type) {
        if(type == 1) {
            $('.wrapped_login_form').hide();
            $('.wrapped_forget_pass').show();
        } else {
            $('.wrapped_login_form').show();
            $('.wrapped_forget_pass').hide();
        }
    }

    function sendForgetPass() {
        let username = $('#forget_username').val()
        let email = $('#forget_email').val()
        $.ajax({
            type: 'POST',
            url: "{{ route('auth.reset_pass') }}",
            dataType: 'json',
            data: {
                username: username,
                email: email,
            },
        }).done(function(data) {
            show_message(data.message, data.status);
            if(data.status == 'success') {
                window.location.href = data.redirect
            }
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
        return false;
    }
</script>

<script type="text/javascript" src="{{ mix('js/theme3.js') }}"></script>
</body>
</html>
