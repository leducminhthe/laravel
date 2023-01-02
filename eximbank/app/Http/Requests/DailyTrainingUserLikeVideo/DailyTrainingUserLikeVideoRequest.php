<?php

namespace App\Http\Requests\DailyTrainingUserLikeVideo;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class DailyTrainingUserLikeVideoRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'video_id' => 'required|exists:el_daily_training_video,id',
            'user_id' => 'required|exists:el_profile,user_id',
            'like' => 'nullable|integer',
            'dislike' => 'nullable|integer',
        ];
    }
}
