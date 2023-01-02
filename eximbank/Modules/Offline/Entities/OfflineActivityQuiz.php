<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineActivityQuiz extends Model
{
    protected $table = 'offline_course_activity_quiz';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'class_id',
        'schedule_id',
        'quiz_id',
        'description',
    ];
}
