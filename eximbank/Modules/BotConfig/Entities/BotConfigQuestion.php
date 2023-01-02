<?php

namespace Modules\BotConfig\Entities;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\BotConfig\Entities\BotConfigQuestion
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigQuestion query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $answer_id
 * @property string $question
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigQuestion whereAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigQuestion whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigQuestion whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigQuestion whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigQuestion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigQuestion whereUpdatedBy($value)
 * @property-read \Modules\BotConfig\Entities\BotConfigAnswer $answer
 */
class BotConfigQuestion extends Model
{
    use Cachable;
    protected $table ='bot_config_question';
    protected $fillable = [
        'answer_id',
        'question',
        'suggest',
        'unit_by'
    ];

    public function answers()
    {
        return $this->belongsToMany(BotConfigAnswer::class,'bot_config_answer_question','question_id','answer_id');
    }
}
