@php
    $training_form = \App\Models\Categories\TrainingForm::where('id', $item->training_form_id)->first(['name']);
    //$url = $item->course_type == 1 ? route('module.online.detail', ['id' => $item->course_id]) : route('module.offline.detail', ['id' => $item->course_id]);

    $url2 = $item->course_type == 1 ? route('module.online.detail_online', ['id' => $item->course_id]) : route('module.offline.detail', ['id' => $item->course_id]);

    $item->getStatus($item->course_type);
    $get_promotion = \Modules\Promotion\Entities\PromotionCourseSetting::where('course_id',$item->course_id)->where('type',$type)->first();
    $get_bookmarked = \App\Models\CourseBookmark::where('course_id',$item->course_id)->where('type',$type)->where('user_id',profile()->user_id)->first();
    $count_user_register = \App\Models\CourseRegisterView::where('course_id', $item->course_id)->where('course_type', $item->course_type)->where('status', 1)->count();
    $check_promotion_course_setting = \Modules\Promotion\Entities\PromotionCourseSetting::where('course_id',$item->course_id)->exists();
    $check_course_complete = \App\Models\CourseComplete::where('course_id',$item->course_id)->where('course_type',$item->course_type)->where('user_id', profile()->user_id)->first();
    $status = $item->getStatusRegister( $item->course_type );
    $text = status_register_text($status);
    if ($type == 1) {
        $percent = \Modules\Online\Entities\OnlineCourse::percentCompleteCourseByUser($item->course_id, profile()->user_id);
    } else {
        $percent = 0;
    }
