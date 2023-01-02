<?php

namespace Modules\Quiz\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizQuestionCategory
 *
 * @property int $id
 * @property int $quiz_id
 * @property string $name
 * @property int $num_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory whereNumOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $percent_group
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory wherePercentGroup($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Quiz\Entities\QuizQuestion[] $questions
 * @property-read int|null $questions_count
 * @property-read \Modules\Quiz\Entities\Quiz|null $quiz
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizQuestionCategory all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizQuestionCategory avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizQuestionCategory cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizQuestionCategory cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizQuestionCategory count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizQuestionCategory disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizQuestionCategory disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizQuestionCategory flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizQuestionCategory getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizQuestionCategory inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizQuestionCategory insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizQuestionCategory isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizQuestionCategory max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizQuestionCategory min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizQuestionCategory sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizQuestionCategory truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizQuestionCategory withCacheCooldownSeconds(?int $seconds = null)
 * @property string|null $template
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizQuestionCategory whereTemplate($value)
 */
class QuizQuestionCategory extends Model
{
    use Cachable;
    protected $table = 'el_quiz_question_category';
    protected $fillable = [
        'name',
        'num_order',
        'percent_group',
        'template'
    ];

    public function questions() {
        return $this->hasMany('Modules\Quiz\Entities\QuizQuestion', 'qqcategory', 'id');
    }
    public function quiz() {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'id');
    }
    public function sumMaxScore(){
        $max_score = $this->questions()
            ->sum('max_score');

        if ($max_score > 0) {
            return $max_score;
        }

        return 0;
    }

    public static function sumMaxScoreByQuizID($quiz_id){
        $quiz_ques_cate = QuizQuestionCategory::where('quiz_id', '=', $quiz_id)->get();
        $total = 0;

        foreach ($quiz_ques_cate as $item){
            $total += $item->percent_group;
        }

        return $total;
    }
    public static function updateCateTemplate($quiz_id){
        $quizs = QuizQuestionCategory::whereQuizId($quiz_id)->get();
        foreach ($quizs as $index => $model) {
            $max_score = $model->sumMaxScore();
            $per_score = $model->percent_group > 0 ? ($model->quiz->max_score * $model->percent_group / 100) / ($max_score ? $max_score : 1) : ($model->quiz->max_score / $max_score);
            $template['categories']=[
                'name'=>$model->name,
                'num_order'=>$model->num_order,
                'percent_group'=>$model->percent_group,
                'qqcategory' => $model->id,
                'max_score' => $max_score,
                "per_score"=> floor($per_score*1000)/1000
            ];
            $model->template= $template;
            $model->save();
        }

    }
}
