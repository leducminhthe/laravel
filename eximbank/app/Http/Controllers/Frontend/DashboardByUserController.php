<?php

namespace App\Http\Controllers\Frontend;

use App\Models\DashboardByUser;
use App\Models\Guide;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\Unit;
use App\Models\CourseRegisterView;
use App\Models\LoginHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\DailyTraining\Entities\DailyTrainingVideo;
use Modules\Forum\Entities\ForumThread;
use Modules\Libraries\Entities\Libraries;
use Modules\Notify\Entities\NotifyCountUser;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivityCompletion;
use Modules\Online\Entities\OnlineCourseActivityHistory;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use Modules\Quiz\Entities\QuizResult;
use Modules\Survey\Entities\SurveyUser;
use Modules\User\Entities\TrainingProcess;

class DashboardByUserController extends Controller
{
    public function index()
    {
        $user_id = profile()->user_id;
        $full_name = profile()->full_name;

        $dashboard_online_complete = $this->dashboardOnlineComplete($user_id);
        $dashboard_online_register = $this->dashboardOnlineRegister($user_id);
        $dashboard_activity_joined = $this->dashboardActivityJoined($user_id);
        $dashboard_user_has_post = $this->dashboardUserHasPost($user_id);
        $dashboard_online_register_by_quarter = $this->dashboardOnlineRegisterByQuarter($user_id);
        $dashboard_user_post_with_like = $this->dashboardUserPostWithLike($user_id);
        $dashboard_you_post_liked_more = $this->dashboardPostLikedMore($user_id);
        $dashboard_top_in_unit = $this->dashboardTopInUnit($user_id);
        $dashboard_top_user = $this->dashboardTopUser($user_id);

        $history_login = LoginHistory::whereUserId($user_id)->where(DB::raw('year(created_at)'), date('Y'))->count();
        $notify_count_user = NotifyCountUser::where('user_id', $user_id)->first(['num_notify']);

        return view('frontend.dashboard_by_user', [
            'full_name' => $full_name,

            'online_result' => !empty($dashboard_online_complete) ? $dashboard_online_complete['online_result'] : [],
            'online_course' => !empty($dashboard_online_complete) ? $dashboard_online_complete['online_course'] : [],
            'online_history_min' => !empty($dashboard_online_complete) ? $dashboard_online_complete['online_history_min'] : [],

            'count_online_register_by_year' => !empty($dashboard_online_register) ? $dashboard_online_register['count_online_register_by_year'] : [],
            'year_dashboard_online_register' => !empty($dashboard_online_register) ? $dashboard_online_register['year_dashboard_online_register'] : [],

            'dashboard_activity_joined' => $dashboard_activity_joined,

            'year_dashboard_user_has_post' => !empty($dashboard_user_has_post) ? $dashboard_user_has_post['year_dashboard_user_has_post'] : [],
            'count_forum_thread' => !empty($dashboard_user_has_post) ? $dashboard_user_has_post['count_forum_thread'] : [],

            'quarter' => !empty($dashboard_online_register_by_quarter) ? $dashboard_online_register_by_quarter['quarter'] : [],
            'count_register_by_quarter' => !empty($dashboard_online_register_by_quarter) ? $dashboard_online_register_by_quarter['count_register_by_quarter'] : [],

            'year_dashboard_user_post_with_like' => !empty($dashboard_user_post_with_like) ? $dashboard_user_post_with_like['year_dashboard_user_post_with_like'] : [],
            'count_forum_thread_like' => !empty($dashboard_user_post_with_like) ? $dashboard_user_post_with_like['count_forum_thread_like'] : [],

            'year_dashboard_you_post_liked_more' => !empty($dashboard_you_post_liked_more) ? $dashboard_you_post_liked_more['year_dashboard_you_post_liked_more'] : [],
            'forum_thread_like_more' => !empty($dashboard_you_post_liked_more) ? $dashboard_you_post_liked_more['forum_thread_like_more'] : [],

            'year_dashboard_top_in_unit' => !empty($dashboard_top_in_unit) ? $dashboard_top_in_unit['year_dashboard_top_in_unit'] : [],
            'count_result' => !empty($dashboard_top_in_unit) ? $dashboard_top_in_unit['count_result'] : [],

            'year_dashboard_top_user' => !empty($dashboard_top_user) ? $dashboard_top_user['year_dashboard_top_user'] : [],
            'top_user' => !empty($dashboard_top_user) ? $dashboard_top_user['top_user'] : [],

            'chart' => $this->ChartUser($user_id),
            'history_login' => $history_login,
            'notify_count_user'=> $notify_count_user,
        ]);
    }

