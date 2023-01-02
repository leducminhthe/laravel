<?php

namespace App\Http\Requests\AppMobile;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class AppMobileRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'image' => 'required',
            'link' => 'required',
            'type' => 'required|in:1,2',
            'created_by' => 'required_if:id,',
            'updated_by' => 'required',
        ];
    }
}
