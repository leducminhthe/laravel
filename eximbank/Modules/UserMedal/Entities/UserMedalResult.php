<?php

namespace Modules\UserMedal\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\UserMedal\Entities\UserMedalResult
 *
 * @property int $id
 * @property int $settings_items_id
 * @property int $user_id
 * @property string $content
 * @property int $point
 * @property int|null $ref
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalResult query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalResult whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalResult wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalResult whereRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalResult whereSettingsItemsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalResult whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalResult whereUserId($value)
 * @mixin \Eloquent
 */
class UserMedalResult extends Model
{
     use Cachable;
    protected $table="el_usermedal_result";
    protected $table_name = 'Kết quả Chương trình thi đua';
    protected $fillable = ["settings_items_id","settings_items_id_got","user_id","content","point","ref"];
}
