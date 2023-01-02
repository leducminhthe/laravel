<?php

namespace Modules\PermissionMasterData\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\PermissionMasterData\Entities\MasterData
 *
 * @property int $id
 * @property string $model tên model
 * @property string $description Mô tả
 * @property int $type 1: all, 2 thấy theo công ty, 3 thấy theo phân quyền đơn vị
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MasterData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MasterData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MasterData query()
 * @method static \Illuminate\Database\Eloquent\Builder|MasterData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterData whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterData whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterData whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterData whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MasterData extends Model
{
    protected $table = 'master_data';
    protected $fillable = [
        'model',
        'description',
        'type',
    ];
    protected $primaryKey = 'id';
}
