<?php

namespace App\Http\Requests\CareerRoadmapTitle;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class CareerRoadmapTitleRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'career_roadmap_id' => 'required|exists:career_roadmap,id',
            'title_id' => 'required|exists:el_titles,id',
            'level' => 'required|min:0',
            'seniority' => 'required|min:0',
        ];
    }
}
