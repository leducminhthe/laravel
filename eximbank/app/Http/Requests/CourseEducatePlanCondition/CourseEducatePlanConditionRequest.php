<?php

namespace App\Http\Requests\CourseEducatePlanCondition;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class CourseEducatePlanConditionRequest extends Request
{
   public function commonRules(): array
   {
       return [
           'ratio' => 'nullable|numeric|min:1|max:100',
           'minscore' => 'nullable|numeric|min:1',
           'survey' => 'nullable|integer|in:0,1',
           'certificate' => 'nullable|integer|in:0,1',
       ];
   }

   public function storeRules(): array
   {
       return [
           'course_id' => 'required|exists:el_course_educate_plan,id|unique:el_course_educate_plan_condition,course_id,',
       ];
   }

    public function updateRules(): array
    {
        $course_educate_plan_condition = \request()->route('course_educate_plan_condition');
        return [
            'course_id' => 'required|exists:el_course_educate_plan,id|unique:el_course_educate_plan_condition,course_id,'.$course_educate_plan_condition,
        ];
    }
}
