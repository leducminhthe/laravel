<?php

namespace Modules\DashboardTeacher\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Modules\Offline\Entities\OfflineCourse;
use App\Models\Categories\TrainingTeacher;
use Modules\Offline\Entities\OfflineSchedule;
use Carbon\Carbon;
use App\Models\Categories\TrainingLocation;
use Modules\TargetManager\Entities\TargetManager;
use Modules\TargetManager\Entities\TargetManagerParent;
use Modules\TargetManager\Entities\TargetManagerGroup;
use App\Models\Profile;
use Modules\Offline\Entities\OfflineNewTeacher;
use Modules\Offline\Entities\OfflineTeacherClass;
use App\Models\Categories\TrainingTeacherHistory;

class DashboardTeacherController extends Controller
{
    public function index()
    {
        $year = date('Y');
        $month = date('m');

        $trainingTeacher = TrainingTeacher::where('user_id', profile()->user_id)->first(['id']);
        $teacherClass = OfflineTeacherClass::query()
        ->join('el_offline_course as course', 'course.id', '=', 'el_offline_teacher_class.course_id')
        ->where('el_offline_teacher_class.teacher_id', $trainingTeacher->id)
        ->pluck('el_offline_teacher_class.class_id')
        ->toArray();

        $courseNotTaughtMonth = [];
        $monthNow = [];
        $classMonth = [];
        $timeTaughtMonth = [];
        $costTaughtMonth = [];

        $classNotTaught = OfflineSchedule::query()
        ->where(function($sub) {
            $sub->where('lesson_date', '>', date('Y-m-d'));
            $sub->orWhere('start_time', '>', date('H:i:s'));
        })
        ->whereNotIn('class_id', $teacherClass)
        ->whereYear('lesson_date', $year)
        ->groupBy('class_id')
        ->pluck('class_id')
        ->toArray();
        $totalClassNotTaught = count($classNotTaught);
        
        $totalClassTaught = count($teacherClass);

        $courseTaught = OfflineSchedule::whereIn('class_id', $teacherClass)->whereYear('lesson_date', $year)->groupBy('course_id')->pluck('course_id')->toArray();
        $countCourse = count($courseTaught);

        for ($i = 1; $i <= 12; $i++) { 
            if($i >= $month) {
                $query = OfflineSchedule::query();
                $query->whereNotIn('class_id', $teacherClass);
                $query->where(function($sub) {
                    $sub->where('lesson_date', '>', date('Y-m-d'));
                    $sub->orWhere('start_time', '>', date('H:i:s'));
                });
                $query->whereMonth('lesson_date', $i);
                $query->whereYear('lesson_date', $year);
                $query->groupBy('course_id');
                $courseNotTaughtMonth[] = $query->count();
                $monthNow[] = trans('ladashboard.month') . $i;
            }

            $class = [];
            $timeTaughts = 0;
            $cost = 0;
            $teacherHistorys = TrainingTeacherHistory::query()
            ->select([
                'history.cost',
                'history.num_hour',
                'history.class_id',
                'schedule.practical_teaching',
                'schedule.start_time',
                'schedule.end_time',
            ])->disableCache()
            ->from('el_training_teacher_history as history')
            ->join('el_offline_course as course', 'course.id', '=', 'history.course_id')
            ->join('offline_course_class as class', 'class.id', '=', 'history.class_id')
            ->join('el_offline_schedule as schedule', 'schedule.id', '=', 'history.schedule_id')
            ->where('history.teacher_id', $trainingTeacher->id)
            ->whereMonth('schedule.lesson_date', $i)
            ->whereYear('schedule.lesson_date', $year)
            ->get();
            foreach ($teacherHistorys as $key => $history) {
                if(!in_array($history->class_id, $class)) {
                    $class[] = $history->class_id;
                }
                $timeTaughts += $history->num_hour;
                $cost += $history->cost;
            }

            $classMonth[] = count($class);
            $timeTaughtMonth[] = $timeTaughts;
            $costTaughtMonth[] = $cost;
        }

        $totalTimeTaught = array_sum($timeTaughtMonth);
        $totalCostTaught = array_sum($costTaughtMonth);

        $profile = profile();
        $targetManagerParent = TargetManagerParent::where('year', $year)->first(['id']);
        $targetManagerGroup = TargetManagerGroup::query()
        ->where(function($sub) use ($profile) {
            $sub->orWhere('user_id', $profile->user_id);
            $sub->orWhere('title_id', $profile->title_id);
        })
        ->groupBy('target_manager_id')
        ->pluck('target_manager_id')
        ->toArray();

        $kpiHoursTeacher = TargetManager::whereIn('id', $targetManagerGroup)->sum('num_hour_teacher');
        $kpiCourseTeacher = TargetManager::whereIn('id', $targetManagerGroup)->sum('num_course_teacher');

        return view('dashboardteacher::index',[
            'courseNotTaughtMonth'   => array_values($courseNotTaughtMonth),
            'monthNow'          => array_values($monthNow),
            'timeTaughtMonth'   => array_values($timeTaughtMonth),
            'classMonth'        => array_values($classMonth),
            'costTaughtMonth'   => array_values($costTaughtMonth),
            'totalTimeTaught'   => $totalTimeTaught,
            'totalClassTaught'  => $totalClassTaught,
            'kpiHoursTeacher'   => $kpiHoursTeacher,
            'kpiCourseTeacher'  => $kpiCourseTeacher,
            'countCourse'       => $countCourse,
            'totalCostTaught'   => number_format($totalCostTaught),
            'totalClassNotTaught'  => $totalClassNotTaught,
        ]);
    }

    public function detail() {
        $trainingTeacher = TrainingTeacher::where('user_id', profile()->user_id)->first(['id']);
        return view('dashboardteacher::detail', [
            'trainingTeacher' => $trainingTeacher
        ]);
    }
}
