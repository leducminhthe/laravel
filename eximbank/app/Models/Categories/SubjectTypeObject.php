<?php

namespace App\Models\Categories;

use App\Models\CacheModel;
use App\Traits\ChangeLogs;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\SubjectTypeObject
 *
 * @property int $id
 * @property int $subject_type_id
 * @property int|null $title_id
 * @property int|null $unit_id
 * @property int|null $user_id
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject whereId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject whereSubjectTypeId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject whereTitleId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject whereUnitId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject whereUserId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectTypeObject withCacheCooldownSeconds(?int $seconds = null)
 * @mixin \Eloquent
 */
class SubjectTypeObject extends Model
{
    use ChangeLogs, Cachable;
    protected $table = 'el_subject_type_object';
    protected $table_name = 'Chương trình đào tạo đối tượng';
    protected $fillable = [
        'subject_type_id',
        'unit_id',
        'title_id',
        'user_id',
    ];
    protected $primaryKey = 'id';

    public $timestamps = null;

    public static function checkObjectUnit ($subject_type_id, $unit_id){
        $query = self::query();
        $query->where('unit_id', '=', $unit_id);
        $query->where('subject_type_id', '=', $subject_type_id);
        return $query->exists();
    }
    public static function checkObjectTitle ($subject_type_id, $title_id){
        $query = self::query();
        $query->where('title_id', '=', $title_id);
        $query->where('subject_type_id', '=', $subject_type_id);
        return $query->exists();
    }
}
