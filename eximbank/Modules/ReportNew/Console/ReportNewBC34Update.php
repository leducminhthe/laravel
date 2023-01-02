<?php

namespace Modules\ReportNew\Console;

use Illuminate\Console\Command;
use Modules\Quiz\Entities\QuestionCategory;
use Modules\Quiz\Entities\QuizUpdateAttempts;
use Modules\ReportNew\Entities\ReportNewBC34;

class ReportNewBC34Update extends Command
{
    protected $signature = 'report_new_bc34:update';
    protected $description = 'Báo cáo thống kê ngân hàng câu hỏi. Chạy ngày lần lúc 22h';
    protected $expression ="0 22 * * *";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $arr_scoring_question_used = [];
        $arr_question_graded_used = [];
        $arr_scoring_question_correct = [];

        $update_attemplate = QuizUpdateAttempts::where('questions', '!=', 'null')->get();
        foreach ($update_attemplate as $item){

            $questions = json_decode($item['questions'], true);
            foreach ($questions as $question){
                $category_id = $question['category_id'];

                if(in_array($question['type'], ['essay', 'fill_in'])){
                    if(array_key_exists($category_id, $arr_question_graded_used)){
                        $arr_question_graded_used[$category_id]++;
                        $arr_question_graded_used[$category_id] = $arr_question_graded_used[$category_id];
                    }else{
                        $arr_question_graded_used[$category_id] = 1;
                    }
                }else{
                    if ($question['score'] == $question['score_group']){

                        if(array_key_exists($category_id, $arr_scoring_question_correct)){
                            $arr_scoring_question_correct[$category_id]++;
                            $arr_scoring_question_correct[$category_id] = $arr_scoring_question_correct[$category_id];
                        }else{
                            $arr_scoring_question_correct[$category_id] = 1;
                        }
                    }

                    if(array_key_exists($category_id, $arr_scoring_question_used)){
                        $arr_scoring_question_used[$category_id]++;
                        $arr_scoring_question_used[$category_id] = $arr_scoring_question_used[$category_id];
                    }else{
                        $arr_scoring_question_used[$category_id] = 1;
                    }
                }
            }
        }

        $categories = QuestionCategory::whereStatus(1)->get(['id']);
        foreach($categories as $category){
            ReportNewBC34::updateOrCreate([
                'category_id' => $category->id
            ],[
                'category_id' => $category->id,
                'scoring_question_used' => isset($arr_scoring_question_used[$category->id]) ? $arr_scoring_question_used[$category->id] : 0,
                'question_graded_used' => isset($arr_question_graded_used[$category->id]) ? $arr_question_graded_used[$category->id] : 0,
                'scoring_question_correct' => isset($arr_scoring_question_correct[$category->id]) ? $arr_scoring_question_correct[$category->id] : 0,
            ]);
        }
    }
}
