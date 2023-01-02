<?php

namespace App\Http\Requests\CommitMonth;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class CommitMonthRequest extends Request
{
    public function commonRules() : array
    {
        return [
            'min_cost' => 'required',
            'max_cost' => 'required',
            'month' => 'required',
        ];
    }
}
