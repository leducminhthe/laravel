<?php

namespace App\Http\Requests\Forum;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class ForumRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'icon' => 'required|string',
            'status'=> 'required|integer|in:0,1',
            'name' => 'required|string',
            'category_id' => 'required|exists:el_forum_category,id',
            'num_topic' => 'required|integer|min:0',
            'num_comment' => 'required|integer|min:0',
            'created_by' => 'required|integer',
            'updated_by' => 'required|integer',
        ];
    }
}
