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
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\ReportNew\Entities\BC09;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC09Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($param)
    {
        $this->training_area_id = $param->area_id;
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->training_type_id = $param->training_type_id;
        $this->title_id = $param->title_id;
        $this->unit_id = isset($param->unit_id) ? $param->unit_id : null;
    }

    public function query()
    {
        $query = BC09::sql($this->training_area_id, $this->from_date, $this->to_date, $this->training_type_id, $this->title_id, $this->unit_id)->orderBy('el_report_new_export_bc05.id', 'asc');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $this->index++;

        $time_schedule = '';
        if ($row->course_type == 2){
            $offline = OfflineCourse::find($row->course_id);
            $row->course_time = @$offline->course_time;

            $schedules = OfflineSchedule::query()
                ->select(['a.end_time', 'a.lesson_date'])
                ->from('el_offline_schedule as a')
                ->where('a.course_id', '=', $row->course_id)
                ->get();
            foreach ($schedules as $schedule){
                if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                    $time_schedule .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
                }else{
                    $time_schedule .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
                }
            }
        }

        return [
            $this->index,
            $row->user_code,
            $row->fullname,
            // $row->unit_code_1,
            $row->area_name,
            $row->unit_name_1,
            // $row->unit_code_2,
            $row->unit_name_2,
            // $row->unit_code_3,
            $row->unit_type,
            $row->position_name,
            $row->title_name,
            $row->course_code,
            $row->course_name,
            $row->class_name,
            $row->course_time,
            get_date($row->start_date),
            get_date($row->end_date),
            $time_schedule,
            $row->training_area_name,
            $row->result == 1 ? 'Đạt' : 'Không đạt',
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
            ['THỐNG KÊ TÌNH HÌNH ĐÀO TẠO NHÂN VIÊN TÂN TUYỂN'],
            [],
            [
                trans('latraining.stt'),
                trans('latraining.employee_code'),
                trans('latraining.fullname'),
                trans('lamenu.area'),
                // 'Mã đơn vị cấp 1',
                trans('lareport.unit_direct'),
                // 'Mã đơn vị cấp 2',
               trans('lareport.unit_management'),
                // 'Mã đơn vị cấp 3',
                // 'Đơn vị cấp 3',
                'Loại đơn vị',
               trans('laprofile.position'),
                trans('latraining.title'),
                trans('lacourse.course_code'),
                trans('lacourse.course_name'),
                trans('latraining.classroom'),
                trans('lareport.duration'),
                trans('latraining.from_date'),
                trans('latraining.to_date'),
                trans('lareport.time'),
                'Vùng',
                'Kết quả',
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

                $event->sheet->getDelegate()->mergeCells('A6:R6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:R8')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:R'.(8 + $this->index))
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
