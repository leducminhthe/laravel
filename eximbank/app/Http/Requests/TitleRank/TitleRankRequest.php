<?php

namespace App\Http\Requests\TitleRank;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class TitleRankRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'code' => 'required|unique:el_title_rank,code,',
            'name' => 'required',
            'status' => 'required|in:0,1',
        ];
    }

    public function updateRules(): array
    {
        $title_rank = \request()->route('title_rank');
        return [
            'code' => 'required|unique:el_title_rank,code,'. $title_rank,
            'name' => 'required',
            'status' => 'required|in:0,1',
        ];
    }
}
