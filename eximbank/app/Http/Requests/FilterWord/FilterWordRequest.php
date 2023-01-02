<?php

namespace App\Http\Requests\FilterWord;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class FilterWordRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'name' => 'required',
            'status' => 'required|in:0,1',
        ];
    }
}
