<!doctype html>
<html lang="en" class="deeppurple-theme {{ session()->exists('nightModeMobile') && session()->get('nightModeMobile') == 1 ? 'theme-dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, shrink-to-fit=9"/>
    <meta name="turbolinks-cache-control" content="no-cache">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="language" content="en">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, public">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>@yield('page_title')</title>
    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{{ image_file(\App\Models\Config::getFavicon()) }}">

    <link href="{{ mix('themes/mobile/css/app_mobile_header.min.css') }}" rel="stylesheet">
    <script src="{{ mix('themes/mobile/js/app_mobile_header.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        var base_url = '{{ url('/') }}';
        var title_message = "{{ data_locale('Thông báo', 'Notification') }}";
    </script>
    @php
        $url_previous = url()->previous();
        $routeName = Route::currentRouteName();

        $getColorButton = App\Models\SettingColor::where('name', 'color_button')->first();
        $backgroundButton = $getColorButton->background;
        $lightBackgroundButton = luminance($getColorButton->background, 0.5);
        $textColorButton = $getColorButton->text;

        $user = profile();
        $user_name = App\Models\User::find($user->user_id)->username;
    @endphp
    @yield('header')
    <style>
        .deeppurple-theme .bg-template,
        .deeppurple-theme .sidebar_right,
        .deeppurple-theme .wrapper .link_btn,
        .deeppurple-theme .wrapper button {
            background: linear-gradient(135deg, {{ $lightBackgroundButton }} 0%, {{ $backgroundButton }} 100%) !important;
        }
        .deeppurple-theme .wrapper .link_btn,
        .deeppurple-theme .wrapper button {
            color: {{ $textColorButton }} !important;
        }

        .btn-primary:hover, .btn-info:hover, .btn-default:hover, .btn-secondary:hover {
            background-color: rgb(43, 142, 235) !important;
            border-color: rgb(43, 142, 235);
        }

        .img_150{
            width: 150px !important;
            height: 150px !important;
            border-radius: 100% !important;
        }

        *:fullscreen
        *:-ms-fullscreen,
        *:-webkit-full-screen,
        *:-moz-full-screen {
            overflow: auto !important;
        }

        .btn {
            color: white !important;
        }
        #loader {
            border: 12px solid #f3f3f3;
            border-radius: 50%;
            border-top: 12px solid #444444;
            width: 70px;
            height: 70px;
            animation: spin 1s linear infinite;
            z-index: 9999;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        .center {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
        }
        .hide { display: none; }
        .show { display: block; }

        /*Đang dùng cho full modal - Không chỉnh*/
        .modal-fullscreen {
            margin: 0 !important;
            height: 100%;
        }

        .modal-fullscreen .modal-dialog {
            width: 100%;
            max-width: none;
            height: 100%;
            margin: 0;
        }

        .modal-fullscreen .modal-content {
            height: 100%;
            border: 0;
            border-radius: 0;
        }

        .modal-fullscreen .modal-body {
            overflow-y: auto;
        }
        /*************************************/
        .tab-content .tab-pane{
            box-shadow: unset;
        }

        .menu-activity{
            width: 100%;
            float: right;
            display: none;
            position: fixed;
            right: 0;
            z-index: 9999;
        }

        #content_footer img{
            max-width: 20px;
            vertical-align: middle;
        }

        .color_text_content_footer{
            color: rgb(12, 33, 136) !important;
        }
        #modalInfoUser ._ttl122_custom{
            float: right;
        }
        #modalInfoUser ._ttl123_custom {
            padding: 5px 0px;
            border-bottom: 0.5px solid rgba(0,0,0,.2);
        }
        .bg_white {
            background: white !important;
        }
    </style>
    <script>
        $('html').removeClass('red-theme blue-theme yellow-theme green-theme pink-theme orange-theme purple-theme deeppurple-theme lightblue-theme teal-theme lime-theme gray-theme black-theme');
        var dataTheme = localStorage.getItem("theme_color_mobile") ? localStorage.getItem("theme_color_mobile") : 'deeppurple-theme';
        $('html').addClass(dataTheme);
    </script>
