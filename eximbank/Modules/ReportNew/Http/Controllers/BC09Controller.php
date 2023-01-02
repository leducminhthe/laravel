<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\ReportNew\Entities\BC09;

class BC09Controller extends ReportNewController
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
        $training_area_id = $request->area_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $training_type_id = $request->training_type_id;
        $title_id = $request->title_id;
        $unit_id = $request->unit_id;

        if (!$from_date && !$to_date)
            json_result([]);

        if(date_convert($from_date) > date_convert($to_date)){
            json_message('Ngày kết thúc phải sau Ngày bắt đầu', 'error');
        }

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC09::sql($training_area_id, $from_date, $to_date, $training_type_id, $title_id, $unit_id);
        $count = $query->count();
        $query->orderBy('el_report_new_export_bc05.'.$sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row){
            $offline = OfflineCourse::find($row->course_id);
            $row->course_time = @$offline->course_time;

            $registers = OfflineRegister::where(['course_id' => $row->course_id, 'user_id' => $row->user_id])->pluck('class_id')->toArray();

            $schedules = OfflineSchedule::query()
                ->select(['a.end_time', 'a.lesson_date'])
                ->from('el_offline_schedule as a')
                ->where('a.course_id', '=', $row->course_id)
                ->whereIn('a.class_id', $registers)
                ->get();
            foreach ($schedules as $schedule){
                if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                    $row->time_schedule .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
                }else{
                    $row->time_schedule .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
                }
            }
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);

            $row->result = $row->result == 1 ? 'Đạt' : 'Không đạt';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
