<?php

namespace Modules\UserPoint\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\UserPoint\Entities\UserPointSettings
 *
 * @property int $id
 * @property string $pkey
 * @property string $pvalue
 * @property int $item_id
 * @property int|null $item_type
 * @property int $start_date
 * @property int|null $end_date
 * @property string|null $min_score
 * @property string|null $max_score
 * @property string|null $note
 * @property int|null $ref
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointSettings query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointSettings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointSettings whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointSettings whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointSettings whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointSettings whereItemType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointSettings whereMaxScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointSettings whereMinScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointSettings whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointSettings wherePkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointSettings wherePvalue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointSettings whereRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointSettings whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointSettings whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointSettings whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class UserPointSettings extends Model
{
    use Cachable;
	protected $table="el_userpoint_settings";
    protected $table_name = 'Thiết lập điểm thưởng';
    protected $fillable = [
        "pkey",
        "pvalue",
        "item_id",
        "item_type",
        "start_date",
        "end_date",
        "min_score",
        "max_score",
        "note",
        "ref",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    public static function getAttributeName() {
        return [
            'pkey' => 'Từ khóa',
            'pvalue' => trans('latraining.score'),
            'start_date' => trans('latraining.start_date'),
            'end_date' => trans('latraining.end_date'),
            'min_score' => 'Từ',
            'max_score' => 'Đến',

        ];
    }
}
