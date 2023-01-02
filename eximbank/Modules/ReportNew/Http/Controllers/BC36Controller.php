<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\CourseView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_decode;
use Modules\ReportNew\Entities\BC36;
use Modules\TrainingByTitle\Entities\TrainingByTitleCategory;
use App\Models\Categories\Subject;
use Modules\TrainingByTitle\Entities\TrainingByTitleDetail;
use Modules\User\Entities\UserCompletedSubject;

class BC36Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $subjects = Subject::where('status', 1)->get();
        $report = parent::reportList();
        $reportGroupList = $this->reportGroupList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'reportList' => $reportGroupList,
            'subjects' => $subjects
        ]);
    }

    public function getData(Request $request)
    {
        $users = $request->users;
        $title = $request->title;
        $training_title_category = $request->training_title_category;

        if (!$title && !$training_title_category)
            json_result([]);

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC36::sql($training_title_category, $users, $title);
        $count = $query->count();
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $key => $row) {
            $subject_training_detail = TrainingByTitleDetail::where('training_title_category_id', $row->training_category_id)->get('subject_id');
            foreach($subject_training_detail as $subject) {
                $check_complete_subject = UserCompletedSubject::where('subject_id', $subject->subject_id)->latest('id')->first();
                if(isset($check_complete_subject)) {
                    $row->{'subject_'. $subject->subject_id} = get_date($check_complete_subject->date_completed, 'd/m/Y');
                } else {
                    $row->{'subject_'. $subject->subject_id} = 'Chưa học';
                }
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
