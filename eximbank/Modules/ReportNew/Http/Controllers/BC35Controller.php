<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\RattingCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_decode;
use Modules\ReportNew\Entities\BC35;
use App\Models\CourseView;
use App\Models\CourseRegisterView;
use App\Models\CourseComplete;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Online\Entities\OnlineCourseCost;

class BC35Controller extends ReportNewController
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
        $date = date('Y-m-d');

        $course_type = $request->course_type;
        $subject_id = $request->subject_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $status = $request->status;

        if (!$from_date && !$to_date)
            json_result([]);

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC35::sql($course_type, $subject_id, $from_date, $to_date, $status);
        $count = $query->count();
        $query->orderBy('a.'.$sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $countRegister = CourseRegisterView::where(['course_id' => $row->course_id, 'course_type' => $row->course_type])->count();
            $countComplete = CourseComplete::where(['course_id' => $row->course_id, 'course_type' => $row->course_type])->count();
            $row->total_register = $countRegister;
            $row->total_complete = $countComplete;

            $row->rate_complete = round($countRegister > 0 ? ($countComplete/$countRegister)*100 : 0, 2) . '%';

            if($row->course_type == 1) {
                $row->name_course_type = 'Trực tuyến';
                $costCourse = OnlineCourseCost::where('course_id', $row->course_id)->sum('actual_amount');
            } else {
                $row->name_course_type = 'Offline';
                $costCourse = OfflineCourseCost::where('course_id', $row->course_id)->sum('actual_amount');
            }
            $row->actual_amount = number_format($costCourse, 2);

            if ($row->status == 0){
                $row->status_name = 'Chưa duyệt';
            } else if($row->status == 1 && ($row->end_date >= $date || empty($row->end_date)) && $row->lock_course == 0) {
                $row->status_name = 'Đã duyệt';
            } else if($row->status == 2) {
                $row->status_name = 'Từ chối';
            } else if(($row->start_date <= $date && $row->end_date >= $date) || ($row->start_date <= $date && empty($row->end_date))) {
                $row->status_name = 'Đang diễn ra';
            } else if($row->lock_course == 0 && $row->end_date <= $date) {
                $row->status_name = 'Chờ kiểm tra';
            } else if($row->lock_course == 1 && $row->end_date <= $date) {
                $row->status_name = 'Đã kết thúc';
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
