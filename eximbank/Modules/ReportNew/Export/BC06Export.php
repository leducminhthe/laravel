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
use Modules\ReportNew\Entities\BC06;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\Unit;
use Modules\Quiz\Entities\QuizResult;

class BC06Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($param)
    {
        $this->joined = $param->joined;
        $this->subject_id = $param->subject_id;
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->training_type_id = $param->training_type_id;
        $this->title_id = $param->title_id;
        $this->unit_id = isset($param->unit_id) ? $param->unit_id : null;
        $this->area_id = $param->area;
    }

    public function query()
    {
        $query = BC06::sql($this->joined, $this->subject_id, $this->from_date, $this->to_date, $this->training_type_id, $this->title_id, $this->unit_id, $this->area_id)->orderBy('el_report_new_export_bc05.id', 'asc');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $this->index++;
        $status_user = '';
        switch ($row->status_user) {
            case 0:
                $status_user = trans('backend.inactivity'); break;
            case 1:
                $status_user = trans('backend.doing'); break;
            case 2:
                $status_user = trans('backend.probationary'); break;
            case 3:
                $status_user = trans('backend.pause'); break;
        }
        $time_schedule = '';
        if ($row->course_type == 2){
            $offline = OfflineCourse::find($row->course_id);
            $row->course_time = $offline->course_time;
            $unit_name = [];
            !empty($offline->training_unit) ? $training_unit = json_decode($offline->training_unit) : $training_unit = [];
            if($offline->training_unit_type == 0 && !empty($training_unit)) {
                $units = Unit::where('status', '=', 1)->get();
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

            $register = OfflineRegister::whereCourseId($row->course_id)->where('user_id', '=', $row->user_id)->first();
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
            if($offline->entrance_quiz_id){
                $entrance_quiz_result = QuizResult::whereQuizId($offline->entrance_quiz_id)->where('user_id', $row->user_id)->where('type', 1)->first();
                if($entrance_quiz_result){
                    $row->entrance_quiz = isset($entrance_quiz_result->reexamine) ? $entrance_quiz_result->reexamine : (isset($entrance_quiz_result->grade) ? $entrance_quiz_result->grade : 0);
                }
            }
        }

        return [
            $this->index,
            $row->course_code,
            $row->course_name,
            $row->user_code,
            $row->fullname,
            $row->email,
            $row->phone,
            $row->area_name_unit,
            // $row->area,
            // $row->unit_code_1,
            $row->unit_name_1,
            // $row->unit_code_2,
            $row->unit_name_2,
            // $row->unit_code_3,
            // $row->unit_name_3,
            $row->position_name,
            $row->title_name,
            $row->training_unit,
            $row->training_type_name,
            $row->course_time,
            $row->attendance,
            get_date($row->start_date),
            get_date($row->end_date),
            $time_schedule,
            $row->entrance_quiz,
            $row->score,
            $row->result == 1 ? 'Đạt' : 'Không đạt',
            $status_user,
            $row->result == 1 ? 'Tham gia' : 'Chưa tham gia',
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
            ['DANH SÁCH HỌC VIÊN CỦA ĐƠN VỊ THEO CHUYÊN ĐỀ'],
            [],
            [
                trans('latraining.stt'),
                trans('lacourse.course_code'),
                trans('lacourse.course_name'),
                trans('latraining.employee_code '),
                trans('latraining.fullname'),
                'Email',
                trans('latraining.phone'),
                trans('lamenu.area'),
                // 'Vùng',
                // 'Mã đơn vị cấp 1',
              trans('lareport.unit_direct'),
                // 'Mã đơn vị cấp 2',
               trans('lareport.unit_management'),
                // 'Mã đơn vị cấp 3',
                // 'Đơn vị cấp 3',
               trans('laprofile.position'),
                 trans('latraining.title'),
                trans('lareport.training_unit'),
                'Loại hình đào tạo',
                trans('lareport.duration'),
                'Tổng thời lượng tham gia',
                trans('latraining.from_date'),
                trans('latraining.to_date'),
                trans('latraining.training_time'),
                'Thi đầu vào',
                'Điểm',
                'Kết quả',
                trans('lareport.status '),
                'Tham gia/Chưa tham gia',
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

                $event->sheet->getDelegate()->mergeCells('A6:W6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:W8')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:W'.(8 + $this->index))
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
