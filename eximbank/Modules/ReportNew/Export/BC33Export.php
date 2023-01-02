<?php
namespace Modules\ReportNew\Export;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Entities\SurveyQuestion;

class BC33Export implements WithMultipleSheets
{
    use Exportable;

    public function __construct($param)
    {
        $this->survey_id = $param->survey_id;

    }

    public function sheets(): array
    {
        $sheets[] = new BC33ExportDetail($this->survey_id);

        if($this->survey_id){

            $query = Survey::query()
                ->select(['a.*','b.id as cate_id'])
                ->from('el_survey as a')
                ->leftJoin('el_survey_template_question_category as b', 'b.template_id', '=', 'a.template_id')
                ->where('a.id', '=', $this->survey_id);

            $model = $query->get()->first();

            $questions = SurveyQuestion::where("category_id",$model->cate_id)->get();
        }

        // $no = 1;
        // foreach ($questions as $v) {
        //     $sheets[] = new BC33ExportChart($this->survey_id, $v, $no);
        //     $no++;
        // }
        return $sheets;
    }
}
