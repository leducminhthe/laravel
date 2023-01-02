<?php

namespace App\Http\Requests\ForumComment;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class ForumCommentRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'comment' => 'required',
            'thread_id' => 'required|exists:el_forum_thread,id',
            'created_by' => 'required|exists:el_profile,user_id',
        ];
    }
}
