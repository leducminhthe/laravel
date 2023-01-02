<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

class OnlineSurveyUserAnswerMatrix extends Model
{
    protected $table = 'el_online_survey_user_answer_matrix';
    protected $fillable = [
        'survey_user_question_id',
        'answer_code',
        'answer_row_id',
        'answer_col_id',
    ];
    protected $primaryKey = 'id';
}
