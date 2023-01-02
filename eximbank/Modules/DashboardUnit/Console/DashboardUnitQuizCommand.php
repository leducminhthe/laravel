<?php

namespace Modules\DashboardUnit\Console;

use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Models\CourseView;
use App\Models\Profile;
use Illuminate\Console\Command;
use Modules\DashboardUnit\Entities\DashboardUnitQuiz;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizRegister;

class DashboardUnitQuizCommand extends Command
{
    protected $signature = 'dashboard_unit_quiz:update {unit_id?}';
    protected $description = 'Cập nhật trình trạng hv trong kỳ thi (Dashboard đơn vị). Chạy lúc 22h (0 22 * * *)';
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

            DashboardUnitQuiz::where(['unit_id' => $unit->id])->delete();

            $quizs = Quiz::where(['status' => 1, 'is_open' => 1])
                ->whereExists(function($sub) use($userOfArrChild){
                    $sub->select(['id'])
                        ->from('el_quiz_register')
                        ->whereColumn('quiz_id', 'el_quiz.id')
                        ->whereIn('user_id', $userOfArrChild);
                })
                ->get(['id', 'start_quiz', 'end_quiz']);
            foreach($quizs as $quiz){
                //Tổng số ghi danh kỳ thi
                $register = QuizRegister::whereQuizId($quiz->id)->whereIn('user_id', $userOfArrChild)->count();

                //HV chưa thi. Mới ghi danh, chưa nằm trong bảng lần làm bài thi (el_quiz_attempts)
                $unlearned = QuizRegister::whereQuizId($quiz->id)
                    ->whereIn('user_id', $userOfArrChild)
                    ->whereNotExists(function($sub){
                        $sub->select(\DB::raw(1))
                            ->from('el_quiz_attempts')
                            ->whereColumn('quiz_id', '=', 'el_quiz_register.quiz_id')
                            ->whereColumn('user_id', '=', 'el_quiz_register.user_id');
                    })->count();

                //HV hoàn thành thi
                $completed = QuizRegister::whereQuizId($quiz->id)
                    ->whereIn('user_id', $userOfArrChild)
                    ->whereExists(function($sub){
                        $sub->select(\DB::raw(1))
                            ->from('el_quiz_result')
                            ->whereColumn('quiz_id', '=', 'el_quiz_register.quiz_id')
                            ->whereColumn('user_id', '=', 'el_quiz_register.user_id')
                            ->where('result', 1);
                    })->count();

                //HV chưa hoàn thành thi
                $uncompleted = $register - ($unlearned + $completed);

                $model = DashboardUnitQuiz::firstOrNew([
                    'unit_id' => $unit->id,
                    'quiz_id' => $quiz->id,
                ]);
                $model->unit_id = $unit->id;
                $model->unit_code = $unit->code;
                $model->quiz_id = $quiz->id;
                $model->total = $register;
                $model->unlearned = $unlearned;
                $model->completed = $completed;
                $model->uncompleted = $uncompleted;
                $model->start_date = $quiz->start_quiz;
                $model->end_date = $quiz->end_quiz;
                $model->save();
            }
        }
    }
}
