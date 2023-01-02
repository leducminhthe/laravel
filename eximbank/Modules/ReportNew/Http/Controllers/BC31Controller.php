<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\RattingCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_decode;
use App\Models\Categories\Unit;
use Modules\ReportNew\Entities\BC31;

class BC31Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        $report = parent::reportList();
        $reportGroupList = $this->reportGroupList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'level_name' => $level_name,
            'reportList' => $reportGroupList,
        ]);
    }

    public function getData(Request $request)
    {
        $year = $request->year;
        $title_id = $request->title_id;
        $unit_id = $request->unit_id;
        $user_id = $request->user_id;
        $show = $request->show;

        if (!$show) {
            json_result([]);
        }
            
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC31::sql($title_id, $unit_id, $user_id, $year);
        $count = $query->count();
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
