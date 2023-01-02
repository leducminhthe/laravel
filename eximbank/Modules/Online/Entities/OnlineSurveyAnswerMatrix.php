<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

class OnlineSurveyAnswerMatrix extends Model
{
    protected $table = 'el_online_survey_answer_matrix';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'course_activity_id',
        'question_id',
        'code',
        'answer_row_id',
        'answer_col_id',
    ];
}
