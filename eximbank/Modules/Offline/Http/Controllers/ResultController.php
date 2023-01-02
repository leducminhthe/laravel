<?php

namespace Modules\Offline\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Offline\Entities\OfflineCondition;
use Modules\Offline\Entities\OfflineCourseClass;
use Modules\Offline\Entities\OfflineCourseComplete;
use Modules\Offline\Entities\OfflineCourseActivityCondition;
use Modules\Offline\Entities\OfflineCourseActivityCompletion;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Imports\ResultImport;
use Modules\Offline\Exports\ResultExport;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionMethodSetting;
use Modules\Promotion\Entities\PromotionUserHistory;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Rating\Entities\RatingCourse;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use Modules\Offline\Entities\OfflineCourseActivity;
use Modules\Online\Entities\OnlineResult;
use Modules\Quiz\Entities\QuizResult;
use Modules\Rating\Entities\RatingLevelCourse;
use App\Models\Permission;
use App\Models\UserRole;
use Modules\Offline\Entities\OfflineInviteRegister;
use Modules\Offline\Entities\OfflineTeachingOrganizationUser;

class ResultController extends Controller
{
    public function index($course_id, $class_id, Request $request) {
        $class = OfflineCourseClass::find($class_id);
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $course = OfflineCourse::find($course_id);
        $page_title = $course->name;

        $errors = session()->get('errors');
        \Session::forget('errors');

        $activities_online = OfflineCourseActivity::where('course_id', '=', $course_id)->where('class_id', $class_id)->exists();
        $anotherClass = OfflineCourseClass::where(['course_id'=>$course_id])->where('id','<>',$class_id)->get();
        $classArray = [];
        foreach ($anotherClass as $item) {
            $classArray[]=["name"=>$item->name,"url"=> route("module.offline.result",['id'=>$course_id,'class_id'=>$item->id])];
        }

        $user_invited = false;
        $check_user_invited = OfflineInviteRegister::query()
            ->where('course_id', '=', $course_id)
            ->where('user_id', '=', \Auth::id());
        if ($check_user_invited->exists()) {
            $user_invited = true;
        }

        return view('offline::backend.result.index', [
            'page_title' => $page_title,
            'course' => $course,
            'level_name'=>$level_name,
            'class'=>$class,
            'classArray'=>$classArray,
            'activities_online' => $activities_online,
            'user_invited' => $user_invited,
        ]);
    }

