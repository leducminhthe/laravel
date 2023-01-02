@php
    $tab_3 = Request::segment(2);

    $get_color_menu = \App\Models\SettingColor::where('name','color_menu')->first();

    $color_link = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_menu->text;
    $text_color_menu_active = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? '#dee2e6' : $get_color_menu->active;
    $hover_text_color_menu = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_menu->hover_text;
    $background_menu = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_menu->background;
    $hover_background_menu = (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 'unset' : $get_color_menu->hover_background;
@endphp
<style>
    #menu_child_1, #menu_child_2, #menu_child_3 {
        display: none;
        background: white;
    }
    @media (max-width: 474px) {
        .all_item_menu_frontend_user li{
            height: 38px;
        }
        .all_item_menu_frontend_user li label{
            margin-top: 7px
        }
    }
    .all_item_menu_frontend_user{
        box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
        margin: 0px 5px
    }
    .all_item_menu_frontend_user .menu--item__has_sub_menu {
        color: {{ $color_link }};
        position: relative;
        height: 40px;
        display: flex;
        align-items: center;
    }
    .all_item_menu_frontend_user li label{
        padding: 0 7px;
    }
    .all_item_menu_frontend_user .menu_child--item{
        display: inline-block;
        height: 40px;
        line-height: 43px;
        padding: 0 8px;
    }
    .show_item_child::after{
        content:"";
        width:0px;
        height:0px;
        position:absolute;
        border:6px solid transparent;
        border-bottom:7px solid white;
        left:42%;
        bottom: 0px;
    }
    .item_active{
        border-radius: 10px;
        background: {{ $background_menu . ' !important' }};
        color: {{ $text_color_menu_active . ' !important' }};
    }
    a.active_menu_item{
        color: {{ $text_color_menu_active . ' !important' }};
    }
    .menu--item_user:hover,
    .menu--item_user:hover a,
    .menu_child--item:hover{
        border-radius: 10px;
        background: {{ $hover_background_menu . ' !important' }};
        color: {{ $hover_text_color_menu . ' !important' }};
    }
    .menu_child_active {
        border-bottom: 3px solid {{ $background_menu }};
    }
</style>
<ul class="all_item_menu_frontend_user d-flex align-items-center m-1" id="profile_info">
    <li class="menu--item_user menu--item__has_sub_menu @if($tab_3 == 'info') active_menu_item item_active @endif">
        <label class="mb-0">
            <span class="menu--label">
                <a href="{{ route('module.frontend.user.info') }}" class="menu_child--link @if($tab_3 == 'info') active_menu_item @endif">
                    {{ trans('laprofile.info') }}
                </a>
            </span>
        </label>
    </li>
    <li class="menu--item_user menu--item__has_sub_menu @if ($tab_3 == 'roadmap' || $tab_3 == 'training-by-title' || $tab_3 == 'my-career-roadmap' || $tab_3 == 'subjectregister') active_menu_item item_active @endif" data-child="1" id="itemMenu1">
        <label class="mb-0 cursor_pointer">
            <span class="menu--label">{{ trans('laprofile.development_roadmap') }}</span>
        </label>
    </li>
    <li class="menu--item_user menu--item__has_sub_menu @if ($tab_3 == 'trainingprocess' || $tab_3 == 'student-cost' || $tab_3 == 'quizresult' || $tab_3 == 'working-process' || $tab_3 == 'subject_type') active_menu_item item_active @endif" data-child="2" id="itemMenu2">
        <label class="mb-0 cursor_pointer">
            <span class="menu--label">{{ trans('laprofile.training_process') }}</span>
        </label>
    </li>
    <li class="menu--item_user menu--item__has_sub_menu @if ($tab_3 == 'my-promotion' || $tab_3 == 'violate-rules' || $tab_3 == 'my-promotion-history' || $tab_3 == 'my-certificate' || $tab_3 == 'edit-my-certificate') active_menu_item item_active @endif" data-child="3" id="itemMenu3">
        <label class="mb-0 cursor_pointer">
            <span class="menu--label">{{ trans('latraining.other') }}</span>
        </label>
    </li>
</ul>
<ul class="all_item_menu_frontend_user menu_child_user" id="menu_child_1">
    <li class="menu_child--item @if ($tab_3 == 'roadmap') menu_child_active @endif">
        <a href="{{ route('module.frontend.user.roadmap') }}" class="menu_child--link">
            <span>{{ trans('laprofile.roadmap') }}</span>
        </a>
    </li>
    <li class="menu_child--item @if ($tab_3 == 'training-by-title') menu_child_active @endif">
        <a href="{{ route('module.frontend.user.training_by_title') }}" class="menu_child--link">
            <span>{{ trans('laprofile.training_path') }}</span>
        </a>
    </li>
    <li class="menu_child--item @if ($tab_3 == 'my-career-roadmap') menu_child_active @endif">
        <a href="{{ route('module.frontend.user.my_career_roadmap') }}" class="menu_child--link">
            <span>{{ trans('laprofile.career_roadmap') }}</span>
        </a>
    </li>
    <li class="menu_child--item @if ($tab_3 == 'subjectregister') menu_child_active @endif">
        <a href="{{ route('module.frontend.user.subjectregister') }}" class="menu_child--link">
            <span>{{ trans('laprofile.subject_registered') }}</span>
        </a>
    </li>
