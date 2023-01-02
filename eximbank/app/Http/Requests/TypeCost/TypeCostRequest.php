<?php

namespace App\Http\Requests\TypeCost;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class TypeCostRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'name' => 'required',
            'code' => 'required',
        ];
    }
}
