<?php

namespace Modules\BotConfig\Entities;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\BotConfig\Entities\BotConfigAnswer
 *
 * @property int $id
 * @property string|null $answer
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigAnswer whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigAnswer whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigAnswer whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigAnswer whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\BotConfig\Entities\BotConfigQuestion[] $questions
 * @property-read int|null $questions_count
 */
class BotConfigAnswer extends Model
{
    use Cachable;
    protected $table = 'bot_config_answer';
    protected $fillable = [
        'answer',
        'unit_by'
    ];
    public function questions(){
        return $this->belongsToMany(BotConfigQuestion::class,'bot_config_answer_question','answer_id','question_id');
    }
}
