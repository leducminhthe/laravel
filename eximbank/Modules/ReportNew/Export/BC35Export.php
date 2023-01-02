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

use Modules\ReportNew\Entities\BC35;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Models\CourseView;
use App\Models\CourseRegisterView;
use App\Models\CourseComplete;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Online\Entities\OnlineCourseCost;

class BC35Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($param)
    {
        $this->course_type = $param->course_type;
        $this->subject_id = $param->subject_id;
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->status = $param->status;
    }

    public function query()
    {
        $query = BC35::sql($this->course_type, $this->subject_id, $this->from_date, $this->to_date, $this->status);
        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $date = date('Y-m-d');

        $countRegister = CourseRegisterView::where(['course_id' => $row->course_id, 'course_type' => $row->course_type])->count();
        $countComplete = CourseComplete::where(['course_id' => $row->course_id, 'course_type' => $row->course_type])->count();
        $rate_complete = round($countRegister > 0 ? ($countComplete/$countRegister)*100 : 0, 2) . '%';

        if($row->course_type == 1) {
            $name_course_type = 'Trực tuyến';
            $costCourse = OnlineCourseCost::where('course_id', $row->course_id)->sum('actual_amount');
        } else {
            $name_course_type = 'Offline';
            $costCourse = OfflineCourseCost::where('course_id', $row->course_id)->sum('actual_amount');
        }
        $actual_amount = number_format($costCourse, 2);

        if ($row->status == 0){
            $row->status_name = 'Chưa duyệt';
        } else if($row->status == 1 && ($row->end_date >= $date || empty($row->end_date)) && $row->lock_course == 0) {
            $row->status_name = 'Đã duyệt';
        } else if($row->status == 2) {
            $row->status_name = 'Từ chối';
        } else if(($row->start_date <= $date && $row->end_date >= $date) || ($row->start_date <= $date && empty($row->end_date))) {
            $row->status_name = 'Đang diễn ra';
        } else if($row->lock_course == 0 && $row->end_date <= $date) {
            $row->status_name = 'Chờ kiểm tra';
        } else if($row->lock_course == 1 && $row->end_date <= $date) {
            $row->status_name = 'Đã kết thúc';
        }

        $this->index++;
        $obj[] = $this->index;
        $obj[] = $row->code;
        $obj[] = $row->name;
        $obj[] = $name_course_type;
        $obj[] = $row->start_date;
        $obj[] = $row->end_date;
        $obj[] = $row->training_form_name;
        $obj[] = $row->status_name;
        $obj[] = (string) $countRegister;
        $obj[] = (string) $countComplete;
        $obj[] = $rate_complete;
        $obj[] = (string) $actual_amount;

        return $obj;
    }

    public function headings(): array
    {
        $title_arr = [];

        $title_arr[] = trans('latraining.stt');
        $title_arr[] = trans('lacourse.course_code');
        $title_arr[] = trans('lacourse.course_name');
        $title_arr[] = 'Hình thức đào tạo';
        $title_arr[] = 'Thời gian bắt đầu';
        $title_arr[] = 'Thời gian kết thúc';
        $title_arr[] = 'Loại hình đào tạo';
        $title_arr[] = trans('lareport.status');
        $title_arr[] = 'SLHV';
        $title_arr[] = 'SLHV đã hoàn thành';
        $title_arr[] = 'Tỷ lệ HV hoàn thành';
        $title_arr[] = 'Chi phí đào tạo';

        return [
            [],
            [],
            [],
            [],
            [],
            ['BÁO CÁO TÌNH HÌNH TỔ CHỨC ĐÀO TẠO E-LEARNING/TẬP TRUNG'],
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
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $event->sheet->getDelegate()->mergeCells('A6:L6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:L8')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:L'.(8 + $this->index))
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
