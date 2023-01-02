<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

class OnlineSurveyAnswer extends Model
{
    protected $table = 'el_online_survey_answer';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'course_id',
        'course_activity_id',
        'question_id',
        'is_text',
        'is_row',
        'icon',
    ];
}
