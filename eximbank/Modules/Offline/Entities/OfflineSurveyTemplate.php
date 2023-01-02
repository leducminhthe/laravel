<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineSurveyTemplate extends Model
{
    protected $table = 'offline_survey_template';
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
