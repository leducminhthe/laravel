<?php

namespace App\Http\Requests\DailyTrainingUserViewVideo;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class DailyTrainingUserViewVideoRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'video_id' => 'required|exists:el_daily_training_video,id',
            'user_id' => 'required|exists:el_profile,user_id',
            'device' => 'required',
            'time_view' => 'required',
        ];
    }
}
