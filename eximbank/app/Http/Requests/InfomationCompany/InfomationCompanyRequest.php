<?php

namespace App\Http\Requests\InfomationCompany;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class InfomationCompanyRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'title' => 'required',
            'content' => 'required',
        ];
    }
}
