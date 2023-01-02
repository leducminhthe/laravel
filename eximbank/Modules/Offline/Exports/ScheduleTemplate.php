<?php
namespace Modules\Offline\Exports;

use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineTeacher;
use App\Models\Categories\TrainingTeacher;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ScheduleTemplate implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 1;

    public function __construct($course_id)
    {
        $this->course_id = $course_id;
    }

    public function map($result): array
    {
        return [];
    }

    public function query(){
        $query= OfflineTeacher::query();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Mẫu import lịch học'],
            [
                trans('latraining.stt'),
                trans('latraining.class_room_code'),
                trans('latraining.start_date') . ' (d/m/Y)',
                trans('laother.start_time') . ' ('.trans('laother.hours'). ':' . trans('latraining.minute') .')',
                trans('laother.end_time') . ' ('.trans('laother.hours'). ':' . trans('latraining.minute') .')',
                trans('latraining.main_lecturer') . ' ('.trans('lasetting.code').')',
                trans('latraining.tutors') . ' ('.trans('lasetting.code').')',
                trans('latraining.cost_hour') . ' ('.trans('latraining.main_lecturer').')',
                trans('latraining.cost_hour') . ' ('.trans('latraining.tutors').')',
                trans('latraining.training_location') . ' ('.trans('lasetting.code').')',
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->mergeCells('A1:J1');
                $event->sheet->getDelegate()->getStyle('A1:J1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:J'.(1 + $this->count).'')->applyFromArray([
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

                // $teacherCourse = OfflineTeacher::where('course_id', $this->course_id)->pluck('teacher_id')->toArray();
                // $trainingTeachers = TrainingTeacher::whereIn('id', $teacherCourse)->pluck('name')->toArray();

                // for($i = 3; $i <= (2 + $this->count); $i++){
                //     $trainingTeachers = implode(', ', $trainingTeachers);

                //     $teacher = $event->sheet->getDelegate()->getCell('F')->getDataValidation();
                //     $teacher->setType(DataValidation::TYPE_LIST);
                //     $teacher->setErrorStyle(DataValidation::STYLE_INFORMATION);
                //     $teacher->setAllowBlank(false);
                //     $teacher->setShowInputMessage(true);
                //     $teacher->setShowErrorMessage(true);
                //     $teacher->setShowDropDown(true);
                //     $teacher->setFormula1('"' . $trainingTeachers . '"');

                //     $tutor = $event->sheet->getDelegate()->getCell('G')->getDataValidation();
                //     $tutor->setType(DataValidation::TYPE_LIST);
                //     $tutor->setErrorStyle(DataValidation::STYLE_INFORMATION);
                //     $tutor->setAllowBlank(false);
                //     $tutor->setShowInputMessage(true);
                //     $tutor->setShowErrorMessage(true);
                //     $tutor->setShowDropDown(true);
                //     $tutor->setFormula1('"' . $trainingTeachers . '"');
                // }
            },
        ];
    }
}
