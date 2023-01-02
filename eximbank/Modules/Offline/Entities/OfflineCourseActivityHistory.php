<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineCourseActivityHistory extends Model
{
    protected $table = 'offline_course_activity_history';

    public function course_activity() {
        return $this->hasOne(OfflineCourseActivity::class, 'id', 'course_activity_id');
    }
}
