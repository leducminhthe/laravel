<?php

namespace App\Http\Requests\TrainingObject;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class TrainingObjectRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'code' => 'required|unique:el_training_object,code,',
            'name' => 'required',
            'status' => 'required|in:0,1',
        ];
    }

    public function updateRules(): array
    {
        $training_object = \request()->route('training_object');
        return [
            'code' => 'required|unique:el_training_object,code,'. $training_object,
            'name' => 'required',
            'status' => 'required|in:0,1',
        ];
    }
}
