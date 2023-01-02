<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineSurveyUserAnswerMatrix extends Model
{
    protected $table = 'offline_survey_user_answer_matrix';
    protected $fillable = [
        'survey_user_question_id',
        'answer_code',
        'answer_row_id',
        'answer_col_id',
    ];
    protected $primaryKey = 'id';
}
