<?php

namespace App\Http\Requests\ForumUserLikeComment;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class ForumUserLikeCommentRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'thread_id' => 'required|exists:el_forum_thread,id',
            'user_id' => 'required|exists:el_profile,user_id',
            'comment_id' => 'required|exists:el_forum_comment,id',
            'like' => 'nullable|integer',
            'dislike' => 'nullable|integer',
        ];
    }
}
