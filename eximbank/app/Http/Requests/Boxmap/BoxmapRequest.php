<?php

namespace App\Http\Requests\Boxmap;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class BoxmapRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ];
    }
}
