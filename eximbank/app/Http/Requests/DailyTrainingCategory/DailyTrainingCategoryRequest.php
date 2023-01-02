<?php

namespace App\Http\Requests\DailyTrainingCategory;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class DailyTrainingCategoryRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'name' => 'required',
            'status_video' => 'required|in:0,1',
        ];
    }
}
