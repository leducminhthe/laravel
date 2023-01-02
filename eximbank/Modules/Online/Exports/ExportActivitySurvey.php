<?php
namespace Modules\Online\Exports;

use App\Models\LogoModel;
use App\Models\Profile;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineSurveyUser;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithDrawings;
use App\Models\Config;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Modules\Online\Entities\OnlineSurveyTemplate;
use Modules\Online\Entities\OnlineSurveyCategory;
use Modules\Online\Entities\OnlineSurveyQuestion;
use Modules\Online\Entities\OnlineSurveyAnswer;
use Modules\Online\Entities\OnlineSurveyUserQuestion;
use Modules\Online\Entities\OnlineSurveyUserCategory;
use Modules\Survey\Entities\SurveyTemplate;

class ExportActivitySurvey implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $column = 1;
    protected $ques = null;
    protected $ans = null;
    protected $survey = null;
    protected $countColHeader = null;
    protected $countColMatrixL1 = null;
    protected $num_char = 8;
    protected $numCharL1 = 8;
    protected $numCharL2 = 8;

    public function __construct($course_id, $activityId, $templateId)
    {
        $this->course_id = $course_id;
        $this->activityId = $activityId;

        $surveyCategories = OnlineSurveyCategory::where(['template_id' => $templateId, 'course_id' => $course_id, 'course_activity_id' => $activityId])->get();
        $arrAns = [];

        foreach ($surveyCategories as $cate){
            $questionCategory = OnlineSurveyQuestion::where(['category_id' => $cate->id, 'course_id' => $course_id, 'course_activity_id' => $activityId])->get();
            foreach ($questionCategory as $v) {
                if($v->type == 'essay') {
                    $arrAns[$v->id][] = '';
                    $v->count_row = 1;
                } else {
                    $colQuestion = 0;
                    $colAnswerOfQuestion = 0;
                    $answerSurveyOnline = OnlineSurveyAnswer::where(['question_id' => $v->id, 'course_id' => $course_id, 'course_activity_id' => $activityId])->get();
                    $v->answer_survey_online = $answerSurveyOnline;
                    foreach ($answerSurveyOnline as $a) {
                        $arrAns[$v->id][] = $a;
                        if(($v->type == 'matrix' && $a->is_row == 1) || ($v->type == 'matrix_text' && $a->is_row == 1)) {
                            $colAnswerOfQuestion++;
                        } else if (($v->type == 'matrix' && $a->is_row == 0) || ($v->type == 'matrix_text' && $a->is_row == 0)) {
                            $colQuestion++;
                        }
                    }
                    if($v->type == 'matrix' || $v->type == 'matrix_text'){
                        $v->colQuestion = $colQuestion;
                        $v->colAnswerOfQuestion = $colAnswerOfQuestion;
                    } else {
                        $v->countColAnswer = count($v->answer_survey_online);
                    }
                }
                $this->ques[] = $v;
            }
        }
        $this->ans = $arrAns;
    }

    public function query()
    {
        $query = OnlineSurveyUser::leftjoin('el_profile_view as b','b.id','=','el_online_survey_user.user_id')
                ->where('el_online_survey_user.course_activity_id', $this->activityId)
                ->where('el_online_survey_user.course_id', $this->course_id)
                ->select(['el_online_survey_user.*', 'b.full_name', 'b.title_name', 'b.email', 'b.code', 'b.unit_name']);

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
        $obj[] = $row->title_name;
        $obj[] = $row->unit_name;
        $obj[] = '';
        $obj[] = '';

        $userCates = OnlineSurveyUserCategory::where('survey_user_id', $row->id)->get();
        $arrUserQues = [];
        $arrUserAns = [];
        foreach ($userCates as $userCate){
            foreach ($userCate->questions as $userQuestion){
                $arrUserQues[$userQuestion->question_id] = $userQuestion;
                foreach ($userQuestion->answers as $a) {
                    $arrUserAns[$userQuestion->question_id][$a->answer_id] = $a;
                }
            }
        }

        foreach ($this->ques as $question){
            $user_ques = $arrUserQues[$question->id];
            if($question->type == 'rank' || $question->type == 'dropdown'){
                foreach ($question->answer_survey_online as $a){
                    if($a->id == $user_ques->answer_essay) $obj[] =  'x';
                    else $obj[] = '';
                }
            } else if($question->type == 'choice'){
                foreach ($question->answer_survey_online as $a){
                    $ans = $arrUserAns[$question->id][$a->id];
                    if($a->id == intval($ans->is_check)) {
                        $obj[] = $ans->text_answer ? $ans->text_answer : 'x';
                    }
                    else $obj[] = '';
                }
            } else if($question->type == 'rank_icon'){
                foreach ($question->answer_survey_online as $a){
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
                foreach ($question->answer_survey_online as $answerOfQuestion){
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
                foreach ($question->answer_survey_online as $answerOfQuestion){
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
                foreach ($question->answer_survey_online as $a){
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
            trans('latraining.title'),
            trans('lamenu.unit'),
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

                foreach ($question->answer_survey_online as $answer) {
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

                foreach ($question->answer_survey_online as $vi){
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
            ['BÁO CÁO KẾT QUẢ HOẠT ĐỘNG KHẢO SÁT'],
            [],
            [],
            $colHeader,
            [],
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
