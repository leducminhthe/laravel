<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\Subject;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitType;
use App\Models\Profile;
use Illuminate\Http\Request;
use Modules\Quiz\Entities\Question;
use Modules\ReportNew\Entities\BC15;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use App\Models\ProfileView;

class BC15Controller extends ReportNewController
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
        $status_id = $request->status_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $title_id = $request->title_id;
        
        if (!$title_id)
            json_result([]);

        $sort = $request->input('sort', 'user_id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC15::sql($title_id, $status_id, $from_date, $to_date);
        $count = $query->count();
        $query->orderBy('user_id', 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $profile = ProfileView::where('user_id', $row->user_id)->first(['firstname','title_id','status_id']);
            $roadmap = TrainingRoadmap::query();
            $roadmap->with('subject');
            $roadmap->from('el_trainingroadmap AS a');
            $roadmap->leftJoin('el_subject AS b', 'b.id', '=', 'a.subject_id');
            $roadmap->where('a.title_id', '=', $profile->title_id);
    
            $count_subject = $roadmap->count();    
            $roadmaps = $roadmap->get();
            foreach ($roadmaps as $row) {
                $subject = Subject::find($row->subject_id);
                if ($subject && $subject->isCompleted()) {
                    $count_subject_complete += 1;
                }
            }
            $progress = $count_subject != 0 ? round((int)$count_subject_complete * 100 / (int)$count_subject, 2) : 0;
            $row->progress_roadmap = $progress;

            $unit = Unit::whereCode($row->unit1_code)->first();
            $unit_type = UnitType::find(@$unit->type);

            $row->unit_type = @$unit_type->name;

            $subjects = json_decode($row->subject,true);
            foreach ($subjects as $index => $subject) {
                $row->{'subject'.$subject['code']}= $subject['type'];
            }
            $row->join_date = get_date($row->join_company);

            switch ($profile->status_id){
                case 0:
                    $status = trans('backend.inactivity'); break;
                case 1:
                    $status = trans('backend.doing'); break;
                case 2:
                    $status = trans('backend.probationary'); break;
                case 3:
                    $status = trans('backend.pause'); break;
            }

            $row->status = $status;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
