<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\CourseRegisterView;
use App\Models\CourseView;
use App\Models\Categories\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ReportNew\Entities\BC40;
use function GuzzleHttp\json_decode;
use Carbon\Carbon;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineTeamsAttendanceReport;
use Modules\Online\Entities\OnlineCourseTimeUserLearn;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;

class BC40Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $report = parent::reportList();
        $reportGroupList = $this->reportGroupList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'reportList' => $reportGroupList,
        ]);
    }

    public function getData(Request $request)
    {
        $show = $request->show;
        $year = $request->year;
        $course_type = $request->course_type;
        $user_id = $request->user_id;
        $title_id = $request->title_id;
        $unit_id = $request->unit_id;

        if ($show == 0)
            json_result([]);

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC40::sql($year, $course_type, $user_id, $title_id, $unit_id);
        $count = $query->count();
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        $quizs = Quiz::where('quiz_type', 3)->pluck('id')->toArray();
        foreach ($rows as $row){
            if($row->end_date) {
                $row->course_time = get_date($row->start_date) . ' => ' . get_date($row->end_date);
            } else {
                $row->course_time = get_date($row->start_date);
            }

            if($row->course_type == 1) {
                $timeLearn = OnlineCourseTimeUserLearn::where(['user_id' => $row->user_id, 'course_id' => $row->id])->whereYear('created_at', $year)->sum('time');
                $row->type = 'Online';
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
                $queryTeams->whereYear('teams.created_at', $year);
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
                $queryOfflineAttendace->whereYear('offline_attendance.created_at', $year);
                $attendancesOffline = $queryOfflineAttendace->get();
                foreach($attendancesOffline as $attendance) {
                    $startLearnOffline = Carbon::parse($attendance->start_time);
                    $endLearnOffline = Carbon::parse($attendance->end_time);
                    $totalTime = $endLearnOffline->diffInSeconds($startLearnOffline);
                    $totalTimeLearn = $attendance->percent * $totalTime / 100;
                    $totalTimeLearnOffline = $totalTimeLearnOffline + $totalTimeLearn;
                }
                $timeLearn =  $totalTimeLearnOffline  + $totalTimeLearnTeamsOffline;

                $row->type = 'Tập trung';
            } else {
                $row->type = 'Kỳ thi';
                $quizResults = QuizAttempts::where('user_id', $row->user_id)->where('timefinish', '>', 0)->whereYear('created_at', $year)->whereIn('quiz_id', $quizs)->get(['timefinish', 'timestart']);
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
            $row->total_time = $hours . ":" . $minutes;
            $row->year = $year;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
