<?php

namespace App\Http\Requests\CoursePlanSchedule;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class CoursePlanScheduleRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'course_id' => 'required|exists:el_course_plan,id',
            'course_type' => 'required|in:1,2',
            'start_time' => 'required',
            'end_time' => 'required',
            'lesson_date' => 'required',
            'teacher_main_id' => 'required|exists:el_training_teacher,id',
            'teach_id' => 'nullable|exists:el_training_teacher,id',
            'cost_teacher_main' => 'required|min:0',
            'cost_teach_type' => 'nullable|min:0',
            'total_lessons' => 'required|numeric',
        ];
    }
}