    //-Khóa học Elearning: Hoàn thành sớm
    public function dashboardOnlineComplete($user_id){
        $data = [];

        $dashboard_online_complete = DashboardByUser::where('code', 'online_complete')->first(['condition']);
        if (isset($dashboard_online_complete->condition)){
            $order_by = $dashboard_online_complete->condition == 1 ? 'ASC' : 'DESC';
            $online_result = OnlineResult::query()
                ->where('user_id', $user_id)
                ->where('result', 1)
                ->where(DB::raw('month(created_at)'), date('m'))
                ->orderBy('created_at', $order_by)
                ->first(['updated_at']);

            $online_course = OnlineCourse::find(@$online_result->course_id, ['name']);

            $online_history_min = OnlineCourseActivityHistory::whereCourseId(@$online_result->course_id)
                ->where('user_id', $user_id)
                ->min('created_at');

            $data['online_result'] = $online_result;
            $data['online_course'] = $online_course;
            $data['online_history_min'] = $online_history_min;
        }

        return $data;
    }

    //-Bạn đã tham gia …..  khóa học trực tuyến trong năm
    public function dashboardOnlineRegister($user_id){
        $dashboard_online_register = DashboardByUser::where('code', 'online_register')->first(['year']);
        $year_dashboard_online_register = $dashboard_online_register->year ? $dashboard_online_register->year : date('Y');

        $count_online_register_by_year = OnlineRegister::whereUserId($user_id)
            ->where(DB::raw('year(created_at)'), $year_dashboard_online_register)
            ->count();

        $data['year_dashboard_online_register'] = $year_dashboard_online_register;
        $data['count_online_register_by_year'] = $count_online_register_by_year;

        return $data;
    }

    //-Các hoạt động bạn đã tham gia: (Đếm số thôi) (Trong năm)
    public function dashboardActivityJoined($user_id){
        $dashboard_activity_joined = DashboardByUser::where('code', 'activity_joined')->first(['year']);
        $year = $dashboard_activity_joined->year ? $dashboard_activity_joined->year : date('Y');

        $online = OnlineRegister::where('user_id', $user_id)
            ->where('status',1)
            ->where(DB::raw('year(created_at)'), '=', $year)
            ->count();

        $offline = OfflineRegister::where('user_id', $user_id)
            ->where('status',1)
            ->where(DB::raw('year(created_at)'), '=', $year)
            ->count();

        $survey = SurveyUser::whereUserId($user_id)
            ->where('send', 1)
            ->where(DB::raw('year(created_at)'), '=', $year)
            ->count();

        $quiz = QuizResult::whereUserId($user_id)
            ->where(DB::raw('year(created_at)'), '=', $year)
            ->whereNull('text_quiz')
            ->count();

        $forum_thread = ForumThread::where('created_by',$user_id)
            ->where('status', 1)
            ->where(DB::raw('year(created_at)'), '=', $year)
            ->count();

        $training_video = DailyTrainingVideo::where('created_by',$user_id)
            ->where('status', 1)
            ->where(DB::raw('year(created_at)'), '=', $year)
            ->count();

        $ebook = Libraries::whereType(2)
            ->where('created_by',$user_id)
            ->where('status', 1)
            ->where(DB::raw('year(created_at)'), '=', $year)
            ->count();

        $total = ($online + $offline + $survey + $quiz + $forum_thread + $training_video + $ebook);

        $data['year'] = $year;
        $data['online'] = $online;
        $data['offline'] = $offline;
        $data['survey'] = $survey;
        $data['quiz'] = $quiz;
        $data['forum_thread'] = $forum_thread;
        $data['training_video'] = $training_video;
        $data['ebook'] = $ebook;
        $data['total'] = $total > 0 ? $total : 1;

        return $data;
    }

