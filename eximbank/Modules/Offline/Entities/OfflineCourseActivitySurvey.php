<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineCourseActivitySurvey extends Model
{
    protected $table = 'offline_course_activity_survey';
    protected $fillable = [
        'course_id',
        'survey_template_id',
        'description',
    ];
}
