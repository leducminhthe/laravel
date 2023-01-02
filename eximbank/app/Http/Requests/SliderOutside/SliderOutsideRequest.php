<?php

namespace App\Http\Requests\SliderOutside;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class SliderOutsideRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'image' => 'required_if:id,',
            'status' => 'required|in:0,1',
        ];
    }
}
