<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;


/**
 * App\PermissionGroup
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionGroup whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionGroup whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class PermissionGroup extends Model
{
    use Cachable;
    protected $table = 'el_permission_group';
    protected $primaryKey = 'id';
    protected $fillable = ['name'];

    public static function getAttributeName() {
        return [
            'name' => trans('laother.permission_group_name')
        ];
    }
}