    //-Bạn đã có ... bài viết
    public function dashboardUserHasPost($user_id){
        $dashboard_user_has_post = DashboardByUser::where('code', 'user_has_post')->first(['year']);
        $year_dashboard_user_has_post = $dashboard_user_has_post->year ? $dashboard_user_has_post->year : date('Y');

        $count_forum_thread = ForumThread::where('created_by', $user_id)
            ->where('status', 1)
            ->where(DB::raw('year(created_at)'), $year_dashboard_user_has_post)
            ->count();

        $data['year_dashboard_user_has_post'] = $year_dashboard_user_has_post;
        $data['count_forum_thread'] = $count_forum_thread;

        return $data;
    }

    //-Bài viết bạn đã có …. người like
    public function dashboardUserPostWithLike($user_id){
        $dashboard_user_post_with_like = DashboardByUser::where('code', 'user_post_with_like')->first(['year']);
        $year_dashboard_user_post_with_like = $dashboard_user_post_with_like->year ? $dashboard_user_post_with_like->year : date('Y');

        $count_forum_thread_like = DB::query()
            ->from('el_forum_thread as a')
            ->leftJoin('el_forum_user_like_thread as b', 'b.thread_id', '=', 'a.id')
            ->where('b.like', 1)
            ->where('a.created_by', $user_id)
            ->where('a.status', 1)
            ->where(DB::raw('year('.DB::getTablePrefix().'a.created_at)'), $year_dashboard_user_post_with_like)
            ->count();

        $data['year_dashboard_user_post_with_like'] = $year_dashboard_user_post_with_like;
        $data['count_forum_thread_like'] = $count_forum_thread_like;

        return $data;
    }

    //-Bài post được like nhiều hơn
    public function dashboardPostLikedMore($user_id){
        $dashboard_you_post_liked_more = DashboardByUser::where('code', 'you_post_liked_more')->first(['year']);
        $year_dashboard_you_post_liked_more = $dashboard_you_post_liked_more->year ? $dashboard_you_post_liked_more->year : date('Y');

        $forum_thread_like_more = ForumThread::query()
            ->where('created_by', $user_id)
            ->where('status', 1)
            ->where(DB::raw('year(created_at)'), $year_dashboard_you_post_liked_more)
            ->where('total_like', '>', 0)
            ->orderByDesc('total_like')
            ->first(['title','total_like','content']);

        $data['year_dashboard_you_post_liked_more'] = $year_dashboard_you_post_liked_more;
        $data['forum_thread_like_more'] = $forum_thread_like_more;

        return $data;
    }

    //Trong Quý …, bạn đã tham gia hơn…. Khóa học trực tuyến (Quý 1: 3 tháng 1 quý)
    public function dashboardOnlineRegisterByQuarter($user_id){
        $arr_quarter_1 = [1,2,3];
        $arr_quarter_2 = [4,5,6];
        $arr_quarter_3 = [7,8,9];
        $arr_quarter_4 = [10,11,12];

        if (in_array(date('m'), $arr_quarter_1)){
            $quarter = 1;
            $count_register_by_quarter = OnlineRegister::whereUserId($user_id)
                ->where(\DB::raw('year(created_at)'), '=', date('Y'))
                ->whereIn(\DB::raw('month(created_at)'), $arr_quarter_1)
                ->count();
        }
        if (in_array(date('m'), $arr_quarter_2)){
            $quarter = 2;
            $count_register_by_quarter = OnlineRegister::whereUserId($user_id)
                ->where(\DB::raw('year(created_at)'), '=', date('Y'))
                ->whereIn(\DB::raw('month(created_at)'), $arr_quarter_2)
                ->count();
        }
        if (in_array(date('m'), $arr_quarter_3)){
            $quarter = 3;
            $count_register_by_quarter = OnlineRegister::whereUserId($user_id)
                ->where(\DB::raw('year(created_at)'), '=', date('Y'))
                ->whereIn(\DB::raw('month(created_at)'), $arr_quarter_3)
                ->count();
        }
        if (in_array(date('m'), $arr_quarter_4)){
            $quarter = 4;
            $count_register_by_quarter = OnlineRegister::whereUserId($user_id)
                ->where(\DB::raw('year(created_at)'), '=', date('Y'))
                ->whereIn(\DB::raw('month(created_at)'), $arr_quarter_4)
                ->count();
        }

        $data['quarter'] = $quarter;
        $data['count_register_by_quarter'] = $count_register_by_quarter;

        return $data;
    }

