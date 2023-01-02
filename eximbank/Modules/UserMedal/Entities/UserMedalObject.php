<?php

namespace Modules\UserMedal\Entities;

use App\Models\CacheModel;
use App\Traits\ChangeLogs;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\UserMedal\Entities\UserMedalObject
 *
 * @property int $id
 * @property int $survey_id
 * @property int|null $title_id
 * @property int|null $unit_id
 * @property int|null $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\UserMedal\Entities\UserMedalObject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\UserMedal\Entities\UserMedalObject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\UserMedal\Entities\UserMedalObject query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\UserMedal\Entities\UserMedalObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\UserMedal\Entities\UserMedalObject whereSurveyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\UserMedal\Entities\UserMedalObject whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\UserMedal\Entities\UserMedalObject whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\UserMedal\Entities\UserMedalObject whereUserId($value)
 * @mixin \Eloquent
 */
class UserMedalObject extends Model
{
    use ChangeLogs, Cachable;
    protected $table = 'el_usermedal_object';
    protected $table_name = 'Đối tượng Chương trình thi đua';
    protected $fillable = [
        'settings_id',
        'unit_id',
        'title_id',
        'user_id',
    ];
    protected $primaryKey = 'id';

    public $timestamps = null;

    public static function checkObjectUnit ($settings_id, $unit_id){
        $query = self::query();
        $query->where('unit_id', '=', $unit_id);
        $query->where('settings_id', '=', $settings_id);
        return $query->exists();
    }
    public static function checkObjectTitle ($settings_id, $title_id){
        $query = self::query();
        $query->where('title_id', '=', $title_id);
        $query->where('settings_id', '=', $settings_id);
        return $query->exists();
    }
}
