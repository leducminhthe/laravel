<?php

namespace App\Http\Requests\Discipline;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class DisciplineRequest extends Request
{
    public function storeRules() : array
    {
        return [
            'code' => 'required|unique:el_discipline,code,',
            'name' => 'required',
            'status' => 'required|in:0,1',
        ];
    }

    public function updateRules() : array
    {
        $discipline = \request()->route('discipline');
        return [
            'code' => 'required|unique:el_discipline,code,'.$discipline,
            'name' => 'required',
            'status' => 'required|in:0,1',
        ];
    }
}
