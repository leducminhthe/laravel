<?php

namespace Modules\DashboardUnit\Console;

use App\Models\CourseView;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\Unit;
use Illuminate\Console\Command;
use Modules\DashboardUnit\Entities\DashboardUnitUserByTrainingForm;

class DashboardUnitUserByTrainingFormCommand  extends Command
{
    protected $signature = 'dashboard_unit_user_by_training_form:update';
    protected $description = 'Cập nhật Thống kê CBNV theo loại hình đào tạo (Dashboard đơn vị). Chạy lúc 23h (0 23 * * *)';
    protected $expression ='0 23 * * *';

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

            $training_form = TrainingForm::get(['id', 'name']);
            foreach ($training_form as $item) {

                $model = DashboardUnitUserByTrainingForm::firstOrNew([
                    'unit_id' => $unit->id,
                    'training_form_id' => $item->id,
                    'year' => $year
                ]);
                $model->unit_id = $unit->id;
                $model->unit_code = $unit->code;
                $model->training_form_id = $item->id;
                $model->training_form_name = $item->name;

                $total_year = 0;
                for ($i = 1; $i <= 12; $i++) {
                    $ii = ($i < 10) ? '0'.$i : $i;

                    $query = CourseView::query();
                    $query->join('el_course_register_view as b', function ($sub){
                        $sub->on('b.course_id','=','el_course_view.course_id');
                        $sub->on('b.course_type','=','el_course_view.course_type');
                    });
                    $query->where('el_course_view.training_form_id', $item->id);
                    $query->where('el_course_view.status', '=', 1);
                    $query->where('el_course_view.isopen', '=', 1);
                    $query->where('el_course_view.offline', '=', 0);
                    $query->where(\DB::raw('month('.$prefix.'el_course_view.start_date)'), '<=', $ii)
                        ->where(function ($sub) use ($prefix, $ii){
                            $sub->orWhereNull('el_course_view.end_date');
                            $sub->orWhere(\DB::raw('month('.$prefix.'el_course_view.end_date)'), '>=', $ii);
                        });
                    $query->where(\DB::raw('year('.$prefix.'el_course_view.start_date)'), '=', $year);
                    $query->where(function ($sub) use ($prefix, $ii){
                        $sub->orWhereExists(function ($sub1) use ($prefix, $ii){
                            $sub1->select(['id'])
                                ->from('el_online_result as result')
                                ->whereColumn('result.user_id', '=', 'b.user_id')
                                ->whereColumn('result.course_id', '=', 'b.course_id')
                                ->whereColumn(\DB::raw(1), '=', 'b.course_type')
                                ->where(\DB::raw('month('.$prefix.'result.updated_at)'), '=', $ii);
                        });
                        $sub->orWhereExists(function ($sub2){
                            $sub2->select(['id'])
                                ->from('el_offline_result as result')
                                ->whereColumn('result.user_id', '=', 'b.user_id')
                                ->whereColumn('result.course_id', '=', 'b.course_id')
                                ->whereColumn(\DB::raw(2), '=', 'b.course_type');
                        });
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
            $model = DashboardUnitUserByTrainingForm::firstOrNew([
                'unit_id' => $unit->id,
                'training_form_id' => 0,
                'year' => $year
            ]);
            $model->unit_id = $unit->id;
            $model->unit_code = $unit->code;
            $model->training_form_id = 0;
            $model->training_form_name = 'Tổng';

            $query_sum = DashboardUnitUserByTrainingForm::where(['unit_id' => $unit->id,'year' => $year])
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
