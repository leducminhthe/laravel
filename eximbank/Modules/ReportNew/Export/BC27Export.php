<?php
namespace Modules\ReportNew\Export;

use App\Models\Config;
use App\Models\LogoModel;
use App\Models\Categories\TrainingCost;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Online\Entities\OnlineRegister;
use Modules\ReportNew\Entities\BC27;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC27Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $count_title = 6;
    protected $index_total = 6;

    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->course_type = $param->course_type;

        $this->training_cost = TrainingCost::query()->orderBy('type')->get();
    }

    public function query()
    {
        $query = BC27::sql($this->course_type, $this->from_date, $this->to_date)->orderBy('id', 'asc');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $obj = [];
        $this->index++;

        if ($row->course_type == 1){
            $num_user = OnlineRegister::whereCourseId($row->course_id)->whereStatus(1)->count();
            $course_time = '';
        }else{
            $num_user = OfflineRegister::whereCourseId($row->course_id)->whereStatus(1)->count();
            $course_time = OfflineSchedule::whereCourseId($row->course_id)->count();
        }

        $obj[] = $this->index;
        $obj[] = $row->name .' ('. $row->code .')';
        $obj[] = get_date($row->start_date) . ($row->end_date ? ' đến '. get_date($row->end_date) : '');
        $obj[] = $course_time;
        $obj[] = $num_user;

        foreach ($this->training_cost as $cost){
            if ($row->course_type == 1){
                $course_cost = OnlineCourseCost::whereCourseId($row->course_id)->whereCostId($cost->id)->first();
            }else{
                $course_cost = OfflineCourseCost::whereCourseId($row->course_id)->whereCostId($cost->id)->first();
            }

            $obj[] = isset($course_cost->actual_amount) ? number_format($course_cost->actual_amount, 2) : '0';
        }

        $obj[] = '';

        return $obj;
    }

    public function headings(): array
    {
        $title_arr = [];
        $title_arr[] = trans('latraining.stt');
        $title_arr[] = trans('ladashboard.subject');
        $title_arr[] =trans('latraining.training_time');
        $title_arr[] = trans('lareport.duration').' ('.trans('latraining.session').')';
        $title_arr[] = 'SL học viên';

        foreach ($this->training_cost as $cost){
            $title_arr[] = $cost->name;

            $this->count_title += 1;
        }

        $title_arr[] = trans('latraining.note');

        return [
            [],
            [],
            [],
            [],
            [],
            ['BÁO CÁO CHI PHÍ ĐÀO TẠO'],
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

                $columnName = $event->sheet->getDelegate()->getColumnDimensionByColumn($this->count_title);
                $char = $columnName->getColumnIndex();

                $event->sheet->getDelegate()->mergeCells('A6:'.$char.'6')
                    ->getStyle('A6')
                    ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:'.$char.'8')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:'.$char.(9 + $this->index))
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

                $event->sheet->getDelegate()->mergeCells('A'.(9 + $this->index).':E'.(9 + $this->index));
                $event->sheet->getDelegate()->setCellValue('A'.(9 + $this->index), 'Tổng chi phí');


                foreach ($this->training_cost as $cost){
                    $online_course_cost = OnlineCourseCost::whereCostId($cost->id)->sum('actual_amount');
                    $offline_course_cost = OfflineCourseCost::whereCostId($cost->id)->sum('actual_amount');

                    $columnName = $event->sheet->getDelegate()->getColumnDimensionByColumn($this->index_total);
                    $char = $columnName->getColumnIndex();

                    $event->sheet->getDelegate()->setCellValue($char.(9 + $this->index), ($online_course_cost + $offline_course_cost));

                    $this->index_total += 1;
                }
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
