<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\PermissionTypeUnit
 *
 * @property int $permission_type_id
 * @property int $unit_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionTypeUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionTypeUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionTypeUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionTypeUnit wherePermissionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionTypeUnit whereUnitId($value)
 * @mixin \Eloquent
 */
class PermissionTypeUnit extends Model
{
    use Cachable;
    protected $table = 'el_permission_type_unit';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'permission_type_id',
        'unit_id',
    ];



    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    public static function getAttributeName() {
        return [
            'permission_type_id' => 'Loại quyền',
            'unit_id' => trans('lamenu.unit'),
        ];
    }

    public static function conditionUnitGroup($permission_type_id)
    {
        $user_unit = Profile::getUnitIdPermission();
        $records=self::join('el_unit','el_permission_type_unit.unit_id','=','el_unit.id')
            ->where(['el_permission_type_unit.permission_type_id'=>$permission_type_id,'el_permission_type_unit.type'=>'group-child'])
            ->where(['el_unit.id'=>$user_unit])
            ->select('el_permission_type_unit.unit_id','el_unit.level')->get();
        $condition =[];
        foreach ($records as $record) {
            $level = $record->level;
            $unit_id = $record->unit_id;
            $condition[] = "unit{$level}_id = {$unit_id}";
        }
        return count($condition)>0? " (". implode(' or ',$condition).") ":"";
    }
}
