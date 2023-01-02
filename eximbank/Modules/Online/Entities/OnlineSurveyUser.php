<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

class OnlineSurveyUser extends Model
{
    protected $table = 'el_online_survey_user';
    protected $fillable = [
        'template_id',
        'user_id',
        'course_id',
        'course_activity_id',
        'send',
    ];
    protected $primaryKey = 'id';
}
