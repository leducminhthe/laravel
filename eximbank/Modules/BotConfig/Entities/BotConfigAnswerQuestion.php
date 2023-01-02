<?php

namespace Modules\BotConfig\Entities;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\BotConfig\Entities\BotConfigAnswerQuestion
 *
 * @property int $id
 * @property int $answer_id
 * @property int $question_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigAnswerQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigAnswerQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigAnswerQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigAnswerQuestion whereAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigAnswerQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigAnswerQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigAnswerQuestion whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotConfigAnswerQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BotConfigAnswerQuestion extends Model
{
    use Cachable;
    protected $table = 'bot_config_answer_question';
    protected $fillable = [
        'answer_id',
        'question_id',
    ];

}
