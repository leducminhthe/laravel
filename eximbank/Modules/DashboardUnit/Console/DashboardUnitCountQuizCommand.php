<?php

namespace Modules\DashboardUnit\Console;

use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use Illuminate\Console\Command;
use Modules\DashboardUnit\Entities\DashboardUnitCountQuiz;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizRegister;

class DashboardUnitCountQuizCommand extends Command
{
    protected $signature = 'dashboard_unit_count_quiz:update {unit_id?}';
    protected $description = 'Cập nhật tổng số kỳ thi (Dashboard đơn vị). Chạy lúc 23h (0 23 * * *)';
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

            $model = DashboardUnitCountQuiz::firstOrNew([
                'unit_id' => $unit->id,
            ]);
            $model->unit_id = $unit->id;
            $model->unit_code = $unit->code;

            $query = QuizRegister::query();
            $query->select(['el_quiz_register.quiz_id']);
            $query->leftJoin('el_quiz as quiz', 'quiz.id', '=', 'el_quiz_register.quiz_id');
            $query->leftJoin('el_profile as b', 'b.user_id', '=', 'el_quiz_register.user_id');
            $query->whereIn('b.unit_id', $allUnitId);
            $query->where('quiz.status', '=', 1);
            $query->where('quiz.is_open', '=', 1);
            $query->groupBy(['el_quiz_register.quiz_id']);

            $total_year = $query->get()->count();

            $model->total = $total_year;
            $model->year = $year;
            $model->save();
        }

    }
}
