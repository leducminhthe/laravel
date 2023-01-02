<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineCourseActivityCompletion extends Model
{
    protected $table = 'offline_course_activity_completion';
    protected $fillable = [
        'user_id',
        'user_type',
        'activity_id',
        'course_id',
        'status',
    ];
}
