<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\Subject;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Modules\ReportNew\Entities\BC21;

class BC21Controller extends ReportNewController
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

        $sort = $request->input('sort', 'user_id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC21::sql();
        $count = $query->count();
        $query->orderBy($sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
//        $subjects = TrainingRoadmap::where(['title_id'=>$title_id])->get('id');
        foreach ($rows as $row){
            $row->start_date_format = get_date($row->start_date);
            $row->end_date_format = get_date($row->end_date);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
