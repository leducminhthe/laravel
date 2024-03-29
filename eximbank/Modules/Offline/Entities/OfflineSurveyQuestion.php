<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineSurveyQuestion extends Model
{
    protected $table = 'offline_survey_question';
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
        return $this->hasMany('Modules\Offline\Entities\OfflineSurveyAnswer', 'survey_user_question_id');
    }
}
