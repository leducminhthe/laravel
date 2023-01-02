<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RolePermissionType
 *
 * @property int $role_id
 * @property int $permission_id
 * @property int $permission_type_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RolePermissionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RolePermissionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RolePermissionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RolePermissionType wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RolePermissionType wherePermissionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RolePermissionType whereRoleId($value)
 * @mixin \Eloquent
 */
class RolePermissionType extends Model
{
    use Cachable;
    protected $table = 'el_role_permission_type';
    protected $table_name = 'Nhóm quyền trong vai trò';
    protected $primaryKey = ['user_id', 'stock_id'];
    public $timestamps = false;
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'permission_id',
        'permission_type_id',
    ];



    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */


}
