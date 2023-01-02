<?php

namespace App\Http\Requests\Province;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class ProvinceRequest extends Request
{
    public function storeRules() : array
    {
        return [
            'code' => 'required|unique:el_province,code,',
            'name' => 'required|max:250',
        ];
    }

    public function updateRules() : array
    {
        $province = \request()->route('province');
        return [
            'code' => 'required|unique:el_province,code,'.$province,
            'name' => 'required|max:250',
        ];
    }
}
