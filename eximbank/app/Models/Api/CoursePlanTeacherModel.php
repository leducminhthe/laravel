<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePlanTeacherModel extends Model
{
    use HasFactory;
    protected $table = 'el_course_plan_teacher';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'course_type',
        'teacher_id',
    ];
}
