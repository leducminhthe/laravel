<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\TeacherType;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\TrainingTeacherHistory;
use App\Models\Categories\TrainingType;
use App\Models\Profile;
use App\Models\ProfileView;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineTeacher;
use Modules\ReportNew\Entities\BC16;

class BC16Controller extends ReportNewController
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
        $type = $request->type;
        // if (!$type)
        //     json_result([]);

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC16::sql($type);
        $count = $query->count();
        $query->orderBy($sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $profile_view = ProfileView::find($row->user_id);
            $partner = TrainingPartner::find($row->training_partner_id);
            $teacher_type = TeacherType::find($row->teacher_type_id);

            $row->title = $profile_view ? $profile_view->title_name : '';
            $row->teacher_type = $teacher_type ? $teacher_type->name : '';
            $row->created_time = get_date($row->created_at);
            $row->total_hour = TrainingTeacherHistory::where('teacher_id', $row->id)->sum('num_hour');
            $row->rank = $this->getRank($row->id);
            $row->num_course = OfflineTeacher::whereTeacherId($row->id)->count();
            $row->partner = $partner ? $partner->name : '';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    private function getRank($teacher_id){
        $rank = [];
        $training_teacher = TrainingTeacher::whereStatus(1)->pluck('id')->toArray();
        foreach($training_teacher as $teacher){
            $history = TrainingTeacherHistory::where('teacher_id', $teacher)->sum('num_hour');

            $rank[$teacher] = $history;
        }
        arsort($rank);
        $i = 0;
        foreach($rank as $key => $value){
            $i += 1;
            $rank[$key] = $i;
        }

        return $rank[$teacher_id];
    }
}
