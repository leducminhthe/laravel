<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

class OnlineCourseActivitySurvey extends Model
{
    protected $table = 'el_online_course_activity_survey';
    protected $fillable = [
        'course_id',
        'survey_template_id',
        'description',
    ];
}
