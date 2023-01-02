<?php
namespace Modules\ReportNew\Export;

use App\Models\Config;
use App\Models\LogoModel;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;

use Modules\ReportNew\Entities\BC37;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\Question;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC37Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count_title = 16;
    protected $list_question_isset = [];

    public function __construct($param)
    {
        $quiz = Quiz::find($param->quiz_id, ['id', 'grade_methor']);
        $this->quiz = $quiz;
        $this->quiz_part = $param->quiz_part;
    }

    public function query()
    {
        $query = BC37::sql($this->quiz, $this->quiz_part);
        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $first = 0;
        $corect = [];
        $choose = [];
        if($row->end_date) {
            $date_exam = get_date($row->start_date, 'd/m/Y H:i:s') .' => '. get_date($row->end_date, 'd/m/Y H:i:s');
        } else {
            $date_exam = get_date($row->start_date, 'd/m/Y H:i:s');
        }
        $this->index++;
        $obj[] = $this->index;
        $obj[] = $row->quiz_name;
        $obj[] = $row->part_name;
        $obj[] = $date_exam;
        $obj[] = $row->limit_time;
        $obj[] = $row->code;
        $obj[] = $row->full_name;
        $obj[] = $row->title_name;
        $obj[] = $row->unit_name;
        $obj[] = $row->email;
        $obj[] = $row->unit_create_quiz;

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

        if($score > 0) {
            $obj[] = 'Đúng';
        } else {
            $obj[] = 'Sai';
        }
        $obj[] = $score > 0 ? round($score, 2) : '0';
        $obj[] = html_entity_decode(strip_tags($name_question));
        $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $answerArrs = [];
        // if($type_question == 'multiple-choise') {
            foreach ($all_answers as $key => $answer) {
                $answerArrs[] = (string)$answer->title;
                if(in_array($answer->id, $corect_answer)) {
                    $corect[] = $arr_char[$key];
                }
                if(in_array($answer->id, $choose_answer)) {
                    $choose[] = $arr_char[$key];
                }
            }

            $obj[] = implode('|', $corect);
            $obj[] = implode('|', $choose);
            foreach ($answerArrs as $key => $answerArr) {
				if (str_contains($answerArr, '=<')) {
					$answerArr = str_replace('=<', '≤', $answerArr);
				}
                $obj[] = html_entity_decode(strip_tags($answerArr));
            }
        // }

        return $obj;
    }

    public function headings(): array
    {
        $title_arr = [];
        $title_arr[] = trans('latraining.stt');
        $title_arr[] = trans('latraining.quiz_name');
        $title_arr[] = trans('latraining.part');
        $title_arr[] = 'Ngày thi';
        $title_arr[] = trans('lareport.duration'). '(Phút)';
        $title_arr[] = trans('latraining.employee_code');
        $title_arr[] = trans('latraining.fullname');
        $title_arr[] = trans('latraining.title');
        $title_arr[] = trans('lareport.unit_direct');
        $title_arr[] = 'Email';
        $title_arr[] = 'Đơn vị ra đề';
        $title_arr[] = trans('latraining.result');
        $title_arr[] = trans('latraining.score');
        $title_arr[] = trans('latraining.question');
        $title_arr[] = 'Đáp án đúng';
        $title_arr[] = 'Đáp án chọn';

        $quizCategory = QuizQuestion::where('quiz_id', $this->quiz->id)->pluck('qcategory_id')->toArray();
        $quizQuestion = Question::whereIn('category_id', $quizCategory)->pluck('id')->toArray();
        $maxCount = \DB::table('el_question_answer')
        ->select(\DB::raw('count(question_id) as total'))
        ->whereIn('question_id', $quizQuestion)
        ->orderBy('total', 'DESC')
        ->groupBy('question_id')
        ->first();

        $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        for ($index = 1; $index <= $maxCount->total; $index++) {
            $title_arr[] = 'Câu trả lời '. $arr_char[$index - 1];
            $this->count_title += 1;
        }

        return [
            [],
            [],
            [],
            [],
            [],
            ['BÁO CÁO THỐNG KÊ CHI TIẾT TỶ LỆ TRẢ LỜI ĐÚNG TỪNG CÂU HỎI'],
            [],
            $title_arr
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $title = [
                    'font' => [
                        'size'      =>  12,
                        'name' => 'Arial',
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                    ],
                ];

                $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
                if ($this->count_title > 26){
                    $num = floor($this->count_title/26);
                    $num_1 = $this->count_title - ($num * 26);

                    $char = $arr_char[($num - 1)] . $arr_char[($num_1 - 1)];
                }else{
                    $char = $arr_char[($this->count_title - 1)];
                }

                $event->sheet->getDelegate()->mergeCells('A6:'.$char.'6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:'.$char.'8')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:'.$char.(8 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                        ],
                    ])->getAlignment()->setWrapText(true);
            },
        ];
    }
    public function startRow(): int
    {
        return 9;
    }

    public function drawings()
    {
        $storage = \Storage::disk('upload');

        LogoModel::addGlobalScope(new CompanyScope());
        $logo = LogoModel::where('status',1)->first();
        $checkLogo = upload_file($logo->image);
        if ($logo && $checkLogo) {
            $path = $storage->path($logo->image);
        }else{
            $path = './images/logo_topleaning.png';
        }

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath($path);
        $drawing->setHeight(100);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
}
