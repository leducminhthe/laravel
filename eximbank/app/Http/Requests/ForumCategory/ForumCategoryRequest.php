<?php

namespace App\Http\Requests\ForumCategory;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class ForumCategoryRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'icon' => 'required',
            'status'=> 'required|in:0,1',
            'name' => 'required',
        ];
    }
}
