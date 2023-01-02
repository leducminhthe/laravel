<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineSurveyCategory extends Model
{
    protected $table = 'offline_survey_category';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'course_id',
        'course_activity_id',
        'template_id',
    ];
}
