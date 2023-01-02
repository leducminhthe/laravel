<!doctype html>
<html lang="en" class="deeppurple-theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="content-language" content="en">
    <meta name="language" content="en">

    <title>@lang('app.login')</title>

    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{{ image_file(\App\Models\Config::getFavicon()) }}">

    <link href="{{ mix('themes/mobile/css/login_mobile_header.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/login2.css') }}" rel="stylesheet">

    <script type="text/javascript">
        var base_url = '{{ url('/') }}';
        var title_message = "{{ data_locale('Thông báo', 'Notification') }}";
    </script>

    @php
        $getColorButton = App\Models\SettingColor::where('name', 'color_button')->first();
        $backgroundButton = $getColorButton->background;
        $lightBackgroundButton = luminance($getColorButton->background, 0.5);
        $textColorButton = $getColorButton->text;
    @endphp

    <style>
        .btn, .btn:hover, .btn-primary:hover, .btn-info:hover, .btn-default:hover {
            border-radius: 15px;
            background: linear-gradient(135deg, {{ $lightBackgroundButton }} 0%, {{ $backgroundButton }} 100%) !important;
        }

        .btn, .btn:hover, .btn-primary:hover, .btn-info:hover, .btn-default:hover {
            color: {{ $textColorButton }} !important;
        }

        .choose_login .button-regular {
            background: rgba(3, 63, 136, 0.5) !important;
            border-radius: 50px;
            border-color: rgba(255, 255, 255, .5) !important;
            width: 100%;
            text-align: left;
        }
        .btn img.icon_login_mobile {
            max-width: 40px !important;
            height: 40px;
        }

        #userThird .modal-body{
            padding: 0px;
        }
        #userThird .form-control{
            border-radius: 0px;
            border-top: unset;
            border-left: unset;
            border-right: unset;
        }
    </style>
</head>

<body>
@php
    $img = \App\Models\LoginImage::where('status', '=', 1)->where('type', 2)->get();
    $logo = \App\Models\LogoModel::where('status',1)->first();
@endphp
<div class="wrapper wrapper_mobile_login">
    @php
        $get_infomation_company = \App\Models\InfomationCompany::first();
    @endphp

    <div id="carouselExampleControls" class="carousel slide w-100" data-ride="carousel">
        <div class="carousel-inner vh-100">
            @foreach($img as $key => $slider)
                <div class="carousel-item h-100 {{ $key == 0 ? 'active' : '' }}" style="background:url({{ $img ? image_file($slider->image) : asset('/images/img-login.jpg')}}) no-repeat center; background-size:cover"></div>
            @endforeach
        </div>
    </div>

    <div class="row no-gutters login-row">
        <div class="col align-self-center px-3 text-center">
            <img src="{{ image_file(\App\Models\Config::getConfig('logo_outside'), 'logo') }}" alt="logo" width="250">
            <h5 class="welcome_e_learning">{{ data_locale('HỆ THỐNG ĐÀO TẠO ELEARNING', 'LEARNING HUB SYSTEM') }}</h5>
            <div class="text-danger">{{session('message')}}</div>
            <form action="{{ route('login') }}" method="post" class="form-signin mt-3" id="form_login_elearning">
                @csrf
                <div class="form-group">
                    <input type="text" name="username" id="inputEmail" class="form-control form-control-lg text-center" placeholder="@lang('backend.user_name')" autofocus value="{{ session()->get('username') ? session()->get('username') : '' }}">
                </div>

                <div class="form-group">
                    <input type="password" name="password" id="inputPassword" class="form-control form-control-lg text-center" placeholder="@lang('backend.pass')" value="{{ session()->get('password') ? session()->get('password') : '' }}">
                </div>
                @php
                    if (request()->session()->has('login_attempts')) {
                        $attempts = request()->session()->get('login_attempts');
                    }
                @endphp
                @if ($attempts >5)
                    <div class="form-group">
                        <div class=" ">
                            @captcha
                            <input type="text" id="captcha" class=" " name="captcha" placeholder="Nhập mã bảo vệ" style="margin: auto; width:43%" autocomplete="off">
                        </div>
                    </div>
                @endif
                <!-- login buttons -->
                <div class="form-group">
                    <button class="btn bg-template btn-default btn-lg shadow btn-block">@lang('app.login')</button>
                </div>
                <div class="form-group">
                    <input type="hidden" name="remember_login" value="1">
                    {{--  <a href="javascript:void(0)" class="form-group text-primary" data-toggle="modal" data-target="#userThird">
                        {{ data_locale('Đăng ký', 'Register Account') }}?
                    </a>  --}}
                </div>
                <!-- login buttons -->
            </form>
        </div>
    </div>
</div>

@include('themes.mobile.modal.create_user_third')
<div id="app-modal"></div>

<!-- jquery, popper and bootstrap js -->
<script src="{{ mix('themes/mobile/js/login_mobile_footer.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    $('#form_login_elearning').submit(function () {
        var form = $('#form_login_elearning');
        var btnsubmit = form.find("button:focus");
        var oldText = btnsubmit.text();
        var exists = btnsubmit.find('i').length;
        if (exists>0)
            btnsubmit.find('i').attr('class', 'fa fa-spinner fa-spin');
        else
            btnsubmit.html('<i class="fa fa-spinner fa-spin"></i>'+oldText);
        btnsubmit.prop("disabled", true);
    })
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

    $('#userThird').on('click', '#btn_register', function(){
        var username = $('#userThird input[name=username]').val();
        var password = $('#userThird input[name=password]').val();
        var lastname = $('#userThird input[name=lastname]').val();
        var firstname = $('#userThird input[name=firstname]').val();
        var notify = '';

        if(username.length <= 0){
            notify = 'Tên đăng nhập không được trống';
        }else if(password.length <= 0){
            notify = 'Mật khẩu không được trống';
        }else if(lastname.length <= 0){
            notify = 'Họ không được trống';
        }else if(firstname.length <= 0){
            notify = 'Tên người dùng không được trống';
        }

        $('#userThird #notify_register').html(notify);
    });
</script>

</body>

</html>
