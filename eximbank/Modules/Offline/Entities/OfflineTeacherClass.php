<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineTeacherClass extends Model
{
    protected $table = 'el_offline_teacher_class';
    protected $fillable = [
        'class_id',
        'course_id',
        'teacher_id',
    ];
    protected $primaryKey = 'id';
}
