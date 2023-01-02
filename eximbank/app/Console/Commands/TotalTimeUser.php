<?php

namespace App\Console\Commands;

use App\Models\TotalTimeUserLearnInYear;
use App\Models\TotalTimeHistoryUser;
use App\Models\ProfileView;
use Illuminate\Console\Command;
use Modules\Online\Entities\OnlineCourseActivityHistory;
use Modules\Online\Entities\OnlineCourseTimeUserLearn;
use Carbon\Carbon;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Offline\Entities\OfflineTeamsAttendanceReport;

class TotalTimeUser extends Command
{
    protected $signature = 'totalTimeUser:update';
    protected $description = 'update tổng thời gian học của HV trong năm chạy 10p 1 lần (*/10 * * * *)';
    protected $expression ='*/10 * * * *';
   
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $year = date('Y');
        $data = [];
        $allUser = ProfileView::where('status_id', 1)->get(['user_id', 'full_name', 'unit_id', 'unit_name', 'title_id', 'title_name']);
        $quizs = Quiz::where('quiz_type', 3)->pluck('id')->toArray();
        foreach ($allUser as $key => $user) {
            // TỔNG THỜI GIAN HỌC KHÓA ONLINE
            $totalTimeLearnOnline = OnlineCourseTimeUserLearn::where('user_id', $user->user_id)->whereYear('created_at', $year)->sum('time');

            // THỜI GIAN HỌC TEAMS
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
            $queryTeams->where('teams.user_id', $user->user_id);
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
            $queryOfflineAttendace->where(['offline_attendance.user_id' => $user->user_id, 'status' => 1]);
            $queryOfflineAttendace->whereYear('offline_attendance.created_at', $year);
            $attendancesOffline = $queryOfflineAttendace->get();
            foreach($attendancesOffline as $attendance) {
                $startLearnOffline = Carbon::parse($attendance->start_time);
                $endLearnOffline = Carbon::parse($attendance->end_time);
                $totalTime = $endLearnOffline->diffInSeconds($startLearnOffline);
                $totalTimeLearn = $attendance->percent * $totalTime / 100;
                $totalTimeLearnOffline = $totalTimeLearnOffline + $totalTimeLearn;
            }

            // TỔNG THỜI GIAN THAM GIA KỲ THI
            $quizResults = QuizAttempts::where('user_id', $user->user_id)->where('timefinish', '>', 0)->whereYear('created_at', $year)->whereIn('quiz_id', $quizs)->get(['timefinish', 'timestart']);
            $totalTimeQuiz = 0;
            foreach($quizResults as $quizResult) {
                $timeFinishQuiz = date('Y-m-d H:i:s', $quizResult->timefinish);
                $timeStartQuiz = date('Y-m-d H:i:s', $quizResult->timestart);
                $startQuiz = Carbon::parse($timeStartQuiz);
                $endQuiz = Carbon::parse($timeFinishQuiz);
                $calculateTimeQuiz = $endQuiz->diffInSeconds($startQuiz);
                $totalTimeQuiz +=  $calculateTimeQuiz;
            }
            
            $time = $totalTimeLearnOnline + $totalTimeLearnOffline + $totalTimeQuiz + $totalTimeLearnTeamsOffline;
            $hours = floor($time / 3600);
            $minutes = floor(($time / 60) % 60);
            $totalTimeLearnInYear = $hours . ":" . $minutes;

            $checkHistoryTime = TotalTimeHistoryUser::where('user_id', $user->user_id)->sum('time_second');

            $data[] = [
                'user_id'   => $user->user_id,
                'full_name' => $user->full_name,
                'unit_id'   => $user->unit_id,
                'unit_name'   => $user->unit_name,
                'title_id'   => $user->title_id,
                'title_name'   => $user->title_name,
                'total_time'   => $totalTimeLearnInYear,
                'time_second'   => (int)$totalTimeLearnOnline + (int)$totalTimeLearnOffline + (int)$totalTimeQuiz + (int)$totalTimeLearnTeamsOffline,
                'title_time_new'   => ((int)$totalTimeLearnOnline + (int)$totalTimeLearnOffline + (int)$totalTimeQuiz + (int)$totalTimeLearnTeamsOffline) - (int)$checkHistoryTime,
                'year'   => $year,
            ];
        }
        TotalTimeUserLearnInYear::truncate();
        foreach (array_chunk($data,500) as $data_chunk) {
            \DB::table('el_total_time_user_learn_year')->insert($data_chunk);
        }
    }
}
