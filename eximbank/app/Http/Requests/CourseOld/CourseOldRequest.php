<?php

namespace App\Http\Requests\CourseOld;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class CourseOldRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'course_type' => 'nullable|in:1,2',
            'course_id' => 'nullable|exists:el_subject,id',
            'course_code' => 'nullable|exists:el_subject,code',
            'user_code' => 'nullable|exists:el_profile,code',
        ];
    }
}
