<?php
namespace Modules\Online\Exports;

use App\Models\Certificate;
use App\Models\ProfileView;
use Modules\Online\Entities\OnlineResult;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRegister;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Rating\Entities\RatingCourse;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RegisterExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $total_percent = 0;
    protected $count = 0;

    public function __construct($course_id)
    {
        $this->course_id = $course_id;
    }

    public function map($result): array
    {
        $this->index++;

        return [
            $this->index,
            $result->code,
            $result->full_name,
            $result->title_name,
            $result->unit_name,
            $result->parent_unit_name,
            get_date($result->join_company, 'd/m/Y'),
            $result->gender == 1 ? 'Nam' : 'Nữ',
            get_date($result->dob, 'd/m/Y'),
            $result->phone,
            $result->email,
            $result->certificate_name,
            $result->register_form == 1 ? 'HVĐK' : 'QTGD',
        ];
    }

    public function query(){
        $query = OnlineRegister::query();
        $query->select([
           'el_online_register.id',
           'el_online_register.register_form',
           'b.*',
        ]);
        $query->leftJoin('el_profile_view as b', 'b.user_id', '=', 'el_online_register.user_id');
        // $query->where('el_online_register.status', '=', 1);
        $query->where('el_online_register.course_id', '=', $this->course_id);
        $query->where('el_online_register.user_id', '>', 2);
        $query->orderBy('el_online_register.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        $course = OnlineCourse::find($this->course_id);
        return [
            ['Danh sách ghi danh khóa học ' . $course->name],
            [
                trans('latraining.stt'),
                trans('latraining.employee_code '),
                trans('latraining.fullname'),
               trans('latraining.title'),
                'Đơn vị công tác',
                'Đơn vị quản lý',
                'Ngày vào làm',
                'Giới tính',
                'Ngày sinh',
                'Số điện thoại',
                'Email',
                'Trình độ',
                'Hình thức ghi danh',
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->mergeCells('A1:M1');
                $event->sheet->getDelegate()->getStyle('A1:M1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:M'.(2 + $this->count).'')->applyFromArray([
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
