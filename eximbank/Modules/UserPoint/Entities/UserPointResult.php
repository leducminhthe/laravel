<?php

namespace Modules\UserPoint\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\UserPoint\Entities\UserPointResult
 *
 * @property int $id
 * @property int $setting_id
 * @property int $user_id
 * @property string $content
 * @property int $point
 * @property int|null $ref
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointResult query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointResult whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointResult wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointResult whereRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointResult whereSettingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointResult whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointResult whereUserId($value)
 * @mixin \Eloquent
 */
class UserPointResult extends Model
{
	protected $table="el_userpoint_result";
    protected $table_name = 'Kết quả điểm thưởng';
    protected $fillable = [
        "setting_id",
        "user_id",
        "content",
        "point",
        "item_id",
        "type",
        "ref",
        "type_promotion",
    ];

	public static function getAttributeName() {
        return [
            'setting_id' => 'Setting',
            'user_id' => trans("latraining.student"),
            'content' => trans("latraining.content"),
            'point' => trans('latraining.score'),
            'ref' => 'Tham chiếu'
        ];
    }

}
