<?php

namespace App\Models\Categories;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\UnitPermission
 *
 * @property int $id
 * @property int $unit_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitPermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitPermission whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitPermission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitPermission whereUserId($value)
 * @mixin \Eloquent
 */
class UnitPermission extends Model
{
    use Cachable;
    protected $table = 'el_unit_permission';
    protected $fillable = [
        'unit_id',
        'user_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'unit_id' => trans('lamenu.unit'),
            'user_id' => trans('lamenu.user'),
        ];
    }

    public static function checkExists($unit_id, $user_id){
        $query = self::query();
        $query->where('unit_id', '=', $unit_id);
        $query->where('user_id', '=', $user_id);
        return $query->exists();
    }

    public static function getIdUnitManagedByUser($user_id = null) {
        $user_id = empty($user_id) ? profile()->user_id : $user_id;
        $result = [];

        $query = UnitPermission::query();
        $query->from('el_unit_permission AS a')
            ->join('el_unit AS b', 'b.id', '=', 'a.unit_id')
            ->where('user_id', '=', $user_id);
        $rows = $query->get(['b.id', 'b.code']);
        foreach ($rows as $row) {
            $result[] = $row->id;
            Unit::getArrayChild($row->code, $result);
        }

        return $result;
    }
}
