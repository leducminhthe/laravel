<?php
namespace Modules\Quiz\Exports;

use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Concerns\WithCharts;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizRank;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizUpdateAttempts;

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
use Carbon\Carbon;

class ResultExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $count_title = 12;

    public function __construct($quiz_id, $status, $type, $part, $unit, $title, $result_quiz, $search)
    {
        $this->quiz_id = $quiz_id;
        $this->status = $status;
        $this->type = $type;
        $this->part = $part;
        $this->unit = $unit;
        $this->title = $title;
        $this->result_quiz = $result_quiz;
        $this->search = $search;
    }

    public function map($register): array
    {
        $this->index++;
        $quiz_result = $this->getQuizResult($register->user_id, $register->type);

        $obj = [];
        $obj[] = $this->index;
        $obj[] = $register->type == 1 ? $register->profile_code : $register->user_secon_code;
        $obj[] = $register->type == 1 ? $register->full_name : $register->secondary_name;
        $obj[] = $register->type == 1 ? 'Thí sinh nội bộ' : 'Thí sinh bên ngoài';
        $obj[] = $register->title_name;
        $obj[] = $register->unit_name;
        $obj[] = $register->type == 1 ? $register->profile_email : $register->user_secon_email;
        $obj[] = $register->quiz_name;
        $obj[] = $register->part_name;
        $obj[] = $quiz_result && $quiz_result->timecompleted && $quiz_result->grade > 0 ? 'Đã hoàn thành' : '';
        $obj[] = $quiz_result ? ($quiz_result->reexamine ? number_format($quiz_result->reexamine, 2) : number_format($quiz_result->grade, 2)) : '';
        $obj[] = $quiz_result ? ($quiz_result->result == 1 ? 'Đậu' : 'Rớt') : '';

        return $obj;
    }

    public function query(){
        $query = QuizRegister::query();
        $query->select([
            'a.*',
            'b.full_name',
            'b.code AS profile_code',
            'b.email AS profile_email',
            'b.dob AS profile_dob',
            'b.identity_card AS profile_identity_card',
            'b.title_name',
            'b.unit_name',
            'e.name as part_name',
            'e.id as part_id',
            'f.id AS secondary_id',
            'f.name AS secondary_name',
            'f.code AS user_secon_code',
            'f.dob AS user_secon_dob',
            'f.email AS user_secon_email',
            'f.identity_card AS user_secon_identity_card',
            'quiz.name as quiz_name'
        ]);
        $query->from('el_quiz_register AS a');
        $query->leftJoin('el_profile_view AS b', function ($join) {
            $join->on('b.user_id', '=', 'a.user_id')
                ->where('a.type', '=', 1);
        });
        $query->leftJoin('el_quiz AS quiz', 'quiz.id', '=', 'a.quiz_id');
        $query->leftJoin('el_quiz_part AS e', 'e.id', '=', 'a.part_id');
        $query->leftJoin('el_quiz_user_secondary AS f', function ($join){
            $join->on('f.id', '=', 'a.user_id')
                ->where('a.type', '=', 2);
        });
        $query->where('a.quiz_id', '=', $this->quiz_id);
        $query->where('a.user_id', '>', 2);

        if ($this->search) {
            $query->where(function ($sub_query) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%'. $this->search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $this->search .'%');
                $sub_query->orWhere('f.code', 'like', '%'. $this->search .'%');
                $sub_query->orWhere('f.name', 'like', '%'. $this->search .'%');
                $sub_query->orWhere('b.email', 'like', '%'. $this->search .'%');
            });
        }

        if (!is_null($this->status)) {
            $query->where('b.status', '=', $this->status);
        }

        if ($this->title) {
            $query->where('c.id', '=', $this->title);
        }

        if ($this->unit) {
            $query->where('d.id', '=',  $this->unit);
        }

        if ($this->part) {
            $query->where('e.id', '=',  $this->part);
        }

        if ($this->type) {
            $query->where('a.type', '=',  $this->type);
        }

        if ($this->result_quiz){
            $quizAttempt = QuizAttempts::whereQuizId($this->quiz_id)->where('state', '=', 'completed')->pluck('user_id')->toArray();
            if ($this->result_quiz == 1){
                $query->whereIn('a.user_id', $quizAttempt);
            }

            if ($this->result_quiz == 2){
                $query->whereNotIn('a.user_id', $quizAttempt);
            }

            if ($this->result_quiz == 3){
                $query->where('g.result', '=', 1);
            }

            if ($this->result_quiz == 4){
                $query->where('g.result', '=', 0);
            }

        }

        $query->orderBy('a.id', 'ASC');
        $this->count = $query->count();

        return $query;
    }

    public function headings(): array
    {
        $title_arr[] = trans('latraining.stt');
        $title_arr[] = trans('latraining.employee_code');
        $title_arr[] = trans('latraining.fullname');
        $title_arr[] = 'Loại';
        $title_arr[] = trans('latraining.title');
        $title_arr[] = trans('latraining.unit');
        $title_arr[] = 'Email';
        $title_arr[] = 'Kỳ thi';
        $title_arr[] = 'Ca thi';
        $title_arr[] = 'Trạng thái';
        $title_arr[] = 'Điểm';
        $title_arr[] = 'Kết quả';

        return [
            ['KẾT QUẢ KIỂM TRA KỲ THI'],
            $title_arr,
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
                $columnName = $event->sheet->getDelegate()->getColumnDimensionByColumn($this->count_title);

                $char = $columnName->getColumnIndex();
                $event->sheet->getDelegate()->mergeCells('A1:'.$char.'1')->getStyle('A1')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A2:'.$char.''.(2 + $this->count))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },
        ];
    }

    public function getQuizResult($user_id, $user_type){
        return QuizResult::where('quiz_id', '=', $this->quiz_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $user_type)
            ->whereNull('text_quiz')
            ->first();
    }
}
