<?php

namespace Modules\DashboardUnit\Console;

use App\Models\Categories\Unit;
use App\Models\CourseView;
use Illuminate\Console\Command;
use Modules\DashboardUnit\Entities\DashboardUnitUserByCourseEmployee;

class DashboardUnitUserByCourseEmployeeCommand extends Command
{
    protected $signature = 'dashboard_unit_user_by_course_employee:update';
    protected $description = 'Cập nhật Thống kê CBNV tân tuyển, hiện hữu (Dashboard đơn vị). Chạy lúc 23h (0 23 * * *)';
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

            for ($course_employee = 1; $course_employee <= 2; $course_employee++) {

                $model = DashboardUnitUserByCourseEmployee::firstOrNew([
                    'unit_id' => $unit->id,
                    'course_employee' => $course_employee,
                    'year' => $year
                ]);
                $model->unit_id = $unit->id;
                $model->unit_code = $unit->code;
                $model->course_employee = $course_employee;

                $total_year = 0;
                for ($i = 1; $i <= 12; $i++) {
                    $ii = ($i < 10) ? '0'.$i : $i;

                    $query = CourseView::query();
                    $query->join('el_course_register_view as b', function ($sub){
                        $sub->on('b.course_id','=','el_course_view.course_id');
                        $sub->on('b.course_type','=','el_course_view.course_type');
                        $sub->where('b.course_type', '=', 2);
                    });
                    $query->where('el_course_view.status', '=', 1);
                    $query->where('el_course_view.isopen', '=', 1);
                    $query->where('el_course_view.course_type', '=', 2);
                    $query->where('el_course_view.offline', '=', 0);
                    $query->where('el_course_view.course_employee', '=', $course_employee);
                    $query->where(\DB::raw('month('.$prefix.'el_course_view.start_date)'), '<=', $ii)
                        ->where(function ($sub) use ($prefix, $ii){
                            $sub->orWhereNull('el_course_view.end_date');
                            $sub->orWhere(\DB::raw('month('.$prefix.'el_course_view.end_date)'), '>=', $ii);
                        });
                    $query->where(\DB::raw('year('.$prefix.'el_course_view.start_date)'), '=', $year);
                    $query->whereExists(function ($sub){
                        $sub->select(['id'])
                            ->from('el_offline_result as result')
                            ->whereColumn('result.user_id', '=', 'b.user_id')
                            ->whereColumn('result.course_id', '=', 'b.course_id');
                    });
                    $query->where(function ($sub_query) use ($unit, $unit_child_arr) {
                        $sub_query->orWhereIn('b.unit_id', $unit_child_arr);
                        $sub_query->orWhere('b.unit_id', '=', $unit->id);
                    });

                    $course_by_units = $query->get()->count();

                    $total_year += $course_by_units;

                    $model->{'t'.$i} = $course_by_units;
                }

                $model->total = $total_year;
                $model->year = $year;
                $model->save();
            }

            //Tổng loại hình theo từng tháng
            $model = DashboardUnitUserByCourseEmployee::firstOrNew([
                'unit_id' => $unit->id,
                'course_employee' => 0,
                'year' => $year
            ]);
            $model->unit_id = $unit->id;
            $model->unit_code = $unit->code;
            $model->course_employee = 0;

            $query_sum = DashboardUnitUserByCourseEmployee::where(['unit_id' => $unit->id,'year' => $year])
                ->selectRaw('sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,sum(t5) as t5,sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,sum(t9) as t9,sum(t10) as t10,sum(t11) as t11,sum(t12) as t12')->first();
            $arr_total = $query_sum ? $query_sum->toArray() : [];

            $total_year = 0;
            for ($i = 1; $i <= 12; $i++) {
                $total_year += $arr_total['t'.$i];

                $model->{'t'.$i} = $arr_total['t'.$i];
            }

            $model->total = $total_year;
            $model->year = $year;
            $model->save();
        }

    }
}
