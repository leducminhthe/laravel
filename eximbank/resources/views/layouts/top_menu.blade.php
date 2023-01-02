@php
    $user_id = getUserId();
    $user_type = getUserType();
    $user_secondary = \Modules\Quiz\Entities\QuizUserSecondary::find($user_id);
    $profile_view =  \App\Models\ProfileView::select('id','avatar','unit_id','firstname','email','full_name')->where('user_id', $user_id)->first();

    $check_unit = 0;
    if (!empty($logo->object) && !empty($profile_view)) {
        $check_objects = json_decode($logo->object);
        foreach ($check_objects as $check_object) {
            $unit_code = \App\Models\Categories\Unit::select(['code','id'])->find($check_object);
            $get_array_childs = \App\Models\Categories\Unit::getArrayChild($unit_code->code);
            if( in_array($profile_view->unit_id, $get_array_childs) || ($profile_view->unit_id == $unit_code->id) ) {
                $check_unit = 1;
            }
        }
    }

    $get_color_menu = \App\Models\SettingColor::where('name','color_menu')->first();

    $text_color_menu = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_menu->text;
    $hover_text_color_menu = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_menu->hover_text;
    $hover_background_menu = $get_color_menu->hover_background;
@endphp
<style>
    .vbm_btn{
        background: white;
    }
    .vbm_btn:hover{
        background: {{ $hover_background_menu }};
        color: {{ $hover_text_color_menu .'!important' }};
    }
    .dp_link_12 {
        width: max-content;
    }
    .dropdown_menu_user .dropdown-menu {
        min-width: 160px;
    }
