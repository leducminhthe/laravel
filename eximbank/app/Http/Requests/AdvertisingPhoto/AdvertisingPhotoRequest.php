<?php

namespace App\Http\Requests\AdvertisingPhoto;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class AdvertisingPhotoRequest extends Request
{
    public function commonRules() : array
    {
        return [
            'image' => 'required_if:id,',
            'status' => 'required|in:0,1',
            'type' => 'required|in:0,1',
        ];
    }
}
