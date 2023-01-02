<header class="header">
    <div class="inner-header">
        <a href="{{ route('home_outside',['type' => 0]) }}">
            <img src="{{ image_file(\App\Models\Config::getLogoOutside()) }}" alt="" class="logo">
        </a>
    </div>
    @if (profile()->user_id)
        @php
            $user_id = profile()->user_id;
            $user_type = getUserType();
            $profile_view = \App\Models\ProfileView::select('id','avatar','unit_id','firstname','email','full_name')->where('user_id', $user_id)->first();
        @endphp
        <div class="group-right menu_top">
            <ul>
                <li class="ui dropdown mr-1">
                    <div class="dropdown dropdown_menu_user">
                        <span><strong>{{ $user_type == 1 ? $profile_view->firstname : '' }}</strong></span>
                        <a class="opts_account" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ $user_type == 1 ? image_user($profile_view->avatar) : asset('/images/design/user_50_50.png') }}" alt="">
                        </a>
                        <div class="dropdown-menu dropdown-menu-info-user w_200" aria-labelledby="dropdownMenuButton">
                            <div class="channel_my">
                                @if($user_type == 1)
                                    <div class="profile_link">
                                        <div class="pd_content">
                                            <div class="rhte85">
                                                <h6 class="mt-0">{{ $profile_view->full_name }}</h6>
                                            </div>
                                            <span>{{ $profile_view->email }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('module.frontend.user.info') }}" class="dp_link_12">@lang('lamenu.user_info')</a>
                                    <a href="{{ route('frontend.all_course',['type' => 3]) }}" class="dp_link_12">Cổng đào tạo</a>
                                @endif
                                <a href="{{ route('logout') }}" class="dp_link_12 item channel_item">@lang('lamenu.logout')</a>
                            </div>
                        </div>
                    </div>
                </li>
                {{-- <li class="has-sub m-0">
                    <a href="{{ route('frontend.all_course',['type' => 3]) }}" style="color: white;" class="no-action btn m-2" aria-label="button">
                        <mark>Khóa học</mark>
                    </a>
                </li> --}}
            </ul>
        </div>
    @else
        <div class="group-right menu_top">
            <ul>
                <li class="has-sub contact m-0">
                    <a href="{{ route('user_contact_outside') }}" style="color: white;" class="no-action" aria-label="button">
                        <mark>THÔNG TIN LIÊN HỆ</mark>
                    </a>
                </li>
                <li class="has-sub m-0">
                    <a href="{{ route('login') }}" style="color: white;" class="no-action" aria-label="button">
                        <mark>ĐĂNG NHẬP</mark>
                        <span class="icon" style="background: url({{ asset('images/user_tt.png') }}) no-repeat 50%/100%"></span>
                    </a>
                </li>
            </ul>
        </div>
    @endif

    <div class="second-menu menu_web" style="opacity: 1;">
        {{-- <span class="line-color"></span> --}}
        <div class="group-left">
            <ul>
                @php
                    $news_category_parent = \Modules\NewsOutside\Entities\NewsOutsideCategory::query()->orderBy('stt_sort_parent')->whereNull('parent_id')->get();
                @endphp
                <li class="has-sub mr-2">
                    <a href="{{ route('home_outside',['type' => 0]) }}">
                        <img src="{{asset('images/home_outside.png')}}" alt="" width="25px">
                    </a>
                </li>
                @foreach($news_category_parent as $category_parent)
                    @php
                        $news_category_child = $category_parent->child;
                    @endphp
                    <li class="has-sub">
                        <button class="no-action" aria-label="button">
                            {{-- <span class="icon" style="background: url({{ image_file($category_parent->icon) }}) no-repeat 50%/100%"></span> --}}
                            <a class="cate_parent_name" href="{{ route('module.frontend.news_outside', ['cate_id' => 0, 'parent_id' => $category_parent->id, 'type' => 0]) }}">
                                <mark>{{ $category_parent->name }}</mark>
                            </a>
                        </button>
                        <div class="sub-menu-drop" data-show="30">
                            <div class="mark-mobile">
                                <mark>{{ $category_parent->name }}</mark>
                            </div>
                            @foreach($news_category_child as $category_child)
                            <div class="has-child">
                                <a class="link-load" href="{{ route('module.frontend.news_outside', ['cate_id' => $category_child->id, 'parent_id' => $category_child->parent_id, 'type' => 1]) }}">
                                    {{-- <span class="icon" style="background: url({{ image_file($category_child->icon) }}) no-repeat 50%/100%"></span> --}}
                                    <mark>{{ $category_child->name }}</mark>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        {{-- @if (profile()->user_id)
            <div class="group-right menu_bottom">
                <ul>
                    <li class="has-sub m-2">
                        <a href="{{ route('frontend.all_course',['type' => 3]) }}" style="color: white;" class="no-action" aria-label="button">
                            <mark>Khóa học</mark>
                        </a>
                    </li>
                </ul>
            </div>
        @else
            <div class="group-right menu_bottom">
                <ul>
                    <li class="has-sub">
                        <a class="button_login_link" href="{{ route('login') }}" style="color: white;" class="no-action" aria-label="button">
                            <span class="icon" style="background: url({{ asset('images/user_tt.png') }}) no-repeat 50%/100%"></span>
                            <mark>ĐĂNG NHẬP</mark>
                        </a>
                    </li>
                </ul>
            </div>
        @endif --}}
    </div>

    <div class="second-menu menu_mobile" style="opacity: 1;">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="{{ route('home_outside',['type' => 0]) }}">
                <img src="{{asset('images/home_outside.png')}}" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
              <ul class="navbar-nav">

                @foreach($news_category_parent as $category_parent)
                    @php
                        $news_category_child = $category_parent->child;
                    @endphp
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ $category_parent->name }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            @foreach($news_category_child as $category_child)
                                <a class="dropdown-item" href="{{ route('module.frontend.news_outside', ['cate_id' => $category_child->id, 'parent_id' => $category_child->parent_id, 'type' => 1]) }}">
                                    {{ $category_child->name }}
                                </a>
                            @endforeach
                        </div>
                    </li>
                @endforeach

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
                </li>
              </ul>
            </div>
          </nav>
    </div>

    <div class="overlay-banner"></div>
    <div class="overlay-menu"></div>
</header>
