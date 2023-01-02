<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineSurveyUser extends Model
{
    protected $table = 'offline_survey_user';
    protected $fillable = [
        'template_id',
        'user_id',
        'course_id',
        'course_activity_id',
        'send',
    ];
    protected $primaryKey = 'id';
}
