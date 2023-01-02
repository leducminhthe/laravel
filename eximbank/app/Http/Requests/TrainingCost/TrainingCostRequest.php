<?php

namespace App\Http\Requests\TrainingCost;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class TrainingCostRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'name' => 'required',
            'type' => 'required|integer',
        ];
    }

    public function updateRules(): array
    {
        return [
            'name' => 'required',
            'type' => 'required|integer',
        ];
    }
}
