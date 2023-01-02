<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

class OnlineSurveyCategory extends Model
{
    protected $table = 'el_online_survey_category';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'course_id',
        'course_activity_id',
        'template_id',
    ];
}
