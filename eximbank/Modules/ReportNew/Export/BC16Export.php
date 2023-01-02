<?php
namespace Modules\ReportNew\Export;

use App\Models\Config;
use App\Models\LogoModel;
use App\Models\Categories\AbsentReason;
use App\Models\Categories\Area;
use App\Models\Categories\StudentCost;
use App\Models\Categories\TeacherType;
use App\Models\Categories\TrainingCost;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\TrainingTeacherHistory;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitType;
use App\Models\ProfileView;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\ReportNew\Entities\BC16;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Offline\Entities\OfflineTeacher;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC16Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($param)
    {
        $this->type = $param->type;
    }

    public function query()
    {
        $query = BC16::sql($this->type)->orderBy('id', 'asc');

        $this->count = $query->count();
        return $query;
    }

    private function getRank($teacher_id){
        $rank = [];
        $training_teacher = TrainingTeacher::whereStatus(1)->pluck('id')->toArray();
        foreach($training_teacher as $teacher){
            $history = TrainingTeacherHistory::where('teacher_id', $teacher)->sum('num_hour');

            $rank[$teacher] = $history;
        }
        arsort($rank);
        $i = 0;
        foreach($rank as $key => $value){
            $i += 1;
            $rank[$key] = $i;
        }

        return $rank[$teacher_id];
    }

    public function map($row): array
    {
        $this->index++;

        $profile_view = ProfileView::find($row->user_id);
        $partner = TrainingPartner::find($row->training_partner_id);
        $teacher_type = TeacherType::find($row->teacher_type_id);

        $row->title = $profile_view ? $profile_view->title_name : '';
        $row->teacher_type = $teacher_type ? $teacher_type->name : '';
        $row->created_time = get_date($row->created_at);
        $row->total_hour = TrainingTeacherHistory::where('teacher_id', $row->id)->sum('num_hour');
        $row->rank = $this->getRank($row->id);
        $row->num_course = OfflineTeacher::whereTeacherId($row->id)->count();
        $row->partner = $partner ? $partner->name : '';

        return [
            $this->index,
            $row->code,
            $row->name,
            $row->title,
            $row->teacher_type,
            $row->created_time,
            $row->total_hour,
            $row->rank,
            $row->num_course,
            $row->partner,
        ];
    }

    public function headings(): array
    {
        $title_arr = [];

        $title_arr[] = trans('latraining.stt');
        $title_arr[] = trans('lacategory.code');
        $title_arr[] = trans('lacategory.name');
        $title_arr[] = trans('lacategory.title');
        $title_arr[] = trans('latraining.teacher_type');
        $title_arr[] = trans('lareport.date_became_teacher');
        $title_arr[] = trans('lareport.total_teaching_hour');
        $title_arr[] = trans('lacategory.rank');
        $title_arr[] = trans('lareport.num_course_teacher');
        $title_arr[] = trans('lacategory.partner');

        return [
            [],
            [],
            [],
            [],
            [],
            [trans('lareport.report_title_16')],
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

                $event->sheet->getDelegate()->mergeCells('A6:J6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:J8')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:J'.(8 + $this->index))
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
