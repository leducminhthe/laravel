<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineActivityScormScore extends Model
{
    protected $table = 'offline_activity_scorm_scores';
    protected $fillable = [
        'user_id',
        'user_type',
        'activity_id',
        'attempt_id',
        'score_max',
        'score_min',
        'score_raw',
        'score',
        'status',
    ];

    public function course_activity() {
        return $this->hasOne(OfflineCourseActivity::class, 'id', 'activity_id');
    }

    public function attempt() {
        return $this->hasOne(OfflineActivityScormAttempt::class, 'id', 'attempt_id');
    }
}