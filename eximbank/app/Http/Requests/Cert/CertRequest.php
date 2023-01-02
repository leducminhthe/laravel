<?php

namespace App\Http\Requests\Cert;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class CertRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'certificate_code' => 'required|unique:el_cert,certificate_code,',
            'certificate_name' => 'required',
        ];
    }

    public function updateRules(): array
    {
        $cert = \request()->route('cert');
        return [
            'certificate_code' => 'required|unique:el_cert,certificate_code,'.$cert,
            'certificate_name' => 'required',
        ];
    }
}