    //-Bạn thuộc Top học viên năng động trong …. Thành viên trong phòng Ban của bạn
    public function dashboardTopInUnit($user_id){
        $dashboard_top_in_unit = DashboardByUser::where('code', 'top_in_unit')->first(['condition','year']);
        $year_dashboard_top_in_unit = $dashboard_top_in_unit->year ? $dashboard_top_in_unit->year : date('Y');

        $profile = Profile::find($user_id);
        $unit_arr = Unit::getParentUnitCodeByLevel(@$profile->unit_code, @$dashboard_top_in_unit->condition);

        $count_result = OnlineResult::whereResult(1)
            ->leftJoin('el_profile', 'el_profile.user_id', '=', 'el_online_result.user_id')
            ->whereIn('el_profile.unit_code', $unit_arr)
            ->where(DB::raw('year('.DB::getTablePrefix().'el_online_result.created_at)'), $year_dashboard_top_in_unit)
            ->count();

        $data['year_dashboard_top_in_unit'] = $year_dashboard_top_in_unit;
        $data['count_result'] = $count_result;

        return $data;
    }

    //-Nằm trong Top ... học viên xuất sắc trong năm
    public function dashboardTopUser($user_id){
        $dashboard_top_user = DashboardByUser::where('code', 'top_user')->first(['condition','year']);
        $year_dashboard_top_user = $dashboard_top_user->year ? $dashboard_top_user->year : date('Y');

        $count_top = CourseRegisterView::whereStatus(1)
            ->where(DB::raw('year(created_at)'), $year_dashboard_top_user)
            ->limit(@$dashboard_top_user->condition)
            ->pluck('user_id')
            ->toArray();

        $data = [];
        if(in_array($user_id, $count_top)){
            $data['year_dashboard_top_user'] = $year_dashboard_top_user;
            $data['top_user'] = $dashboard_top_user->condition;
        }

        return $data;
    }

    //Đây là biểu đồ tương tác của bạn
    public function ChartUser($user_id){
        $year = date('Y');
        for ($m = 1; $m <= 12; $m++) {
            $onlineRegister = OnlineRegister::where('user_id', $user_id)
                ->where('status',1)
                ->where(DB::raw('month(created_at)'), '=', $m)
                ->where(DB::raw('year(created_at)'), '=', $year)->count();

            $offlineRegister = OfflineRegister::where('user_id', $user_id)
                ->where('status',1)
                ->where(DB::raw('month(created_at)'), '=', $m)
                ->where(DB::raw('year(created_at)'), '=', $year)->count();

            $surveyUser = SurveyUser::whereUserId($user_id)
                ->where('send', 1)
                ->where(DB::raw('month(created_at)'), '=', $m)
                ->where(DB::raw('year(created_at)'), '=', $year)
                ->count();

            $quizResult = QuizResult::whereUserId($user_id)
                ->where(DB::raw('month(created_at)'), '=', $m)
                ->where(DB::raw('year(created_at)'), '=', $year)
                ->whereNull('text_quiz')
                ->count();

            $forumThread = ForumThread::where('created_by',$user_id)
                ->where('status', 1)
                ->where(DB::raw('month(created_at)'), '=', $m)
                ->where(DB::raw('year(created_at)'), '=', $year)
                ->count();

            $daily_training_video = DailyTrainingVideo::where('created_by',$user_id)
                ->where('status', 1)
                ->where(DB::raw('month(created_at)'), '=', $m)
                ->where(DB::raw('year(created_at)'), '=', $year)
                ->count();

            $libraries_2 = Libraries::whereType(2)
                ->where('created_by',$user_id)
                ->where('status', 1)
                ->where(DB::raw('month(created_at)'), '=', $m)
                ->where(DB::raw('year(created_at)'), '=', $year)
                ->count();

            $online[] = $onlineRegister;
            $offline[] = $offlineRegister;
            $survey[] = $surveyUser;
            $quiz[] = $quizResult;
            $forum_thread[] = $forumThread;
            $training_video[] = $daily_training_video;
            $ebook[] = $libraries_2;
        }

        $data['online'] = $online;
        $data['offline'] = $offline;
        $data['survey'] = $survey;
        $data['quiz'] = $quiz;
        $data['forum_thread'] = $forum_thread;
        $data['training_video'] = $training_video;
        $data['ebook'] = $ebook;

        return $data;
    }
}
