<?php

namespace App\Http\Requests\CoursePlanObject;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class CoursePlanObjectRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'course_id' => 'required|exists:el_course_plan,id',
            'course_type' => 'required|in:1,2',
            'title_id' => 'nullable|exists:el_titles,id',
            'unit_id' => 'nullable|exists:el_unit,id',
            'unit_level' => 'required_if:unit_id,<>,0|nullable',
            'type' => 'required|in:1,2',
            'created_by' => 'required',
            'updated_by' => 'required',
        ];
    }
}
