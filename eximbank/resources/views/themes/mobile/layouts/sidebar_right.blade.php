<div class="sidebar_right">
    @php
        $colorMenu = \App\Models\SettingColor::where('name','color_menu')->first();
        $backgroundMenu = \App\Models\Config::where('name', 'bg_menu')->first()->value;
        $colorMenu = $colorMenu->text;
    @endphp
    <style>
        .wrapped_text_icon {
            display: inline-block;
        }
        .icons-menu {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            float: left;
            background: {{ $colorMenu }};
        }
        .deeppurple-theme .sidebar_right .main-menu .list-group-item {
            color: {{ $colorMenu }}
        }
    </style>
    <div class="row">
        <div class="col pl-1 pr-0">
            <div class="list-group main-menu">
                @if(!in_array($routeName, ['themes.mobile.frontend.home', 'frontend.home']))
                    <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.home') }}')" class="list-group-item list-group-item-action">
                        <div class="icons-menu"
                            style="-webkit-mask: url({{ asset('themes/mobile/img/home.png') }}) no-repeat;
                            mask: url({{ asset('themes/mobile/img/home.png') }}) no-repeat;
                            -webkit-mask-size: 20px 20px;">
                        </div>
                        <span>Home</span>
                    </a>
                @endif
                @if(userThird())
                    <a href="javascript:void(0);" onclick="loadSpinner('{{ route('theme.mobile.news') }}')" class="list-group-item list-group-item-action">
                        <div class="icons-menu"
                            style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-3.svg') }}) no-repeat;
                            mask: url({{ asset('images/svg-frontend/svgexport-3.svg') }}) no-repeat;
                            -webkit-mask-size: 20px 20px;">
                        </div>
                        <span>@lang('app.news')</span>
                    </a>
                    <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.libraries') }}')" class="list-group-item list-group-item-action">
                        <div class="icons-menu"
                            style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-13.svg') }}) no-repeat;
                            mask: url({{ asset('images/svg-frontend/svgexport-13.svg') }}) no-repeat;
                            -webkit-mask-size: 20px 20px;">
                        </div>
                        <span>@lang('app.library')</span>
                    </a>
                    <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.forums') }}')" class="list-group-item list-group-item-action">
                        <div class="icons-menu"
                            style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-19.svg') }}) no-repeat;
                            mask: url({{ asset('images/svg-frontend/svgexport-19.svg') }}) no-repeat;
                            -webkit-mask-size: 20px 20px;">
                        </div>
                        <span>@lang('app.forum')</span>
                    </a>
                @endif
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.faq.frontend.index') }}')"  class="list-group-item list-group-item-action">
                    <div class="icons-menu"
                        style="-webkit-mask: url({{ asset('themes/mobile/img/design/faq.png') }}) no-repeat;
                        mask: url({{ asset('themes/mobile/img/design/faq.png') }}) no-repeat;
                        -webkit-mask-size: 20px 20px;">
                    </div>
                    <span>@lang('app.faq')</span>
                </a>
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.guide') }}')" class="list-group-item list-group-item-action">
                    <div class="icons-menu"
                        style="-webkit-mask: url({{ asset('themes/mobile/img/design/guide.png') }}) no-repeat;
                        mask: url({{ asset('themes/mobile/img/design/guide.png') }}) no-repeat;
                        -webkit-mask-size: 20px 20px;">
                    </div>
                    <span>@lang('app.guide')</span>
                </a>
                <a href="javascript:void(0)" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#colorscheme">
                    <div class="icons-menu"
                        style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-86.svg') }}) no-repeat;
                        mask: url({{ asset('images/svg-backend/svgexport-86.svg') }}) no-repeat;
                        -webkit-mask-size: 20px 20px;">
                    </div>
                    <span>@lang('app.color_scheme')</span>
                </a>
            </div>
        </div>
    </div>
</div>
