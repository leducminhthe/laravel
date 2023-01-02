<?php

namespace App\Http\Requests\UserContactOutside;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class UserContactOutsideRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'title' => 'required',
            'content' => 'required',
        ];
    }
}
