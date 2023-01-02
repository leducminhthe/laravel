<?php

namespace Modules\DashboardUnit\Console;

use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Models\CourseView;
use Illuminate\Console\Command;
use Modules\DashboardUnit\Entities\DashboardUnitCountOnline;

class DashboardUnitCountOnlineCommand extends Command
{
    protected $signature = 'dashboard_unit_count_online:update {unit_id?}';
    protected $description = 'Cập nhật tổng số khoá online (Dashboard đơn vị). Chạy lúc 23h (0 23 * * *)';
    protected $expression = '0 23 * * *';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $prefix = \DB::getTablePrefix();
        $year = date('Y');

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

            $model = DashboardUnitCountOnline::firstOrNew([
                'unit_id' => $unit->id,
            ]);
            $model->unit_id = $unit->id;
            $model->unit_code = $unit->code;

            $query = CourseView::query();
            $query->select(['el_course_view.course_id', 'el_course_view.course_type']);
            $query->leftjoin('el_course_register_view as b', function ($sub){
                $sub->on('b.course_id','=','el_course_view.course_id');
                $sub->on('b.course_type','=','el_course_view.course_type');
                $sub->where('b.course_type', '=', 1);
            });
            $query->where('b.status', '=', 1);
            $query->where('el_course_view.course_type', '=', 1);
            $query->where('el_course_view.status', '=', 1);
            $query->where('el_course_view.isopen', '=', 1);
            $query->where('el_course_view.offline', '=', 0);
            $query->whereIn('b.unit_id', $allUnitId);
            $query->groupBy(['el_course_view.course_id', 'el_course_view.course_type']);

            $total_year = $query->get()->count();

            $model->total = $total_year;
            $model->year = $year;
            $model->save();
        }

    }
}
