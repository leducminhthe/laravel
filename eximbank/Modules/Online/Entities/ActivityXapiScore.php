<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

class ActivityXapiScore extends Model
{
    protected $table = 'el_activity_xapi_scores';
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
        return $this->hasOne('Modules\Online\Entities\OnlineCourseActivity', 'id', 'activity_id');
    }

    public function attempt() {
        return $this->hasOne(ActivityXapiAttempt::class, 'id', 'attempt_id');
    }
}
