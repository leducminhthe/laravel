<?php

namespace Modules\UserPoint\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\UserPoint\Entities\UserPointItem
 *
 * @property int $id
 * @property string|null $ikey
 * @property string $name
 * @property int|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointItem whereIkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPointItem whereType($value)
 * @mixin \Eloquent
 */
class UserPointItem extends Model
{
    use Cachable;
	protected $table="el_userpoint_item";
    protected $table_name = 'Danh mục Điểm thưởng';
    protected $fillable = ["ikey","name","type","default_value"];
}
