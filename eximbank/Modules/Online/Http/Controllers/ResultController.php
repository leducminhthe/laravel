<?php

namespace Modules\Online\Http\Controllers;

use App\Models\ProfileView;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\ModelHistory\Entities\ModelHistory;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityCompletion;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineCourseActivityXapi;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use Modules\Rating\Entities\RatingCourse;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\Models\Categories\Titles;
use Modules\Online\Exports\ExportResultOnline;

class ResultController extends Controller
{
    public function index($course_id, Request $request) {
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $course = OnlineCourse::find($course_id);
        $page_title = $course->name;

        $activities = OnlineCourseActivity::getByCourse($course_id);

        return view('online::backend.result.index', [
            'page_title' => $page_title,
            'course' => $course,
            'activities' => $activities,
            'max_unit'=>$max_unit,
            'level_name'=>$level_name,
        ]);
    }

    public function getData($course_id, Request $request) {
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

        $query = OnlineRegister::query();
        $query->select([
            'a.*',
            'b.full_name',
            'b.code',
            'b.email',
        ]);
        $query->from('el_online_register AS a');
        $query->leftjoin('el_profile_view AS b', 'b.user_id', '=', 'a.user_id');
        $query->where('a.course_id', '=', $course_id);
        $query->where('a.status', '=', 1);
        $query->where('a.user_id', '>', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('b.full_name', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
            });
        }

        if ($start_date) {
            $query->where('a.updated_at', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('a.updated_at', '<=', date_convert($end_date, '23:59:59'));
        }

        if (!is_null($status)) {
            $query->where('a.status', '=', $status);
        }
        if ($request->area) {
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('b.area_id', $area_id);
                $sub_query->orWhere('b.area_id', '=', $area->id);
            });
        }
        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('b.unit_id', $unit_id);
                $sub_query->orWhere('b.unit_id', '=', $unit->id);
            });
        }

        if ($title) {
            $query->where('b.title_id', '=', $title);
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach($rows as $row){
            $row->user_type = 1;
            $row->name = $row->full_name;

            $activities = OnlineCourseActivity::getByCourse($course_id);
            foreach ($activities as $activity){
                $check_complete = $activity->isComplete($row->user_id, $row->user_type);

                $row->{'activity_'. $activity->id} = ($check_complete ? trans("backend.finish") : '<input type="checkbox" class="check-complete" data-activity_id="'. $activity->id .'" data-user_id="'. $row->user_id .'" data-user_type="'. $row->user_type .'">' . ' '. trans("backend.incomplete"));

                if ($activity->activity_id == 1) {
                    $activity_scorm = OnlineCourseActivityScorm::find($activity->subject_id);
                    $score = $activity_scorm->getScoreScorm($row->user_id,  $row->user_type);
                    $row->{'score_'. $activity->id} = ($score ? number_format($score, 2) : '');
                }elseif($activity->activity_id == 7){
                    $activity_xapi = OnlineCourseActivityXapi::find($activity->subject_id);
                    $score = $activity_xapi->getScoreXapi($row->user_id,  $row->user_type);
                    $row->{'score_'. $activity->id} = ($score ? number_format($score, 2) : '');
                }
            }

            $row->view_history_learning = '';
            if ($activities->count() > 0){
                $row->view_history_learning = route('module.online.result.view_history_learning', ['id' => $course_id, 'user_id' => $row->user_id]);
            }

            $result = OnlineResult::where('user_id', '=', $row->user_id)
                ->where('user_type', '=', $row->user_type)
                ->where('course_id', '=', $course_id)
                ->first();

            $row->score = ($result && $result->score > 0) ? number_format($result->score, 2) : '';
            $row->result = $result ? ($result->result == 1 ? trans("backend.achieved") : trans("backend.not_achieved")) : '';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function updateActivityComplete($course_id, Request $request){
        $user_id = $request->user_id;
        $user_type = $request->user_type;
        $activity_id = $request->activity_id;

        $completion = OnlineCourseActivityCompletion::firstOrNew([
            'user_id' => $user_id,
            'course_id'=>$course_id,
            'activity_id' => $activity_id,
        ]);
        $completion->course_id = $course_id;
        $completion->status = 1;
        $completion->save();

        json_message('Cập nhật thành công');
    }

    public function exportResult($course_id) {
        return (new ExportResultOnline($course_id))->download('danh_sach_ket_qua_'. date('d_m_Y') .'.xlsx');
    }

    public function viewHistoryLearning($course_id, $user_id, Request $request){
        $course = OnlineCourse::find($course_id);
        $page_title = $course->name;

        $profile = ProfileView::whereUserId($user_id)->first();
        $get_activity_courses = OnlineCourseActivity::where('course_id',$course_id)->get();

        return view('online::backend.result.view_history_learning', [
            'page_title' => $page_title,
            'course' => $course,
            'profile' => $profile,
            'get_activity_courses' => $get_activity_courses
        ]);
    }
}
