<?php

namespace Modules\Offline\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineReference
 *
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property int $register_id
 * @property int $schedule_id
 * @property string|null $reference Đơn xin phép
 * @property int|null $class_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference whereClassId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference whereCourseId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference whereCreatedAt($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference whereId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference whereReference($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference whereRegisterId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference whereScheduleId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference whereUpdatedAt($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference whereUserId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineReference withCacheCooldownSeconds(?int $seconds = null)
 * @mixin \Eloquent
 */
class OfflineReference extends Model
{
    use Cachable;
    protected $table = 'el_offline_reference';
    protected $table_name = 'Đơn xin phép Khóa học tập trung';
    protected $fillable = [
        'register_id',
        'schedule_id',
        'reference',
        'class_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'register_id' => 'Học viên ghi danh',
            'schedule_id' => 'Buổi học',
            'reference' => 'Đơn xin phép',
        ];
    }

    public static function checkExists($register_id, $schedule_id){
        $query = self::query();
        $query->where('schedule_id', '=', $schedule_id);
        $query->where('register_id', '=', $register_id);
        return $query->first();
    }
}