</head>
<body>
    {{-- <div id="loader" class="center"></div> --}}
    <div id="loading_gift">
        <img class="center img_gift img_gift_default" src="{{ asset('themes/mobile/img/loading_default.gif') }}" width="300px" alt="loading">
        <img class="center img_gift img_gift_loading1" src="{{ asset('themes/mobile/img/loading1.gif') }}" width="300px" alt="loading">
        <img class="center img_gift img_gift_loading2" src="{{ asset('themes/mobile/img/loading2.gif') }}" width="300px" alt="loading">
        <img class="center img_gift img_gift_loading3" src="{{ asset('themes/mobile/img/loading3.gif') }}" width="300px" alt="loading">
    </div>
    <div class="body_mobile">
        @include('themes.mobile.layouts.sidebar_right')
        <div class="homepage" id="homepage">
            @include('themes.mobile.layouts.header')

            <div class="wrapper">
                @yield('header_activity')

                @yield('content')
            </div>

            @if (in_array($routeName, ['themes.mobile.frontend.home', 'frontend.home', 'themes.mobile.frontend.offline.detail', 'themes.mobile.frontend.online.detail']))
                @include('themes.mobile.layouts.footer')
            @endif
        </div>
    </div>

    <div class="refresher">
        <div class="loading-bar"></div>
        <div class="loading-bar"></div>
        <div class="loading-bar"></div>
        <div class="loading-bar"></div>
    </div>

    <div id="element_app_mobile"
        data-url_close_note = "{{ route('themes.mobile.cloe_note_mobile') }}"
        data-route_name = "{{ $routeName }}"
    >
    </div>
    </div>

    <script>
        (() => {
            async function simulateRefreshAction() {
                const sleep = (timeout) => new Promise(resolve => setTimeout(resolve, timeout));

                const transitionEnd = function (propertyName, node) {
                    return new Promise(resolve => {
                        function callback(e) {
                            e.stopPropagation();
                            if (e.propertyName === propertyName) {
                                node.removeEventListener('transitionend', callback);
                                resolve(e);
                            }
                        }

                        node.addEventListener('transitionend', callback);
                    });
                };

                const refresher = document.querySelector('.refresher');

                document.body.classList.add('refreshing');
                await sleep(2000);

                refresher.classList.add('shrink');
                await transitionEnd('transform', refresher);
                refresher.classList.add('done');

                refresher.classList.remove('shrink');
                document.body.classList.remove('refreshing');
                await sleep(0); // let new styles settle.
                refresher.classList.remove('done');
            }

            let _startY = 0;
            const homepage = document.querySelector('#homepage');
            homepage.addEventListener('touchstart', e => {
                _startY = e.touches[0].pageY + 100;
            }, {passive: true});

            homepage.addEventListener('touchmove', e => {
                const y = e.touches[0].pageY;

                // Activate custom pull-to-refresh effects when at the top fo the container
                // and user is scrolling up.
                if (document.scrollingElement.scrollTop === 0 && y > _startY && !document.body.classList.contains('refreshing')) {
                    simulateRefreshAction();
                    window.location = '';
                }
            }, {passive: true});
        })();

        $('body').on('click', '.userThird', function () {
            show_message("{{ data_locale('Không có quyền', 'Permission denied') }}", 'error')
        });

        $('#user-unit-top').on('change', function () {
            var url = $(this).attr('data-url');
            var value = $(this).val();
            $.ajax({
                url: url,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'post',
                data: {
                    'unit-select': value,
                },
            }).done(function(data) {
                console.log(data);
                location.reload();
                return false;
            }).fail(function(data) {
                return false;
            });
        })

        {{--  Menu hoạt động khoá học  --}}
        $('#btn-menu-activity').on('click', function(){
            if($('#menu-activity').is(':hidden')){
                $('#menu-activity').show();
            }else{
                $('#menu-activity').hide();
            }
        });

        window.addEventListener('pageshow', function(event) {
            $('#loading_gift').hide();
            $('#loader').hide();
            $('.body_mobile').show();
            $('body').removeClass('bg-white');
        });

        function loadSpinner(url, save, type_img) {
            $('.img_gift').hide()
            if(save != 0) {
                let names = []
                let routeName = $('#element_app_mobile').attr('data-route_name')
                console.log(routeName);
                if(routeName == 'themes.mobile.frontend.home') {
                    names = []
                } else {
                    names = JSON.parse(localStorage.getItem("url_back_mobile")) ? JSON.parse(localStorage.getItem("url_back_mobile")) : [];
                }
                let old_url = window.location.href;
                if(!names.includes(old_url)) {
                    names.push(old_url);
                }
                localStorage.setItem("url_back_mobile", JSON.stringify(names))
            }
            if(type_img == 1) {
                $('.img_gift_loading1').show()
            } else if (type_img == 2) {
                $('.img_gift_loading2').show()
            } else if (type_img == 3) {
                $('.img_gift_loading3').show()
            } else {
                $('.img_gift_default').show()
            }
            $('#loading_gift').show();
            $('body').addClass('bg_white');
            //$('#loader').show();
            $('.body_mobile').hide();
            window.location.href = url
        }

        function backUrlHandle(url, type_img) {
            $('.img_gift').hide()
            if(!url) {
                let names = JSON.parse(localStorage.getItem("url_back_mobile")) ? JSON.parse(localStorage.getItem("url_back_mobile")) : [];
                url = names[names.length - 1];

                const index = names.indexOf(url);
                if (index > -1) {
                    names.splice(index, 1);
                }
                localStorage.setItem("url_back_mobile", JSON.stringify(names))
            }
            if(type_img == 1) {
                $('.img_gift_loading1').show()
            } else if (type_img == 2) {
                $('.img_gift_loading2').show()
            } else if (type_img == 3) {
                $('.img_gift_loading3').show()
            } else {
                $('.img_gift_default').show()
            }
            $('#loading_gift').show();
            $('body').addClass('bg_white');
            // $('#loader').show();
            $('.body_mobile').hide();
            window.location.href = url
        }

        function changePassHandle(url) {
            $('#modalInfoUser').modal('hide')
            loadSpinner(url, 1, 3)
        }
    </script>

