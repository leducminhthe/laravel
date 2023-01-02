<?php

namespace App\Http\Controllers\Mobile;

use App\Models\Categories\TrainingTeacher;
use App\Models\CourseBookmark;
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
use Modules\Promotion\Entities\PromotionLevel;
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
use App\Models\MenuSetting;
use Modules\Rating\Entities\CourseRatingLevel;
use Modules\Online\Entities\OnlineRatingLevel;
use Modules\Offline\Entities\OfflineRatingLevel;
use App\Models\Categories\UnitManager;
use Modules\Rating\Entities\RatingLevelCourse;

class HomeController extends Controller
{
    public function changeLanguage($lang)
    {
        \App::setLocale($lang);
        session()->put('locale', $lang);
        return redirect()->back();
    }

    public function index()
    {
        $user_id = getUserId();
        $user_type = getUserType();
        $date = date('Y-m-d H:i:s');
        $profile = profile();
        $laster_news = News::getLasterNews();

        $count_my_course = CourseView::countMyCourse();

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

        $lay = 'home';
        $laster_thread = ForumThread::getLasterThread();
        $unit_arr = Unit::getTreeParentUnit($profile->unit_code);

        Slider::addGlobalScope(new CompanyScope());
        $sliders = Slider::where('status', '=', 1)
            ->where('type', '=', 2)
            ->where('location', '!=', 1)
            ->where(function ($sub) use ($unit_arr){
                $sub->where('location', '=', 0);
                foreach ($unit_arr as $item){
                    $sub->orWhereIn('location', [$item->id]);
                }
            })->get();

        Survey::addGlobalScope(new CompanyScope());
        $model = Survey::query();
        $model->where('status', '=', 1);
        $model->where('end_date', '>', date('Y-m-d H:i:s'));
        $model->where(function ($subquery) use ($profile) {
            $subquery->orWhereIn('id', function ($subquery2) use ($profile) {
                $subquery2->select(['survey_id'])
                    ->from('el_survey_object')
                    ->where('user_id', '=', $profile->user_id)
                    ->orWhere('title_id', '=', @$profile->title_id)
                    ->orWhere('unit_id', '=', @$profile->unit_id);
            });
        });
        $count_survey = $model->count();

        $new_onlines = OnlineCourse::getNewCourse();
        $feedbacks = Feedback::get();

        $promotion_user_point = PromotionUserPoint::whereUserId($user_id)->first();
        if(!$promotion_user_point){
            $promotion_user_point = new PromotionUserPoint();
            $promotion_user_point->user_id = $user_id;
            $promotion_user_point->point = 0;
            $promotion_user_point->level_id = 0;
            $promotion_user_point->save();
        }

        $promotion = PromotionUserPoint::whereUserId($user_id)->first();
        $promotion_level = '';
        if(!empty($promotion)) {
            $promotion_level = PromotionLevel::where('status',1)->where('level',$promotion->level_id)->first();
        }

        $getMenuSetting = MenuSetting::where('title_id', $profile->title_id)->pluck('menu_value')->toArray();
        $menuSetting = (!empty($getMenuSetting) && $user_id > 2) ? $getMenuSetting : [];

        // $countRating = $this->countRating();
        return view('themes.mobile.frontend.home', [
            'laster_news' => $laster_news,
            'count_my_course' => $count_my_course,
            'count_quiz' => $total_quiz,
            'count_survey' => $count_survey,
            'new_onlines' => $new_onlines,
            'laster_thread' => $laster_thread,
            'feedbacks' => $feedbacks,
            'lay' => $lay,
            'user_max_point' => $this->getUserMaxPoint(),
            'teacher_max_point' => $this->getTeacherMaxPoint(),
            'sliders' => $sliders,
            'profile' => $profile,
            'promotion' => $promotion,
            'promotion_level' => $promotion_level,
            'total_user' => $this->getTotalUser(),
            'user_rank' => $this->getRankUser(),
            'menuSetting' => $menuSetting,
            'user_type' => $user_type,
            // 'countRating' => $countRating
        ]);
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
        $bookmark = CourseBookmark::where('course_id',$course_id)->where('type',$course_type)->where('user_id',\auth()->id());
        if (!$bookmark->exists()){
            $model = new CourseBookmark();
            $model->course_id = $course_id;
            $model->type = $course_type;
            $model->user_id = profile()->user_id;
            $model->save();
        }
        if ($course_type == 1 && $my_course == 0) {
            // return redirect()->route('module.online');
            return redirect()->route('frontend.all_course',['type' => 0]);
        } else if ($my_course == 1){
            return redirect()->route('module.frontend.user.my_course',['type' => 0]);
        }
        // return redirect()->route('module.offline');
        return redirect()->route('frontend.all_course',['type' => 0]);
    }

