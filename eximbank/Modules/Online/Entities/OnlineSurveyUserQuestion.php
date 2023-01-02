<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

class OnlineSurveyUserQuestion extends Model
{
    protected $table = 'el_online_survey_user_question';
    protected $fillable = [
        'survey_user_category_id',
        'question_id',
        'question_code',
        'question_name',
    ];
    protected $primaryKey = 'id';

    public function answers()
    {
        return $this->hasMany('Modules\Online\Entities\OnlineSurveyUserAnswer', 'survey_user_question_id');
    }

    public function answers_matrix()
    {
        return $this->hasMany('Modules\Online\Entities\OnlineSurveyUserAnswerMatrix', 'survey_user_question_id');
    }
}
