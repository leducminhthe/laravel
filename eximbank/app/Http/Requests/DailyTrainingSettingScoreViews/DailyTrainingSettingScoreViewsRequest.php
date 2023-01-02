<?php

namespace App\Http\Requests\DailyTrainingSettingScoreViews;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class DailyTrainingSettingScoreViewsRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'from' => 'required|min:1',
            'score' => 'required|min:0',
        ];
    }
}
