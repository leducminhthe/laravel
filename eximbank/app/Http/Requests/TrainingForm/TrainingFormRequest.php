<?php

namespace App\Http\Requests\TrainingForm;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class TrainingFormRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'code' => 'required|unique:el_training_form,code,',
            'name' => 'required',
            'training_type_id' => 'required|exists:el_training_type,id',
        ];
    }

    public function updateRules(): array
    {
        $training_form = \request()->route('training_form');
        return [
            'code' => 'required|unique:el_training_form,code,'. $training_form,
            'name' => 'required',
            'training_type_id' => 'required|exists:el_training_type,id',
        ];
    }
}
