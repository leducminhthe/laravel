<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\RattingCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_decode;
use App\Models\Categories\Unit;
use Modules\ReportNew\Entities\BC32;

class BC32Controller extends ReportNewController
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
        $title_id = $request->title_id;
        $unit_id = $request->unit_id;

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        if($title_id || $unit_id) {
            $query = BC32::sql($title_id, $unit_id);
            $count = $query->count();
            $query->orderBy('unit_id', 'DESC');
            $query->offset($offset);
            $query->limit($limit);
            $rows = $query->get();
        } else {
            $rows = [];
        }

        foreach ($rows as $key => $row) {
            $row->sum = gmdate("H:i", $row->sum);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
