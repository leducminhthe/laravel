<?php

namespace Modules\Role\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;


/**
 * Modules\Role\Entities\ModelHasRoles
 *
 * @property int $role_id
 * @property string $model_type
 * @property int $model_id
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles whereModelId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles whereModelType($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles whereRoleId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModelHasRoles withCacheCooldownSeconds(?int $seconds = null)
 * @mixin \Eloquent
 */
class ModelHasRoles extends Model
{
    use Cachable;
    protected $table= 'el_model_has_roles';
    public $timestamps = false;
    protected $fillable = [
        'role_id',
        'model_type',
        'model_id',
    ];
}
