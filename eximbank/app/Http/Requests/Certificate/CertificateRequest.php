<?php

namespace App\Http\Requests\Certificate;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class CertificateRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'code' => 'required|unique:el_certificate,code',
            'name' => 'required',
            'image' => 'required|string',
        ];
    }

    public function updateRules(): array
    {
        $certificate = \request()->route('certificate');
        return [
            'code' => 'required|unique:el_certificate,code,'.$certificate,
            'name' => 'required',
            'image' => 'required|string',
        ];
    }
}
