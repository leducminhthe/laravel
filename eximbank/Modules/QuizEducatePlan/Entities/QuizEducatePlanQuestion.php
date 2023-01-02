<?php

namespace Modules\QuizEducatePlan\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuizQuestion;

/**
 * Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestion
 *
 * @property int $id
 * @property int $quiz_id
 * @property int|null $question_id
 * @property int|null $qcategory_id
 * @property int $random
 * @property int $num_order
 * @property float $max_score
 * @property int $qqcategory
 * @property \Modules\QuizEducatePlan\Entities\Question|null $question
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestion whereMaxScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestion whereNumOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestion whereQcategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestion whereQqcategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestion whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestion whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestion whereRandom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuizEducatePlanQuestion extends Model
{
    use Cachable;
    protected $table = 'el_quiz_educate_plan_question';
    protected $fillable = [
        'quiz_id',
        'question_id',
        'qcategory_id',
        'random',
        'max_score',
    ];

    public function question() {
        return $this->hasOne('Modules\Quiz\Entities\Question', 'id', 'question_id');
    }

    public static function getAttributeName() {
        return [
            'quiz_id' => 'Kỳ thi',
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

    public static function countQuestion($quiz_id, $cat_id) {
        $query = self::query();
        $query->where('qcategory_id', '=', $cat_id);
        $query->where('quiz_id', '=', $quiz_id);
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
}
