<?php

namespace Modules\UserMedal\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\UserMedal\Entities\UserMedalCompleted
 *
 * @property int $id
 * @property int $settings_id
 * @property int $settings_items_id_got
 * @property int $user_id
 * @property int $point
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalCompleted newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalCompleted newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalCompleted query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalCompleted whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalCompleted whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalCompleted wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalCompleted whereSettingsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalCompleted whereSettingsItemsIdGot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalCompleted whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalCompleted whereUserId($value)
 * @mixin \Eloquent
 */
class UserMedalCompleted extends Model
{
	use Cachable;
    protected $table="el_usermedal_completed";
    protected $table_name = 'Kết quả Chương trình thi đua';
    protected $fillable = ["settings_id","settings_items_id_got","user_id","point"];
}
