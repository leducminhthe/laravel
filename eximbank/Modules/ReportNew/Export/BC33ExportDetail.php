<?php
namespace Modules\ReportNew\Export;

use App\Models\Config;
use App\Models\LogoModel;
use App\Models\Categories\Titles;

use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithTitle;
use Modules\ReportNew\Entities\BC33;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Entities\SurveyQuestion;
use Modules\Survey\Entities\SurveyQuestionAnswer;
use Modules\Survey\Entities\SurveyUserCategory;
use Modules\Survey\Entities\SurveyUserAnswer;
use Modules\Survey\Entities\SurveyTemplate;
use Modules\Survey\Entities\SurveyTemplate2;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Modules\Survey\Entities\SurveyQuestionCategory2;
use Modules\Survey\Entities\SurveyQuestion2;
use Modules\Survey\Entities\SurveyQuestionAnswer2;

class BC33ExportDetail implements FromQuery, WithHeadings, WithHeadingRow, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings, WithTitle
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $column = 1;
    protected $ques = null;
    protected $ans = null;
    protected $survey = null;
    protected $countColHeader = null;
    protected $countColMatrixL1 = null;
    protected $num_char = 8;
    protected $numCharL1 = 8;
    protected $numCharL2 = 8;

    public function __construct($survey_id)
    {
        $survey = Survey::findOrFail($survey_id);
        $this->survey = $survey;
        $model = SurveyTemplate2::where('survey_id', $survey->id)->first(['id']);
        $surveyCategories2 = SurveyQuestionCategory2::where(['survey_id'=> $survey->id, 'template_id' => $model->id])->get();
        $arrAns = [];

        foreach ($surveyCategories2 as $cate){
            $surveyQuestions2 = SurveyQuestion2::where(['survey_id'=> $survey->id, 'category_id' => $cate->id])->get();
            foreach ($surveyQuestions2 as $question2) {
                if($question2->type == 'essay') {
                    $arrAns[ $question2->id ][] = '';
                    $question2->count_row = 1;
                } else {
                    $colQuestion = 0;
                    $colAnswerOfQuestion = 0;
                    $surveyAnswers2 = SurveyQuestionAnswer2::where(['survey_id'=> $survey->id, 'question_id' => $question2->id])->get();
                    $question2->answers2 = $surveyAnswers2;
                    foreach ($surveyAnswers2 as $answer2) {
                        $arrAns[ $question2->id ][] = $answer2;
                        if(($question2->type == 'matrix' && $answer2->is_row == 1) || ($question2->type == 'matrix_text' && $answer2->is_row == 1)) {
                            $colAnswerOfQuestion++;
                        } else if (($question2->type == 'matrix' && $answer2->is_row == 0) || ($question2->type == 'matrix_text' && $answer2->is_row == 0)) {
                            $colQuestion++;
                        }
                    }
                    if($question2->type == 'matrix' || $question2->type == 'matrix_text'){
                        $question2->colQuestion = $colQuestion;
                        $question2->colAnswerOfQuestion = $colAnswerOfQuestion;
                    } else {
                        $question2->countColAnswer = count($question2->answers2);
                    }
                }
                $this->ques[] = $question2;
            }

        }
        $this->ans = $arrAns;

    }

    public function title(): string
    {
        return 'Detail';
    }

    public function query()
    {
        $query = BC33::sql($this->survey->id)->orderBy('id', 'DESC');
        $this->count = $query->count();
        return $query;
    }

    public function map($row): array
    {

        $obj = [];
        $this->index++;
        $obj[] = $this->index;
        $obj[] = $row->code;
        $obj[] = $row->full_name;
        $obj[] = $row->email;
        $obj[] = $row->phone;
        $obj[] = $row->title_name;
        $obj[] = '';
        $obj[] = '';

        $userCates = SurveyUserCategory::where('category_id',$row->cate_id)->where('survey_user_id',$row->survey_user_id)->get();
        $arrUserQues = [];
        $arrUserAns = [];
        foreach ($userCates as $cate){
            foreach ($cate->questions as $q){
                $arrUserQues[$q->question_id] = $q;
                foreach ($q->answers as $a) {
                    $arrUserAns[$q->question_id][$a->answer_id] = $a;
                }
            }
        }

        foreach ($this->ques as $question){
            $user_ques = $arrUserQues[$question->id];
            if($question->type == 'rank' || $question->type == 'dropdown'){
                foreach ($question->answers2 as $a){
                    if($a->id == $user_ques->answer_essay) $obj[] =  'x';
                    else $obj[] = '';
                }
            } else if($question->type == 'choice'){
                foreach ($question->answers2 as $a){
                    $ans = $arrUserAns[$question->id][$a->id];
                    if($a->id == intval($ans->is_check)) {
                        $obj[] = $ans->text_answer ? $ans->text_answer : 'x';
                    }
                    else $obj[] = '';
                }
            } else if($question->type == 'rank_icon'){
                foreach ($question->answers2 as $a){
                    $ans = $arrUserAns[$question->id][$a->id];
                    if($a->id == intval($ans->is_check)) {
                        $obj[] =  'x';
                    }
                    else $obj[] = '';
                }
            } else if($question->type == 'essay' || $question->type == 'time'){
                $obj[] = $user_ques->answer_essay;
            } else if($question->type == 'matrix'){
                $arrMatrix1 = [];
                $arrMatrix2 = [];
                foreach ($question->answers2 as $answerOfQuestion){
                    if($answerOfQuestion->is_row == '0'){
                        $arrMatrix1[] = $answerOfQuestion->id;
                    } else if($answerOfQuestion->is_row == '1'){
                        $arrMatrix2[] = $answerOfQuestion->id;
                    }
                }
                foreach ($arrMatrix1 as $v){
                    foreach ($arrMatrix2 as $vi){
                        $ans = $arrUserAns[$question->id][$vi];
                        $arrCheckMaxtrix = json_decode($ans->check_answer_matrix);
                        if(in_array($v, $arrCheckMaxtrix)) {
                            $obj[] =  'x';
                        } else {
                            $obj[] = '';
                        }
                    }
                }
            } else if($question->type == 'matrix_text'){
                $arrL1 = [];
                $arrL2 = [];
                foreach ($question->answers2 as $answerOfQuestion){
                    if($answerOfQuestion->is_row == '0'){
                        $arrL1[] = $answerOfQuestion->id;
                    } else if($answerOfQuestion->is_row == '1'){
                        $arrL2[] = $answerOfQuestion->id;
                    }
                }
                foreach ($arrL1 as $keyArrL1 => $v){
                    foreach ($arrL2 as $key => $vi){
                        $ans = $arrUserAns[$question->id][$vi];
                        $arrCheckMaxtrix = json_decode($ans->answer_matrix);
                        if(!empty($arrCheckMaxtrix[$keyArrL1])) {
                            $obj[] =  $arrCheckMaxtrix[$keyArrL1];
                        } else {
                            $obj[] = '';
                        }
                    }
                }
            } else {
                foreach ($question->answers2 as $a){
                    $ans = $arrUserAns[$question->id][$a->id];
                    $obj[] = $ans->text_answer;
                }
            }
        }
        return $obj;
    }

    public function headings(): array
    {

        $colHeader= [
            trans('latraining.stt'),
            trans('latraining.employee_code'),
            trans('latraining.fullname'),
            'Email',
            trans('latraining.phone'),
            trans('latraining.title'),
            'Thời gian thực hiện',
            'Thời gian gửi'
        ];
        $arrAnswer = ['','','','','','','',''];
        $arrAnswerL2 = ['','','','','','','',''];
        $arrAns = $this->ans;
        $countColMatrixL1 = [];

        $countColHeader = [];
        foreach ($this->ques as $question){
            $colHeader[] = $question->name;
            if($question->type == 'essay' || $question->type == 'time') {
                $arrAnswer[] = '';
                $arrAnswerL2[] = '';
                $countColHeader[$this->num_char] = 1;
                $this->num_char += 1;
                $this->numCharL1 += 1;
            } else if($question->type == 'matrix' || $question->type == 'matrix_text') {
                $arrL1 = [];
                $arrL2 = [];
                $count = $this->num_char;

                $calculateColOfQuestion = $question->colQuestion * $question->colAnswerOfQuestion;
                $countColHeader[$this->num_char] = $calculateColOfQuestion;
                for ($i = 1; $i < $calculateColOfQuestion; $i++) {
                    $colHeader[] = '';
                    $count += 1;
                }
                $this->num_char = $count + 1;

                foreach ($question->answers2 as $answer) {
                    if($answer->is_row == '0'){
                        $arrL1[] = $answer->name;
                    } else if($answer->is_row == '1'){
                        $arrL2[] = $answer->name;
                    }
                }

                foreach ($arrL1 as $v){
                    $countL1 = 0;
                    $arrAnswer[] = $v;
                    for ($i = 1; $i < $question->colAnswerOfQuestion; $i++) {
                        $arrAnswer[] = '';
                        $countL1 += 1;
                    }
                    $countColMatrixL1[$this->numCharL1] = $this->numCharL1 + $countL1;
                    $this->numCharL1 = $this->numCharL1 + $countL1 + 1;

                    foreach ($arrL2 as $vi){
                        $arrAnswerL2[] = $vi;
                    }
                }
            } else {
                $count = $this->num_char;
                $countColHeader[$this->num_char] = $question->countColAnswer;
                for ($i = 1; $i < $question->countColAnswer; $i++) {
                    $colHeader[] = '';
                    $count += 1;
                }
                $this->num_char = $count + 1;
                $this->numCharL1 += 1;

                foreach ($question->answers2 as $vi){
                    $arrAnswer[] = $vi->name;
                    $arrAnswerL2[] = '';
                }
            }
        }
        $this->countColMatrixL1 = $countColMatrixL1;
        $this->countColHeader = $countColHeader;

        return [
            [],
            [],
            [],
            [],
            [],
            ['BÁO CÁO KẾT QUẢ KHẢO SÁT'],
            [],
            [],
            $colHeader,
            $arrAnswer,
            $arrAnswerL2
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $header = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'size'      =>  12,
                        'name' => 'Arial',
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ];

                $event->sheet->mergeCells('A1:M1');
                $event->sheet->getDelegate()->getStyle('A1:M1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                foreach(range('a','h') as $v){
                    $char = strtoupper($v);
                    $event->sheet->getDelegate()->mergeCells($char.'9:'.$char.'11')->getStyle($char.'9:'.$char.'11')->applyFromArray($header);
                }

                $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
                foreach ($this->countColHeader as $key => $colHeaderQuestion) {
                    if ($key >= 26){
                        $num_key = floor($key/26);
                        $num_key_1 = $key - ($num_key * 26);
                        $char1 = $arr_char[($num_key - 1)] . $arr_char[($num_key_1)];
                    }else{
                        $char1 = $arr_char[$key];
                    }

                    $sum = ($key + $colHeaderQuestion - 1);
                    if ($sum > 26){
                        $num = floor($sum/26);
                        $num_1 = $sum - ($num * 26);
                        $char2 = $arr_char[($num - 1)] . $arr_char[($num_1)];
                    }else{
                        $char2 = $arr_char[$sum];
                    }
                    $event->sheet->getDelegate()->mergeCells($char1.'9:'.$char2.'9')->getStyle($char1.'9:'.$char2.'9')
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                }

                // foreach ($this->countColMatrixL1 as $keyColMatrix => $colMatrixL1) {
                //     if ($keyColMatrix >= 26){
                //         $num_key_matrix = floor($keyColMatrix/26);
                //         $num_key_matrix_1 = $keyColMatrix - ($num_key_matrix * 26);
                //         $char_matrix_1 = $arr_char[($num_key_matrix - 1)] . $arr_char[($num_key_matrix_1)];
                //     }else{
                //         $char_matrix_1 = $arr_char[$keyColMatrix];
                //     }

                //     if ($colMatrixL1 > 26){
                //         $num_matrix = floor($colMatrixL1/26);
                //         $num_matrxi_1 = $colMatrixL1 - ($num_matrix * 26);
                //         $char_matrix_2 = $arr_char[($num_matrix - 1)] . $arr_char[($num_matrxi_1)];
                //     }else{
                //         $char_matrix_2 = $arr_char[$colMatrixL1];
                //     }

                //     $event->sheet->getDelegate()->mergeCells($char_matrix_1.'10:'.$char_matrix_2.'10')->getStyle($char_matrix_1.'10:'.$char_matrix_2.'10');
                // }
            },
        ];
    }


    public function headingRow(): int
    {
        return 0;
    }

    public function startRow(): int
    {
        return 22;
    }

    public function drawings()
    {
        $storage = \Storage::disk('upload');

        LogoModel::addGlobalScope(new CompanyScope());
        $logo = LogoModel::where('status',1)->first();
        if ($logo) {
            $path = $storage->path($logo->image);
        }else{
            $path = './images/image_default.jpg';
        }

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath($path);
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
}
