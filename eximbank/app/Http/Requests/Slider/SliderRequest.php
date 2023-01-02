<?php

namespace App\Http\Requests\Slider;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class SliderRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'image' => 'required_if:id,',
            'display_order' => 'required|integer',
            'location' => 'required',
            'status' => 'required|in:0,1',
            'type' => 'required|in:1,2',
        ];
    }
}