<!-- Modal -->
@yield('modal')

{{-- MODAL GHI CHÚ --}}
<div class="modal fade" id="modal-create-note-mobile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <form action="{{ route('themes.mobile.note_mobile.save') }}" method="post" class="form-ajax w-100">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ data_locale('Thêm ghi chú', 'Add note') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body-note">
                    <input type="hidden" name="id" id="id_note">
                    <input type="hidden" name="type" value="0">
                    <div class="form-group row">
                        <div class="col-12 label">
                            <label><i class="fa fa-calendar-alt" aria-hidden="true"></i> {{ data_locale('Thời gian thông báo', 'Notice time') }}</label>
                        </div>
                        <div class="col-12">
                            <input type="datetime-local" class="form-control w-100" id="date_time" name="date_times[]">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 label">
                            <label><i class="fa fa-edit"></i> {{ data_locale('Nội dung ghi chú', 'Content') }}</label>
                        </div>
                        <div class="col-12">
                            <textarea class="form-control" id="content_note" name="contents[]" required style="height: 30vh"></textarea>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group row">
                        <div class="col-12">
                            <button type="submit" class="btn w-100">{{ trans('app.save') }}</button>
                        </div>
                    </div>
                </div>
                <div class="separation_modal" data-dismiss="modal">
                    <hr>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="modalInfoUser" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">{{ trans('app.info') }}</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="mb-1 text-center">
                    <figure class="avatar avatar-60">
                        <a href="javascript:void(0)" class="" data-toggle="modal" data-target="#modalChangeAvatar">
                            <img src="{{ \App\Models\Profile::avatar() }}" alt="" class="avatar-60">
                        </a>
                    </figure>
                </div>
                <div class="_ttl123_custom mt-0">
                    <b>@lang('laprofile.user_name')</b>
                    <span class="_ttl122_custom">
                        {{ $user_name }}
                    </span>
                </div>
                <div class="_ttl123_custom">
                    <b>@lang('app.employee_code')</b>
                    <span class="_ttl122_custom">
                        {{ $user->code }}
                    </span>
                </div>
                <div class="_ttl123_custom">
                    <b>@lang('app.full_name')</b>
                    <span class="_ttl122_custom">
                        {{ $user->full_name }}
                    </span>
                </div>
                <div class="_ttl123_custom">
                    <b>@lang('app.title')</b>
                    <span class="_ttl122_custom">
                        {{ $user->title_name }}
                    </span>
                </div>
                <div class="_ttl123_custom">
                    <b>@lang('lamenu.unit')</b>
                    <span class="_ttl122_custom">
                        {{ $user->unit_name }}
                    </span>
                </div>
                <div class="_ttl123_custom">
                    <b>@lang('app.phone')</b>
                    <span class="_ttl122_custom">
                        {{ $user->phone }}
                    </span>
                </div>
                <div class="_ttl123_custom">
                    <b>Email</b>
                    <span class="_ttl122_custom">
                        {{ $user->email }}
                    </span>
                </div>
                {{-- ĐỔI MẬT KHẨU--}}
                <div class="container my-4">
                    <div class="row m-0">
                        <div class="col-12 text-center">
                            <a href="javascript:void(0);" onclick="changePassHandle('{{ route('themes.mobile.front.change_pass') }}')" class="btn w-100">
                                Đổi mật khẩu
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- color chooser menu -->
@include('themes.mobile.modal.colorscheme')

<!-- change language -->
@include('themes.mobile.modal.settings')

<!-- change avatar user -->
@include('themes.mobile.modal.change_avatar_user')

@include('themes.mobile.modal.filter_online')

<!-- jquery, popper and bootstrap js -->
<script src="{{ mix('themes/mobile/js/app_mobile_footer.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendor/fullcalendar/locales-all.js') }}" type="text/javascript"></script>

<div id="app-modal"></div>

@yield('footer')

</body>
</html>
