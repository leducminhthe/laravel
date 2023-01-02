<?php

namespace App\Http\Requests\PermissionGroup;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class PermissionGroupRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'name' => 'required|max:150',
            'created_by' => 'required',
            'updated_by' => 'required',
        ];
    }
}
