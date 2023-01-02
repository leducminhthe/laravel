<?php

namespace App\Http\Requests\CourseEducatePlanTeacher;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class CourseEducatePlanTeacherRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'course_id' => 'required|exists:el_course_educate_plan,id',
            'teacher_id' => 'required|exists:el_training_teacher,id',
        ];
    }
}
