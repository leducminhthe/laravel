<?php

namespace App\Http\Requests\SettingTimeObject;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class SettingTimeObjectRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'object' => 'required'
        ];
    }
}
