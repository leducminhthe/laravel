<?php
namespace Modules\Quiz\Exports;

use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;

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
use Maatwebsite\Excel\Concerns\FromArray;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class TemplateRegisterExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 1;
    protected $total_percent = 0;

    public function __construct($quiz_id)
    {
        $this->quiz_id = $quiz_id;
    }

    public function array(): array
    {
        return [
            [1, 'demo1', 'Demo 01', '', '1'],
        ];
    }

    public function headings(): array
    {
        return [
            [
                trans('latraining.stt'),
                trans('laprofile.user_name'),
                trans('latraining.fullname'),
                'Tên ca thi',
                'Loại thí sinh (1: Nội bộ, 2:Bên ngoài)',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:E1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:E'.(2 + $this->count).'')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $date = date('Y-m-d H:i:s');
                $query = QuizPart::query();
                $query->where('quiz_id', $this->quiz_id);
                $query->where('end_date', '>', $date);
                $all_part = $query->pluck('name')->toArray();

                for($i = 3; $i <= 3; $i++){
                    $part_str = implode(', ', $all_part);

                    $part = $event->sheet->getDelegate()->getCell('D'.$i)->getDataValidation();
                    $part->setType(DataValidation::TYPE_LIST);
                    $part->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $part->setAllowBlank(false);
                    $part->setShowInputMessage(true);
                    $part->setShowErrorMessage(true);
                    $part->setShowDropDown(true);
                    $part->setFormula1('"' . $part_str . '"');
                }
            },
        ];
    }
}
