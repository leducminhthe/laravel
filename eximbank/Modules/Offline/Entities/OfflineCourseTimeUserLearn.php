<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineCourseTimeUserLearn extends Model
{
    protected $table = 'offline_course_time_user_learn';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'user_id',
        'time',
    ];

    public static function getAttributeName() {
        return [
            'course_id' => trans('lamenu.course'),
            'time' => 'Thời gian',
        ];
    }
}
