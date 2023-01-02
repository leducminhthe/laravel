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
    <link rel="icon" type="image/png" href="{{ image_file(\App\Models\Config::getFavicon()) }}">
    <script type="text/javascript">
        base_url = '{{ url('/') }}';
        window._app_env_ = '{{ config('app.env')}}';
    </script>    <!-- Stylesheets -->
    <link href='{{ asset('css/font_roboto_400_700_500.css') }}' rel='stylesheet'>
    <link href="{{ mix('css/theme.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login_web.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
    <script src="{{ mix('js/theme.js') }}"></script>
    <style>
        body {
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-white">

    <div class="first-login">
        <div class="bg-white">
            <div class="main_logo25 pt-3" id="logo">
                <img src="{{ image_file(\App\Models\Config::getLogoOutside()) }}" alt="" class="" style="width: 15%">
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="row pb-4 pt-3">
                        <div class="col-4"></div>
                        <div class="col-md-4 text-uppercase text-center">
                            Vui lòng đổi mật khẩu lần đầu truy cập !!!
                        </div>
                    </div>

                    <form action="{{ route('module.frontend.user.change_pass_first') }}" method="post" id="form-change-pass" enctype="multipart/form-data" class="form-ajax">
                        @csrf
                        <div class="form-group row">
                            <div class="col-4"></div>
                            <div class="col-md-4">
                                <label for="password_old">@lang('app.old_password')</label> <span class="text-danger">*</span>
                                <input name="password_old" id="password-old" type="password" class="form-control" value=""
                                       placeholder="@lang('app.old_password')" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-4"></div>
                            <div class="col-md-4">
                                <label for="password">@lang('app.new_password')</label> <span class="text-danger">*</span>
                                <input name="password" id="password" type="password" class="form-control" value="" placeholder="@lang('app.password')"
                                       autocomplete="off" required>
                                <p></p>
                                <input name="repassword" id="repassword" type="password" class="form-control" value=""
                                       placeholder="@lang('app.confirm_password')" autocomplete="off" required>
                                <p></p>
                                <span class="text-danger">Lưu ý: Password có viết hoa, thường, số, ký tự đặc biệt ít nhất 8 ký tự</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-4"></div>
                            <div class="col-4">
                                <button type="submit" class="btn">Lưu</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('js/theme3.js') }}"></script>
</body>
</html>