</ul>
<ul class="all_item_menu_frontend_user menu_child_user" id="menu_child_2">
    <li class="menu_child--item menu--item__has_sub_menu @if ($tab_3 == 'trainingprocess') menu_child_active @endif">
        <a href="{{ route('module.frontend.user.trainingprocess') }}" class="menu_child--link">
            <span>{{ trans('laprofile.study_progress') }}</span>
        </a>
    </li>
    <li class="menu_child--item menu--item__has_sub_menu @if ($tab_3 == 'working-process') menu_child_active @endif">
        <a href="{{ route('module.frontend.user.working_process') }}" class="menu_child--link">
            <span>{{ trans('laprofile.working_process') }}</span>
        </a>
    </li>
    <li class="menu_child--item menu--item__has_sub_menu @if ($tab_3 == 'student-cost') menu_child_active @endif">
        <a href="{{ route('module.frontend.user.student_cost') }}" class="menu_child--link">
            <span>{{ trans('laprofile.student_cost') }}</span>
        </a>
    </li>
    <li class="menu_child--item menu--item__has_sub_menu @if ($tab_3 == 'quizresult') menu_child_active @endif">
        <a href="{{ route('module.frontend.user.quizresult') }}" class="menu_child--link">
            <span>{{ trans('laprofile.quiz_result') }}</span>
        </a>
    </li>
    <li class="menu_child--item menu--item__has_sub_menu @if ($tab_3 == 'subject_type') menu_child_active @endif">
        <a href="{{ route('module.frontend.user.subject_type') }}" class="menu_child--link">
            <span>{{ trans('latraining.training_certification') }}</span>
        </a>
    </li>
</ul>
<ul class="all_item_menu_frontend_user menu_child_user" id="menu_child_3">
    <li class="menu_child--item @if ($tab_3 == 'violate-rules') menu_child_active @endif">
        <a href="{{ route('module.frontend.user.violate_rules') }}" class="menu_child--link">
            <span>{{ trans('laprofile.violate_rules') }}</span>
        </a>
    </li>
    <li class="menu_child--item @if ($tab_3 == 'my-promotion') menu_child_active @endif">
        <a href="{{ route('module.frontend.user.my_promotion') }}" class="menu_child--link">
            <span>{{ trans('laprofile.gift_list') }}</span>
        </a>
    </li>
    <li class="menu_child--item @if ($tab_3 == 'my-promotion-history') menu_child_active @endif">
        <a href="{{ route('module.frontend.user.my_promotion_history') }}" class="menu_child--link">
            <span>{{ trans('lamenu.purchase_history') }}</span>
        </a>
    </li>
    <li class="menu_child--item @if ($tab_3 == 'my-certificate' || $tab_3 == 'add-my-certificate' || $tab_3 == 'edit-my-certificate') menu_child_active @endif">
        <a href="{{ route('module.frontend.user.my_certificate') }}" class="menu_child--link">
            <span>{{ trans('laprofile.external_certificate') }}</span>
        </a>
    </li>
    <li class="menu_child--item @if ($tab_3 == 'my-capability') menu_child_active @endif">
        <a href="{{ route('module.frontend.user.my_capability') }}" class="menu_child--link">
            <span>Khung đánh giá năng lực</span>
        </a>
    </li>
</ul>
<script>
    var itemMenu1 = ['roadmap', 'training-by-title', 'my-career-roadmap', 'subjectregister'];
    var itemMenu2 = ['trainingprocess', 'student-cost', 'quizresult', 'working-process', 'subject_type'];
    var itemMenu3 = ['my-promotion', 'violate-rules', 'my-promotion-history', 'my-certificate', 'edit-my-certificate', 'add-my-certificate','my-capability'];
    var tab = '{{ $tab_3 }}'
    if(itemMenu1.includes(tab)) {
        $('#itemMenu1').addClass('show_item_child item_active');
        $('#menu_child_1').show();
    } else if(itemMenu2.includes(tab)) {
        $('#itemMenu2').addClass('show_item_child item_active');
        $('#menu_child_2').show();
    } else if(itemMenu3.includes(tab)) {
        $('#itemMenu3').addClass('show_item_child item_active');
        $('#menu_child_3').show();
    }

    $(".menu--item_user").click(function(){
        $('.menu--item__has_sub_menu').removeClass('active_menu_item');
        $('.menu--item__has_sub_menu a').removeClass('active_menu_item');

        $('.menu--item_user').removeClass('show_item_child item_active');
        $('.menu_child_user').css('display', 'none');

        $(this).addClass('show_item_child item_active');
        var i = $(this).data('child');
        $('#menu_child_'+i).show();
    });
</script>
