<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\PermissionType
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property string|null $description
 * @property int|null $sort
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionType whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionType whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionType whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class PermissionType extends BaseModel
{
    use Cachable;
    protected $table = 'el_permission_type';
    protected $table_name = "Nhóm quyền";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'description',
        'sort',
    ];



    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    public static function getAttributeName() {
        return [
            'name' => 'Tên',
            'type' => 'Loại',
            'description' => 'Miêu tả',
        ];
    }

    public static function getPermissionType($type=2)
    {
        return PermissionType::select(['id','name','type','description'])->where('type', '=', $type)->get();
    }

}