</style>
<header class="header clearfix top_menu row m-0">
    <div class="wrapped_logo_btn col-auto">
        <div class="row">
            <div class="wrraped_button_vertical_menu_left_scroll col-auto p-0">
                <button class="btn btn_vertical_menu_frontend" id="btn_3_gach_left_menu">
                    <i class="uil uil-bars collapse_menu--icon "></i>
                    <span class="collapse_menu--label"></span>
                </button>
            </div>
            <div class="col-auto p-0">
                @if ((!empty($logo->object) && $check_unit == 1) || empty($logo->object))
                    <div class="main_logo" id="logo">
                        <a href="/" class="w-100"><img src="{{ image_file(@$logo->image, 'logo') }}" alt=""></a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="wrraped_breaking_new col-4 pr-0">
        <div class="row ml-0 w-100">
            <div class="col-auto p-0">
                <span class="breaking_new">{{ trans('laother.latest_new') }}</span>
            </div>
            <div class="col-9 px-1 all_title_new">
                <div id="carouselExampleControls" class="carousel slide w-100" data-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($news_created_at as $key => $item)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}" data-interval="10000">
                                <a href="/news-react/detail/{{ $item->id }}">
                                    <span class="time_new">{{ $item->created_at2 }} - </span>
                                    <span class="title_new">{{ $item->title }}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header_right col text-right">
        <ul>
            <li class="button_slide_carousel_news">
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
            <li class="ui dropdown dropdown_noty">
                @php
                    $lang_types = \App\Models\LanguagesType::all();
                @endphp
                <a onclick="myFunctionLang()" class="dropbtn option_links" id="all_lang">
                    <img src="{{ asset('images/design/language.png') }}" alt="" class="img-responsive" style="width: 21px;">
                </a>
                <div id="myDropdownLang" class="dropdown-content w-auto menu dropdown_mn text-left">
                    @if ($lang_types->count() > 0)
                        @foreach($lang_types as $lang_type)
                            <div class="pd_content p-2 {{ \App::getLocale() == $lang_type->key ? 'bg-info' : '' }}">
                                <a href="{{ route('change_language',['language'=> $lang_type->key]) }}" class="{{ \App::getLocale() == $lang_type->key ? 'text-white' : '' }}" data-turbolinks="false">
                                    <img src="{{ upload_file($lang_type->icon) ? upload_file($lang_type->icon) : asset($lang_type->icon) }}" alt=""> {{ $lang_type->name }}
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </li>
            @if($user_type == 1)
                <li class="ui dropdown dropdown_noty">
                    <a onclick="loadNotyHandle()" class="dropbtn option_links cursor_pointer" id="all_noty">
                        @php
                            $count_noty = \Modules\Notify\Entities\NotifySend::countMessage();
                        @endphp
                        <img src="{{ asset('images/design/notification.svg') }}" alt="" class="img-responsive" style="width: 21px;">
                        <span class="noti_count">{{ $count_noty > 99 ? '99+' : $count_noty }}</span>
                    </a>
                    <div id="myDropdown" class="dropdown-content w-auto menu dropdown_mn">
                        <div class="all_noty">
                            <div class="loading">
                                
                            </div>
                        </div>
                        <a class="vbm_btn" href="{{ route('module.notify.index') }}">View All <i class='uil uil-arrow-right'></i></a>
                    </div>
                </li>

                <li class="mx-2 point_user">
                    @php
                        $promotion_user_point = \Modules\Promotion\Entities\PromotionUserPoint::whereUserId(profile()->user_id)->first(['point']);
                    @endphp
                    {{ $promotion_user_point ? $promotion_user_point->point : 0 }} <img src="{{ asset('images/level/point.png') }}" alt="" width="20px" height="20px">
                </li>
            @endif
            <li class="mx-2 name_user">
                @php
                    $t = date('H:i');
                    $localeLanguage = \App::getLocale();
                    $get_id_setting_object = '';
                    $get_time = '';
                    $check_all = \App\Models\SettingTimeObjectModel::where('object','All')->first();
                    $get_objects = \App\Models\SettingTimeObjectModel::where('object','!=','All')->get(['id','object']);
                    foreach ($get_objects as $key => $get_object) {
                        $objects = json_decode($get_object->object);
                        if (!empty($profile_view) && in_array($profile_view->unit_id, $objects)) {
                            $get_id_setting_object = $get_object->id;
                        }
                    }
                    if ($check_all && !$get_id_setting_object) {
                        $get_time = \App\Models\SettingTimeModel::where('object',$check_all->id)->where('start_time','<=',$t)->where('end_time','>=',$t)->first();
                    } elseif ($get_id_setting_object) {
                        $get_time = \App\Models\SettingTimeModel::where('object',$get_id_setting_object)->where('start_time','<=',$t)->where('end_time','>=',$t)->first();
                    }
                @endphp
                @if (!empty($get_time))
                    @php
                        $findname = '{Name}';
                        $get_value = \App\Models\SettingTimeValueLanguages::where('setting_time_id', $get_time->id)->where('languages', $localeLanguage)->first(['value']);
                        $pos = str_contains($get_value->value, $findname);
                        if($pos) {
                            $name = '<span class="name_user_menu">'. $profile_view->firstname .'</span>';
                            $formatText = str_replace("{Name}", $name, $get_value->value);
                        } else {
                            $formatText = $get_value->value. ', '. $profile_view->firstname;
                        }
                    @endphp
                    @if (session()->exists('nightMode') && session()->get('nightMode') == 1)
                        <span style="color: white">
                            {!! $formatText !!}
                        </span>
                    @else
                        @if ($get_time->i_text && empty($get_time->b_text))
                            <i>
                                <span style="color: {{ $get_time->color_text ? $get_time->color_text : ''}}">
                                    {!! $formatText !!}
                                </span>
                            </i>
                        @elseif (empty($get_time->i_text) && $get_time->b_text)
                            <b style="color: {{ $get_time->color_text ? $get_time->color_text : ''}} !important">
                                <span>
                                    {!! $formatText !!}
                                </span>
                            </b>
                        @elseif ($get_time->i_text && $get_time->b_text)
                            <i>
                                <b style="color: {{ $get_time->color_text ? $get_time->color_text : ''}} !important">
                                    {!! $formatText !!}
                                </b>
                            </i>
                        @else
                            <span style="color: {{ $get_time->color_text ? $get_time->color_text : ''}}">
                                {!! $formatText !!}
                            </span>
                        @endif
                    @endif
                @else
                    @if (session()->exists('nightMode') && session()->get('nightMode') == 1)
                        <span><strong style="color: white !important">{{ $user_type == 1 ? $profile_view->firstname : @$user_secondary->name }}</strong></span>
                    @else
                        <span><strong>{{ $user_type == 1 ? $profile_view->firstname : @$user_secondary->name }}</strong></span>
                    @endif
                @endif
            </li>
            <li class="ui dropdown mr-1">
                <div class="dropdown dropdown_menu_user">
                    <a class="opts_account" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ $user_type == 1 ? image_user($profile_view->avatar) : asset('/images/design/user_50_50.png') }}" alt="">
                    </a>
                    <div class="dropdown-menu w_auto dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <div class="channel_my">
                            @if($user_type == 1)
                                <div class="profile_link w-100">
                                    <div class="pd_content">
                                        <div class="rhte85">
                                            <h6 class="mt-0">{{ $profile_view->full_name }}</h6>
                                        </div>
                                        <span>{{ $profile_view->email }}</span>
                                    </div>
                                </div>
                                <div class="mt-2 point_user_mobile">
                                    <span>{{ $promotion_user_point ? $promotion_user_point->point : 0 }}</span>
                                    <img src="{{ asset('images/level/point.png') }}" width="20px" height="20px">
                                </div>
                                <a href="{{ route('module.frontend.user.info') }}" class="dp_link_12">@lang('lamenu.user_info')</a>

                                {{-- User Admin, User có quyền, User TĐV --}}
                                @if(\App\Models\Permission::isAdmin() || \App\Models\Permission::isTeacher() ||  \App\Models\Profile::hasRole() || \App\Models\Permission::isUnitManager())

                                    {{-- User vai trò TĐV, không có phân quyền gì khác --}}
                                    @if(\App\Models\Permission::isUnitManager())
                                        <a href="{{ route('module.dashboard_unit') }}" class="dp_link_12 item channel_item link_permission" data-turbolinks="false">
                                            @lang('latraining.unit_chief')
                                        </a>
                                    @endif
                                    {{-- User trong danh mục GV, không có quyền gì --}}
                                    @if (\App\Models\Permission::isTeacher())
                                        <a href="{{ route('backend.category.training_teacher.list_permission') }}" class="dp_link_12 item channel_item link_permission" data-turbolinks="false">
                                            {{ trans('laother.permission_teacher') }}
                                        </a>
                                    @endif
                                    {{-- User Admin, User có quyền --}}
                                    @if(\App\Models\Profile::hasRole())
                                        <a href="{{ route('module.dashboard') }}" class="dp_link_12 item channel_item link_permission" data-turbolinks="false">
                                            @lang('lamenu.admin_panel')
                                        </a>
                                    @endif
                                @endif
                            @endif
                            <a href="{{ route('logout') }}" class="dp_link_12 item channel_item">@lang('lamenu.logout')</a>
                        </div>
                        <div class="night_mode_switch__btn">
                            <div id="function-night-mode" class="cursor_pointer btn-night-mode p-3" onclick="nightModeHandle()">
                                <i class="uil uil-moon icon"></i> Night mode
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</header>
<script>
    // Add slideDown animation to Bootstrap dropdown when expanding.
    $('.dropdown_menu_user').on('show.bs.dropdown', function() {
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
    });

    // Add slideUp animation to Bootstrap dropdown when collapsing.
    $('.dropdown_menu_user').on('hide.bs.dropdown', function() {
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
    });

    var flag = 0;
    function loadNotyHandle() {
        $("#myDropdown").slideToggle();
        if(flag == 0 ) {
            var html = '';
            let loading = '<i class="fa fa-spinner fa-spin"></i>';
            $('.loading').html(loading);
            $.ajax({
                type: 'POST',
                url: "{{ route('module.notify.get_noty_menu') }}",
            }).done(function(data) {
                $('.loading').html('');
                if(data.noties.length > 0) {
                    data.noties.forEach(note => {
                        var html2 = '';
                        if(note.viewed != 1) {
                            html2 = `<div class="col-2 pl-1 pr-0">
                                            <img src="{{ asset('images/noty.png') }}" width="100%">
                                        </div>`
                        } 
                        html += `<div class="channel_my item all__noti5">
                                    <div class="">
                                        `+ (note.important == 1 ? '<i class="uil uil-star text-warning"></i>' : '') +`
                                        <a href="`+ note.link +`">
                                            <div class="pd_content row m-0">
                                                `+ html2 +`
                                                <div class="pl-2 pr-1 `+ (note.viewed != 1 ? 'col-10' : 'col-12') +`">
                                                    <h6 class="title_noty">
                                                        <span class="`+ (note.viewed == 1 ? 'text-black-50' : 'text-black font-weight-bold') +`" >
                                                            `+ note.subject +`
                                                        </span>
                                                    </h6>
                                                    <span class="nm_time">
                                                        `+ note.created_at2 +`
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>` 
                    });
                    $('.all_noty').html(html);
                    flag = 1;
                }
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }
    }
    function myFunctionLang() {
        $("#myDropdownLang").slideToggle();
    }

    window.addEventListener('click', function(e) {
        if (!document.getElementById('all_noty').contains(e.target) && $("#myDropdown:visible").length > 0) {
            $("#myDropdown").slideToggle();
        }
        if (!document.getElementById('all_lang').contains(e.target) && $("#myDropdownLang:visible").length > 0) {
            $("#myDropdownLang").slideToggle();
        }
    });

    function nightModeHandle() {
        $.ajax({
            type: 'POST',
            url: "{{ route('setting_night_mode') }}",
        }).done(function(data) {
            location.reload();
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    }
</script>

