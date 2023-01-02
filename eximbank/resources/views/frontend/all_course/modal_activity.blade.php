<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Hoạt động
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table class="table tDefault table-reponsive">
                    <thead>
                        <tr>
                            <th>Yêu cầu</th>
                            <th class="text-center w-15">{{ trans('latraining.status') }}</th>
                            <th class="text-center w-15">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($entrance_quiz)
                            <tr>
                                <td>Kỳ thi đầu vào</td>
                                <td class="text-center">{{ $status_entrance_quiz_result == 1 ? 'Hoàn thành' : '' }}</td>
                                <td class="text-center">
                                    @if ($closed_entrance_quiz)
                                        Kỳ thi kết thúc
                                    @elseif ($go_entrance_quiz_url)
                                        <a href="{{ $go_entrance_quiz_url }}" target="_blank" class="btn text-white">
                                            {{ $status_entrance_quiz_result == 1 ? 'Xem lại' : 'Vào thi' }}
                                        </a>
                                    @else
                                        Chưa tới giờ
                                    @endif
                                </td>
                            </tr>
                        @endif
                        @if ($quiz)
                            <tr>
                                <td>Kỳ thi cuối khóa</td>
                                <td class="text-center">{{ $status_quiz_result == 1 ? 'Hoàn thành' : '' }}</td>
                                <td class="text-center">
                                    @if ($closed_quiz)
                                        Kỳ thi kết thúc
                                    @elseif ($go_quiz_url)
                                        <a href="{{ $go_quiz_url }}" target="_blank" class="btn text-white">
                                            {{ $status_quiz_result == 1 ? 'Xem lại' : 'Vào thi' }}
                                        </a>
                                    @else
                                        Chưa tới giờ
                                    @endif

                                </td>
                            </tr>
                        @endif
                        @if ($action_plan == 1)
                            <tr>
                                <td>Kế hoạch ứng dụng</td>
                                <td class="text-center">{{ $text_status_plan_app }}</td>
                                <td class="text-center">
                                    @if($url_plan_app)
                                    <a href="{{ $url_plan_app }}" target="_blank" class="btn text-white">
                                        {{ $status_plan_app == 5 ? 'Xem lại' : ($status_plan_app == 0 ? 'Lập kế hoạch' : 'Đánh giá') }}
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @endif
                        @if($offline_rating_level_object)
                            @foreach ($offline_rating_level_object as $item)
                                @php
                                    $check = [];
                                    $start_date_rating = '';
                                    $end_date_rating = '';
                                    $url_rating_level = '';
                                    $rating_status = 0;
                                    $user_completed = 0;
                                    $user_result = 0;
                                    $setting_time = 0;
                                    $notify_rating = [];

                                    $course = \Modules\Offline\Entities\OfflineCourse::find($item->course_id);
                                    $result = \Modules\Offline\Entities\OfflineResult::where('course_id', '=', $item->course_id)
                                        ->where('user_id', '=', profile()->user_id)
                                        ->where('result', '=', 1)
                                        ->first();
                                    if ($result) {
                                        $user_result = 1;
                                    }

                                    if ($item->time_type == 1){
                                        $setting_time = 1;
                                        $start_date_rating = $item->start_date;
                                        $end_date_rating = $item->end_date;
                                    }
                                    if ($item->time_type == 2){
                                        $setting_time = 1;
                                        if (isset($item->num_date)){
                                            $start_date_rating = date("Y-m-d", strtotime($course->start_date)) . " +{$item->num_date} day";
                                        }else{
                                            $start_date_rating = $course->start_date;
                                        }
                                    }
                                    if ($item->time_type == 3){
                                        $setting_time = 1;
                                        if (isset($item->num_date)){
                                            $start_date_rating = date("Y-m-d", strtotime($course->end_date)) . " +{$item->num_date} day";
                                        }else{
                                            $start_date_rating = $course->end_date;
                                        }
                                    }
                                    if ($item->time_type == 4){
                                        $setting_time = 1;
                                        if ($result){
                                            if (isset($item->num_date)){
                                                $start_date_rating = date("Y-m-d", strtotime($result->created_at)) . " +{$item->num_date} day";
                                            }else{
                                                $start_date_rating = $result->created_at;
                                            }
                                        }
                                    }
                                    if($item->user_completed == 1){
                                        $user_completed = 1;
                                    }
                                    if (empty($start_date_rating) && empty($end_date_rating) && $user_completed == 0 && $setting_time == 0){
                                        $url_rating_level = route('module.rating_level.course', [$item->course_id, 2, $item->offline_rating_level_id, profile()->user_id]).'?rating_level_object_id='.@$item->id.'&view_type=course';
                                    }else{

                                        if($setting_time == 1){
                                            if(empty($start_date_rating) && empty($end_date_rating)){
                                                $check[] = false;
                                                $notify_rating[] = 'Bạn cần phải hoàn thành khóa học trước';
                                            }else{
                                                if ($start_date_rating){
                                                    if ($start_date_rating <= now()){
                                                        $check[] = true;
                                                    }else{
                                                        $check[] = false;
                                                        $notify_rating[] = 'Chưa tới thời gian đánh giá';
                                                    }
                                                }

                                                if ($end_date_rating){
                                                    if ($end_date_rating >= now()){
                                                        $check[] = true;
                                                    }else{
                                                        $check[] = false;
                                                        $notify_rating[] = 'Kết thúc thời gian đánh giá';
                                                    }
                                                }
                                            }
                                        }

                                        if ($user_completed == 1){
                                            if ($user_result == 1){
                                                $check[] = true;
                                            }else{
                                                $check[] = false;
                                                $notify_rating[] = 'Bạn cần phải hoàn thành khóa học trước';
                                            }
                                        }

                                        if (!in_array(false, $check)){
                                            $url_rating_level = route('module.rating_level.course', [$item->course_id, 2, $item->offline_rating_level_id, profile()->user_id]).'?rating_level_object_id='.@$item->id.'&view_type=course';
                                        }
                                    }

                                    $offline_rating_level = \Modules\Offline\Entities\OfflineRatingLevel::find($item->offline_rating_level_id);
                                    $rating_level_course = \Modules\Rating\Entities\RatingLevelCourse::query()
                                        ->where('course_rating_level_id', $item->offline_rating_level_id)
                                        ->where('user_id', '=', profile()->user_id)
                                        ->where('user_type', '=', 1)
                                        ->where('course_id', '=', $item->course_id)
                                        ->where('course_type', '=', 2)
                                        ->first();
                                    if($rating_level_course){
                                        $rating_status = $rating_level_course->send == 1 ? 1 : 2;
                                        $url_rating_level = route('module.rating_level.edit_course', [$item->course_id, 2, $item->offline_rating_level_id, profile()->user_id]).'?rating_level_object_id='.@$item->id.'&view_type=course';
                                    }
                                    $notify_rating = implode(',', $notify_rating);
                                @endphp
                                <tr>
                                    <td>{{ $offline_rating_level->rating_name }}</td>
                                    <td class="text-center">{{ $rating_status == 1 ? 'Đã đánh giá' : ($rating_status == 2 ? 'Chưa gửi đánh giá' : 'Chưa đánh giá') }}</td>
                                    <td class="text-center">
                                        @if($url_rating_level)
                                            <a href="{{ $url_rating_level }}" target="_blank" class="btn text-white">
                                                {{ $rating_level_course->send == 1 ? 'Xem lại' : 'Vào làm' }}
                                            </a>
                                        @else
                                            <span>Đánh giá <i class="fa fa-info-circle view-notify-rating cursor_pointer" data-notify_rating="{{ $notify_rating }}" ></i></span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        @if($online_rating_level_object)
                            @foreach ($online_rating_level_object as $item)
                                @php
                                    $check = [];
                                    $start_date_rating = '';
                                    $end_date_rating = '';
                                    $url_rating_level = '';
                                    $rating_status = 0;
                                    $user_completed = 0;
                                    $user_result = 0;
                                    $setting_time = 0;
                                    $notify_rating = [];

                                    $course = \Modules\Online\Entities\OnlineCourse::find($item->course_id);
                                    $result = \Modules\Online\Entities\OnlineResult::where('course_id', '=', $item->course_id)
                                        ->where('user_id', '=', profile()->user_id)
                                        ->where('result', '=', 1)
                                        ->first();
                                    if ($result) {
                                        $user_result = 1;
                                    }

                                    if ($item->time_type == 1){
                                        $setting_time = 1;
                                        $start_date_rating = $item->start_date;
                                        $end_date_rating = $item->end_date;
                                    }
                                    if ($item->time_type == 2){
                                        $setting_time = 1;
                                        if (isset($item->num_date)){
                                            $start_date_rating = date("Y-m-d", strtotime($course->start_date)) . " +{$item->num_date} day";
                                        }else{
                                            $start_date_rating = $course->start_date;
                                        }
                                    }
                                    if ($item->time_type == 3){
                                        $setting_time = 1;
                                        if (isset($item->num_date)){
                                            $start_date_rating = date("Y-m-d", strtotime($course->end_date)) . " +{$item->num_date} day";
                                        }else{
                                            $start_date_rating = $course->end_date;
                                        }
                                    }
                                    if ($item->time_type == 4){
                                        $setting_time = 1;
                                        if ($result){
                                            if (isset($item->num_date)){
                                                $start_date_rating = date("Y-m-d", strtotime($result->created_at)) . " +{$item->num_date} day";
                                            }else{
                                                $start_date_rating = $result->created_at;
                                            }
                                        }
                                    }
                                    if($item->user_completed == 1){
                                        $user_completed = 1;
                                    }

                                    if (empty($start_date_rating) && empty($end_date_rating) && $user_completed == 0 && $setting_time == 0){
                                        $url_rating_level = route('module.rating_level.course', [$item->course_id, 1, $item->online_rating_level_id, profile()->user_id]).'?rating_level_object_id='.@$item->id.'&view_type=course';
                                    }else{

                                        if($setting_time == 1){
                                            if(empty($start_date_rating) && empty($end_date_rating)){
                                                $check[] = false;
                                                $notify_rating[] = 'Bạn cần phải hoàn thành khóa học trước';
                                            }else{
                                                if ($start_date_rating){
                                                    if ($start_date_rating <= now()){
                                                        $check[] = true;
                                                    }else{
                                                        $check[] = false;
                                                        $notify_rating[] = 'Chưa tới thời gian đánh giá';
                                                    }
                                                }

                                                if ($end_date_rating){
                                                    if ($end_date_rating >= now()){
                                                        $check[] = true;
                                                    }else{
                                                        $check[] = false;
                                                        $notify_rating[] = 'Kết thúc thời gian đánh giá';
                                                    }
                                                }
                                            }
                                        }

                                        if ($user_completed == 1){
                                            if ($user_result == 1){
                                                $check[] = true;
                                            }else{
                                                $check[] = false;
                                                $notify_rating[] = 'Bạn cần phải hoàn thành khóa học trước';
                                            }
                                        }

                                        if (!in_array(false, $check)){
                                            $url_rating_level = route('module.rating_level.course', [$item->course_id, 1, $item->online_rating_level_id, profile()->user_id]).'?rating_level_object_id='.@$item->id.'&view_type=course';
                                        }
                                    }

                                    $online_rating_level =  \Modules\Online\Entities\OnlineRatingLevel::find($item->online_rating_level_id);
                                    $rating_level_course = \Modules\Rating\Entities\RatingLevelCourse::query()
                                        ->where('course_rating_level_id', $item->online_rating_level_id)
                                        ->where('user_id', '=', profile()->user_id)
                                        ->where('user_type', '=', 1)
                                        ->where('course_id', '=', $item->course_id)
                                        ->where('course_type', '=', 1)
                                        ->first();
                                    if($rating_level_course){
                                        $rating_status = $rating_level_course->send == 1 ? 1 : 2;
                                        $url_rating_level = route('module.rating_level.edit_course', [$item->course_id, 1, $item->online_rating_level_id, profile()->user_id]).'?rating_level_object_id='.@$item->id.'&view_type=course';
                                    }
                                    $notify_rating = implode(',', $notify_rating);
                                @endphp
                                <tr>
                                    <td>{{ $online_rating_level->rating_name }}</td>
                                    <td class="text-center">{{ $rating_status == 1 ? 'Đã đánh giá' : ($rating_status == 2 ? 'Chưa gửi đánh giá' : 'Chưa đánh giá') }}</td>
                                    <td class="text-center">
                                        @if($url_rating_level)
                                            <a href="{{ $url_rating_level }}" target="_blank" class="btn text-white">
                                                {{ $rating_level_course->send == 1 ? 'Xem lại' : 'Vào làm' }}
                                            </a>
                                        @else
                                            <span>Đánh giá <i class="fa fa-info-circle view-notify-rating cursor_pointer" data-notify_rating="{{ $notify_rating }}"></i> </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        @if ($offlineTeachingOrganizationTemplate)
                            <tr>
                                <td>{{ $offlineTeachingOrganizationTemplate->name }}</td>
                                <td class="text-center">{{ $status_organization_user == 1 ? 'Hoàn thành' : '' }}</td>
                                <td class="text-center">
                                    <a href="{{ $url_organization_user }}" target="_blank" class="btn text-white">
                                        {{ $text_organization_user }}
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="modal-notify-rating">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#myModal .view-notify-rating').on('click', function(){
        var text = '';
        var notify_rating = $(this).data('notify_rating');
        console.log(notify_rating);

        if(notify_rating){
            notify_rating = notify_rating.split(',');

            for(var i = 0; i < notify_rating.length; i++){
                text += '<p class="h5 text-center">'+ (i+1) +'. '+ notify_rating[i] +'</p>';
            }

            $('#modal-notify-rating .modal-body').html(text);
            $('#modal-notify-rating').modal();
        }
    });
</script>
