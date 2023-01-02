<?php

namespace Modules\Quiz\Entities;

use App\Models\BaseModel;
use App\Models\Profile;
use App\Traits\ChangeLogs;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizRegister
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $user_id
 * @property int $part_id
 * @property int $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister wherePartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Quiz\Entities\QuizPart[] $quizparts
 * @property-read int|null $quizparts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Profile[] $users
 * @property-read int|null $users_count
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister userInternal()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister whereCreatedBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister whereUnitBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister whereUpdatedBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|QuizRegister withCacheCooldownSeconds(?int $seconds = null)
 */
class QuizRegister extends BaseModel
{
    use ChangeLogs, Cachable;
    protected $table = 'el_quiz_register';
    protected $table_name = 'Ghi danh kỳ thi';
    protected $fillable = [
        'user_id',
        'quiz_id',
        'part_id',
        'type',
        'locked',
        'blocked_quiz',
        'blocked_quiz_note',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'user_id' => trans('lamenu.user'),
            'quiz_id' => trans('lamenu.quiz'),
            'part_id' => 'Ca thi',
            'type' => 'Loại thí sinh'
        ];
    }

    public static function checkExists($user_id, $quiz_id){
        $query = self::query();
        $query->where('user_id', '=', $user_id);
        $query->where('quiz_id', '=', $quiz_id);
        $query->where('type', '=', 1);
        return $query->exists();
    }

    public static function checkSecondaryExists($user_secondary_id, $quiz_id){
        $query = self::query();
        $query->where('user_id', '=', $user_secondary_id);
        $query->where('quiz_id', '=', $quiz_id);
        $query->where('type', '=', 2);
        return $query->exists();
    }
    public function users()
    {
        return $this->belongsToMany(Profile::class,'el_quiz_register','id','user_id');
    }
    public function quizparts()
    {
        return $this->belongsToMany(QuizPart::class,'el_quiz_register','id','part_id');
    }

    public function scopeUserInternal(Builder $query)
    {
        return $query->where('type','=',1);
    }
}
