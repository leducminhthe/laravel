<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineSurveyUserAnswer extends Model
{
    protected $table = 'offline_survey_user_answer';
    protected $fillable = [
        'survey_user_question_id',
        'answer_id',
        'answer_code',
        'answer_name',
        'text_answer',
        'icon',
    ];
    protected $primaryKey = 'id';
}
