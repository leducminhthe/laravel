<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\PlanAppStatus
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanAppStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanAppStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanAppStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanAppStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanAppStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanAppStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanAppStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PlanAppStatus extends Model
{
    use Cachable;
    protected $table = 'el_plan_app_status';
    protected $fillable = [
        'id',
        'name',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => trans("latraining.status")
        ];
    }

    public static function getStatus($id)
    {
        if ($id)
            return self::query()->where('id','=',$id)->value('name');
        return trans('app.planning');
    }
}
