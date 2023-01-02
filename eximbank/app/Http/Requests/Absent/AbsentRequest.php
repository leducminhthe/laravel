<?php

namespace App\Http\Requests\Absent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Orion\Http\Requests\Request;

class AbsentRequest extends Request
{
    public function storeRules() : array
    {
        return [
            'code' => 'required|unique:el_absent,code,',
            'name' => 'required',
            'status' => 'required|in:0,1',
        ];
    }

    public function updateRules() : array
    {
        $absent = \request()->route('absent');
        return [
            'code' => 'required|unique:el_absent,code,'.$absent,
            'name' => 'required',
            'status' => 'required|in:0,1',
        ];
    }
}
