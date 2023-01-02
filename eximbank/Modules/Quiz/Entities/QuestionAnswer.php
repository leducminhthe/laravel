<?php

namespace Modules\Quiz\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuestionAnswer
 *
 * @property int $id
 * @property int $question_id
 * @property string $title
 * @property int $is_text
 * @property int $correct_answer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer whereCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer whereIsText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property float|null $percent_answer
 * @property string|null $feedback_answer
 * @property string|null $matching_answer
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer whereFeedbackAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer whereMatchingAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionAnswer wherePercentAnswer($value)
 * @property string|null $image_answer Đáp án hình ảnh
 * @property string|null $fill_in_correct_answer Đáp án điền từ chính xác
 * @property int|null $select_word_correct
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer whereFillInCorrectAnswer($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer whereImageAnswer($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer whereSelectWordCorrect($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuestionAnswer withCacheCooldownSeconds(?int $seconds = null)
 */
class QuestionAnswer extends Model
{
    use Cachable;
    protected $table = 'el_question_answer';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'question_id',
        'correct_answer',
        'feedback_answer',
        'matching_answer',
        'percent_answer',
        'image_answer',
        'fill_in_correct_answer',
        'select_word_correct',
        'marker_answer',
    ];

    public static function getAttributeName() {
        return [
            'title' => 'Tên câu trả lời',
            'question_id' => trans('latraining.question'),
            'correct_answer' => 'Đáp án đúng',
        ];
    }
}
