<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DonatePoints
 *
 * @property int $id
 * @property int $user_id
 * @property int $score
 * @property string $note
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePoints newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePoints newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePoints query()
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePoints whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePoints whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePoints whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePoints whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePoints whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePoints whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePoints whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonatePoints whereUserId($value)
 * @mixin \Eloquent
 */
class DonatePoints extends BaseModel
{
    use Cachable;
    protected $table = 'el_donate_points';
    protected $table_name = "Tặng điểm";
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'score',
        'note',
        'created_by',
        'updated_by',
    ];

    public static function getAttributeName() {
        return [
            'user_id' => 'Người nhận',
            'score' => trans('latraining.score'),
            'note' => 'Lý do',
            'created_by' => 'Người tặng',
            'updated_by' => 'Người chỉnh sửa',
        ];
    }
}
