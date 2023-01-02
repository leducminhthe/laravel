<?php

namespace App\Http\Composers;

use Modules\DailyTraining\Entities\DailyTrainingVideo;
use Illuminate\Contracts\View\View;
use Modules\Suggest\Entities\Suggest;
use App\Scopes\CompanyScope;
use Modules\TopicSituations\Entities\Topic;
use Modules\Survey\Entities\Survey;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\Quiz;
use App\Models\CourseView;
use Modules\Libraries\Entities\Libraries;
use Modules\Promotion\Entities\Promotion;
use Modules\FAQ\Entities\FAQs;
use App\Models\Guide;
use Modules\Forum\Entities\ForumThread;
use App\Models\Categories\UnitManager;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Models\Categories\Unit;
use App\Models\CourseRegisterView;
use Modules\Offline\Entities\OfflineRatingLevel;
use Modules\Online\Entities\OnlineRatingLevel;
use Modules\Rating\Entities\CourseRatingLevel;
use App\Models\ProfileView;
use Illuminate\Support\Facades\Auth;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourseActivityHistory;
use Carbon\Carbon;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineSchedule;
use App\Models\TotalTimeUserLearnInYear;
use App\Models\MenuSetting;
use App\Models\Categories\TrainingProgram;

class LeftMenuFrontend{
    protected $count_daily_video;
    protected $count_suggest;
    protected $count_topic_situation;
    protected $count_survey;
    protected $count_quiz;
    protected $count_course_online;
    protected $count_course_offline;
    protected $count_book;
    protected $count_ebook;
    protected $count_document;
    protected $count_video;
    protected $count_audiobook;
    protected $count_faq;
    protected $count_guide_pdf;
    protected $count_guide_video;
    protected $count_guide_post;
    protected $count_user_medal;
    protected $count_forum;
    protected $count_rating_level;
    protected $count_course_register;
    protected $totalTimeLearnInYear;
    protected $menuSetting;
    protected $trainingPrograms;

    public function __construct() {
        $this->countDailyVideo();
        $this->countSuggest();
        $this->countTopicSituation();
        $this->countSurvey();
        $this->countQuiz();
        $this->countCourseOnline();
        $this->countCourseOffline();
        $this->countLibraries();
        $this->countPromotion();
        $this->countFAQ();
        $this->countGuide();
        $this->countForum();
        $this->countRatingLevel();
        $this->countCourseRegister();
        $this->countTotalTimeLearn();
        $this->menuSettingHandle();
        $this->trainingProgramHandle();
        // $this->countUserMedal();
    }

    public function compose(View $view) {
        $view->with('count_daily_video',$this->count_daily_video)
        ->with('count_topic_situation', $this->count_topic_situation)
        ->with('count_survey', $this->count_survey)
        ->with('count_quiz', $this->count_quiz)
        ->with('count_course_online', $this->count_course_online)
        ->with('count_course_offline', $this->count_course_offline)
        ->with('count_book', $this->count_book)
        ->with('count_ebook', $this->count_ebook)
        ->with('count_document', $this->count_document)
        ->with('count_video', $this->count_video)
        ->with('count_audiobook', $this->count_audiobook)
        ->with('count_promotion', $this->count_promotion)
        ->with('count_faq', $this->count_faq)
        ->with('count_guide_pdf', $this->count_guide_pdf)
        ->with('count_guide_video', $this->count_guide_video)
        ->with('count_guide_post', $this->count_guide_post)
        ->with('count_forum', $this->count_forum)
        ->with('count_rating_level', $this->count_rating_level)
        ->with('count_suggest', $this->count_suggest)
        ->with('count_course_register', $this->count_course_register)
        ->with('totalTimeLearnInYear', $this->totalTimeLearnInYear)
        ->with('menuSetting', $this->menuSetting)
        ->with('trainingPrograms', $this->trainingPrograms);
    }

    public function countForum() {
        $query = ForumThread::query()->where('el_forum_thread.status', '=', 1)->count();
        $this->count_forum = $query;
    }

    public function countUserMedal() {

    }

