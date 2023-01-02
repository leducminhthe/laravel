<?php

namespace App\Models;

use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

/**
 * App\Models\AreaView
 *
 * @property int $id
 * @property int|null $area1_id
 * @property string|null $area1_code
 * @property string|null $area1_name
 * @property int|null $area2_id
 * @property string|null $area2_code
 * @property string|null $area2_name
 * @property int|null $area3_id
 * @property string|null $area3_code
 * @property string|null $area3_name
 * @property int|null $area4_id
 * @property string|null $area4_code
 * @property string|null $area4_name
 * @property int|null $area5_id
 * @property string|null $area5_code
 * @property string|null $area5_name
 * @property string|null $area_code mã đơn vị trực tiếp
 * @property int|null $area_level cấp độ khu vực
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereArea1Code($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereArea1Id($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereArea1Name($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereArea2Code($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereArea2Id($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereArea2Name($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereArea3Code($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereArea3Id($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereArea3Name($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereArea4Code($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereArea4Id($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereArea4Name($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereArea5Code($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereArea5Id($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereArea5Name($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereAreaCode($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereAreaLevel($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereCreatedAt($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereStatus($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView whereUpdatedAt($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AreaView withCacheCooldownSeconds(?int $seconds = null)
 * @mixin \Eloquent
 */
class AreaView extends Model
{
    use Cachable;
    protected $table = 'area_view';
    protected $fillable=['id','object_id','status','area_code','area_level',
        'area1_id','area1_code','area1_name','area2_id','area2_code','area2_name',
        'area3_id','area3_code','area3_name','area4_id','area4_code','area4_name','area5_id','area5_code','area5_name'];
    public static function mapField($area_code, &$data=[]){
        try {
            $record = Area::where(['code' => $area_code])->first();
            $data["area{$record->level}_id"] = $record->id;
            $data["area{$record->level}_code"] = $record->code;
            $data["area{$record->level}_name"] = $record->name;
            if ($record->parent_code)
                self::mapField($record->parent_code, $data);
        }catch (\Exception $e){
            dd($e->getMessage());
        }
        return $data;
    }

    public static function generalWhereArea($area_level)
    {

    }
}
