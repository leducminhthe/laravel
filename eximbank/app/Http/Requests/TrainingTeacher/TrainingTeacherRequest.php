<?php

namespace App\Http\Requests\TrainingTeacher;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class TrainingTeacherRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'type' => 'required_if:id,<>,|in:1,2',
            'code' => 'required|unique:el_training_teacher,code,',
            'name' => 'required',
            'status' => 'required|in:0,1',
            'phone' => 'required',
            'email' => 'required',
        ];
    }

    public function updateRules(): array
    {
        $training_teacher = \request()->route('training_teacher');
        return [
            'type' => 'required_if:id,<>,|in:1,2',
            'code' => 'required|unique:el_training_teacher,code,'. $training_teacher,
            'name' => 'required',
            'status' => 'required|in:0,1',
            'phone' => 'required',
            'email' => 'required',
        ];
    }
}
