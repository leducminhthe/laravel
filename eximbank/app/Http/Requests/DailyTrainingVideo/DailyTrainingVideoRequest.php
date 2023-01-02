<?php

namespace App\Http\Requests\DailyTrainingVideo;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class DailyTrainingVideoRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'name' => 'required',
            'video' => 'required',
            'avatar' => 'required',
            'hashtag' => 'required',
            'category_id' => 'required|exists:el_daily_training_category,id',
            'view' => 'required|integer|min:0',
            'status' => 'required|in:0,1',
            'approve' => 'required|in:0,1,2',
        ];
    }
}
