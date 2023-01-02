<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineCourseActivityCondition extends Model
{
    protected $table = 'offline_course_activity_condition';
    protected $fillable = [
        'course_id',
        'class_id',
        'schedule_id',
        'course_activity_id',
    ];
}
