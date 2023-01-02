<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

class OnlineSurveyQuestion extends Model
{
    protected $table = 'el_online_survey_question';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'type',
        'multiple',
        'course_id',
        'course_activity_id',
        'obligatory',
        'num_order',
    ];

    public function answers()
    {
        return $this->hasMany('Modules\Online\Entities\OnlineSurveyAnswer', 'survey_user_question_id');
    }
}
