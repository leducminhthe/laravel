<?php

namespace Modules\Quiz\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizRank
 *
 * @property int $id
 * @property int $quiz_id
 * @property string $rank
 * @property float $score_min
 * @property float $score_max
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRank query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRank whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRank whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRank whereScoreMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRank whereScoreMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRank whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuizTemplatesRank extends Model
{
    use Cachable;
    protected $table = 'el_quiz_templates_rank';
    protected $table_name = 'Xếp loại Cơ cấu đề thi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'rank',
        'score_min',
        'score_max',
    ];
}
