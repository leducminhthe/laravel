<?php

namespace App\Http\Requests\TrainingProgram;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class TrainingProgramRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'code' => 'required|unique:el_training_program,code,',
            'name' => 'required',
            'status' => 'required|in:0,1'
        ];
    }

    public function updateRules(): array
    {
        $training_program = \request()->route('training_program');
        return [
            'code' => 'required|unique:el_training_program,code,'. $training_program,
            'name' => 'required',
            'status' => 'required|in:0,1'
        ];
    }
}
