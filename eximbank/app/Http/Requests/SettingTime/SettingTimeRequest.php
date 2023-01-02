<?php

namespace App\Http\Requests\SettingTime;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class SettingTimeRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'start_time' => 'required',
            'end_time' => 'required',
            'session' => 'required|in:morning,noon,afternoon',
            'object' => 'required',
            'value' => 'required',
        ];
    }
}
