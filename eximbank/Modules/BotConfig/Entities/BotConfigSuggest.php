<?php

namespace Modules\BotConfig\Entities;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\BotConfig\Entities\BotConfigSuggest
 *
 * @property int $id
 * @property string|null $name suggest
 * @property string|null $parent_id
 * @property string|null $url
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigSuggest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigSuggest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigSuggest query()
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigSuggest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigSuggest whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigSuggest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigSuggest whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigSuggest whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigSuggest whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigSuggest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigSuggest whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigSuggest whereUrl($value)
 * @mixin \Eloquent
 * @property int|null $level
 * @property-read \Illuminate\Database\Eloquent\Collection|BotConfigSuggest[] $suggests
 * @property-read int|null $suggests_count
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigSuggest whereLevel($value)
 * @property int|null $type
 * @property-read \Illuminate\Database\Eloquent\Collection|BotConfigSuggest[] $childs
 * @property-read int|null $childs_count
 * @property-read BotConfigSuggest|null $parent
 * @method static Builder|BotConfigSuggest suggestFirst()
 * @method static Builder|BotConfigSuggest whereType($value)
 */
class BotConfigSuggest extends Model
{
    use Cachable;
    protected $table ='bot_config_suggest';
    protected $fillable = [
        'name',
        'parent_id',
        'url',
        'level',
        'answer'
    ];
    protected $casts = [
        'parent_id' => 'integer',
    ];
    public function childs()
    {
        return $this->hasMany(BotConfigSuggest::class,'parent_id');
    }
    public function parent()
    {
        return $this->belongsTo(BotConfigSuggest::class,'parent_id');
    }

    public function scopeSuggestFirst(Builder $query)
    {
        return $query->where('parent_id',0);
    }
}
