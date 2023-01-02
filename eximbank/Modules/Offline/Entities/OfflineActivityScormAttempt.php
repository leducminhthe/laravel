<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineActivityScormAttempt extends Model
{
    protected $table = 'offline_activity_scorm_attempts';
    protected $fillable = [
        'activity_id',
        'user_id',
        'user_type',
        'attempt',
        'lesson_location',
        'suspend_data',
    ];

    public function activity_scorm() {
        return $this->hasOne(OfflineCourseActivityScorm::class, 'id', 'activity_id');
    }

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
