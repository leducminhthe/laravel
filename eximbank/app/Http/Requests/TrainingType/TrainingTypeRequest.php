<?php

namespace App\Http\Requests\TrainingType;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class TrainingTypeRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'code' => 'required|unique:el_training_type,code,',
            'name' => 'required',
        ];
    }

    public function updateRules(): array
    {
        $training_type = \request()->route('training_type');
        return [
            'code' => 'required|unique:el_training_type,code,'. $training_type,
            'name' => 'required',
        ];
    }
}
