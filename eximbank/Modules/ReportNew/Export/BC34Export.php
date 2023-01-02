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
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizUpdateAttempts;
use Modules\ReportNew\Entities\BC03;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\ReportNew\Entities\BC34;
use Modules\ReportNew\Entities\ReportNewBC34;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC34Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;

    public function __construct($param)
    {
        $this->question_category_id = $param->question_category_id;
    }

    public function query()
    {
        $query = BC34::sql($this->question_category_id)->orderBy('id', 'ASC');

        return $query;
    }
    public function map($row): array
    {
        $this->index++;

        $report_new_bc34 = ReportNewBC34::where('category_id', $row->id)->first();

        $scoring_question_quantity = Question::where('category_id', $row->id)->whereNotIn('type', ['essay', 'fill_in'])->count();
        $scoring_question_active = Question::where('category_id', $row->id)->whereNotIn('type', ['essay', 'fill_in'])->where('status', 1)->count();
        $scoring_question_used = $report_new_bc34 ? $report_new_bc34->scoring_question_used : '';
        $scoring_question_ratio_correct = $report_new_bc34 ? ($report_new_bc34->scoring_question_used > 0 ? round(($report_new_bc34->scoring_question_correct/$report_new_bc34->scoring_question_used)*100, 2) : 0) .'%' : '';

        $question_graded_quantity = Question::where('category_id', $row->id)->whereIn('type', ['essay', 'fill_in'])->count();
        $question_graded_active = Question::where('category_id', $row->id)->whereIn('type', ['essay', 'fill_in'])->where('status', 1)->count();
        $question_graded_used = $report_new_bc34 ? $report_new_bc34->question_graded_used : '';

        return [
            $this->index,
            $row->name,
            $scoring_question_quantity,
            $scoring_question_active .'/'. $scoring_question_quantity,
            $scoring_question_used,
            $scoring_question_ratio_correct,
            $question_graded_quantity,
            $question_graded_active .'/'. $question_graded_quantity,
            $question_graded_used,
        ];
    }

    public function headings(): array
    {
        return [
            [],
            [],
            [],
            [],
            [],
            ['BÁO CÁO THỐNG KÊ NGÂN HÀNG CÂU HỎI'],
            [],
            [
                trans('latraining.stt'),
                'Danh mục câu hỏi',
                'Câu hỏi tinh điểm', '', '', '',
                'Câu hỏi được chấm điểm', '', '',
            ],
            [
                '',
                '',
                'Số lượng', 'Hoạt động', 'Đã sử dụng', 'Tỉ lệ đáp đúng',
                'Số lượng', 'Hoạt động', 'Đã sử dụng',
            ]
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

                $event->sheet->getDelegate()->mergeCells('A6:I6')->getStyle('A6')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A8:A9')->getStyle('A8')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('B8:B9')->getStyle('B8')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('C8:F8')->getStyle('C8')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('G8:I8')->getStyle('G8')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:I9')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:I'.(9 + $this->index))
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
