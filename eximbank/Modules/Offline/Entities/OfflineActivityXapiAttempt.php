<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineActivityXapiAttempt extends Model
{
    protected $table = 'offline_activity_xapi_attempts';
    protected $fillable = [
        'activity_id',
        'user_id',
        'user_type',
        'attempt',
        'course_id',
        'uuid',
    ];

    public function activity_xapi() {
        return $this->hasOne(OfflineCourseActivityXapi::class, 'id', 'activity_id');
    }

    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
