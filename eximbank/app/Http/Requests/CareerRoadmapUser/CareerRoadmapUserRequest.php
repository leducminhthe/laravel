<?php

namespace App\Http\Requests\CareerRoadmapUser;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class CareerRoadmapUserRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'name' => 'required|max:500',
            'primary' => 'required|in:0,1',
            'title_id' => 'required|exists:el_titles,id',
            'user_id' => 'required|exists:el_profile,user_id',
        ];
    }
}
