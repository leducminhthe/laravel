<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

class OnlineSurveyTemplate extends Model
{
    protected $table = 'el_online_survey_template';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'course_id',
        'course_activity_id',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
