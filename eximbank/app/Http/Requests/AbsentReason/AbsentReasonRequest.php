<?php

namespace App\Http\Requests\AbsentReason;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class AbsentReasonRequest extends Request
{
    public function storeRules() : array
    {
        return [
            'code' => 'required|unique:el_absent_reason,code,',
            'name' => 'required',
            'status' => 'required|in:0,1',
            'group' => 'nullable',
            'unit_id' => 'nullable|exists:el_unit,id'
        ];
    }

    public function updateRules() : array
    {
        $absent_reason = \request()->route('absent_reason');
        return [
            'code' => 'required|unique:el_absent_reason,code,'. $absent_reason,
            'name' => 'required',
            'status' => 'required|in:0,1',
            'group' => 'nullable',
            'unit_id' => 'nullable|exists:el_unit,id'
        ];
    }
}
