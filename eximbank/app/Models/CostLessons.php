<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;


/**
 * App\CostLessons
 *
 * @property int $id
 * @property string $name
 * @property int $cost
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CostLessons newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CostLessons newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CostLessons query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CostLessons whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CostLessons whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CostLessons whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CostLessons whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CostLessons whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CostLessons whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CostLessons extends Model
{
    use Cachable;
    protected $table = 'el_cost_lessons';
    protected $table_name = "Chi phí tiết giảng";
    protected $fillable = [
        'name',
        'cost',
        'status'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'cost' => 'Chi phí tiết giảng',
            'name' => 'Tên chi phí tiết giảng',
            'status' => trans("latraining.status")
        ];
    }
}
