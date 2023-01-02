<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizAttemptHistory
 *
 * @property int $id
 * @property int $attempt_id
 * @property string|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAttemptHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAttemptHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAttemptHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAttemptHistory whereAttemptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAttemptHistory whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAttemptHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAttemptHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAttemptHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuizAttemptHistory extends Model
{
    protected $table = 'el_quiz_attempt_history';
    protected $fillable = [
        'attempt_id',
        'content'
    ];
}
