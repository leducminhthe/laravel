<?php
namespace Modules\CoursePlan\Exports;

use App\Models\Categories\Area;
use App\Models\ProfileView;
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

class RegisterTrainingPlanOffline implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
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

        if($row->training_area_id){
            $training_area = Area::whereIn('id', json_decode($row->training_area_id))->pluck('code')->toArray();
            $row->training_area = implode(';', $training_area);
        }

        $teachers = '';
        if($row->teacher_id){
            $user_teacher = ProfileView::whereIn('user_id', json_decode($row->teacher_id))->pluck('code')->toArray();
            $teachers = implode(';', $user_teacher);
        }

        return [
            $this->index,
            $row->training_program_code,
            $row->subject_code,
            $row->name,
            get_date($row->start_date),
            get_date($row->end_date),
            '',
            $row->training_form_code,
            '',
            '',
            $row->max_student,
            '',
            $row->training_area,
            '',
            '',
            '',
            '',
            $row->course_employee,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            $teachers,
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
        $query->where('a.course_type', 2);
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
                'Tên khoá (*)',
                'Ngày bắt đầu (*)',
                'Ngày kết thúc (*)',
                'Hạn đăng ký',
                'Mã loại hình đào tạo (*)',
                'Điểm tối đa',
                'Điểm cần đạt',
                'Số HV tối đa',
                "Mã đối tượng tham gia (Mỗi đối tượng cách nhau dấu ;)",
	            "Mã khu vực đào tạo (*) (Mỗi đối tượng cách nhau dấu ;)",
                'Mã địa điểm đào tạo',
                "Mã đơn vị tổ chức (Mỗi đơn vị cách nhau dấu ;)",
                "Mã đơn vị phối hợp (Mỗi đơn vị cách nhau dấu ;)",
                'Mã loại GV',
                "Khoá học dành cho (*) (1: Tân tuyển; 2: Hiện hữu)",
                "Khoá học thực hiện (1: Kế hoạch; 2: Phát sinh)",
                'Tóm tắt',
                'Mô tả',
                'Bài học (Số bài)',
                'Mã mẫu chứng chỉ',
                'Mã kỳ thi',
                "Cam kết đào tạo (1: nếu có)",
                "Nhập ngày cam kết (ngày/tháng/năm)",
                'Nhập hệ số K',
                "Mã Giảng viên (Mỗi GV cách nhau dấu ;)",
                "Khoá học thuộc (*) (1: Đào tạo nội bộ; 2: Đào tạo chéo)",
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:AC1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:AC'.(1 + $this->count).'')->applyFromArray([
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
