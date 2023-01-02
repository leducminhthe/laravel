<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\RattingCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ReportNew\Entities\BC30;
use function GuzzleHttp\json_decode;

class BC30Controller extends ReportNewController
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
        $subject_id = $request->subject_id;
        $course_type = $request->course_type;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        if (!$course_type && !$subject_id)
            json_result([]);

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC30::sql($course_type, $subject_id, $from_date, $to_date);
        $count = $query->count();
        $query->orderBy('id', 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
