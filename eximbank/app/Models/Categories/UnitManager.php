<?php

namespace App\Models\Categories;

use App\Models\CacheModel;
use App\Models\Permission;
use App\Models\PermissionUser;
use App\Models\Profile;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Categories\UnitManager
 *
 * @property string $unit_code
 * @property string $user_code
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitManager newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitManager newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitManager query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitManager whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitManager whereUnitCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitManager whereUserCode($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|Profile[] $managers
 * @property-read int|null $managers_count
 * @property int $manager_type 1: TĐV chính thức, 2: TĐV được ủy quyền
 * @method static \Illuminate\Database\Eloquent\Builder|UnitManager whereManagerType($value)
 */
class UnitManager extends Model
{
    use Cachable;
    protected $table = 'el_unit_manager';
    protected $table_name = "Quản lý đơn vị (TĐV)";
    protected $fillable = [
        'unit_code',
        'user_code',
        'user_id',
        'unit_id',
        'manager_type',
        'type',
        'type_manager',
    ];
    // protected $primaryKey = false;
    public $timestamps = false;

    public static function getAttributeName() {
        return [
            'unit_code' => 'Mã đơn vị',
            'user_code' => 'Mã nhân viên',
            'status' => trans("latraining.status"),
        ];
    }

    public static function getUnitManager($unit_code){
        $query = UnitManager::query();
        $query->select(['a.type_manager', 'b.user_id', 'b.code AS user_code', 'b.lastname AS user_lastname', 'b.firstname AS user_firstname']);
        $query->from('el_unit_manager AS a');
        $query->join('el_profile AS b', 'b.code', '=', 'a.user_code');
        $query->where('a.unit_code', '=', $unit_code);
        return $query->get();
    }

    public static function getArrayUnitManagedByUser($code = null, &$result = []) {
        $code = empty($code) ? profile()->code : $code;
        $query = self::query();
        $query->select(['a.unit_code', 'c.id'])
            ->from('el_unit_manager AS a')
            ->join('el_profile AS b', 'b.code', '=', 'a.user_code')
            ->join('el_unit AS c', 'c.code', '=', 'a.unit_code')
            ->where('b.code', '=', $code);
        $rows = $query->get();
        foreach ($rows as $row) {
            $result[] = $row->id;
            Unit::getArrayChild($row->unit_code, $result);
        }

        return $result;
    }

    public static function getIdUnitManagedByUser($user_id = null) {
        $user_id = empty($user_id) ? profile()->user_id : $user_id;
        $result = [];

        $query = self::query();
        $query->select(['c.id', 'c.code'])
            ->from('el_unit_manager AS a')
            ->join('el_profile AS b', 'b.code', '=', 'a.user_code')
            ->join('el_unit AS c', 'c.code', '=', 'a.unit_code')
            ->where('b.user_id', '=', $user_id);
        $rows = $query->get();
        foreach ($rows as $row) {
            $result[] = $row->id;
            Unit::getArrayChild($row->code, $result);
        }
        return $result;
    }

    public static function getArrayChild($unit_code, &$result = []) {
        $rows = Unit::where('parent_code', '=', $unit_code)->get();
        foreach ($rows as $row) {
            $result[] = $row->id;
            self::getArrayChild($row->code, $result);
        }

        return $result;
    }

    public static function getIdUnitPermissionByUser($parent_code, $user_id = null) {
        $user_id = empty($user_id) ? profile()->user_id : $user_id;
        $permission_child = Permission::getArrayCodeChild($parent_code);
        return PermissionUser::where('user_id', '=', $user_id)
            ->whereIn('permission_code', $permission_child)
            ->pluck('unit_id')
            ->toArray();
    }

    public static function getManagerOfUser($user_id) {
        $profile = Profile::find($user_id);
        if (empty($profile)) {
            return [];
        }

        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        if (empty($unit)) {
            return [];
        }

        $query = UnitManager::query();
        $query->from('el_unit_manager AS manager')
            ->join('el_profile AS profile', 'profile.code', '=', 'manager.user_code')
            ->where('manager.unit_code', '=', $profile->unit_code);

        return $query->pluck('profile.user_id')->toArray();
    }

    public function managers()
    {
        return $this->hasMany(Profile::class,'code','user_code');
    }
    public static function getCodeUnitManagedByUser($user_id = null) {
        $user_id = empty($user_id) ? profile()->user_id : $user_id;
        $result = [];
        $query = self::query();
        $query->select(['c.id', 'c.code'])
            ->from('el_unit_manager AS a')
            ->join('el_profile AS b', 'b.code', '=', 'a.user_code')
            ->join('el_unit AS c', 'c.code', '=', 'a.unit_code')
            ->where('b.user_id', '=', $user_id);
        $rows = $query->get();

        foreach ($rows as $row) {
            $result[] = $row->code;
            Unit::getArrayChild($row->code, $result);
        }

        return $result;
    }
    public static function getUnitManagedByUser($multiple = false) {
        $user_id = profile()->user_id;
        $query = self::query();
        $query->select(['c.id','c.code','c.name','a.type_manager'])
            ->from('el_unit_manager AS a')
            ->join('el_unit AS c', 'c.id', '=', 'a.unit_id')
            ->where('a.user_id', '=', $user_id);
        if ($multiple)
            return $query->get();
        return $query->first();
    }

}
