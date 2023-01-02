<?php

namespace App\Http\Requests\PermissionType;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class PermissionTypeRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'name' => 'required|string|unique:el_permission_type,name,',
            'type' => 'required|in:1,2',
        ];
    }

    public function updateRules(): array
    {
        $permission_type = \request()->route('permission_type');
        return [
            'name' => 'required|string|unique:el_permission_type,name,'.$permission_type,
            'type' => 'required|in:1,2',
        ];
    }
}
