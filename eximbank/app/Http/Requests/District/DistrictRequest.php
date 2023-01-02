<?php

namespace App\Http\Requests\District;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class DistrictRequest extends Request
{
    public function storeRules() : array
    {
        return [
            'name' => 'required|max:250',
            'province_id'=> 'required|integer|min:1|exists:el_province,id',
        ];
    }

    public function updateRules() : array
    {
        return [
            'name' => 'required|max:250',
            'province_id'=> 'required|integer|min:1|exists:el_province,id',
        ];
    }
}
