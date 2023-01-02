<?php

namespace Modules\DashboardUnit\Console;

use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Models\CourseView;
use App\Models\Profile;
use Illuminate\Console\Command;
use Modules\DashboardUnit\Entities\DashboardUnitOnlineCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseComplete;
use Modules\Online\Entities\OnlineCourseCondition;
use Modules\Online\Entities\OnlineRegister;

class DashboardUnitOnlineCourseCommand extends Command
{
    protected $signature = 'dashboard_unit_online_course:update {unit_id?}';
    protected $description = 'Cập nhật trình trạng hv trong khoá online (Dashboard đơn vị). Chạy lúc 22h (0 22 * * *)';
    protected $expression = '0 22 * * *';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $param_unit_id = $this->argument('unit_id');

        $units = Unit::whereStatus(1);
        if($param_unit_id){
            $units = $units->where('id', $param_unit_id);
        }
        $units = $units->get(['id', 'code']);

        foreach ($units as $unit){
            $unit_manager = UnitManager::whereUnitCode($unit->code)->first();
            if($unit_manager && $unit_manager->type_manager == 1){
                $allUnitId = [$unit->id];
            }else{
                $unit_child_arr = Unit::getArrayChild($unit->code);
                $allUnitId = array_merge([$unit->id], $unit_child_arr);
            }

            $userOfArrChild = Profile::whereIn('unit_id', $allUnitId)->pluck('user_id')->toArray();

            DashboardUnitOnlineCourse::where(['unit_id' => $unit->id])->delete();

            $online_course = OnlineCourse::where(['status' => 1, 'isopen' => 1, 'offline' => 0])->get(['id', 'start_date', 'end_date']);
            foreach($online_course as $course){
                $condition = OnlineCourseCondition::where('course_id', '=', $course->id)->first();
                $activity_condition = ($condition && $condition->activity) ? explode(',', $condition->activity) : [];

                //Tổng HV trong đơn vị ghi danh khoá học
                $register = OnlineRegister::where(['course_id' => $course->id])->whereStatus(1)->whereIn('user_id', $userOfArrChild)->count();

                //HV chưa học. Mới ghi danh vào được duyệt, chưa tham gia nên cột cron_complete = null
                $unlearned = OnlineRegister::where(['course_id' => $course->id])->whereStatus(1)->whereIn('user_id', $userOfArrChild)->whereNull('cron_complete')->count();

                //HV hoàn thành khoá học
                $completed = OnlineCourseComplete::where(['course_id' => $course->id])->whereIn('user_id', $userOfArrChild)->count();

                //HV đang học. Nằm trong bảng hoàn thành hoạt động khoá học (el_online_course_activity_completion) nhưng chưa đủ số hoạt động cần hoàn thành
                $studying = OnlineRegister::whereStatus(1)
                    ->whereIn('user_id', $userOfArrChild)
                    ->where('course_id', $course->id)
                    ->whereNotNull('cron_complete')
                    ->where(\DB::raw(1), '=', function($sub) use($activity_condition){
                        $sub->select(\DB::raw('CASE WHEN COUNT(activity_id) < '.count($activity_condition).' THEN 1 ELSE 0 END'))
                            ->from('el_online_course_activity_completion')
                            ->whereColumn('course_id', '=', 'el_online_register.course_id')
                            ->whereColumn('user_id', '=', 'el_online_register.user_id')
                            ->where('status', 1);
                    })
                    ->whereNotExists(function($sub){
                        $sub->select(\DB::raw(1))
                        ->from('el_online_course_complete')
                        ->whereColumn('course_id', '=', 'el_online_register.course_id')
                        ->whereColumn('user_id', '=', 'el_online_register.user_id');
                    })
                    ->count();

                //HV không hoàn thành
                $uncompleted = $register - ($unlearned + $completed + $studying);

                $model = DashboardUnitOnlineCourse::firstOrNew([
                    'unit_id' => $unit->id,
                    'course_id' => $course->id,
                ]);
                $model->unit_id = $unit->id;
                $model->unit_code = $unit->code;
                $model->course_id = $course->id;
                $model->total = $register;
                $model->unlearned = $unlearned;
                $model->studying = $studying;
                $model->completed = $completed;
                $model->uncompleted = $uncompleted;
                $model->start_date = $course->start_date;
                $model->end_date = $course->end_date;
                $model->save();
            }
        }
    }
}
