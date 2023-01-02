<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionCategory;
use Modules\ReportNew\Entities\BC34;
use Modules\ReportNew\Entities\ReportNewBC34;

class BC34Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        QuestionCategory::addGlobalScope(new DraftScope());
        $question_categories = QuestionCategory::get();

        $report = parent::reportList();
        $reportGroupList = $this->reportGroupList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'question_categories' => $question_categories,
            'reportList' => $reportGroupList,
        ]);
    }

    public function getData(Request $request)
    {
        if (!$request->question_category_id)
            json_result([]);

        $question_category_id = $request->question_category_id;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC34::sql($question_category_id);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $report_new_bc34 = ReportNewBC34::where('category_id', $row->id)->first();

            $scoring_question_quantity = Question::where('category_id', $row->id)->whereNotIn('type', ['essay', 'fill_in'])->count();
            $scoring_question_active = Question::where('category_id', $row->id)->whereNotIn('type', ['essay', 'fill_in'])->where('status', 1)->count();

            $question_graded_quantity = Question::where('category_id', $row->id)->whereIn('type', ['essay', 'fill_in'])->count();
            $question_graded_active = Question::where('category_id', $row->id)->whereIn('type', ['essay', 'fill_in'])->where('status', 1)->count();

            $row->scoring_question_quantity = $scoring_question_quantity;
            $row->scoring_question_active = $scoring_question_active .'/'. $scoring_question_quantity;
            $row->scoring_question_used = $report_new_bc34 ? $report_new_bc34->scoring_question_used : '';
            $row->scoring_question_ratio_correct = $report_new_bc34 ? ($report_new_bc34->scoring_question_used > 0 ? round(($report_new_bc34->scoring_question_correct/$report_new_bc34->scoring_question_used)*100, 2) : 0).'%' : '';

            $row->question_graded_quantity = $question_graded_quantity;
            $row->question_graded_active = $question_graded_active .'/'. $question_graded_quantity;
            $row->question_graded_used = $report_new_bc34 ? $report_new_bc34->question_graded_used : '';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
