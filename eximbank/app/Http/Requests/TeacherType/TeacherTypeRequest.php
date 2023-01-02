<?php

namespace App\Http\Requests\TeacherType;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class TeacherTypeRequest extends Request
{
    public function storeRules() : array
    {
        return [
            'code' => 'required|unique:el_teacher_type,code,',
            'name' => 'required',
            'status' => 'required|in:1,0',
        ];
    }

    public function updateRules() : array
    {
        $teacher_type = \request()->route('teacher_type');
        return [
            'code' => 'required|unique:el_teacher_type,code,'. $teacher_type,
            'name' => 'required',
            'status' => 'required|in:1,0',
        ];
    }
}
