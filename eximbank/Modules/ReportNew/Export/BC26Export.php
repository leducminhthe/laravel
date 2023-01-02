<?php
namespace Modules\ReportNew\Export;

use App\Models\Config;
use App\Models\LogoModel;
use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Offline\Entities\OfflineCourse;
use Modules\ReportNew\Entities\BC26;
use Modules\ReportNew\Entities\ReportNewExportBC11;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Models\Categories\TrainingLocation;
use Modules\Offline\Entities\OfflineRegister;
use App\Models\Categories\TrainingTeacherStar;

class BC26Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->user_id = $param->user_id;
    }

    public function query()
    {
        $query = BC26::sql($this->from_date, $this->to_date, $this->user_id);
        $query->orderBy('user_code', 'asc');
        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $obj = [];
        $this->index++;
        $course_time = '';
        $course_time_unit_text = '';

        $traning_location = TrainingLocation::where('id', $row->training_location_id)->first(['name']);
        $row->traning_location = @$traning_location->name;

        if ($row->course_type == 2){
            $course = OfflineCourse::find($row->course_id);
            $course_time = $course->course_time;
            $course_time_unit = preg_replace("/[^a-z]/", '', $course->course_time_unit);

            switch ($course_time_unit){
                case 'day': $course_time_unit_text = 'Ngày'; break;
                case 'session': $course_time_unit_text = 'Buổi'; break;
                case 'hour': $course_time_unit_text = 'Giờ'; break;
            }
        }
        $row->course_time = $course_time . ' ' . $course_time_unit_text;

        $query = OfflineRegister::query();
        $query->where('course_id', $row->course_id);
        $query->where('class_id', $row->class_id);
        $query->where('status', 1);
        $num_student = $query->count();

        $num_user_rating = TrainingTeacherStar::where('teacher_id', $row->training_teacher_id)
            ->where('course_id', $row->course_id)
            ->where('course_type', 2)
            ->where('class_id', $row->class_id)
            ->count();
        $num_star = TrainingTeacherStar::where('teacher_id', $row->training_teacher_id)
            ->where('course_id', $row->course_id)
            ->where('course_type', 2)
            ->where('class_id', $row->class_id)
            ->sum('num_star');

        $num_star = (int)$num_star > 0 ? round($num_star/$num_user_rating, 1) : 0;

        $row->cost = '';
        if($num_student >= 15 && $num_star >= 3.5 && $row->practical_teaching) {
            $row->cost = number_format($row->cost_teacher_main * $row->practical_teaching, 2);
        }

        $obj[] = $this->index;
        $obj[] = $row->course_name .' ('. $row->course_code .')';
        $obj[] = get_date($row->start_date) .' => '. get_date($row->end_date);
        // $obj[] = $row->class_name;
        // $obj[] = get_date($row->schedule_start_time, 'H:i') .' => '. get_date($row->schedule_end_time, 'H:i');
        $obj[] = $row->user_code;
        $obj[] = $row->fullname;
        $obj[] = $row->traning_location;
        $obj[] = $row->unit_name_1;
        $obj[] = $row->unit_name_2;
        $obj[] = $row->account_number.' ';
        $obj[] = $row->num_hour;
        // $obj[] = number_format($row->cost_lecturer + ($row->cost_tuteurs ? $row->cost_tuteurs : 0), 2);
        $obj[] = $row->cost;
        $obj[] = '';

        return $obj;
    }

    public function headings(): array
    {
        $title_arr = [];
        $title_arr[] = trans('latraining.stt');
        $title_arr[] = trans('lamenu.course');
        $title_arr[] = trans('latraining.training_time');
        // $title_arr[] = trans('latraining.classroom');
        // $title_arr[] = trans('latraining.time');
        $title_arr[] = trans('latraining.employee_code');
        $title_arr[] = trans('lareport.lecture_name');
        $title_arr[] = trans('latraining.training_location');
        $title_arr[] = trans('lareport.unit_direct');
        $title_arr[] = trans('lareport.unit_management');
        $title_arr[] = trans('lacategory.account_number');
        $title_arr[] = trans('latraining.teaching_time');
        $title_arr[] = trans('lareport.total_fee');
        $title_arr[] = trans('latraining.note');

        return [
            [],
            [],
            [],
            [],
            [],
            ['BÁO CÁO THÙ LAO GIẢNG VIÊN'],
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
