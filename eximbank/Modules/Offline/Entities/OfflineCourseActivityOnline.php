<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineCourseActivityOnline extends Model
{
    protected $table = 'offline_course_activity_online';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'online_id',
        'description',
    ];
}
