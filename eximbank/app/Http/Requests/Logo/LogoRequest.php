<?php

namespace App\Http\Requests\Logo;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class LogoRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'image' => 'required_if:id,',
            'status' => 'required|in:0,1',
            'type' => 'required|in:1,2',
        ];
    }
}
