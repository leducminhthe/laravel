<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineSurveyAnswer extends Model
{
    protected $table = 'offline_survey_answer';
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
