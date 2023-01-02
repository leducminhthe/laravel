<?php

namespace App\Http\Requests\CoursePlanCost;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class CoursePlanCostRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'course_id' => 'required|exists:el_course_plan,id',
            'course_type' => 'required|in:1,2',
            'cost_id' => 'required',
            'plan_amount' => 'nullable|numeric|min:0',
            'actual_amount' => 'nullable|numeric|min:0',
        ];
    }
}
