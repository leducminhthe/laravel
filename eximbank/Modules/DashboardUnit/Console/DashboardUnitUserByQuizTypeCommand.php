<?php

namespace Modules\DashboardUnit\Console;

use App\Models\Categories\Unit;
use Illuminate\Console\Command;
use Modules\DashboardUnit\Entities\DashboardUnitUserByQuizType;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizType;

class DashboardUnitUserByQuizTypeCommand extends Command
{
    protected $signature = 'dashboard_unit_user_by_quiz_type:update';
    protected $description = 'Cập nhật Thống kê CBNV thi theo loại kỳ thi (Dashboard đơn vị). Chạy lúc 23h (0 23 * * *)';
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

            $quiz_type = QuizType::get(['id', 'name']);
            foreach ($quiz_type as $item) {

                $model = DashboardUnitUserByQuizType::firstOrNew([
                    'unit_id' => $unit->id,
                    'quiz_type_id' => $item->id,
                    'year' => $year
                ]);
                $model->unit_id = $unit->id;
                $model->unit_code = $unit->code;
                $model->quiz_type_id = $item->id;
                $model->quiz_type_name = $item->name;

                $total_year = 0;
                for ($i = 1; $i <= 12; $i++) {
                    $ii = ($i < 10) ? '0'.$i : $i;

                    $query = QuizPart::query();
                    $query->leftJoin('el_quiz_register as b', 'b.part_id', '=', 'el_quiz_part.id');
                    $query->leftjoin('el_profile_view as c', 'c.user_id', '=', 'b.user_id');
                    $query->where('b.type', '=', 1);
                    $query->whereExists(function ($sub2) use ($item){
                        $sub2->select(['id'])
                            ->from('el_quiz')
                            ->where('status', '=', 1)
                            ->where('is_open', '=', 1)
                            ->where('type_id', '=', $item->id)
                            ->whereColumn('id', '=', 'el_quiz_part.quiz_id');
                    });
                    $query->whereExists(function ($sub) use ($prefix, $ii) {
                        $sub->select(['id'])
                            ->from('el_quiz_result as result')
                            ->whereColumn('result.user_id', '=', 'b.user_id')
                            ->whereColumn('result.quiz_id', '=', 'b.quiz_id')
                            ->where('result.type', '=', 1)
                            ->where(\DB::raw('month('.$prefix.'result.updated_at)'), '=', $ii);
                    });
                    $query->where(\DB::raw('year('.$prefix.'el_quiz_part.start_date)'), '=', $year);
                    $query->where(function ($sub_query) use ($unit, $unit_child_arr) {
                        $sub_query->orWhereIn('c.unit_id', $unit_child_arr);
                        $sub_query->orWhere('c.unit_id', '=', $unit->id);
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
            $model = DashboardUnitUserByQuizType::firstOrNew([
                'unit_id' => $unit->id,
                'quiz_type_id' => 0,
                'year' => $year
            ]);
            $model->unit_id = $unit->id;
            $model->unit_code = $unit->code;
            $model->quiz_type_id = 0;
            $model->quiz_type_name = 'Tổng';

            $query_sum = DashboardUnitUserByQuizType::where(['unit_id' => $unit->id,'year' => $year])
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
