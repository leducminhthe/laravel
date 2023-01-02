<?php

namespace Modules\Quiz\Console;

use App\Automail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizQuestion;
use Illuminate\Database\Query\Builder;
use Modules\Quiz\Entities\QuizQuestionCategory;
use Modules\Quiz\Entities\ReportCorrectAnswerRate;

class CreateTemplate extends Command
{
    protected $signature = 'quiz:create_template';

    protected $description = 'Tạo bộ đề tự động trước khi thi 3 phút/lần';
    protected $expression = "*/3 * * * *";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
//        $quizs = \DB::table('el_quiz')->where(['flag'=>1])->get();
        $quizs = Quiz::where(['flag' => 1])->get();
        foreach ($quizs as $index => $quiz) {
            $quiz_teacher_grade = 0;
            $quizQuestions = QuizQuestion::with('question:id,name,type,multiple,multiple_full_score,answer_horizontal,image_drag_drop,difficulty')
                ->whereQuizId($quiz->id)->orderby('num_order')->get();
            $total_score = QuizQuestion::where('quiz_id', '=', $quiz->id)->sum('max_score');
            $score_group = $total_score > 0 ? ($quiz->max_score / $total_score) : 0;
            for ($i = 1; $i <= 10; $i++) {
                $data = [];
                $ramdom_questions = [0];
                $data['quiz'] = $this->getQuizTemplate($quiz->id);
                $data['categories'] = $this->getCategoryQuizQuestionTemplate($quiz->id);
                foreach ($quizQuestions as $index => $quizQuestion) {
                    if ($quizQuestion->question->type == 'fill_in' || $quizQuestion->question->type == 'essay') {
                        $data['quiz']['teacher_grade'] = 1;
                        $quiz_teacher_grade = 1;
                    }

                    if ($quizQuestion->random == 1) {
                        $quizRandom = \DB::table('el_question')->where('category_id', '=', $quizQuestion->qcategory_id)
                            ->whereNotIn('id', $ramdom_questions)
                            ->whereNotExists(function (Builder $builder) use ($quiz) {
                                $builder->select(['question_id'])
                                    ->from('el_quiz_question')
                                    ->where('quiz_id', '=', $quiz->id)
                                    ->whereColumn('question_id', '=', 'el_question.id')
                                    ->whereNotNull('question_id');
                            })
                            ->where('status', 1);
                        if ($quizQuestion->difficulty) {
                            $quizRandom->where('difficulty', '=', $quizQuestion->difficulty);
                        }
                        $quizRandom = $quizRandom->inRandomOrder()->first();

                        $answers = QuizQuestion::getAnwsersQuestion($quiz, $quizRandom->id);
                        $data['questions'][$quizRandom->id] = [
                            'id' => $quizRandom->id,
                            'qindex' => $quizQuestion->num_order,
                            'question_id' => $quizRandom->id,
                            'name' => $quizRandom->name,
                            'type' => $quizRandom->type,
                            'category_id' => $quizRandom->category_id,
                            'qqcategory' => $quizQuestion->qqcategory,
                            'random' => 1,
                            'multiple' => $quizRandom->multiple,
                            'multiple_full_score' => $quizRandom->multiple_full_score,
                            'max_score' => $quizQuestion->max_score,
                            'score_group' => $quizQuestion->qqcategory > 0 ? ($data['categories'][$quizQuestion->qqcategory]['per_score']) : $score_group,
                            'answers' => $answers['anwsers'],
                            'answer_horizontal' => $quizRandom->answer_horizontal,
                            'shuffle_answers' => $quizRandom->shuffle_answers,
                            'correct_answers' => $answers['correct_answers'],
                            'image_drag_drop' => $quizRandom->image_drag_drop ? image_file($quizRandom->image_drag_drop) : '',
                            'difficulty' => $quizRandom->difficulty
                        ];
                        $ramdom_questions[] = $quizRandom->id;

                        //Lưu dành cho BC04
                        if ($quiz->quiz_template_id) {
                            ReportCorrectAnswerRate::query()
                                ->updateOrCreate([
                                    'quiz_template_id' => $quiz->quiz_template_id,
                                    'question_id' => $quizRandom->id,
                                ], [
                                    'question_type' => $quizRandom->type,
                                    'num_question_used' => (ReportCorrectAnswerRate::countQuestionUsed($quiz->quiz_template_id, $quizRandom->id) + 1),
                                ]);
                        }
                    } else {
                        $answers = QuizQuestion::getAnwsersQuestion($quiz, $quizQuestion->question_id);
                        $data['questions'][$quizQuestion->question_id] = [
                            'id' => $quizQuestion->question_id,
                            'qindex' => $quizQuestion->num_order,
                            'question_id' => $quizQuestion->question_id,
                            'name' => $quizQuestion->question->name,
                            'type' => $quizQuestion->question->type,
                            'category_id' => $quizQuestion->qcategory_id,
                            'qqcategory' => $quizQuestion->qqcategory,
                            'random' => $quizQuestion->random,
                            'multiple' => $quizQuestion->question->multiple,
                            'multiple_full_score' => $quizQuestion->question->multiple_full_score,
                            'max_score' => $quizQuestion->max_score,
                            'score_group' => $quizQuestion->qqcategory > 0 ? ($data['categories'][$quizQuestion->qqcategory]['per_score']) : $score_group,
                            'answers' => $answers['anwsers'],
                            'answer_horizontal' => $quizQuestion->question->answer_horizontal,
                            'shuffle_answers' => $quizQuestion->question->shuffle_answers,
                            'correct_answers' => $answers['correct_answers'],
                            'image_drag_drop' => $quizQuestion->question->image_drag_drop ? image_file($quizQuestion->question->image_drag_drop) : '',
                        ];

                        //Lưu dành cho BC04
                        if ($quiz->quiz_template_id) {
                            ReportCorrectAnswerRate::query()
                                ->updateOrCreate([
                                    'quiz_template_id' => $quiz->quiz_template_id,
                                    'question_id' => $quizQuestion->question_id,
                                ], [
                                    'question_type' => $quizQuestion->question->type,
                                    'num_question_used' => (ReportCorrectAnswerRate::countQuestionUsed($quiz->quiz_template_id, $quizQuestion->question_id) + 1),
                                ]);
                        }
                    }
                }
                if (!empty($data)) {
                    if ($quiz->shuffle_question)
                        $data['questions'] = $this->shuffleQuestion($data['questions']);
                    $storage = \Storage::disk('local');
                    $attempt_folder = 'quiz/' . $quiz->id . '/template';
                    if (!$storage->exists($attempt_folder)) {
                        \File::makeDirectory($storage->path($attempt_folder), 0777, true);
                    }
                    $storage->put($attempt_folder . '/' . $i . '.json', json_encode($data));
                }
            }
            // update lại flag, teacher_grade
            $quiz->update([
                'flag' => 0,
                'teacher_grade' => $quiz_teacher_grade,
            ]);
        }
    }

    private function getQuizTemplate($quiz_id)
    {
        $quiz = Quiz::find($quiz_id);
        return [
            'id' => $quiz_id,
            'course_id' => $quiz->course_id,
            'quiz_type' => $quiz->quiz_type,
            'limit_time' => $quiz->limit_time,
            'view_result' => $quiz->view_result,
            'shuffle_answers' => $quiz->shuffle_answers,
            'shuffle_question' => $quiz->shuffle_question,
            'questions_perpage' => $quiz->questions_perpage,
            'pass_score' => $quiz->pass_score,
            'max_score' => $quiz->max_score,
            'max_attempts' => $quiz->max_attempts,
            'grade_methor' => $quiz->grade_methor,
        ];
    }

    private function getCategoryQuizQuestionTemplate($quiz_id)
    {
        $quizs = QuizQuestionCategory::whereQuizId($quiz_id)->get();
        $template = [];
        foreach ($quizs as $index => $model) {
            $max_score = $model->sumMaxScore();
            $per_score = $model->percent_group > 0 ? ($model->quiz->max_score * $model->percent_group / 100) / ($max_score ? $max_score : 1) : ($model->quiz->max_score / $max_score);
            $template[$model->id] = [
                'name' => $model->name,
                'num_order' => $model->num_order,
                'percent_group' => $model->percent_group,
                'qqcategory' => $model->id,
                'max_score' => $max_score,
                "per_score" => floor($per_score * 1000) / 1000
            ];
        }
        return $template;
    }

    private function shuffleQuestion($questionArr)
    {
        $keys = array_keys($questionArr);
        shuffle($keys);
        $shuffledArray = array();
        $ix = 1;
        foreach ($keys as $key) {
            $questionArr[$key]['qindex'] = $ix;
            $shuffledArray[$key] = $questionArr[$key];
            $ix++;
        }
        return $shuffledArray;
    }
}