    public function getData($course_id, $class_id, Request $request) {
        $search = $request->input('search');
        $status = $request->input('status');
        $unit = $request->unit_id;
        $title = $request->input('title');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $offline = OfflineCourse::find($course_id);

        $query = OfflineRegister::query();
        $query->select([
            'a.*',
            'b.lastname',
            'b.firstname',
            'b.email',
            'b.code',
            'b.status as profile_status',
            'b.title_code as profile_title',
            'c.id AS unit_id',
        ]);
        $query->from('el_offline_register AS a');
        $query->join('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftjoin('el_unit as c','c.code','=','b.unit_code');
        $query->leftJoin('el_area AS area', 'area.id', '=', 'c.area_id');
        $query->where('a.course_id', '=', $course_id);
        $query->where('a.class_id', $class_id);
        $query->where('a.status', '=', 1);
        $query->where('a.user_id', '>', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('b.lastname', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.firstname', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
            });
        }

        if (!is_null($status)) {
            $query->where('b.status', '=', $status);
        }

        if ($start_date) {
            $query->where('a.updated_at', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('a.updated_at', '<=', date_convert($end_date, '23:59:59'));
        }

        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);
            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('b.code', $unit_id);
                $sub_query->orWhere('c.id', '=', $unit->id);
            });
        }
        if ($request->area) {
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($title) {
            $title = Titles::where('id', '=', $title)->first();
            $query->where('b.title_code', '=', $title->code);
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        $offline_course_activity_condition = OfflineCourseActivityCondition::where('course_id', $course_id)
        ->where('class_id', $class_id)
        ->pluck('course_activity_id')
        ->toArray();

        foreach($rows as $row){
            $result = OfflineResult::where('register_id', '=', $row->id)->first();
            $rating = OfflineTeachingOrganizationUser::where('course_id', $course_id)->where('user_id', $row->user_id)->where('send', 1)->exists() ? 1 : 0;

            $percent = OfflineAttendance::where('register_id', '=', $row->id)->sum('percent');
            $schedule = OfflineSchedule::where('course_id', '=', $course_id)->where('class_id', $class_id)->where('type_study', '!=', 3)->count();

            $row->rating_send = $rating;

            $row->score_1 = $result && $result->score_1 ? number_format($result->score_1, 2) : '';
            $row->score_2 = $result && $result->score_2 ? number_format($result->score_2, 2) : '';
            $row->score = $result && $result->score ? number_format($result->score, 2) : '';

            if($result) {
                $row->result_id = $result->id;
                $row->note = $result->note;
                $row->result = $result->result;
            }
            $row->percent = $percent / ($schedule > 0 ? $schedule : 1) . ' %';

            if($offline->entrance_quiz_id){
                $entrance_quiz_result = QuizResult::whereQuizId($offline->entrance_quiz_id)->whereNull('text_quiz')->where('user_id', $row->user_id)->where('type', 1)->first();
                if($entrance_quiz_result){
                    $row->entrance_quiz = isset($entrance_quiz_result->reexamine) ? $entrance_quiz_result->reexamine : (isset($entrance_quiz_result->grade) ? $entrance_quiz_result->grade : 0);
                }
            }

            $offline_course_activity_complete = OfflineCourseActivityCompletion::where('course_id', $course_id)
                ->where('user_id', $row->user_id)
                ->where('status', 1)
                ->whereIn('activity_id', $offline_course_activity_condition)
                ->count();

            $row->complete_elearning = false;
            if($offline_course_activity_complete != 0 && $offline_course_activity_complete >= count($offline_course_activity_condition)){
                $row->complete_elearning = true;
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveScore($course_id, Request $request) {
        $this->validateRequest([
            'regid' => 'required|exists:el_offline_register,id',
            'score' => 'required|min:0',
        ], $request);

        $register_id = $request->input('regid');
        $score = $request->input('score');

        $condition = OfflineCondition::where('course_id', '=', $course_id)->first();

        if (empty($condition)){
            json_message('Chưa xét điều kiện hoàn thành', 'error');
        }

        $register = OfflineRegister::find($register_id);
        $model = OfflineResult::firstOrNew(['register_id' => $register_id]);
        $model->register_id = $register_id;
        $model->user_id = $register->user_id;
        $model->course_id = $register->course_id;
        $model->score_2 = $score;
        $model->score = $score;
        $model->pass_score = $condition->minscore;
        $model->percent = OfflineResult::getPercent($register_id);
        $model->save();

        $model->updateResult();
        if ($model->result == 1){
            OfflineCourseComplete::updateOrCreate([
                'user_id' => $register->user_id,
                'course_id' => $register->course_id,
            ],[
                'user_id' => $register->user_id,
                'course_id' => $register->course_id,
                'time_complete' => date('Y-m-d H:i:s'),
            ]);

            \Artisan::call('command:offline_complete '. $register->user_id .' '. $register->course_id);
        }
        event(new \App\Events\SaveOfflineScore($model));
        json_message('ok');
    }

    public function saveNote($course_id, Request $request) {
        $this->validateRequest([
            'note' => 'nullable',
            'regid' => 'required',
        ], $request);

        $note = $request->input('note');
        $register_id = $request->input('regid');
        $register = OfflineRegister::findOrFail($register_id);

        $model = OfflineResult::firstOrNew(['register_id' => $register_id]);
        $model->register_id = $register_id;
        $model->course_id = $course_id;
        $model->user_id = $register->user_id;
        $model->note = $note;
        $model->save();

        json_message('ok');
    }

    public function importResult($course_id, $class_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $is_unit = $request->input('unit');

        $user_role = '';
        if(!Permission::isAdmin()){
            $userUnit = session()->get('user_unit');
            $user_role = UserRole::query()
                ->select(['c.unit_id', 'c.type', 'd.code', 'd.name'])->disableCache()
                ->from('el_user_role as a')
                ->join('el_role_has_permission_type as b', 'b.role_id', '=', 'a.role_id')
                ->join('el_permission_type_unit as c', 'c.permission_type_id', '=', 'b.permission_type_id')
                ->join('el_unit as d', 'd.id', '=', 'c.unit_id')
                ->where('a.user_id', '=', profile()->user_id)
                ->where('c.unit_id', '=', $userUnit)
                ->first();
        }

        $import = new ResultImport($course_id, $class_id, $user_role);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        $redirect = $is_unit > 0 ? route('module.training_unit.offline.result', ['id' => $course_id]) : route('module.offline.result', ['id' => $course_id, 'class_id' => $class_id]);

        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => $redirect
        ]);
    }

    public function exportResult($course_id, $class_id)
    {
        ob_end_clean();
        ob_start();
        return (new ResultExport($course_id, $class_id))->download('ket_qua_dao_tao_'. date('d_m_Y') .'.xlsx');
    }
}
