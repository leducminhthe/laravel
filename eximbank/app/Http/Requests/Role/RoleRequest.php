<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class RoleRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'code' => 'required_without:id|max:255|unique:el_roles,code,',
            'name' => 'required_without:id|max:255|unique:el_roles,name,',
            'type' => 'required|in:1,2',
            'guard_name' => 'required',
            'description' => 'required|max:255',
            'created_by' => 'required',
            'updated_by' => 'required',
        ];
    }

    public function updateRules(): array
    {
        $role = \request()->route('role');
        return [
            'code' => 'required_without:id|max:255|unique:el_roles,code,'.$role,
            'name' => 'required_without:id|max:255|unique:el_roles,name,'.$role,
            'type' => 'required|in:1,2',
            'guard_name' => 'required',
            'description' => 'required|max:255',
            'created_by' => 'required',
            'updated_by' => 'required',
        ];
    }
}
