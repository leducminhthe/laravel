<?php

namespace App\Http\Requests\Guide;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class GuideRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'name' => 'required_if:id,',
            'type' => 'required',
            'attach' => 'required',
        ];
    }
}
