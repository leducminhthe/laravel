<?php
namespace Modules\Offline\Exports;

use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineTeamsReport;
use Modules\Offline\Entities\OfflineTeamsAttendanceReport;

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

class ReportTeamsExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $total_percent = 0;
    protected $count = 0;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function map($row): array
    {
        $this->index++;
        return [
            $row->full_name,
            $row->email,            
            get_datetime($row->join_time),
            get_datetime($row->leave_time),
            gmdate("H:i:s", $row->duration),
            $row->role,
        ];
    }

    public function query(){
        $query = OfflineTeamsAttendanceReport::query();
        $query->where(['course_id' => $this->report->course_id, 'schedule_id' => $this->report->schedule_id, 'report_id' => $this->report->report_id]);
        $query->orderBy('id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        $course = OfflineCourse::find($this->report->course_id);
        $seconds = strtotime($this->report->meeting_end) - strtotime($this->report->meeting_start);
        $days    = floor($seconds / 86400);
        $hours   = floor(($seconds - ($days * 86400)) / 3600);
        $minutes = floor(($seconds - ($days * 86400) - ($hours * 3600))/60);
        $seconds = floor(($seconds - ($days * 86400) - ($hours * 3600) - ($minutes*60)));
        $duration = ($hours ? $hours. 'h ' : '') . ($minutes ? $minutes. 'm ' : '') . ($seconds ? $seconds. 's' : '');
        return [
            ['Báo cáo hoạt động teams khóa học ' . $course->name],
            [
                'Người tham dự: '. $this->report->total_participant
            ],
            [
                'Thời gian diễn ra: '. get_datetime($this->report->meeting_start) . ' - ' . get_datetime($this->report->meeting_end)
            ],
            [
                'Thời lượng: '. $duration
            ],
            [
                'Tên tham dự', 
                'Email', 
                'Thời gian vào', 
                'Thời gian rời', 
                'Thời lượng', 
                'Vai trò', 
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->mergeCells('A1:F1');
                $event->sheet->getDelegate()->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->mergeCells('A2:F2');
                $event->sheet->mergeCells('A3:F3');
                $event->sheet->mergeCells('A4:F4');

                $event->sheet->getDelegate()->getStyle('A5:F'.(5 + $this->count).'')->applyFromArray([
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