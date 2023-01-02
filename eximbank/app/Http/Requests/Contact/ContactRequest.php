<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class ContactRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'name' => 'required',
            'description' => 'required',
        ];
    }
}
