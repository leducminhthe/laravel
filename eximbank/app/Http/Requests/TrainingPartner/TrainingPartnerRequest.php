<?php

namespace App\Http\Requests\TrainingPartner;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class TrainingPartnerRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'code' => 'required|unique:el_training_partner,code,',
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric',
        ];
    }

    public function updateRules(): array
    {
        $training_partner = \request()->route('training_partner');
        return [
            'code' => 'required|unique:el_training_partner,code,'. $training_partner,
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric',
        ];
    }
}
