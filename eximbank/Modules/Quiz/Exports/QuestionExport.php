<?php

namespace Modules\Quiz\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCharts;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\QuizRank;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\Quiz;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class QuestionExport implements FromQuery, WithHeadings, WithMapping, WithEvents
{
    use Exportable;

    protected $count = 0;
    protected $index = 0;

    public function __construct($category_id)
    {
        $this->category_id = $category_id;
    }

    public function map($row): array
    {
        $obj = [];
        $correct = [];
        $question = trim(html_entity_decode(strip_tags($row->name), ENT_QUOTES), "\xc2\xa0");
        $type = $row->type;
        $feedback = json_decode($row->feedback, true);
        $shuffleAnswers = ($row->shuffle_answers == 1) ? 'X' : '';
        $note = $row->note;
        $code = $row->code;
        $difficulty = $row->difficulty;
        $multipleFulScore = ($row->multiple_full_score == 1) ? '' : 'X';
        $this->index++;
        $obj[] = $this->index;
        $obj[] = $code;
        $obj[] = $question;
        $obj[] = $difficulty;
        switch ($type) {
            case('essay'):
                $obj[] = 1;
                break;
            case ('multiple-choise'):
                if ($row->multiple == 0) {
                    $obj[] = 2;
                } else {
                    $obj[] = 3;
                }
                break;
            case('fill_in_correct'):
                $obj[] = 4;
                break;
            case('matching'):
                $obj[] = 5;
                break;
            default:
                $obj[] = 6;
        }
        $obj[] = $note;
        $obj[] = $multipleFulScore;
        $obj[] = $shuffleAnswers;
        $obj[] = implode(',', $feedback);
        $arr_char = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        switch ($row->type) {
            case('fill_in'):
                $obj[] = '';
                $answers = QuestionAnswer::where('question_id', $row->id)->get();
                foreach ($answers as $answer) {
                    $obj[] = trim(html_entity_decode(strip_tags($answer->title), ENT_QUOTES), "\xc2\xa0");
                }
                break;
            case('fill_in_correct'):
                $answers = QuestionAnswer::where('question_id', $row->id)->get();
                $correctAnswers = QuestionAnswer::where('question_id', $row->id)->whereNotNull('fill_in_correct_answer')->get();
                foreach ($correctAnswers as $correctAnswer) {
                    $correct[] = $correctAnswer->fill_in_correct_answer;
                }
                $obj[] = implode('|', $correct);
                foreach ($answers as $answer) {
                    $obj[] = trim(html_entity_decode(strip_tags($answer->title), ENT_QUOTES), "\xc2\xa0");
                }
                break;
            case('matching'):
                $answers = QuestionAnswer::where('question_id', $row->id)->get();
                $correctAnswers = QuestionAnswer::where('question_id', $row->id)->whereNotNull('matching_answer')->get();
                foreach ($correctAnswers as $correctAnswer) {
                    $correct[] = $correctAnswer->matching_answer;
                }
                $obj[] = implode('|', $correct);
                foreach ($answers as $answer) {
                    $obj[] = trim(html_entity_decode(strip_tags($answer->title), ENT_QUOTES), "\xc2\xa0");
                }
                break;
            case('multiple-choise'):
                $multiAnswer = QuestionAnswer::where('question_id', $row->id)->where('percent_answer', '!=', 0.00)->get();
                $answers = QuestionAnswer::where('question_id', $row->id)->get();
                if ($multiAnswer->isNotEmpty()) {
                    foreach ($multiAnswer as $key => $correctAnswer) {
                        $correct[] = $arr_char[$key];
                    }
                    $obj[] = implode('|', $correct);
                }
                foreach ($answers as $key => $answer) {
                    if ($answer->correct_answer == 1) {
                        $obj[] = $arr_char[$key];
                    }
                }
                foreach ($answers as $answer) {
                    $obj[] = trim(html_entity_decode(strip_tags($answer->title), ENT_QUOTES), "\xc2\xa0");
                }
                break;
            case('select_word_correct'):
                $answersGroup = [];
                $answersArrayOfGroup = [];
                $answersArray = [];
                $correctAnswersArrayOfGroup = [];
                $correctAnswersArray = [];
                $answers = QuestionAnswer::where('question_id', $row->id)->get();
                foreach ($answers as $key => $answer) {
                    $answersGroup[$answer->select_word_correct][] = $answer;
                }
                foreach ($answersGroup as $answers) {
                    foreach ($answers as $answer) {
                        $answersArrayOfGroup[] = $answer->title;
                    }
                    $answersArray[] = implode('|', $answersArrayOfGroup);
                    unset($answersArrayOfGroup);
                }

                foreach ($answersGroup as $answers) {
                    foreach ($answers as $key => $answer) {
                        if ($answer->correct_answer > 0) {
                            $correctAnswersArrayOfGroup = $key + 1;
                        }
                    }
                    $correctAnswersArray[] = $correctAnswersArrayOfGroup;
                    unset($correctAnswersArrayOfGroup);
                }
                $obj[] = implode('|', $correctAnswersArray);
                foreach ($answersArray as $answer) {
                    $obj[] = $answer;
                }
                break;
            default:
                $obj[] = ' ';
                break;
        }
        return $obj;
    }

    public function query()
    {
        $query = Question::where('category_id', $this->category_id);
        return $query;
    }

    public function headings(): array
    {
        return [
            ['DANH SÁCH CÂU HỎI'],
            [
                'STT',
                'Mã câu hỏi',
                'Tên câu hỏi',
                'Mức độ',
                'Loại câu hỏi',
                'Ghi chú',
                'Cách tính điểm (Câu hỏi chọn nhiều)',
                'Xáo trộn đáp án',
                'Phản hồi câu hỏi',
                'Đáp án đúng',
                'Câu trả lời',
                'Câu trả lời',
                'Câu trả lời',
                'Câu trả lời',
                'Câu trả lời',
                'Câu trả lời',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:P1');
                $event->sheet->getDelegate()->getStyle('A1:P1')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                    'font' => [
                        'size' => 13,
                        'bold' => true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');
                $event->sheet->getDelegate()->getStyle('A2:P2')->applyFromArray([
                    'font' => [
                        'size' => 13,
                        'bold' => true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('0080FF');
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(10);
                $columns = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
                foreach ($columns as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setWidth(30);
                }
                $columnsOfAnswer = ['K', 'L', 'M', 'N', 'O', 'P'];
                foreach ($columnsOfAnswer as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setWidth(50);
                }
                $event->sheet->getDelegate()->getStyle('A1:P' . (2 + $this->count))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name' => 'Arial',
                    ],
                ]);
            },
        ];

    }

}
