<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineCourse;
use Modules\ReportNew\Entities\BC26;
use Modules\ReportNew\Entities\ReportNewExportBC11;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\TrainingTeacherStar;
use Modules\Offline\Entities\OfflineRegister;

class BC26Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $report = parent::reportList();
        $reportGroupList = $this->reportGroupList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'reportList' => $reportGroupList,
        ]);
    }

    public function getData(Request $request)
    {
        $from_date = $request->from_date;
        $check_teacher = $request->check_teacher;
        $to_date = $request->to_date;

        if($check_teacher != 1) {
            $user_id = $request->user_id;
        } else {
            $user_id = profile()->user_id;
        }
        
        if (!$from_date && !$to_date)
            json_result([]);

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC26::sql($from_date, $to_date, $user_id);
        $count = $query->count();
        $query->orderBy('user_code', 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $traning_location = TrainingLocation::where('id', $row->training_location_id)->first(['name']);
            $row->traning_location = @$traning_location->name;

            $row->schedule_start_time = get_date($row->schedule_start_time, 'H:i');
            $row->schedule_end_time = get_date($row->schedule_end_time, 'H:i');

            $course_time = '';
            $course_time_unit_text = '';

            if ($row->course_type == 2){
                $course = OfflineCourse::find($row->course_id);
                $course_time = $course->course_time;
                $course_time_unit = preg_replace("/[^a-z]/", '', $course->course_time_unit);

                switch ($course_time_unit){
                    case 'day': $course_time_unit_text = 'Ngày'; break;
                    case 'session': $course_time_unit_text = 'Buổi'; break;
                    case 'hour': $course_time_unit_text = 'Giờ'; break;
                }
            }
            $row->course_time = $course_time . ' ' . $course_time_unit_text;

            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);

            $query = OfflineRegister::query();
            $query->where('course_id', $row->course_id);
            $query->where('class_id', $row->class_id);
            $query->where('status', 1);
            $num_student = $query->count();

            $num_user_rating = TrainingTeacherStar::where('teacher_id', $row->training_teacher_id)
                ->where('course_id', $row->course_id)
                ->where('course_type', 2)
                ->where('class_id', $row->class_id)
                ->count();
            $num_star = TrainingTeacherStar::where('teacher_id', $row->training_teacher_id)
                ->where('course_id', $row->course_id)
                ->where('course_type', 2)
                ->where('class_id', $row->class_id)
                ->sum('num_star');

            $num_star = (int)$num_star > 0 ? round($num_star/$num_user_rating, 1) : 0;

            $row->cost = '';
            if($num_student >= 15 && $num_star >= 3.5 && $row->practical_teaching) {
                $row->cost = number_format($row->cost_teacher_main * $row->practical_teaching, 2);
            }
            
            // $row->cost = number_format($row->cost_lecturer + ($row->cost_tuteurs ? $row->cost_tuteurs : 0), 2);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
