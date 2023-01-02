<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\Unit;
use Illuminate\Support\Facades\DB;
use App\Models\Categories\UnitManager;
use Carbon\Carbon;

use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\DashboardUnit\Entities\DashboardUnitOfflineCourse;
use Modules\DashboardUnit\Entities\DashboardUnitOnlineCourse;
use Modules\DashboardUnit\Entities\DashboardUnitQuiz;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCourseComplete;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Online\Entities\OnlineCourseActivityCompletion;
use Modules\Online\Entities\OnlineCourseComplete;
use Modules\Online\Entities\OnlineCourseCondition;
use Modules\Online\Entities\OnlineRegister;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizRegister;
use Modules\User\Entities\User;

class DashboardUnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $lay = 'dashboard_unit';

        if(is_null(session('user_unit'))){
            $unit_manager = UnitManager::where('user_id', profile()->user_id)->first();
            $unit_user = @$unit_manager->unit_id;
        }else{
            $unit_user = session('user_unit');
        }

        $getCourseOnline = $this->dataCourseOnline($unit_user, $request);
        $getCourseOffline = $this->dataCourseOffline($unit_user, $request);
        $getQuiz = $this->dataQuiz($unit_user, $request);

        $dataCourseOnline = $getCourseOnline[0];
        $courseOnlineNameData = $getCourseOnline[1];
        $courseOnlineIdData = $getCourseOnline[2];

        $dataCourseOffline = $getCourseOffline[0];
        $courseOfflineNameData = $getCourseOffline[1];
        $courseOfflineIdData = $getCourseOffline[2];

        $dataQuiz = $getQuiz[0];
        $quizNameData = $getQuiz[1];
        $quizIdData = $getQuiz[2];

        $userUnits = auth()->check() ? User::getRoleAndManagerUnitUser() : [];
        return view('themes.mobile.frontend.unit_manager.index', [
            'lay' => $lay,

            'dataCourseOnline' => $dataCourseOnline,
            'courseOnlineNameData' => $courseOnlineNameData,
            'courseOnlineIdData' => $courseOnlineIdData,

            'dataCourseOffline' => $dataCourseOffline,
            'courseOfflineNameData' => $courseOfflineNameData,
            'courseOfflineIdData' => $courseOfflineIdData,

            'dataQuiz' => $dataQuiz,
            'quizNameData' => $quizNameData,
            'quizIdData' => $quizIdData,
            'userUnits' => $userUnits
        ]);
    }

    public function userOnline(Request $request){
        $model_id = $request->model_id;
        $year_course = $request->year_course;
        $month_course = $request->month_course;
        $status_course = $request->status_course;
        $filter_name = $request->filter_name;
        $data_model = explode(',', $request->data_model);

        return view('themes.mobile.frontend.dashboard_unit.user_course_online', [
            'model_id' => $model_id,
            'year_course' => $year_course,
            'month_course' => $month_course,
            'status_course' => $status_course,
            'filter_name' => $filter_name,
            'data_model' => $data_model,
        ]);
    }

    public function userOffline(Request $request){
        $model_id = $request->model_id;
        $year_course = $request->year_course;
        $month_course = $request->month_course;
        $status_course = $request->status_course;
        $filter_name = $request->filter_name;
        $data_model = explode(',', $request->data_model);

        return view('themes.mobile.frontend.dashboard_unit.user_course_offline', [
            'model_id' => $model_id,
            'year_course' => $year_course,
            'month_course' => $month_course,
            'status_course' => $status_course,
            'filter_name' => $filter_name,
            'data_model' => $data_model,
        ]);
    }

    public function userQuiz(Request $request){
        $model_id = $request->model_id;
        $year_course = $request->year_course;
        $month_course = $request->month_course;
        $status_course = $request->status_course;
        $filter_name = $request->filter_name;
        $data_model = explode(',', $request->data_model);

        return view('themes.mobile.frontend.dashboard_unit.user_quiz', [
            'model_id' => $model_id,
            'year_course' => $year_course,
            'month_course' => $month_course,
            'status_course' => $status_course,
            'filter_name' => $filter_name,
            'data_model' => $data_model,
        ]);
    }

    private function dataCourseOnline($unit_user, Request $request)
    {
        if(count($request->all()) == 0){
            $studying = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)->sum('studying');
            $unlearned = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)->sum('unlearned');
            $completed = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)->sum('completed');
            $uncompleted = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)->sum('uncompleted');

            $data = [$studying, $unlearned, $completed, $uncompleted];
            $courseName = 'Trạng thái nhân viên các khoá học';
            $courseId = '';
        }else{
            $model_id = $request->model_id;
            $year_course = $request->year_course;
            $month_course = $request->month_course;
            $status_course = $request->status_course;
            $filter_name = $request->filter_name;

            if($filter_name){
                if($filter_name == 'model_new'){
                    $models = DashboardUnitOnlineCourse::where('unit_id', $unit_user)->orderByDesc('start_date')->first();
                    $course = OnlineCourse::find($models->course_id, ['id', 'code', 'name', 'start_date', 'end_date']);

                    $data = [$models->studying, $models->unlearned, $models->completed, $models->uncompleted];
                    $courseName = $course ? ($course->name .' ('. get_date($course->start_date) .' - '. ($course->end_date ? get_date($course->end_date) : 'Vô thời hạn') .')') : '';
                    $courseId = $course ? $course->id : '';
                }
                if($filter_name == 'week_now'){
                    $now = Carbon::now();
                    $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i:s');
                    $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i:s');

                    $studying = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)
                        ->where('start_date', '>=', $weekStartDate)
                        ->where('start_date', '<=', $weekEndDate)
                        ->sum('studying');

                    $unlearned = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)
                        ->where('start_date', '>=', $weekStartDate)
                        ->where('start_date', '<=', $weekEndDate)
                        ->sum('unlearned');

                    $completed = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)
                        ->where('start_date', '>=', $weekStartDate)
                        ->where('start_date', '<=', $weekEndDate)
                        ->sum('completed');

                    $uncompleted = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)
                        ->where('start_date', '>=', $weekStartDate)
                        ->where('start_date', '<=', $weekEndDate)
                        ->sum('uncompleted');

                    $data = [$studying, $unlearned, $completed, $uncompleted];
                    $courseName = 'Trạng thái nhân viên các khoá học theo tuần hiện tại';
                    $courseId = '';
                }
                if($filter_name == 'month_now'){
                    $month_now = date('m');
                    $year_now = date('Y');

                    $studying = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)
                        ->where(DB::raw('month(start_date)'), $month_now)
                        ->where(DB::raw('year(start_date)'), $year_now)
                        ->sum('studying');

                    $unlearned = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)
                        ->where(DB::raw('month(start_date)'), $month_now)
                        ->where(DB::raw('year(start_date)'), $year_now)
                        ->sum('unlearned');

                    $completed = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)
                        ->where(DB::raw('month(start_date)'), $month_now)
                        ->where(DB::raw('year(start_date)'), $year_now)
                        ->sum('completed');

                    $uncompleted = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)
                        ->where(DB::raw('month(start_date)'), $month_now)
                        ->where(DB::raw('year(start_date)'), $year_now)
                        ->sum('uncompleted');

                    $data = [$studying, $unlearned, $completed, $uncompleted];
                    $courseName = 'Trạng thái nhân viên các khoá học theo tháng hiện tại';
                    $courseId = '';
                }
                if($filter_name == 'year_now'){
                    $year_now = date('Y');

                    $studying = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)->where(DB::raw('year(start_date)'), $year_now)->sum('studying');
                    $unlearned = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)->where(DB::raw('year(start_date)'), $year_now)->sum('unlearned');
                    $completed = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)->where(DB::raw('year(start_date)'), $year_now)->sum('completed');
                    $uncompleted = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)->where(DB::raw('year(start_date)'), $year_now)->sum('uncompleted');

                    $data = [$studying, $unlearned, $completed, $uncompleted];
                    $courseName = 'Trạng thái nhân viên các khoá học theo năm hiện tại';
                    $courseId = '';
                }
            }else if($model_id){
                $models = DashboardUnitOnlineCourse::where('unit_id', $unit_user)->where('course_id', $model_id)->first();
                $course = OnlineCourse::find($models->course_id, ['id', 'code', 'name', 'start_date', 'end_date']);

                switch ($status_course) {
                    case 'studying':
                        $data = [$models->studying, 0, 0, 0];
                        break;
                    case 'unlearned':
                        $data = [0, $models->unlearned, 0, 0];
                        break;
                    case 'completed':
                        $data = [0, 0, $models->completed, 0];
                        break;
                    case 'uncompleted':
                        $data = [0, 0, 0, $models->uncompleted];
                        break;
                    default:
                        $data = [$models->studying, $models->unlearned, $models->completed, $models->uncompleted];
                        break;
                }

                $courseName = $course ? ($course->name .' ('. get_date($course->start_date) .' - '. ($course->end_date ? get_date($course->end_date) : 'Vô thời hạn') .')') : '';
                $courseId = $course ? $course->id : '';
            }else if($status_course){
                $text_status = '';
                switch ($status_course) {
                    case 'studying':
                        $studying = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)->sum('studying');
                        $data = [$studying, 0, 0, 0];
                        $text_status = 'Đang học';
                        break;
                    case 'unlearned':
                        $unlearned = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)->sum('unlearned');
                        $data = [0, $unlearned, 0, 0];
                        $text_status = 'Chưa học';
                        break;
                    case 'completed':
                        $completed = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)->sum('completed');
                        $data = [0, 0, $completed, 0];
                        $text_status = 'Hoàn thành';
                        break;
                    case 'uncompleted':
                        $uncompleted = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)->sum('uncompleted');
                        $data = [0, 0, 0, $uncompleted];
                        $text_status = 'Chưa hoàn thành';
                        break;
                }

                $courseName = 'Trạng thái nhân viên các khoá '. $text_status;
                $courseId = '';
            }else if($month_course && $year_course){
                $studying = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)
                    ->where(DB::raw('month(start_date)'), $month_course)
                    ->where(DB::raw('year(start_date)'), $year_course)
                    ->sum('studying');

                $unlearned = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)
                    ->where(DB::raw('month(start_date)'), $month_course)
                    ->where(DB::raw('year(start_date)'), $year_course)
                    ->sum('unlearned');

                $completed = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)
                    ->where(DB::raw('month(start_date)'), $month_course)
                    ->where(DB::raw('year(start_date)'), $year_course)
                    ->sum('completed');

                $uncompleted = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)
                    ->where(DB::raw('month(start_date)'), $month_course)
                    ->where(DB::raw('year(start_date)'), $year_course)
                    ->sum('uncompleted');

                switch ($status_course) {
                    case 'studying':
                        $data = [$studying, 0, 0, 0];
                        break;
                    case 'unlearned':
                        $data = [0, $unlearned, 0, 0];
                        break;
                    case 'completed':
                        $data = [0, 0, $completed, 0];
                        break;
                    case 'uncompleted':
                        $data = [0, 0, 0, $uncompleted];
                        break;
                    default:
                        $data = [$studying, $unlearned, $completed, $uncompleted];
                        break;
                }

                $courseName = 'Trạng thái nhân viên các khoá học theo tháng '. $month_course;
                $courseId = '';
            }else{
                $studying = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)->sum('studying');
                $unlearned = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)->sum('unlearned');
                $completed = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)->sum('completed');
                $uncompleted = (int) DashboardUnitOnlineCourse::where('unit_id', $unit_user)->sum('uncompleted');

                $data = [$studying, $unlearned, $completed, $uncompleted];
                $courseName = 'Trạng thái nhân viên các khoá học';
                $courseId = '';
            }
        }

        return [
            $data,
            $courseName,
            $courseId
        ];
    }

    private function dataCourseOffline($unit_user, Request $request)
    {
        if(count($request->all()) == 0){
            $studying = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)->sum('studying');
            $unlearned = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)->sum('unlearned');
            $completed = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)->sum('completed');
            $uncompleted = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)->sum('uncompleted');

            $data = [$studying, $unlearned, $completed, $uncompleted];
            $courseName = 'Trạng thái nhân viên các khoá học';
            $courseId = '';
        }else{
            $model_id = $request->model_id;
            $year_course = $request->year_course;
            $month_course = $request->month_course;
            $status_course = $request->status_course;
            $filter_name = $request->filter_name;

            if($filter_name){
                if($filter_name == 'model_new'){
                    $models = DashboardUnitOfflineCourse::where('unit_id', $unit_user)->orderByDesc('start_date')->first();
                    $course = OfflineCourse::find($models->course_id, ['id', 'code', 'name', 'start_date', 'end_date']);

                    $data = [$models->studying, $models->unlearned, $models->completed, $models->uncompleted];
                    $courseName = $course ? ($course->name .' ('. get_date($course->start_date) .' - '. ($course->end_date ? get_date($course->end_date) : 'Vô thời hạn') .')') : '';
                    $courseId = $course ? $course->id : '';
                }
                if($filter_name == 'week_now'){
                    $now = Carbon::now();
                    $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i:s');
                    $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i:s');

                    $studying = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)
                        ->where('start_date', '>=', $weekStartDate)
                        ->where('start_date', '<=', $weekEndDate)
                        ->sum('studying');

                    $unlearned = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)
                        ->where('start_date', '>=', $weekStartDate)
                        ->where('start_date', '<=', $weekEndDate)
                        ->sum('unlearned');

                    $completed = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)
                        ->where('start_date', '>=', $weekStartDate)
                        ->where('start_date', '<=', $weekEndDate)
                        ->sum('completed');

                    $uncompleted = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)
                        ->where('start_date', '>=', $weekStartDate)
                        ->where('start_date', '<=', $weekEndDate)
                        ->sum('uncompleted');

                    $data = [$studying, $unlearned, $completed, $uncompleted];
                    $courseName = 'Trạng thái nhân viên các khoá học theo tuần hiện tại';
                    $courseId = '';
                }
                if($filter_name == 'month_now'){
                    $month_now = date('m');
                    $year_now = date('Y');

                    $studying = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)
                        ->where(DB::raw('month(start_date)'), $month_now)
                        ->where(DB::raw('year(start_date)'), $year_now)
                        ->sum('studying');

                    $unlearned = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)
                        ->where(DB::raw('month(start_date)'), $month_now)
                        ->where(DB::raw('year(start_date)'), $year_now)
                        ->sum('unlearned');

                    $completed = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)
                        ->where(DB::raw('month(start_date)'), $month_now)
                        ->where(DB::raw('year(start_date)'), $year_now)
                        ->sum('completed');

                    $uncompleted = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)
                        ->where(DB::raw('month(start_date)'), $month_now)
                        ->where(DB::raw('year(start_date)'), $year_now)
                        ->sum('uncompleted');

                    $data = [$studying, $unlearned, $completed, $uncompleted];
                    $courseName = 'Trạng thái nhân viên các khoá học theo tháng hiện tại';
                    $courseId = '';
                }
                if($filter_name == 'year_now'){
                    $year_now = date('Y');

                    $studying = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)->where(DB::raw('year(start_date)'), $year_now)->sum('studying');
                    $unlearned = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)->where(DB::raw('year(start_date)'), $year_now)->sum('unlearned');
                    $completed = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)->where(DB::raw('year(start_date)'), $year_now)->sum('completed');
                    $uncompleted = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)->where(DB::raw('year(start_date)'), $year_now)->sum('uncompleted');

                    $data = [$studying, $unlearned, $completed, $uncompleted];
                    $courseName = 'Trạng thái nhân viên các khoá học theo năm hiện tại';
                    $courseId = '';
                }
            }else if($model_id){
                $models = DashboardUnitOfflineCourse::where('unit_id', $unit_user)->where('course_id', $model_id)->first();
                $course = OfflineCourse::find($models->course_id, ['id', 'code', 'name', 'start_date', 'end_date']);

                switch ($status_course) {
                    case 'studying':
                        $data = [$models->studying, 0, 0, 0];
                        break;
                    case 'unlearned':
                        $data = [0, $models->unlearned, 0, 0];
                        break;
                    case 'completed':
                        $data = [0, 0, $models->completed, 0];
                        break;
                    case 'uncompleted':
                        $data = [0, 0, 0, $models->uncompleted];
                        break;
                    default:
                        $data = [$models->studying, $models->unlearned, $models->completed, $models->uncompleted];
                        break;
                }

                $courseName = $course ? ($course->name .' ('. get_date($course->start_date) .' - '. ($course->end_date ? get_date($course->end_date) : 'Vô thời hạn') .')') : '';
                $courseId = $course ? $course->id : '';
            }else if($status_course){
                $text_status = '';
                switch ($status_course) {
                    case 'studying':
                        $studying = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)->sum('studying');
                        $data = [$studying, 0, 0, 0];
                        $text_status = 'Đang học';
                        break;
                    case 'unlearned':
                        $unlearned = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)->sum('unlearned');
                        $data = [0, $unlearned, 0, 0];
                        $text_status = 'Chưa học';
                        break;
                    case 'completed':
                        $completed = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)->sum('completed');
                        $data = [0, 0, $completed, 0];
                        $text_status = 'Hoàn thành';
                        break;
                    case 'uncompleted':
                        $uncompleted = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)->sum('uncompleted');
                        $data = [0, 0, 0, $uncompleted];
                        $text_status = 'Chưa hoàn thành';
                        break;
                }

                $courseName = 'Trạng thái nhân viên các khoá '. $text_status;
                $courseId = '';
            }else if($month_course && $year_course){
                $studying = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)
                    ->where(DB::raw('month(start_date)'), $month_course)
                    ->where(DB::raw('year(start_date)'), $year_course)
                    ->sum('studying');

                $unlearned = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)
                    ->where(DB::raw('month(start_date)'), $month_course)
                    ->where(DB::raw('year(start_date)'), $year_course)
                    ->sum('unlearned');

                $completed = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)
                    ->where(DB::raw('month(start_date)'), $month_course)
                    ->where(DB::raw('year(start_date)'), $year_course)
                    ->sum('completed');

                $uncompleted = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)
                    ->where(DB::raw('month(start_date)'), $month_course)
                    ->where(DB::raw('year(start_date)'), $year_course)
                    ->sum('uncompleted');

                switch ($status_course) {
                    case 'studying':
                        $data = [$studying, 0, 0, 0];
                        break;
                    case 'unlearned':
                        $data = [0, $unlearned, 0, 0];
                        break;
                    case 'completed':
                        $data = [0, 0, $completed, 0];
                        break;
                    case 'uncompleted':
                        $data = [0, 0, 0, $uncompleted];
                        break;
                    default:
                        $data = [$studying, $unlearned, $completed, $uncompleted];
                        break;
                }

                $courseName = 'Trạng thái nhân viên các khoá học theo tháng '. $month_course;
                $courseId = '';
            }else{
                $studying = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)->sum('studying');
                $unlearned = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)->sum('unlearned');
                $completed = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)->sum('completed');
                $uncompleted = (int) DashboardUnitOfflineCourse::where('unit_id', $unit_user)->sum('uncompleted');

                $data = [$studying, $unlearned, $completed, $uncompleted];
                $courseName = 'Trạng thái nhân viên các khoá học';
                $courseId = '';
            }
        }

        return [
            $data,
            $courseName,
            $courseId
        ];
    }

    private function dataQuiz($unit_user, Request $request)
    {
        if(count($request->all()) == 0){
            $unlearned = (int) DashboardUnitQuiz::where('unit_id', $unit_user)->sum('unlearned');
            $completed = (int) DashboardUnitQuiz::where('unit_id', $unit_user)->sum('completed');
            $uncompleted = (int) DashboardUnitQuiz::where('unit_id', $unit_user)->sum('uncompleted');

            $data = [$unlearned, $completed, $uncompleted];
            $quizName = 'Trạng thái nhân viên các Kỳ thi';
            $quizId = '';
        }else{
            $model_id = $request->model_id;
            $year_course = $request->year_course;
            $month_course = $request->month_course;
            $status_course = $request->status_course;
            $filter_name = $request->filter_name;

            if($filter_name){
                if($filter_name == 'model_new'){
                    $models = DashboardUnitQuiz::where('unit_id', $unit_user)->orderByDesc('start_date')->first();
                    $quiz = Quiz::find($models->quiz_id, ['id', 'code', 'name', 'start_quiz', 'end_quiz']);

                    $data = [$models->unlearned, $models->completed, $models->uncompleted];
                    $quizName = $quiz ? ($quiz->name .' ('. get_date($quiz->start_quiz) .' - '. ($quiz->end_quiz ? get_date($quiz->end_quiz) : 'Vô thời hạn') .')') : '';
                    $quizId = $quiz ? $quiz->id : '';
                }
                if($filter_name == 'week_now'){
                    $now = Carbon::now();
                    $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i:s');
                    $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i:s');

                    $unlearned = (int) DashboardUnitQuiz::where('unit_id', $unit_user)
                        ->where('start_date', '>=', $weekStartDate)
                        ->where('start_date', '<=', $weekEndDate)
                        ->sum('unlearned');

                    $completed = (int) DashboardUnitQuiz::where('unit_id', $unit_user)
                        ->where('start_date', '>=', $weekStartDate)
                        ->where('start_date', '<=', $weekEndDate)
                        ->sum('completed');

                    $uncompleted = (int) DashboardUnitQuiz::where('unit_id', $unit_user)
                        ->where('start_date', '>=', $weekStartDate)
                        ->where('start_date', '<=', $weekEndDate)
                        ->sum('uncompleted');

                    $data = [$unlearned, $completed, $uncompleted];
                    $quizName = 'Trạng thái nhân viên các kỳ thi theo tuần hiện tại';
                    $quizId = '';
                }
                if($filter_name == 'month_now'){
                    $month_now = date('m');
                    $year_now = date('Y');

                    $unlearned = (int) DashboardUnitQuiz::where('unit_id', $unit_user)
                        ->where(DB::raw('month(start_date)'), $month_now)
                        ->where(DB::raw('year(start_date)'), $year_now)
                        ->sum('unlearned');

                    $completed = (int) DashboardUnitQuiz::where('unit_id', $unit_user)
                        ->where(DB::raw('month(start_date)'), $month_now)
                        ->where(DB::raw('year(start_date)'), $year_now)
                        ->sum('completed');

                    $uncompleted = (int) DashboardUnitQuiz::where('unit_id', $unit_user)
                        ->where(DB::raw('month(start_date)'), $month_now)
                        ->where(DB::raw('year(start_date)'), $year_now)
                        ->sum('uncompleted');

                    $data = [$unlearned, $completed, $uncompleted];
                    $quizName = 'Trạng thái nhân viên các kỳ thi theo tháng hiện tại';
                    $quizId = '';
                }
                if($filter_name == 'year_now'){
                    $year_now = date('Y');

                    $unlearned = (int) DashboardUnitQuiz::where('unit_id', $unit_user)->where(DB::raw('year(start_date)'), $year_now)->sum('unlearned');
                    $completed = (int) DashboardUnitQuiz::where('unit_id', $unit_user)->where(DB::raw('year(start_date)'), $year_now)->sum('completed');
                    $uncompleted = (int) DashboardUnitQuiz::where('unit_id', $unit_user)->where(DB::raw('year(start_date)'), $year_now)->sum('uncompleted');

                    $data = [$unlearned, $completed, $uncompleted];
                    $quizName = 'Trạng thái nhân viên các kỳ thi theo năm hiện tại';
                    $quizId = '';
                }
            }else if($model_id){
                $models = DashboardUnitQuiz::where('unit_id', $unit_user)->where('quiz_id', $model_id)->first();
                $quiz = Quiz::find($models->quiz_id, ['id', 'code', 'name', 'start_quiz', 'end_quiz']);

                switch ($status_course) {
                    case 'unlearned':
                        $data = [$models->unlearned, 0, 0];
                        break;
                    case 'completed':
                        $data = [0, $models->completed, 0];
                        break;
                    case 'uncompleted':
                        $data = [0, 0, $models->uncompleted];
                        break;
                    default:
                        $data = [$models->unlearned, $models->completed, $models->uncompleted];
                        break;
                }

                $quizName = $quiz ? ($quiz->name .' ('. get_date($quiz->start_quiz) .' - '. ($quiz->end_quiz ? get_date($quiz->end_quiz) : 'Vô thời hạn') .')') : '';
                $quizId = $quiz ? $quiz->id : '';
            }else if($status_course){
                $text_status = '';
                switch ($status_course) {
                    case 'unlearned':
                        $unlearned = (int) DashboardUnitQuiz::where('unit_id', $unit_user)->sum('unlearned');
                        $data = [$unlearned, 0, 0];
                        $text_status = 'Chưa thi';
                        break;
                    case 'completed':
                        $completed = (int) DashboardUnitQuiz::where('unit_id', $unit_user)->sum('completed');
                        $data = [0, $completed, 0];
                        $text_status = 'Hoàn thành';
                        break;
                    case 'uncompleted':
                        $uncompleted = (int) DashboardUnitQuiz::where('unit_id', $unit_user)->sum('uncompleted');
                        $data = [0, 0, $uncompleted];
                        $text_status = 'Chưa hoàn thành';
                        break;
                }

                $quizName = 'Trạng thái nhân viên các kỳ thi '. $text_status;
                $quizId = '';
            }else if($month_course && $year_course){
                $unlearned = (int) DashboardUnitQuiz::where('unit_id', $unit_user)
                    ->where(DB::raw('month(start_date)'), $month_course)
                    ->where(DB::raw('year(start_date)'), $year_course)
                    ->sum('unlearned');

                $completed = (int) DashboardUnitQuiz::where('unit_id', $unit_user)
                    ->where(DB::raw('month(start_date)'), $month_course)
                    ->where(DB::raw('year(start_date)'), $year_course)
                    ->sum('completed');

                $uncompleted = (int) DashboardUnitQuiz::where('unit_id', $unit_user)
                    ->where(DB::raw('month(start_date)'), $month_course)
                    ->where(DB::raw('year(start_date)'), $year_course)
                    ->sum('uncompleted');

                switch ($status_course) {
                    case 'unlearned':
                        $data = [$unlearned, 0, 0];
                        break;
                    case 'completed':
                        $data = [0, $completed, 0];
                        break;
                    case 'uncompleted':
                        $data = [0, 0, $uncompleted];
                        break;
                    default:
                        $data = [$unlearned, $completed, $uncompleted];
                        break;
                }

                $quizName = 'Trạng thái nhân viên các kỳ thi theo tháng '. $month_course;
                $quizId = '';
            }else{
                $unlearned = (int) DashboardUnitQuiz::where('unit_id', $unit_user)->sum('unlearned');
                $completed = (int) DashboardUnitQuiz::where('unit_id', $unit_user)->sum('completed');
                $uncompleted = (int) DashboardUnitQuiz::where('unit_id', $unit_user)->sum('uncompleted');

                $data = [$unlearned, $completed, $uncompleted];
                $quizName = 'Trạng thái nhân viên các Kỳ thi';
                $quizId = '';
            }
        }

        return [
            $data,
            $quizName,
            $quizId
        ];
    }

    public function dataUserOnline(Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 50);

        $model_id = $request->model_id;
        $year_course = $request->year_course;
        $month_course = $request->month_course;
        $status_course = $request->status_course;
        $filter_name = $request->filter_name;

        if(is_null(session('user_unit'))){
            $unit_manager = UnitManager::where('user_id', profile()->user_id)->first();
            $unit_user = @$unit_manager->unit_id;
            $unit_code_user = @$unit_manager->unit_code;
        }else{
            $unit = Unit::find(session('user_unit'));
            $unit_user = $unit->id;
            $unit_code_user = $unit->code;
        }
        $child_unit_arr = Unit::getArrayChild($unit_code_user);

        $query = DB::table('el_online_register');
        $query->select([
            'el_online_register.id',
            'el_online_register.course_id',
            'profile.user_id',
            'profile.full_name',
            'profile.code',
            'profile.unit_name',
            'course.name',
            'course.start_date',
        ]);
        $query->leftJoin('el_online_course as course', 'course.id', '=', 'el_online_register.course_id');
        $query->leftJoin('el_profile_view as profile', 'profile.user_id', '=', 'el_online_register.user_id');
        $query->where('el_online_register.status', 1);
        $query->where('course.status', 1);
        $query->where('course.isopen', 1);
        $query->where('course.offline', 0);
        $query->where(function($sub) use($unit_user, $child_unit_arr){
            $sub->orWhere('profile.unit_id', $unit_user);
            $sub->orWhereIn('profile.unit_id', $child_unit_arr);
        });

        if($filter_name){
            if($filter_name == 'model_new'){
                $models = DashboardUnitOnlineCourse::where('unit_id', $unit_user)->orderByDesc('start_date')->first();
                $query->where('course_id', '=', $models->course_id);
            }
            if($filter_name == 'week_now'){
                $now = Carbon::now();
                $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i:s');
                $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i:s');

                $query->where('start_date', '>=', $weekStartDate);
                $query->where('start_date', '<=', $weekEndDate);
            }
            if($filter_name == 'month_now'){
                $month_now = date('m');
                $year_now = date('Y');

                $query->where(DB::raw('month(start_date)'), $month_now);
                $query->where(DB::raw('year(start_date)'), $year_now);
            }
            if($filter_name == 'year_now'){
                $year_now = date('Y');

                $query->where(DB::raw('year(start_date)'), $year_now);
            }
        }else{
            if($model_id){
                $query->where('el_online_register.course_id', $model_id);
            }else if($month_course && $year_course){
                $query->where(DB::raw('month(start_date)'), $month_course);
                $query->where(DB::raw('year(start_date)'), $year_course);
            }
            if($status_course){
                if($status_course == 'studying'){
                    $query->where(
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
                    );
                    $query->whereNotExists(function($sub) {
                        $sub->select(DB::raw(1))
                            ->from('el_online_course_complete')
                            ->whereColumn('course_id', '=', 'el_online_register.course_id')
                            ->whereColumn('user_id', '=', 'el_online_register.user_id');
                    });
                }
                if($status_course == 'unlearned'){
                    $query->whereNull('el_online_register.cron_complete');
                }
                if($status_course == 'completed'){
                    $query->whereExists(function($sub) {
                        $sub->select(DB::raw(1))
                            ->from('el_online_course_complete')
                            ->whereColumn('course_id', '=', 'el_online_register.course_id')
                            ->whereColumn('user_id', '=', 'el_online_register.user_id');
                    });
                }
                if($status_course == 'uncompleted'){
                    $query->whereNotNull('cron_complete');
                    $query->where(
                        function($sub){
                            $sub->select(\DB::raw('COUNT(a.id)'))
                                ->from('el_online_course_activity as a')
                                ->join('el_online_course_condition as b', 'b.course_id', '=', 'a.course_id')
                                ->whereRaw('FIND_IN_SET(a.id,b.activity)')
                                ->whereColumn('a.course_id', '=', 'el_online_register.course_id')
                                ->groupBy('a.course_id');
                        }, '<=', function($sub2){
                            $sub2->select(\DB::raw('COUNT(activity_id)'))
                            ->from('el_online_course_activity_completion')
                            ->whereColumn('course_id', '=', 'el_online_register.course_id')
                            ->whereColumn('user_id', '=', 'el_online_register.user_id')
                            ->where('status', 1);
                        }
                    );
                    $query->whereNotExists(function($sub) {
                        $sub->select(DB::raw(1))
                            ->from('el_online_course_complete')
                            ->whereColumn('course_id', '=', 'el_online_register.course_id')
                            ->whereColumn('user_id', '=', 'el_online_register.user_id');
                    });
                }
            }
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            //$course = OnlineCourse::find($row->course_id, ['id', 'code', 'name', 'start_date', 'end_date']);
            $row->model_info = $row->name ? ('Khoá: ' . $row->name .' ('. get_date($row->start_date) .')') : '';

            $condition = OnlineCourseCondition::where('course_id', '=', $row->course_id)->first();
            $activity_condition = ($condition && $condition->activity) ? explode(',', $condition->activity) : [];
            $activity_complete = OnlineCourseActivityCompletion::whereCourseId($row->course_id)->where('user_id', $row->user_id)->where('status', 1)->count();

            $bg_color = '';
            $percent = '';

            $studying = OnlineRegister::whereStatus(1)
                ->where('user_id', $row->user_id)
                ->where('course_id', $row->course_id)
                ->whereNotNull('cron_complete')
                ->where(\DB::raw(1), '=', function($sub) use($activity_condition){
                    $sub->select(\DB::raw('CASE WHEN COUNT(activity_id) < '.count($activity_condition).' THEN 1 ELSE 0 END'))
                        ->from('el_online_course_activity_completion')
                        ->whereColumn('course_id', '=', 'el_online_register.course_id')
                        ->whereColumn('user_id', '=', 'el_online_register.user_id')
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

    public function dataUserOffline(Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 50);

        $model_id = $request->model_id;
        $year_course = $request->year_course;
        $month_course = $request->month_course;
        $status_course = $request->status_course;
        $filter_name = $request->filter_name;

        if(is_null(session('user_unit'))){
            $unit_manager = UnitManager::where('user_id', profile()->user_id)->first();
            $unit_user = @$unit_manager->unit_id;
            $unit_code_user = @$unit_manager->unit_code;
        }else{
            $unit = Unit::find(session('user_unit'));
            $unit_user = $unit->id;
            $unit_code_user = $unit->code;
        }
        $child_unit_arr = Unit::getArrayChild($unit_code_user);

        $query = DB::table('el_offline_register');
        $query->select([
            'el_offline_register.id',
            'el_offline_register.course_id',
            'el_offline_register.class_id',
            'profile.user_id',
            'profile.full_name',
            'profile.code',
            'profile.unit_name',
            'course.name',
            'course.start_date',
        ]);
        $query->leftJoin('el_offline_course as course', 'course.id', '=', 'el_offline_register.course_id');
        $query->leftJoin('el_profile_view as profile', 'profile.user_id', '=', 'el_offline_register.user_id');
        $query->where('el_offline_register.status', 1);
        $query->where('course.status', 1);
        $query->where('course.isopen', 1);
        $query->where(function($sub) use($unit_user, $child_unit_arr){
            $sub->orWhere('profile.unit_id', $unit_user);
            $sub->orWhereIn('profile.unit_id', $child_unit_arr);
        });

        if($filter_name){
            if($filter_name == 'model_new'){
                $models = DashboardUnitOfflineCourse::where('unit_id', $unit_user)->orderByDesc('start_date')->first();
                $query->where('course_id', '=', $models->course_id);
            }
            if($filter_name == 'week_now'){
                $now = Carbon::now();
                $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i:s');
                $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i:s');

                $query->where('start_date', '>=', $weekStartDate);
                $query->where('start_date', '<=', $weekEndDate);
            }
            if($filter_name == 'month_now'){
                $month_now = date('m');
                $year_now = date('Y');

                $query->where(DB::raw('month(start_date)'), $month_now);
                $query->where(DB::raw('year(start_date)'), $year_now);
            }
            if($filter_name == 'year_now'){
                $year_now = date('Y');

                $query->where(DB::raw('year(start_date)'), $year_now);
            }
        }else{
            if($model_id){
                $query->where('el_offline_register.course_id', $model_id);
            }else if($month_course && $year_course){
                $query->where(DB::raw('month(start_date)'), $month_course);
                $query->where(DB::raw('year(start_date)'), $year_course);
            }
            if($status_course){
                if($status_course == 'studying'){
                    $query->whereExists(function($sub) {
                        $sub->select(DB::raw(1))
                            ->from('el_offline_attendance')
                            ->whereColumn('course_id', '=', 'el_offline_register.course_id')
                            ->whereColumn('user_id', '=', 'el_offline_register.user_id')
                            ->where('status', 1);
                    });
                    $query->whereNotExists(function($sub) {
                        $sub->select(DB::raw(1))
                            ->from('el_offline_course_complete')
                            ->whereColumn('course_id', '=', 'el_offline_register.course_id')
                            ->whereColumn('user_id', '=', 'el_offline_register.user_id');
                    });
                }
                if($status_course == 'unlearned'){
                    $query->whereNull('el_offline_register.cron_complete');
                }
                if($status_course == 'completed'){
                    $query->whereExists(function($sub) {
                        $sub->select(DB::raw(1))
                            ->from('el_offline_course_complete')
                            ->whereColumn('course_id', '=', 'el_offline_register.course_id')
                            ->whereColumn('user_id', '=', 'el_offline_register.user_id');
                    });
                }
                if($status_course == 'uncompleted'){
                    $query->whereNotNull('cron_complete');
                    $query->where(
                        function($sub) {
                            $sub->select(\DB::raw('COUNT(schedule_id)'))
                                ->from('el_offline_attendance')
                                ->whereColumn('course_id', '=', 'el_offline_register.course_id')
                                ->whereColumn('user_id', '=', 'el_offline_register.user_id')
                                ->where('status', 1);
                        }, '>=', function($sub) {
                            $sub->select(\DB::raw('COUNT(id)'))
                                ->from('el_offline_schedule')
                                ->whereColumn('course_id', '=', 'el_offline_register.course_id')
                                ->whereColumn('class_id', '=', 'el_offline_register.class_id');
                        }
                    );
                    $query->whereNotExists(function($sub) {
                        $sub->select(\DB::raw(1))
                            ->from('el_offline_course_complete')
                            ->whereColumn('course_id', '=', 'el_offline_register.course_id')
                            ->whereColumn('user_id', '=', 'el_offline_register.user_id');
                    });
                }
            }
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            //$course = OfflineCourse::find($row->course_id, ['id', 'code', 'name', 'start_date', 'end_date']);
            $row->model_info = $row->name ? ('Khoá: ' . $row->name .' ('. get_date($row->start_date) .')') : '';

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

    public function dataUserQuiz(Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 50);

        $model_id = $request->model_id;
        $year_course = $request->year_course;
        $month_course = $request->month_course;
        $status_course = $request->status_course;
        $filter_name = $request->filter_name;

        if(is_null(session('user_unit'))){
            $unit_manager = UnitManager::where('user_id', profile()->user_id)->first();
            $unit_user = @$unit_manager->unit_id;
            $unit_code_user = @$unit_manager->unit_code;
        }else{
            $unit = Unit::find(session('user_unit'));
            $unit_user = $unit->id;
            $unit_code_user = $unit->code;
        }
        $child_unit_arr = Unit::getArrayChild($unit_code_user);

        $query = DB::table('el_quiz_register');
        $query->select([
            'el_quiz_register.id',
            'el_quiz_register.quiz_id',
            'profile.user_id',
            'profile.full_name',
            'profile.code',
            'profile.unit_name',
            'quiz.name',
            'quiz.start_quiz',
        ]);
        $query->leftJoin('el_quiz as quiz', 'quiz.id', '=', 'el_quiz_register.quiz_id');
        $query->leftJoin('el_profile_view as profile', 'profile.user_id', '=', 'el_quiz_register.user_id');
        $query->where('quiz.status', 1);
        $query->where('quiz.is_open', 1);
        $query->where(function($sub) use($unit_user, $child_unit_arr){
            $sub->orWhere('profile.unit_id', $unit_user);
            $sub->orWhereIn('profile.unit_id', $child_unit_arr);
        });

        if($filter_name){
            if($filter_name == 'model_new'){
                $models = DashboardUnitQuiz::where('unit_id', $unit_user)->orderByDesc('start_date')->first();
                $query->where('quiz_id', '=', $models->quiz_id);
            }
            if($filter_name == 'week_now'){
                $now = Carbon::now();
                $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i:s');
                $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i:s');

                $query->where('start_quiz', '>=', $weekStartDate);
                $query->where('start_quiz', '<=', $weekEndDate);
            }
            if($filter_name == 'month_now'){
                $month_now = date('m');
                $year_now = date('Y');

                $query->where(DB::raw('month(start_quiz)'), $month_now);
                $query->where(DB::raw('year(start_quiz)'), $year_now);
            }
            if($filter_name == 'year_now'){
                $year_now = date('Y');

                $query->where(DB::raw('year(start_quiz)'), $year_now);
            }
        }else{
            if($model_id){
                $query->where('el_quiz_register.quiz_id', $model_id);
            }else if($month_course && $year_course){
                $query->where(DB::raw('month(start_quiz)'), $month_course);
                $query->where(DB::raw('year(start_quiz)'), $year_course);
            }
            if($status_course){
                if($status_course == 'unlearned'){
                    $query->whereNotExists(function($sub){
                        $sub->select(DB::raw(1))
                            ->from('el_quiz_attempts')
                            ->whereColumn('quiz_id', '=', 'el_quiz_register.quiz_id')
                            ->whereColumn('user_id', '=', 'el_quiz_register.user_id');
                    });
                }
                if($status_course == 'completed'){
                    $query->whereExists(function($sub){
                        $sub->select(DB::raw(1))
                            ->from('el_quiz_result')
                            ->whereColumn('quiz_id', '=', 'el_quiz_register.quiz_id')
                            ->whereColumn('user_id', '=', 'el_quiz_register.user_id')
                            ->where('result', 1);
                    });
                }
                if($status_course == 'uncompleted'){
                    $query->whereExists(function($sub){
                        $sub->select(DB::raw(1))
                            ->from('el_quiz_attempts')
                            ->whereColumn('quiz_id', '=', 'el_quiz_register.quiz_id')
                            ->whereColumn('user_id', '=', 'el_quiz_register.user_id');
                    });
                    $query->whereNotExists(function($sub){
                        $sub->select(DB::raw(1))
                            ->from('el_quiz_result')
                            ->whereColumn('quiz_id', '=', 'el_quiz_register.quiz_id')
                            ->whereColumn('user_id', '=', 'el_quiz_register.user_id')
                            ->where('result', 1);
                    });
                }
            }
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            //$quiz = Quiz::find($row->quiz_id, ['id', 'code', 'name', 'start_quiz', 'end_quiz']);
            $row->model_info = $row->name ? ('Kỳ thi: ' . $row->name .' ('. get_date($row->start_quiz) .')') : '';

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

    public function searchCourse(Request $request) {
        if(is_null(session('user_unit'))){
            $unit_manager = UnitManager::where('user_id', profile()->user_id)->first();
            $unit_user = @$unit_manager->unit_id;
        }else{
            $unit_user = session('user_unit');
        }

        if(count($request->all()) == 0) {
            $checkSearch = 0;
            $data = [];
        } else {
            $checkSearch = 1;
            $type = $request->type;

            if($type == 1) {
                $getData = $this->dataCourseOnline($unit_user, $request);
            } else if($type == 2){
                $getData = $this->dataCourseOffline($unit_user, $request);
            }else{
                $getData = $this->dataQuiz($unit_user, $request);
            }

            $data = $getData[0];
            $courseName = $getData[1];
        }

        json_result([
            'courseName' => $courseName,
            'data' => $data,
            'data_model' => implode(',', $data),
            'checkSearch' => $checkSearch
        ]);
    }

    public function syncData(){
        if(is_null(session('user_unit'))){
            $unit_manager = UnitManager::where('user_id', profile()->user_id)->first();
            $unit_user = @$unit_manager->unit_id;
        }else{
            $unit_user = session('user_unit');
        }

        \Artisan::call('dashboard_unit_online_course:update '.$unit_user);
        \Artisan::call('dashboard_unit_offline_course:update '.$unit_user);
        \Artisan::call('dashboard_unit_quiz:update '.$unit_user);

        \Artisan::call('dashboard_unit_count_online:update '.$unit_user);
        \Artisan::call('dashboard_unit_count_offline:update '.$unit_user);
        \Artisan::call('dashboard_unit_count_quiz:update '.$unit_user);

        json_message('Dữ liệu sẽ được cập nhật lại </br> Hãy refresh lại trang!');
    }
}
