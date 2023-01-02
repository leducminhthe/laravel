<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserPermissionType
 *
 * @property int $user_id
 * @property int $permission_id
 * @property int $permission_type_id
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermissionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermissionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermissionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermissionType wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermissionType wherePermissionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermissionType whereUserId($value)
 * @mixin \Eloquent
 */
class UserPermissionType extends Model
{
    use Cachable;
    protected $table = 'el_user_permission_type';
//    protected $primaryKey = ['user_id', 'stock_id'];
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'permission_id',
        'permission_type_id',
    ];
}
