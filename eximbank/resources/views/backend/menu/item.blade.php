@php
    $active_quiz = 0;
    $tabs = Request::segment(2);
    $user_type = \Modules\Quiz\Entities\Quiz::getUserType();
    $tab_3 = Request::segment(3);
    $tab_4 = Request::segment(4);
    if ($tabs == 'quiz' && ($tab_3 == 'edit' || $tab_3 == 'result' || $tab_3 == 'register' || $tab_3 == 'user-secondary' || $tab_4 == 'add-question' || $tab_4 == 'review-quiz')) {
        $active_quiz = 1;
    }
    $url_domain = parse_url(request()->url());
@endphp

<div class="wrrpaed_menu_left_backend">
    <div class="menu_left_backend {{ session()->exists('close_open_menu_backend') && session()->get('close_open_menu_backend') == 0 ? 'w_50' : '' }}">
        <div class="wrapped_menu">
            <ul class="all_item_menu_backend">
                @foreach($items as $key => $item)
                    @if(!$item['permission'])
                        @continue
                    @endif
                    @if(!isset($item['items']))
                        <li class="cursor_pointer d_flex_align {{ session()->exists('close_open_menu_backend') && session()->get('close_open_menu_backend') == 0 ? 'w_50' : '' }} menu--item menu--item__has_sub_menu
                            @if ($item['url_name'] == $tabs)
                                active_menu_parent have_child_active
                            @endif"
                        >
                            <a href="{{ $item['url'] }}" class="menu--link mb-0 pl-2 d_flex_align w-100">
                                <div class="icon_menu_svg" 
                                    style="-webkit-mask: url({{ $item['icon'] }}) no-repeat; 
                                    mask: url({{ $item['icon'] }}) no-repeat;
                                    -webkit-mask-size: 20px 20px;">
                                </div>
                                <div class="tilte_item_menu ml-1 {{ session()->exists('close_open_menu_backend') && session()->get('close_open_menu_backend') == 0 ? 'd_none' : '' }}">
                                    <span class="menu--label">{{ $item['name'] }}</span>
                                </div>
                            </a>
                        </li>
                    @else
                        <div class="warraped_menu_item">
                            <li class="cursor_pointer d_flex_align {{ session()->exists('close_open_menu_backend') && session()->get('close_open_menu_backend') == 0 ? 'w_50' : '' }}
                                menu--item menu--item__has_sub_menu
                                @if (in_array($tabs, $item['url_name_child']) && $tabs != 'quiz' && $tabs != 'dashboard')
                                    active_menu_parent have_child_active
                                @elseif ($tabs == 'quiz' && in_array($tab_3, $item['url_name_child']))
                                    active_menu_parent have_child_active
                                @elseif ($tabs == 'report-new' && in_array($tab_3, $item['url_name_child']))
                                    active_menu_parent have_child_active
                                @endif"
                                id="menu_{{$key}}" onclick="showMenuChild('{{$key}}')"
                            >
                                <div class="icon_menu_svg ml-2" 
                                    style="-webkit-mask: url({{ $item['icon'] }}) no-repeat; 
                                    mask: url({{ $item['icon'] }}) no-repeat;
                                    -webkit-mask-size: 20px 20px;">
                                </div>
                                <label class="menu--link mb-0 pl-2">
                                    <div class="tilte_item_menu {{ session()->exists('close_open_menu_backend') && session()->get('close_open_menu_backend') == 0 ? 'd_none' : '' }}">
                                        <span class="menu--label">{{ $item['name'] }}</span>
                                        <i class="fa fa-chevron-down icon_show_down icon_show_{{$key}}"></i>
                                    </div>
                                </label>
                            </li>
                            <ul class="menu_child child_{{$key}}">
                                @foreach ($item['items'] as $menu_child)
                                    @if(!$menu_child['permission'])
                                        @continue
                                    @endif
    
                                    @if (!isset($menu_child['item_childs']))
                                        <li class="cursor_pointer menu_child--item
                                            @if (isset($menu_child['url_name']) && ($menu_child['url_name'] == $tabs) && $tabs != 'quiz' && $tabs != 'dashboard')
                                                active_menu_child
                                            @elseif (isset($menu_child['url_name']) && $tabs == 'quiz' && ($menu_child['url_name'] == $tab_3))
                                                active_menu_child
                                            @elseif (isset($menu_child['url_name']) && $menu_child['url_name'] == 'all' && $active_quiz == 1)
                                                active_menu_child
                                            @elseif (isset($menu_child['url_name']) && $tabs == 'training-unit' && ($menu_child['url_name'] == $tab_3))
                                                active_menu_child
                                            @elseif (isset($menu_child['url_name']) && $tabs == 'libraries' && ($menu_child['url_name'] == $tab_3) && !$tab_4)
                                                active_menu_child
                                            @elseif (isset($menu_child['url_name']) && $tabs == 'libraries' && ($menu_child['url_name'] == $tab_4))
                                                active_menu_child
                                            @elseif (isset($menu_child['url_name']) && $tabs == 'report-new' && ($menu_child['url_name'] == $tab_3))
                                                active_menu_child
                                            @endif
                                        ">
                                            <a href="{{ $menu_child['url'] }}" class="menu_child--link">
                                                <div class="icon_menu_svg mr-1" 
                                                    style="-webkit-mask: url({{ $menu_child['icon'] }}) no-repeat; 
                                                    mask: url({{ $menu_child['icon'] }}) no-repeat;
                                                    -webkit-mask-size: 20px 20px;">
                                                </div>
                                                <span>{{ $menu_child['name'] }}</span>
                                            </a>
                                        </li>
                                    @else
                                        <li class="cursor_pointer menu_child--item 
                                            @if (isset($menu_child['url_item_child']) && $tabs != 'category' && in_array($tabs, $menu_child['url_item_child']))
                                                active_menu_child
                                            @elseif (isset($menu_child['url_item_child']) && $tabs == 'category' && in_array($tab_3, $menu_child['url_item_child']))
                                                active_menu_child
                                            @elseif (isset($menu_child['url_item_child']) && $tabs == 'quiz' && in_array($tab_3, $menu_child['url_item_child']))
                                                active_menu_child
                                            @elseif (isset($menu_child['url_item_child']) && $tabs == 'report-new' && in_array($tab_4, $menu_child['url_item_child']))
                                                active_menu_child
                                            @endif"
                                            id="{{ $menu_child['id'] }}"
                                            onmouseover="hoverSubmenu('{{ $menu_child['id'] }}')"
                                            onmouseout="outHover('{{ $menu_child['id'] }}')"
                                        >
                                            <a href="{{ $menu_child['url'] }}" class="menu_child--link">
                                                <div class="icon_menu_svg mr-1" 
                                                    style="-webkit-mask: url({{ $menu_child['icon'] }}) no-repeat; 
                                                    mask: url({{ $menu_child['icon'] }}) no-repeat;
                                                    -webkit-mask-size: 20px 20px;">
                                                </div>
                                                <span>{{ $menu_child['name'] }}</span>
                                            </a>
                                            <i class="fa fa-chevron-right"></i>
                                            <ul class="sub_menu_child {{ $key == 'report' ? 'list_report' : '' }}">
                                                @foreach ($menu_child['item_childs'] as $sub)
                                                    @if(!$sub['permission'])
                                                        @continue
                                                    @endif
                                                    <li class="cursor_pointer sub_menu--item
                                                        @if ($sub['url'] == $url_domain['path']) 
                                                            active_menu_child 
                                                        @endif
                                                    ">
                                                        <a href="{{ $sub['url'] }}" class="sub_menu--link">
                                                            <div class="icon_menu_svg mr-1" 
                                                                style="-webkit-mask: url({{ $sub['icon'] }}) no-repeat; 
                                                                mask: url({{ $sub['icon'] }}) no-repeat;
                                                                -webkit-mask-size: 20px 20px;">
                                                            </div>
                                                            <span>{{ $sub['name'] }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>


<script>
	function hoverSubmenu(id) {
        $('#'+ id).find('.sub_menu_child').show();
        $('.all_item_menu_backend').addClass('w_500');
    }
    function outHover(id) {
        $('#'+ id).find('.sub_menu_child').hide();
        if (!$('.menu_left_backend').hasClass('w_50')) {
            $('.all_item_menu_backend').removeClass('w_500');
        }
    }

    var menu_clicked = localStorage.getItem("menu_click_backend") ? JSON.parse(localStorage.getItem("menu_click_backend")) : '';
    if (menu_clicked) {
        $('#menu_'+menu_clicked).addClass('active_menu_item');
        $('#menu_'+menu_clicked).addClass('active_menu_parent');
        $('.icon_show_'+ menu_clicked).addClass('fa-chevron-up');
        $('.child_'+menu_clicked).show();
    }

    function showMenuChild(name) {
        if ($('.menu_left_backend').hasClass('w_50')) {
            $('.menu_child').removeClass('show_menu_child_backend');
            $('.menu_child').hide();
            $('.menu--item').removeClass('active_menu');

            $('.all_item_menu_backend').addClass('w_500');
            $('.child_'+name).addClass('show_menu_child_backend').show('slow');
            $('#menu_'+name).addClass('active_menu');
        } else {
            if ($('#menu_'+name).hasClass('active_menu_item')) {
                $('#menu_'+name).removeClass('active_menu_item');
                if(!$('#menu_'+name).hasClass('have_child_active')) {
                    $('#menu_'+name).removeClass('active_menu_parent');
                } 
                $('.icon_show_'+ name).addClass('fa-chevron-down');
                $('.icon_show_'+ name).removeClass('fa-chevron-up');
                $('.child_'+name).hide('slow');
                localStorage.setItem("menu_click_backend", '');
            } else {
                $('.active_menu_item').removeClass('active_menu_parent')
                $('.menu--item__has_sub_menu').removeClass('active_menu_item')
                $('.menu_child').hide('slow');
                $('.icon_show_down').removeClass('fa-chevron-up')
                $('.icon_show_down').addClass('fa-chevron-down')

                $('#menu_'+name).addClass('active_menu_item');
                $('#menu_'+name).addClass('active_menu_parent')
                $('.icon_show_'+ name).removeClass('fa-chevron-down');
                $('.icon_show_'+ name).addClass('fa-chevron-up');
                $('.child_'+name).show('slow');
                localStorage.setItem("menu_click_backend", JSON.stringify(name));
            }
            $('.all_item_menu_backend').removeClass('w_500');
        }
    }

    $(document).ready(function(){
        $( "body" ).click(function(event) {
            if ((event.target.className != "icon_menu_svg ml-2" && event.target.nodeName != 'LI') && $('.menu_left_backend').hasClass('w_50')) {
                console.log(event.target.className, event.target.nodeName);
                $('.all_item_menu_backend').removeClass('w_500');
                $('.menu_child').removeClass('show_menu_child_backend').hide();
                $('.menu--item').removeClass('active_menu');
            }
        });
    });
</script>
