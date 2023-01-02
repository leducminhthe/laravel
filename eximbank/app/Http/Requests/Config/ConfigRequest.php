<?php

namespace App\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class ConfigRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'name' => 'required|unique:el_config,name,',
            'value' => 'required',
        ];
    }

    public function updateRules(): array
    {
        $config = \request()->route('con_fig');
        return [
            'name' => 'required|unique:el_config,name,'.$config,
            'value' => 'required',
        ];
    }
}
