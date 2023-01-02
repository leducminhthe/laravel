<?php
namespace Modules\RegisterTrainingPlan\Exports;

use App\Models\Categories\Area;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingForm;
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
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class ExportTemplateRegisterSheet1 implements WithHeadings, ShouldAutoSize, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $total_percent = 0;
    protected $count = 0;

    public function __construct()
    {
    }

    public function headings(): array
    {
        return [
            [
                'STT',
                'Chuyên đề (*)',
                'Tên khoá học (Không nhập mặc định lấy tên Chuyên đề)',
                'Ngày bắt đầu (*)',
                'Ngày kết thúc (*)',
                'Hình thức (*) (1: Trực tuyến: 2: Tập trung)',
                'Loại hình đào tạo (*)',
                'Khu vực đào tạo (*)',
                'Khoá học dành cho (*) (1: Tân tuyển; 2: Hiện hữu)',
                'Mục tiêu',
                'Nội dung',
                'Thời lượng (Giờ)',
                'SL Học viên',
                'Khoá học thuộc (*) (1: Đào tạo nội bộ; 2: Đào tạo chéo)',
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:N1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:N10')->applyFromArray([
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

                $subject_count = Subject::where('status', '=', 1)->where('subsection', 0)->count();
                $areas_count = Area::where('status', '=', 1)->count();
                $training_forms_count = TrainingForm::count();

                for($i = 2; $i <= 10; $i++){

                    $subject = $event->sheet->getDelegate()->getCell('B'.$i)->getDataValidation();
                    $subject->setType(DataValidation::TYPE_LIST);
                    $subject->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $subject->setAllowBlank(false);
                    $subject->setShowInputMessage(true);
                    $subject->setShowErrorMessage(true);
                    $subject->setShowDropDown(true);
                    $subject->setFormula1('Sheet2!$A$1:$A$'.$subject_count);

                    $training_forms = $event->sheet->getDelegate()->getCell('G'.$i)->getDataValidation();
                    $training_forms->setType(DataValidation::TYPE_LIST);
                    $training_forms->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $training_forms->setAllowBlank(false);
                    $training_forms->setShowInputMessage(true);
                    $training_forms->setShowErrorMessage(true);
                    $training_forms->setShowDropDown(true);
                    $training_forms->setFormula1('Sheet3!$A$1:$A$'.$training_forms_count);

                    $areas = $event->sheet->getDelegate()->getCell('H'.$i)->getDataValidation();
                    $areas->setType(DataValidation::TYPE_LIST);
                    $areas->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $areas->setAllowBlank(false);
                    $areas->setShowInputMessage(true);
                    $areas->setShowErrorMessage(true);
                    $areas->setShowDropDown(true);
                    $areas->setFormula1('Sheet4!$A$1:$A$'.$areas_count);
                }

            },
        ];
    }
}
