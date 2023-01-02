<?php

namespace App\Http\Requests\MailTemplate;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class MailTemplateRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'code' => 'required|unique:el_mail_template,code,',
            'name' => 'required|max:255',
            'title' => 'required|max:255',
            'content' => 'required',
            'status' => 'required|in:0,1',
        ];
    }

    public function updateRules(): array
    {
        $mail_template = \request()->route('mail_template');
        return [
            'code' => 'required|unique:el_mail_template,code,'.$mail_template,
            'name' => 'required|max:255',
            'title' => 'required|max:255',
            'content' => 'required',
            'status' => 'required|in:0,1',
        ];
    }
}
