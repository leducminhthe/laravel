<?php

namespace App\Http\Requests\Feedback;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class FeedbackRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'name' => 'required',
            'content' => 'required',
            'position' => 'required',
            'star' => 'required',
            'image' => 'required',
            'created_by' => 'required',
            'updated_by' => 'required',
        ];
    }
}
