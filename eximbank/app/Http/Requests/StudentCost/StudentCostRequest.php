<?php

namespace App\Http\Requests\StudentCost;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class StudentCostRequest extends Request
{
    public function storeRules() : array
    {
        return [
            'name' => 'required',
            'status' => 'required|in:0,1',
        ];
    }

    public function updateRules() : array
    {
        return [
            'name' => 'required',
            'status' => 'required|in:0,1',
        ];
    }
}
