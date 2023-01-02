<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

class QuizAttemptsAgain extends Model
{
    protected $table = 'el_quiz_attempts_again';
    protected $fillable = [
        'quiz_id',
        'part_id',
        'user_id',
        'attempt',
    ];
}
