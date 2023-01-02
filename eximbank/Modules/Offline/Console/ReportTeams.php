<?php

namespace Modules\Offline\Console;

use App\Models\Profile;
use App\Traits\TeamsMeetingTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCourseActivityTeams;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineTeamsAttendanceReport;
use Modules\Offline\Entities\OfflineTeamsReport;

class ReportTeams extends Command
{
    use TeamsMeetingTrait;
    protected $signature = 'command:report_teams {course_id?} {schedule_id?}';

    protected $description = 'report teams sau khi tham dự chạy 10phut/lần (*/10 * * * *)';

    protected $expression = "*/10 * * * *";
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $param_course_id = $this->argument('course_id');
        $param_schedule_id = $this->argument('schedule_id');

        $maxRecord = 0;
        $processed = 0;
        if(isset($param_course_id) && isset($param_schedule_id)) {
            $model = OfflineCourseActivityTeams::where(['course_id' => $param_course_id, 'schedule_id' => $param_schedule_id, 'report' => 0])->get();
        } else {
            $model = OfflineCourseActivityTeams::where(['report' => 0])->get();
        }

        foreach ($model as  $course_teams) {
//            $report =  $this->getReport('MSo2Y2M1OWMyYS05MGRiLTQ4NGMtOTc2Yi01NWE5N2I0OTcyM2MqMCoqMTk6bWVldGluZ19OREptTURFeE9XVXROVEEwTmkwME1XWmxMV0UyT0dZdFpUSTVORGhoTVRGbE5tSTRAdGhyZWFkLnYy');
            $attendanceReports = $this->getAttendanceReportsMe($course_teams->teams_id);
            $tmp=0;
            if ($attendanceReports) {
                foreach ($attendanceReports['value'] as $index => $attendanceReport) {
                    $report_id = $attendanceReport['id'];
                    $report = $this->getReportMe($course_teams->teams_id,$report_id, $course_teams->user_teams_id);
                    if ($report) {
                        $meeting_start = Carbon::parse($this->convertDatetime($report['meetingStartDateTime']));
                        $meeting_end = Carbon::parse($this->convertDatetime($report['meetingEndDateTime']));
                        $totalDuration = $meeting_end->diffInSeconds($meeting_start);
                        $teamsReport = OfflineTeamsReport::firstOrNew(['teams_id' => $course_teams->teams_id,'report_id'=>$report_id]);
                        $teamsReport->course_id = $course_teams->course_id;
                        $teamsReport->class_id = $course_teams->class_id;
                        $teamsReport->schedule_id = $course_teams->schedule_id;
                        $teamsReport->teams_id = $course_teams->teams_id;
                        $teamsReport->report_id = $report_id;
                        $teamsReport->title = $course_teams->topic;
                        $teamsReport->total_participant = $report['totalParticipantCount'];
                        $teamsReport->meeting_start = $meeting_start;
                        $teamsReport->meeting_end = $meeting_end;
                        $teamsReport->duration = $totalDuration;
                        $teamsReport->save();
                        $attendace = $report['attendanceRecords'];
                        $maxRecord = $report['totalParticipantCount'];
                        foreach ($attendace as $item) {
                            $attendaceJoin = $item['attendanceIntervals'];
                            $profile = Profile::where(['email' => $item['emailAddress']])->first();
                            foreach ($attendaceJoin as $joinTime) {
                                $joinDateTime = $this->convertDatetime($joinTime['joinDateTime']);
                                $leaveDateTime = $this->convertDatetime($joinTime['leaveDateTime']);
                                $reportTeams = OfflineTeamsAttendanceReport::firstOrNew(['teams_id' => $course_teams->teams_id, 'user_teams_id' => $item['id'], 'report_id' => $report_id, 'join_time' => $joinDateTime]);
                                $reportTeams->course_id = $course_teams->course_id;
                                $reportTeams->class_id = $course_teams->class_id;
                                $reportTeams->schedule_id = $course_teams->schedule_id;
                                $reportTeams->teams_id = $course_teams->teams_id;
                                $reportTeams->report_id = $report_id;
                                $reportTeams->user_id = $profile ? $profile->id : null;
                                $reportTeams->user_teams_id = $item['id'];
                                $reportTeams->full_name = $item['identity']['displayName'];
                                $reportTeams->email = $item['emailAddress'];
                                $reportTeams->join_time = $joinDateTime;
                                $reportTeams->leave_time = $leaveDateTime;
                                $reportTeams->duration = $joinTime['durationInSeconds'];
                                $reportTeams->total_second = $item['totalAttendanceInSeconds'];
                                $reportTeams->role = $item['role'];
                                $reportTeams->save();
                            }
                            // cập nhật vào kết quả điểm danh
                            if ($profile) {
                                $user_id = $profile->id;
                                $register_id = OfflineRegister::where(['course_id' => $course_teams->course_id, 'user_id' => $user_id])->value('id');
                                if ($register_id) {
                                    $offlineSchedule = OfflineSchedule::find($course_teams->schedule_id);
                                    $model = OfflineAttendance::firstOrNew(['user_id' => $user_id, 'schedule_id' => $course_teams->schedule_id]);
                                    $totalDurations = OfflineTeamsReport::where(['course_id'=>$course_teams->course_id,'teams_id'=>$course_teams->teams_id])->sum('duration');
                                    $totalAttendanceInSeconds = OfflineTeamsAttendanceReport::where(['teams_id' => $course_teams->teams_id, 'user_teams_id' => $item['id']])->sum('duration');
                                    $percent = floor(($totalAttendanceInSeconds / $totalDurations) * 100);
                                    $model->percent = $percent;
                                    $model->status = $percent >= $offlineSchedule->condition_complete_teams ? 1 : 0;
                                    $model->course_id = $course_teams->course_id;
                                    $model->class_id = $course_teams->class_id;
                                    $model->schedule_id = $course_teams->schedule_id;
                                    $model->register_id = $register_id;
                                    $model->user_id = $user_id;
                                    $model->type = '3.Manual';
                                    $model->save();
                                    $processed++;
                                } else
                                    $processed++;
                            } else
                                $processed++;
                        }

                    }
                    $tmp++;
                }
            }
            // dd($tmp,$attendanceReports,$maxRecord,$processed);
            // && ($maxRecord && $processed == $maxRecord
            if ($tmp == count($attendanceReports['value']))
                OfflineCourseActivityTeams::where(['id' => $course_teams->id])->update(['report' => 1]);
        }
    }

    private function convertDatetime($dateTime)
    {
        $d = new \DateTime($dateTime);
        $d->modify('+ 7 hour');
        return $d->format('Y-m-d H:i:s');
    }
}
