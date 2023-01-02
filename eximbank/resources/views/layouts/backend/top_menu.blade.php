@php
    $user_type = getUserType();
    $text_color_menu = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_menu->text;
    $hover_text_color_menu = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_menu->hover_text;
    $hover_background_menu = $get_color_menu->hover_background;
@endphp
<style>
    .active_unit,
    .title_unit_manager:hover {
        background: #eceef0;
        border-radius: 5px;
    }
    .wrapped_choose_unit_manager {
        border: 1px solid #d2d6de;
    }
    .wrapped_choose_unit_manager .choose_unit_manager {
        text-align: left;
        padding: 0px 5px;
        white-space: nowrap;
        width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .wrapped_choose_unit_manager span {
        line-height: 20px;
        font-size: 10px !important;
    }
    .vbm_btn{
        background: white;
    }
    .vbm_btn:hover{
        background: {{ $hover_background_menu }};
        color: {{ $hover_text_color_menu .'!important' }};
    }
    #infoDropdown {
        top: 15px !important;
    }
</style>
<header class="header clearfix top_menu_backend">
    <div class="row wrraped_header w-100 m-0">
        <div class="col-2 pr-0">
            <div class="main_logo" id="logo">
                <a href="/" class="w-100">
                    <img src="{{ image_file(@$logo->image, 'logo') }}" alt="" width="100%">
                </a>
            </div>
        </div>
        <div class="col-10">
            <div class="header_right">
                <ul>
                    @if (count($userUnits)>1)
                        {{-- <li style="width: 30%">
                            <select class="form-control" name="user-unit" id="user-unit-top" role="button" data-url="{{route('backend.save_select_unit')}}" style="font-size: 10px !important">
                                @foreach($userUnits as $index =>$item)
                                    {{$selected = $item->id==session('user_unit')?'selected':''}}
                                    <option value="{{$item->id}}" {{$selected}}>{{$item->name}}  - {{$item->code}}</option>
                                @endforeach
                            </select>
                        </li> --}}
                        <li style="width: 30%">
                            <div class="wrapped_choose_unit_manager cursor_pointer row d_flex_align m-0" onclick="chooseUnitManagerHandle()">
                                <div class="col-11 p-1 choose_unit_manager">
                                    @if (session()->has('user_unit_info'))
                                        <span>{{ session()->get('user_unit_info')->name }} - {{ session()->get('user_unit_info')->code  }}</span>
                                    @else
                                        <span>Chọn trưởng đơn vị</span>
                                    @endif
                                </div>
                                <div class="col-1 p-1 text-center">
                                    <i class="fas fa-caret-down"></i>
                                </div>
                            </div>
                            <input type="hidden" name="user-unit" id="user-unit-top" value="{{ session('user_unit') }}">
                        </li>
                    @endif
                    <li class="ui dropdown mx-2">
                        @php
                            $lang_types = \App\Models\LanguagesType::all();
                        @endphp
                        <a class="option_links" type="button" data-toggle="collapse" data-target="#languageDropdown" aria-controls="languageDropdown" aria-expanded="false">
                            <img src="{{ asset('images/design/language.png') }}" alt="" class="img-responsive" style="width: 21px;">
                        </a>
                        <div id="languageDropdown" class="dropdown-menu w_200" aria-labelledby="languageDropdown">
                            @if ($lang_types->count() > 0)
                                @foreach($lang_types as $lang_type)
                                    <div class="channel_my item all__noti5 {{ \App::getLocale() == $lang_type->key ? 'bg-info' : '' }}">
                                        <div class="profile_link">
                                            <a href="{{ route('change_language',['language'=>$lang_type->key]) }}" class="{{ \App::getLocale() == $lang_type->key ? 'text-white' : '' }}" data-turbolinks="false">
                                                <img src="{{ upload_file($lang_type->icon) ? upload_file($lang_type->icon) : asset($lang_type->icon) }}" alt=""> {{ $lang_type->name }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </li>
                    <li class="ui dropdown">
                        <a class="option_links dropdown-toggle cursor_pointer" id="notyDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="loadNotyHandle()">
                            @php
                                $count_noty = \Modules\Notify\Entities\NotifySend::countMessage();
                            @endphp
                            <img src="{{ asset('images/design/notification.svg') }}" alt="" class="img-responsive" style="width: 21px;">
                            <span class="noti_count">{{ $count_noty > 99 ? '99+' : $count_noty }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notyDropdown">
                            <div class="all_noty px-1" style="max-height: 500px; overflow-y: auto; width: 350px;">
                                <div class="loading">
                                </div>
                            </div>
                            <a class="dropdown-item text-center" href="{{ route('module.notify.index') }}">View All <i class='uil uil-arrow-right'></i></a>
                        </div>
                    </li>
                    <li class="mx-2">
                        @php
                            $promotion_user_point = \Modules\Promotion\Entities\PromotionUserPoint::whereUserId(profile()->user_id)->first(['point']);
                        @endphp
                        {{ $promotion_user_point ? $promotion_user_point->point : 0 }} <img src="{{ asset('images/level/point.png') }}" alt="" width="20px" height="20px">
                    </li>
                    <li class="mx-2 name_user">
                        @php
                            $t = date('H:i');
                            $localeLanguage = \App::getLocale();
                            $get_id_setting_object = '';
                            $get_time = '';
                            $check_all = \App\Models\SettingTimeObjectModel::where('object','All')->first();
                            $get_objects = \App\Models\SettingTimeObjectModel::where('object','!=','All')->get(['id', 'object']);
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
                                $findname   = '{Name}';
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
                                <span><strong style="color: white !important">{{$profile_view->firstname}}</strong></span>
                            @else
                                <span><strong>{{$profile_view->firstname}}</strong></span>
                            @endif

                        @endif
                    </li>
                    <li class="ui dropdown">
                        <a class="opts_account dropdown-toggle cursor_pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ $user_type == 1 ? image_user($profile_view->avatar) : asset('/images/design/user_50_50.png') }}" alt="">
                        </a>
                        <div id="infoDropdown" class="dropdown-menu w_200 dropdown_account dropdown-menu-right">
                            <div class="channel_my">
                                <div class="profile_link">
                                    <div class="pd_content">
                                        <div class="rhte85">
                                            <h6>{{ $profile_view->lastname }} {{ $profile_view->firstname }}</h6>
                                        </div>
                                        <span>{{ $profile_view->email }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('module.frontend.user.info') }}" class="dp_link_12">@lang('lamenu.user_info')</a>
                                @if (session('user_role')=='unit_manager')
                                    @if(\App\Models\Profile::hasRole())
                                        <a href="{{ route('module.dashboard') }}" class="dp_link_12 item channel_item link_permission" data-turbolinks="false">
                                            @lang('lamenu.admin_panel')
                                        </a>
                                    @endif
                                    @if(\App\Models\Permission::isTeacher())
                                        <a href="{{ route('backend.category.training_teacher.list_permission') }}" class="dp_link_12 item channel_item link_permission" data-turbolinks="false">
                                            @lang('laother.permission_teacher')
                                        </a>
                                    @endif
                                @elseif (session('user_role')=='manager')
                                    @if(\App\Models\Permission::isUnitManager($profile_view))
                                        <a href="{{ route('module.dashboard_unit') }}" class="dp_link_12 item channel_item link_permission" data-turbolinks="false">
                                            @lang('latraining.unit_chief')
                                        </a>
                                    @endif
                                    @if(\App\Models\Permission::isTeacher())
                                        <a href="{{ route('backend.category.training_teacher.list_permission') }}" class="dp_link_12 item channel_item link_permission" data-turbolinks="false">
                                            @lang('laother.permission_teacher')
                                        </a>
                                    @endif
                                @elseif (session('user_role')=='teacher')
                                    @if(\App\Models\Permission::isUnitManager($profile_view))
                                        <a href="{{ route('module.dashboard_unit') }}" class="dp_link_12 item channel_item link_permission" data-turbolinks="false">
                                            @lang('latraining.unit_chief')
                                        </a>
                                    @endif
                                    @if(\App\Models\Profile::hasRole())
                                        <a href="{{ route('module.dashboard') }}" class="dp_link_12 item channel_item link_permission" data-turbolinks="false">
                                            @lang('lamenu.admin_panel')
                                        </a>
                                    @endif
                                @endif
                                <a href="{{ route('logout') }}" class="dp_link_12 item channel_item">@lang('lamenu.logout')</a>
                            </div>
                            <div class="dropdown-item night_mode_switch__btn">
                                <div id="function-night-mode" class="cursor_pointer btn-night-mode p-3" onclick="nightModeHandle()">
                                    <i class="uil uil-moon icon"></i> Night mode
                                </div>
                            </div>
                        </div>

                        {{-- <a class="opts_account dropdown-toggle" type="button" data-toggle="collapse" data-target="#infoDropdown" aria-controls="infoDropdown" aria-expanded="false">
                            <img src="{{ $user_type == 1 ? image_user($profile_view->avatar) : asset('/images/design/user_50_50.png') }}" alt="">
                        </a>
                        <div id="infoDropdown" class="dropdown-menu w_200 dropdown_account" aria-labelledby="infoDropdown">
                            <div class="channel_my">
                                <div class="profile_link">
                                    <div class="pd_content">
                                        <div class="rhte85">
                                            <h6>{{ $profile_view->lastname }} {{ $profile_view->firstname }}</h6>
                                        </div>
                                        <span>{{ $profile_view->email }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('module.frontend.user.info') }}" class="dp_link_12">@lang('lamenu.user_info')</a>
                                @if (session('user_role')=='unit_manager')
                                    @if(\App\Models\Profile::hasRole())
                                        <a href="{{ route('module.dashboard') }}" class="dp_link_12 item channel_item link_permission" data-turbolinks="false">
                                            @lang('lamenu.admin_panel')
                                        </a>
                                    @endif
                                    @if(\App\Models\Permission::isTeacher())
                                        <a href="{{ route('backend.category.training_teacher.list_permission') }}" class="dp_link_12 item channel_item link_permission" data-turbolinks="false">
                                            @lang('laother.permission_teacher')
                                        </a>
                                    @endif
                                @elseif (session('user_role')=='manager')
                                    @if(\App\Models\Permission::isUnitManager($profile_view))
                                        <a href="{{ route('module.dashboard_unit') }}" class="dp_link_12 item channel_item link_permission" data-turbolinks="false">
                                            @lang('latraining.unit_chief')
                                        </a>
                                    @endif
                                    @if(\App\Models\Permission::isTeacher())
                                        <a href="{{ route('backend.category.training_teacher.list_permission') }}" class="dp_link_12 item channel_item link_permission" data-turbolinks="false">
                                            @lang('laother.permission_teacher')
                                        </a>
                                    @endif
                                @elseif (session('user_role')=='teacher')
                                    @if(\App\Models\Permission::isUnitManager($profile_view))
                                        <a href="{{ route('module.dashboard_unit') }}" class="dp_link_12 item channel_item link_permission" data-turbolinks="false">
                                            @lang('latraining.unit_chief')
                                        </a>
                                    @endif
                                    @if(\App\Models\Profile::hasRole())
                                        <a href="{{ route('module.dashboard') }}" class="dp_link_12 item channel_item link_permission" data-turbolinks="false">
                                            @lang('lamenu.admin_panel')
                                        </a>
                                    @endif
                                @endif
                                <a href="{{ route('logout') }}" class="dp_link_12 item channel_item">@lang('lamenu.logout')</a>
                            </div>

                            <div class="night_mode_switch__btn">
                                <div id="function-night-mode" class="cursor_pointer btn-night-mode p-3" onclick="nightModeHandle()">
                                    <i class="uil uil-moon icon"></i> Night mode
                                </div>
                            </div>
                        </div> --}}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
