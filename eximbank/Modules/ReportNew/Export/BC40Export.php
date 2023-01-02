<?php
namespace Modules\ReportNew\Export;

use App\Models\Config;
use App\Models\LogoModel;

use Carbon\Carbon;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineTeamsAttendanceReport;
use Modules\Online\Entities\OnlineCourseTimeUserLearn;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;

use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\ReportNew\Entities\BC40;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC40Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $column = 1;
    public function __construct($param)
    {
        $this->quizs = Quiz::where('quiz_type', 3)->pluck('id')->toArray();
        $this->course_type = $param->course_type;
        $this->user_id = $param->user_id;
        $this->title_id = $param->title_id;
        $this->unit_id = $param->unit_id;
        $this->year = $param->year;
    }

    public function query()
    {
        $query = BC40::sql($this->year, $this->course_type, $this->user_id, $this->title_id, $this->unit_id);
        $this->count = $query->count();
        return $query;
    }

    public function map($row): array
    {
        if($row->end_date) {
            $course_time = get_date($row->start_date) . ' => ' . get_date($row->end_date);
        } else {
            $course_time = get_date($row->start_date);
        }

        if($row->course_type == 1) {
            $timeLearn = OnlineCourseTimeUserLearn::where(['user_id' => $row->user_id, 'course_id' => $row->id])->whereYear('created_at', $this->year)->sum('time');
            $type = 'Online';
        } else if ($row->course_type == 2) {
            $checkTeams = [];
            $totalTimeLearnTeamsOffline = 0;
            $queryTeams = OfflineTeamsAttendanceReport::query();
            $queryTeams->select([
                'teams.schedule_id',
                'teams.total_second',
                'offline_schedule.start_time',
                'offline_schedule.end_time',
                'offline_schedule.condition_complete_teams',
            ]);
            $queryTeams->from('offline_teams_attendance_report as teams');
            $queryTeams->join('el_offline_schedule as offline_schedule', 'offline_schedule.id', '=', 'teams.schedule_id');
            $queryTeams->whereYear('teams.created_at', $this->year);
            $queryTeams->where('teams.user_id', $row->user_id);
            $queryTeams->where('teams.course_id', $row->id);
            $timeLearnTeams = $queryTeams->get();
            foreach($timeLearnTeams as $timeLearn) {
                $startLearnOfflineTeam = Carbon::parse($timeLearn->start_time);
                $endLearnOfflineTeam = Carbon::parse($timeLearn->end_time);
                $totalTimeTeam = $endLearnOfflineTeam->diffInSeconds($startLearnOfflineTeam);
                $calculateTimeTeam = ($timeLearn->total_second / $totalTimeTeam) * 100;
                if(round((int)$calculateTimeTeam, 0) > (int) $timeLearn->condition_complete_teams) {
                    $totalTimeLearnTeamsOffline += $timeLearn->total_second;
                }
                $checkTeams[] = $timeLearn->schedule_id;
            }
            
            // TỔNG THỜI HỌC, ĐIỂM DANH KHÓA OFFLINE
            $totalTimeLearnOffline = 0;
            $queryOfflineAttendace = OfflineAttendance::query();
            $queryOfflineAttendace->select([
                'offline_attendance.schedule_id',
                'offline_attendance.percent',
                'offline_schedule.start_time',
                'offline_schedule.end_time',
            ]);
            $queryOfflineAttendace->from('el_offline_attendance as offline_attendance');
            $queryOfflineAttendace->join('el_offline_schedule as offline_schedule', 'offline_schedule.id', '=', 'offline_attendance.schedule_id');
            $queryOfflineAttendace->whereNotIn('schedule_id', $checkTeams);
            $queryOfflineAttendace->where(['offline_attendance.user_id' => $row->user_id, 'status' => 1, 'offline_attendance.course_id' => $row->id]);
            $queryOfflineAttendace->whereYear('offline_attendance.created_at', $this->year);
            $attendancesOffline = $queryOfflineAttendace->get();
            foreach($attendancesOffline as $attendance) {
                $startLearnOffline = Carbon::parse($attendance->start_time);
                $endLearnOffline = Carbon::parse($attendance->end_time);
                $totalTime = $endLearnOffline->diffInSeconds($startLearnOffline);
                $totalTimeLearn = $attendance->percent * $totalTime / 100;
                $totalTimeLearnOffline = $totalTimeLearnOffline + $totalTimeLearn;
            }
            $timeLearn =  $totalTimeLearnOffline  + $totalTimeLearnTeamsOffline;

            $type = 'Tập trung';
        } else {
            $type = 'Kỳ thi';
            $quizResults = QuizAttempts::where('user_id', $row->user_id)->where('timefinish', '>', 0)->whereYear('created_at', $this->year)->whereIn('quiz_id', $this->quizs)->get(['timefinish', 'timestart']);
            $totalTimeQuiz = 0;
            foreach($quizResults as $quizResult) {
                $timeFinishQuiz = date('Y-m-d H:i:s', $quizResult->timefinish);
                $timeStartQuiz = date('Y-m-d H:i:s', $quizResult->timestart);
                $startQuiz = Carbon::parse($timeStartQuiz);
                $endQuiz = Carbon::parse($timeFinishQuiz);
                $calculateTimeQuiz = $endQuiz->diffInSeconds($startQuiz);
                $totalTimeQuiz +=  $calculateTimeQuiz;
            }
            $timeLearn = $totalTimeQuiz;
        }

        $hours = floor($timeLearn / 3600);
        $minutes = floor(($timeLearn / 60) % 60);
        $total_time = $hours . ":" . $minutes;

        $obj = [];
        $this->index++;
        $obj[] = $this->index;
        $obj[] = $row->name;
        $obj[] = $row->code;
        $obj[] = $course_time;
        $obj[] = $type;
        $obj[] = $row->full_name;
        $obj[] = $row->unit_name;
        $obj[] = $row->title_name;
        $obj[] = $total_time;
        $obj[] = $this->year;

        return $obj;
    }

    public function headings(): array
    {
        $colHeader= [
            trans('latraining.stt'),
            trans('lamenu.course'),
            trans('latraining.course_code'),
            trans('ladashboard.time'),
            trans('lacategory.form'),
            trans('latraining.fullname'),
            trans('latraining.unit'),
            trans('latraining.title'),
            trans('lareport.spend_learned_summary'),
            trans('lanote.year')
        ];
        return [
            [],
            [],
            [],
            [],
            [],
            ['BÁO CÁO CHI TIẾT TỔNG GIỜ HỌC CỦA HỌC VIÊN THEO KHÓA HỌC'],
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
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
}
