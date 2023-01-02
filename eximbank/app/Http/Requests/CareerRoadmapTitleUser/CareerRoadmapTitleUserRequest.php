<?php

namespace App\Http\Requests\CareerRoadmapTitleUser;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class CareerRoadmapTitleUserRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'career_roadmap_user_id' => 'required|exists:career_roadmap_user,id',
            'title_id' => 'required|exists:el_titles,id',
            'level' => 'required|min:0',
            'seniority' => 'required|min:0',
        ];
    }
}
