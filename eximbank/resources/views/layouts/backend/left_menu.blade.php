@php
    $text_color_menu = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_menu->text;
    $text_color_menu_active = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_menu->active;
    $hover_text_color_menu = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_menu->hover_text;
    $background_menu = $get_color_menu->background;
    $background_menu_child = $get_color_menu->background_child;
    $hover_background_menu = $get_color_menu->hover_background;
@endphp
<style type="text/css">
    .all_item_menu_backend .icon_menu_svg {
        background-color: {{ $text_color_menu }}
    }
    .sub_menu--link:hover .icon_menu_svg,
    .menu_child--item:hover .menu_child--link .icon_menu_svg,
    .all_item_menu_backend .menu--item:hover .icon_menu_svg{
        background-color: {{ $hover_text_color_menu }}
    }
    .sub_menu--link span,
    .menu_child--item .menu_child--link span,
    .menu--item .menu--link .tilte_item_menu span {
        color: {{ $text_color_menu }};
    }
    .active_menu_parent .icon_menu_svg {
        background-color: {{ $text_color_menu_active }};
    }
    .all_item_menu_backend .active_menu_parent .menu--link .tilte_item_menu span {
        color: {{ $text_color_menu_active }};
    }
    .all_item_menu_backend .active_menu_parent {
        background: {{ $background_menu }};
    }
    .menu--item:hover,
    .menu_child--item:hover,
    .sub_menu--link:hover {
        border-radius: 10px;
        background: {{ $hover_background_menu }};
    }
    .sub_menu--link:hover span,
    .menu_child--item:hover .menu_child--link span,
    .menu--item:hover .tilte_item_menu  span{
        color: {{ $hover_text_color_menu . ' !important' }};
    }
    .all_item_menu_backend .active_menu_child > a {
        border-right: 5px solid {{ $background_menu }};
    }
    .all_item_menu_backend  .menu_child {
        background: {{ $background_menu_child }};
    }
</style>

<div class="search_button_vertical bg-white mt-0">
    <button id="collapse_menu" class="collapse_menu">
        <i class="uil uil-bars collapse_menu--icon"></i>
        <span class="collapse_menu--label"></span>
    </button>
    @if (userCan('user') || \App\Models\Permission::isUnitManager($profile_view))
    <div class="wrapped_search_user" style="margin-top: 8px;">
        <input type="text" name="search_user" class="search_user border p-2 rounded form_search_user" style="width:60%; display:none; font-size: x-small; margin-top:3px;" placeholder="Nhập MSNV / Họ tên"/>
        <button type="button" class="btn search_user show_search_user" id="btn_search_user" style="float: right;margin-right: 3px;">
            <i class="fas fa-search"></i>
        </button>
    </div>
    @endif
</div>

{!! \App\Helpers\MenuHelper\BackendMenuLeft::render() !!}

<script>
    var get_hover_color = '{{ $hover_background_menu }}';
    
    $('#collapse_menu').on('click',function() {
        $('.menu_child').removeClass('show_menu_child_backend');
        $('.menu_child').hide();
        if ($('.menu_left_backend').hasClass('w_50')) {
            $('.menu_left_backend').removeClass('w_50').addClass('w_230');
            $('.tilte_item_menu').show();
            $('.menu--item').removeClass('w_50');
            $('.body_content').css('left', '220px');
            open_close_menu(1);
        } else {
            $('.menu_left_backend').removeClass('w_230').addClass('w_50');
            $('.tilte_item_menu').hide();
            $('.menu--item').addClass('w_50');
            $('.body_content').css('left', '50px');
            open_close_menu(0);
        }
    })

    $("#btn_search_user").on('click', function(){
        if($(this).hasClass('show_search_user')){
            $('.form_search_user').show();
            $(this).removeClass('show_search_user');
        }else{
            var search = $('input[name=search_user]').val();
            if(search){
                console.log(search);
                $.ajax({
                    type: "POST",
                    url: "{{ route('frontend.search_user') }}",
                    data:{
                        search: search
                    },
                    success: function (data) {
                        if (data) {
                            console.log(data);

                            if(data.redirect){
                                window.location = data.redirect;
                            }else{
                                show_message('Không tồn tại nhân viên', 'warning');
                            }

                            return false;
                        } else {
                            console.log('Lỗi hệ thống');

                            return false;
                        }
                    }
                });
            }else{
                $('.form_search_user').hide();
                $(this).addClass('show_search_user');
            }
        }
    });

    function open_close_menu(status) {
        $.ajax({
            url: "{{ route('backend.close_open_menu') }}",
            type: 'post',
            data: {
                status: status,
            }
        }).done(function(data) {
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    }
</script>
