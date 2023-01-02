<?php

namespace App\Http\Requests\MailSignature;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class MailSignatureRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'unit_id' => 'required|exists:el_unit,id|unique:el_mail_signature,unit_id,',
            'content' => 'required',
            'created_by' => 'required',
            'updated_by' => 'required',
        ];
    }

    public function updateRules(): array
    {
        $mail_signature = \request()->route('mail_signature');
        return [
            'unit_id' => 'required|exists:el_unit,id|unique:el_mail_signature,unit_id,'. $mail_signature,
            'content' => 'required',
            'created_by' => 'required',
            'updated_by' => 'required',
        ];
    }
}