    public function countGuide() {
        $query_pdf = Guide::query()->where('type',1)->count();
        $query_video = Guide::query()->where('type',2)->count();
        $query_post = Guide::query()->where('type',3)->count();
        $this->count_guide_pdf = $query_pdf;
        $this->count_guide_video = $query_video;
        $this->count_guide_post = $query_post;
    }

    public function countFAQ() {
        $query = FAQs::query()->count();
        $this->count_faq = $query;
    }

    public function countPromotion() {
        Promotion::addGlobalScope(new CompanyScope());
        $query = Promotion::query()->where('status', '=', 1)->count();
        $this->count_promotion = $query;
    }

    public function countLibraries() {
        Libraries::addGlobalScope(new CompanyScope());
        $query = Libraries::query()->where('el_libraries.type', '=', 1)->where('el_libraries.status', '=', 1)->count();
        $query_ebook = Libraries::query()->where('el_libraries.type', '=', 2)->where('el_libraries.status', '=', 1)->count();
        $query_document = Libraries::query()->where('el_libraries.type', '=', 3)->where('el_libraries.status', '=', 1)->count();
        $query_video = Libraries::query()->where('el_libraries.type', '=', 4)->where('el_libraries.status', '=', 1)->count();
        $query_audiobook = Libraries::query()->where('el_libraries.type', '=', 5)->where('el_libraries.status', '=', 1)->count();
        $this->count_book = $query;
        $this->count_ebook = $query_ebook;
        $this->count_document = $query_document;
        $this->count_video = $query_video;
        $this->count_audiobook = $query_audiobook;
    }

    public function countCourseOnline() {
        CourseView::addGlobalScope(new CompanyScope());
        $query = CourseView::query();
        $query->where('el_course_view.status', '=', 1);
        $query->where('el_course_view.isopen', '=', 1);
        $this->count_course_online = $query->where('el_course_view.course_type', 1)->where('offline',0)->count();
    }

    public function countCourseOffline() {
        CourseView::addGlobalScope(new CompanyScope());
        $query = CourseView::query();
        $query->where('el_course_view.status', '=', 1);
        $query->where('el_course_view.isopen', '=', 1);
        $query->whereNotExists(function ($builder)  {
            $builder->select(['course_id'])
                ->from('el_course_register_view AS register')
                ->where('register.status', 1)
                ->whereColumn('register.course_type', '=', 'el_course_view.course_type')
                ->whereColumn('register.course_id', '=', 'el_course_view.course_id');
        });
        $this->count_course_offline = $query->where('el_course_view.course_type', 2)->count();
    }

    public function countCourseRegister(){
        $query = CourseView::query();
        $query->leftjoin('el_course_register_view as b',function($join){
            $join->on('el_course_view.course_id','=','b.course_id');
            $join->on('el_course_view.course_type','=','b.course_type');
        });
        $query->where('el_course_view.status', '=', 1);
        $query->where('el_course_view.isopen', '=', 1);
        $query->where('b.user_id', profile()->user_id);
        $query->where('b.status', 1);
        $query->where('el_course_view.offline', '=', 0);
        $this->count_course_register = $query->count();
    }

    public function countQuiz() {
        $user_id = getUserId();
        $user_type = getUserType();
        $date = date('Y-m-d H:i:s');

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

        $this->count_quiz = $count_quiz + $count_quiz_not_register;
    }

    public function countDailyVideo() {
        DailyTrainingVideo::addGlobalScope(new CompanyScope());
        $query = DailyTrainingVideo::query()
            ->where('status', 1)
            ->where(function($sub_query) {
                $sub_query->orWhere('created_by', '=', profile()->user_id);
                $sub_query->orWhere('approve', 1);
            })
            ->count();
        $this->count_daily_video = $query;
    }

    public function countSuggest() {
        Suggest::addGlobalScope(new CompanyScope());
        $query = Suggest::query()->count();
        $this->count_suggest = $query;
    }

    public function countTopicSituation() {
        Topic::addGlobalScope(new CompanyScope());
        $query = Topic::query()->where('isopen',1)->count();
        $this->count_topic_situation = $query;
    }

