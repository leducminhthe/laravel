<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\Models\ProfileView;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizType;
use Modules\Quiz\Entities\QuizUpdateAttempts;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\ReportNew\Entities\BC28;
use function GuzzleHttp\json_decode;

class BC28Controller extends ReportNewController
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
        $to_date = $request->to_date;
        $quiz_id = $request->quiz_id;
        $quiz_part = $request->quiz_part;

        if (!$quiz_id)
            json_result([]);

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC28::sql($quiz_id, $quiz_part);
        $count = $query->count();
        $query->orderBy('user_id', 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            if ($row->type == 1){
                $profile = ProfileView::whereUserId($row->user_id)->first(['code', 'full_name', 'unit_name', 'unit_code', 'parent_unit_name', 'email', 'title_name']);
            }else{
                $profile = QuizUserSecondary::find($row->user_id);
            }

            $count_attempt = QuizAttempts::whereQuizId($row->quiz_id)->where('part_id', $row->part_id)->whereUserId($row->user_id)->where('type', $row->type)->count();
            $quiz_attempt = QuizAttempts::whereQuizId($row->quiz_id)->where('part_id', $row->part_id)->whereUserId($row->user_id)->where('type', $row->type)->latest()->first();
            $quiz_question = QuizQuestion::whereQuizId($row->quiz_id)->count();
            $quiz_update_attempt = QuizUpdateAttempts::whereQuizId($row->quiz_id)->where('part_id', $row->part_id)->whereUserId($row->user_id)->whereType($row->type)->latest()->first();

            $num_true = 0;
            if ($quiz_update_attempt){
                $questions = json_decode($quiz_update_attempt->questions);
                foreach ($questions as $question){
                    $score = isset($question->score) ? $question->score : 0;
                    if ($question->score_group == $score){
                        $num_true += 1;
                    }
                }
            }

            $row->quiz_name = $row->quiz_name;
            $row->type_name = $row->quiz_type_name;
            $row->user_code = $profile->code;
            $row->full_name = $row->type == 1 ? $profile->full_name : $profile->name;
            $row->title_name = $row->type == 1 ? $profile->title_name : '_';
            $row->unit_name = $row->type == 1 ? $profile->unit_name : '_';
            $row->unit_parent_name = $row->type == 1 ? $profile->parent_unit_name : '_';
            $row->email = $profile->email;
            $row->status = (isset($row->result) && $row->result == 1) ? 'Hoàn thành' : 'Chưa hoàn thành';
            $row->count_attempt = $count_attempt;
            $row->start_date = isset($quiz_attempt->timestart) ? date('H:i:s d/m/Y', @$quiz_attempt->timestart) : '';
            $row->end_date = isset($quiz_attempt->timefinish) && $quiz_attempt->timefinish > 0 ? date('H:i:s d/m/Y', @$quiz_attempt->timefinish) : '';
            $row->execution_time = isset($quiz_attempt->timefinish) && $quiz_attempt->timefinish > 0 ? calculate_time_span(@$quiz_attempt->timefinish, @$quiz_attempt->timestart) : '';
            $row->score = $row->grade ? $row->grade : '';
            $row->num_true = $num_true;
            $row->num_false = $quiz_question - $num_true;
            $row->percent_true = number_format(($row->num_true / ($quiz_question > 0 ? $quiz_question : 1)) * 100, 2);
            $row->percent_false = number_format(($row->num_false / ($quiz_question > 0 ? $quiz_question : 1)) * 100, 2);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