@endphp
<div class="col-lg-3 col-md-4 p-1 list_course">
    <div class="fcrse_1 mb-20">
        <a href="{{ $url2 }}" class="img_promotion">
            <div class="img_course">
                <img class="picture_course" src="{{ image_file($item->image) }}" width="100%">
                <div class="show_count_register">
                    <div class="count_register">
                        <i class="far fa-user"></i>
                        <span class="ml-1">{{ $count_user_register }} Học viên</span>
                    </div>
                </div>
            </div>
            <div class="course-overlay">
                @if ( !empty($get_promotion) )
                    @php
                        if ($get_promotion->method == 1)
                            $point = $get_promotion->point;
                        else{
                            $setting = $get_promotion->methodSetting->sortByDesc('point');
                            $point = $setting->count() > 0 ? $setting->first()->point : 0;
                        }
                    @endphp
                    <div class="badge_seller">
                        {{ $point }}
                        <img class="point ml-1" style="width: 20px;height: 20px" src="{{ asset('styles/images/level/point.png') }}" alt="">
                    </div>
                @endif
                <div class="crse_reviews">
                    <i class='uil uil-star'></i>{{ $item->avgRatingStar($type) }}
                </div>
            </div>
        </a>
        <div class="fcrse_content">
            <div class="eps_dots more_dropdown check_course">
                <a href="javascript:void(0)"><i class='uil uil-ellipsis-v'></i></a>
                <div class="dropdown-content">
                    <span>
                        <i class='uil uil-heart-alt'></i>
                        @if (!empty($get_bookmarked))
                            <a href="{{ route('frontend.home.remove_course_bookmark',['course_id'=>$item->course_id, 'course_type' => $type, 'my_course'=> 0]) }}" class="item-bookmark">
                                @lang('app.unbookmark')
                            </a>
                        @else
                            <a href="{{ route('frontend.home.save_course_bookmark',['course_id'=>$item->course_id, 'course_type' => $type, 'my_course' => 0]) }}" class="item-bookmark">
                                @lang('app.bookmark')
                            </a>
                        @endif
                    </span>
                    @if ($check_promotion_course_setting)
                        <span onclick="openModalBonus({{$item->course_id}}, {{$type}})">
                            <img class="image_bonus_courses" src="{{asset("images/level/point.png")}}" alt="" width="29px" height="15px">
                            {{ trans('latraining.reward_points') }}
                        </span>
                    @endif
                    <span href="javascript:void(0)" style="cursor: pointer" class="ml-1" onclick="shareCourse({{$item->course_id}},{{$type}})">
                        <i class="fas fa-link mr-2"></i>
                        Share
                    </span>
                </div>
            </div>
            <div class="wrraped_detail_info_course">
                <div class="vdtodt info_join">
                    <span class="vdt14 mr-1">
                        <i class="uil uil-windsock"></i>
                        {{ $item->register($item->course_type)->count() }} @lang('app.joined')
                    </span>
                    <span class="vdt14 mr-1">
                        {{ $item->views }}
                        <i class="uil uil-eye"></i>
                    </span>
                    <span class="vdt14 mr-1 check_bookmark">
                        @if (!empty($get_bookmarked))
                            <a href="{{ route('frontend.home.remove_course_bookmark',['course_id'=>$item->course_id, 'course_type' => $type, 'my_course'=> 0]) }}" class="item-bookmark">
                                <i class="fas fa-heart check-heart"></i>
                            </a>
                        @else
                            <a href="{{ route('frontend.home.save_course_bookmark',['course_id'=>$item->course_id, 'course_type' => $type, 'my_course' => 0]) }}" class="item-bookmark">
                                <i class="far fa-heart"></i>
                            </a>
                        @endif
                    </span>
                </div>
                <div class="course_names">
                    <a href="{{ $url2 }}" class="crse14s course_name">{{ $item->name }}</a>
                    <span class="hidden_name">{{ $item->name }}</span>
                </div>
                <div class="vdtodt">
                    <span class="vdt14 crse14s"><b>{{ trans('latraining.course_code') }}:</b> {{$item->code}}</span>
                </div>

                <div class="vdtodt">
                    <span class="vdt14">
                        <i class="far fa-calendar-alt"></i>:
                        {{get_date($item->start_date)}} {{ $end_time }}
                    </span>
                </div>

                <div class="detail_info_course">
                    <div class="vdtodt description_course">
                        <span onclick="openModalSummary({{ $item->course_id }}, {{ $type }})" class="vdt14" style="cursor: pointer"><b>Mô tả:</b> Tóm tắt</span> |
                        <span onclick="openModalDescription({{ $item->course_id }}, {{ $type }})">Chi tiết</span>
                    </div>

                    <div class="vdtodt register_deadline">
                        <span class="vdt14"><b>@lang('app.register_deadline'):</b> {{ get_date($item->register_deadline) }}</span>
                    </div>

                    <div class="vdtodt passing_score">
                        <span class="vdt14"><b>Điểm đạt:</b> {{$item->min_grades}}</span>
                    </div>

                    <div class="vdtodt course_type_item">
                        <span class="vdt14"><b>{{ trans('lacategory.form') }}:</b> {{ $training_form->name }}</span>
                    </div>

                    <div class="vdtodt course_object" onclick="openModalObject({{$item->course_id}},{{$type}})" style="cursor: pointer">
                        <p class="cr1fot import-plan"><b>Đối tượng:</b> <i title="{{ $item->getStatus($item->course_type) }}">Chi tiết</i></p>
                    </div>
                </div>
            </div>
            <div class="auth1lnkprce">
                <div class="row">
                    <div class="col-4 chart">
                        <div class="chartProgress">
                            <input type="hidden" name="text" class="canvas_percent" value="{{ $item->course_id }},{{ $type }},{{ $percent }},{{ $status }}">
                            @if ($status == 4 && $type == 1)
                                <canvas id="chartProgress_{{$item->course_id}}_{{ $type }}" width="80px" height="80px"></canvas>
                            @endif
                        </div>
                    </div>
                    <div class="prce142 col-8 button_course">
                        @if($status == 1 && empty($check_course_complete))
                            <div class="mt-2 item item-btn">
                                <button id="btn_register_{{$item->course_id}}_{{ $type }}" class="btn btn_adcart" onclick="submitRegister({{$item->course_id}},{{$type}})">{{ $text }}</button>
                            </div>
                        @elseif($status == 4 && empty($check_course_complete))
                            <div class="mt-2">
                                <button onclick="window.location.href='{{ $url2 }}'" class="btn btn_adcart">Vào học</button>
                            </div>
                        @elseif ( !empty($check_course_complete) )
                            <div class="mt-2">
                                <button onclick="window.location.href='{{ $url2 }}'" class="btn btn_adcart">Hoàn thành</button>
                            </div>
                        @else
                            <div class="mt-2">
                                <button onclick="endCourse({{ $item->course_id }},{{ $type }},{{ $status }})" type="button" class="btn btn_adcart">{{ $text }}</button>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="mt-2 name_type">
                    {{ ($type == 1 ? "Online" : "Tập trung") }}
                </div>
            </div>
        </div>
        <div class="info_all_cousre_hiden">
            <div class="course_names">
                <a href="'.$url2 .'" class="crse14s course_name">{{ $item->name }}</a>
            </div>
            <div class="vdtodt">
                <span class="vdt14"><b>{{ trans('latraining.course_code') }}:</b> {{ $item->code }}</span>
            </div>
            <div class="vdtodt">
                <span class="vdt14"><i class="far fa-calendar-alt"></i>: {{ get_date($item->start_date) }} {{ $end_time }}</span>
            </div>
            <div class="vdtodt description_course">
                <span onclick="openModalSummary({{ $item->course_id }}, {{ $type }})" class="vdt14" style="cursor: pointer"><b>Mô tả:</b> Tóm tắt</span> |
                <span onclick="openModalDescription({{ $item->course_id }}, {{ $type }})">Chi tiết</span>
            </div>
            <div class="vdtodt register_deadline">
                <span class="vdt14"><b>{{ trans("app.register_deadline") }}:</b> {{ get_date($item->register_deadline) }}</span>
            </div>
            <div class="vdtodt passing_score">
                <span class="vdt14"><b>Điểm đạt:</b> {{ $item->min_grades }}</span>
            </div>
            <div class="vdtodt course_type_item">
                <span class="vdt14"><b>{{ trans('lacategory.form') }}:</b> {{ $training_form->name }}</span>
            </div>
            <div class="vdtodt course_object" onclick="openModalObject({{ $item->course_id }}, {{ $type }})" style="cursor: pointer">
                <p class="cr1fot import-plan"><b>Đối tượng:</b> <i title="{{ $item->getStatus($item->course_type) }}">Chi tiết</i></p>
            </div>
        </div>
    </div>
</div>
