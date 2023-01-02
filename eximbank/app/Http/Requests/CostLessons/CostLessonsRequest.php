<?php

namespace App\Http\Requests\CostLessons;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class CostLessonsRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'name' => 'required',
            'cost' => 'required',
            'status' => 'required|in:0,1',
        ];
    }
}
