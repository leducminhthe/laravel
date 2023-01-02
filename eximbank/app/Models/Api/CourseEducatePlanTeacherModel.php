<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEducatePlanTeacherModel extends Model
{
    use HasFactory;
    protected $table = 'el_course_educate_plan_teacher';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'teacher_id',
    ];
}
