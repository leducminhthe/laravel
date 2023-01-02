<?php

namespace Modules\UserMedal\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\UserMedal\Entities\UserMedalSettingsItems
 *
 * @property int $id
 * @property int|null $setting_id
 * @property int $usermedal_id
 * @property string $pvalue
 * @property int $item_id
 * @property int|null $item_type
 * @property int|null $start_date
 * @property int|null $end_date
 * @property string|null $min_score
 * @property string|null $max_score
 * @property string|null $note
 * @property int|null $ref
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems whereItemType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems whereMaxScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems whereMinScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems wherePvalue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems whereRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems whereSettingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettingsItems whereUsermedalId($value)
 * @mixin \Eloquent
 */
class UserMedalSettingsItems extends Model
{
    use Cachable;
	protected $table="el_usermedal_settings_items";
    protected $table_name = 'Hạng mục Chương trình thi đua';
    protected $fillable = ["setting_id","usermedal_id","item_id","item_type","min_score","max_score","note","ref"];
}
