<?php

namespace App\Http\Requests\LevelSubject;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class LevelSubjectRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'code' => 'required|unique:el_level_subject,code,',
            'name' => 'required',
            'status' => 'required|in:0,1',
        ];
    }

    public function updateRules(): array
    {
        $level_subject = \request()->route('level_subject');
        return [
            'code' => 'required|unique:el_level_subject,code,'. $level_subject,
            'name' => 'required',
            'status' => 'required|in:0,1',
        ];
    }
}
