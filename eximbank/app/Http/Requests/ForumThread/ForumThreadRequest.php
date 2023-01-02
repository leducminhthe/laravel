<?php

namespace App\Http\Requests\ForumThread;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class ForumThreadRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'title' => 'required',
            'content' => 'required',
            'forum_id' => 'required|exists:el_forum,id',
            'main_article' => 'nullable|integer',
            'status' => 'required|integer|in:0,1',
            'views' => 'required|integer|min:0',
            'total_comment' => 'required|integer|min:0',
            'created_by' => 'required|integer|exists:el_profile,user_id',
            'updated_by' => 'required|integer|exists:el_profile,user_id',
        ];
    }
}
