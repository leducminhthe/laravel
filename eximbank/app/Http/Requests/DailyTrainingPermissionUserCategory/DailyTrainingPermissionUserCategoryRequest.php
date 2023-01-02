<?php

namespace App\Http\Requests\DailyTrainingPermissionUserCategory;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class DailyTrainingPermissionUserCategoryRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'category_id' => 'required|exists:el_daily_training_category,id',
            'user_id' => 'required|exists:el_profile,user_id',
        ];
    }
}