<div class="modal fade" tabindex="-1" role="dialog" id="modal-select-unit" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('laother.choose_unit') }}</h4>
            </div>
            <form action="{{route('backend.save_select_unit')}}" id="frm-unit-manager-select" method="post" >
                @csrf
                <div class="modal-body"> 
                    @foreach($userUnits as $index => $item)
                        <div class="">
                            <div class="row d_flex_align m-0">
                                <div class="col-1 pr-1 text-center">
                                    @if ($item->type_manager == 2)
                                        <i class="fas fa-plus-square cursor_pointer icon_show_{{ $item->id }}" onclick="showChildUnitManager({{ $item->id }})"></i>
                                    @else
                                        <i class="fas fa-minus-square cursor_pointer icon_show_{{ $item->id }}"></i>
                                    @endif
                                </div>
                                <div class="col-11 pl-0 title_unit_manager">
                                    <input type="hidden" class="show_child_{{ $item->id }}" value="0">
                                    <p class="px-2 mb-0 cursor_pointer title_unit_manager_{{ $item->id }}" onclick="setUnitManagerHandle({{ $item->id }}, '')">
                                        <span>{{$item->name}}  - {{$item->code}}</span>
                                    </p>
                                </div>
                                <div class="row ml-3 mr-0 w-100 wrapped_child_unit_manager child_unit_manager_{{ $item->id }}">

                                </div>
                            </div>
                        </div>
                    @endforeach
                    <input type="hidden" name="unit_select" id="unit_select" value="">
                     <input type="hidden" name="role-select" value="{{$userUnits[0]->type}}" />
                </div>

                 <div class="modal-footer">
                    <button type="submit" class="btn" id=""><i class="fa fa-check-circle"></i> {{ trans('latraining.choose') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade " data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" id="modal-select-role" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('laother.choose_role') }}</h4>
            </div>
            <form action="{{route('backend.save_select_role')}}" id="frm-role-select" method="post" class="form-ajax">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 block-left">
                            <label>{{ trans('laother.choose_role_perform') }}</label>
                        </div>
                        <div class="col-md-7">
                            <select class="form-control" name="role-select" role="button">
                                @foreach($userRoles as $index =>$item)
                                    <option value="{{$item->role}}"  >{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn" ><i class="fa fa-check-circle"></i> {{ trans('latraining.choose') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    let url_modal_unit = '{{route('backend.check_select_unit')}}';
    /*$(window).on('load',function() {
        $.ajax({
            url: url_modal_unit,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'post',
            data: {},
        }).done(function(data) {
            console.log(data);
            if(data.modal){
                if(data.type=='unit')
                    $('#modal-select-unit').modal();
                else
                    $('#modal-select-role').modal();
            }

            return false;
        }).fail(function(data) {
            return false;
        });

    });*/
    // $('select[name=unit-select]').on('change',function () {
    //     $role_select = $('option:selected',this).attr('data-role');
    //     $('#frm-unit-manager-select input[name=role-select]').val( $role_select );
    // })

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

    var flag = 0;
    function loadNotyHandle() {
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
                        html += `<div class="">
                                        `+ (note.important == 1 ? '<i class="uil uil-star text-warning"></i>' : '') +`
                                        <a class="dropdown-item" href="`+ note.link +`">
                                            <div class="pd_content row m-0">
                                                `+ html2 +`
                                                <div class="pl-2 pr-1 `+ (note.viewed != 1 ? 'col-10' : 'col-12') +`">
                                                    <h6 class="title_noty">
                                                        <span class="`+ (note.viewed == 1 ? 'text-black-50' : ' font-weight-bold') +`" >
                                                            `+ note.subject +`
                                                        </span>
                                                    </h6>
                                                    <span class="nm_time">
                                                        `+ note.created_at2 +`
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
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

    function chooseUnitManagerHandle() {
        $('#modal-select-unit').modal();
    }

    function setUnitManagerHandle(id, type) {
        let item = $('.title_unit_manager_'+ id);
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');

        $('#frm-unit-manager-select input[name=role-select]').val(type);
        $('#unit_select').val(id)
        $('#frm-unit-manager-select').submit();
    }

    function showChildUnitManager(id) {
        var show_child = $('.show_child_'+ id).val()
        if(show_child == 1) {
            $('.show_child_'+ id).val(0)
            $('.icon_show_'+ id).addClass('fa-plus-square')
            $('.icon_show_'+ id).removeClass('fa-minus-square')
            $('.child_unit_manager_'+ id).html('')
        } else {
            $.ajax({
                url: '{{ route('backend.show_child_unit_manager') }}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'post',
                data: {
                    parent_id: id,
                },
            }).done(function(data) {
                $('.show_child_'+ id).val(1)
                $('.icon_show_'+ id).removeClass('fa-plus-square')
                $('.icon_show_'+ id).addClass('fa-minus-square')
                let html = ''
                let childs = data.childs
                childs.forEach(element => {
                    html += `<div class="col-12">
                                <div class="row d_flex_align">
                                    <div class="col-1 py-1 pr-2 text-right">
                                        <i class="fas fa-plus-square cursor_pointer icon_show_`+ element.id +`" onclick="showChildUnitManager(`+ element.id +`)"></i>
                                    </div>
                                    <div class="col-11 pl-0 title_unit_manager">
                                        <input type="hidden" class="show_child_`+ element.id +`" value="0">
                                        <p class="px-2 mb-0 cursor_pointer name_unit title_unit_manager_`+ element.id +`" onclick="setUnitManagerHandle(`+ element.id +`)">
                                            <span>`+ element.name +` - `+ element.code +`</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row ml-3 mr-0 w-100 wrapped_child_unit_manager child_unit_manager_`+ element.id +`"></div>`
                });
                $('.child_unit_manager_'+ id).html(html)
                return false;
            }).fail(function(data) {
                return false;
            });
        }
    }
</script>
