<?php

namespace App\Http\Requests\DailyTrainingUserCommentVideo;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class DailyTrainingUserCommentVideoRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'video_id' => 'required|exists:el_daily_training_video,id',
            'user_id' => 'required|exists:el_profile,user_id',
            'content' => 'required',
            'failed' => 'required|in:0,1',
        ];
    }
}
