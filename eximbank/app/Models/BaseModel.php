<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use function Clue\StreamFilter\fun;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

/**
 * App\Models\BaseModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel query()
 * @mixin \Eloquent
 */
class BaseModel extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function($model)
        {
            $user_id = profile()->user_id ?? 0;
            $unit_by = session('user_unit') ?? Profile::getUnitId() ?? null;

            $model->created_by = is_null($model->created_by) ? $user_id : $model->created_by;
            $model->updated_by = is_null($model->updated_by) ? $user_id : $model->updated_by;
            $model->unit_by = is_null($model->unit_by) ? $unit_by : $model->unit_by;
        });
        static::updating(function($model)
        {
            $user_id = profile()->user_id ?? 0;
            $unit_by = session('user_unit') ?? Profile::getUnitId() ?? null;

            $model->updated_by = is_null($model->updated_by) ? $user_id : $model->updated_by;
            $model->unit_by = is_null($model->unit_by) ? $unit_by : $model->unit_by;

        });
    }
}
