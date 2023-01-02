<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\RattingCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_decode;
use App\Models\Categories\Unit;
use Modules\ReportNew\Entities\BC33;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Entities\SurveyUser;
use Modules\Survey\Entities\SurveyUserCategory;
use Modules\Survey\Entities\SurveyQuestion2;
use Modules\Survey\Entities\SurveyUserQuestion;
use Modules\Survey\Entities\SurveyUserAnswer;

class BC33Controller extends ReportNewController
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
    public function getQuestionFromSurvey(Request $request)
    {
        $survey_id = $request->survey_id;
        $model = null;
        if($survey_id){
            $query = Survey::query()
                ->select(['a.*','b.id as cate_id'])
                ->from('el_survey as a')
                ->leftJoin('el_survey_template2_question_category as b', function ($join){
                    $join->on('b.template_id', '=', 'a.template_id');
                    $join->on('b.survey_id', '=', 'a.id');
                })
                ->where('a.id', '=', $survey_id);

            $model = $query->get()->first();

            $questions = SurveyQuestion2::where("category_id",$model->cate_id)->get()->toArray();
        }
        return json_result($questions);
    }

    public function getData(Request $request)
    {
        $survey_id = $request->id;

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        if($survey_id) {
            $query = BC33::sql($survey_id);
            $count = $query->count();
            $query->orderBy('created_at', 'DESC');
            $query->offset($offset);
            $query->limit($limit);
            $rows = $query->get();
        } else {
            $rows = [];
        }

        foreach ($rows as $key => $row) {
            if($row->send == 1) {
                $row->survey_user_status = '<span>Hoàn thành</span>';
            } else {
                $row->survey_user_status = '<span>Lưu</span>';
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
