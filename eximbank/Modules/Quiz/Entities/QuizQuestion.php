<?php

namespace Modules\Quiz\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizQuestion
 *
 * @property int $id
 * @property int $quiz_id
 * @property int|null $question_id
 * @property int|null $qcategory_id
 * @property int $random
 * @property int $num_order
 * @property float $max_score
 * @property int $qqcategory
 * @property \Modules\Quiz\Entities\Question|null $question
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereMaxScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereNumOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereQcategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereQqcategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereRandom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuizQuestion extends Model
{
    use Cachable;
    protected $table = 'el_quiz_question';
    protected $table_name = 'Câu hỏi kỳ thi';
    protected $fillable = [
        'quiz_id',
        'question_id',
        'qcategory_id',
        'random',
        'max_score',
        'difficulty',
    ];

    public function question() {
        return $this->hasOne('Modules\Quiz\Entities\Question', 'id', 'question_id');
    }
    public function quiz()
    {
        return $this->belongsTo(Quiz::class,'quiz_id');
    }
    public static function getAttributeName() {
        return [
            'quiz_id' => trans('lamenu.quiz'),
            'question_id' => trans('latraining.question'),
            'qcategory_id' => 'Danh mục câu hỏi',
            'random' => 'Ngẫu nhiên',
            'max_score' => 'Điểm tối đa',
        ];
    }

    public static function getMaxOrder($quiz_id) {
        $query = self::query();
        $query->where('quiz_id', '=', $quiz_id);
        $query->orderBy('num_order', 'desc');
        if ($query->exists()) {
            return $query->first()->num_order;
        }

        return 0;
    }

    public static function getQuestions($quiz_id) {
        $query = self::query();
        $query->where('quiz_id', '=', $quiz_id);
        $query->orderBy('num_order', 'asc');
        if ($query->exists()) {
            return $query->get();
        }

        return [];
    }

    public static function getQuestions2($quiz_id, $category = 0) {
        $query = QuizQuestion::query();
        $query->select([
            'a.id',
            'a.qcategory_id',
            'a.num_order',
            'a.max_score',
            'b.name AS question_name',
            'c.name AS category_name'
        ])
            ->from('el_quiz_question AS a')
            ->leftJoin('el_question AS b', 'b.id', '=', 'a.question_id')
            ->leftJoin('el_question_category AS c', 'c.id', '=', 'a.qcategory_id')
            ->where('quiz_id', '=', $quiz_id)
            ->where('qqcategory', '=', $category)
            ->orderBy('num_order', 'asc');

        if ($query->exists()) {
            return $query->get();
        }

        return [];
    }

    public static function getArrayQuestions($quiz_id) {
        $query = self::query();
        $query->where('quiz_id', '=', $quiz_id);
        $query->whereNotNull('question_id');
        if ($query->exists()) {
            return $query->pluck('question_id')->toArray();
        }

        return [];
    }

    public static function countQuestion($quiz_id, $cat_id, $difficulty = null) {
        $query = self::query();
        $query->where('qcategory_id', '=', $cat_id);
        $query->where('quiz_id', '=', $quiz_id);

        if($difficulty){
            $query->where('difficulty', '=', $difficulty);
        }

        return $query->count('id');
    }
    public static function getTotalScore($quiz_id) {
        $query = self::where('quiz_id', '=', $quiz_id);
        if ($query->exists()) {
            return $query->sum('max_score');
        }
        return 0;
    }

    public static function SumMaxScoreByGroup($quiz_id, $qqcate_id){
        $query = self::where('quiz_id', '=', $quiz_id)->where('qqcategory','=', $qqcate_id);
        if ($query->exists()) {
            return $query->sum('max_score');
        }
        return 0;
    }

    public static function randomQuestionUnique($cate_id, $num, $quiz_id,$template_id)
    {
//        $question_randed = QuizTemplateQuestionRanded::query()->where('template_id','=',$template_id)->where('quiz_id','=',$quiz_id)->value('question_rand');
        $question_randed = QuizTemplateQuestionRand::query()
            ->select(["question_id"])
            ->where('template_id','=',$template_id)
            ->where('quiz_id','=',$quiz_id)
            ->pluck('question_id')->toArray();

        $query = Question::query();
        $query->from('el_question');
        $query->where('category_id','=',$cate_id);
        if ($question_randed)
            $query->whereNotIn('id', $question_randed);
        $query->limit($num)->inRandomOrder();
        return $query->first();
    }
    public static function updateQuestionTemplate($quiz_id,$quiz_question_id=0)
    {
        $quizs = QuizQuestion::with('question:id,name,type,multiple,answer_horizontal')
            ->whereQuizId($quiz_id);
        if ($quiz_question_id > 0)
            $quizs = $quizs->where(['id' => $quiz_question_id]);
        $quizs = $quizs->orderby('num_order')->get();
        $quiz = Quiz::findOrFail($quiz_id);
        $total_score = self::getTotalScore($quiz_id);
        $score_group = $total_score > 0 ? ($quiz->max_score / $total_score) : 0;
        $data = [];
        foreach ($quizs as $index => $quizQuestion) {
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
                'max_score' => $quizQuestion->max_score,
                'score_group' => $score_group,
                'answers' => self::getAnwsersQuestion($quiz, $quizQuestion->question_id),
                'answer_horizontal' => $quizQuestion->question->answer_horizontal,
                'shuffle_answers' => $quizQuestion->question->shuffle_answers,
            ];

//            QuizQuestion::where(['id' => $quiz->id])->update(['template' => $template]);
//            $data = null;
        }

        if (!empty($data)) {
            $storage = \Storage::disk('local');
            $attempt_folder = 'quiz/' . $quiz_id . '/template';

            if (!$storage->exists($attempt_folder)) {
                \File::makeDirectory($storage->path($attempt_folder), 0777, true);
            }
            $storage->put($attempt_folder . '/template-' . $quiz_id . '.json', json_encode($data));
            return true;
        }
    }
    public static function getAnwsersQuestion($quiz, $question_id) {
        $answer_text = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','p','q',];
        $question = Question::find($question_id);
        $query = QuestionAnswer::whereQuestionId($question_id);
        if ($quiz->shuffle_answers == 1 && $question->shuffle_answers == 1){
            $query = $query->inRandomOrder();
        }
        $anwsers = $query->get()->toArray();

        $correct_answers = [];
        foreach ($anwsers as $index => $answer) {
            if ($question->type=='matching'){
                if ($answer['matching_answer'])
                    $correct_answers[] = $answer['id'];
            }
            elseif ($question->type=='multiple-choise'){
                if ($question->multiple==1) {
                    if ($answer['percent_answer'] > 0)
                        $correct_answers[] = $answer['id'];
                }
                else {
                    if ($answer['correct_answer'] > 0)
                        $correct_answers[] = $answer['id'];
                }
            }
            elseif (in_array($question->type, ['select_word_correct', 'drag_drop_document'])){
                if ($answer['correct_answer'] > 0)
                    $correct_answers[] = $answer['id'];
            }
            elseif($question->type=='drag_drop_image'){
                if ($answer['marker_answer'])
                    $correct_answers[] = $answer['id'];
            }

            $anwsers[$index]['index_text'] = $answer_text[$index];
            $anwsers[$index]['image_answer'] = $answer['image_answer'] ? image_file($answer['image_answer']) : '';
        }

        return ['anwsers'=>$anwsers,'correct_answers'=>$correct_answers];
    }
}
