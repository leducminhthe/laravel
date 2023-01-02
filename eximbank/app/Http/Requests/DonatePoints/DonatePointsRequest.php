<?php

namespace App\Http\Requests\DonatePoints;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class DonatePointsRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'user_id' => 'required|exists:el_profile,user_id',
            'score' => 'required',
            'note' => 'required',
        ];
    }
}
