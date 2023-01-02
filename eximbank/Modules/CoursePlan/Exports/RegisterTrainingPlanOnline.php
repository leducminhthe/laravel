<?php
namespace Modules\CoursePlan\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\RegisterTrainingPlan\Entities\RegisterTrainingPlan;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class RegisterTrainingPlanOnline implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $total_percent = 0;
    protected $count = 0;

    public function __construct()
    {
    }

    public function map($row): array
    {
        $this->index++;

        return [
            $this->index,
            $row->training_program_code,
            $row->subject_code,
            $row->name,
            get_date($row->start_date),
            $row->end_date ? get_date($row->end_date) : '',
            '',
            '',
            '',
            '',
            $row->training_form_code,
            '',
            '',
            '',
            '',
            '',
            '0',
            '',
            '',
            $row->course_belong_to,
        ];
    }

    public function query(){
        $query = RegisterTrainingPlan::query();
        $query->select([
            'a.*',
            'b.code as training_program_code',
            'c.code as subject_code',
            'd.code as training_form_code',
        ]);
        $query->from('el_register_training_plan as a');
        $query->leftJoin('el_training_program as b', 'b.id', '=', 'a.training_program_id');
        $query->leftJoin('el_subject as c', 'c.id', '=', 'a.subject_id');
        $query->leftJoin('el_training_form as d', 'd.id', '=', 'a.training_form_id');
        $query->where('a.course_type', 1);
        $query->where('a.send', 1);
        $query->where('a.status', 1);
        $query->orderBy('a.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            [
                'STT',
                'Mã chủ đề (*)',
                'Mã chuyên đề (*)',
                'Tên lớp (*)',
                "Ngày bắt đầu (*) (ngày/tháng/năm)",
                "Ngày kết thúc (ngày/tháng/năm)",
                "Hạn đăng ký (ngày/tháng/năm)",
                "Giới hạn thời gian học (1: Nếu có)",
                "Thời gian bắt đầu (Giờ:phút)",
                "Thời gian kết thúc (Giờ:phút)",
                'Mã loại hình đào tạo (*)',
                'Điểm tối đa',
                'Điểm cần đạt',
                "Mã đối tượng tham gia (Mỗi đối tượng cách nhau dấu ;)",
                'Tóm tắt',
                'Mô tả',
                "Duyệt khoá (*) (0: Bắt buộc; 2: Tự động)",
                'Bài học (Số bài)',
                'Mã mẫu chứng chỉ',
                "Khoá học thuộc (*) (1: Đào tạo nội bộ; 2: Đào tạo chéo)",
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:T1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:T'.(1 + $this->count).'')->applyFromArray([
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

            },
        ];
    }
}
