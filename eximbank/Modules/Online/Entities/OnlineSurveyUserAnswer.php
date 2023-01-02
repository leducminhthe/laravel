<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

class OnlineSurveyUserAnswer extends Model
{
    protected $table = 'el_online_survey_user_answer';
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
