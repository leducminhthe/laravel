<?php

namespace App\Http\Requests\UnitTypeCode;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class UnitTypeCodeRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'code' => 'required|unique:el_unit_type_code,code,',
            'unit_type_id' => 'required|exists:el_unit_type,id',
        ];
    }

    public function updateRules(): array
    {
        $unit_type_code = \request()->route('unit_type_code');
        return [
            'code' => 'required|unique:el_unit_type_code,code,'. $unit_type_code,
            'unit_type_id' => 'required|exists:el_unit_type,id',
        ];
    }
}
