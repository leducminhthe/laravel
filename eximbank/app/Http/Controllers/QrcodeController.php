<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\ProfileView;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRatingLevelObject;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRatingLevelObject;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Rating\Entities\RatingLevelCourse;
use Modules\Survey\Entities\SurveyUser;
use Modules\Survey\Entities\SurveyObject;
use Modules\Offline\Entities\OfflineTeachingOrganizationUser;

class QrcodeController extends Controller
{
    public function index()
    {
        if (url_mobile()){
            return view('themes.mobile.frontend.qrcode.qrcode');
        }
        return view('qrcode.qrcode');
    }

    public function message()
    {
        if (url_mobile()){
            return view('themes.mobile.frontend.qrcode.qrcode-message');
        }
        return view('qrcode.qrcode-message');
    }

    public function process(Request $request)
    {
        $user_id = $request->user??profile()->user_id;
        if($request->type=='attendance') { // ghi danh
            $x=OfflineAttendance::updateAttendance($user_id,$request->course,$request->schedule, '1.HVQRC');
            if ($x){
                $count_schedule = OfflineSchedule::whereCourseId($request->course)->where('class_id',$request->class_id)->count();
                $count_attendance = OfflineAttendance::whereUserId($request->user)->where(['course_id' => $request->course,'class_id'=>$request->class_id])->count();
                if ($count_attendance == $count_schedule){
                    \Artisan::call('command:offline_complete '.$user_id .' '.$request->course);
                }

                return redirect()->route('qrcode_message')->with('success','Điểm danh thành công');
            }else{
                return redirect()->route('qrcode_message')->with('error','Học viên này chưa được ghi danh vào khóa học');
            }
        } elseif($request->type=='survey_after_course') {
            $url = route('module.rating.course',['type'=>$request->course_type,'id'=>$request->course]);
            return redirect($url);
        } elseif($request->type=='quiz') { // thi
            $quiz_id = $request->quiz;
            $quiz_register = QuizRegister::whereQuizId($quiz_id)->whereUserId($user_id);
            if($quiz_register->exists()){
                if (url_mobile()) {
                    $url = route('module.quiz_mobile.doquiz.index', ['quiz_id' => $request->quiz, 'part_id' => $request->part]);
                } else {
                    $url = route('module.quiz.doquiz.index', ['quiz_id' => $request->quiz, 'part_id' => $request->part]);
                }
                return redirect($url);
            }else {
                return redirect()->route('qrcode_message')->with('error','Bạn chưa ghi danh vào kỳ thi này');
            }
        } elseif($request->type == 'survey') {
            $profile = profile();
            $objects = SurveyObject::where('survey_id', $request->survey_id)->get();
            if(!$objects->isEmpty()) {
                $checkObject = 1;
                foreach ($objects as $key => $object) {
                    if($object->title_id == $profile->title_id || $object->unit_id == $profile->unit_id || $object->user_id == $profile->user_id) {
                        $checkObject = 0;
                    }
                }
            } else {
                $checkObject = 0;
            }
            if($checkObject == 0) {
                $checkUserSurvey = SurveyUser::where(['user_id' => profile()->user_id, 'survey_id' => $request->survey_id])->exists();
                if($checkUserSurvey) {
                    $url = route('themes.mobile.survey.user.edit',['id' => $request->survey_id]);
                } else {
                    $url = route('themes.mobile.survey.user',['id' => $request->survey_id]);

                }
                return redirect($url);
            } else {
                return redirect()->route('qrcode_message')->with('error','Bạn không thuộc đối tượng tham gia khảo sát');
            }
        } elseif($request->type=='teacher_attendance') {
            $x=OfflineAttendance::updateAttendance($request->user,$request->course,$request->schedule, '2.GVQRC');
            if ($x){
                $count_schedule = OfflineSchedule::whereCourseId($request->course)->count();
                $count_attendance = OfflineAttendance::whereUserId($request->user)->where('course_id', '=', $request->course)->count();
                if ($count_attendance == $count_schedule){
                    \Artisan::call('command:offline_complete '.$request->user .' '.$request->course);
                }

                return redirect()->route('frontend.attendance.course',['course_id'=>$request->course]+ ['schedule'=>$request->schedule])->with('success','Điểm danh thành công');
            }else{
                return redirect()->route('frontend.attendance.course',['course_id'=>$request->course]+ ['schedule'=>$request->schedule])->with('error','Học viên này chưa được ghi danh vào khóa học');
            }
        } elseif($request->type=='referer') {
            $user = Profile::where('user_id','=',$request->user)->first();
            if ($user){
                if (!$user->referer){
                    Profile::where('user_id','=',profile()->user_id)->update(['referer'=>$user->id_code]);
                    PromotionUserPoint::updatePoint($user->id_code);
                }
                return redirect()->route('frontend.user.referer')->with('success','Cập nhật người giới thiệu thành công');
            }
            else
                return redirect()->route('frontend.user.referer' )->with('error','User không tồn tại trong hệ thống');
        } elseif($request->type=='referer-course') {
            $user = Profile::where('user_id','=',$request->user)->first();
            if ($user){
                return json_result(['message'=>'ok','data'=>$user->id_code,'status'=>'success']);
            }
            else
                return json_result(['message'=>'error','status'=>'error']);
        } elseif($request->type=='entrance_quiz') {//Kỳ thi đầu vào offline
            $course_id = $request->course;
            $course_type = $request->course_type;
            $quiz_id = $request->quiz_id;

            $quiz_register = QuizRegister::whereQuizId($quiz_id)->whereUserId($user_id);
            if($quiz_register->exists()){
                if (url_mobile()){
                    $url = route('module.quiz_mobile.doquiz.index',['quiz_id'=>$quiz_register->quiz_id,'part_id'=>$quiz_register->part_id]);
                }else{
                    $url = route('module.quiz.doquiz.index',['quiz_id'=>$quiz_register->quiz_id,'part_id'=>$quiz_register->part_id]);
                }

                return redirect($url);
            }else{
                return redirect()->route('qrcode_message')->with('error','Học viên này chưa được ghi danh kỳ thi');
            }

        } elseif($request->type=='end_quiz') {//Kỳ thi cuối khoá offline
            $course_id = $request->course;
            $course_type = $request->course_type;
            $quiz_id = $request->quiz_id;

            $quiz_register = QuizRegister::whereQuizId($quiz_id)->whereUserId($user_id);
            if($quiz_register->exists()){
                if (url_mobile()){
                    $url = route('module.quiz_mobile.doquiz.index',['quiz_id'=>$quiz_register->quiz_id,'part_id'=>$quiz_register->part_id]);
                }else{
                    $url = route('module.quiz.doquiz.index',['quiz_id'=>$quiz_register->quiz_id,'part_id'=>$quiz_register->part_id]);
                }

                return redirect($url);
            }else{
                return redirect()->route('qrcode_message')->with('error','Học viên này chưa được ghi danh kỳ thi');
            }

        } elseif($request->type == 'rating_level_online') {
            $course_id = $request->course;
            $online_rating_level_id = $request->online_rating_level_id;

            $register = OnlineRegister::whereCourseId($course_id)->whereUserId($user_id)->whereStatus(1);
            if($register->exists()){
                $rating_level_object = OnlineRatingLevelObject::query()
                    ->where('course_id', '=', $course_id)
                    ->where('online_rating_level_id', '=', $online_rating_level_id)
                    ->where('object_type', '=', 1)
                    ->first();
                if ($rating_level_object){
                    $start_date_rating = '';
                    $end_date_rating = '';
                    $rating_level_url = '';

                    $course = OnlineCourse::find($course_id);

                    $user_rating_level_course = RatingLevelCourse::query()
                        ->where('course_rating_level_id', '=', $online_rating_level_id)
                        ->where('user_id', '=', $user_id)
                        ->where('user_type', '=', 1)
                        ->where('course_id', '=', $course_id)
                        ->where('course_type', '=', 1)
                        ->first();
                    if ($user_rating_level_course){ //Check đã làm đánh giá
                        $rating_level_url = route('module.rating_level.edit_course', [$course_id, 1, $online_rating_level_id, $user_rating_level_course->rating_user]).'?rating_level_object_id='.@$rating_level_object->id.'&view_type=course';

                        return redirect($rating_level_url);
                    }

                    if ($rating_level_object->time_type == 1){
                        $start_date_rating = $rating_level_object->start_date;
                        $end_date_rating = $rating_level_object->end_date;
                    }
                    if ($rating_level_object->time_type == 2){
                        if (isset($rating_level_object->num_date)){
                            $start_date_rating = date("Y-m-d", strtotime($course->start_date)) . " +{$rating_level_object->num_date} day";
                        }else{
                            $start_date_rating= $course->start_date;
                        }
                    }
                    if ($rating_level_object->time_type == 3){
                        if (isset($rating_level_object->num_date)){
                            $start_date_rating = date("Y-m-d", strtotime($course->end_date)) . " +{$rating_level_object->num_date} day";
                        }else{
                            $start_date_rating = $course->end_date;
                        }
                    }
                    if ($rating_level_object->time_type == 4){
                        $result = OnlineResult::query()
                            ->where('course_id', '=', $course_id)
                            ->where('user_id', '=', $user_id)
                            ->where('result', '=', 1)
                            ->first();

                        if ($result){
                            if (isset($rating_level_object->num_date)){
                                $start_date_rating = date("Y-m-d", strtotime($result->created_at)) . " +{$rating_level_object->num_date} day";
                            }else{
                                $start_date_rating = $result->created_at;
                            }
                        }else{
                            return redirect()->route('qrcode_message')->with('error','Bạn chưa hoàn thành khoá học. Không đủ điều kiện đánh giá');
                        }
                    }

                    if ($start_date_rating && $start_date_rating > now()){
                        return redirect()->route('qrcode_message')->with('error','Chưa tới thời gian đánh giá');
                    }

                    if ($end_date_rating && $end_date_rating < now()){
                        return redirect()->route('qrcode_message')->with('error','Kết thúc thời gian đánh giá');
                    }

                    if ($rating_level_object->user_completed == 1){
                        $result = OnlineResult::query()
                            ->where('course_id', '=', $course_id)
                            ->where('user_id', '=', $user_id)
                            ->where('result', '=', 1);
                        if (!$result->exists()){
                            return redirect()->route('qrcode_message')->with('error','Bạn chưa hoàn thành khoá học. Không đủ điều kiện đánh giá');
                        }
                    }

                    $rating_level_url = route('module.rating_level.course', [$course_id, 1, $online_rating_level_id, $user_id]).'?rating_level_object_id='.@$rating_level_object->id.'&view_type=course';

                    return redirect($rating_level_url);
                }else{
                    return redirect()->route('qrcode_message')->with('error','Bài đánh giá chưa thiết lập đối tượng');
                }
            }else{
                return redirect()->route('qrcode_message')->with('error','Học viên này chưa được ghi danh khóa học');
            }
        } elseif($request->type == 'rating_level_offline') {
            $course_id = $request->course;
            $offline_rating_level_id = $request->offline_rating_level_id;

            $register = OfflineRegister::whereCourseId($course_id)->whereUserId($user_id)->whereStatus(1);
            if($register->exists()){
                $rating_level_object = OfflineRatingLevelObject::query()
                    ->where('course_id', '=', $course_id)
                    ->where('offline_rating_level_id', '=', $offline_rating_level_id)
                    ->where('object_type', '=', 1)
                    ->first();
                if ($rating_level_object){
                    $start_date_rating = '';
                    $end_date_rating = '';
                    $rating_level_url = '';

                    $course = OfflineCourse::find($course_id);

                    $user_rating_level_course = RatingLevelCourse::query()
                        ->where('course_rating_level_id', '=', $offline_rating_level_id)
                        ->where('user_id', '=', $user_id)
                        ->where('user_type', '=', 1)
                        ->where('course_id', '=', $course_id)
                        ->where('course_type', '=', 2)
                        ->first();
                    if ($user_rating_level_course){ //Check đã làm đánh giá
                        $rating_level_url = route('module.rating_level.edit_course', [$course_id, 2, $offline_rating_level_id, $user_rating_level_course->rating_user]).'?rating_level_object_id='.@$rating_level_object->id.'&view_type=course';

                        return redirect($rating_level_url);
                    }

                    if ($rating_level_object->time_type == 1){
                        $start_date_rating = $rating_level_object->start_date;
                        $end_date_rating = $rating_level_object->end_date;
                    }
                    if ($rating_level_object->time_type == 2){
                        if (isset($rating_level_object->num_date)){
                            $start_date_rating = date("Y-m-d", strtotime($course->start_date)) . " +{$rating_level_object->num_date} day";
                        }else{
                            $start_date_rating= $course->start_date;
                        }
                    }
                    if ($rating_level_object->time_type == 3){
                        if (isset($rating_level_object->num_date)){
                            $start_date_rating = date("Y-m-d", strtotime($course->end_date)) . " +{$rating_level_object->num_date} day";
                        }else{
                            $start_date_rating = $course->end_date;
                        }
                    }
                    if ($rating_level_object->time_type == 4){
                        $result = OfflineResult::query()
                            ->where('course_id', '=', $course_id)
                            ->where('user_id', '=', $user_id)
                            ->where('result', '=', 1)
                            ->first();

                        if ($result){
                            if (isset($rating_level_object->num_date)){
                                $start_date_rating = date("Y-m-d", strtotime($result->created_at)) . " +{$rating_level_object->num_date} day";
                            }else{
                                $start_date_rating = $result->created_at;
                            }
                        }else{
                            return redirect()->route('qrcode_message')->with('error','Bạn chưa hoàn thành khoá học. Không đủ điều kiện đánh giá');
                        }
                    }

                    if ($start_date_rating && $start_date_rating > now()){
                        return redirect()->route('qrcode_message')->with('error','Chưa tới thời gian đánh giá');
                    }

                    if ($end_date_rating && $end_date_rating < now()){
                        return redirect()->route('qrcode_message')->with('error','Kết thúc thời gian đánh giá');
                    }

                    if ($rating_level_object->user_completed == 1){
                        $result = OfflineResult::query()
                            ->where('course_id', '=', $course_id)
                            ->where('user_id', '=', $user_id)
                            ->where('result', '=', 1);
                        if (!$result->exists()){
                            return redirect()->route('qrcode_message')->with('error','Bạn chưa hoàn thành khoá học. Không đủ điều kiện đánh giá');
                        }
                    }

                    $rating_level_url = route('module.rating_level.course', [$course_id, 2, $offline_rating_level_id, $user_id]).'?rating_level_object_id='.@$rating_level_object->id.'&view_type=course';

                    return redirect($rating_level_url);
                }else{
                    return redirect()->route('qrcode_message')->with('error','Bài đánh giá chưa thiết lập đối tượng');
                }
            }else{
                return redirect()->route('qrcode_message')->with('error','Học viên này chưa được ghi danh khóa học');
            }
        } else if ($request->type == 'rating-teaching-organization') { // đánh giá công tác tổ chức  sau khóa học
            $checkRegister = OfflineRegister::where('user_id', '=', profile()->user_id)
                ->where('course_id', '=', $request->id)
                ->where('status', '=', 1)
                ->exists();
            if($checkRegister) {
                $organization_user = OfflineTeachingOrganizationUser::where('course_id', $request->id)->where('user_id', profile()->user_id)->exists();
                if($organization_user) {
                    $url = route('themes.mobile.frontend.offline.detail.rating_teacher.edit', ['course_id' => $request->id]);
                } else {
                    $url = route('themes.mobile.frontend.offline.detail.rating_teacher', ['course_id' => $request->id]);
                }
                return redirect($url);
            } else {
                return redirect()->route('qrcode_message')->with('error', 'Bạn chưa đăng ký khóa học này');
            }
        }

        return abort('404');
    }
}
