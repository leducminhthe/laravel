<?php

namespace App\Http\Requests\FAQ;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class FAQRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'name' => 'required',
            'content' => 'required',
        ];
    }
}
