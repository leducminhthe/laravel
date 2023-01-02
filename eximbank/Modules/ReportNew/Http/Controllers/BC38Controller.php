<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\RattingCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_decode;
use Modules\ReportNew\Entities\BC38;
use App\Models\CourseView;
use App\Models\CourseRegisterView;
use App\Models\CourseComplete;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Online\Entities\OnlineCourseCost;

class BC38Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $report = parent::reportList();
        $reportGroupList = $this->reportGroupList();
        $allCourse = CourseView::where(['status' => 1, 'isopen' => 1])->get(['course_id', 'course_type', 'name', 'code', 'start_date', 'end_date']);
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'reportList' => $reportGroupList,
            'allCourse' => $allCourse
        ]);
    }

    public function getData(Request $request)
    {
        $date = date('Y-m-d');

        $unit_id = $request->unit_id;
        $title_id = $request->title_id;

        if ($request->isSubmit != 1)
            json_result([]);

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC38::sql($unit_id, $title_id);
        $count = $query->count();
        $query->orderBy('profile.'.$sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $model = CourseRegisterView::query();
            $model->select([
                'register.cron_complete',
                'register.course_id',
                'register.course_type',
                'online.score as online_score',
                'online.result as online_result',
                'offline.score as offline_score',
                'offline.result as offline_result',
            ]);
            $model->from('el_course_register_view as register');
            $model->leftJoin('el_online_result as online', function($join) {
                $join->on('register.course_id', '=', 'online.course_id');
                $join->on('register.register_id', '=', 'online.register_id');
                $join->where('register.course_type', '=', 1);
            });
            $model->leftJoin('el_offline_result as offline', function($join2) {
                $join2->on('register.course_id', '=', 'offline.course_id');
                $join2->on('register.register_id', '=', 'offline.register_id');
                $join2->where('register.course_type', '=', 2);
            });
            $model->where('register.user_id', $row->user_id);
            $infoUser = $model->get();
            foreach ($infoUser as $key => $value) {
                $row->{"register_". $value->course_id . "_" . $value->course_type} = 'x';
                if($value->course_type == 1) {
                    $row->{"score_". $value->course_id . "_" . $value->course_type} = $value->online_score ? $value->online_score : '-';
                    if($value->online_result == 0) {
                        $result = 'Không đạt';
                    } else if ($value->online_result == 1) {
                        $result = 'Đạt';
                    } else {
                        $result = 'Đang học';
                    }
                    $row->{"result_". $value->course_id . "_" . $value->course_type} = $result;
                } else {
                    $row->{"score_". $value->course_id . "_" . $value->course_type} = $value->offline_score ? $value->offline_score : '-';
                    if($value->offline_result == 0) {
                        $result = 'Không đạt';
                    } else if ($value->offline_result == 1) {
                        $result = 'Đạt';
                    } else {
                        $result = 'Đang học';
                    }
                    $row->{"result_". $value->course_id . "_" . $value->course_type} = $result;
                }
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
