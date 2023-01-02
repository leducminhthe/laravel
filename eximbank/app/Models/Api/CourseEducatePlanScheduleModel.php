<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEducatePlanScheduleModel extends Model
{
    use HasFactory;
    protected $table = 'el_course_educate_plan_schedule';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'start_time',
        'end_time',
        'lesson_date',
        'teacher_main_id',
        'teach_id',
        'cost_teacher_main',
        'cost_teach_type',
        'total_lessons',
    ];
}
