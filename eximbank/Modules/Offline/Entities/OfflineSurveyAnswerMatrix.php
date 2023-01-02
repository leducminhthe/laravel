<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineSurveyAnswerMatrix extends Model
{
    protected $table = 'offline_survey_answer_matrix';
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