    public function removeCourseBookmark($course_id, $course_type, $my_course){
        CourseBookmark::query()
            ->where('course_id', '=', $course_id)
            ->where('type', '=', $course_type)
            ->where('user_id', '=', profile()->user_id)
            ->delete();

        if ($course_type == 1 && $my_course == 0) {
            return redirect()->route('frontend.all_course',['type' => 0]);
        } else if ($my_course == 1){
            return redirect()->route('module.frontend.user.my_course',['type' => 0]);
        }
        return redirect()->route('frontend.all_course',['type' => 0]);
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

    public function getRegisterCourse()
    {
        $year = date('Y');
        for ($m = 1; $m <= 12; $m++) {
            $onlineRegister = TrainingProcess::where('user_id',\auth()->id())
                ->where('status',1)
                ->where('course_type',1)
                ->where(\DB::raw('month(created_at)'), '=', $m)
                ->where(\DB::raw('year(created_at)'), '=', $year)->count();

            $offlineRegister = TrainingProcess::where('user_id',\auth()->id())
                ->where('status',1)
                ->where('course_type',2)
                ->where(\DB::raw('month(created_at)'), '=', $m)
                ->where(\DB::raw('year(created_at)'), '=', $year)->count();

            $onlineComplete = TrainingProcess::where('user_id',\auth()->id())
                ->whereRaw('(pass = 0 or pass is null) ')
                ->where('status',1)
                ->where('course_type',1)
                ->where(\DB::raw('month(start_date)'), '=', $m)
                ->where(\DB::raw('year(start_date)'), '=', $year)->count();

            $offlineComplete = TrainingProcess::where('user_id',\auth()->id())
                ->where('pass', 1)
                ->where('course_type',2)
                ->where(\DB::raw('year(start_date)'), '=', $year)
                ->where(\DB::raw('month(start_date)'), '=', $m)
                ->count();

            $totalQuiz = QuizRegister::where('user_id',\auth()->id())
                ->where(\DB::raw('month(created_at)'), '=', $m)
                ->where(\DB::raw('year(created_at)'), '=', $year)->count();

            $totalQuizComplete = QuizResult::where('user_id',\auth()->id())
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
        $totalOnlineRegisterYear =  TrainingProcess::where('user_id',\auth()->id())
            ->where('course_type',1)
            ->where('status',1)
            ->where(\DB::raw('year(start_date)'), '=', $year)->count();

        $onl_complete_year = TrainingProcess::where('user_id',\auth()->id())
            ->where('course_type',1)
            ->where('pass', 1)
            ->where(\DB::raw('year(start_date)'), '=', $year)->count();

        $onl_incomplete_year = $totalOnlineRegisterYear - $onl_complete_year;

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

        $subQuery = CourseRegisterView::query()
            ->from('el_course_register_view as a1')
            ->join('el_course_view as a2', function ($join){
                $join->on('a1.course_id','=','a2.course_id');
                $join->on('a1.course_type','=','a2.course_type');
            })
            ->where('a1.user_id','=', profile()->user_id)
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
        $query->join('el_course_register_view as  d',function ($join){
            $join->on('d.course_id', '=', 'c.course_id');
            $join->on('d.course_type', '=', 'c.course_type')->where('d.user_id', '=', profile()->user_id);
        });
        $query->where('c.status','=', 1);
        $query->where('c.isopen','=', 1);
        $query->where('a.title_id','=', @$title->id);

        return $query->limit(3)->get();
    }

    /*Lấy 5 khóa mới nhất*/
    public function getFiveCourseNew($limit = 5){
        $now = date('Y-m-d H:i:s');
        $profile = profile();
        $title = Titles::where('code', '=', $profile->title_code)->first();
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

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
            ->whereIn('id', function ($sub) use ($title, $unit){
                $sub->select(['course_id'])
                    ->from('el_online_object')
                    ->orWhere('unit_id', '=', @$unit->id)
                    ->orWhere('title_id', '=', @$title->id)
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
            ->whereIn('id', function ($sub) use ($title, $unit){
                $sub->select(['course_id'])
                    ->from('el_offline_object')
                    ->orWhere('unit_id', '=', @$unit->id)
                    ->orWhere('title_id', '=', @$title->id)
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

    public function chartCourseByUser(){
        $online_complete = OnlineResult::whereUserId(profile()->user_id)->where('result','=', 1)->count();
        $online_uncomplete = OnlineResult::whereUserId(profile()->user_id)->where('result','=', 0)->count();
        $online_not_learned = OnlineRegister::whereStatus(1)
            ->where('user_id', '=', profile()->user_id)
            ->whereNotIn('id', function ($sub){
                $sub->select(['register_id'])
                    ->from('el_online_result')
                    ->pluck('register_id')
                    ->toArray();
            })
            ->count();

        $offline_complete = OfflineResult::whereUserId(profile()->user_id)->where('result','=', 1)->count();
        $offline_uncomplete = OfflineResult::whereUserId(profile()->user_id)->where('result','=', 0)->count();
        $offline_not_learned = OfflineRegister::whereStatus(1)
            ->where('user_id', '=', profile()->user_id)
            ->whereNotIn('id', function ($sub){
                $sub->select(['register_id'])
                    ->from('el_offline_result')
                    ->pluck('register_id')
                    ->toArray();
            })
            ->count();

        $not_learned = $online_not_learned + $offline_not_learned;
        $uncomplete = $online_uncomplete + $offline_uncomplete;
        $complete = $online_complete + $offline_complete;

        $data['course_by_user'] = [$not_learned, $uncomplete, $complete];
        return $data;
    }

    /*Chuyên đề của nhân viên*/
    public function getDataRoadmap()
    {
        $user_id = profile()->user_id;
        $user = profile();
        $subQuery = TrainingProcess::query()
            ->where('user_id','=', $user_id)
            ->where('titles_code','=', $user->title_code)
            ->groupBy('subject_id')
            ->select([
                \DB::raw('MAX(id) as id'),
                'subject_id',
            ]);

        $query = TrainingRoadmap::query();
        $query->select([
            'c.id',
            'c.subject_name',
            'c.pass',
        ]);
        $query->from("el_trainingroadmap AS a");
        $query->joinSub($subQuery,'b', function ($join){
            $join->on('b.subject_id', '=', 'a.subject_id');
        });
        $query->leftJoin('el_training_process as c', function ($join){
            $join->on('c.id', '=', 'b.id');
        });
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
        $user_id = profile()->user_id;
        $user = profile();
        $subQuery = TrainingProcess::query()
            ->where('user_id','=', $user_id)
            ->where('titles_code','=', $user->title_code)
            ->groupBy('subject_id')
            ->select([
                \DB::raw('MAX(id) as id'),
                'subject_id',
            ]);

        $query = TrainingRoadmap::query();
        $query->select([
            'c.pass',
        ]);
        $query->from("el_trainingroadmap AS a");
        $query->joinSub($subQuery,'b', function ($join){
            $join->on('b.subject_id', '=', 'a.subject_id');
        });
        $query->leftJoin('el_training_process as c', function ($join){
            $join->on('c.id', '=', 'b.id');
        });
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
        $user_id = profile()->user_id;
        $user = ProfileView::where(['user_id'=>$user_id])->first();
        $subQuery = TrainingProcess::query()
            ->select(['subject_id'])
            //->where('user_id','=', $user_id)
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
            //->leftJoin('el_trainingroadmap as c', 'c.subject_id', '=', 'b.id')
            //->where('c.title_id','=', $user->title_id)
            ->whereIn('b.id', $subQuery)
            ->groupBy('a.name')
            ->get();

        return $level_subject;
    }

    public function closeOpendMenuBottom(Request $request) {
        session(['close_open_menu' => $request->status]);
        session()->save();
    }

    public function closeOpendMenu(Request $request){
        session(['close_open_menu_frontend' => $request->status]);
        session()->save();
    }

    public function getTotalUser(){
        $training_teacher = TrainingTeacher::whereNotNull('user_id')->pluck('user_id')->toArray();
        $user = Profile::query()
            ->select([
                'profile.user_id',
            ])
            ->from('el_promotion_user_point as user_point')
            ->leftJoin('el_profile as profile', 'user_point.user_id', '=', 'profile.user_id')
            ->where('profile.status', '=', 1)
            //->whereNotIn('profile.user_id', $training_teacher)
            ->orderBy('user_point.point', 'DESC')
            ->get();

        return $user;
    }

    public function getRankUser(){
        $user = $this->getTotalUser();
        $user_rank = '';
        foreach ($user as $key => $item){
            if ($item->user_id == profile()->user_id){
                $user_rank = ($key + 1);
            }
        }

        return $user_rank;
    }

    public function settingNightMode(Request $request) {
        if ($request->type == 0) {
            session(['nightModeMobile' => 0]);
            session()->save();
            $setting = 0;
        } else {
            session(['nightModeMobile' => 1]);
            session()->save();
            $setting = 1;
        }

        json_result(['setting' => $setting]);
    }

    // ĐẾM SỐ LƯỢNG ĐÁNH GIÁ
    public function countRating(){
        $user_id = getUserId();
        $user_manager = UnitManager::query()
            ->from('el_unit_manager AS a')
            ->join('el_profile AS b', 'b.code', '=', 'a.user_code')
            ->where('b.user_id', '=', $user_id)
            ->pluck('a.unit_code')->toArray();

        $unit_child = [];
        $unit_child_id = [];
        foreach ($user_manager as $manager) {
            $unit_child_id = UnitManager::getArrayChild($manager);
        }
        $unit_child = Unit::whereIn('id', $unit_child_id)->pluck('code')->toArray();
        $user_manager = array_merge($user_manager, $unit_child);

        $prefix = DB::getTablePrefix();

        $query = OfflineRatingLevel::query();
        $query->select([
            'a.id',
            'a.course_id',
            DB::raw('2 as course_type'),
        ]);
        $query->from('el_offline_rating_level as a');
        $query->leftJoin('el_offline_course AS b', 'b.id', '=', 'a.course_id');
        $query->leftJoin('el_offline_register_view AS c', 'c.course_id', '=', 'b.id');
        $query->leftJoin('el_offline_rating_level_object AS d', 'd.offline_rating_level_id', '=', 'a.id');
        $query->where('b.status', '=', 1);
        $query->where('c.status', '=', 1);
        $query->where(function ($sub) use ($user_id, $user_manager) {
            $sub->orWhere(function ($sub2) use ($user_id) {
                $sub2->where('d.object_type', '=', 1);
                $sub2->where('c.user_id', '=', $user_id);
            });
            if (Permission::isUnitManager()) {
                $sub->orWhere(function ($sub2) use ($user_manager) {
                    $sub2->where('d.object_type', '=', 2);
                    $sub2->whereIn('c.unit_code', $user_manager);
                });
            }
            $sub->orWhere(function ($sub2) use ($user_id, $user_manager) {
                $sub2->where('d.object_type', '=', 3);
                if (Permission::isUnitManager()){
                    $sub2->whereIn('c.unit_code', $user_manager);
                }
                $sub2->whereExists(function ($sub3) use ($user_id) {
                    $sub3->select(['id'])
                        ->from('el_offline_rating_level_object_colleague as colleague')
                        ->where('colleague.user_id', '=', $user_id)
                        ->whereColumn('colleague.rating_user_id', '=', 'c.user_id');
                });
            });
            $sub->orWhere(function ($sub2) use ($user_id) {
                $sub2->where('d.object_type', '=', 4);
                $sub2->where('d.user_id', 'like', '%' . $user_id . '%');
            });
        });

        $offline_rating_level = $query;

        $query = OnlineRatingLevel::query();
        $query->select([
            'a.id',
            'a.course_id',
            DB::raw('1 as course_type'),
        ]);
        $query->from('el_online_rating_level as a');
        $query->leftJoin('el_online_course AS b', 'b.id', '=', 'a.course_id');
        $query->leftJoin('el_online_register_view AS c', 'c.course_id', '=', 'b.id');
        $query->leftJoin('el_online_rating_level_object AS d', 'd.online_rating_level_id', '=', 'a.id');
        $query->where('b.status', '=', 1);
        $query->where('b.offline', '=', 0);
        $query->where('c.status', '=', 1);
        $query->where('c.user_type', '=', 1);
        $query->where(function ($sub) use ($user_id, $user_manager){
            $sub->orWhere(function ($sub2) use ($user_id){
                $sub2->where('d.object_type', '=', 1);
                $sub2->where('c.user_id', '=', $user_id);
            });
            if (Permission::isUnitManager()){
                $sub->orWhere(function ($sub2) use ($user_manager){
                    $sub2->where('d.object_type', '=', 2);
                    $sub2->whereIn('c.unit_code', $user_manager);
                });
            }
            $sub->orWhere(function ($sub2) use ($user_id, $user_manager){
                $sub2->where('d.object_type', '=', 3);
                if (Permission::isUnitManager()){
                    $sub2->whereIn('c.unit_code', $user_manager);
                }
                $sub2->whereExists(function ($sub3) use ($user_id){
                    $sub3->select(['id'])
                        ->from('el_online_rating_level_object_colleague as colleague')
                        ->where('colleague.user_id', '=', $user_id)
                        ->whereColumn('colleague.rating_user_id', '=', 'c.user_id');
                });
            });
            $sub->orWhere(function ($sub2) use ($user_id){
                $sub2->where('d.object_type', '=', 4);
                $sub2->where('d.user_id', 'like', '%'.$user_id.'%');
            });
        });

        $online_rating_level = $query;

        $query = CourseRatingLevel::query();
        $query->select([
            'a.id',
            'a.rating_levels_id as course_id',
            DB::raw('3 as course_type'),
        ]);
        $query->from('el_course_rating_level as a');
        $query->leftJoin('el_rating_levels AS b', 'b.id', '=', 'a.rating_levels_id');
        $query->leftJoin('el_rating_levels_register AS c', 'c.rating_levels_id', '=', 'b.id');
        $query->leftJoin('el_course_rating_level_object AS d', 'd.course_rating_level_id', '=', 'a.id');
        $query->where(function ($sub) use ($user_id, $user_manager){
            $sub->orWhere(function ($sub2) use ($user_id){
                $sub2->where('d.object_type', '=', 1);
                $sub2->where('c.user_id', '=', $user_id);
            });
            if (Permission::isUnitManager()){
                $sub->orWhere(function ($sub2) use ($user_manager){
                    $sub2->where('d.object_type', '=', 2);
                    $sub2->whereIn('c.unit_code', $user_manager);
                });
            }
            $sub->orWhere(function ($sub2) use ($user_id, $user_manager){
                $sub2->where('d.object_type', '=', 3);
                if (Permission::isUnitManager()){
                    $sub2->whereIn('c.unit_code', $user_manager);
                }
                $sub2->whereExists(function ($sub3) use ($user_id){
                    $sub3->select(['id'])
                        ->from('el_course_rating_level_object_colleague as colleague')
                        ->where('colleague.user_id', '=', $user_id)
                        ->whereColumn('colleague.rating_user_id', '=', 'c.user_id');
                });
            });
            $sub->orWhere(function ($sub2) use ($user_id){
                $sub2->where('d.object_type', '=', 4);
                $sub2->where('d.user_id', 'like', '%'.$user_id.'%');
            });
        });

        $query->union($offline_rating_level);
        $query->union($online_rating_level);

        $querySql = $query->toSql();
        $query = DB::table(DB::raw("($querySql) as a"))->mergeBindings($query->getQuery());
        $count = $query->count();

        $countUserRating = RatingLevelCourse::where('user_id', $user_id)->where('send', 1)->count();
        $calculateRating = $count - $countUserRating;
        return $calculateRating;
    }
}
