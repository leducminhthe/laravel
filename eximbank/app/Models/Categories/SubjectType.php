<?php

namespace App\Models\Categories;

use App\Traits\ChangeLogs;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

/**
 * App\Models\Categories\SubjectType
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $startdate
 * @property string|null $enddate
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType whereCode($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType whereCreatedAt($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType whereCreatedBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType whereEnddate($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType whereId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType whereName($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType whereStartdate($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType whereStatus($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType whereUnitBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType whereUpdatedAt($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType whereUpdatedBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|SubjectType withCacheCooldownSeconds(?int $seconds = null)
 * @mixin \Eloquent
 */
class SubjectType extends BaseModel
{
    use ChangeLogs, Cachable;

    protected $table = 'el_subject_type';
    protected $table_name = "Chương trình đào tạo";

    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'certificate_id',
        'startdate',
        'enddate',
        'status',
        'created_by',
        'updated_by',
        'unit_by',
        'created_at',
        'updated_at',
        'certificate_expiry',
    ];

    public static function getAttributeName() {
        return [
            'code' => trans('backend.subject_code'),
            'name' => trans('latraining.subject_name'),
            'certificate_id' => trans('latraining.certificate'),
            'startdate' =>  trans('backend.startdate'),
            'enddate' => trans('backend.enddate'),
            'subjects' => 'học phần',
        ];
    }


}
