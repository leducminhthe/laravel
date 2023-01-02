<?php

namespace App\Http\Requests\CoursePlanTeacher;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class CoursePlanTeacherRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'course_id' => 'required|exists:el_course_educate_plan,id',
            'course_type' => 'required|in:1,2',
            'teacher_id' => 'required|exists:el_training_teacher,id',
        ];
    }
}
