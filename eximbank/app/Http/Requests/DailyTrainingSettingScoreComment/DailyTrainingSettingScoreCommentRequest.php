<?php

namespace App\Http\Requests\DailyTrainingSettingScoreComment;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class DailyTrainingSettingScoreCommentRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'from' => 'required|min:1',
            'score' => 'required|min:0',
        ];
    }
}
