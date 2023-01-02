<?php

namespace Modules\UserMedal\Entities;

use App\Models\BaseModel;
use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\UserMedal\Entities\UserMedalSettings
 *
 * @property int $id
 * @property int|null $usermedal_id
 * @property int $start_date
 * @property int|null $end_date
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettings query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettings whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettings whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettings whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettings whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedalSettings whereUsermedalId($value)
 * @mixin \Eloquent
 */
class UserMedalSettings extends BaseModel
{
    use Cachable;
	protected $table="el_usermedal_settings";
    protected $table_name = 'Chương trình thi đua';
    protected $fillable = ["usermedal_id","start_date","end_date","status"];
}
