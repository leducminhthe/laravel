<?php
namespace Modules\ReportNew\Export;

use App\Models\Config;
use App\Models\LogoModel;
use App\Models\Categories\Area;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\TrainingType;
use App\Models\Categories\Unit;
use App\Models\Categories\TrainingPartner;

use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\ReportNew\Entities\BC17;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC17Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $column = 1;
    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->area_id = $param->area_id;
        $this->title_id = $param->title_id;
        $this->unit_id = $param->unit_id;
        $this->training_type_id = $param->training_type_id;
    }

    public function query()
    {
        $query = BC17::sql($this->title_id, $this->unit_id,$this->area_id,$this->training_type_id, $this->from_date, $this->to_date)->orderBy('full_name', 'asc');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $offline = OfflineCourse::find($row->course_id);
        $unit_name = [];
        !empty($offline->training_unit) ? $training_unit = json_decode($offline->training_unit) : $training_unit = [];
        if($offline->training_unit_type == 0 && !empty($training_unit)) {
            $units = Unit::select(['id','name','code'])->where('status', '=', 1)->get();
            foreach ($units as $key => $unit) {
                if(in_array($unit->id, $training_unit)) {
                    $unit_name[] = $unit->name;
                }
            }
        } else if ($offline->training_unit_type == 1 && !empty($training_unit)) {
            $training_partners = TrainingPartner::get();
            foreach ($training_partners as $key => $training_partner) {
                if(in_array($training_partner->id, $training_unit)) {
                    $unit_name[] = $training_partner->name;
                }
            }
        }
        $row->training_unit = !empty($unit_name) ? implode(',',$unit_name) : '';

        $offline = OfflineCourse::find($row->course_id);
        $row->course_time = @$offline->course_time;
        $row->training_address = @TrainingLocation::find(@$offline->training_location_id)->name;

        $schedules = OfflineSchedule::query()
            ->select(['a.end_time', 'a.lesson_date'])
            ->from('el_offline_schedule as a')
            ->where('a.course_id', '=', $row->course_id)
            ->get();

        foreach ($schedules as $schedule){
            if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                $row->time_schedule .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
            }else{
                $row->time_schedule .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
            }
        }

        $obj = [];
        $this->index++;
        $obj[] = $this->index;
        $obj[]=$row->user_code;
        $obj[]=$row->full_name;
        $obj[]=$row->email;
        $obj[]=$row->phone;
        $obj[]=$row->area;
        // $obj[]=$row->unit1_code;
        $obj[]=$row->unit1_name;
        // $obj[]=$row->unit2_code;
        $obj[]=$row->unit2_name;
        // $obj[]=$row->unit3_code;
        // $obj[]=$row->unit3_name;
        $obj[]=$row->position_name;
        $obj[]=$row->titles_name;
        $obj[]=$row->training_program_name;
        $obj[]=$row->subject_name;
        $obj[]=$row->course_code;
        $obj[]=$row->course_name;
        $obj[]=$row->training_unit;
        $obj[]=$row->training_type;
        $obj[]=$row->training_address;
        $obj[]=$row->course_time;
        $obj[]=get_date($row->start_date);
        $obj[]=get_date($row->end_date);
        $obj[]=$row->time_schedule;
        $obj[]= number_format($row->cost_held, 2);
        $obj[]= number_format($row->cost_training, 2);
        $obj[]= number_format($row->cost_external, 2);
        $obj[]= number_format($row->cost_teacher, 2);
        $obj[]= number_format($row->cost_student, 2);
        $obj[]= number_format($row->cost_total, 2);
        $obj[]=$row->time_commit;
        $obj[]=get_date($row->from_time_commit).' - '.get_date($row->to_time_commit);
        $obj[]=$row->time_rest;
        $obj[]= number_format($row->cost_refund, 2);
        return $obj;
    }

    public function headings(): array
    {
        $title = Titles::find($this->title_id);
        $area = Area::find($this->area_id);
        $unit = Unit::find($this->unit_id);
        $trainingType = TrainingType::find($this->training_type_id);
        $colHeader= [
            trans('latraining.stt'),
            trans('latraining.employee_code '),
            trans('latraining.fullname'),
            'Email',
           trans('latraining.phone'),
           trans('lamenu.area'),
            // 'Mã đơn vị cấp 1',
          trans('lareport.unit_direct'),
            // 'Mã đơn vị cấp 2',
          trans('lareport.unit_management'),
            // 'Mã đơn vị cấp 3',
            // 'Đơn vị cấp 3',
            'Chúc vụ',
            trans('latraining.title'),
            'Tên chương trình',
           trans('laprofile.subject_name'),
            trans('lacourse.course_code'),
            trans('lacourse.course_name'),
            trans('lareport.training_unit'),
            'Hình thức đào tạo',
            'Địa điểm đào tạo',
            trans('lareport.duration'),
            trans('latraining.from_date'),
            trans('latraining.to_date'),
            trans('lareport.time'),
            trans('lareport.average_fee_open'),
            trans('lareport.average_fee_training_department'),
            trans('lareport.average_fee_outside'),
           trans('lareport.average_fee_lecture'),
            trans('latraining.student_cost'),
            trans('lareport.total_cost'),
            trans('latraining.coimmitted_date'),
            trans('latraining.coimmitted_time'),
           trans('lareport.total_cost'),
            trans('latraining.reimbursement_costs').' (VND)'
        ];
        return [
            [],
            [],
            [],
            [],
            [],
            ['DANH SÁCH XÁC NHẬN BỒI HOÀN CHI PHÍ ĐÀO TẠO ĐỐI VỚI CBNV TÂN TUYỂN'],
            [trans('lareport.time').' : '. (($this->from_date && $this->to_date) ? get_date($this->from_date).' - '.get_date($this->to_date):'')],
            [ trans('latraining.title').' : '.@$title->name],
            ['Vùng : '.@$area->name],
            [trans('latraining.unit').' : '.@$unit->name],
            ['Loại hình đào tạo : '.@$trainingType->name],
            [],
            $colHeader
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

                $event->sheet->getDelegate()->mergeCells('A6:Z6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A13:AE13')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A13:AE'.(13 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ])->getAlignment()->setWrapText(true);
            },

        ];
    }
    public function startRow(): int
    {
        return 12;
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
