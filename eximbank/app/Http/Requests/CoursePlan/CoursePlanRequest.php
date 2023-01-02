<?php

namespace App\Http\Requests\CoursePlan;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class CoursePlanRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'course_type' => 'required|in:1,2',
            'name' => 'required',
            'auto' => 'required|in:0,1',
            'isopen' => 'required|in:0,1',
            'start_date' => 'required',
            'created_by' => 'required',
            'updated_by' => 'required',
            'training_program_id' => 'required|exists:el_training_program,id',
            'subject_id' => 'required|exists:el_subject,id',
            'in_plan' => 'nullable|exists:el_training_plan,id',
            'status' => 'required|in:0,1,2',
            'views' => 'required|min:0',
            'action_plan' => 'required|in:0,1',
            'plan_app_template' => 'required_if:action_plan,1|nullable|integer',
            'plan_app_day' => 'required_if:action_plan,1|nullable|integer|max:1000',
            'status_convert' => 'required|in:0,1',
        ];
    }
}
