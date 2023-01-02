<?php

namespace App\Http\Requests\Footer;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class FooterRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'name' => 'required',
            'status' => 'required|in:0,1',
            'email' => 'nullable|email',
        ];
    }
}
