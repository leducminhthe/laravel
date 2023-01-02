<?php

namespace Modules\UserMedal\Entities;

use App\Models\BaseModel;
use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\UserMedal\Entities\UserMedal
 *
 * @property int $id
 * @property string $code
 * @property string|null $name
 * @property string|null $photo
 * @property int|null $parent_id
 * @property string|null $content
 * @property int|null $rank
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedal query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedal whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedal whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedal whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedal whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedal whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedal wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedal whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMedal whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class UserMedal extends BaseModel
{
    use Cachable;
	protected $table="el_usermedal";
    protected $table_name = 'Huy hiệu thi đua';
    protected $fillable = ["code","name","photo","parent_id","content","rank","status","rule"];
}
