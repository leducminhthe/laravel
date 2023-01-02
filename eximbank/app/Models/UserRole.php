<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserRole
 *
 * @property int $user_id
 * @property int $role_id
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereRoleId($value)
 * @mixin \Eloquent
 */
class UserRole extends Model
{
    use Cachable;
    protected $table = 'el_user_role';
    protected $table_name = 'Vai trò của Nhân viên';
//    protected $primaryKey = ['user_id', 'stock_id'];
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'role_id',
    ];
}
