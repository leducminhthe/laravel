{{--  @dump($routeName)  --}}
<div class="header @if(!in_array($routeName, ['themes.mobile.frontend.home', 'frontend.home'])) bg-template text-white @endif">
    <div class="row no-gutters">
        @if (isset($lay) && $lay == 'home')
            <div class="col-auto">
                {{--  <button class="btn btn-link text-dark menu-btn">
                    <i class="material-icons color_text">menu</i>
                </button>  --}}
                <figure class="avatar avatar-50">
                    <a href="javascript:void(0)" class="" @if (!userThird()) data-toggle="modal" data-target="#modalInfoUser" @endif>
                        <img src="{{ \App\Models\Profile::avatar() }}" alt="" class="avatar-50">
                    </a>
                </figure>
            </div>
            @php
                $user_id = getUserId();
                $localeLanguage = \App::getLocale();
                $profile_view =  \App\Models\ProfileView::select('id','unit_id','firstname')->where('user_id', $user_id)->first();
                $t = date('H:i');
                $get_id_setting_object = '';
                $get_time = '';
                $check_all = \App\Models\SettingTimeObjectModel::where('object','All')->first();
                $get_objects = \App\Models\SettingTimeObjectModel::where('object','!=','All')->get();
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
            <div class="col d-flex align-items-center ml-1">
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
                    <h6 class="mb-0 d-flex">
                        <span>
                            {!! $formatText !!}
                        </span>
                    </h6>
                @else
                    <h6 class="mb-0 d-flex">
                        <span>Chào bạn, <strong>{{ ' '.$profile_view->firstname  }}</strong></span>
                    </h6>
                @endif
            </div>
            <div class="col-auto">
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('qrcode') }}')" class="btn btn-link text-dark position-relative">
                    <img class="QR_code" src="{{ asset('themes/mobile/img/qrcode-user.png') }}" alt="qr-code">
                </a>
            </div>
            <div class="col-auto noty_home_mobile">
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.notify.index') }}')" class="btn btn-link @if($routeName != 'themes.mobile.frontend.home') text-white @else text-dark @endif position-relative">
                    <i class="material-icons {{ $routeName == 'themes.mobile.frontend.home' ? 'color_text' : '' }}">notifications_none</i>
                    @php
                        $count_noty = \Modules\Notify\Entities\NotifySend::countMessage();
                    @endphp
                    <span class="counts">{{ $count_noty > 99 ? '99+' : $count_noty }}</span>
                </a>
            </div>
            <div class="col-auto d-flex align-items-center">
                <span class="menu-btn pr-2">
                    <i class="material-icons">more_vert</i>
                </span>
            </div>
        @else
            {{--  Nút back lại trang trước  --}}
            <div class="col-auto">
                @if ($routeName == 'themes.mobile.frontend.offline.detail.go_activity')
                    <a href="javascript:void(0);" onclick="backUrlHandle('{{ route('themes.mobile.frontend.offline.detail', [$course_id, 'my_course' => session('my_course')]) }}', 1)" class="btn btn-link-default text-center text-white pt-1 px-1">
                        <i class="material-icons md-24 vm font-weight-bold">navigate_before</i>
                    </a>
                @elseif ($routeName == 'themes.mobile.frontend.online.detail.go_activity')
                    <a href="javascript:void(0);" onclick="backUrlHandle('{{ route('themes.mobile.frontend.online.detail', [$course_id, 'my_course' => session('my_course')]) }}', 1)"  class="btn btn-link-default text-center text-white pt-1 px-1">
                        <i class="material-icons md-24 vm font-weight-bold">navigate_before</i>
                    </a>
                @elseif ($routeName == 'themes.mobile.frontend.online.detail')
                    <a href="javascript:void(0);" onclick="backUrlHandle('{{ session('my_course') ? route('themes.mobile.frontend.my_course') : route('themes.mobile.frontend.online.index') }}', 1)" class="btn btn-link-default text-center text-white pt-1 px-1">
                        <i class="material-icons md-24 vm font-weight-bold">navigate_before</i>
                    </a>
                @elseif ($routeName == 'themes.mobile.frontend.offline.detail')
                    <a href="javascript:void(0);" onclick="backUrlHandle('{{ session('my_course') ? route('themes.mobile.frontend.my_course') : route('themes.mobile.frontend.offline.index') }}', 1)" class="btn btn-link-default text-center text-white pt-1 px-1">
                        <i class="material-icons md-24 vm font-weight-bold">navigate_before</i>
                    </a>
                @elseif ($routeName == 'module.quiz_mobile.doquiz.index')
                    <a href="javascript:void(0);" onclick="backUrlHandle('{{ route('module.quiz.mobile') }}')" class="btn btn-link-default text-center text-white pt-1 px-1">
                        <i class="material-icons md-24 vm font-weight-bold">navigate_before</i>
                    </a>
                @elseif ($routeName == 'module.quiz_mobile.doquiz.do_quiz')
                    @php
                        //Check xem có phải xem kỳ thi trong khoá Online không
                        if($quiz->quiz_type == 1){
                            $return_exam_screen = route('themes.mobile.frontend.online.detail', [$quiz->course_id]);
                        }else{
                            $return_exam_screen = route('module.quiz_mobile.doquiz.index', [ 'quiz_id' => $quiz->id, 'part_id' => $part_id]);
                        }
                    @endphp
                    <a href="javascript:void(0);" onclick="backUrlHandle('{{ $return_exam_screen }}')" class="btn btn-link-default text-center text-white pt-1 px-1">
                        <i class="material-icons md-24 vm font-weight-bold">navigate_before</i>
                    </a>
                @elseif (in_array($routeName, ['module.quiz.mobile', 'themes.mobile.frontend.online.index', 'themes.mobile.frontend.offline.index', 'themes.mobile.frontend.my_course']))
                    @if ($routeName == 'module.quiz.mobile')
                    <a href="javascript:void(0);" onclick="backUrlHandle('{{ route('themes.mobile.frontend.home') }}', 2)" class="btn btn-link-default text-center text-white pt-1 px-1">
                    @else
                    <a href="javascript:void(0);" onclick="backUrlHandle('{{ route('themes.mobile.frontend.home') }}', 1)" class="btn btn-link-default text-center text-white pt-1 px-1">
                    @endif
                        <i class="material-icons md-24 vm font-weight-bold">navigate_before</i>
                    </a>
                @elseif ($routeName == 'themes.mobile.front.my_certificate.create' || $routeName == 'themes.mobile.front.my_certificate.edit')
                    <a href="javascript:void(0);" onclick="backUrlHandle('{{ route('themes.mobile.front.my_certificate') }}')" class="btn btn-link-default text-center text-white pt-1 px-1">
                        <i class="material-icons md-24 vm font-weight-bold">navigate_before</i>
                    </a>
                @elseif ($routeName == 'module.online.survey.user')
                    <a href="javascript:void(0);" onclick="backUrlHandle('{{ route('themes.mobile.frontend.online.detail.go_activity', [$course_id]) }}')" class="btn btn-link-default text-center text-white pt-1 px-1">
                        <i class="material-icons md-24 vm font-weight-bold">navigate_before</i>
                    </a>
                @else
                    @if (in_array($routeName, $route_loading1))
                    <a href="javascript:void(0)" onclick="backUrlHandle(null, 1)" class="btn btn-link-default text-center text-white pt-1 px-1">
                    @elseif (in_array($routeName, $route_loading2))
                    <a href="javascript:void(0)" onclick="backUrlHandle(null, 2)" class="btn btn-link-default text-center text-white pt-1 px-1">
                    @elseif (in_array($routeName, $route_loading3))
                    <a href="javascript:void(0)" onclick="backUrlHandle(null, 3)" class="btn btn-link-default text-center text-white pt-1 px-1">
                    @else
                    <a href="javascript:void(0)" onclick="backUrlHandle()" class="btn btn-link-default text-center text-white pt-1 px-1">
                    @endif
                        <i class="material-icons md-24 vm font-weight-bold">navigate_before</i>
                    </a>
                @endif
            </div>

            {{--  Tiêu đề trang  --}}
            <div class="col text-center mt-3">
                <h5 class="page_title">
                    @yield('page_title')
                </h5>
            </div>

            {{--  Vị trí các cột ở giữa theo từng trang  --}}
            @if(isset($lay) && in_array($lay, ['online', 'offline']))
                <div class="col-auto">
                    <a href="javascript:void(0)" class="btn btn-link text-white position-relative" data-toggle="modal" data-target="#filterOnline">
                        <img src="{{ asset('themes/mobile/img/filter.png') }}" alt="">
                    </a>
                </div>
            @elseif(isset($lay) && $lay == 'video')
                <div class="col-auto">
                    <a href="{{ route('themes.mobile.daily_training.frontend.add_video') }}" class="btn btn-link text-white position-relative">
                        <i class="material-icons vm">add_circle</i>
                    </a>
                    <a href="{{ route('themes.mobile.daily_training.frontend.search') }}" class="text-white">
                        <i class="material-icons vm">search</i>
                    </a>
                </div>
            @elseif($routeName == 'theme.mobile.frontend.attendance')
                <div class="col-auto">
                    <a href="javascript:void(0)" class="btn btn-link text-white position-relative" data-toggle="modal" data-target="#seachCourseInAttendace">
                        <i class="material-icons color_text">search</i>
                    </a>
                </div>
            @elseif($routeName == 'module.online.embed')
                <div class="col-auto">
                    <button class="btn btn-link text-white position-relative" id="autorenew"><i class="material-icons color_text">autorenew</i></button>
                </div>
            @endif

            {{--  Vị trí cuối cùng header  --}}
            @if ($routeName == 'module.quiz_mobile.doquiz.do_quiz')
                <div class="col-auto m-auto text-center pr-3">
                    <div id="clockdiv" class="mr-1"></div>
                    <span>{{ trans('latraining.question') }}</span>:
                    <span class="font-weight-bold">
                        <span id="num-question-selected">0</span>{{ '/'. count($questions) }}
                    </span>
                </div>
            @elseif(in_array($routeName, ['themes.mobile.frontend.offline.detail.go_activity', 'themes.mobile.frontend.online.detail.go_activity']))
                <div class="col-auto">
                    <div class="btn-group">
                        <button type="button" class="btn" id="btn-menu-activity">
                            <i class="material-icons">menu</i>
                        </button>
                    </div>
                </div>
            @elseif ($routeName == 'themes.mobile.suggest.index')
                <div class="col-auto col-auto d-flex align-items-center">
                    @yield('col_header_right')
                </div>
            @else
                <div class="col-auto">
                    <button class="btn btn-link text-white menu-btn">
                        <i class="material-icons">more_vert</i>
                    </button>
                </div>
            @endif
        @endif
    </div>
</div>
