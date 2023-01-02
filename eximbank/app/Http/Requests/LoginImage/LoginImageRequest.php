<?php

namespace App\Http\Requests\LoginImage;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class LoginImageRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'image' => 'required|string',
            'type' => 'required|integer|in:1,2',
            'status' => 'required|integer|in:0,1',
            'created_by' => 'required|integer',
            'updated_by' => 'required|integer',
        ];
    }
}
