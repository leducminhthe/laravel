<?php

namespace App\Http\Controllers\Frontend;

use App\Models\CourseBookmark;
use App\Models\CourseComplete;
use App\Models\Feedback;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Notifications;
use App\Models\Permission;
use App\Models\Profile;
use App\Models\ProfileView;
use App\Scopes\CompanyScope;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppMobile;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\TrainingTeacherHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\ConvertTitles\Entities\ConvertTitles;
use Modules\Forum\Entities\ForumComment;
use Modules\Forum\Entities\ForumThread;
use Modules\Libraries\Entities\Libraries;
use Modules\News\Entities\News;
use Modules\Notify\Entities\Notify;
use Modules\Notify\Entities\NotifySend;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineCourse;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use Modules\Potential\Entities\Potential;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Modules\User\Entities\TrainingProcess;
use Modules\Survey\Entities\SurveyUser;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Entities\SurveyObject;
use App\Models\CourseView;
use App\Models\CourseRegisterView;
use App\Models\CountUserExperienceNavigate;
use App\Models\InteractionHistory;
use App\Models\InteractionHistoryName;
use App\Models\SettingExperienceNavigate;
use App\Models\TotalTimeUserLearnInYear;
use Artisan;
use Illuminate\Support\Facades\Cache;
use Modules\TargetManager\Entities\TargetManager;
use Modules\TargetManager\Entities\TargetManagerParent;
use Modules\Online\Entities\OnlineCourseTimeUserLearn;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineTeamsAttendanceReport;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\TargetManager\Entities\TargetManagerGroup;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function changeLanguage($lang)
    {
        $user_id = profile()->user_id;
        session()->put('locale_'.$user_id, $lang);

        if (url_mobile()){
            if(session()->has('locale_'.$user_id)){
                \App::setLocale(session()->get('locale_'.$user_id));
            }
            Artisan::call('view:clear');
            return redirect()->back();
        }

        if(session()->has('locale_'.$user_id)){
            \App::setLocale(session()->get('locale_'.$user_id));
        }
        Artisan::call('view:clear');
        return redirect()->back();
    }

	public function homeAfterLogin()
    {
        $user_id = getUserId();
        $user_type = getUserType();

        Slider::addGlobalScope(new CompanyScope());

        $profile_view = profile();
        $sliders = Slider::where('type',1)->where('location','all')->where('status', 1)->get();
        $app_android = AppMobile::where('type', '=', 1)->first();
        $app_apple = AppMobile::where('type', '=', 2)->first();

        return view('frontend.home_after_login',[
            'user_type' => $user_type,
            'profile_view' => $profile_view,
            'sliders' => $sliders,
            'app_android' => $app_android,
            'app_apple' => $app_apple,
        ]);
    }

    public function index()
    {
        $profile = profile();
        $user_id = $profile->user_id;
        $user_type = $profile->type_user;
        $date = date('Y-m-d H:i:s');

        $laster_news = News::getLasterNews();

        $countRegister = CourseRegisterView::where(['user_id' => $user_id, 'status' => 1])->count();

        $quiz_by_offline = OfflineCourse::whereNotNull('quiz_id')
        ->where('status', '=', 1)
        ->where('isopen', '=', 1)
        ->pluck('quiz_id')
        ->toArray();

        $quiz = Quiz::query()
            ->from('el_quiz AS a')
            ->join('el_quiz_register AS b', 'b.quiz_id', '=', 'a.id')
            ->where('a.status', '=', 1)
            ->where('a.is_open', '=', 1)
            ->where(function ($sub) use ($quiz_by_offline){
                $sub->orWhere('a.quiz_type', '=', 3);
                $sub->orWhereIn('a.id', $quiz_by_offline);
            })
            ->where('b.user_id', '=', $user_id)
            ->where('b.type', '=', $user_type)
            ->pluck('a.id')->toArray();
        $count_quiz = count($quiz);

        $count_quiz_not_register = Quiz::query()
        ->from('el_quiz')
        ->where(['el_quiz.quiz_not_register' => 1, 'el_quiz.status' => 1, 'el_quiz.is_open' => 1, 'el_quiz.quiz_type' => 3])
        ->whereNotIn('el_quiz.id', $quiz)
        ->whereExists(function($sub) use ($date) {
            $sub->select(['quiz_id'])
                ->from('el_quiz_part as part')
                ->where('part.end_date', '>', $date)
                ->whereColumn('part.quiz_id', '=', 'el_quiz.id');
        })
        ->count();

        $total_quiz = $count_quiz + $count_quiz_not_register;

        $is_teacher = Permission::isTeacher();
        $total_hour_teacher = 0;
        $total_course_teacher = 0;
        if($is_teacher){
            $training_teacher = TrainingTeacher::where('user_id', '=', $user_id)->where('status', '=', 1)->first();

            $total_hour_teacher = TrainingTeacherHistory::where('teacher_id', $training_teacher->id)->where('year', date('Y'))->sum('num_hour');
            $total_course_teacher = TrainingTeacherHistory::where('teacher_id', $training_teacher->id)->where('year', date('Y'))->groupBy('course_id')->get('course_id')->count();
        }

        $count_complete_course_by_user = CourseComplete::where('user_id', '=', $user_id)->count();

        $model_online = DB::query();
        $model_online->select(['id','start_date','end_date','register_deadline','name','code']);
        $model_online->from('el_online_course');
        $model_online->where('el_online_course.status', 1);
        $model_online->where('el_online_course.isopen', 1);
        $model_online->whereExists(function($sub) use($user_id){
            $sub->selectRaw('1')
                ->from('el_online_register')
                ->whereColumn('el_online_register.course_id', '=', 'el_online_course.id')
                ->where('el_online_register.status', 1)
                ->where('el_online_register.user_id', $user_id);
        });
        $countMyOnlineCourse = $model_online->count();
        $my_onlCourse = $model_online->take(5)->get();

        $model_offline = DB::query();
        $model_offline->select(['id','start_date','end_date','register_deadline','name','code']);
        $model_offline->from('el_offline_course');
        $model_offline->where('el_offline_course.status',1);
        $model_offline->where('el_offline_course.isopen',1);
        $model_offline->whereExists(function($sub) use($user_id){
            $sub->selectRaw('1')
                ->from('el_offline_register')
                ->whereColumn('el_offline_register.course_id', '=', 'el_offline_course.id')
                ->where('el_offline_register.status', 1)
                ->where('el_offline_register.user_id', $user_id);
        });
        $countMyOfflineCourse = $model_offline->count();
        $my_offCourse = $model_offline->take(5)->get();

        $userPoint = PromotionUserPoint::where('user_id', $user_id)->first(['point']);
        $point = $userPoint ? $userPoint->point : 0;

        $notify = NotifySend::getNotifyNew(5);
        $count_subject_by_level_subject = function ($level_subject_id, $complete = null){
            $profile = profile();
            $subQuery = TrainingProcess::query()
                ->where('user_id','=', $profile->user_id)
                ->where('titles_code','=', $profile->title_code)
                ->groupBy('subject_id')
                ->select([
                    \DB::raw('MAX(id) as id'),
                    'subject_id',
                ]);

            $query = TrainingRoadmap::query();
            $query->from("el_trainingroadmap AS a");
            $query->joinSub($subQuery,'b', function ($join){
                $join->on('b.subject_id', '=', 'a.subject_id');
            });
            $query->leftJoin('el_subject as d', 'd.id', '=', 'b.subject_id');
            $query->leftJoin('el_training_process as c', 'c.id', '=', 'b.id');
            $query->where('d.level_subject_id', '=', $level_subject_id);
            $query->where('a.title_id', '=', @$profile->title_id);
            if ($complete){
                $query->where('c.pass', '=', 1);
            }

            return $query->count();
        };

        $total_time_user_learn_year = TotalTimeUserLearnInYear::where('user_id', $user_id)->where('year', date('Y'))->first(['total_time']);
        $totalTime = $total_time_user_learn_year ? $total_time_user_learn_year->total_time : 0;

        return view('frontend.home', [
            'count_quiz' => $total_quiz,
            'countMyOnlineCourse' => $countMyOnlineCourse,
            'countMyOfflineCourse' => $countMyOfflineCourse,
            'my_onl' => $my_onlCourse,
            'my_off' => $my_offCourse,
            'point' => $point,
            'chart' => $this->getRegisterCourse($user_id),
            'notify' => $notify,
            'training_roadmap_course' => $this->getCourseTrainingRoadmap(),
            'laster_news' => $laster_news,
            'get_course_new' => $this->getFiveCourseNew(),
            'chartCourseByUser' => $this->chartCourseByUser($countRegister),
            'count_register_course_by_user' => $countRegister,
            'count_complete_course_by_user' => $count_complete_course_by_user,
            'chartSubjectByUser' => $this->chartSubjectByUser(),
            'count_register_subject_by_user' => $this->chartSubjectByUser()[0] + $this->chartSubjectByUser()[1],
            'count_complete_subject_by_user' => $this->chartSubjectByUser()[1],
            'getLevelSubjectByUser' => $this->getLevelSubjectByUser(),
            'count_subject_by_level_subject' => $count_subject_by_level_subject,
            'target_manager_by_year' => $this->targetManagerByYear($total_time_user_learn_year),
            'is_teacher' => $is_teacher,
            'total_hour_teacher' => $total_hour_teacher,
            'total_course_teacher' => $total_course_teacher,
            'chartCourseInOutTrainingRoadmap' => $this->chartCourseInOutTrainingRoadmap(),
            'totalTime' => $totalTime
        ]);
    }

    /*Quản lý chỉ tiêu theo năm*/
    public function targetManagerByYear($total_time_user_learn_year)
    {
        $profile = profile();
        $num_hour_student = 0;
        $num_course_student = 0;
        $num_hour_teacher = 0;
        $num_course_teacher = 0;
        $total_time_user_learn = 0;

        $target_manager_parent = TargetManagerParent::where('year', date('Y'))->first(['id']);
        if($target_manager_parent){
            $target_manager = TargetManager::where('parent_id', $target_manager_parent->id)->get();
            foreach($target_manager as $target){

                $group_object_title = TargetManagerGroup::where('target_manager_id', $target->id)->whereNull('user_id')->pluck('title_id')->toArray();
                $group_object_user = TargetManagerGroup::where('target_manager_id', $target->id)->whereNull('title_id')->pluck('user_id')->toArray();

                if ($target->type == 2 && in_array($profile->user_id, $group_object_user)) {
                    $num_hour_student += $target->num_hour_student;
                    $num_course_student += $target->num_course_student;
                    $num_hour_teacher += $target->num_hour_teacher;
                    $num_course_teacher += $target->num_course_teacher;
                    $total_time_user_learn += ($total_time_user_learn_year ? ($total_time_user_learn_year->time_second) : 0);
                }else if ($target->type == 1 && in_array($profile->title_id, $group_object_title)) {
                    $num_hour_student += $target->num_hour_student;
                    $num_course_student += $target->num_course_student;
                    $num_hour_teacher += $target->num_hour_teacher;
                    $num_course_teacher += $target->num_course_teacher;
                    $total_time_user_learn += ($total_time_user_learn_year ? ($total_time_user_learn_year->time_second) : 0);
                }
            }
        }

        $data['num_hour_student'] = $num_hour_student;
        $data['num_course_student'] = $num_course_student;
        $data['num_hour_teacher'] = $num_hour_teacher;
        $data['num_course_teacher'] = $num_course_teacher;

        if($total_time_user_learn > 0){
            $hours = floor($total_time_user_learn / 3600);
            $minutes = floor(($total_time_user_learn / 60) % 60);

            $data['total_time_user_learn'] = $hours . ":" . $minutes;
        }else{
            $data['total_time_user_learn'] = $total_time_user_learn;
        }

        return $data;
    }

    public function countOnline() {
        $query = OnlineCourse::query();
        $query->where('status', '=', 1);
        $query->where('isopen', '=', 1);
        return $query->count();
    }

    public function countOffline() {
        $query = OfflineCourse::query();
        $query->where('status', '=', 1);
        $query->where('isopen', '=', 1);
        return $query->count();
    }

    public function countBook() {
        $query = Libraries::query();
        $query->where('type', '=', 1);
        $query->where('status', '=', 1);
        return $query->count();
    }

    public function countEBook() {
        $query = Libraries::query();
        $query->where('type', '=', 2);
        $query->where('status', '=', 1);
        return $query->count();
    }

    public function countDocument() {
        $query = Libraries::query();
        $query->where('type', '=', 3);
        $query->where('status', '=', 1);
        return $query->count();
    }

    public function countQuiz() {
        $query = Quiz::query();
        $query->where('status', '=', 1);
        return $query->count();
    }

    public function saveCourseBookmark($course_id, $course_type, $my_course){
        $user_id = profile()->user_id;

        if (url_mobile()){
            $bookmark = CourseBookmark::where('course_id',$course_id)->where('type',$course_type)->where('user_id', $user_id);
            if (!$bookmark->exists()){
                $model = new CourseBookmark();
                $model->course_id = $course_id;
                $model->type = $course_type;
                $model->user_id = $user_id;
                $model->save();
            }

            if(url_mobile()) {
                if($course_type == 1) {
                    return redirect()->route('themes.mobile.frontend.online.detail', ['course_id' => $course_id]);
                } else {
                    return redirect()->route('themes.mobile.frontend.offline.detail', ['course_id' => $course_id]);
                }
            }

            if ($my_course == 0) {
                if($course_type == 1) {
                    $course = CourseView::where(['course_id' => $course_id, 'course_type' => $course_type])->first(['training_program_id']);
                    $url = route('frontend.all_course',['type' => $course_type]).'?trainingProgramId='. $course->training_program_id;
                    return redirect()->to($url);
                } else {
                    return redirect()->route('frontend.all_course',['type' => $course_type]);
                }
            } else if ($my_course == 1){
                return redirect()->route('module.frontend.user.my_course',['type' => 0]);
            }
        }

        $model = new CourseBookmark();
        $model->course_id = $course_id;
        $model->type = $course_type;
        $model->user_id = $user_id;
        $model->save();

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
            'check' => 1,
        ]);
    }

    public function removeCourseBookmark($course_id, $course_type, $my_course){
        $user_id = profile()->user_id;

        if (url_mobile()){

            CourseBookmark::query()
                ->where('course_id', '=', $course_id)
                ->where('type', '=', $course_type)
                ->where('user_id', '=', $user_id)
                ->delete();

            if(url_mobile()) {
                if($course_type == 1) {
                    return redirect()->route('themes.mobile.frontend.online.detail', ['course_id' => $course_id]);
                } else {
                    return redirect()->route('themes.mobile.frontend.offline.detail', ['course_id' => $course_id]);
                }
            }

            if ($my_course == 0) {
                if($course_type == 1) {
                    $course = CourseView::where(['course_id' => $course_id, 'course_type' => $course_type])->first(['training_program_id']);
                    $url = route('frontend.all_course',['type' => $course_type]).'?trainingProgramId='. $course->training_program_id;
                    return redirect()->to($url);
                } else {
                    return redirect()->route('frontend.all_course',['type' => $course_type]);
                }
            } else if ($my_course == 1){
                return redirect()->route('module.frontend.user.my_course',['type' => 0]);
            }
        }

        CourseBookmark::query()
            ->where('course_id', '=', $course_id)
            ->where('type', '=', $course_type)
            ->where('user_id', '=', $user_id)
            ->delete();

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
            'check' => 0,
        ]);
    }

    /*Tổng số user*/
    public function totalLearners(){
        $count = Profile::where('status', '!=', 0)->where('user_id', '>', 2)->count();
        return $count;
    }

    /*Đếm số Khóa học theo tháng*/
    public function getCourseNew(){
        $online = OnlineCourse::where('status', '=', 1)
            ->where(\DB::raw('month(start_date)'), '=', date('m'))->count();

        $offline = OfflineCourse::where('status', '=', 1)
            ->where(\DB::raw('month(start_date)'), '=', date('m'))->count();

        return ($online + $offline);
    }

    /*khóa học đang tổ chức*/
    public function countCourseBeingHeld(){
        $now = date('Y-m-d H:i:s');
        $online = OnlineCourse::where('status', '=', 1)
            ->where('start_date', '<=', $now)
            ->where(function ($sub) use ($now){
                $sub->where('end_date', '>=', $now);
                $sub->orWhereNull('end_date');
            })
            ->count();

        $offline = OfflineCourse::where('status', '=', 1)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->count();

        return ($online + $offline);
    }

    /*Lấy điểm cao nhất của học viên*/
    public function getUserMaxPoint(){
        $max_point = PromotionUserPoint::getMaxPoint();

        $user = Profile::query()
            ->select(['profile.user_id', 'user_point.point'])
            ->from('el_profile as profile')
            ->leftJoin('el_promotion_user_point as user_point', 'user_point.user_id', '=', 'profile.user_id')
            ->where('user_point.point', '=', $max_point)
            ->where('profile.status', '=', 1)
            ->whereNotIn('profile.user_id', function ($sub){
                $sub->select(['user_id'])
                    ->from('el_training_teacher')
                    ->pluck('user_id')->toArray();
            })->first();

        return $user;
    }

    /*Lấy giảng viên có điểm cao nhất*/
    public function getTeacherMaxPoint(){
        $max_point = PromotionUserPoint::getMaxPoint();

        $user = Profile::query()
            ->select(['profile.user_id', 'user_point.point'])
            ->from('el_profile as profile')
            ->leftJoin('el_promotion_user_point as user_point', 'user_point.user_id', '=', 'profile.user_id')
            ->where('user_point.point', '=', $max_point)
            ->where('profile.status', '=', 1)
            ->whereIn('profile.user_id', function ($sub){
                $sub->select(['user_id'])
                    ->from('el_training_teacher')
                    ->pluck('user_id')->toArray();
            })->first();

        return $user;
    }

    /*Lấy khóa học đã đăng ký theo năm chart*/
    public function getRegisterCourse($user_id)
    {
        $year = date('Y');
        for ($m = 1; $m <= 12; $m++) {
            $onlineRegister = TrainingProcess::where('user_id', $user_id)
                ->where('status',1)
                ->where('course_type',1)
                ->where(\DB::raw('month(created_at)'), '=', $m)
                ->where(\DB::raw('year(created_at)'), '=', $year)->count();

            $offlineRegister = TrainingProcess::where('user_id', $user_id)
                ->where('status',1)
                ->where('course_type',2)
                ->where(\DB::raw('month(created_at)'), '=', $m)
                ->where(\DB::raw('year(created_at)'), '=', $year)->count();

            $onlineComplete = TrainingProcess::where('user_id', $user_id)
                ->where('pass', 1)
                ->where('status',1)
                ->where('course_type',1)
                ->where(\DB::raw('month(start_date)'), '=', $m)
                ->where(\DB::raw('year(start_date)'), '=', $year)->count();

            $offlineComplete = TrainingProcess::where('user_id',$user_id)
                ->where('pass', 1)
                ->where('course_type',2)
                ->where(\DB::raw('year(start_date)'), '=', $year)
                ->where(\DB::raw('month(start_date)'), '=', $m)
                ->count();

            $totalQuiz = QuizRegister::where('user_id',$user_id)
                ->where(\DB::raw('month(created_at)'), '=', $m)
                ->where(\DB::raw('year(created_at)'), '=', $year)->count();

            $totalQuizComplete = QuizResult::where('user_id',$user_id)
                ->where('result', 1)
                ->whereNull('text_quiz')
                ->where(\DB::raw('month(created_at)'), '=', $m)
                ->where(\DB::raw('year(created_at)'), '=', $year)->count();

            $online[] = $onlineRegister;
            $offline[] = $offlineRegister;
            $onl_complete[] = $onlineComplete;
            $off_complete[] = $offlineComplete;
            $quiz[] = $totalQuiz;
            $quizComplete[] = $totalQuizComplete;
        }
        $totalOnlineRegisterYear =  TrainingProcess::where('user_id',$user_id)
            ->where('course_type',1)
            ->where('status',1)
            ->where(\DB::raw('year(start_date)'), '=', $year)->count();

        $onl_complete_year = TrainingProcess::where('user_id',$user_id)
            ->where('course_type',1)
            ->where('pass', 1)
            ->where(\DB::raw('year(start_date)'), '=', $year)->count();

        $onl_incomplete_year = $totalOnlineRegisterYear - $onl_complete_year;

        $totalOfflineRegisterYear = TrainingProcess::where('user_id',$user_id)
            ->where('course_type',2)
            ->where('status',1)
            ->where(\DB::raw('year(created_at)'), '=', $year)->count();

        $off_complete_year = TrainingProcess::where('user_id',$user_id)
            ->where('course_type',2)
            ->where('pass', 1)
            ->where(\DB::raw('year(start_date)'), '=', $year)
            ->count();

        $off_incomplete_year = $totalOfflineRegisterYear - $off_complete_year;

        $data['online'] = $online;
        $data['offline'] = $offline;
        $data['onl_complete'] = $onl_complete;
        $data['off_complete'] = $off_complete;
        $data['quiz'] = $quiz;
        $data['quiz_complete'] = $quizComplete;
        $data['onl_year'] = [$onl_incomplete_year,$onl_complete_year];
        $data['off_year'] = [$off_incomplete_year,$off_complete_year];
        return $data;
    }

    public function search(Request $request)
    {
        $profile = profile();
        $unit_user = Unit::getTreeParentUnit($profile->unit_code);

        $search = $request->input('search');
        $online = OnlineCourse::select(['id','code','name','image','start_date','end_date','register_deadline', \DB::raw('1 as type')])
            ->where(function ($sub) use ($search){
                $sub->orWhere('name', 'LIKE','%'.$search.'%')
                    ->orWhere('code','LIKE','%'.$search.'%');
            });

        if (!Permission::isAdmin()){
            $online->whereNull('unit_id');
            foreach ($unit_user as $item){
                $online->orWhere('unit_id', 'like', '%'.$item->id.'%');
            }
        }

        $offline = OfflineCourse::select(['id','code','name','image','start_date','end_date','register_deadline', \DB::raw('2 as type')])
            ->where(function ($sub) use ($search){
                $sub->orWhere('name', 'LIKE','%'.$search.'%')
                    ->orWhere('code','LIKE','%'.$search.'%');
            });

        if (!Permission::isAdmin()){
            $offline->whereNull('unit_id');
            foreach ($unit_user as $item){
                $offline->orWhere('unit_id', 'like', '%'.$item->id.'%');
            }
        }

        $result = $online->union($offline)->paginate(20);
        return view('data.search_result',['items'=>$result]);
    }

    /*khóa học theo lộ trình*/
    public function getCourseTrainingRoadmap()
    {
        $user_convert_titles = ConvertTitles::query()
            ->where('user_id','=',profile()->user_id)
            ->where('end_date','>',date('Y-m-d H:i:s'))
            ->first();

        $user_potential = Potential::query()
            ->where('user_id','=',profile()->user_id)
            ->where('end_date','>',date('Y-m-d H:i:s'))
            ->first();

        if ($user_convert_titles){
            $roadmap = 'el_convert_titles_roadmap';
            $title = Titles::find($user_convert_titles->title_id);
        }
        elseif ($user_potential){
            $roadmap = 'el_potential_roadmap';
            $user = profile();
            $title = Titles::where('code','=', $user->title_code)->first();
        }
        else{
            $roadmap = 'el_trainingroadmap';
            $user = profile();
            $title = Titles::where('code','=', $user->title_code)->first();
        }

        $subQuery = CourseView::query()
            ->from('el_course_view as a2')
            ->groupBy(['a2.subject_id','a2.course_type'])
            ->select([
                \DB::raw('MAX('.\DB::getTablePrefix().'a2.course_id) as course_id'),
                'a2.subject_id',
                'a2.course_type'
            ]);

        $query = \DB::query();
        $query->select([
            'c.*'
        ]);
        $query->from("$roadmap AS a");
        if($roadmap = 'el_trainingroadmap') {
            $query->joinSub($subQuery,'b', function ($join){
                $join->on('b.subject_id', '=', 'a.subject_id');
            });
        } else {
            $query->joinSub($subQuery,'b', function ($join){
                $join->on('b.course_type', '=', 'a.training_form');
                $join->on('b.subject_id', '=', 'a.subject_id');
            });
        }
        $query->join('el_course_view AS c', function ($join){
            $join->on('c.course_id', '=', 'b.course_id');
            $join->on('c.course_type', '=', 'b.course_type');
        });
        $query->where('c.status','=', 1);
        $query->where('c.isopen','=', 1);
        $query->where('a.title_id','=', @$title->id);

        return $query->get();
    }

    /*Lấy 5 khóa mới nhất*/
    public function getFiveCourseNew($limit = 5){
        $now = date('Y-m-d H:i:s');
        $profile = profile();

        $query = OnlineCourse::query()
            ->select([
                'id',
                'code',
                'name',
                'start_date',
                'end_date',
                'register_deadline',
                'image',
                DB::raw('1 AS type')
            ])
            ->where('status', '=', 1)
            ->where('isopen', '=', 1)
            ->where(function ($sub) use ($now){
                $sub->where('end_date', '>=', $now);
                $sub->orWhereNull('end_date');
            })
            ->whereIn('id', function ($sub) use ($profile){
                $sub->select(['course_id'])
                    ->from('el_online_object')
                    ->orWhere('unit_id', '=', @$profile->unit_id)
                    ->orWhere('title_id', '=', @$profile->title_id)
                    ->pluck('course_id')
                    ->toArray();
            });

        $offline = OfflineCourse::query()
            ->select([
                'id',
                'code',
                'name',
                'start_date',
                'end_date',
                'register_deadline',
                'image',
                DB::raw('2 AS type')
            ])
            ->where('status', '=', 1)
            ->where('isopen', '=', 1)
            ->where(function ($sub) use ($now){
                $sub->where('end_date', '>=', $now);
                $sub->orWhereNull('end_date');
            })
            ->whereIn('id', function ($sub) use ($profile){
                $sub->select(['course_id'])
                    ->from('el_offline_object')
                    ->orWhere('unit_id', '=', @$profile->unit_id)
                    ->orWhere('title_id', '=', @$profile->title_id)
                    ->pluck('course_id')
                    ->toArray();
            });

        $query = $query->union($offline);
        $query_sql = $query->toSql();
        $query = \DB::table(\DB::raw("($query_sql) AS q"))->mergeBindings($query->getQuery());
        $query->orderBy('start_date', 'DESC');
        $query->limit($limit);

        return $query->get();
    }

    public function chartCourseByUser($count_register_course_by_user){
        $user_id = profile()->user_id;

        $onlineComplete = 0;
        $onlineUncomplete = 0;
        $queryOnlineResults = OnlineResult::where(['user_id' => $user_id])->get(['result']);
        foreach($queryOnlineResults as $queryOnlineResult) {
            if($queryOnlineResult->result == 1) {
                $onlineComplete += 1;
            } else {
                $onlineUncomplete += 1;
            }

        }

        $offlineComplete = 0;
        $offlineUncomplete = 0;
        $queryOfflines = OfflineResult::whereUserId($user_id)->get(['result']);
        foreach($queryOfflines as $queryOffline) {
            if($queryOffline->result == 1) {
                $offlineComplete += 1;
            } else {
                $offlineUncomplete += 1;
            }
        }

        $uncomplete = $onlineUncomplete + $offlineUncomplete;
        $complete = $onlineComplete + $offlineComplete;
        $not_learned = $count_register_course_by_user - ($uncomplete + $complete);
        $data['course_by_user'] = [$not_learned, $uncomplete, $complete];
        return $data;
    }

    /*Chuyên đề của nhân viên*/
    public function getDataRoadmap()
    {
        $user = profile();
        $subQuery = TrainingProcess::query()
            ->where('user_id','=', $user->user_id)
            ->where('titles_code','=', $user->title_code)
            ->groupBy('subject_id')
            ->select([
                \DB::raw('MAX(id) as id'),
                'subject_id',
            ]);

        $query = TrainingRoadmap::query();
        $query->select(['c.id','c.subject_name','c.pass']);
        $query->from("el_trainingroadmap AS a");
        $query->joinSub($subQuery,'b', function ($join){
            $join->on('b.subject_id', '=', 'a.subject_id');
        });
        $query->leftJoin('el_training_process as c', 'c.id', '=', 'b.id');
        $query->where('a.title_id','=', $user->title_id);
        $count = $query->count();
        $rows = $query->get();

        foreach ($rows as $row) {
            if ($row->pass == 1){
                $row->status = trans('backend.finish');
            }else{
                $row->status = trans('backend.incomplete');
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function chartSubjectByUser(){
        $user = profile();
        $subQuery = TrainingProcess::query()
            ->where('user_id','=', $user->user_id)
            ->where('titles_code','=', $user->title_code)
            ->groupBy('subject_id')
            ->select([
                \DB::raw('MAX(id) as id'),
                'subject_id',
            ]);

        $query = TrainingRoadmap::query();
        $query->select(['c.pass']);
        $query->from("el_trainingroadmap AS a");
        $query->joinSub($subQuery,'b', function ($join){
            $join->on('b.subject_id', '=', 'a.subject_id');
        });
        $query->leftJoin('el_training_process as c', 'c.id', '=', 'b.id');
        $query->where('a.title_id','=', $user->title_id);
        $rows = $query->get();

        $uncomplete = 0;
        $complete = 0;
        foreach ($rows as $row) {
            if ($row->pass == 1){
                $complete += 1;
            }else{
                $uncomplete += 1;
            }
        }
        $data = [$uncomplete, $complete];

        return $data;
    }

    public function getLevelSubjectByUser(){
        $dbprefix = \DB::getTablePrefix();
        $user = profile();

        $subQuery = TrainingProcess::query()
            ->select(['subject_id'])
            ->where('titles_code','=', $user->title_code)
            ->groupBy('subject_id')
            ->pluck('subject_id')->toArray();

        $level_subject = LevelSubject::query()
            ->select([
                \DB::raw('MAX('.$dbprefix.'a.id) as id'),
                'a.name'
            ])
            ->from('el_level_subject as a')
            ->leftJoin('el_subject as b', 'b.level_subject_id', '=', 'a.id')
            ->whereIn('b.id', $subQuery)
            ->groupBy('a.name')
            ->get();

        return $level_subject;
    }

    public function closeOpendMenuBottom(Request $request) {
        session(['close_open_menu' => $request->status]);
        session()->save();
    }

    public function closeOpendMenu(Request $request) {
        session(['close_open_menu_frontend' => $request->status]);
        session()->save();
    }

    //LƯU SỐ LẦN MỞ ĐIỀU HƯỚNG TRẢI NGHIỆM
    public function saveExperienceNavigate(Request $request)
    {
        session(['close_experience_navigate' => 1]);
        session()->save();
        $id = $request->id;
        $model = CountUserExperienceNavigate::firstOrNew(['user_id' => profile()->user_id, 'experience_navigate_id' => $id]);
        $model->user_id = profile()->user_id;
        $model->experience_navigate_id = $id;
        $model->number_count = $model->number_count + 1;
        $model->date_number_count = $model->date_number_count + 1;
        $model->save();
    }

     /*Lấy khóa học online đã đăng ký theo năm chart*/
    public function getRegisterOnlineByYear(Request $request){
        $year = $request->year;

        $totalOnlineRegisterYear =  TrainingProcess::where('user_id',\auth()->id())
            ->where('course_type',1)
            ->where('status',1)
            ->where(\DB::raw('year(start_date)'), '=', $year)->count();

        $onl_complete_year = TrainingProcess::where('user_id',\auth()->id())
            ->where('course_type',1)
            ->where('pass', 1)
            ->where(\DB::raw('year(start_date)'), '=', $year)->count();

        $onl_incomplete_year = $totalOnlineRegisterYear - $onl_complete_year;

        $data = [$onl_incomplete_year,$onl_complete_year];

        json_result([
            'status' => 'ok',
            'data' => $data
        ]);
    }
    public function getRegisterOfflineByYear(Request $request){
        $year = $request->year;

        $totalOfflineRegisterYear = TrainingProcess::where('user_id',\auth()->id())
            ->where('course_type',2)
            ->where('status',1)
            ->where(\DB::raw('year(created_at)'), '=', $year)->count();

        $off_complete_year = TrainingProcess::where('user_id',\auth()->id())
            ->where('course_type',2)
            ->where('pass', 1)
            ->where(\DB::raw('year(start_date)'), '=', $year)
            ->count();

        $off_incomplete_year = $totalOfflineRegisterYear - $off_complete_year;

        $data = [$off_incomplete_year,$off_complete_year];

        json_result([
            'status' => 'ok',
            'data' => $data
        ]);
    }

    /*Lịch sử tương tác*/
    public function interactionHistory(){
        return view('frontend.interaction_history');
    }

    /*Lấy dữ liệu lịch sử tương tác của HV*/
    public function getInteractionHistory(){
        $data_chart = [];
        $data_chart[] = ['', trans("lamenu.summary")];

        $user_id = profile()->user_id;

        $interaction_history_name = InteractionHistoryName::get(['code']);
        $total = InteractionHistory::where('user_id', $user_id)->sum('number');

        foreach($interaction_history_name as $item){
            $interaction_history = InteractionHistory::where('user_id', $user_id)->where('code', $item->code)->first(['number']);
            $percent = $interaction_history ? (float)($interaction_history->number*100/$total) : 0;

            switch ($item->code) {
                case 'news':
                    $name = trans('lamenu.news');
                    break;
                case 'libraries':
                    $name = trans('lamenu.libraries');
                    break;
                case 'forum':
                    $name = trans('lamenu.forum');
                    break;
                case 'survey':
                    $name = trans('lamenu.survey');
                    break;
                case 'training_video':
                    $name = trans('lamenu.training_video');
                    break;
                case 'quiz':
                    $name = trans('lamenu.quiz');
                    break;
                case 'rating_level':
                    $name = trans('lamenu.kirkpatrick_model');
                    break;
                case 'help':
                    $name = trans('lamenu.support');
                    break;
                default:
                    $name = trans('latraining.other');
                    break;
            }

            $data_chart[] = [
                $name,
                $percent,
            ];
        }

        return \response()->json($data_chart);
    }

    /*Dữ liệu biểu đồ khoá học thuộc tháp đào tạo và nằm ngoài tháp đào đạo*/
    public function chartCourseInOutTrainingRoadmap(){
        $profile = profile();

        $course_in_training_roadmap = TrainingRoadmap::query()
            ->from('el_trainingroadmap')
            ->leftJoin('el_course_view as course_view', 'course_view.subject_id', '=', 'el_trainingroadmap.subject_id')
            ->where(function($sub2){
                $sub2->orWhere('el_trainingroadmap.training_form', "");
                $sub2->orWhereColumn('course_view.course_type', 'el_trainingroadmap.training_form');
            })
            ->where('el_trainingroadmap.title_id', $profile->title_id)
            ->where('course_view.status', 1)
            ->pluck('course_view.id')->toArray();

        $course_out_training_roadmap = CourseView::whereNotIn('id', $course_in_training_roadmap)
        ->where('status', 1)
        ->pluck('id')->toArray();

        return [count($course_in_training_roadmap), count($course_out_training_roadmap)];
    }

    // CHI TIẾT TỔNG GIỜ HỌC CỦA HV
    public function detailTotalTimeUser(Request $request) {
        return view('frontend.detail_total_time');
    }

    public function getDataDetailTotalTimeUser(Request $request) {
        $search = $request->search;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $user_id = profile()->user_id;
        $year = date('Y');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CourseRegisterView::query();
        $query->select([
            'course.name',
            'course.code',
            'course.start_date',
            'course.end_date',
            'course.course_type',
            'course.course_id as id',
        ]);
        $query->from("el_course_register_view AS register");
        $query->join('el_course_view as course',function ($join){
            $join->on('course.course_id', '=', 'register.course_id');
            $join->on('course.course_type', '=', 'register.course_type');
        });
        $query->where('register.user_id', '=', $user_id);
        $query->where('course.offline', 0);
        $query->whereYear('course.created_at', $year);
        if($search) {
            $query->where(function($sub) use ($search) {
                $sub->orWhere('course.name', 'LIKE','%'.$search.'%')
                    ->orWhere('course.code','LIKE','%'.$search.'%');
            });
        }
        if($start_date) {
            $query->where('course.start_date', '>=', get_date($start_date, 'Y-m-d'));
        }
        if($end_date) {
            $query->where('course.end_date', '<=', get_date($end_date, 'Y-m-d'));
        }
        $course = $query;

        $query = QuizResult::query();
        $query->select([
            'quiz.name',
            'quiz.code',
            'quiz.start_quiz as start_date',
            'quiz.end_quiz as end_date',
            DB::raw('3 as course_type'),
            'quiz.id as id',
        ]);
        $query->from('el_quiz_result as result');
        $query->join('el_quiz AS quiz', 'quiz.id', '=', 'result.quiz_id');
        $query->where(['result.user_id' => $user_id, 'quiz_type' => 3]);
        $query->where('result.timecompleted', '>', 0);
        $query->whereNull('result.text_quiz');
        $query->whereYear('quiz.created_at', $year);
        if($search) {
            $query->where(function($sub) use ($search) {
                $sub->orWhere('quiz.name', 'LIKE','%'.$search.'%')
                    ->orWhere('quiz.code','LIKE','%'.$search.'%');
            });
        }
        if($start_date) {
            $query->where('quiz.start_quiz', '>=', get_date($start_date, 'Y-m-d'));
        }
        if($end_date) {
            $query->where('quiz.end_quiz', '<=', get_date($end_date, 'Y-m-d'));
        }

        $query->union($course);
        $query_sql = $query->toSql();
        $query = \DB::table(\DB::raw("($query_sql) AS q"))->mergeBindings($query->getQuery());
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        $quizs = Quiz::where('quiz_type', 3)->pluck('id')->toArray();
        foreach ($rows as $row) {
            if($row->end_date) {
                $row->course_time = get_date($row->start_date) . ' => ' . get_date($row->end_date);
            } else {
                $row->course_time = get_date($row->start_date);
            }

            if($row->course_type == 1) {
                $timeLearn = OnlineCourseTimeUserLearn::where(['user_id' => $user_id, 'course_id' => $row->id])->whereYear('created_at', $year)->sum('time');
                $row->type = 'Online';
            } else if ($row->course_type == 2) {
                $checkTeams = [];
                $totalTimeLearnTeamsOffline = 0;
                $queryTeams = OfflineTeamsAttendanceReport::query();
                $queryTeams->select([
                    'teams.schedule_id',
                    'teams.total_second',
                    'offline_schedule.start_time',
                    'offline_schedule.end_time',
                    'offline_schedule.condition_complete_teams',
                ]);
                $queryTeams->from('offline_teams_attendance_report as teams');
                $queryTeams->join('el_offline_schedule as offline_schedule', 'offline_schedule.id', '=', 'teams.schedule_id');
                $queryTeams->whereYear('teams.created_at', $year);
                $queryTeams->where('teams.user_id', $user_id);
                $queryTeams->where('teams.course_id', $row->id);
                $timeLearnTeams = $queryTeams->get();
                foreach($timeLearnTeams as $timeLearn) {
                    $startLearnOfflineTeam = Carbon::parse($timeLearn->start_time);
                    $endLearnOfflineTeam = Carbon::parse($timeLearn->end_time);
                    $totalTimeTeam = $endLearnOfflineTeam->diffInSeconds($startLearnOfflineTeam);
                    $calculateTimeTeam = ($timeLearn->total_second / $totalTimeTeam) * 100;
                    if(round((int)$calculateTimeTeam, 0) > (int) $timeLearn->condition_complete_teams) {
                        $totalTimeLearnTeamsOffline += $timeLearn->total_second;
                    }
                    $checkTeams[] = $timeLearn->schedule_id;
                }

                // TỔNG THỜI HỌC, ĐIỂM DANH KHÓA OFFLINE
                $totalTimeLearnOffline = 0;
                $queryOfflineAttendace = OfflineAttendance::query();
                $queryOfflineAttendace->select([
                    'offline_attendance.schedule_id',
                    'offline_attendance.percent',
                    'offline_schedule.start_time',
                    'offline_schedule.end_time',
                ]);
                $queryOfflineAttendace->from('el_offline_attendance as offline_attendance');
                $queryOfflineAttendace->join('el_offline_schedule as offline_schedule', 'offline_schedule.id', '=', 'offline_attendance.schedule_id');
                $queryOfflineAttendace->whereNotIn('schedule_id', $checkTeams);
                $queryOfflineAttendace->where(['offline_attendance.user_id' => $user_id, 'status' => 1, 'offline_attendance.course_id' => $row->id]);
                $queryOfflineAttendace->whereYear('offline_attendance.created_at', $year);
                $attendancesOffline = $queryOfflineAttendace->get();
                foreach($attendancesOffline as $attendance) {
                    $startLearnOffline = Carbon::parse($attendance->start_time);
                    $endLearnOffline = Carbon::parse($attendance->end_time);
                    $totalTime = $endLearnOffline->diffInSeconds($startLearnOffline);
                    $totalTimeLearn = $attendance->percent * $totalTime / 100;
                    $totalTimeLearnOffline = $totalTimeLearnOffline + $totalTimeLearn;
                }
                $timeLearn =  $totalTimeLearnOffline  + $totalTimeLearnTeamsOffline;

                $row->type = 'Tập trung';
            } else {
                $row->type = 'Kỳ thi';
                $quizResults = QuizAttempts::where('user_id', profile()->user_id)->where('timefinish', '>', 0)->whereYear('created_at', $year)->whereIn('quiz_id', $quizs)->get(['timefinish', 'timestart']);
                $totalTimeQuiz = 0;
                foreach($quizResults as $quizResult) {
                    $timeFinishQuiz = date('Y-m-d H:i:s', $quizResult->timefinish);
                    $timeStartQuiz = date('Y-m-d H:i:s', $quizResult->timestart);
                    $startQuiz = Carbon::parse($timeStartQuiz);
                    $endQuiz = Carbon::parse($timeFinishQuiz);
                    $calculateTimeQuiz = $endQuiz->diffInSeconds($startQuiz);
                    $totalTimeQuiz +=  $calculateTimeQuiz;
                }
                $timeLearn = $totalTimeQuiz;
            }

            $hours = floor($timeLearn / 3600);
            $minutes = floor(($timeLearn / 60) % 60);
            $row->total_time = $hours . ":" . $minutes;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
