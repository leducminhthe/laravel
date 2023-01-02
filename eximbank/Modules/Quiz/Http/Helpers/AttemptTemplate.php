<?php

namespace Modules\Quiz\Http\Helpers;

use App\Jobs\SaveQuizAttempt;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Redis;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizQuestionCategory;
use Modules\Quiz\Entities\ReportCorrectAnswerRate;

class AttemptTemplate
{
    protected $attempt;

    protected $quiz;

    protected $template = [];

    protected $ramdom_questions;

    protected $answer_text = [
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j',
        'k',
        'l',
        'm',
        'n',
        'p',
        'q',
    ];

    public function __construct(QuizAttempts $attempt) {
        $this->attempt = $attempt;
        $this->quiz = $attempt->quiz;
        $this->ramdom_questions = [0];
    }

    public function create() {
        $this->mapQuizQuestionCategories();

        if ($this->template) {
            $data = json_encode($this->template);
            \Cache::put('attempt-' . $this->attempt->id,$data,3600);
            SaveQuizAttempt::dispatch($this->attempt->id,$data); 

            return true;
        }

        return false;
    }

    protected function mapQuizQuestionCategories() {
        $qqcategorys = QuizQuestionCategory::whereQuizId($this->attempt->quiz_id)->orderBy('num_order', 'asc')->get();
        $categories = [];
        foreach ($qqcategorys as $qqcategory) {
            $template = json_decode($qqcategory->template(),true);
            $categories[] =  $template['categories'];
        }
        $this->template['categories'] = $categories;
    }

    /**
     * Get anwsers by quiz question
     * @param int $question_id
     * @return array
     * */
    protected function getAnwsersQuestion($question_id) {
        $question = Question::find($question_id);
        $anwsers = QuestionAnswer::whereQuestionId($question_id);
        if ($this->quiz->shuffle_answers == 1 && $question->shuffle_answers == 1){
            $anwsers->inRandomOrder();
        }else{
            $anwsers->orderBy('id');
        }
        $anwsers = $anwsers->get([
            'id',
            'title',
            'question_id',
            'correct_answer',
            'feedback_answer',
            'matching_answer',
            'percent_answer',
            'image_answer',
            'fill_in_correct_answer',
            'select_word_correct'
        ])->toArray();

        foreach ($anwsers as $index => $anwser) {
            if (strpos($anwser['title'], '<p>') !== false) {
                $title = str_replace(['<p>','</p>'], '', $anwser['title']);
                $anwsers[$index]['title'] = $title;
            }
            $anwsers[$index]['index_text'] = @$this->answer_text[$index];
            $anwsers[$index]['image_answer'] = $anwser['image_answer'] ? image_file($anwser['image_answer']) : '';
        }

        return $anwsers;
    }
    private function getTemplateData() {
        $storage = \Storage::disk('local');
        $template = 'quiz/' . $this->quiz_id . '/template/template-' . $this->id .'.json';

        if ($storage->exists($template)) {
            return json_decode($storage->get($template), true);
        }
        return null;
    }
    private function shuffleQuestion($questionArr){
        $keys = array_keys($questionArr);
        shuffle($keys);
        $shuffledArray = array();
        $i=1;
        foreach($keys as $key) {
            $questionArr[$key]['qindex'] = $i;
            $shuffledArray[$key] = $questionArr[$key];
            $i++;
        }
        return $shuffledArray;
    }
}
