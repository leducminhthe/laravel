<?php
namespace Modules\ReportNew\Export;

use App\Models\Config;
use App\Models\LogoModel;
use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\Models\ProfileView;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizQuestionCategory;
use Modules\Quiz\Entities\QuizRank;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizType;
use Modules\Quiz\Entities\QuizUpdateAttempts;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\ReportNew\Entities\BC28;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use function GuzzleHttp\json_decode;

class BC28Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $count_title = 22;
    protected $arr_title = [];
    protected $res = 0;
    protected $rank = '';

    public function __construct($param)
    {
        $this->quiz_part = $param->quiz_part;
        $this->quiz_id = $param->quiz_id;
    }

    public function query()
    {
        $query = BC28::sql($this->quiz_id, $this->quiz_part)->orderBy('user_id', 'ASC');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $obj = [];

        $this->index++;
        if (isset($row->result)){
            if ($row->reexamine) {
                $row->reexamine >= $row->pass_score ? $this->res = 1 : $this->res = 0;
                $this->rank = $this->getRank($this->quiz_id, $row->reexamine);
            }else{
                $row->grade >= $row->pass_score ? $this->res = 1 : $this->res = 0;
                $this->rank = $this->getRank($this->quiz_id, $row->grade);
            }
        }else{
            $this->res = 0;
        }

        if ($row->type == 1){
            $profile = ProfileView::whereUserId($row->user_id)->first(['code', 'full_name', 'unit_name', 'unit_code', 'parent_unit_name', 'email', 'title_name']);
        }else{
            $profile = QuizUserSecondary::find($row->user_id);
        }

        if($row->grade_methor == 1) {
            $quiz_attempt = QuizAttempts::whereQuizId($row->quiz_id)->where('part_id', $row->part_id)->whereUserId($row->user_id)->where('type', $row->type)->orderBy('sumgrades', 'desc')->orderBy('id', 'desc')->first();
        } else if ($quiz->grade_methor == 3) {
            $quiz_attempt = QuizAttempts::whereQuizId($row->quiz_id)->where('part_id', $row->part_id)->whereUserId($row->user_id)->where('type', $row->type)->first();
        } else {
            $quiz_attempt = QuizAttempts::whereQuizId($row->quiz_id)->where('part_id', $row->part_id)->whereUserId($row->user_id)->where('type', $row->type)->latest()->first();
        }
        $count_attempt = QuizAttempts::whereQuizId($row->quiz_id)->where('part_id', $row->part_id)->whereUserId($row->user_id)->where('type', $row->type)->count();

        $quiz_question = QuizQuestion::whereQuizId($row->quiz_id)->count();
        $quiz_update_attempt = QuizUpdateAttempts::whereQuizId($row->quiz_id)->whereUserId($row->user_id)->whereType($row->type)->latest()->first();

        $obj[] = $this->index;
        $obj[] = $row->quiz_name;
        $obj[] = $row->quiz_type_name;
        $obj[] = $profile->code;
        $obj[] = $row->type == 1 ? $profile->full_name : $profile->name;
        $obj[] = $row->type == 1 ? $profile->title_name : '_';
        $obj[] = $row->type == 1 ? $profile->unit_name : '_';
        $obj[] = $row->type == 1 ? $profile->parent_unit_name : '_';
        $obj[] = $profile->email;
        $obj[] = $row->part_name;
        $obj[] = (isset($row->result) && $row->result == 1) ? 'Hoàn thành' : 'Chưa hoàn thành';
        $obj[] = $count_attempt;
        $obj[] = isset($quiz_attempt->timestart) ? date('H:i:s d/m/Y', @$quiz_attempt->timestart) : '';
        $obj[] = isset($quiz_attempt->timefinish) && $quiz_attempt->timefinish > 0 ? date('H:i:s d/m/Y', @$quiz_attempt->timefinish) : '';
        $obj[] = $row->limit_time;
        $obj[] = isset($quiz_attempt->timefinish) && $quiz_attempt->timefinish > 0 ? calculate_time_span(@$quiz_attempt->timefinish, @$quiz_attempt->timestart) : '';
        $obj[] = isset($row->grade) ? ($row->grade == 0 ? '0' : $row->grade) : '';
        $obj[] = isset($row->result) ? ($this->res == 1 ? 'Đậu' : 'Rớt') : 'Không nộp bài';

        $num_true = 0;
        if ($quiz_update_attempt){
            $questions = json_decode($quiz_update_attempt->questions);
            foreach ($questions as $question){
                if ($question->score_group == $question->score){
                    $num_true += 1;
                }

                $obj[] = $question->score == 0 ? '0' : number_format($question->score, 2);
            }
        }else{
            for ($i = 1; $i <= $quiz_question; $i++){
                $obj[] = '0';
            }
        }
        $num_false = $quiz_question - $num_true;

        $obj[] = $num_true;
        $obj[] = $num_false;
        $obj[] = number_format(($num_true / ($quiz_question > 0 ? $quiz_question : 1)) * 100, 2);
        $obj[] = number_format(($num_false / ($quiz_question > 0 ? $quiz_question : 1)) * 100, 2);

        return $obj;
    }

    public function headings(): array
    {
        $title_arr[] = trans('latraining.stt');
        $title_arr[] = 'Tên kỳ thi';
        $title_arr[] = 'Loại hình thi';
        $title_arr[] = trans('latraining.employee_code');
        $title_arr[] = trans('latraining.fullname');
        $title_arr[] =  trans('latraining.title');
        $title_arr[] = trans('lareport.unit_direct');
        $title_arr[] = trans('lareport.unit_management');
        $title_arr[] = 'Email';
        $title_arr[] = trans('latraining.name_part');
        $title_arr[] = trans('lareport.status');
        $title_arr[] = 'Số lần thi';
        $title_arr[] = 'Bắt đầu vào lúc';
        $title_arr[] = trans('lareport.end_time');
        $title_arr[] = 'Thời gian làm bài (Phút)';
        $title_arr[] = trans('lareport.time_done');
        $title_arr[] = 'Điểm';
        $title_arr[] = 'Đậu/Rớt';

        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';

        $quiz = Quiz::find($this->quiz_id);
        $max_score = QuizQuestion::getTotalScore($quiz->id);
        $score_group = $max_score > 0 ? ($quiz->max_score / $max_score) : 0;

        $quiz_questions = QuizQuestion::where('quiz_id', $this->quiz_id)->get();
        $i = 0;

        $num_title = 18;
        foreach($quiz_questions as $key => $quiz_question) {
            $num_title += 1;

            if ($quiz_question->qqcategory > 0){
                if ($key > 0 && $quiz_questions[$key - 1]->qqcategory == $quiz_question->qqcategory){
                    $title_arr[] = '';
                }else{
                    $quiz_question_cate = QuizQuestionCategory::whereQuizId($quiz->id)->where('id', $quiz_question->qqcategory)->first();
                    $title_arr[] = @$quiz_question_cate->name;

                    if ($key == 0){
                        $this->arr_title['start_cate_'.$quiz_question->qqcategory] = $num_title;
                    }else{
                        $this->arr_title['end_cate_'.$quiz_questions[$key - 1]->qqcategory] = ($num_title - 1);
                        $this->arr_title['start_cate_'.$quiz_question->qqcategory] = $num_title;
                    }
                }
            }else{
                $title_arr[] = '';

                if ($key == 0){
                    $this->arr_title['start_cate_'.$quiz_question->qqcategory] = $num_title;
                }
            }

            $calculate_socre = ($score_group * $quiz_question->max_score);
            $i++;
            $title_arr2[] =  'Q.'.$i. '/'. number_format($calculate_socre, 2);

            $this->count_title += 1;

            if ($quiz_questions->count() == ($key + 1)){
                $this->arr_title['end_cate_'.$quiz_question->qqcategory] = $num_title;
            }
        }

        $this->arr_title['start_num_ques'] = $num_title + 1;
        $this->arr_title['end_num_ques'] = $num_title + 2;

        $this->arr_title['start_percent'] = $num_title + 3;
        $this->arr_title['end_percent'] = $num_title + 4;

        $title_arr[] = 'SL câu hỏi';
        $title_arr[] = '';
        $title_arr[] = 'Tỉ lệ';
        $title_arr[] = '';

        $title_arr2[] = 'SL Đúng';
        $title_arr2[] = 'SL Sai';
        $title_arr2[] = '% Đúng';
        $title_arr2[] = '% Sai';

        return [
            [],
            [],
            [],
            [],
            [],
            ['BÁO CÁO KẾT QUẢ CHI TIẾT THEO KỲ THI'],
            [],
            $title_arr,
            $title_arr2
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
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $char = $this->getChar($this->count_title);

                $event->sheet->getDelegate()->mergeCells('A6:'.$char.'6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:'.$char.'9')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:'.$char.''.(9 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                $event->sheet->getDelegate()->mergeCells('A8:A9');
                $event->sheet->getDelegate()->mergeCells('B8:B9');
                $event->sheet->getDelegate()->mergeCells('C8:C9');
                $event->sheet->getDelegate()->mergeCells('D8:D9');
                $event->sheet->getDelegate()->mergeCells('E8:E9');
                $event->sheet->getDelegate()->mergeCells('F8:F9');
                $event->sheet->getDelegate()->mergeCells('G8:G9');
                $event->sheet->getDelegate()->mergeCells('H8:H9');
                $event->sheet->getDelegate()->mergeCells('I8:I9');
                $event->sheet->getDelegate()->mergeCells('J8:J9');
                $event->sheet->getDelegate()->mergeCells('K8:K9');
                $event->sheet->getDelegate()->mergeCells('L8:L9');
                $event->sheet->getDelegate()->mergeCells('M8:M9');
                $event->sheet->getDelegate()->mergeCells('N8:N9');
                $event->sheet->getDelegate()->mergeCells('O8:O9');
                $event->sheet->getDelegate()->mergeCells('P8:P9');
                $event->sheet->getDelegate()->mergeCells('Q8:Q9');
                $event->sheet->getDelegate()->mergeCells('R8:R9');

                $quiz_questions = QuizQuestion::query()
                    ->where('quiz_id', $this->quiz_id)
                    ->groupBy('qqcategory')
                    ->pluck('qqcategory')->toArray();
                foreach ($quiz_questions as $cate){
                    $start_char = $this->getChar($this->arr_title['start_cate_'.$cate]);
                    $end_char = $this->getChar($this->arr_title['end_cate_'.$cate]);

                    $event->sheet->getDelegate()->mergeCells($start_char.'8:'.$end_char.'8');
                }

                $start_num_ques = $this->getChar($this->arr_title['start_num_ques']);
                $end_num_ques = $this->getChar($this->arr_title['end_num_ques']);
                $start_percent = $this->getChar($this->arr_title['start_percent']);
                $end_percent = $this->getChar($this->arr_title['end_percent']);

                $event->sheet->getDelegate()->mergeCells($start_num_ques.'8:'.$end_num_ques.'8');
                $event->sheet->getDelegate()->mergeCells($start_percent.'8:'.$end_percent.'8');
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

    public function getChar($number){
        $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        if ($number > 26){
            $num = floor($number/26);
            $num_1 = $number - ($num * 26);

            $char = $arr_char[($num - 1)] . $arr_char[($num_1 - 1)];
        }else{
            $char = $arr_char[($number - 1)];
        }

        return $char;
    }

    public function getRank($quiz_id, $score){
        if ($score) {
            $quiz_rank = QuizRank::where('quiz_id', '=', $quiz_id)
                ->where('score_min', '<=', $score)
                ->where('score_max', '>=', $score)
                ->first(['rank']);
            return $quiz_rank ? $quiz_rank->rank : 'Không xếp loại';
        }
        return  'Không xếp loại';
    }
}
