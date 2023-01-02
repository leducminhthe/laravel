<?php

namespace App\Http\Requests\TrainingLocation;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class TrainingLocationRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'code' => 'required|unique:el_training_location,code,',
            'name' => 'required',
            'status' => 'required|in:0,1',
            'province_id' => 'required|exists:el_province,id',
            'district_id' => 'required|exists:el_district,id',
        ];
    }

    public function updateRules(): array
    {
        $training_location = \request()->route('training_location');
        return [
            'code' => 'required|unique:el_training_location,code,'. $training_location,
            'name' => 'required',
            'status' => 'required|in:0,1',
            'province_id' => 'required|exists:el_province,id',
            'district_id' => 'required|exists:el_district,id',
        ];
    }
}
