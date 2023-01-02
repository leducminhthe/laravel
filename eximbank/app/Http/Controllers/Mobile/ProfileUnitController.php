<?php

namespace App\Http\Controllers\Mobile;

use App\Models\Categories\Unit;
use App\Models\Categories\Titles;
use App\Http\Controllers\Controller;
use App\Models\Categories\Area;
use Illuminate\Http\Request;
use App\Models\Categories\UnitManager;
use App\Models\ProfileView;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCourseComplete;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Online\Entities\OnlineCourseActivityCompletion;
use Modules\Online\Entities\OnlineCourseComplete;
use Modules\Online\Entities\OnlineCourseCondition;
use Modules\Online\Entities\OnlineRegister;
use Modules\Quiz\Entities\QuizRegister;
use Modules\User\Entities\ProfileProgressRoadmap;
use Modules\User\Entities\User;
use Modules\User\Entities\UserCompletedSubject;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\TrainingProcess;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;

class ProfileUnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        if(is_null(session('user_unit'))){
            $unit_manager = UnitManager::where('user_id', profile()->user_id)->first();
            $unit_user = @$unit_manager->unit_id;
        }else{
            $unit_user = session('user_unit');
        }
        $unit = Unit::find($unit_user,['level']);

        $lay = 'profile_unit';
        $getUserByUnit = $this->getUserByUnit($request);

        if($request->unit){
            $request_unit = Unit::find($request->unit,['id', 'code', 'name']);;
        }

        if($request->title_id){
            $request_title_id = Titles::find($request->title_id,['id', 'code', 'name']);;
        }

        $userUnits = auth()->check() ? User::getRoleAndManagerUnitUser() : [];
        return view('themes.mobile.frontend.unit_manager.index', [
            'lay' => $lay,
            'list_user' => $getUserByUnit,
            'unit' => $unit,
            'request_unit' => $request_unit,
            'request_title_id' => $request_title_id,
            'userUnits' => $userUnits
        ]);
    }

    public function getUserByUnit(Request $request){
        $search = $request->input('search');
        $status = $request->input('status');
        $unit = $request->unit_id;
        $title_id = $request->title_id;
        $area = $request->input('area');

        // $unit_user = UnitManager::getIdUnitManagedByUser();

        $query = ProfileView::query();
        $query->select([
            'el_profile_view.id',
            'el_profile_view.user_id',
            'el_profile_view.code',
            'el_profile_view.full_name',
            'el_profile_view.avatar',
            'el_profile_view.title_id',
        ]);
        $query->from('el_profile_view');
        $query->leftjoin('user as u','u.id','=','el_profile_view.user_id');
        $query->where('el_profile_view.user_id', '>', 2);
        $query->where('el_profile_view.type_user', '=', 1);
        // $query->whereIn('el_profile_view.unit_id', $unit_user);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('el_profile_view.full_name', 'like', '%' . $search . '%');
                $sub_query->orWhere('el_profile_view.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile_view.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('u.username', 'like', '%'. $search .'%');
            });
        }
        if ($request->area) {
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);

            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->whereIn('el_profile_view.area_id', $area_id);
                $sub_query->orWhere('el_profile_view.area_id', '=', $area->id);
            });
        }
        if (!is_null($status)) {
            $query->where('el_profile_view.status_id', '=', $status);
        }
        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('el_profile_view.unit_id', $unit_id);
                $sub_query->orWhere('el_profile_view.unit_id', '=', $unit->id);
            });
        }
        if ($title_id) {
            $query->where('el_profile_view.title_id', '=', $title_id);
        }

        $query->orderBy('el_profile_view.status_id', 'desc');
        $query->orderBy('el_profile_view.code', 'desc');

        $data['rows'] = $query->paginate(12);
        foreach ($data['rows'] as $row) {
            $row->img_avatar = $row->getAvatar();

            $progress_roadmap = ProfileProgressRoadmap::where('user_id', $row->user_id)->where('title_id', $row->title_id)->first();
            $row->percent_roadmap = $progress_roadmap ? number_format($progress_roadmap->percent).'%' : '';

            if($progress_roadmap->percent >= 100){
                $row->text_color = 'text-success';
            }else if($progress_roadmap->percent < 1){
                $row->text_color = 'text-danger';
            }else{
                $row->text_color = 'text-warning';
            }
        }

        return $data['rows'];
    }

    public function detail($user_id, Request $request){
        $profile = ProfileView::find($user_id);

        $pieChartOnlineCourse = $this->pieChartOnlineCourse($user_id);
        $pieChartOfflineCourse = $this->pieChartOfflineCourse($user_id);
        $pieChartQuiz = $this->pieChartQuiz($user_id);

        return view('themes.mobile.frontend.profile_unit.detail', [
            'profile' => $profile,
            'pieChartOnlineCourse' => $pieChartOnlineCourse,
            'pieChartOfflineCourse' => $pieChartOfflineCourse,
            'pieChartQuiz' => $pieChartQuiz,
        ]);
    }

    public function detailModel($user_id, $type, Request $request){
        $profile = ProfileView::find($user_id);

        if($type == 1){
            $table_url = route('themes.mobile.frontend.profile_unit.data_online', ['user_id' => $user_id]);
        }else if($type == 2){
            $table_url = route('themes.mobile.frontend.profile_unit.data_offline', ['user_id' => $user_id]);
        }else{
            $table_url = route('themes.mobile.frontend.profile_unit.data_quiz', ['user_id' => $user_id]);
        }

        return view('themes.mobile.frontend.profile_unit.detail_model', [
            'profile' => $profile,
            'type' => $type,
            'table_url' => $table_url,
        ]);
    }

    public function dataOnline($user_id, Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 50);

        $query = DB::table('el_online_register');
        $query->select([
            'el_online_register.id',
            'el_online_register.course_id',
            'el_online_register.user_id',
            'course.code',
            'course.name',
            'course.start_date',
            'course.end_date',
        ]);
        $query->leftJoin('el_online_course as course', 'course.id', '=', 'el_online_register.course_id');
        $query->where('el_online_register.user_id', $user_id);
        $query->where('el_online_register.status', 1);
        $query->where('course.status', 1);
        $query->where('course.isopen', 1);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->time = get_date($row->start_date) .' - '. ($row->end_date ? get_date($row->end_date) : 'Vô thời hạn');

            $condition = OnlineCourseCondition::where('course_id', '=', $row->course_id)->first();
            $activity_condition = ($condition && $condition->activity) ? explode(',', $condition->activity) : [];
            $activity_complete = OnlineCourseActivityCompletion::whereCourseId($row->course_id)->where('user_id', $row->user_id)->where('status', 1)->count();

            $bg_color = '';
            $percent = '';

            $studying = OnlineRegister::whereStatus(1)
                ->where('user_id', $row->user_id)
                ->where('course_id', $row->course_id)
                ->whereNotNull('cron_complete')
                ->where(DB::raw(1), '=', function($sub) use($activity_condition, $row){
                    $sub->select(DB::raw('CASE WHEN COUNT(activity_id) < '.count($activity_condition).' THEN 1 ELSE 0 END'))
                        ->from('el_online_course_activity_completion')
                        ->whereColumn('course_id', '=', 'el_online_register.course_id')
                        ->whereColumn('user_id', '=', 'el_online_register.user_id')
                        ->where('user_id', $row->user_id)
                        ->where('status', 1);
                })->exists();
            $not_learned = OnlineRegister::whereStatus(1)->whereCourseId($row->course_id)->whereUserId($row->user_id)->whereNull('cron_complete')->exists();

            $complete = OnlineCourseComplete::whereCourseId($row->course_id)->where('user_id', $row->user_id)->exists();
            if($complete){
                $bg_color = 'completed';
                $percent = '100%';
            }else if($not_learned){
                $bg_color = 'not_learned';
            }else if($studying){
                $bg_color = 'studying';
                $percent = count($activity_condition) > 0 ? number_format($activity_complete/count($activity_condition)*100) .'%' : '';
            }else{
                $bg_color = 'uncomplete';
                $percent = count($activity_condition) > 0 ? number_format($activity_complete/count($activity_condition)*100) .'%' : '';
            }

            $row->bg_color = $bg_color;
            $row->percent = $percent;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function dataOffline($user_id, Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 50);

        $query = DB::table('el_offline_register');
        $query->select([
            'el_offline_register.id',
            'el_offline_register.course_id',
            'el_offline_register.class_id',
            'el_offline_register.user_id',
            'course.code',
            'course.name',
            'course.start_date',
            'course.end_date',
        ]);
        $query->leftJoin('el_offline_course as course', 'course.id', '=', 'el_offline_register.course_id');
        $query->where('el_offline_register.user_id', $user_id);
        $query->where('el_offline_register.status', 1);
        $query->where('course.status', 1);
        $query->where('course.isopen', 1);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->time = get_date($row->start_date) .' - '. ($row->end_date ? get_date($row->end_date) : 'Vô thời hạn');

            $schedule = OfflineSchedule::whereCourseId($row->course_id)->where('class_id', $row->class_id)->count();
            $attendance = OfflineAttendance::where('course_id', $row->course_id)->where('user_id', $row->user_id)->where('status', 1)->sum('percent');

            $bg_color = '';
            $percent = '';

            $studying = OfflineRegister::whereStatus(1)
                ->where('user_id', $row->user_id)
                ->where('course_id', $row->course_id)
                ->whereNotNull('cron_complete')
                ->whereExists(function($sub) {
                    $sub->select(\DB::raw(1))
                        ->from('el_offline_attendance')
                        ->whereColumn('course_id', '=', 'el_offline_register.course_id')
                        ->whereColumn('user_id', '=', 'el_offline_register.user_id')
                        ->where('status', 1);
                })
                ->whereNotExists(function($sub) {
                    $sub->select(\DB::raw(1))
                        ->from('el_offline_course_complete')
                        ->whereColumn('course_id', '=', 'el_offline_register.course_id')
                        ->whereColumn('user_id', '=', 'el_offline_register.user_id');
                })->exists();

            $not_learned = OfflineRegister::whereStatus(1)->whereCourseId($row->course_id)->whereUserId($row->user_id)->whereNull('cron_complete')->exists();

            $complete = OfflineCourseComplete::whereCourseId($row->course_id)->where('user_id', $row->user_id)->exists();
            if($complete){
                $bg_color = 'completed';
                $percent = '100%';
            }else if($not_learned){
                $bg_color = 'not_learned';
            }else if($studying){
                $bg_color = 'studying';
                $percent = $schedule ? number_format($attendance/$schedule) .'%' : '';
            }else{
                $bg_color = 'uncomplete';
                $percent = $schedule ? number_format($attendance/$schedule) .'%' : '';
            }

            $row->bg_color = $bg_color;
            $row->percent = $percent;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function dataQuiz($user_id, Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 50);

        $query = DB::table('el_quiz_register');
        $query->select([
            'el_quiz_register.id',
            'el_quiz_register.quiz_id',
            'el_quiz_register.user_id',
            'quiz.code',
            'quiz.name',
            'quiz.start_quiz',
            'quiz.end_quiz',
        ]);
        $query->leftJoin('el_quiz as quiz', 'quiz.id', '=', 'el_quiz_register.quiz_id');
        $query->where('el_quiz_register.user_id', $user_id);
        $query->where('quiz.status', 1);
        $query->where('quiz.is_open', 1);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->time = get_date($row->start_quiz) .' - '. ($row->end_quiz ? get_date($row->end_quiz) : 'Vô thời hạn');

            $bg_color = '';
            $percent = '';

            $not_learned = QuizRegister::whereQuizId($row->quiz_id)
                ->where('user_id', $row->user_id)
                ->whereNotExists(function($sub){
                    $sub->select(\DB::raw(1))
                        ->from('el_quiz_attempts')
                        ->whereColumn('quiz_id', '=', 'el_quiz_register.quiz_id')
                        ->whereColumn('user_id', '=', 'el_quiz_register.user_id');
                })->exists();

            $complete = QuizRegister::whereQuizId($row->quiz_id)
                ->where('user_id', $row->user_id)
                ->whereExists(function($sub){
                    $sub->select(\DB::raw(1))
                        ->from('el_quiz_result')
                        ->whereColumn('quiz_id', '=', 'el_quiz_register.quiz_id')
                        ->whereColumn('user_id', '=', 'el_quiz_register.user_id')
                        ->where('result', 1);
                })->exists();

            if($complete){
                $bg_color = 'completed';
                $percent = '100%';
            }else if($not_learned){
                $bg_color = 'not_learned';
            }else{
                $bg_color = 'uncomplete';
                $percent = '0%';
            }

            $row->bg_color = $bg_color;
            $row->percent = $percent;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    //Biểu đồ tròn khoá học Online
    private function pieChartOnlineCourse($user_id)
    {
        $total_register = OnlineRegister::whereStatus(1)->whereUserId($user_id)
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_online_course')
                ->whereColumn('id', '=', 'el_online_register.course_id')
                ->where('status', 1)
                ->where('isopen', 1);
            })
            ->count('course_id');
        $not_learned = OnlineRegister::whereStatus(1)->whereUserId($user_id)->whereNull('cron_complete')
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_online_course')
                ->whereColumn('id', '=', 'el_online_register.course_id')
                ->where('status', 1)
                ->where('isopen', 1);
            })
            ->count('course_id');
        $completed = OnlineCourseComplete::where('user_id', $user_id)
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_online_course')
                ->whereColumn('id', '=', 'el_online_course_complete.course_id')
                ->where('status', 1)
                ->where('isopen', 1);
            })
            ->count('course_id');
        $studying = OnlineRegister::whereStatus(1)
            ->where('user_id', $user_id)
            ->whereNotNull('cron_complete')
            ->where(
                function($sub){
                    $sub->select(\DB::raw('COUNT(a.id)'))
                        ->from('el_online_course_activity as a')
                        ->join('el_online_course_condition as b', 'b.course_id', '=', 'a.course_id')
                        ->whereRaw('FIND_IN_SET(a.id,b.activity)')
                        ->whereColumn('a.course_id', '=', 'el_online_register.course_id')
                        ->groupBy('a.course_id');
                }, '>', function($sub2){
                    $sub2->select(\DB::raw('COUNT(activity_id)'))
                    ->from('el_online_course_activity_completion')
                    ->whereColumn('course_id', '=', 'el_online_register.course_id')
                    ->whereColumn('user_id', '=', 'el_online_register.user_id')
                    ->where('status', 1);
                }
            )
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_online_course')
                ->whereColumn('id', '=', 'el_online_register.course_id')
                ->where('status', 1)
                ->where('isopen', 1);
            })
            ->count('course_id');
        $uncompleted = $total_register - ($studying + $not_learned + $completed);

        $result = [$studying, $not_learned, $completed, $uncompleted];

        return $result;
    }

    //Biểu đồ tròn khoá học Offline
    private function pieChartOfflineCourse($user_id)
    {
        $total_register = OfflineRegister::whereStatus(1)->whereUserId($user_id)
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_offline_course')
                ->whereColumn('id', '=', 'el_offline_register.course_id')
                ->where('status', 1)
                ->where('isopen', 1);
            })
            ->count('course_id');
        $not_learned = OfflineRegister::whereStatus(1)->whereUserId($user_id)->whereNull('cron_complete')
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_offline_course')
                ->whereColumn('id', '=', 'el_offline_register.course_id')
                ->where('status', 1)
                ->where('isopen', 1);
            })
            ->count('course_id');
        $completed = OfflineCourseComplete::where('user_id', $user_id)
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_offline_course')
                ->whereColumn('id', '=', 'el_offline_course_complete.course_id')
                ->where('status', 1)
                ->where('isopen', 1);
            })
            ->count('course_id');
        $studying = OfflineRegister::whereStatus(1)
            ->where('user_id', $user_id)
            ->whereNotNull('cron_complete')
            ->whereExists(function($sub) {
                $sub->select(\DB::raw(1))
                    ->from('el_offline_attendance')
                    ->whereColumn('course_id', '=', 'el_offline_register.course_id')
                    ->whereColumn('user_id', '=', 'el_offline_register.user_id')
                    ->where('status', 1);
            })
            ->whereNotExists(function($sub) {
                $sub->select(\DB::raw(1))
                    ->from('el_offline_course_complete')
                    ->whereColumn('course_id', '=', 'el_offline_register.course_id')
                    ->whereColumn('user_id', '=', 'el_offline_register.user_id');
            })
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_offline_course')
                ->whereColumn('id', '=', 'el_offline_register.course_id')
                ->where('status', 1)
                ->where('isopen', 1);
            })
            ->count('course_id');
        $uncompleted = $total_register - ($studying + $not_learned + $completed);

        $result = [$studying, $not_learned, $completed, $uncompleted];

        return $result;
    }

    //Biểu đồ tròn kỳ thi
    private function pieChartQuiz($user_id)
    {
        //Tổng số ghi danh kỳ thi
        $register = QuizRegister::where('user_id', $user_id)
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_quiz')
                ->whereColumn('id', '=', 'el_quiz_register.quiz_id')
                ->where('status', 1)
                ->where('is_open', 1);
            })
            ->count('quiz_id');

        //HV chưa thi. Mới ghi danh, chưa nằm trong bảng lần làm bài thi (el_quiz_attempts)
        $unlearned = QuizRegister::where('user_id', $user_id)
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_quiz')
                ->whereColumn('id', '=', 'el_quiz_register.quiz_id')
                ->where('status', 1)
                ->where('is_open', 1);
            })
            ->whereNotExists(function($sub){
                $sub->select(\DB::raw(1))
                    ->from('el_quiz_attempts')
                    ->whereColumn('quiz_id', '=', 'el_quiz_register.quiz_id')
                    ->whereColumn('user_id', '=', 'el_quiz_register.user_id');
            })->count('quiz_id');

        //HV hoàn thành thi
        $completed = QuizRegister::where('user_id', $user_id)
            ->whereExists(function($sub){
                $sub->select(['id'])
                ->from('el_quiz')
                ->whereColumn('id', '=', 'el_quiz_register.quiz_id')
                ->where('status', 1)
                ->where('is_open', 1);
            })
            ->whereExists(function($sub){
                $sub->select(\DB::raw(1))
                    ->from('el_quiz_result')
                    ->whereColumn('quiz_id', '=', 'el_quiz_register.quiz_id')
                    ->whereColumn('user_id', '=', 'el_quiz_register.user_id')
                    ->where('result', 1);
            })->count('quiz_id');

        //HV chưa hoàn thành thi
        $uncompleted = $register - ($unlearned + $completed);

        $result = [$unlearned, $completed, $uncompleted];

        return $result;
    }

    // LỘ TRÌNH ĐÀO TẠO
    public function trainingByTitle($user_id) {
        $profile = ProfileView::where('user_id', $user_id)->first();
        $get_training_roadmap = $this->getTrainingRoadmap($user_id, @$profile->title_id, @$profile->join_company);
        return view('themes.mobile.frontend.profile_unit.training_by_title', [
            'get_training_roadmap' => $get_training_roadmap,
            'profile' => $profile,
        ]);
    }

    // LỘ TRÌNH ĐÀO TẠO
    public function getTrainingRoadmap($user_id, $title_id, $join_company = null){
        $subject_user_new = ['HNV', 'KTSPDCDNP&S'];
        $check_user_new = false;
        $count_subject_complete = 0;
        
        if($join_company){
            $count_subject_complete = UserCompletedSubject::leftJoin('el_subject as subject', 'subject.id', '=', 'el_user_completed_subject.subject_id')
                ->where('user_id', $user_id)
                ->whereIn('subject.code', $subject_user_new)
                ->count();

            $check_day = date('Y-m-d', strtotime($join_company."+ 45 days"));
            if($check_day >= now()){
                $check_user_new = true;
            }
        }

        $query = TrainingRoadmap::query();
        $query->select([
            'a.subject_id',
            'a.training_form',
            'subject.code',
            'subject.name',
        ]);
        $query->from("el_trainingroadmap AS a");
        $query->leftJoin('el_subject as subject', 'subject.id', '=', 'a.subject_id');
        $query->where('a.title_id','=', $title_id);
        if($check_user_new && $count_subject_complete < 2){ //Còn là nhân viên mới và chưa hoàn thành 2 HP bắt buộc ('HNV', 'KTSPDCDNP&S')
            $query->whereIn('subject.code', $subject_user_new);
        }
        $query->orderBy('a.order');
        $rows = $query->get();

        foreach($rows as $row){
            if($row->training_form){
                $complete = UserCompletedSubject::whereSubjectId($row->subject_id)->where('user_id', $user_id)->where('course_type', $row->training_form);
                $row->percent = $complete->exists() ? 100 : 0;
            }else{
                $complete = UserCompletedSubject::whereSubjectId($row->subject_id)->where('user_id', $user_id);
                $row->percent = $complete->exists() ? 100 : 0;
            }
        }

        return $rows;
    }

    // QUÁ TRÌNH HỌC
    public function trainingProcess($user_id) {
        $query = TrainingProcess::query();
        $query->select([
            'id',
            'course_id',
            'course_code as code',
            'course_name as name',
            'course_type',
            'pass as result',
            'start_date',
            'end_date',
        ]);
        $query->from('el_training_process as process');
        $query->where('user_id','=', $user_id);
        $rows = $query->paginate(20);

        foreach($rows as $item){
            if ($item->course_type == 1){
                $percent = OnlineCourse::percentCompleteCourseByUser($item->course_id, $user_id);
                $type = 'Online';
                $url = route('themes.mobile.frontend.online.detail',['course_id' => $item->course_id]);
            }else{
                $percent = OfflineCourse::percent($item->course_id, $user_id);
                $type = 'Offline';
                $url = route('themes.mobile.frontend.offline.detail',['course_id' => $item->course_id]);
            }

            $item->percent = $percent;
            $item->type = $type;
            $item->url = $url;
        }

        return view('themes.mobile.frontend.profile_unit.training_process', [
            'get_history_course' => $rows,
        ]);
    }

    public function infoUser($user_id) {
        $user = User::find($user_id, ['username']);
        $profile = ProfileView::where('user_id', $user_id)->first(['full_name', 'title_name', 'unit_name', 'code', 'email', 'phone']);
        return view('themes.mobile.frontend.profile_unit.modal_info', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }
}
