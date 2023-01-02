@extends('layouts.backend')

@section('page_title', trans('lamenu.setting'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('lamenu.setting') }}
        </h2>
    </div>
@endsection

@section('content')
    <div class="row mb-5 ml-2">
        @can('config')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.config') }}">
                    <img class="mb-1" src="{{ asset('images/icon_setting/config.png') }}" alt="">
                </a>
                <a href="{{ route('backend.config') }}">{{ trans('lasetting.generals_setting') }}</a>
            </div>
        </div>
        @endcan

        @can('config-email')
            <div class="col-md-2 mb-3">
                <div class="category-icon">
                    <a href="{{ route('backend.config.email.index') }}">
                        <img class="mb-1" src="{{ asset('images/icon_setting/email.png') }}" alt="">
                    </a>
                    <a href="{{ route('backend.config.email.index') }}">{{ trans('lasetting.email_configuration') }}</a>
                </div>
            </div>
        @endcan

        @can('config-login-image')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.login_image') }}">
                    <img class="mb-1" src="{{ asset('images/icon_setting/login_image.png') }}" alt="">
                </a>
                <a href="{{ route('backend.login_image') }}">{{ trans('lasetting.login_wallpaper') }}</a>
            </div>
        </div>
        @endcan

        @can('config-logo')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.logo') }}"><img class="mb-1" src="{{ asset('images/icon_setting/logo.png') }}" alt=""></a>
                <a href="{{ route('backend.logo') }}">{{ trans('lasetting.logo') }}</a>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.logo_outside') }}"><img class="mb-1" src="{{ asset('images/icon_setting/logo_outside.png') }}" alt=""></a>
                <a href="{{ route('backend.logo_outside') }}">{{ trans('lasetting.extenal_logo') }}</a>
            </div>
        </div>
        @endcan

        @can('config-favicon')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.logo.favicon') }}"><img class="mb-1" src="{{ asset('images/icon_setting/favicon.png') }}" alt=""></a>
                <a href="{{ route('backend.logo.favicon') }}">{{ trans('lasetting.favicon') }}</a>
            </div>
        </div>
        @endcan

		@can('config-app-mobile')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.app_mobile') }}"><img class="mb-1" src="{{ asset('images/icon_setting/app_mobile.png') }}" alt=""></a>
                <a href="{{ route('backend.app_mobile') }}">{{ trans('lasetting.app_mobile') }}</a>
            </div>
        </div>
        @endcan

		@can('config-notify-send')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('module.notify_send') }}"><img class="mb-1" src="{{ asset('images/icon_setting/notify_send.png') }}" alt=""></a>
                <a href="{{ route('module.notify_send') }}">{{trans('lasetting.notify')}}</a>
            </div>
        </div>
        @endcan

        @can('config-notify-template')
            <div class="col-md-2 mb-3">
                <div class="category-icon">
                    <a href="{{ route('module.notify.template') }}"><img class="mb-1" src="{{ asset('images/icon_setting/notification_template.png') }}" alt=""></a>
                    <a href="{{ route('module.notify.template') }}">{{ trans('lasetting.notification_template') }}</a>
                </div>
            </div>
        @endcan

        @can('mail-template')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.mailtemplate') }}"><img class="mb-1" src="{{ asset('images/icon_setting/mailtemplate.png') }}" alt=""></a>
                <a href="{{ route('backend.mailtemplate') }}">{{ trans('lasetting.mailtemplate') }}</a>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.mail_signature') }}"><img class="mb-1" src="{{ asset('images/icon_setting/mail_signature.png') }}" alt=""></a>
                <a href="{{ route('backend.mail_signature') }}">{{ trans('lasetting.email_signature') }}</a>
            </div>
        </div>
        @endcan

        @can('mail-template-history')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.mailhistory') }}"><img class="mb-1" src="{{ asset('images/icon_setting/mailhistory.png') }}" alt=""></a>
                <a href="{{ route('backend.mailhistory') }}">{{ trans('lasetting.mailhistory') }}</a>
            </div>
        </div>
        @endcan

        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.contact') }}"><img class="mb-1" src="{{ asset('images/icon_setting/contact.png') }}" alt=""></a>
                <a href="{{ route('backend.contact') }}">{{ trans('lasetting.contact') }}</a>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.google.map') }}"><img class="mb-1" src="{{ asset('images/icon_setting/training_position.png') }}" alt=""></a>
                <a href="{{ route('backend.google.map') }}">{{ trans('lasetting.training_position') }}</a>
            </div>
        </div>

        @can('banner')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.slider') }}"><img class="mb-1" src="{{ asset('images/icon_setting/banner.png') }}" alt=""></a>
                <a href="{{ route('backend.slider') }}">{{ trans('lasetting.banner') }}</a>
            </div>
        </div>
        @endcan

        @can('banner')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.slider_outside') }}"><img class="mb-1" src="{{ asset('images/icon_setting/slider_outside.png') }}" alt=""></a>
                <a href="{{ route('backend.slider_outside') }}">{{ trans('lasetting.extenal_banner') }}</a>
            </div>
        </div>
        @endcan

        @can('FAQ')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.infomation_company') }}"><img class="mb-1" src="{{ asset('images/icon_setting/infomation_company.png') }}" alt=""></a>
                <a href="{{ route('backend.infomation_company') }}">{{ trans('lasetting.company_info') }}</a>
            </div>
        </div>
        @endcan

        @can('config-login-image')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.banner_login_mobile') }}"><img class="mb-1" src="{{ asset('images/icon_setting/banner_login_mobile.png') }}" alt=""></a>
                <a href="{{ route('backend.banner_login_mobile') }}">{{ trans('lasetting.banner_login_mobile') }}</a>
            </div>
        </div>
        @endcan

        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.setting_color') }}"><img class="mb-1" src="{{ asset('images/icon_setting/setting_color.png') }}" alt=""></a>
                <a href="{{ route('backend.setting_color') }}">{{ trans('lasetting.button_setting_color') }}</a>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.languages') }}"><img class="mb-1" src="{{ asset('images/icon_setting/languages.png') }}" alt=""></a>
                <a href="{{ route('backend.languages') }}">{{ trans('lasetting.languages') }}</a>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.setting_time') }}"><img class="mb-1" src="{{ asset('images/icon_setting/setting_time.png') }}" alt=""></a>
                <a href="{{ route('backend.setting_time') }}">{{ trans('lasetting.setting_time') }} </a>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('module.botconfig') }}"><img class="mb-1" src="{{ asset('images/icon_setting/botconfig.png') }}" alt=""></a>
                <a href="{{ route('module.botconfig') }}">{{ trans('lasetting.setting_chatbot') }} </a>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.dashboard_by_user') }}"><img class="mb-1" src="{{ asset('images/icon_menu_backend/dashboard.png') }}" alt=""></a>
                <a href="{{ route('backend.dashboard_by_user') }}">Thống kê theo người</a>
            </div>
        </div>
        {{-- @can('footer')
        <div class="col-md-2 mb-3">
            <div class="category-icon">
                <a href="{{ route('backend.footer') }}"><i class="fas fa-bezier-curve"></i></a>
                <a href="{{ route('backend.footer') }}">Footer</a>
            </div>
        </div>
        @endcan --}}
        @if(\App\Models\Permission::isSuperAdmin())
            <div class="col-md-2 mb-3">
                <div class="category-icon">
                    <a href="{{ route('backend.cache') }}"><img class="mb-1" src="{{ asset('images/icon_setting/setting_time.png') }}" alt=""></a>
                    <a href="{{ route('backend.cache') }}">Xóa bộ nhớ đệm </a>
                </div>
            </div>
        @endif
    </div>
@endsection
