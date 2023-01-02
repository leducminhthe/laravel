<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineSurveyUserQuestion extends Model
{
    protected $table = 'offline_survey_user_question';
    protected $fillable = [
        'survey_user_category_id',
        'question_id',
        'question_code',
        'question_name',
    ];
    protected $primaryKey = 'id';

    public function answers()
    {
        return $this->hasMany('Modules\Offline\Entities\OfflineSurveyUserAnswer', 'survey_user_question_id');
    }

    public function answers_matrix()
    {
        return $this->hasMany('Modules\Offline\Entities\OfflineSurveyUserAnswerMatrix', 'survey_user_question_id');
    }
}
