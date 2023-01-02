<?php

namespace Modules\DashboardUnit\Console;

use App\Models\Categories\Unit;
use App\Models\CourseView;
use Illuminate\Console\Command;
use Modules\DashboardUnit\Entities\DashboardUnitCountUserByOffline;

class DashboardUnitCountUserByOfflineCommand extends Command
{
    protected $signature = 'dashboard_unit_count_user_by_offline:update';
    protected $description = 'Cập nhật tổng số CBNV KH Offline (Dashboard đơn vị). Chạy lúc 23h (0 23 * * *)';
    protected $expression = '0 23 * * *';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $prefix = \DB::getTablePrefix();
        $year = date('Y');

        $units = Unit::whereStatus(1)->get(['id', 'code']);
        foreach ($units as $unit){
            $unit_child_arr = Unit::getArrayChild($unit->code);

            $model = DashboardUnitCountUserByOffline::firstOrNew([
                'unit_id' => $unit->id,
                'year' => $year
            ]);
            $model->unit_id = $unit->id;
            $model->unit_code = $unit->code;

            $total_year = 0;
            for ($i = 1; $i <= 12; $i++) {
                $i = ($i < 10) ? '0'.$i : $i;

                $query = CourseView::query();
                $query->join('el_course_register_view as b', function ($sub) {
                    $sub->on('b.course_id', '=', 'el_course_view.course_id');
                    $sub->on('b.course_type', '=', 'el_course_view.course_type');
                    $sub->where('b.course_type', '=', 2);
                });
                $query->where('el_course_view.status', '=', 1);
                $query->where('el_course_view.isopen', '=', 1);
                $query->where('el_course_view.course_type', '=', 2);
                $query->where('el_course_view.offline', '=', 0);
                $query->whereExists(function ($sub) {
                    $sub->select(['id'])
                        ->from('el_offline_result as result')
                        ->whereColumn('result.user_id', '=', 'b.user_id')
                        ->whereColumn('result.course_id', '=', 'b.course_id');
                });
                $query->where(\DB::raw('month('.$prefix.'el_course_view.start_date)'), '<=', $i);
                $query->where(\DB::raw('month('.$prefix.'el_course_view.end_date)'), '>=', $i);
                $query->where(\DB::raw('year('.$prefix.'el_course_view.start_date)'), '=', $year);
                $query->where(function ($sub_query) use ($unit, $unit_child_arr) {
                    $sub_query->orWhereIn('b.unit_id', $unit_child_arr);
                    $sub_query->orWhere('b.unit_id', '=', $unit->id);
                });

                $course_by_units = $query->get()->count();

                $total_year += $course_by_units;
            }

            $model->total = $total_year;
            $model->year = $year;
            $model->save();
        }

    }
}
