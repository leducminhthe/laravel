<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\CourseView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_decode;
use Modules\ReportNew\Entities\BC37;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizQuestion;

class BC37Controller extends ReportNewController
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
        $users = $request->users;
        $quiz_id = $request->quiz_id;
        $quiz_part = $request->quiz_part;
        $quiz = Quiz::find($quiz_id, ['id', 'grade_methor']);
        if (!isset($quiz) || $quiz->grade_methor == 2)
            json_result([]);

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC37::sql($quiz, $quiz_part);
        $count = $query->count();
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $list_question_isset = [];
        foreach ($rows as $key => $row) {
            $first = 0;
            $corect = [];
            $choose = [];
            if($row->end_date) {
                $row->date_exam = get_date($row->start_date, 'd/m/Y H:i:s') .' => '. get_date($row->end_date, 'd/m/Y H:i:s');
            } else {
                $row->date_exam = get_date($row->start_date, 'd/m/Y H:i:s');
            }
            $get_question = json_decode($row->questions);
            usort($get_question, function ($a, $b) {
                return $a['qindex'] <=> $b['qindex'];
            });

            foreach ($get_question as $key => $question) {
                if($row->num_order == $question->qindex) {
                    $first = 1;
                    $list_question_isset[$row->user_id][] = $question->id;
                    $name_question = $question->name;
                    $corect_answer = $question->correct_answers;
                    $choose_answer = $question->answer;
                    $all_answers = $question->answers;
                    $type_question = $question->type;
                    $score = $question->score;
                } else {
                    continue;
                }
            }

            $row->question = $name_question;
            $row->score = round($score, 2);
            // if($type_question == 'multiple-choise') {
                foreach ($all_answers as $key => $answer) {
                    $row->{'answer_'. ($key+1)} = $answer->title;

                    if(in_array($answer->id, $corect_answer)) {
                        $corect[] = $arr_char[$key];
                    }
                    if(in_array($answer->id, $choose_answer)) {
                        $choose[] = $arr_char[$key];
                    }
                }
                $row->corect_answer = implode('|', $corect);
                $row->choose_answer = implode('|', $choose);
            // }

            if($score > 0) {
                $row->result = 'Đúng';
            } else {
                $row->result = 'Sai';
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
