<?php

namespace App\Http\Requests\CommitGroup;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class CommitGroupRequest extends Request
{
    public function commonRules() : array
    {
        return [
            'group' => 'required'
        ];
    }
}