    public function countSurvey() {
        $profile = profile();
        Survey::addGlobalScope(new CompanyScope());
        $query = Survey::query();
        $query->where('status', '=', 1);
        $query->where('type', '=', 1);
        if (!Permission::isAdmin()) {
            $query->where(function ($subquery) use ($profile) {
                $subquery->orWhereIn('id', function ($subquery2) use ($profile) {
                    $subquery2->select(['survey_id'])
                        ->from('el_survey_object')
                        ->where('user_id', '=', $profile->user_id)
                        ->orWhere('title_id', '=', @$profile->title_id)
                        ->orWhere('unit_id', '=', @$profile->unit_id);
                });
            });
        }
        $this->count_survey = $query->count();
    }

    public function countRatingLevel() {
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
            'a.rating_name',
            'a.course_id',
            'a.object_rating',
            DB::raw('2 as course_type'),
            'b.code as course_code',
            'b.name as course_name',
            'b.start_date',
            'b.end_date',
            'c.user_id',
            'd.id as rating_level_object_id',
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
            'a.rating_name',
            'a.course_id',
            'a.object_rating',
            DB::raw('1 as course_type'),
            'b.code as course_code',
            'b.name as course_name',
            'b.start_date',
            'b.end_date',
            'c.user_id',
            'd.id as rating_level_object_id',
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
            'a.rating_name',
            'a.rating_levels_id as course_id',
            'a.object_rating',
            DB::raw('3 as course_type'),
            DB::raw('null as course_code'),
            DB::raw('null as course_name'),
            DB::raw('null as start_date'),
            DB::raw('null as end_date'),
            'c.user_id',
            'd.id as rating_level_object_id',
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
        $this->count_rating_level = $count;
    }

    public function countTotalTimeLearn()
    {
        $totalTimeLearnInYear = TotalTimeUserLearnInYear::where('user_id', profile()->user_id)->first(['total_time']);
        $this->totalTimeLearnInYear = $totalTimeLearnInYear ? $totalTimeLearnInYear->total_time : 0;
    }

    public function menuSettingHandle()
    {
        $profile = profile();
        $getMenuSetting = MenuSetting::where('title_id', $profile->title_id)->pluck('menu_value')->toArray();
        $this->menuSetting = (!empty($getMenuSetting) && profile()->user_id > 2) ? $getMenuSetting : [];
    }

    public function trainingProgramHandle()
    {
        TrainingProgram::addGlobalScope(new CompanyScope());
        $trainingProgramCourse = CourseView::where(['offline' => 0, 'course_type' => 1, 'isopen' => 1, 'status' => 1])
        ->whereNotExists(function ($builder)  {
            $builder->select(['course_id'])
                ->from('el_course_register_view AS register')
                ->where(['register.status' => 1, 'user_id' => profile()->user_id])
                ->whereColumn('register.course_type', '=', 'el_course_view.course_type')
                ->whereColumn('register.course_id', '=', 'el_course_view.course_id');
        })
        ->groupBy('training_program_id')->pluck('training_program_id')->toArray();

        $getTrainingProgram = TrainingProgram::whereIn('id', $trainingProgramCourse)->where('status', 1)->orderBy('order', 'ASC')->get();
        foreach ($getTrainingProgram as $trainingProgram) {
            $query = CourseView::query();
            $query->where(['offline' => 0, 'course_type' => 1, 'isopen' => 1, 'status' => 1, 'training_program_id' => $trainingProgram->id]);
            $query->whereNotExists(function ($builder) {
                $builder->select(['course_id'])
                    ->from('el_course_register_view AS register')
                    ->where(['register.status' => 1, 'user_id' => profile()->user_id])
                    ->whereColumn('register.course_type', '=', 'el_course_view.course_type')
                    ->whereColumn('register.course_id', '=', 'el_course_view.course_id');
            });
            $trainingProgram->countCourse = $query->count();
        }
        $this->trainingPrograms = @$getTrainingProgram;
    }
}
