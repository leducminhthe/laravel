<?php

namespace App\Http\Requests\CourseEducatePlan;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class CourseEducatePlanRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'name' => 'required',
            'auto' => 'required|in:0,1',
            'isopen' => 'required|in:0,1',
            'start_date' => 'required',
            'created_by' => 'required',
            'updated_by' => 'required',
            'training_program_id' => 'required|exists:el_training_program,id',
            'subject_id' => 'required|exists:el_subject,id',
            'status' => 'required|in:0,1,2',
            'views' => 'required|min:0',
            'in_plan' => 'nullable|exists:el_training_plan,id',
            'image' => 'nullable|string',
            'document' => 'nullable|string',
            'status_convert' => 'required|in:0,1',
            'action_plan' => 'required|in:0,1',
            'plan_app_template' => 'required_if:action_plan,1|nullable|integer',
            'plan_app_day' => 'required_if:action_plan,1|nullable|integer|max:1000',
        ];
    }

    public function storeRules(): array
    {
        return [
            'code' => 'nullable|unique:el_course_educate_plan,code',
        ];
    }

    public function updateRules(): array
    {
        $course_educate_plan = \request()->route('course_educate_plan');
        return [
            'code' => 'nullable|unique:el_course_educate_plan,code,'.$course_educate_plan,
        ];
    }
}
