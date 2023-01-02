<?php

namespace App\Http\Requests\CourseEducatePlanCost;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class CourseEducatePlanCostRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'course_id' => 'required|exists:el_course_educate_plan,id',
            'cost_id' => 'required',
            'plan_amount' => 'nullable|numeric|min:0',
            'actual_amount' => 'nullable|numeric|min:0',
        ];
    }
}
