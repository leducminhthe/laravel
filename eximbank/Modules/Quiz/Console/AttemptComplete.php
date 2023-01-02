<?php

namespace Modules\Quiz\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttemptGrade;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizUpdateAttempts;
use Modules\Quiz\Http\Helpers\AttemptGrade;

class AttemptComplete extends Command
{
    protected $signature = 'attempt:complete {attempt?}';

    protected $description = 'Quiz complete attempt. Cron hoàn thành lần thử kỳ thi 5phut/lần';

    protected $expression = "*/5 * * * *";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $attempt_id = $this->argument('attempt');
        $query = QuizAttempts::query();

        if ($attempt_id) {
            $query->whereId($attempt_id);
        }
        else {
            $query->where('cron_complete','=',0);
            $query->where('end_quiz','<',time());
        }
        //$query->limit(10);
        $rows = $query->get();

        foreach ($rows as $row) {
            $quiz = Quiz::find($row->quiz_id);
            $attempt_grade = new AttemptGrade($row);
            $score = $attempt_grade->getGrade();

            if($row->end_quiz <= time()) {
                $check_time = $row->end_quiz;
            } else {
                $check_time = time();
            }

            QuizAttempts::where('id',$row->id)->update([
                'sumgrades' => $score,
                'timefinish' => $check_time,
                'cron_complete'=>1
            ]);

            $result = QuizResult::firstOrNew(['quiz_id'=> $row->quiz_id,'user_id'=>$row->user_id, 'part_id' => $row->part_id]);
            $result->quiz_id = $row->quiz_id;
            $result->part_id = $row->part_id;
            $result->user_id = $row->user_id;
            $result->type = $row->type;
            $result->timecompleted = $check_time;
            $result->save();
        }
    }
}
